@extends('layout')

@section('content')
    <h1>Edit Post</h1>

    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="published_at" class="form-label">Published Date</label>
            <input type="date" class="form-control" id="published_at" name="published_at" value="{{ $post->published_at ? $post->published_at->format('Y-m-d') : '' }}">
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Images (Upload to append/replace)</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple>

            @if($post->images)
                <div class="mt-2">
                    <small>Current Images:</small>
                    <div class="d-flex gap-2">
                         @foreach($post->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" width="50" class="rounded">
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5">{{ $post->content }}</textarea>
        </div>

    <script>
        flatpickr("#published_at", {
            enableTime: false,
            dateFormat: "Y-m-d",
        });
    </script>
    
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
    const easyMDE = new EasyMDE({element: document.getElementById('content')});
</script>
@endpush

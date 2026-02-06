@extends('layout')

@section('content')
    <h1>Create Post</h1>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="" selected disabled>Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @if($categories->isEmpty())
                <div class="form-text text-danger">No categories found. <a href="{{ route('categories.create') }}">Create one first.</a></div>
            @endif
        </div>
        <div class="mb-3">
            <label for="published_at" class="form-label">Published Date</label>
            <input type="date" class="form-control" id="published_at" name="published_at">
        </div>
        <div class="mb-3">
            <label for="images" class="form-label">Images</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>
        <script>
            flatpickr("#published_at", {
                enableTime: false,
                dateFormat: "Y-m-d",
            });
        </script>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection

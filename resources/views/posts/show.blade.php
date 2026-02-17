@extends('layout')

@section('content')
    <div class="mb-3">
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">&larr; Back to Posts</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="card-title">{{ $post->title }}</h1>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Category: {{ $post->category->name }}
                        @if($post->published_at)
                            <span class="ms-3">Published: {{ $post->published_at->format('F d, Y') }}</span>
                        @endif
                    </h6>
                    <div class="d-flex align-items-center mt-2">
                        <i class="bi bi-person-circle me-2 text-primary fs-5"></i>
                        <div>
                            <span class="fw-semibold">{{ $post->user->name }}</span>
                            <small class="text-muted ms-2">(User ID: {{ $post->user_id }})</small>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    @endcan
                    @can('delete', $post)
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            @if($post->images)
                <div class="mb-4">
                    <h5>Images</h5>
                    <div class="row">
                        @foreach($post->images as $image)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Post Image">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card-text">
                {!! \Illuminate\Support\Str::markdown($post->content) !!}
            </div>
        </div>
    </div>
@endsection

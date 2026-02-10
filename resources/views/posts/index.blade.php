@extends('layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="fw-bold h2 mb-0">Manage Posts</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg shadow-sm rounded-3">
            <i class="bi bi-plus-lg me-1"></i> Create New Post
        </a>
    </div>

    <div class="premium-card p-4">
        <div class="table-responsive">

        <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Content</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->category->name }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($post->content, 50) }}</td>
                    <td>    
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </div>
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
@endsection


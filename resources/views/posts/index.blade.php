@extends('layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="fw-bold h2 mb-0">
            @if(request()->routeIs('posts.liked'))
                Liked Posts
            @else
                All Posts
            @endif
        </h1>
        @can('create', App\Models\Post::class)
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg shadow-sm rounded-3">
                <i class="bi bi-plus-lg me-1"></i> Create New Post
            </a>
        @endcan
    </div>

    <!-- Top Categories Section -->
    @if($topCategories->isNotEmpty())
        <div class="mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Trending Categories</h5>
            <div class="d-flex gap-2 flex-wrap">
                @foreach($topCategories as $category)
                    <a href="{{ route('posts.index', ['category_id' => $category->id]) }}" class="btn btn-light border shadow-sm rounded-pill d-flex align-items-center px-3">
                        <span class="fw-medium">{{ $category->name }}</span>
                        <span class="badge bg-primary rounded-pill ms-2">{{ $category->posts_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="premium-card p-4 mb-4">
        <form action="{{ route('posts.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search posts..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>

    <div class="premium-card p-4">
        <div class="table-responsive">

        <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Created By</th>
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
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle me-2 text-primary"></i>
                            <div>
                                <div class="fw-semibold">{{ $post->user->name }}</div>
                                <small class="text-muted">ID: {{ $post->user_id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit(strip_tags(\Illuminate\Support\Str::markdown($post->content)), 50) }}</td>
                    <td>    
                        <div class="d-flex gap-1">
                        <form action="{{ route('posts.like', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ auth()->user()->likes->contains($post) ? 'btn-danger' : 'btn-outline-danger' }}">
                                <i class="bi {{ auth()->user()->likes->contains($post) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-info">View</a>
                        
                        @can('update', $post)
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning">Edit</a>
                        @endcan
                        
                        @can('delete', $post)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @endcan
                        </div>
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


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

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($posts as $post)
            <div class="col">
                <article class="premium-card h-100 hover-lift overflow-hidden d-flex flex-column">
                    @if($post->images && count($post->images) > 0)
                        <img src="{{ asset('storage/' . $post->images[0]) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            @foreach($post->categories as $category)
                                <span class="badge badge-category small">{{ $category->name }}</span>
                            @endforeach
                        </div>

                        <h3 class="h5 fw-bold mb-3">
                            <a href="{{ route('posts.show', $post) }}" class="text-dark text-decoration-none hover-primary">{{ $post->title }}</a>
                        </h3>

                        <p class="text-muted small mb-4 flex-grow-1">
                            {{ \Illuminate\Support\Str::limit(strip_tags(\Illuminate\Support\Str::markdown($post->content)), 120) }}
                        </p>

                        <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle text-primary me-2"></i>
                                <span class="small fw-medium">{{ $post->user->name }}</span>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ auth()->user()->likes->contains($post) ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill border-0">
                                        <i class="bi {{ auth()->user()->likes->contains($post) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    </button>
                                </form>

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-pill border-0" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item" href="{{ route('posts.show', $post) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                        @can('update', $post)
                                            <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        @endcan
                                        @can('delete', $post)
                                            <li>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="bi bi-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
@endsection


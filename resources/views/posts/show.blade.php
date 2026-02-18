@extends('layout')

@section('content')
    <div class="article-container py-4">
        <nav aria-label="breadcrumb" class="mb-5">
            <a href="{{ route('posts.index') }}" class="btn btn-link text-decoration-none text-muted p-0">
                <i class="bi bi-arrow-left me-1"></i> Back to Feed
            </a>
        </nav>

        <header class="mb-5">
            <div class="d-flex gap-2 mb-3 flex-wrap">
                @foreach($post->categories as $category)
                    <span class="badge badge-category">{{ $category->name }}</span>
                @endforeach
            </div>

            <h1 class="display-4 fw-bold mb-4">{{ $post->title }}</h1>

            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                        <i class="bi bi-person text-primary fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold">{{ $post->user->name }}</div>
                        <div class="text-muted small">
                            @if($post->published_at)
                                Published on {{ $post->published_at->format('M j, Y') }}
                            @else
                                Created on {{ $post->created_at->format('M j, Y') }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-light rounded-pill px-3 shadow-sm">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                    @endcan
                    @can('delete', $post)
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure?')">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </header>

        @if($post->images && count($post->images) > 0)
            <div class="mb-5">
                <div class="row g-3">
                    @foreach($post->images as $image)
                        <div class="col-12 col-md-{{ count($post->images) == 1 ? '12' : '6' }}">
                            <div class="premium-card overflow-hidden shadow-sm h-100">
                                <img src="{{ asset('storage/' . $image) }}" class="img-fluid w-100" style="object-fit: cover; max-height: 500px;" alt="{{ $post->title }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <article class="post-content glass-card p-4 p-md-5 premium-card shadow-sm border-0 mb-5">
            {!! \Illuminate\Support\Str::markdown($post->content) !!}
        </article>

        <footer class="border-top pt-5 mb-5">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <form action="{{ route('posts.like', $post) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-lg {{ auth()->user()->likes->contains($post) ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-4 shadow-sm">
                            <i class="bi {{ auth()->user()->likes->contains($post) ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                            {{ auth()->user()->likes->contains($post) ? 'Liked' : 'Like' }}
                        </button>
                    </form>
                </div>
                
                <div class="share-links">
                    <span class="text-muted small me-2">Share:</span>
                    <button class="btn btn-light btn-sm rounded-circle"><i class="bi bi-twitter-x"></i></button>
                    <button class="btn btn-light btn-sm rounded-circle ms-1"><i class="bi bi-facebook"></i></button>
                    <button class="btn btn-light btn-sm rounded-circle ms-1"><i class="bi bi-link-45deg"></i></button>
                </div>
            </div>
        </footer>
    </div>
@endsection

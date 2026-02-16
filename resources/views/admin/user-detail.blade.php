@extends('layout')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2>{{ $user->name }}</h2>
            <p class="text-muted">{{ $user->email }}</p>
            <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-secondary' }}">
                {{ ucfirst($user->role) }}
            </span>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Posts ({{ $user->posts->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($user->posts as $post)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6>{{ $post->title }}</h6>
                            <small class="text-muted">
                                {{ $post->published_at?->format('M d, Y') ?? 'Not published' }}
                            </small>
                        </div>
                    @empty
                        <p class="text-muted">No posts yet</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Categories ({{ $user->categories->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($user->categories as $category)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6>{{ $category->name }}</h6>
                            <small class="text-muted">
                                {{ $category->description ?? 'No description' }}
                            </small>
                        </div>
                    @empty
                        <p class="text-muted">No categories yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

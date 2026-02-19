@extends('layout')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Admin Dashboard</h1>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people fs-1 text-primary mb-3"></i>
                        <h5 class="card-title">Total Users</h5>
                        <p class="display-4 fw-bold">{{ $stats['total_users'] }}</p>
                        <a href="{{ route('admin.users') }}" class="btn btn-primary">View All Users</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-text fs-1 text-success mb-3"></i>
                        <h5 class="card-title">Total Posts</h5>
                        <p class="display-4 fw-bold">{{ $stats['total_posts'] }}</p>
                        <a href="{{ route('admin.posts') }}" class="btn btn-success">View All Posts</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-tags fs-1 text-warning mb-3"></i>
                        <h5 class="card-title">Total Categories</h5>
                        <p class="display-4 fw-bold">{{ $stats['total_categories'] }}</p>
                        <a href="{{ route('admin.categories') }}" class="btn btn-warning">View All Categories</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-cpu fs-1 text-info mb-3"></i>
                        <h5 class="card-title text-muted">Background Jobs</h5>
                        <p class="display-5 fw-bold">{{ $stats['pending_jobs'] }}</p>
                        <a href="{{ route('admin.jobs') }}" class="btn btn-outline-info rounded-pill">Monitor Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
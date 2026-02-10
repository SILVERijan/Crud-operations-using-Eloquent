@extends('layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="fw-bold h2 mb-0">Categories</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-lg shadow-sm rounded-3">
            <i class="bi bi-tag-fill me-1"></i> Create Category
        </a>
    </div>

    <div class="premium-card p-4">
        <div class="table-responsive">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>    
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
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
        {{ $categories->links() }}
    </div>
    
@endsection


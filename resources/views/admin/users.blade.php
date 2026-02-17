@extends('layout')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">All Users</h1>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Posts</th>
                        <th>Categories</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->roles->isNotEmpty())
                                    @foreach($user->roles as $role)
                                        @if($role->slug === 'admin')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-shield-lock"></i> Admin
                                            </span>
                                        @elseif($role->slug === 'customer')
                                            <span class="badge bg-primary">
                                                <i class="bi bi-person-fill"></i> Customer
                                            </span>
                                        @elseif($role->slug === 'reader')
                                            <span class="badge bg-info">
                                                <i class="bi bi-book"></i> Reader
                                            </span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">No Role</span>
                                @endif
                            </td>
                            <td>{{ $user->posts_count }}</td>
                            <td>{{ $user->categories_count }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

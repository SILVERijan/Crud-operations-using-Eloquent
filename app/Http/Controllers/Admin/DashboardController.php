<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Admin Dashboard - Overview
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'total_categories' => Category::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // View All Users
    public function users()
    {
        $users = User::withCount(['posts', 'categories'])->paginate(20);
        return view('admin.users', compact('users'));
    }

    // View All Posts (from all users)
    public function posts(Request $request)
    {
        $posts = Post::with(['user', 'category'])
            ->when($request->user_id, function($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
            ->latest()
            ->paginate(20);

        $users = User::all();
        return view('admin.posts', compact('posts', 'users'));
    }

    // View All Categories (from all users)
    public function categories(Request $request)
    {
        $categories = Category::with('user')
            ->withCount('posts')
            ->when($request->user_id, function($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
            ->latest()
            ->paginate(20);

        $users = User::all();
        return view('admin.categories', compact('categories', 'users'));
    }

    // View specific user's data
    public function userDetail(User $user)
    {
        $user->load(['posts', 'categories']);
        return view('admin.user-detail', compact('user'));
    }
}

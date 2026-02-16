<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Category;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();
        
        // This toggles the relationship: if exists, detach; if not, attach.
        $user->likes()->toggle($post->id);

        return back();
    }

    public function index()
    {
        $posts = auth()->user()->likes()->with('category')->paginate(10);
        $categories = Category::where('user_id', auth()->id())->get();
        $topCategories = Category::where('user_id', auth()->id())->topUsed(3)->get();
        
        return view('posts.index', compact('posts', 'categories', 'topCategories'));
    }
}

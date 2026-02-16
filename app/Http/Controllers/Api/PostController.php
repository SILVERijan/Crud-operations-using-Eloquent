<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $posts = Post::with('category')->where('user_id', auth()->id())->latest()->paginate();
        return PostResource::collection($posts);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'published_at' => 'nullable|date',
            'images' => 'nullable|array',
        ]);

        $data['user_id'] = auth()->id();
        $post = Post::create($data);

        return new PostResource($post->load('category'));
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return new PostResource($post->load('category'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'published_at' => 'nullable|date',
            'images' => 'nullable|array',
        ]);

        $post->update($data);

        return new PostResource($post->load('category'));
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return response()->json(null, 204);
    }

    public function userPosts(Request $request, \App\Models\User $user)
    {
        $posts = Post::where('user_id', $user->id)
            ->when($request->category_id, function($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })
            ->select('id', 'title', 'created_at', 'category_id', 'user_id')
            ->with(['category:id,name'])
            ->latest()
            ->paginate();

        return PostResource::collection($posts);
    }
}
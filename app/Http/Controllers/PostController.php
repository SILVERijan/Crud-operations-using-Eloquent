<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\StoreRequest;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Notifications\PostPublished;
use App\Jobs\ProcessPostImage;

class PostController extends Controller
{
    /**
     * Display a listing of all posts (public for all authenticated users).
     */
    public function index(Request $request)
    {
        // Show ALL posts (not filtered by user_id) - PUBLIC ACCESS
        $posts = Post::query()
            ->filter($request->only(['search', 'category_id']))
            ->with(['categories', 'user']) // Load categories relationship

            ->latest()
            ->paginate(10)
            ->appends($request->query());

        // Get all categories for filtering (only those created by the user, unless admin)
        $categories = auth()->user()->isAdmin() 
            ? Category::all() 
            : Category::where('user_id', auth()->id())->get();

        $topCategories = auth()->user()->isAdmin()
            ? Category::topUsed(3)->get()
            : Category::where('user_id', auth()->id())->topUsed(3)->get();


        return view('posts.index', compact('posts', 'categories', 'topCategories'));
    }

    /**
     * Display a listing of the authenticated user's posts.
     */
    public function myPosts(Request $request)
    {
        $posts = Post::query()
            ->where('user_id', auth()->id())
            ->filter($request->only(['search', 'category_id']))
            ->with(['categories', 'user'])
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $categories = Category::where('user_id', auth()->id())->get();
        $topCategories = Category::where('user_id', auth()->id())->topUsed(3)->get();

        return view('posts.index', compact('posts', 'categories', 'topCategories'));
    }

    /**
     * Show the form for creating a new post.
     * Only customers and admins can create posts.
     */
    public function create()
    {
        $this->authorize('create', Post::class);
        
        $categories = auth()->user()->isAdmin() 
            ? Category::all() 
            : Category::where('user_id', auth()->id())->get();

        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Post::class);
        
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('posts', 'public');
            }
            $data['images'] = $images;
        }

        $post = Post::create($data);
        $post->categories()->sync($data['category_id']);

        //sending queued notifcation to the post autheor
        $post->user->notify(new PostPublished($post));

        // dispatch the background job
        ProcessPostImage::dispatch($post);

    
        return redirect()
        ->route('posts.index')
        ->with('success', 'Post created (Email and Processing queued!)');
    }

    /**
     * Display the specified post.
     * All authenticated users can view any post.
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post);
        
        // Load user relationship to show creator
        $post->load('user', 'categories');

        
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     * Only the owner or admin can edit.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        
        $categories = auth()->user()->isAdmin() 
            ? Category::all() 
            : Category::where('user_id', auth()->id())->get();

        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post in storage.
     * Only the owner or admin can update.
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $data = $request->validated();

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('posts', 'public');
            }
            $data['images'] = $images;
        }

        $post->update($data);
        $post->categories()->sync($data['category_id']);


        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified post from storage.
     * Only the owner or admin can delete.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully');
    }
}

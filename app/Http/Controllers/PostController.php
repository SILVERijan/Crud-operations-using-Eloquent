<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\StoreRequest;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //index of the post where there is pagination, viewing of the post and all.
    public function index(Request $request)
    {
        $posts = Post::where('user_id', auth()->id())
            ->filter($request->only(['search', 'category_id']))
            ->with('category')
            ->oldest()
            ->paginate(5)
            ->appends($request->query());

        $categories = Category::where('user_id', auth()->id())->get();
        $topCategories = Category::where('user_id', auth()->id())->topUsed(3)->get();

        return view('posts.index', compact('posts', 'categories', 'topCategories'));
    }

    //creation of the post 
    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get();
        return view('posts.create', compact('categories'));
    }

    //storing values in the post
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('posts', 'public');
            }
            $data['images'] = $images;
        }

        Post::create($data);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    //view section of the post
    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('posts.show', compact('post'));
    }

    //editing of the post
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $categories = Category::where('user_id', auth()->id())->get();
        return view('posts.edit', compact('post', 'categories'));
    }

    //updating post 
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

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully');
    }

    //deletion of post
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully');
    }
}

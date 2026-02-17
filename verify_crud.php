<?php

use App\Models\Category;
use App\Models\Post;

// Create Category
$category = Category::create([
    'name' => 'Tech',
    'description' => 'Technology news'
]);
echo "Category created: " . $category->name . "\n";

// Create Post
$post = Post::create([
    'title' => 'Laravel is awesome',
    'content' => 'Eloquent makes DB interactions easy.',
    'category_id' => $category->id
]);
echo "Post created: " . $post->title . "\n";

// Read
$fetchedPost = Post::with('categories')->find($post->id);

echo "Fetched Post Categories: " . $fetchedPost->categories->pluck('name')->implode(', ') . "\n";


// Update
$category->update(['name' => 'Technology']);
echo "Category updated to: " . $category->name . "\n";

// Delete
$post->delete();
$category->delete();
echo "Deleted post and category.\n";

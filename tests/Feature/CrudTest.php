<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Post;

class CrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_crud_operations()
    {
        // Create Category
        $category = Category::create([
            'name' => 'Tech',
            'description' => 'Technology news'
        ]);
        $this->assertDatabaseHas('categories', ['name' => 'Tech']);

        // Create Post
        $post = Post::create([
            'title' => 'Laravel is awesome',
            'content' => 'Eloquent makes DB interactions easy.',
            'category_id' => $category->id
        ]);
        $this->assertDatabaseHas('posts', ['title' => 'Laravel is awesome']);

        // Relationships
        $this->assertEquals('Tech', $post->category->name);
        $this->assertTrue($category->posts->contains($post));

        // Update
        $category->update(['name' => 'Technology']);
        $this->assertDatabaseHas('categories', ['name' => 'Technology']);

        // Delete
        $post->delete();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}

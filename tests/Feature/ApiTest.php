<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_can_list_posts()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $category = Category::create(['name' => 'Test Category', 'user_id' => $user->id]);
        Post::create([
            'title' => 'Test Post',
            'content' => 'Content',
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'category']
                ]
            ]);
    }

    public function test_api_can_list_categories()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // parent::setUp(); removed
        Category::create(['name' => 'Test Category', 'user_id' => $user->id]);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name']
                ]
            ]);
    }
}

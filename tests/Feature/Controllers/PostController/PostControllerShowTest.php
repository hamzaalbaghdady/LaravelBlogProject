<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Post;

class PostControllerShowTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authentacated_user_can_see_post(): void
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->creator);
        $route = route('posts.show', $post);
        $response = $this->getJson($route);
        $response->assertOk()
            ->assertJson([
                'data' => [
                    "id" => $post->id,
                    "creator_id" => $post->creator->id,
                    "creator_name" => $post->creator->name,
                    "content" => $post->content,
                    "created_at" => $post->created_at->jsonSerialize(),
                    "updated_at" => $post->updated_at->jsonSerialize(),
                ],
            ]);
    }

    public function test_unauthentacated_user_can_not_see_post(): void
    {
        $post = Post::factory()->create();
        $route = route('posts.show', $post);
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }
}

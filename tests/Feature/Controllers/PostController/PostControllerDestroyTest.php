<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class PostControllerDestroyTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_post_creator_can_delete(): void
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->creator);

        $route = route('posts.destroy', $post);
        $response = $this->deleteJson($route);
        $response->assertNoContent();
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'creator_id' => $post->creator_id,
            'content' => $post->content
        ]);
    }
    public function test_unauthorized_user_can_delete(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $route = route('posts.destroy', $post);
        $response = $this->deleteJson($route);
        $response->assertForbidden();
    }
    public function test_unauthentecated_user_can_delete(): void
    {
        $post = Post::factory()->create();

        $route = route('posts.destroy', $post);
        $response = $this->deleteJson($route);
        $response->assertUnauthorized();
    }
}

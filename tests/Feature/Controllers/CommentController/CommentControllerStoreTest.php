<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class CommentControllerStoreTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authentecated_user_can_make_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $route = route('posts.comments.store', $post);
        $response = $this->postJson($route, [
            'content' => 'This is a new comment',
        ]);
        $response->assertCreated();
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a new comment',
            'creator_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
    public function test_unauthentecated_user_can_not_make_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $route = route('posts.comments.store', $post);
        $response = $this->postJson($route, [
            'content' => 'This post is all about content',
        ]);
        $response->assertUnauthorized();
    }
    public function test_content_is_required()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $route = route('posts.comments.store', $post);
        $response = $this->postJson($route, []);
        $response->assertJsonValidationErrors([
            'content' => 'required',
        ]);
    }
}

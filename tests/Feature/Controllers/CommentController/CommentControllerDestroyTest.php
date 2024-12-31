<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Comment;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class CommentControllerDestroyTest extends TestCase
{

    public function test_comment_creator_can_delete_comment(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        Sanctum::actingAs($comment->creator);

        $route = route('posts.comments.destroy', [$post, $comment]);
        $response = $this->deleteJson($route);
        $response->assertNoContent();
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
            'creator_id' => $comment->creator_id,
            'content' => $comment->content
        ]);
    }
    public function test_post_creator_can_delete_any_comment(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        Sanctum::actingAs($post->creator);

        $route = route('posts.comments.destroy', [$post, $comment]);
        $response = $this->deleteJson($route);
        $response->assertNoContent();
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
            'creator_id' => $comment->creator_id,
            'content' => $comment->content
        ]);
    }
    public function test_unauthorized_user_can_not_delete_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        Sanctum::actingAs($user);

        $route = route('posts.comments.destroy', [$post, $comment]);
        $response = $this->deleteJson($route);
        $response->assertForbidden();
    }
    public function test_unauthentecated_user_can_not_delete_comment(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();

        $route = route('posts.comments.destroy', [$post, $comment]);
        $response = $this->deleteJson($route);
        $response->assertUnauthorized();
    }
}

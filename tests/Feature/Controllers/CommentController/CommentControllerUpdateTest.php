<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Comment;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class CommentControllerUpdateTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_comment_creator_can_update()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();

        Sanctum::actingAs($comment->creator);
        $route = route('posts.comments.update', [$post, $comment]);
        $response = $this->putJson($route, [
            'content' => 'Update test',
        ]);
        $response->assertOk();
        $this->assertEquals("Update test", $comment->refresh()->content);
    }

    public function test_user_can_not_update_others_comment()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        $comment = Comment::factory()->for($post)->create();

        Sanctum::actingAs($user);
        $route = route('posts.comments.update', [$post, $comment]);
        $response = $this->putJson($route, [
            'content' => 'Update test',
        ]);
        $response->assertForbidden();
    }

    public function test_unauthenticated_user_can_not_update()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();


        $route = route('posts.comments.update', [$comment, $post]);
        $response = $this->putJson($route, [
            'content' => 'Update test',
        ]);
        $response->assertUnauthorized();
    }

}

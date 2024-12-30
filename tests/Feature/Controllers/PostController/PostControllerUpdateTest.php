<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class PostControllerUpdateTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_post_creator_can_update_his_post()
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->creator);
        $route = route('posts.update', $post);
        $response = $this->putJson($route, [
            'content' => 'Update test',
        ]);
        $response->assertOk();
        $this->assertEquals("Update test", $post->refresh()->content);
    }

    public function test_user_can_not_update_others_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);
        $route = route('posts.update', $post);
        $response = $this->putJson($route, [
            'content' => 'Update test',
        ]);
        $response->assertForbidden();
    }

    public function test_unauthenticated_user_can_not_update_post()
    {
        $post = Post::factory()->create();
        $route = route('posts.update', $post);
        $response = $this->putJson($route, [
            'content' => 'Update test',
        ]);
        $response->assertUnauthorized();
    }

}

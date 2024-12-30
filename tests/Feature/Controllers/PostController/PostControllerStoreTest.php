<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class PostControllerStoreTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authentecated_user_can_create_posts(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = route('posts.store');
        $response = $this->postJson($route, [
            'content' => 'This post is all about content',
        ]);
        $response->assertCreated();
        $this->assertDatabaseHas('posts', [
            'content' => 'This post is all about content',
            'creator_id' => $user->id,
        ]);
    }
    public function test_unauthentecated_user_can_not_create_posts(): void
    {
        $user = User::factory()->create();
        $route = route('posts.store');
        $response = $this->postJson($route, [
            'content' => 'This post is all about content',
        ]);
        $response->assertUnauthorized();
    }
    public function test_content_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = route('posts.store');
        $response = $this->postJson($route, []);
        $response->assertJsonValidationErrors([
            'content' => 'required',
        ]);
    }
}

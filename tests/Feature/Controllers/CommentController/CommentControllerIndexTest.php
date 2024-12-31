<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Laravel\Sanctum\Sanctum;


class CommentControllerIndexTest extends TestCase
{

    public function test_authenticated_users_can_fetch_the_comment_list(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();

        Sanctum::actingAs($post->creator);
        $route = route('posts.comments.index', $post);
        $response = $this->getJson($route);
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [ // * to apply this structure for all items "posts".
                        'id',
                        'creator_id',
                        'creator_name',
                        'post_id',
                        'content',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    /**
     * Summary of test_sortable_fields
     * @dataProvider sortableFields
     */
    public function test_sortable_fields($field, $expected_code)
    {
        $post = Post::factory()->create();
        Sanctum::actingAs(User::factory()->create());
        $route = route('posts.comments.index', [
            $post,
            'sort' => $field,
        ]);
        $response = $this->getJson($route);
        $response->assertStatus($expected_code);
    }
    public function sortableFields()
    {
        return [  // this is like a test cases, each one will be tested seperatly
            // ['field_name', 'expected_code']
            ['id', 400],
            ['content', 200],
            ['creator_name', 400],
            ['created_at', 200],
            ['updated_at', 200],
        ];
    }


    public function test_unauthenticated_users_can_not_fetch_the_comment_list(): void
    {
        $post = Post::factory()->create();
        $route = route('posts.comments.index', $post);
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }
}

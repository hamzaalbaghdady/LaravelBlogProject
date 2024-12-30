<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class PostControllerIndexTest extends TestCase
{

    public function test_authenticated_users_can_fetch_the_post_list(): void
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->creator);
        $route = route('posts.index');
        $response = $this->getJson($route);
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [ // * to apply this structure for all items "posts".
                        'id',
                        'creator_id',
                        'creator_name',
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
        Sanctum::actingAs(User::factory()->create());
        $route = route('posts.index', [
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
    /**
     * Summary of test_filterable_fields
     * @dataProvider filterFields
     */
    public function test_filterable_fields($field, $value, $expected_code)
    {
        Sanctum::actingAs(User::factory()->create());
        $route = route('posts.index', [
            "filter[{$field}]" => $value
        ]);
        $response = $this->getJson($route);
        $response->assertStatus($expected_code);
    }
    public function filterFields()
    {
        return [  // this is like a test cases, each one will be tested seperatly
            // ['field_name','value','expected_code']
            ['id', 1, 400],
            ['content', 'foo', 400],
            ['creator_id', 1, 200],
        ];
    }

    public function test_unauthenticated_users_can_not_fetch_the_post_list(): void
    {
        $route = route('posts.index');
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $post = Auth::user()->posts()->create($validated);
        return new PostResource($post);
    }
    public function update(StorePostRequest $request, Post $post)
    {
        $validated = $request->validated();
        $post->update($validated);
        return new PostResource($post);
    }
    public function destroy(Request $request, Post $post)
    {
        $post->delete();
        return response()->noContent();
    }
    public function show(Post $post)
    {
        return new PostResource($post);
    }
    public function index(Request $request)
    {
        $posts = QueryBuilder::for(Post::class)
            ->allowedSorts(['content', 'created_at'])
            ->defaultSort('-created_at')
            ->paginate();
        return new PostCollection($posts);
    }

}

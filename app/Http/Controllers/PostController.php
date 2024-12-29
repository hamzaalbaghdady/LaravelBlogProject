<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    public function store(StorePostRequest $request, Post $post)
    {
        $validated = $request->validated();
        $post = $post->create($validated);
        return new PostResource($post);
    }
}

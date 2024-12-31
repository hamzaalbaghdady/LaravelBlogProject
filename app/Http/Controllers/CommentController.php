<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;
use Spatie\QueryBuilder\QueryBuilder;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|max:1500',
        ]);
        $comment = $post->comments()->make($validated);
        $comment->creator()->associate(Auth::user());
        $comment->save();
        return new CommentResource($comment);
    }

    public function update(Request $request, Post $post, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'sometimes|required|max:1500',
        ]);

        $comment->update($validated);
        return new CommentResource($comment);
    }

    public function destroy(Request $request, Post $post, Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }

    public function index(Request $request, Post $post)
    {
        $comments = QueryBuilder::for($post->comments())
            ->allowedSorts(['created_at', 'content', 'updated_at'])
            ->defaultSort('-updated_at')
            ->paginate();
        return new CommentCollection($comments);
    }
}

<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LockedCommentsController extends Controller
{
    /**
     * Create a new model instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Post $post
     * @return Response
     */
    public function index(Post $post)
    {
        return $post->comments()->inactive()->paginate(20);
    }

    /**
     * Lock the given post.
     *
     * @param Comment $comment
     *
     * @return Response
     */
    public function store(Comment $comment)
    {
        $comment->update(['locked' => true]);

        return response(['message' => 'The comment was locked.']);
    }

    /**
     * Unlock the given post.
     *
     * @param Comment $comment
     *
     * @return Response
     */
    public function destroy(Comment $comment)
    {
        $comment->update(['locked' => false]);

        return response(['message' => 'The comment was unlocked.']);
    }
}

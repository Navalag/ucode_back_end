<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Response;

class CommentsLikesController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Store a new like in the database.
     *
     * @param  Comment $comment
     *
     * @return Response
     */
    public function store(Comment $comment)
    {
        $comment->like();

        return response(['message' => 'The comment was liked.']);
    }

    /**
     * Delete the like.
     *
     * @param Comment $comment
     *
     * @return Response
     */
    public function destroy(Comment $comment)
    {
        $comment->unlike();

        return response(['message' => 'The comment was unliked.']);
    }
}

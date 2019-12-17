<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CreatePostRequest;
use App\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param      $category
     * @param Post $post
     *
     * @return mixed
     */
    public function index($category, Post $post)
    {
        return $post->comments()->paginate(20);
    }

    /**
     * Persist a new comment.
     *
     * @param integer $category
     * @param Post $post
     * @param CreatePostRequest $request
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function store($category, Post $post, CreatePostRequest $request)
    {
        if ($post->locked) {
            return response('Thread is locked', 422);
        }

        return $post->addComment([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }

    /**
     * Display the specified resource.
     *
     * @param integer $category
     * @param Post $post
     * @param Comment $comment
     *
     * @return Response
     */
    public function show($category, Post $post, Comment $comment)
    {
        return response($comment, 200);
    }

    /**
     * Update an existing comment.
     *
     * @param Comment $comment
     * @param Request $request
     *
     * @return Response
     * @throws
     */
    public function update(Comment $comment, Request $request)
    {
        $this->authorize('update', $comment);

        $validator = Validator::make($request->all(), [
            'body' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $comment->update(['body' => request('body')]);

        return response()->json($comment, 200);
    }

    /**
     * Delete the given comment.
     *
     * @param  Comment $comment
     *
     * @return Response
     * @throws
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->delete();

        return response(['status' => 'Comment deleted!'], 200);
    }
}

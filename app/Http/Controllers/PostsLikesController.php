<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Response;

class PostsLikesController extends Controller
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
     * @param  $category
     * @param  Post $post
     *
     * @return Response
     */
    public function store($category, Post $post)
    {
        $post->like();

        return response(['message' => 'The post was liked.']);
    }

    /**
     * Delete the like.
     *
     * @param  $category
     * @param  Post $post
     *
     * @return Response
     */
    public function destroy($category, Post $post)
    {
        $post->unlike();

        return response(['message' => 'The post was unliked.']);
    }
}

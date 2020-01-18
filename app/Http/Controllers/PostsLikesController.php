<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Builder;

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
     * @param  Post $post
     *
     * @return Response
     */
    public function store(Post $post)
    {
        $post->like();

        return response(['message' => 'The post was liked.']);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     *
     * @return Response
     */
    public function show(Post $post)
    {
        $likes = Like::whereHasMorph(
            'liked',
            Post::class,
            function (Builder $query) use ($post) {
                $query->where('liked_id', $post->id);
            }
        )->get();

        return response()->json($likes, 200);
    }

    /**
     * Delete the like.
     *
     * @param  Post $post
     *
     * @return Response
     */
    public function destroy(Post $post)
    {
        $post->unlike();

        return response(['message' => 'The post was unliked.']);
    }
}

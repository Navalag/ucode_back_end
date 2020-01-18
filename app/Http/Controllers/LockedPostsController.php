<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Response;

class LockedPostsController extends Controller
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
     * @param Category $category
     *
     * @return Response
     */
    public function index(Category $category)
    {
        $posts = Post::latest()->inactive();

        if ($category->exists) {
            $posts->whereHas('categories', function ($query) use ($category) {
                $query->where('name', $category->name);
            })->get();
        }

        return response()->json($posts->paginate(10), 200);
    }

    /**
     * Lock the given post.
     *
     * @param Post $post
     *
     * @return Response
     */
    public function store(Post $post)
    {
        $post->update(['locked' => true]);

        return response(['message' => 'The post was locked.']);
    }

    /**
     * Unlock the given post.
     *
     * @param Post $post
     *
     * @return Response
     */
    public function destroy(Post $post)
    {
        $post->update(['locked' => false]);

        return response(['message' => 'The post was unlocked.']);
    }
}

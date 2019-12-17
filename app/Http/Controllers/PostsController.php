<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PostsController extends Controller
{
    /**
     * Create a new PostsController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
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
        $posts = $this->getPosts($category);

        return response()->json($posts, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['required', Rule::exists((new Category())->getTable(), 'id')],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'category_id' => request('category_id'),
            'title' => request('title'),
            'body' => request('body'),
            'slug' => request('title'),
        ]);

        return response()->json($post, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $category
     * @param  $post
     *
     * @return Response
     */
    public function show($category, Post $post)
    {
        return response()->json($post, 200);
    }

    /**
     * Update the given post.
     *
     * @param Request $request
     * @param string $category
     * @param Post $post
     *
     * @return Response
     *
     * @throws
     */
    public function update(Request $request, $category, Post $post)
    {
        $this->authorize('update', $post);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();

        $post->update($input);

        return response()->json($post, 200);
    }

    /**
     * Delete the given thread.
     *
     * @param  Post $post
     * @param       $category
     *
     * @return Response
     * @throws
     */
    public function destroy($category, Post $post)
    {
        $this->authorize('update', $post);

        $post->delete();

        return response()->json('Post deleted successfully', 200);
    }

    /**
     * Fetch all relevant threads.
     *
     * @param Category $category
     * @return mixed
     */
    protected function getPosts(Category $category)
    {
        $posts = Post::latest();

        if ($category->exists) {
            $posts->where('category_id', $category->id);
        }

        return $posts->paginate(10);
    }
}

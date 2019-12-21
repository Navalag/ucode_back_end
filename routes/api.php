<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout')->middleware('auth:api');
    Route::post('password_reset', 'AuthController@passwordReset')->middleware('auth:api');

    Route::post('refresh', 'AuthController@refresh')->middleware('auth:api');
    Route::post('me', 'AuthController@me')->middleware('auth:api');
});

Route::resource('users', '\\' . UserController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('categories', '\\' . CategoriesController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

Route::get('posts', 'PostsController@index')->name('posts');
Route::get('posts/{post}', 'PostsController@show');
Route::patch('posts/{post}', 'PostsController@update');
Route::delete('posts/{post}', 'PostsController@destroy');
Route::post('posts', 'PostsController@store')->middleware('verified');
Route::get('posts/{category}', 'PostsController@index');

Route::post('locked-posts/{post}', 'LockedPostsController@store')->name('locked-posts.store')->middleware('admin');
Route::delete('locked-posts/{post}', 'LockedPostsController@destroy')->name('locked-posts.destroy')->middleware('admin');

Route::get('posts/{post}/comments', 'CommentsController@index');
Route::post('posts/{post}/comments', 'CommentsController@store');
Route::get('comments/{comment}', 'CommentsController@show');
Route::patch('comments/{comment}', 'CommentsController@update');
Route::delete('comments/{comment}', 'CommentsController@destroy')->name('comments.destroy');

Route::post('/comments/{comment}/like', 'CommentsLikesController@store');
Route::delete('/comments/{comment}/like', 'CommentsLikesController@destroy');

Route::post('/posts/{post}/like', 'PostsLikesController@store');
Route::delete('/posts/{post}/like', 'PostsLikesController@destroy');

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
    Route::post('register', 'ApiAuth\AuthController@register');
    Route::post('login', 'ApiAuth\AuthController@login');
    Route::post('logout', 'ApiAuth\AuthController@logout');

    Route::post('password-reset', 'ApiAuth\ForgotPasswordController@sendPasswordResetLink');
    Route::post('password-reset/{token}', 'ApiAuth\ResetPasswordController@verifyPasswordReset');

    Route::get('email/verify/{id}', 'ApiAuth\VerificationApiController@verify')->name('verification_api.verify');
    Route::get('email/resend', 'ApiAuth\VerificationApiController@resend')->middleware('auth:api')->name('verification_api.resend');

    Route::post('refresh', 'ApiAuth\AuthController@refresh');
    Route::post('me', 'ApiAuth\AuthController@me')->middleware('verified');
});

Route::resource('users', '\\' . UserController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
Route::post('users/avatar', 'UserAvatarController@store')->middleware('auth:api');

Route::resource('categories', '\\' . CategoriesController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

Route::get('posts', 'PostsController@index')->name('posts');
Route::get('posts/{post}', 'PostsController@show');
Route::get('posts/{post}/categories', 'PostsController@showCategories');
Route::patch('posts/{post}', 'PostsController@update');
Route::delete('posts/{post}', 'PostsController@destroy');
Route::post('posts', 'PostsController@store')->middleware('verified');
Route::get('categories/{category}/posts', 'PostsController@index');

Route::get('locked-posts', 'LockedPostsController@index');
Route::get('locked-posts/{category}', 'LockedPostsController@index');
Route::post('locked-posts/{post}', 'LockedPostsController@store');
Route::delete('locked-posts/{post}', 'LockedPostsController@destroy');

Route::get('posts/{post}/comments', 'CommentsController@index');
Route::post('posts/{post}/comments', 'CommentsController@store');
Route::get('comments/{comment}', 'CommentsController@show');
Route::patch('comments/{comment}', 'CommentsController@update');
Route::delete('comments/{comment}', 'CommentsController@destroy')->name('comments.destroy');

Route::get('posts/{post}/locked-comment', 'LockedCommentsController@index');
Route::post('locked-comment/{comment}', 'LockedCommentsController@store');
Route::delete('locked-comment/{comment}', 'LockedCommentsController@destroy');

Route::post('/comments/{comment}/like', 'CommentsLikesController@store');
Route::get('/comments/{comment}/like', 'CommentsLikesController@show');
Route::delete('/comments/{comment}/like', 'CommentsLikesController@destroy');

Route::post('/posts/{post}/like', 'PostsLikesController@store');
Route::get('/posts/{post}/like', 'PostsLikesController@show');
Route::delete('/posts/{post}/like', 'PostsLikesController@destroy');

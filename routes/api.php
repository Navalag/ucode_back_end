<?php

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

Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', '\\' . UserController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
});

Route::get('posts', 'PostsController@index')->name('posts');
Route::get('posts/{category}/{post}', 'PostsController@show');
Route::patch('posts/{category}/{post}', 'PostsController@update');
Route::delete('posts/{category}/{post}', 'PostsController@destroy');
Route::post('posts', 'PostsController@store')->middleware('verified');
Route::get('posts/{category}', 'PostsController@index');

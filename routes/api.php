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

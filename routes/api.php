<?php

use Illuminate\Http\Request;

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

Route::prefix('admin')->group(function(){
    Route::post('login', 'Admin\AuthController@login');
    Route::post('forgot-password', 'Admin\AuthController@forgotPassword');
    Route::get('reset-password-check', 'Admin\AuthController@resetPasswordCheckLink');
    Route::post('reset-password', 'Admin\AuthController@resetPassword');
    Route::get('user', 'Admin\AuthController@getAuthenticatedUser')->middleware('jwt.verify');

    /* Article Categories */
    Route::prefix('article_categories')->group(function(){
        Route::get('/', 'Admin\ArticleCategoryController@index')->middleware('jwt.verify');
        Route::get('/{id}', 'Admin\ArticleCategoryController@details')->middleware('jwt.verify');
        Route::post('/', 'Admin\ArticleCategoryController@create')->middleware('jwt.verify');
        Route::put('/{id}', 'Admin\ArticleCategoryController@update')->middleware('jwt.verify');
        Route::delete('/{id}', 'Admin\ArticleCategoryController@delete')->middleware('jwt.verify');
    });
});
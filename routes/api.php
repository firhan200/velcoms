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
    Route::get('getTotal', 'Admin\DashboardController@getTotal')->middleware('jwt.verify');

    /* Profile */
    Route::prefix('profiles')->group(function(){
        Route::put('/', 'Admin\AuthController@updateProfile')->middleware('jwt.verify');
        Route::put('/password', 'Admin\AuthController@updatePassword')->middleware('jwt.verify');
    });

    /* Article Categories */
    Route::prefix('article_categories')->group(function(){
        Route::get('/', 'Admin\ArticleCategoryController@index')->middleware('jwt.verify');
        Route::get('/{id}', 'Admin\ArticleCategoryController@details')->middleware('jwt.verify');
        Route::post('/', 'Admin\ArticleCategoryController@create')->middleware('jwt.verify');
        Route::put('/{id}', 'Admin\ArticleCategoryController@update')->middleware('jwt.verify');
        Route::delete('/{id}', 'Admin\ArticleCategoryController@delete')->middleware('jwt.verify');
    });

    /* Article */
    Route::prefix('articles')->group(function(){
        Route::get('/', 'Admin\ArticleController@index')->middleware('jwt.verify');
        Route::get('/{id}', 'Admin\ArticleController@details')->middleware('jwt.verify');
        Route::post('/', 'Admin\ArticleController@create')->middleware('jwt.verify');
        Route::put('/{id}', 'Admin\ArticleController@update')->middleware('jwt.verify');
        Route::delete('/{id}', 'Admin\ArticleController@delete')->middleware('jwt.verify');
    });

    /* Sliders */
    Route::prefix('sliders')->group(function(){
        Route::get('/', 'Admin\SliderController@index')->middleware('jwt.verify');
        Route::get('/{id}', 'Admin\SliderController@details')->middleware('jwt.verify');
        Route::post('/', 'Admin\SliderController@create')->middleware('jwt.verify');
        Route::put('/{id}', 'Admin\SliderController@update')->middleware('jwt.verify');
        Route::delete('/{id}', 'Admin\SliderController@delete')->middleware('jwt.verify');
    });

    /* Galleries */
    Route::prefix('galleries')->group(function(){
        Route::get('/', 'Admin\GalleryController@index')->middleware('jwt.verify');
        Route::get('/{id}', 'Admin\GalleryController@details')->middleware('jwt.verify');
        Route::post('/', 'Admin\GalleryController@create')->middleware('jwt.verify');
        Route::put('/{id}', 'Admin\GalleryController@update')->middleware('jwt.verify');
        Route::delete('/{id}', 'Admin\GalleryController@delete')->middleware('jwt.verify');
    });

    /* Social Links */
    Route::prefix('social_links')->group(function(){
        Route::get('/', 'Admin\SocialLinkController@index')->middleware('jwt.verify');
        Route::get('/{id}', 'Admin\SocialLinkController@details')->middleware('jwt.verify');
        Route::post('/', 'Admin\SocialLinkController@create')->middleware('jwt.verify');
        Route::put('/{id}', 'Admin\SocialLinkController@update')->middleware('jwt.verify');
        Route::delete('/{id}', 'Admin\SocialLinkController@delete')->middleware('jwt.verify');
    });
});
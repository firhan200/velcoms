<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Admin Vue App */
Route::get('/admin', function () {
    return view('admin.app');
});

/** Front Web */
Route::get('/', 'HomeController@index');

/* articles */
Route::prefix('pages')->group(function(){
    Route::get('/{url}', 'PageController@show');
});

/* articles */
Route::prefix('articles')->group(function(){
    Route::get('/', 'ArticleController@index');
    Route::get('/{url}', 'ArticleController@detail');
});
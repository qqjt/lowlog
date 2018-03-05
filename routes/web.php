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

/*Route::get('/', function () {
    return view('welcome');
});*/
Auth::routes();
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/', 'PostController@index')->name('post.index');
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix'=>'p'], function(){
    //add, edit, comment post
    Route::middleware(['auth'])->group(function(){
        Route::get('/create', 'PostController@create')->name('post.create')->middleware('can:create,App\Post');
        Route::post('/create', 'PostController@store')->name('post.store')->middleware('can:create,App\Post');
        Route::post('/{post}/edit', 'PostController@update')->name('post.update')->middleware('can:update,post');
        Route::get('/{post}/edit', 'PostController@edit')->name('post.edit')->middleware('can:update,post');
        Route::post('/{post}/comment', 'CommentController@store')->name('comment.store');
    });
    //show post
    Route::get('/{post}', 'PostController@show')->name('post.show');
    Route::get('/{post}/c', 'CommentController@load')->name('comment.load');
});

<?php
Auth::routes();

Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/', 'PostController@index')->name('post.index')->middleware('http.cache');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/about', 'PageController@about')->name('about')->middleware('http.cache');;
Route::get('/archive', 'PageController@archive')->name('archive')->middleware('http.cache');;
//search post
Route::match(['get', 'post'], '/s', 'PostController@search')->name('post.search');

Route::group(['prefix'=>'p'], function(){
    //add, edit, comment post
    Route::middleware(['auth'])->group(function(){
        Route::get('/create', 'PostController@create')->name('post.create')->middleware('can:create,App\Post');
        Route::post('/create', 'PostController@store')->name('post.store')->middleware('can:create,App\Post');
        Route::post('/{post}/edit', 'PostController@update')->name('post.update')->middleware('can:update,post');
        Route::get('/{post}/edit', 'PostController@edit')->name('post.edit')->middleware('can:update,post');
        Route::get('/{post}/preview', 'PostController@preview')->name('post.preview');
    });
    //add comment
    Route::post('/{post}/comment', 'CommentController@store')->name('comment.store');
    //show post, load comments
    Route::get('/{post}', 'PostController@show')->name('post.show')->middleware('http.cache');;
    Route::get('/{post}/c', 'CommentController@load')->name('comment.load');
});
Route::group([], function(){
    // post category
    Route::post('/cat', 'CategoryController@store')->name('category.store');
    Route::delete('/cat/{category}', 'CategoryController@destroy')->name('category.destroy');
});
//upload image
Route::post('/image', 'ImageController@upload')->name('image.upload')->middleware('auth');
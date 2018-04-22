<?php
Auth::routes();
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/', 'PostController@index')->name('post.index');
Route::get('/p', function(){$query = request()->all(); return redirect()->route('post.index', $query);})->name('p.index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/about', 'PageController@about')->name('about');
Route::get('/archive', 'PageController@archive')->name('archive');
//search post
Route::match(['get', 'post'], '/s', 'PostController@search')->name('post.search');

Route::group(['prefix'=>'p'], function(){
    //add, edit, comment post
    Route::middleware(['auth'])->group(function(){
        Route::get('/create', 'PostController@create')->name('post.create')->middleware('can:create,App\Post');
        Route::post('/create', 'PostController@store')->name('post.store')->middleware('can:create,App\Post');
        Route::post('/{post}/edit', 'PostController@update')->name('post.update')->middleware('can:update,post');
        Route::get('/{post}/edit', 'PostController@edit')->name('post.edit')->middleware('can:update,post');
        Route::post('/{post}/comment', 'CommentController@store')->name('comment.store');
    });
    //show post, load comments
    Route::get('/{post}', 'PostController@show')->name('post.show');
    Route::get('/{post}/c', 'CommentController@load')->name('comment.load');
});
//upload image
Route::post('/image', 'ImageController@upload')->name('image.upload')->middleware('auth');

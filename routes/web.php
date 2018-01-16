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

Route::get('/', 'PostController@index')->name('index');
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware'=> ['auth']], function(){
    Route::get('/new', 'PostController@create')->name('post.create');
    Route::post('/new', 'PostController@store')->name('post.store');
    Route::post('/edit/{post}', 'PostController@update')->name('post.update');
    Route::get('/edit/{post}', 'PostController@edit')->name('post.edit');
});
Route::get('/p/{post}', 'PostController@show')->name('post.show');

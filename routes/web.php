<?php
Route::get('/','PostsController@index')->name('home');
Route::group(['middleware' => 'is_admin'], function(){
	Route::get('/admin', 'AdminController@admin')->name('admin');
	Route::get('/approve', 'AdminController@approve')->name('approve');
	Route::post('/posts/{id}/approve','PostsController@approve');
	Route::post('/posts/{id}/delete','PostsController@delete');
});

Route::get('/posts/create','PostsController@create');
Route::post('/posts','PostsController@store');
Route::get('/posts/{post}','PostsController@show');

Route::get('search/{s?}', 'SearchesController@index')->where('s', '[\w\d]+');

Route::get('/posts/tags/{tag}','TagsController@index');

Route::post('/posts/{post}/comments','CommentsController@store');

Route::get('/register','RegistrationController@create');
Route::post('/register','RegistrationController@store');

Route::get('/login','SessionsController@create');
Route::post('/login','SessionsController@store');
Route::get('/logout','SessionsController@destroy');
// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');

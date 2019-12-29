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

Route::get('/', function () {
    if (Auth::check()) return redirect(route('home'));
    else return view('Auth/login');
});

Route::group(['middleware' => ['auth']], function (){
    Route::get('/user/profile', 'UserController@edit')->name('user_profile');
    Route::patch('/user/profile', 'UserController@update')->name('user_profile_edit');
    Route::get('users', 'UserController@index')->name('users');
    Route::get('users/{id}/profile', 'UserController@show')->name('user_info');
    Route::get('search', 'UserController@autocomplete');

    Route::resource('post', 'PostController');
    Route::get('/home', 'PostController@allPosts')->name('home');
    Route::get('/user/{id}/posts', 'PostController@userFriendPosts')->name('userFriendPosts');
    Route::resource('like', 'LikeController');
    Route::resource('comment', 'CommentController');
    Route::resource('follow', 'FollowController');

});

Auth::routes();


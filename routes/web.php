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
    if (Auth::check()) return redirect('/home');
    else return view('Auth/login');
});

Route::group(['middleware' => ['auth']], function (){
    Route::get('/user/profile', 'UserController@edit')->name('user_profile');
    Route::patch('/user/profile', 'UserController@update')->name('user_profile_edit');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('resources', 'ResourceController');
Route::resource('modules', 'ModuleController');
Route::resource('users', 'UserController');
Route::resource('announcements', 'AnnouncementController');

Route::get('/logout', 'Auth\LoginController@logout');

// Disabled Registration without changing Auth:routes()
Route::match(['get', 'post'], 'register', 'Auth\LoginController@login')->name('register');
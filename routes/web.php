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

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', 'HomeController@index')->name('index');
Route::get('/home', 'HomeController@home')->name('home');

Route::get('/users', 'UserController@index')->name('users');
Route::get('/user/edit', 'UserController@edit')->name('user.edit');
Route::patch('/user', 'UserController@update')->name('user.update');
Route::delete('/user', 'UserController@destroy')->name('user.delete');

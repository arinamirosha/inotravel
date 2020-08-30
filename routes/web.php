<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return view('welcome'); });

Auth::routes();

Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');
Route::patch('/password/{user}', 'PasswordsController@update')->name('password.update');

Route::get('/house/create', 'HousesController@create')->name('house.create');
Route::post('/house', 'HousesController@store')->name('house.store');
Route::get('/house', 'HousesController@index')->name('house.index');
Route::get('/house/{house}', 'HousesController@show')->name('house.show');
Route::delete('/house/{house}', 'HousesController@destroy')->name('house.destroy');
Route::get('/house/{house}/edit', 'HousesController@edit')->name('house.edit');
Route::patch('/house/{house}', 'HousesController@update')->name('house.update');

//сделать нормальные пути
Route::get('/profile/{user}', function ($user) { return redirect("/profile/{$user}/edit"); }); // из браузера идет не в patch
Route::get('/password/{user}', function ($user) { return redirect("/profile/{$user}/edit"); }); // из браузера идет не в patch



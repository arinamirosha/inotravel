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

Route::get('/', 'HomeController@welcome')->name('welcome');

Auth::routes();

Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::post('/profile/{user}', 'ProfilesController@update')->name('profile.update');
Route::post('/profile/{user}/update-password', 'ProfilesController@updatePassword')->name('profile.update_password');

Route::get('/house/create', 'HousesController@create')->name('house.create');
Route::get('/house', 'HousesController@index')->name('house.index');
Route::get('/house/{house}', 'HousesController@show')->name('house.show');
Route::get('/house/{house}/edit', 'HousesController@edit')->name('house.edit');
Route::post('/house', 'HousesController@store')->name('house.store');
Route::post('/house/{house}/update', 'HousesController@update')->name('house.update');
Route::post('/house/{house}', 'HousesController@destroy')->name('house.destroy');

Route::get('/search', 'SearchController@index')->name('search');

Route::get('/booking', 'BookingController@index')->name('booking.index');
Route::post('/booking', 'BookingController@store')->name('booking.store');
Route::post('/booking/{booking}/update', 'BookingController@update')->name('booking.update');
Route::post('/booking/{booking}', 'BookingController@destroy')->name('booking.destroy');

// идет не в post
Route::get('/profile/{user}', function ($user) { return redirect(route('profile.edit', $user)); });
Route::get('/profile/{user}/update-password', function ($user) { return redirect(route('profile.edit', $user)); });
Route::get('/house/{house}/update', function () { return redirect(route('house.index')); });
Route::get('/booking/{booking}/update', function () { return redirect(route('booking.index')); });
Route::get('/booking/{booking}', function () { return redirect(route('booking.index')); });

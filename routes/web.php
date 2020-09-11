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
Route::post('/house', 'HousesController@store')->name('house.store');
Route::get('/house', 'HousesController@index')->name('house.index');
Route::get('/house/{house}', 'HousesController@show')->name('house.show');
Route::post('/house/{house}', 'HousesController@destroy')->name('house.destroy');
Route::get('/house/{house}/edit', 'HousesController@edit')->name('house.edit');
Route::post('/house/{house}', 'HousesController@update')->name('house.update');

Route::get('/search', 'SearchController@index')->name('search');

Route::post('/booking', 'BookingController@store')->name('booking.store');
Route::get('/booking', 'BookingController@index')->name('booking.index');
Route::post('/booking/{booking}', 'BookingController@update')->name('booking.update');
Route::post('/booking/{booking}', 'BookingController@destroy')->name('booking.destroy');

// нет пути
Route::get('/profile/{user}', function ($user) { return redirect(route('profile.edit', $user)); }); // из браузера идет не в patch
Route::get('/profile/{user}/update-password', function ($user) { return redirect(route('profile.edit', $user)); }); // из браузера идет не в patch
Route::get('/booking/{booking}', function () { return redirect(route('welcome')); }); // из браузера идет не в patch


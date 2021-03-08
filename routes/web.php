<?php

use App\Libraries\Localization\Facades\Localization;
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

Route::group([
    'prefix' => Localization::locale(),
    'middleware' => 'setLocale',
],
    function () {
        Route::get('/', 'HomeController')->name('welcome');

        Auth::routes();

        Route::middleware('admin')->group(function () {
            Route::get('/admin', 'AdminController@index')->name('admin.index');
            Route::post('/admin/mark-as-read', 'AdminController@markAsRead')->name('admin.mark-as-read');
            Route::post('/admin/{user}/update', 'AdminController@update')->name('admin.update');
        });

        Route::get('/profile/{user}/show', 'ProfilesController@show')->name('profile.show');
        Route::get('/profile/edit', 'ProfilesController@edit')->name('profile.edit');
        Route::post('/profile/update', 'ProfilesController@update')->name('profile.update');
        Route::post('/profile/update-password', 'ProfilesController@updatePassword')->name('profile.update_password');
        Route::post('/upload-avatar', 'ProfilesController@uploadAvatar')->name('profile.upload-avatar');

        Route::get('/house/create', 'HousesController@create')->name('house.create');
        Route::get('/house', 'HousesController@index')->name('house.index');
        Route::get('/house/{house}', 'HousesController@show')->name('house.show');
        Route::get('/house/{house}/edit', 'HousesController@edit')->name('house.edit');
        Route::post('/house', 'HousesController@store')->name('house.store');
        Route::post('/house/{house}/update', 'HousesController@update')->name('house.update');
        Route::post('/house/{house}', 'HousesController@destroy')->name('house.destroy');
        Route::post('/upload-image', 'HousesController@uploadImage')->name('house.upload-image');
        Route::post('/upload-images', 'HousesController@uploadImages')->name('house.upload-images');

        Route::get('/search', 'SearchController')->name('search');

        Route::get('/booking', 'BookingController@index')->name('booking.index');
        Route::get('/booking/history', 'BookingController@history')->name('booking.history');
        Route::post('/booking', 'BookingController@store')->name('booking.store');
        Route::post('/booking/{booking}/update', 'BookingController@update')->name('booking.update');

        Route::get('/viewed', 'BookingController@viewed');
        Route::post('/toast', 'ToastController');

        // идет не в post
        Route::get('/profile/update', function ($user) { return redirect(route('profile.edit', $user)); });
        Route::get('/profile/update-password', function ($user) { return redirect(route('profile.edit', $user)); });
        Route::get('/house/{house}/update', function () { return redirect(route('house.index')); });
        Route::get('/booking/{booking}/update', function () { return redirect(route('booking.index')); });
        Route::get('/admin/{user}/update', function () { return redirect(route('admin.index')); });
        Route::get('/upload-image', function () { return redirect(route('house.index')); });
        Route::get('/upload-avatar', function ($user) { return redirect(route('profile.edit', $user)); });
        Route::get('/toast', function () { return redirect(route('welcome')); });
        Route::get('/admin/mark-as-read/{id}', function () { return redirect(route('admin.index')); });
    }
);

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
Route::prefix('backend')->group(function () {
    Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin.show_login');
    Route::post('login', 'Auth\AdminLoginController@login')->name('admin.login');
    Route::post('logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/', 'Backend\AdminController@index')->name('admin.dashboard');
    });
});


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

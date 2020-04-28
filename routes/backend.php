<?php

Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin.show_login');
Route::post('login', 'Auth\AdminLoginController@login')->name('admin.login');
Route::post('logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
Route::middleware(['auth:admin'])->group(function () {
    Route::get('partials/server-resources', 'AdminController@serverResources');
    Route::middleware(['is_superadmin'])->group(function () {
        Route::get('operators/search', 'OperatorController@search')->name('operators.search');
        Route::resource('operators', 'OperatorController');
    });
    Route::get('emails/search', 'EmailController@search')->name('emails.search');
    Route::resource('emails', 'EmailController');
    Route::resource('domains', 'DomainController');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
});

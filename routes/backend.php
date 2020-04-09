<?php

Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin.show_login');
Route::post('login', 'Auth\AdminLoginController@login')->name('admin.login');
Route::post('logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
Route::middleware(['auth:admin'])->group(function () {
    Route::get('partials/server-resources', 'AdminController@serverResources');
    Route::get('operators/filter-by-role', 'OperatorController@filterByRole')->name('operators.filter_by_role');
    Route::resource('operators', 'OperatorController');
    Route::resource('emails', 'EmailController');
    Route::resource('domains', 'DomainController');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
});

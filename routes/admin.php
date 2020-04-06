<?php

Route::prefix('admin')->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard');
    Route::post('login', 'Auth\AdminLoginController@login')->name('admin.login');
});

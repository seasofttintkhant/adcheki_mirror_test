<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::get('email/results', 'EmailController@getResults')->name('emails.results');
    Route::post('emails/individual-check', 'EmailController@individualCheck');
    Route::post('emails/delete', 'EmailController@deleteEmails');
    Route::apiResource('emails', 'EmailController');
    Route::post('update-token', 'DeviceController@updateFcmToken');
});

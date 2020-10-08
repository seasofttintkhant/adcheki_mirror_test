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
    Route::post('emails', 'EmailController@store');
    Route::post('emails/individual-check', 'EmailController@individualCheck');
    Route::post('update-token', 'DeviceController@updateFcmToken');
    Route::get('email/results', 'EmailController@getResults');
    Route::post('email/results/status', 'EmailController@resultsStatus');
    Route::get('email/completed', 'EmailController@completed');
    Route::post('email/cancel', 'EmailController@cancel');

    Route::post("get-domains", 'EVSController@getIpDomains');
    Route::post("update-job-and-email-result", 'EVSController@updateJobAndEmailResult');
    Route::post("sync-due-emails", "IsolatedController@syncDueEmails");

    Route::get("jsons/{file_name}", 'EmailController@getJson');
});

<?php

use App\Services\AdvanceEmailValidator;
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
    Route::apiResource('emails', 'EmailController');
    Route::get("test", function(){
        $emails = [
            "chittatthu98@gmail.com",
            "chittatthu98ewerwe@gmail.com",
            "yanlay129@yahoo.com",
            "yanlay129erewrwer@yahoo.com",
            "khant.a.tint@seasoft.asia",
            "khant.a.tintxxxx@seasoft.asia",
            "xx@seasoft.asia",
            "aaa@bbb"
        ];
        $email_validator = new AdvanceEmailValidator();
        $email_validator->setStreamTimeoutWait(20);
        $email_validator->setEmailFrom('seasoft.tint.khant@gmail.com');

        return $result = $email_validator->checkEmails($emails);
    });
});

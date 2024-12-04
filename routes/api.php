<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Bank\FICampaignController;

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
Route::group(['namespace'=>'Api'], function () {
    Route::post('login', 'AuthController@loginPost')->name('login-post');
    Route::post('verify-otp', 'AuthController@verifyOTP')->name('verify-otp');
    Route::post('resend-otp', 'AuthController@resendOTP')->name('resend-otp');
    Route::post('sign-up', 'AuthController@signUp')->name('sign-up-post');
    Route::post('reset-password', 'AuthController@resetPasswordRequest')->name('reset-password');
    Route::get('resend-reset-password', 'AuthController@resendResetPasswordRequest')->name('resend-reset-password');
    Route::get('logout', 'AuthController@logout')->name('logout');
    Route::group(['middleware' => ['auth']], function() {

    });
});

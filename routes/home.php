<?php

use Illuminate\Support\Facades\Route;

Route::get('depositors-Fi-count', 'Dashboard\SettingsController@DepositorsFICount')->name('depositors-Fi-count');
Route::get('get-all-provinces-sign-up', 'Dashboard\SettingsController@getAllProvinces')->name('all-provinces');
Route::get('province-cities', 'Dashboard\SettingsController@loadProvinceCity')->name('province-cities');
Route::get('industries', 'Dashboard\SettingsController@getAllIndustries')->name('get-industries');


Route::post('new-sign-up', 'AuthController@firstStepSignUp')->name('new-sign-up-first-step');
Route::get('conference-authorize/{userid}', 'AuthController@verifyConferenceSignupAfterApproval')->name('conference-authorize');
Route::post('keep-me-informed', 'AuthController@KeepMeInformed')->name('keep-me-informed');
Route::post('depositor-pesonal-create-organization', 'AuthController@DPersonalOrganization')->name('create-depositor-personal-organization');
Route::post('depositor-business-create-organization', 'AuthController@DBusinessOrganisation')->name('create-depositor-business-organization');
Route::post('update-user-info', 'AuthController@UpdateUserInfo')->name('update-user-info');

Route::post('add-an-entity', 'AuthController@addOrUpdateEntity')->name('add-an-entity');
Route::post('add-an-individual', 'AuthController@addOrUpdateKeyIndividuals')->name('add-an-individual');
Route::post('remove-an-individual', 'AuthController@deleteKeyIndividuals')->name('remove-an-individual');
Route::post('remove-an-entity', 'AuthController@deleteEntity')->name('remove-an-entity');
Route::post('create-document-on-signup', 'AuthController@createOrganizationDocuments')->name('create-document-on-signup');

Route::get('login', 'AuthController@index')->name('login');
Route::get('sign-up/{referral?}', 'AuthController@signUp')->name('sign-up');
Route::get('depositors/{referral?}', 'AuthController@depositorSignUp')->name('depositor-sign-up-form');
Route::post('depositors', 'AuthController@depositorSignUpPost')->name('depositor-sign-up');
Route::post('depositors/verify/{code}', 'AuthController@verificationDSignUpPost')->name('depositor-sign-up-verification');
//Route::post('contact-us','HomeController@contactSubmit')->name('contact-us');
//Route::get('about-us','HomeController@aboutUs')->name('about-us');
Route::get('logout', 'AuthController@logout')->name('logout');
//Route::get('blogs','HomeController@blogs')->name('home.blogs');
//Route::post('blogs','HomeController@blogsWithTagsAndCategory')->name('home.blogs');
//Route::get('blog/{id}/{slug}','HomeController@blogDetail')->name('home.blog-detail');
//Route::get('blogs/{filter_by}/{id}','HomeController@blogs');

Route::post('login', 'AuthController@loginPost')->name('login-post');
Route::post('verify-otp', 'AuthController@verifyOTP')->name('verify-otp');
Route::post('resend-otp', 'AuthController@resendOTP')->name('resend-otp');
Route::post('sign-up', 'AuthController@signUpPost')->name('sign-up-post');
Route::post('reset-password', 'AuthController@resetPasswordRequest')->name('reset-password');
Route::get('resend-reset-password', 'AuthController@resendResetPasswordRequest')->name('resend-reset-password');
Route::post('reset-password-final-step', 'AuthController@resetPasswordFinalStepRequest')->name('reset-password-final-step');

Route::get('/', function () {
    return redirect()->to('/login');
})->name('home');

//Route::get('/','HomeController@index')->name('home');
Route::get('contact-us', 'HomeController@index')->name('contact-us');

// Admin authentication
Route::group(['prefix' => 'yie-admin'], function () {
    Route::get('/', 'AuthController@index')->name('admin.login');
    Route::get('login', 'AuthController@index')->name('admin.login');
    Route::post('login', 'AuthController@loginPost')->name('admin.login-post');
    Route::post('verify-otp', 'AuthController@verifyOTP')->name('admin.verify-otp');
    Route::post('resend-otp', 'AuthController@resendOTP')->name('admin.resend-otp');
    Route::post('reset-password', 'AuthController@resetPasswordRequest')->name('admin.reset-password');
    Route::get('resend-reset-password', 'AuthController@resendResetPasswordRequest')->name('admin.resend-reset-password');
    Route::post('reset-password-final-step', 'AuthController@resetPasswordFinalStepRequest')->name('admin.reset-password-final-step');
});

// Non Partnered FI
Route::get('view-invitation/{token}', 'AuthController@viewInvitation')->name('user.non-fi-view-invitation');
Route::get('/request-an-account', function () {
    return view('auth.request-account');
});

Route::get('/account_verification/{code}', function () {
    return view('auth.request-account');
})->name('depositor-sign-up-verify');
Route::get('token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('get-all-products', 'Dashboard\SettingsController@getProducts');
Route::get('get-all-organizations/{type?}', 'Dashboard\SettingsController@getOrganization');



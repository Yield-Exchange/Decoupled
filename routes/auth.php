<?php
use Illuminate\Support\Facades\Route;

Route::get('first-login/update-password', 'AuthController@forceUpdateUserPassword')->name('force-update-password');
Route::get('yie-admin/first-login/update-password', 'AuthController@forceUpdateUserPassword')->name('yie-admin.force-update-password');
Route::get('first-login/proceed-with-signup', 'AuthController@signUpConfirmSeats')->name('force-confirm-organization-seats');
Route::post('first-login/request-organization-seats', 'AuthController@requestOrganizationSeats')->name('request-organization-seats');
Route::post('first-login/complete-registration', 'AuthController@completeOrganizationRegistration')->name('complete-registration-organization');
Route::post('users-create', 'Dashboard\UsersController@usersCreate')->name('users.create'); // moved here since the onboarding journey allows create user
Route::post('depositor-terms-review', 'AuthController@accept_terms_and_conditions')->name('user.depositor-terms-review');
Route::get('depositor-terms-conditions-review', 'AuthController@accept_terms_and_conditions_render')->name('user.depositor-terms-conditions-review');

Route::get('back-to-yie', [App\Http\Controllers\AuthController::class, 'loginAsAdmin'])->name('go-back-to-admin');
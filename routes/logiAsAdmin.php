<?php
use Illuminate\Support\Facades\Route;

Route::get('back-to-yie', [App\Http\Controllers\AuthController::class, 'loginAsAdmin'])->name('go-back-to-admin');
Route::get('login-as-client/{organization_id}/{admin}/{accesstoken}', [App\Http\Controllers\Dashboard\Admin\ManageUsersController::class,'loginAsClient'])->name('login-as-client');

<?php

use App\Http\Controllers\ProcessFileContent;
use App\Http\Controllers\SessionController;
use App\Models\FITypes;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|routes/logiAsAdmin.php
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test_pdf', function () {
    return view('mt527');
});

Route::middleware('log.route.access')->group(base_path('routes/home.php'));
//require_once base_path('routes/home.php');

Route::get('access-denied', function () {
    return view('dashboard.403');
})->name('access-denied');

Route::middleware('auth')->group(function () {
    Route::middleware('log.route.access')->group(base_path('routes/auth.php'));
});

require base_path('routes/logiAsAdmin.php');


Route::namespace('Dashboard')->middleware(['auth', 'auth.active_user'])->group(function () {
    Route::middleware('log.route.access')->group(base_path('routes/dashboard.php'));
});
Route::middleware('auth')->get('/proceed-to-reg', function () {
    $fi_types = FITypes::all();
    return view('auth.proceed-with-signup', compact('fi_types'));
})->name('proceed-to-reg');

Route::get('/getuser', function () {
    return json_encode(auth()->user());
});
Route::get('token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
Route::get('get-session', 'SessionController@getSessionIp')->name('get-session');
Route::post('set-session', 'SessionController@index')->name('set-session');
Route::get('unsubscribe/{email_type}/{user_id}/{user_email}','UnsubscribeEmailController@viewUnsubscribePage')->name('unsubscribe');
Route::post('unsubscribe/emails','UnsubscribeEmailController@unsubscribedEmail');

Route::get('system_rates', function () {
    $interestrates = SystemSetting::where("status", "ACTIVE")->where("setting_type", "rate")->select("system_settings.*", "system_settings.value as rate_value")->get();
    return $interestrates;
});

Route::get('get-formated-timezone', function () {
    $unformattedusertimezone = auth()->user()->timezone;
    $formattedtimezone = formattedTimezone($unformattedusertimezone);
    return response()->json($formattedtimezone);
});
Route::get('get_all_rate_types', function () {
    $interestrates = SystemSetting::where("status", "ACTIVE")->where("setting_type", "rate")->select("system_settings.*", "system_settings.value as rate_value")->get();
    return $interestrates;
});
Route::middleware('auth')->group(function () {

    Route::get('launchpad', function () {
        $enable_repos = auth()->user()->organization->organizationHas('enable_repos');
        $enable_campaigns = auth()->user()->organization->organizationHas('enable_campaigns');
        return view('dashboard.depositor.launchpad', compact('enable_repos', 'enable_campaigns'));
    });
});

Route::middleware('auth')->group(function () {
    Route::middleware('auth.common')->group(base_path('routes/common/web.php'));
});

Route::post('/upload-mt-file', [ProcessFileContent::class, 'storeFromTxtFile']);

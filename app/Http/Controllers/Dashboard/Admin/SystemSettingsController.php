<?php
namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SystemSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        $user=\auth()->user();
        if(!$user->userCan('admin/system-settings/page-access')){
            return view('dashboard.403');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > System Settings");
        return view('dashboard.admin.settings.system');
    }

    public function update(Request $request){
        $user=\auth()->user();
        if( !$user->userCan('admin/system-settings/save-button') ){
            return view('dashboard.403');
        }

        $fields= $request->except(['_token']);
        $update=false;
        foreach ($fields as $key => $value) {
            $prime_rate_system_setting = getSystemSettings($key);
            if(!$prime_rate_system_setting){
                $prime_rate_system_setting =  SystemSetting::create([
                    'value'=>$value,
                    'key'=>$key,
                    'created_date'=>Carbon::now(),
                    'modified_by'=>$user->id
                ]);
                $update=true;
            }

            if ($value != $prime_rate_system_setting->value) {
                archiveTable($prime_rate_system_setting->id, 'system_settings', $user->id, strtoupper($key).' UPDATED');
                $prime_rate_system_setting->update([
                    'value'=>$value,
                    'modified_date'=>Carbon::now()
                ]);
                $update=true;
            }
        }

        if($update){
            $response = ["data" => [], "success" => true, "message" => "Setting updated successfully"];
            return response()->json($response, 200);
        }else {
            $response = ["data" => [], "success" => false, "message" => "Nothing was updated"];
            return response()->json($response, 200);
        }
    }

}
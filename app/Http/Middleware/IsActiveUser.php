<?php

namespace App\Http\Middleware;

use App\Data\GlobalData;
use App\Traits\BaseMiddlewareTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsActiveUser
{
    use BaseMiddlewareTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        $segment1 = $request->segment(1);
        if ( $segment1 != "yie-admin" ) {
            $organization = $user->organization;
            if ( $organization && in_array($organization->type, ["BANK", "BOTH"])) {

                $should_logout = $user->account_status != 'ACTIVE' || !in_array($organization->status,['ACTIVE']);

            }else if($organization && in_array($organization->type, ["DEPOSITOR"])) {

                $should_logout = !in_array($user->account_status,['ACTIVE']);

                if(in_array($organization->status,['PENDING'])){

                    $should_logout = !in_array($organization->status,['ACTIVE']);

                    if ($organization->is_partially_approved == 1 && $organization->needs_update == "yes"
                    && !$request->routeIs('user.account-setting') && !$request->routeIs('user.update-account-setting')
                    ) {
                        alert()->warning("Please complete account settings in order to use the Yield Exchange");
                        return redirect()->route('user.account-setting',['fromPage'=>'/dashboard']);
                    }
                }

            }else{
                $should_logout = !($user->is_super_admin && in_array($segment1,GlobalData::$admin_can_access_routes) && $user->account_status == 'ACTIVE');
            }
        }else {
            $should_logout = $user->account_status != 'ACTIVE';
        }

        if($should_logout){
            $request->session()->flush();
            Auth::logout();

            if ($request->ajax() && $request->has("draw") && $request->has('length') && $request->has('start')) {
                return response()->json(array(
                    "draw" => 1,
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => []
                ));
            }

            if ($request->segment(1) == "yie-admin") {
                return redirect()->to('yie-admin/login');
            } else {
                return redirect()->to('login');
            }
        }

        $timezone = formattedTimezone($user->timezone);
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsBank
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try{
            if(Auth::check()) {
                $user = Auth::user();
                $organization = $user->organization;
                if ( in_array($organization->type,["BANK","BOTH"]) ) {

                    if ($organization->is_non_partnered_fi == 1 && $organization->status == 'ACTIVE' && $user->password_changed == 0) {
                        alert()->warning("Please complete account settings in order to use the Yield Exchange Limited Version");
                        return redirect()->route('user.account-setting');
                    }
                    return $next($request);
                }
            }

            $message="You do not have access";
            if($request->ajax()){
                $response = array("success"=>false, "message"=>$message, "data"=>[]);
                return response()->json($response, 403);
            }

            alert()->error($message);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
        }

        if( $request->ajax() ){
            $response = array("success"=>false, "message"=>"Unauthorized", "data"=>[]);
            return response()->json($response, 403);
        }
        if(Auth::check()) {
            return redirect()->to('/dashboard');
        }

        return redirect()->to('/');
    }
}

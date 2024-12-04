<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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
        if(Auth::check()) {
            $user=Auth::user();
            if ( $request->segment(1) == "yie-admin" ) {
                if ( !$user->is_super_admin ){
                    $request->session()->flush();
                    Auth::logout();

                    if( $request->ajax() && $request->has("draw") && $request->has('length') && $request->has('start') ){
                        return response()->json(array(
                            "draw" => 1,
                            "iTotalRecords" => 0,
                            "iTotalDisplayRecords" => 0,
                            "aaData" => []
                        ));
                    }
                    return redirect()->to('yie-admin/login');
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

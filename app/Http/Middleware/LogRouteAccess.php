<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RouteAccessLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogRouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->route()->getName(),['notifications.unread.count','deposit-chats.unread.count'])){
            return;
        }

        try {

            if( $request->url() === "http://172.31.8.19" || $request->url() === "http://localhost" ){
                return;
            }

            $log = new RouteAccessLog();
            if (Auth::check()) {
                $user = DB::table('users')->find(Auth::id());
                $log->user_id = $user->id;
                $log->organization_id = $user->switched_organization_id;
            }
            $log->route = $request->url();
            $log->method = $request->getMethod();
            $clientIp = $request->header('X-Client-IP');
            $log->ip_address = $clientIp;
            $log->headers = json_encode($request->headers->all());
            $log->save();
        }catch (\Exception $exception){

        }

        return $next($request);
    }
}

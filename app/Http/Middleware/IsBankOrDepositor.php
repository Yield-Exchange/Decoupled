<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsBankOrDepositor
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
               return $next($request);                
            }

        }catch (\Exception $exception){
            Log::error($exception->getMessage());
        }
    }
}

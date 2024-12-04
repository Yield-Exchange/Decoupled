<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $user=null;
    public $organization=null;

    public function loadUserObject(){
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->organization = $this->user->organization;
            return $next($request);
        });
    }
}

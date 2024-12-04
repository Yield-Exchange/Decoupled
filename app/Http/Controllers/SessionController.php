<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $sessionValue = $request->input('my_ip');
        $request->session()->put('my_ip', $sessionValue);
        return response()->json(['message' => 'Session variable set successfully']);
    }
    public function getSessionIp(Request $request){
        if(session("my_ip")){
          return session("my_ip");
        }else{
            $request->session()->put('my_ip', $request->my_ip);
            
        }
    }
}

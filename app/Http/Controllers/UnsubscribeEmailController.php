<?php

namespace App\Http\Controllers;

use App\CustomEncoder;
use App\Models\UnsubscribedEmail;
use Illuminate\Http\Request;

class UnsubscribeEmailController extends Controller
{
    public function viewUnsubscribePage(Request $request,$email,$user_id,$user_email){
        return view('home.unsubscribe',compact('user_email','email','user_id'));
    }
    public function unsubscribedEmail(Request $request){
        $user_email = CustomEncoder::urlValueDecrypt($request->user_email);
        $user_id = CustomEncoder::urlValueDecrypt($request->user_id);
        $unsubscribed = UnsubscribedEmail::where([['user_id',$user_id],['user_email',$user_email]])->first();
        //dd($unsubscribed);
        if (!$unsubscribed) {
            $data = new UnsubscribedEmail();
            $data['user_id'] = $user_id;
            $data['user_email'] = $user_email;
            if ($request->filled('marketing_email')) {
                if ($request->marketing_email == '1') {
                    $data['unsubscribe_from_all_marketing'] = 1;
                    $data['email_type'] = 'all';
                }else{
                    $data['unsubscribe_from_all_marketing'] = 0;
                    $data['email_type'] = $request->email;
                }
                
            }  
            $data->save();
        }else{
            if ($request->filled('marketing_email')) {
                if ($request->marketing_email == '1') {
                    $unsubscribed['unsubscribe_from_all_marketing'] = 1;
                    $unsubscribed['email_type'] = 'all';
                }else{
                    $unsubscribed['unsubscribe_from_all_marketing'] = 0;
                    $unsubscribed['email_type'] = $request->email;
                }
                
            }  
            $unsubscribed->save();
        }
        $email = $request->email;
        $user_email = $request->user_email;
        $user_id = $request->user_id;
        return view('home.unsubscribed',compact('user_email','user_id','email'));
    }
}

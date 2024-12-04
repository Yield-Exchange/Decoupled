<?php
namespace App\Mixins;

class ResponseMixin{
    public function jsonError404(){
        return function ($message="Not Found"){
            return response()->json(['message'=>$message,'success'=>false, 'data'=>[]], 404);
        };
    }

    public function jsonSuccess(){
        return function ($message="Successful", $data=[], $code=200){
            return response()->json(['message'=>$message,'success'=>true, 'data'=>$data], $code);
        };
    }

    public function jsonErrorFailure(){
        return function ($message="Failed", $code=400){
            return response()->json(['message'=>$message,'success'=>false, 'data'=>[]], $code);
        };
    }
}
<?php

namespace App\Http\Controllers;

use App\CustomEncoder;
use App\Services\Auth\UserAccountManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class UserAccountManagerController extends Controller
{
    //
    protected $accountmanagerserviice;
    public function __construct(UserAccountManagerService $accountmanagerserviice)
    {
        $this->accountmanagerserviice = new UserAccountManagerService();
    }
    public function getAllowedOrganizations(){

      return $this->accountmanagerserviice->getAllowedOrganizations();
      
    }
    public function changeDefaultOrganization(Request $request)
    {
        $userLogged = Auth::user();
        $request = $request->all();
        if(isset($request['newOrganization'])){
            $request['newOrganization'] = CustomEncoder::urlValueDecrypt($request['newOrganization']);
        }        
        $rules = ['newOrganization' => [
            'required',
            'exists:organizations,id',
            function ($attribute, $value, $fail) use ($userLogged) {
                $exists = DB::table('users_organizations')
                    ->where('user_id', $userLogged->id)
                    ->where('organization_id', $value)
                    ->exists();

                if (!$exists) {
                    $fail('The selected organization is not associated with the logged-in user.');
                }
            }
        ]];

        $validator = Validator::make($request, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'You have errors in your data.',
                'errors' => $validator->errors()
            ], 400);
        }

        return $this->accountmanagerserviice->changeDefaultOrganization($request);



    }
    public function getOrgLevelPermissions(Request $request){

        return $this->accountmanagerserviice->getOrgLevelPermissions($request);

    }
    public function requestAccessToLaunchpadItem(Request $request){

        $rules = [
            'parentPermID' => [
                'required',
                'string'
            ],
            'itemLabel' => 'required|string'
        ];   
        $requests= $request->all(); 
        $requests['parentPermID']=CustomEncoder::urlValueDecrypt($request->parentPermID);
        // return CustomEncoder::urlValueDecrypt($request->parentPermID);
        $validator = Validator::make($requests, $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'You have errors in your data.',
                'errors' => $validator->errors()
            ], 400);
        }
        return $this->accountmanagerserviice->requestAccessToLaunchpadItem($request);
        
    }

}

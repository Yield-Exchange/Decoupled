<?php 
namespace App\Services\Auth;

use App\CustomEncoder;
use App\Mail\AdminMails;
use App\Mail\BankMails;
use App\Mail\DepositorMails;
use App\Models\OrgPermissionsList;
use App\Models\OrgRequestAccess;
use App\Models\UserOrganization;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserAccountManagerService extends BaseService
{
    public function getAllowedOrganizations(){
        $user=Auth::user();
        if($user){
            return $user->allowedOrganizations();
        }else{
            return [];
        }
      
    }
    public function changeDefaultOrganization($request){
        try{
            DB::beginTransaction();
            $user=Auth::user();
            UserOrganization::where("user_id", $user->id)->update(['is_default'=>0]);    
            $updated = UserOrganization::where('user_id', $user->id)
            ->where('organization_id', $request['newOrganization'])
            ->update(['is_default' => 1]);
           
            DB::commit();
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Default organization updated successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update the default organization.'
                ], 500);
            }

        }catch(Exception $ex){
            DB::rollback();
        }
     
    }

    public function getOrgLevelPermissions(Request $request){

        $allperms=OrgPermissionsList::all();
        return $allperms;

    }

    public function requestAccessToLaunchpadItem(Request $request)
    {

        $found = OrgPermissionsList::where("id", CustomEncoder::urlValueDecrypt($request->parentPermID))->first();
        $user = \auth()->user();
 
        if ($found) {
            try {

                DB::beginTransaction();
                $org_permissions_list_id = CustomEncoder::urlValueDecrypt($request->parentPermID);
                $existingRequest = OrgRequestAccess::where('org_permissions_list_id', $org_permissions_list_id)
                ->where('organization_id', $user->organization->id)
                ->where('user_id', $user->id)
                ->where('status', 'PENDING')
                ->first();

                if (!$existingRequest) {

                   
                    if($user->isOrganizationAdmin()){
                        $orgRequestAccess = new OrgRequestAccess();
                        $orgRequestAccess->org_permissions_list_id = $org_permissions_list_id;
                        $orgRequestAccess->organization_id = $user->organization->id;
                        $orgRequestAccess->user_id = $user->id;
    
                        if ($orgRequestAccess->save()) {

                            $orgRequestAccess->load(['organization', 'permissionDetails']);    
                            $this->sendMailForRequest("superadmin", $user->organization->type, $orgRequestAccess);

                        }


                    }else{
                        $details['adminMails']=$user->organization->adminUsersEmails();
                        $details['requestingUser']=$user;
                        $details['details']=$request->all();

                       $this->sendMailForRequest("organizationAdminUser", $user->organization->type, $details);

                    }

                } else {
                    return response()->json(['message' => 'A pending request already exists for this user.'], 409);
                }


                DB::commit();



                return response()->json([
                    'success' => true,
                    'message' => 'Send successfully.',
                    'data' => []
                ], 201);

            } catch (Exception $e) {

                DB::rollBack();


                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create record.',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.'
            ], 404);
        }
    }
    public function sendMailForRequest($userType,$orgType,$request){
        if ($userType == "organizationAdminUser") {
          
            if($orgType=="DEPOSITOR"){

              Mail::to($request['adminMails'])->queue(new DepositorMails([
                    'subject' => "Feature Access Request!",
                    'access_request' => $request,
                    'user_type' => "CG"
                ], 'yourOrgUserAccessRequest'));

            }else if($orgType=="BANK"){

                Mail::to($request['adminMails'])->queue(new BankMails([
                    'subject' => "Feature Access Request!",
                    'ct_Request' => $request,
                    'user_type' => "CG"
                ], 'yourOrgUserAccessRequest'));

            }
          
        } else if ($userType == "requestingUser") {

            if($orgType=="DEPOSITOR"){
                Mail::to($request['adminMails'])->queue(new DepositorMails([
                    'subject' => "Feature Access Request!",
                    'access_request' => $request,
                    'user_type' => "CG"
                ], 'accessRequesShared'));
            }else if($orgType=="BANK"){
                Mail::to($request['radminMailsequestingUser'])->queue(new BankMails([
                    'subject' => "Feature Access Request!",
                    'access_request' => $request,
                    'user_type' => "CG"
                ], 'accessRequesShared')); 
            }

         
        } else if ($userType == "superadmin") {    

            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Feature Access Request!",
                'access_request' => $request,
                'user_type' => "CG"
            ], 'accessRequesShared'));
        }
    }

}
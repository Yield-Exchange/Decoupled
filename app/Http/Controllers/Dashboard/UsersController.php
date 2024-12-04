<?php
namespace App\Http\Controllers\Dashboard;

use App\Constants;
use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Mail\Admin\AdminUserActionMail;
use App\Mail\NewUserPasswordMail;
use App\Models\Organization;
use App\Models\UserOrganization;
use App\Models\UserPassword;
use App\Models\UserPreference;
use App\Models\Preference;
use App\Models\UserApprovalLimit;
use App\Models\UsersDemoGraphicData;
use App\PermissionsGroup;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request,$organization_id=null){
        $user=\auth()->user();
        if( !$user->userCan('universal/users/page-access') ){
            return view('dashboard.403');
        }

        if (empty($organization_id)){
            $organization = $user->organization;
        }else{
            $organization = Organization::find(CustomEncoder::urlValueDecrypt($organization_id));
        }

        if(!$organization){
            if($request->ajax()) {
                $response = ['data'=>[],'message'=>'Organization not found', 'success'=>false];
                return response()->json($response, 400);
            }

            alert()->error('Organization not found');
            return redirect()->to('/dashboard');
        }

//        $users_count = $organization->users();
// User::select([
//            'users.*',
//            'creator.name as creator_name',
////            'roles.name as role_name'
//        ])->leftJoin('users as creator','creator.id','=','users.created_by')
//            ->join('users_organizations','users_organizations.user_id','=','users.id')
//            ->join('organizations','users_organizations.organization_id','=','organizations.id')
//            ->where('users_organizations.organization_id',$organization->id)
//            ->leftJoin('role_user','role_user.user_id','=','users.id')
//            ->leftJoin('roles','role_user.role_id','=','roles.id')
//            ->whereIn('users.account_status',systemActiveUsersStatuses())
//            ->groupBy('users.id')
//            ->count();

//        $organization_limit=$organization->organization_users_limit;

        $users_limit_exceeded = $organization->organization_users_limit <= $organization->users_count;

        $org_id =CustomEncoder::urlValueEncrypt($organization->id);
        return view('dashboard.users.index',compact('users_limit_exceeded','organization','org_id'));
    }

    public function usersDataNew(Request $request,$organization_id=null){
        //echo $organization_id;
        $user=\auth()->user();
        if( !$user->userCan('universal/users/page-access') ){

            $response = array(
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            );
            return response()->json($response);
        }

        if(empty($organization_id)){
            $organization = $user->organization;
        }
        else{
            $organization = Organization::find(CustomEncoder::urlValueDecrypt($organization_id));
            if(!$organization){
                $response = array(
                    "draw" => 0,
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => []
                );
                return response()->json($response);
            }
        }

        $users = User::select([
            'users.*',
            'demographic_user_data.timezone as timezone',
            'demographic_user_data.phone as phone',
            'demographic_user_data.city as city',
            'demographic_user_data.province as province',
            'demographic_user_data.department as department',
            'demographic_user_data.job_title as job_title',
            'creator.firstname as creator_firstname',
            'creator.lastname as creator_lastname'
        ])->leftJoin('users as creator','creator.id','=','users.created_by')
        ->join('users_organizations','users_organizations.user_id','=','users.id');
        $users = $users->where('users_organizations.organization_id',$organization->id);
        $users = $users->leftJoin('role_user','role_user.user_id','=','users.id')
            ->leftJoin('roles','role_user.role_id','=','roles.id')
            ->leftJoin('demographic_user_data','users.id','=','demographic_user_data.user_id');
        $users = $users->whereIn('users.account_status',systemActiveUsersStatuses())
        ->groupBy('users.id');
        //  $users = $users->whereNotIn('users.id',[auth()->id()]);


//        $totalRecords = with(clone $users)->count();


        //Kept aside for search
        $searchValue = request('search'); // Search value

        $getColumns = explode(',', request('columns'));

        if( !empty($searchValue) ) {
            $users = $users->where(function ($query) use ($searchValue, $getColumns) {
                $query->where(DB::raw("CONCAT('users.firstname',' ','users.lastname')"), 'like', '%' . $searchValue . '%');
                foreach ($getColumns as $column) {
                    switch ($column) {
                        case 'name':
                            $query->orWhere(DB::raw("CONCAT('users.firstname',' ','users.lastname')"), 'like', '%' . $searchValue . '%');
                            break;
                        case 'role':
                            $query->orWhere('roles.name', 'like', '%' . $searchValue . '%');
                            break;
                        case 'email':
                        case 'account_status':
                            $query->orWhere('users.'.$column, 'like', '%' . $searchValue . '%');
                            break;
                        case 'account_opening_date':
                        case 'last_login':
                            try {
                                $date = Carbon::createFromFormat(Constants::DATE_FORMAT,$searchValue);
                                $date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT);
                                $query->orWhere('users.account_opening_date', 'like', '%' . $date_in_utc . '%')
                                    ->orWhere('users.last_login', 'like', '%' . $date_in_utc . '%');
                            } catch (\Exception $exception) {
                                $query->orWhere('users.account_opening_date', 'like', '%' . $searchValue . '%')
                                    ->orWhere('users.last_login', 'like', '%' . $searchValue . '%');
                            }
                            break;
                    }
                }
            });
        }
        // For Sorting


        $sortOrder = request('sortOrder');
        $sortColumn = request('sortColumn');

        if(!$sortColumn){
            $users = $users->orderBy('users.id','DESC');
        }else{
            foreach ($getColumns as $column) {
                if ($sortColumn  == $column && ($column == 'role' || $column == 'creator')) {
                    $users = $users->orderBy('roles.name', strtoupper($sortOrder));
                    break;
                }

                if ($sortColumn  == $column) {
                    $users = $users->orderBy('users.'.$column,strtoupper($sortOrder));
                }
            }
        }

        $data = $users;


//        $totalRecordswithFilter = with(clone $data)->count();


        ## Read value
//        $start =  request('startQueryFrom');
        $rowperpage = request('rowParPage');

        $data = $data->paginate($rowperpage ? $rowperpage : 10);/*skip($start)
            ->take($rowperpage)
            ->get();*/


        $data_arr = array();
//        $roles = Role::whereNotIn('roles.name',unfetchableRoles())->get();

//        $user = auth()->user();
        $i=1;
        foreach($data as $record){
            $record_role=$record->role($organization)->first();
            if($record_role) {
                $record->role_id = $record_role->id;
                $record->role_name = $record_role->display_name;
            }
            $data_arr[] = array(
                "sno" => $i++,
                "id" => $record->id,
                "name"=> ( !empty($this->firstname) || !empty($this->lastname) ) ? ucwords($record->firstname).' '.ucwords($record->lastname) : $record->name,
                "email"=>$record->email,
                "role"=>$record->role_name=="Organization Administrator" ? "Admin" : $record->role_name,
                "account_opening_date"=>$record->account_opening_date ? changeDateFromUTCtoLocal($record->account_opening_date) : '-',
                "account_status"=>$record->account_status,
                "last_login"=>$record->last_login ? changeDateFromUTCtoLocal($record->last_login) : '-',
                "creator"=>ucwords($record->creator_firstname).' '.ucwords($record->creator_lastname),
                "data" => $record,
            );
        }

//        $response = array(
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr
//        );

        $data = new LengthAwarePaginator($data_arr,$data->total(),$data->perPage(),$data->currentPage(),[
            'path'=>$request->url(),
            'query'=>$request->query()
        ]);

        return response()->json(['success'=>true, 'message'=>'Users fetched successfully', 'data'=>$data]);
    }

    public function usersData(Request $request,$organization_id=null){
        //echo $organization_id;
        $user=\auth()->user();
        if( !$user->userCan('universal/users/page-access') ){

            $response = array(
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            );
            return response()->json($response);
        }
        
 //       if ( !$request->filled('organization_id') ){
 //           $organization = auth()->user()->organization;
 //       }
        if(empty($organization_id)){
            $organization = $user->organization;
        }
        else{
            $organization = Organization::find(CustomEncoder::urlValueDecrypt($organization_id));
            if(!$organization){
                $response = array(
                    "draw" => 0,
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => []
                );
                return response()->json($response);
            }
        }
        //var_dump($organization);
        $users = User::select([
            'users.*',
            'demographic_user_data.timezone as timezone',
            'demographic_user_data.phone as phone',
            'demographic_user_data.city as city',
            'demographic_user_data.province as province',
            'demographic_user_data.department as department',
            'demographic_user_data.job_title as job_title',
            'creator.firstname as creator_firstname',
            'creator.lastname as creator_lastname'
        ])->leftJoin('users as creator','creator.id','=','users.created_by')
        ->join('users_organizations','users_organizations.user_id','=','users.id');
        $users = $users->where('users_organizations.organization_id',$organization->id);
        $users = $users->leftJoin('role_user','role_user.user_id','=','users.id')
            ->leftJoin('roles','role_user.role_id','=','roles.id')
            ->leftJoin('demographic_user_data',function ($join) use($organization){
                $join->on('users.id','=','demographic_user_data.user_id');
                $join->on('demographic_user_data.organization_id','=',$organization->id);
            });
           // ->leftJoin('demographic_user_data','users.id','=','demographic_user_data.user_id');
        $users = $users->whereIn('users.account_status',systemActiveUsersStatuses())
        ->groupBy('users.id');
//        $users = $users->whereNotIn('users.id',[auth()->id()]);

        ## Read value
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length: 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        $totalRecords = with(clone $users)->get()->count();

        $search_columns = [
            "name",
            "email",
            "role",
            "account_opening_date",
            "account_status",
            "last_login",
            "creator",
            "action"
        ];
        
        if( !empty($searchValue) ) {
            $users = $users->where(function ($query) use ($searchValue, $search_columns) {
                $query->where(DB::raw("CONCAT('users.firstname',' ','users.lastname')"), 'like', '%' . $searchValue . '%');
                foreach ($search_columns as $search_column) {
                    switch ($search_column) {
                        case 'name':
                            $query->orWhere(DB::raw("CONCAT('users.firstname',' ','users.lastname')"), 'like', '%' . $searchValue . '%');
                            break;
                        case 'role':
                            $query->orWhere('roles.name', 'like', '%' . $searchValue . '%');
                            break;
                        case 'email':
                        case 'account_status':
                            $query->orWhere('users.'.$search_column, 'like', '%' . $searchValue . '%');
                            break;
                        case 'account_opening_date':
                        case 'last_login':
                            try {
                                $date = Carbon::createFromFormat(Constants::DATE_FORMAT,$searchValue);
                                $date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT);
                                $query->orWhere('users.account_opening_date', 'like', '%' . $date_in_utc . '%')
                                    ->orWhere('users.last_login', 'like', '%' . $date_in_utc . '%');
                            } catch (\Exception $exception) {
                                $query->orWhere('users.account_opening_date', 'like', '%' . $searchValue . '%')
                                    ->orWhere('users.last_login', 'like', '%' . $searchValue . '%');
                            }
                            break;
                    }
                }
            });
        }

        if(!$columnIndex && !is_numeric($columnIndex)){
            $users = $users->orderBy('id','DESC');
        }else{
            switch ($columnName){
                case 'name':
                    $users = $users->orderBy(DB::raw("CONCAT('users.firstname',' ','users.lastname')"),strtoupper($columnSortOrder));
                    break;
                case 'role':
                    $users = $users->orderBy('roles.name', strtoupper($columnSortOrder));
                    break;
                case 'email':
                case 'account_opening_date':
                case 'account_status':
                case 'last_login':
                    $users = $users->orderBy('users.'.$columnName,strtoupper($columnSortOrder));
                    break;
            }
        }

        $data = $users;

        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $roles = Role::whereNotIn('roles.name',unfetchableRoles())->where('for_system_admin',false)->get();

        $user = auth()->user();
        foreach($data as $record){

            $organization_field=$user->is_super_admin ?
                '<input type="hidden" name="organization_id" value="'.$organization->id.'"/>' : "";

            $role_name = $record->role_name;
            $role_id = $record->role_id;
            $timezone_prof = $record->timezone;
            $city = $record->city;
            $department = $record->department;
            $job_title = $record->job_title;
            $province_prof = $record->province;
            $phone = $record->phone;
            if($user->is_super_admin){
                $record_role=$record->role($organization)->first();
                $record_demographic=$record->demographicData($organization)->first();
                $timezone_prof = $record_demographic ? $record_demographic->timezone : null;
                $province_prof = $record_demographic ? $record_demographic->province : null;
                $job_title = $record_demographic ? $record_demographic->job_title : null;
                $department = $record_demographic ? $record_demographic->department : null;
                $city = $record_demographic ? $record_demographic->city : null;
                $role_id = $record_role ? $record_role->id : null;
                $role_name = $record_role ? $record_role->display_name : null;
                $phone = $record_role ? $record_demographic->phone : null;
            }

            $action ="";
            if($user->userCan('universal/users/edit-users')) {
                $action = '<div id="edit-user-' . $record->id . '" class="modal fade hide userModal" role="dialog">

        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Update User</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" autocomplete="off" class="CreateUser">
                    <input type="hidden" name="user_id" value="'.$record->id.'" />
                       '.csrf_field().'

                        <div class="row">
                            <div class="col-md-6 well">
                                <h5>First Name</h5>
                                <div class="form-group">
                                    <input type="text" value="' . $record->firstname . '" id="firstname" name="firstname" onkeyup="checkFirstnameLength(this)" placeholder="Enter First Name" maxlength="26" minlength="1"  class="form-control col-md-12 edit_firstname" required/>
                                </div>
                                <div class="edit_firstnameError"></div>
                                <h5>Last Name</h5>
                                <div class="form-group">
                                    <input type="text" value="' . $record->lastname . '" id="lastname" name="lastname" onkeyup="checkLastnameLength(this)" placeholder="Enter Last Name" maxlength="26" minlength="0"  class="form-control col-md-12 edit_lastname" required/>
                                </div>
                                <div class="edit_lastnameError" ></div>
                                <h5>Phone</h5>
                                <div class="form-group">
                                    <input type="text" value="' . $phone . '" id="phone" name="phone" onkeyup="checkPhoneLength(this)" placeholder="Enter Phone" maxlength="11" class="form-control col-md-12 edit_phone" required/>
                                </div>
                                <div class="edit_phoneError" ></div>
                                <h5>Email</h5>
                                <div class="form-group">
                                    <input type="email" value="' . $record->email . '" id="email" maxlength="51"  name="email" onkeyup="checkEmailLength(this)" placeholder="Enter Email"  class="form-control col-md-12 edit_email" required/>
                                </div>
                                <div class="edit_emailError" ></div>
                                 <h5>Department</h5>
                                <div class="form-group">
                                    <input type="text" value="' . $department . '" id="department" maxlength="51" name="department" onkeyup="checkDepartmentLength(this)" placeholder="Enter Department"  class="form-control col-md-12 edit_department" required/>
                                </div>
                                <div class="edit_departmentError" ></div>
                                
                                </div>
                                <div class="col-md-6 well">
                                 
                                <h5>Job Title</h5>
                                <div class="form-group">
                                    <input type="text" value="' . $job_title . '" id="job_title" name="job_title" onkeyup="checkJobTitleLength(this)" maxlength="51" placeholder="Enter Job Title"  class="form-control col-md-12 edit_job_title" required/>
                                </div>
                                <div class="edit_jobtitleError" ></div>
                                <h5>City</h5>
                                <div class="form-group">
                                    <input type="text" value="' . $city . '" id="city" name="city"  onkeyup="checkCityLength(this)" placeholder="Enter City" maxlength="51"  class="form-control col-md-12 edit_city" required/>
                                </div>
                                <div class="edit_cityError" ></div>
                                <h5>Province</h5>
                                <div class="form-group">
                            
                                    <select name="province" id="province" class="form-control select2" required>
                                        <option value="">Select Province</option>';

                $provinces = provinces();

                foreach ($provinces as $province):
                    $action .= '<option ' . ($province_prof == $province ? "selected" : "") . ' value="' . $province . '">' . $province . '</option>';
                endforeach;

                $action .= '</select>
                                </div>
                                 <h5>Timezone</h5>
                                <div class="form-group">
                
                                    <select name="timezone" id="timezone" class="form-control select2" required>
                                        <option value="">Select Timezone</option>';

                $timezones = timezonesList();

                foreach ($timezones as $key => $timezone):
                    $action .= '<option ' . ($timezone_prof == $key ? "selected" : "") . ' value="' . $key . '">' . $timezone . '</option>';
                endforeach;

                $action .= '</select>
                                </div>' . $organization_field . '<h5>Roles</h5>
                                <div class="form-group">
                                    
                                    <select name="role_id" class="form-control select2" required>
                                        <option value="">Select Role</option>';

                foreach ($roles as $role):
                    $action .= '<option ' . ($role->id == $role_id ? "selected" : "") . ' value="' . $role->id . '">' . $role->display_name . '</option>';
                endforeach;

                $action .= '</select>
                                </div>
                                </div>
                                </div>

                            <div class="row" align="center">
                                <div class="col-md-12 well">

                                <div class="form-group">
                                    <input type="button" class="btn custom-secondary round" data-dismiss="modal" value="Cancel">
                                    <input type="submit" class="btn custom-primary round mmy_btn CreateUserSubmitBtn" value="Submit" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>';
            }

          $user_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
          $action1='<div class="dropdown">
                        <button class="btn custom-primary round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

          $can_update_user = $user->userCan('universal/users/create-users') || $user->userCan('universal/users/edit-users') ;

              if($user->userCan('universal/users/edit-users')) {
                  $action1 .= ' <a class="dropdown-item"  href="javascript:void()" data-toggle="modal" data-target="#edit-user-' . $record->id . '">Edit</a> ';
              }

            if( $user->userCan('universal/users/suspend/activate-users') ) {
                if ($can_update_user && in_array($record['account_status'], ["ACTIVE"])) {
                    $action1 .= '<a class="dropdown-item org-action-to-user" href="' . route('org.update-users-status', ['action' => 'suspend', 'user_id' => $user_id_encoded]) . '">Suspend</a>';
                }
                if ($can_update_user && in_array($record['account_status'], ["SUSPENDED"])) {
                    $action1 .= '<a class="dropdown-item org-action-to-user" href="' . route('org.update-users-status', ['action' => 'activate', 'user_id' => $user_id_encoded]) . '">Activate</a>';
                }
            }

              if( $user->userCan('universal/users/lock/unlock-users') && in_array($record['account_status'], ["LOCKED"]) ) {
                $action1 .= '<a class="dropdown-item org-action-to-user" href="'.route('org.update-users-status',['action'=>'activate','user_id'=>$user_id_encoded]).'">Unlock</a>';
              }else if($user->userCan('universal/users/lock/unlock-users') && !in_array($record['account_status'], ["LOCKED"])){
                  $action1 .= '<a class="dropdown-item org-action-to-user" href="'.route('org.update-users-status',['action'=>'lock','user_id'=>$user_id_encoded]).'">Lock</a>';
              }

              if($user->userCan('universal/users/close/delete-users')) {
                 $action1 .= '<a class="dropdown-item" href="javascript:void()" user-id="' .CustomEncoder::urlValueEncrypt($record->id). '" onclick="return closeUser(this)">Close</a>';
              }

          $action1.='</div>';
          $action1.='</div>';
          $action1.=$action;

            $data_arr[] = array(
                "name"=> ( !empty($this->firstname) || !empty($this->lastname) ) ? ucwords($record->firstname).' '.ucwords($record->lastname) : $record->name,
                "email"=>$record->email,
                "role"=>$role_name=="Organization Administrator" ? "Admin" : $role_name,
                "account_opening_date"=>$record->account_opening_date ? changeDateFromUTCtoLocal($record->account_opening_date) : '-',
                "account_status"=>$record->account_status,
                "last_login"=>$record->last_login ? changeDateFromUTCtoLocal($record->last_login) : '-',
                "creator"=>ucwords($record->creator_firstname).' '.ucwords($record->creator_lastname),
                "action" =>$record->id ==auth()->id() ? " " : $action1
            );
            
        }
        
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function roles(Request $request){
        $user=\auth()->user();
        if($user->is_super_admin){
            return view('dashboard.users.roles.index');
        }

        if(!$user->userCan('admin/roles/page-access')){
            return view('dashboard.users.roles.index');
        }

        return view('dashboard.403');
    }

    public function rolesData(Request $request){
        $user = auth()->user();
        if(!$user->is_super_admin){

            $response = array(
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            );
            return response()->json($response);
        }

        if(!$user->userCan('admin/roles/page-access')){
            $response = array(
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            );
            return response()->json($response);
        }

        $roles = Role::select([
            'roles.*',
            'users.name as creator_name',
            DB::raw("(SELECT COUNT(role_user.user_id) FROM role_user,users u WHERE role_user.role_id=roles.id AND u.id=role_user.user_id AND u.account_status!='CLOSED') as assigned_users"),
            DB::raw("(SELECT COUNT(permission_role.permission_id) FROM permission_role WHERE permission_role.role_id=roles.id) as role_permissions")
        ])->leftJoin('users','users.id','=','roles.created_by');

//        $roles = $roles->whereNotIn('roles.name',unfetchableRoles());

//        $roles = $roles->where('roles.organization_id',\auth()->user()->organization_id); for admins

        ## Read value
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length: 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        $totalRecords = with(clone $roles)->get()->count();

        $search_columns = [
            "role_name",
            "assigned_users",
            "role_permissions",
            "creator",
            "action"
        ];

        if( !empty($searchValue) ) {
            $roles = $roles->where(function ($query) use ($searchValue, $search_columns) {
                $query->where('roles.name', 'like', '%' . $searchValue . '%');
                foreach ($search_columns as $search_column) {
                    switch ($search_column) {
                        case 'creator':
                            $query->orWhere('users.name', 'like', '%' . $searchValue . '%');
                            break;
                    }
                }
            });
        }

        if(!$columnIndex && !is_numeric($columnIndex)){
            $roles = $roles->orderBy('id','DESC');
        }else{
            switch ($columnName){
                case 'creator':
                    $roles = $roles->orderBy('users.name',strtoupper($columnSortOrder));
                    break;
                case 'role_name':
                    $roles = $roles->orderBy('roles.name',strtoupper($columnSortOrder));
                    break;

            }
        }

        $data = $roles;

        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach($data as $record){
            $action='<div id="edit-role-'.$record->id.'" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Update Role</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" autocomplete="off" id="CreateRole">
                    <input type="hidden" name="role_id" value="'.$record->id.'" />
                       '.csrf_field().'
                        <div class="row">
                            <div class="col-md-12 well">
                                <h5>Role Name</h5>
                                <div class="form-group">
                                    <input type="text" id="role_name" name="role_name" placeholder="Enter Role Name"  class="form-control col-md-12" value="'.$record->display_name.'" />
                                </div>
                                
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label style="font-weight: normal"> For System Admin ? <input type="checkbox" '.($record->for_system_admin == 1 ? "checked" : "").' name="for_system_admin" /></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="button" class="btn custom-secondary round" data-dismiss="modal" value="Cancel">
                                    <input type="submit" class="btn custom-primary round mmy_btn CreateRoleSubmitBtn" value="Submit" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>';

            $role_id=CustomEncoder::urlValueEncrypt($record->id);
            $action1 = '';
            if(!in_array($record->name,readOnlyRoles())) {
                $action1 .= ' <a href="' . route('admin.roles.permissions', $role_id) . '" class="btn btn-info mmy_btn btn-md">Permissions</a>';
            }
            $action1 .= ' <a href="" data-toggle="modal" data-target="#edit-role-' . $record->id . '" class="btn custom-primary round mmy_btn btn-md">Edit</a> ';
            $action1 .= (($record->assigned_users > 0 /*|| $record->role_permissions > 0*/) ? "" : ' <a href="' . route('admin.roles.delete', $role_id) . '" class="btn btn-danger mmy_btn btn-md delete-role-confirm">Delete</a>');

            $data_arr[] = array(
                "role_name" => ucwords($record->display_name).' '.($record->for_system_admin == 1 ? '<span class="badge badge-success">System Admin</span>' : ''),
                "assigned_users" => $record->assigned_users,
                "role_permissions" => $record->role_permissions,
                "creator" => $record->creator_name,
                "action" => $action1.$action,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function deleteUser(Request $request){
        $logged_in_user = auth()->user();
        $id = request('id');
        // dd($id);
        if(!$logged_in_user->userCan('universal/users/close/delete-users')){
            $response = array("success"=>false, "message"=>"access-denied", "data"=>[]);
            return response()->json($response,403);
        }

        if (empty($id)) {
            $response = array("success"=>false, "message"=>"User id is required", "data"=>[]);
            return response()->json($response);
        }

        $id = CustomEncoder::urlValueDecrypt($id);
        $user = User::find($id);
        if (!$user /*|| $user->organization->id != $logged_in_user_organization->id*/) {
            $response = array("success"=>false, "message"=>"User not found", "data"=>[]);
            return response()->json($response);
        }
        $user_organization = $user->organization;
        $user_allowed_organizations = $user->allowedOrganizations();

        $remove_user_from_organization=false;
        if(!$logged_in_user->is_super_admin) {
            $logged_in_user_organization = $logged_in_user->organization;

            if( $user_organization && $user_organization->allow_multi_organizations ){
                if( in_array($logged_in_user_organization->id,$user_allowed_organizations->toArray()) ){
                    $remove_user_from_organization=$user_allowed_organizations->count() > 1;
                }
            }

        }else{
            if( $user_organization && $user_organization->allow_multi_organizations ){
                $remove_user_from_organization=$user_allowed_organizations->count() > 1;
            }
        }

        if(!$remove_user_from_organization) {
            $user->update([
                'account_status' => 'CLOSED'
            ]);

            $subject = "Your Yield Exchange account has been closed.";
            $header = "Account closed";

            $message = "Your Yield Exchange account has been closed. ";
            $message .= "Please contact us at <a href='mailto:info@yieldexchange.ca'>info@yieldexchange.ca</a> for more information.";

            Mail::to([$user->email])->queue(new AdminUserActionMail([
                'message' => $message,
                'header' => $header,
                'user_type' => get_user_type($user),
                'subject' => $subject
            ]));
            systemActivities(Auth::id(), json_encode($request->query()), "Closed organization user account");
        }else{
           UserOrganization::where('user_id',$user->id)->where('organization_id',$user_organization->id)->delete();
            $subject = "Your Yield Exchange account access to ".$user_organization->name.' has been revoked';
            $header = "Access revoked";

            $message = $subject;
            $message .= " Please contact us at <a href='mailto:info@yieldexchange.ca'>info@yieldexchange.ca</a> for more information.";

        Mail::to([$user->email])->queue(new AdminUserActionMail([
            'message' => $message,
            'header' => $header,
            'user_type' => get_user_type($user),
            'subject'=> $subject
        ]));

           systemActivities(Auth::id(), json_encode($request->query()), "Deleted user from organization access for multi-org");
        }

        $response = array("success"=>true, "message"=>"User closed successfully", "data"=>[]);
        return response()->json($response);
    }

    public function deleteRole(Request $request,$id)
    {
        $user = auth()->user();
        if(!$user->is_super_admin){
            $response = array("success"=>false, "message"=>"Access Denied", "data"=>[]);
            return response()->json($response, 403);
//            return redirect()->to('access-denied');
        }

        if( !$user->userCan('admin/roles/delete') ){
            $response = array("success"=>false, "message"=>"Access Denied", "data"=>[]);
            return response()->json($response, 403);
        }

        if (empty($id)) {
//            alert()->error("Role id is required");
//            return redirect()->back();
            $response = array("success"=>false, "message"=>"Role id is required", "data"=>[]);
            return response()->json($response, 400);
        }

        $id = CustomEncoder::urlValueDecrypt($id);
        $role = Role::select([
            'roles.*',
            DB::raw("(SELECT COUNT(role_user.user_id) FROM role_user,users u WHERE role_user.role_id=roles.id AND u.id=role_user.user_id AND u.account_status!='CLOSED') as assigned_users"),
            DB::raw("(SELECT COUNT(permission_role.permission_id) FROM permission_role WHERE permission_role.role_id=roles.id) as role_permissions")
        ])->find($id);

        if (!$role || !auth()->user()->is_super_admin /*$role->organization_id != auth()->user()->organization_id*/){
            $response = array("success"=>false, "message"=>"Role not found", "data"=>[]);
            return response()->json($response, 400);
        }

        if (in_array($role->name,readOnlyRoles())){
            $response = array("success"=>false, "message"=>"Roles can not be deleted. Its read only", "data"=>[]);
            return response()->json($response, 400);
        }

        if($role->assigned_users > 0 /*|| $role->role_permissions > 0*/){
//            alert()->error("Unable to delete this role, some user or permissions are using it");
//            return redirect()->back();
            $response = array("success"=>false, "message"=>"Unable to delete this role, some user or permissions are using it", "data"=>[]);
            return response()->json($response, 400);
        }

        $role->delete();

//        alert()->success("Role deleted");
//        return redirect()->back();
        systemActivities(Auth::id(), json_encode($request->query()), "Deleted Role");
        $response = array("success"=>false, "message"=>"Role deleted", "data"=>[]);
        return response()->json($response, 200);
    }

    public function rolesCreate(Request $request){
        $user = auth()->user();
        if(!$user->is_super_admin){
            $response = array("success"=>false, "message"=>"Access Denied", "data"=>[]);
            return response()->json($response, 403);
        }

        if( !$user->userCan('admin/roles/create') || !$user->userCan('admin/roles/edit') ){
            $response = array("success"=>false, "message"=>"Access Denied", "data"=>[]);
            return response()->json($response, 403);
        }

        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        $role_exits = Role::where('name',trim($request->display_name));
        if ($request->filled('role_id')) {
            $role_exits = $role_exits->where('id','!=',$request->role_id);
        }

        $organization = auth()->user()->organization;
        if($organization) {
            $role_exits = $role_exits->where('organization_id', $organization->id);
        }
        $role_exits = $role_exits->first();

        if($role_exits){
            $response = array("success"=>false, "message"=>"Role already exists", "data"=>[]);
            return response()->json($response, 409);
        }

        if ($request->filled('role_id')){

            $role = Role::where('id',$request->role_id)->first();
            if (!$role || in_array($role->name,readOnlyRoles())){
                $response = array("success"=>false, "message"=>"Roles can not be updated. Its read only", "data"=>[]);
                return response()->json($response, 400);
            }

            $role->update([
                'display_name' => $request->role_name,
                'description' => $request->role_name,
                'organization_id' => 0, // when it is zero, it will be accessed globally
                'created_by' => auth()->id(),
                'for_system_admin'=> $request->filled('for_system_admin')
            ]);

            $response = array("success"=>true, "message"=>"Role updated", "data"=>[]);
            return response()->json($response, 200);
        }

        $created_role = Role::create([
            'name' => time().'-'.str_replace(" ", "-", strtolower($request->role_name)) . '-' . ($organization ? $organization->id : ''),
            'display_name' => $request->role_name,
            'description' => $request->role_name,
            'organization_id' => 0,// when it is zero, it will be accessed globally
            'created_by' => $user->id,
            'for_system_admin'=> $request->filled('for_system_admin')
        ]);

        if (!$created_role){
            $response = array("success"=>false, "message"=>"Failed, unable to create the role", "data"=>[]);
            return response()->json($response, 400);
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Created Role");
        $response = array("success"=>true, "message"=>"Role created", "data"=>[]);
        return response()->json($response, 200);
    }

    public function usersCreate(Request $request){
        $user = auth()->user();
        if(!$user->userCan('universal/users/create-users')){
            $response = array("success"=>false, "message"=>"Access Denied", "data"=>[]);
            return response()->json($response, 403);
        }

        $validation_data=[
            'firstname' => 'required|string|max:25',
            'lastname' => 'required|string|max:25',
            'timezone' => 'required|string',
            'email' => 'required|email|max:50',
            'department'=>'nullable|max:50',
            'job_title' => 'required|string|max:50',
            'role_id' => 'required|integer'
        ];
        $setApprovalLimit = false;
        if(request()->has('minimumLimit') && request()->has('maximumLimit')) {
            $validation_data['minimumLimit'] = 'required';
            $validation_data['maximumLimit'] = 'required';
            $setApprovalLimit = true;
        }


        if ($user->is_super_admin){
            $validation_data['organization_id']='required|integer|min:0';
        }

        $validator = Validator::make($request->all(), $validation_data);
        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        if ($request->filled('user_id')){
            if(!User::find($request->user_id)){
                $response = array("success"=>false, "message"=>"User not found", "data"=>[]);
                return response()->json($response, 400);
            }
        }

        if ($request->filled('organization_id')){
            $organization=Organization::find($request->organization_id);
            if (!$organization){
                $response = array("success"=>false, "message"=>"Organization not found", "data"=>[]);
                return response()->json($response, 400);
            }
        }else{
            $organization = $user->organization;
        }
        $email = trim(strtolower($request->email));
        $user_exists = User::select([
            'users.*'
        ])->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('account_status',systemActiveUsersStatuses())
            ->where('email',$email)
            ->whereIn('organizations.type',['BANK','DEPOSITOR']);
        if ($request->filled('user_id')){
            $user_exists = $user_exists->where('users.id','!=',$request->user_id);
        }
        $user_exists = $user_exists->first();

//        $user_exists = User::where('email',trim($request->email))
//            ->whereIn('account_status',systemActiveUsersStatuses());
//        if ($request->filled('user_id')){
//            $user_exists = $user_exists->where('id','!=',$request->user_id);
//        }
//        $user_exists = $user_exists->first();

        if($user_exists && !can_switch_to_organizations($user_exists,$organization) && !$request->filled('user_id') ){
            $response = array("success"=>false, "message"=>"Email already exists", "data"=>[]);
            return response()->json($response, 409);
        }

        if(!$request->filled('user_id') && $user_exists) {
            $organizations =$user_exists->allowedOrganizations();
            $allowed_organizations = !empty($organizations) ? $organizations->pluck('id')->toArray() : [];
            if (can_switch_to_organizations($user_exists) && in_array($organization->id, $allowed_organizations)) {
                $response = array("success" => false, "message" => "User already belongs to the organization", "data" => []);
                return response()->json($response, 400);
            }
        }

        $role = Role::find($request->role_id);
        if(!$role){
            $response = array("success"=>false, "message"=>"Role does not exist", "data"=>[]);
            return response()->json($response, 400);
        }
        
        if($request->filled('user_id')){
            $user = User::find($request->user_id);
            if (!$user){
                $response = array("success"=>true, "message"=>"User not found", "data"=>[]);
                return response()->json($response, 400);
            }

            $user->update([
                'name' => $request->firstname.' '.$request->lastname,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $email,
                'is_test'=> $organization->is_test,
                'modified_by'=> auth()->user()->id,
                'modified_date' => getUTCDateNow()
            ]);

            $user_role = DB::table('role_user')
                ->where('user_id',$user->id)
                ->where('organization_id',$organization->id)
                ->first();
            if($user_role) {
                DB::table('role_user')
                    ->where('user_id',$user->id)
                    ->where('organization_id',$organization->id)
                    ->update([
                    'role_id' => $role->id,
                    'user_type' => $role->display_name
                ]);
            }else{
                DB::table('role_user')->insert([
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                    'user_type' => $role->display_name,
                    'organization_id' => $organization->id
                ]);
            }
            if(request()->has('minimumLimit') && request()->has('maximumLimit')) {
                UserApprovalLimit::updateOrCreate(['user_id' => $request->user_id],[
                    'minimumLimit' => request('minimumLimit'),
                    'maximumLimit' => request('maximumLimit'),
                    'endDate' => request()->has('endDate') ? request('endDate') : null,
                    'startDate' => request()->has('startDate') ? request('startDate') : null,
                    'status' => request()->has('status') ? "Active" : "InActive",
                ]);
            }
//            $user->roles()->sync([$role->id]);<

            $user_demo = UsersDemoGraphicData::where('user_id',$request->user_id)
                ->where('organization_id',$organization->id)
                ->first();
            if ($user_demo) {
                $user_demo->update([
                    'phone' => $request->phone,
                    'department' => $request->department,
                    'timezone' => $request->timezone,
                    'job_title' => $request->job_title,
                    'city' => $request->city,
                    'province' => $request->province,
                    'updated_at' => getUTCDateNow()
                ]);
            }else{
                UsersDemoGraphicData::create([
                    'phone' => $request->phone,
                    'department' => $request->department,
                    'timezone' => $request->timezone,
                    'job_title' => $request->job_title,
                    'city' => $request->city,
                    'province' => $request->province,
                    'updated_at' => getUTCDateNow(),
                    'user_id' => $user->id,
                    'organization_id'=>$organization->id
                ]);
            }

            $response = array("success"=>true, "message"=>"User updated", "data"=>[]);
            return response()->json($response, 200);
        }

        if($organization->organization_users_limit <= $organization->users_count){
            $response = array("success"=>false, "message"=>"You have reached the users limit", "data"=>[]);
            return response()->json($response, 400);
        }

        try {
            Db::beginTransaction();
            if(!$user_exists) {
                $created_user = User::create([
                    'name' => $request->firstname . ' ' . $request->lastname,
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'email' => $email,
                    'created_by' => auth()->user()->id,
                    'is_test' => $organization->is_test,
                    'account_opening_date' => getUTCDateNow(),
                    'account_status' => 'ACTIVE',
                    'is_non_partnered_fi' => false,
                    'failed_login_attempts' => 0,
                    'requires_password_update'=>true
                ]);
            }else{
                $created_user = $user_exists;
            }

            if (!$created_user) {
                $response = array("success" => false, "message" => "Failed, unable to create the user", "data" => []);
                return response()->json($response, 400);
            }

             UsersDemoGraphicData::create([
                'user_id' =>$created_user->id,
                'phone' => $request->phone,
                'department' => $request->department,
                'timezone' => $request->timezone,
                'job_title' => $request->job_title,
                'city' => $request->city,
                'province' => $request->province,
                'modified_date' => getUTCDateNow()
            ]);

            // link users to organizations
            UserOrganization::create([
                'user_id' => $created_user->id,
                'organization_id' => $organization->id,
                'status' => 'ACTIVE',
                'switched_organization_type' => NULL
            ]);

            if(request()->has('minimumLimit') && request()->has('maximumLimit')) {
                UserApprovalLimit::create([
                    'user_id' => $created_user->id,
                    'minimumLimit' => request('minimumLimit'),
                    'maximumLimit' => request('maximumLimit'),
                    'endDate' => request()->has('endDate') ? request('endDate') : null,
                    'startDate' => request()->has('startDate') ? request('startDate') : null,
                    'status' => "Active",
                ]);
            }

            if(!$user_exists) {
                $password_ = getRandomNumberBetween(90000, 9999999);
                $password = password_hash($password_, PASSWORD_BCRYPT);
                UserPassword::create([
                    'hash' => $password,
                    'created_at' => getUTCDateNow(),
                    'user_id' => $created_user->id
                ]);

                $preference = Preference::where("name", "mute_notification")->first();
                if ($preference) {
                    UserPreference::create([
                        'value' => 0,
                        'preference_id' => $preference->id,
                        'user_id' => $created_user->id
                    ]);
                }
            }

            DB::table('role_user')->insert([
                'role_id' => $role->id,
                'user_id' => $created_user->id,
                'user_type' => $role->display_name,
                'organization_id' => $organization->id
            ]);

//            $user->roles()->attach([$role->id]);

            try {
                Artisan::call("cache:clear");
            }catch (\Exception $exception){}

            if(!$user_exists) {
                Mail::to($created_user->email)->queue(new NewUserPasswordMail([
                    'password' => $password_,
                    'user_type' => get_user_type($created_user)
                ]));
            }

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            $timestamp= time();
            Log::error($timestamp.': '.$exception->getTraceAsString());
            loginActivities("User sign up attempt failed, check with the developer. Error No: ".$timestamp, $request->server('HTTP_USER_AGENT'), 0);
            $response = array("success"=>true, "message"=>"Unable to create user", "data"=>[]);
            return response()->json($response, 400);
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Created User");
        $response = array("success"=>true, "message"=>"User created", "data"=>[]);
        return response()->json($response, 200);
    }

    public function rolePermissions(Request $request,$role_id){
        $user= auth()->user();
        if(!$user->is_super_admin){
            return view('dashboard.403');
        }

        if( !$user->userCan('admin/roles/assign-permissions') ){
            return view('dashboard.403');
        }

        $role = Role::find(CustomEncoder::urlValueDecrypt($role_id));
        if(!$role){
            alert()->error("Role does not exist");
            return back();
        }
        $user_group=["ALL"];
        $organization = $user->organization;
        if($user->is_super_admin) {
            array_push($user_group,"BANK","DEPOSITOR","UNIVERSAL","ADMIN");
        }else if($organization->type=="BOTH"){
            array_push($user_group,"BANK");
            array_push($user_group,"DEPOSITOR");
        }else{
            array_push($user_group,$user->type);
        }

        $permissions=PermissionsGroup::whereIn('user_group',$user_group)->groupBy('permissions_group.id')->orderBy('name','ASC')->get();
        $permission_user_groups = collect($permissions)->unique('user_group')
            ->sortByDesc('user_group')->reverse()
            ->pluck('user_group')
            ->toArray();
        $user = auth()->user();
        return view('dashboard.users.permissions',compact('permissions','role','user','permission_user_groups'));
    }

    public function assignRolePermission(Request $request){
    //    dd($request->all());
        $user= auth()->user();
        if(!$user->is_super_admin){
            return view('dashboard.403');
//            return redirect()->back()->with('error', "Access Denied");
        }

        if( !$user->userCan('admin/roles/assign-permissions') ){
            return view('dashboard.403');
        }

        $validator = Validator::make($request->all(), [
            'role_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            alert()->error((string)Arr::flatten($validator->messages()->get('*')));
            return back();
//            return redirect()->back()->with('error', Arr::flatten($validator->messages()->get('*')));
        }

        $role = Role::find($request->role_id);
        if(!$role){
            alert()->error("Role does not exist");
            return back();
//            return redirect()->back()->with('error', "Role does not exist");
        }

        if($role->name == 'organization-administrator' || $role->name == 'system-administrator'){
            alert()->error("Role can not be updated, it's read only.");
            return back();
        }

        $role->permissions()->sync($request['permissions']);
        try {
            Artisan::call("cache:clear");
        }catch (\Exception $exception){}
        systemActivities(Auth::id(), json_encode($request->query()), "Role assigned permissions successfully!");
        alert()->success("Role assigned permissions successfully!");
        return back();
//        return redirect()->back()->with('success', "Role assigned permissions successfully!");
    }

    public function doAction(Request $request, $action){
        $user = \auth()->user();
        $user_data = User::find(CustomEncoder::urlValueDecrypt($request->user_id));
        if(!$user_data){
            $response = ['data'=>[],'message'=>'User not found', 'success'=>false];
            return response()->json($response, 400);
        }

        switch ($action){
            case "lock":
                if( !$user->userCan('universal/users/lock/unlock-users') ) {
                    $response = ['data'=>[],'message'=>'Permission denied', 'success'=>false];
                    return response()->json($response, 400);
                }
                systemActivities(Auth::id(), json_encode($request->query()), "Admin locked an account");
                $subject ="Account locked";
                $header ="Your account has been locked.";
                $message = "To unlock your account or for more information on ";
                $message .="why your account has been locked please contact your organization admin or <a href='mailto:nfo@yieldexchange.ca'>info@yieldexchange.ca.</a>";

                $user_data->account_status='LOCKED';
                archiveTable($user_data->id,'users',$user->id,'Admin Control > LOCKED');
                $user_data->save();

                Mail::to([$user_data->email])->queue(new AdminUserActionMail([
                    'message' => $message,
                    'header' =>$header,
                    'user_type' => get_user_type($user_data),
                    'subject'=> $subject
                ]));

                $response_message="Account locked successfully";
                break;
            case "activate":
                if( !$user->userCan('universal/users/suspend/activate-users') ) {
                    $response = ['data'=>[],'message'=>'Permission denied', 'success'=>false];
                    return response()->json($response, 400);
                }
                $action="Unlock";
                if( $user_data->account_status == "SUSPENDED") {
                    $action="Unsuspend";
                    systemActivities(Auth::id(), json_encode($request->query()), "Admin unsuspended an account");
                    $subject = "Your account is activated";
                    $header = "Your account has been re-activated.";
                    $message = "Your Yield Exchange account is ready to be used.";
                }else{
                    systemActivities(Auth::id(), json_encode($request->query()), "Admin unlocked an account");
                    $subject = "Unlocked account";
                    $header = "Your account has been unlocked.";
                    $message = "Your account has been unlocked and is ready for use.";
                }
                
                $user_data->account_status='ACTIVE';
                $user_data->failed_login_attempts=0;
                archiveTable($user_data->id,'users',$user->id,'Admin Control > '.$action.' Account');
                $user_data->save();

                Mail::to([$user_data->email])->queue(new AdminUserActionMail([
                    'message' => $message,
                    'header' =>$header,
                    'user_type' => get_user_type($user_data),
                    'subject'=> $subject,
                    'show_login'=>true
                ]));

                $response_message="Account ".$action." successfully";
 
                break;
            case "suspend":
                if( !$user->userCan('universal/users/suspend/activate-users') ) {
                    $response = ['data'=>[],'message'=>'Permission denied', 'success'=>false];
                    return response()->json($response, 400);
                }
                systemActivities(Auth::id(), json_encode($request->query()), "Admin suspended an account");
                $subject ="Account suspended";
                $header ="Your account has been suspended.";
                $message = "To re-activate your account or for more information on ";
                $message .="why your account has been suspended please contact your organization admin or <a href='mailto:nfo@yieldexchange.ca'>info@yieldexchange.ca.</a>";

                $user_data->account_status='SUSPENDED';
                archiveTable($user_data->id,'users',$user->id,'Admin Control > SUSPEND');
                $user_data->save();

                Mail::to([$user_data->email])->queue(new AdminUserActionMail([
                    'message' => $message,
                    'header' =>$header,
                    'user_type' => get_user_type($user_data),
                    'subject'=> $subject
                ]));

                $response_message="Account suspended successfully";
                break;
            default:
                $response_message="Unknown action, failed.";
                break;
        }

        if($request->ajax()) {
            $response = ['data' => [], 'message' => $response_message, 'success' => true];
            return response()->json($response, 200);
        }
        alert()->success($response_message);
        return redirect()->back();
    }
}

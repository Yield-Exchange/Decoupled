<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Role;
use App\User;
use App\Constants;
use Carbon\Carbon;
use App\CustomEncoder;
use App\Mail\BankMails;
use Illuminate\Support\Arr;
use App\Models\Organization;
use App\Models\UserPassword;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

use App\Models\DepositRequest;
use App\Models\FICampaignGroup;
use App\Mail\NewUserPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\UsersDemoGraphicData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\AdminUserActionMail;
use Illuminate\Support\Facades\Artisan;
use App\Mail\Bank\RequestInvitationMail;
use Illuminate\Support\Facades\Validator;
use App\Mail\RegistrationMail;
use App\Mail\VerifyConferenceSignupMail;
use App\Models\AWSFileRouting;
use App\Models\OrganizationLevelPermission;
use App\Models\UsersIPAddress;

class ManageUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['loginAsClient']);
        $this->middleware('auth.admin')->except(['loginAsClient']);
    }

    public function loginAsClient(Request $request, $organization_id,$admin,$accesstoken)
    {

        // $ipAddress = getCurrentIp();
        // if($ipAddress==""){
        //     alert()->error("Please try again.Ip address was not fetched successfully.If the issue continues please contact Admin.");
        //     return redirect()->back();
        // }      
        $foundRecord=UsersIPAddress::where("user_id",CustomEncoder::urlValueDecrypt($admin))->where("login_as_admin_token",$accesstoken)->first(); 
        if(!$foundRecord){
            alert()->error("Invalid Request");
            return redirect()->back(); 
        }
        
        $ipAddress = getIp($admin);
        if($ipAddress=="")
        {
            alert()->error("No Ip was fetched");
            return redirect()->back();
        }

        $user = \auth()->user();
        if (!$user->userCan('admin/login-as-client/full-access')) {
            alert()->error("Permission Denied");
            return redirect()->back();
        }

        $organization_id = CustomEncoder::urlValueDecrypt($organization_id);
        $organization = Organization::find($organization_id);
        if (!$organization) {
            alert()->error("Organization not found");
            return redirect()->back();
        }

        $admin_id = $user->id;

        $user = $organization->users()
            ->where('account_status', 'ACTIVE')
            ->whereIn('role_name', ['Organization Administrator', 'Administrator'])->first();
        if (!$user) {
            alert()->error("No ACTIVE organization admin found to login into");
            return redirect()->back();
        }

        \auth()->logout();

        // Retrieve the IP address
        
        $request->session()->put('my_ip', $ipAddress);
        $request->headers->add(['X-User-IP' => $ipAddress]);
        $request->headers->add(['X-Client-IP' => $ipAddress]);
        

        \auth()->loginUsingId($user->id);
        User::where('id', $user->id)->update([
            'admin_loggedin_as' => $admin_id,
            'switched_organization_id' => $organization->id,
            'admin_loggedin_as_agent' => json_encode($request->headers->all())
        ]);
        return redirect()->to('/dashboard');
    }

    public function index(Request $request, $user_type)
    {
        $user = \auth()->user();
        $view = "";
        switch ($user_type) {
            case 'users_onboard':
                if (!$user->userCan('admin/organizations-onboard/page-access')) {
                    return view('dashboard.403');
                }
                $view = "users-onboard";
                break;
            case 'depositors':
                if (!$user->userCan('admin/gic-investors/page-access')) {
                    return view('dashboard.403');
                }
                $view = "depositors";
                break;
            case 'banks':
                if (!$user->userCan('admin/banks/page-access')) {
                    return view('dashboard.403');
                }
                $view = "banks";
                break;
            case 'non_partnered_fi':
                if (!$user->userCan('admin/non-partnered-fi/page-access')) {
                    return view('dashboard.403');
                }
                $view = "non-partnered-fi";
                break;
            case 'admins':
                if (!$user->userCan('admin/manage-admins/page-access')) {
                    return view('dashboard.403');
                }
                $view = "admins";
                break;
            case 'waiting_list':
                if (!$user->userCan('admin/organizations-onboard/page-access')) {
                    return view('dashboard.403');
                }
                $view = "waiting-list";
                break;
            default:
                alert()->error('Page not found');
                return redirect()->back();
                break;
        }
        systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > " . $view);
        return view('dashboard.admin.users.' . $view);
    }

    public function getData(Request $request, $user_type)
    {
        $user = \auth()->user();
        switch ($user_type) {
            case 'users_onboard':
                if (!$user->userCan('admin/organizations-onboard/page-access')) {
                    $response = array(
                        "draw" => intval(0),
                        "iTotalRecords" => 0,
                        "iTotalDisplayRecords" => 0,
                        "aaData" => []
                    );
                    return response()->json($response, 200);
                }
                return $this->usersOnBoardData($request, $user);
                break;
            case 'depositors':
                if (!$user->userCan('admin/gic-investors/page-access')) {
                    $response = array(
                        "draw" => intval(0),
                        "iTotalRecords" => 0,
                        "iTotalDisplayRecords" => 0,
                        "aaData" => []
                    );
                    return response()->json($response, 200);
                }
                return $this->depositorsData($request, $user);
                break;
            case 'banks':
                if (!$user->userCan('admin/banks/page-access')) {
                    $response = array(
                        "draw" => intval(0),
                        "iTotalRecords" => 0,
                        "iTotalDisplayRecords" => 0,
                        "aaData" => []
                    );
                    return response()->json($response, 200);
                }
                return $this->banksData($request, $user);
                break;
            case 'non_partnered_fi':
                if (!$user->userCan('admin/non-partnered-fi/page-access')) {
                    $response = array(
                        "draw" => intval(0),
                        "iTotalRecords" => 0,
                        "iTotalDisplayRecords" => 0,
                        "aaData" => []
                    );
                    return response()->json($response, 200);
                }
                return $this->noPartneredFiData($request, $user);
                break;
            case 'admins':
                if (!$user->userCan('admin/manage-admins/page-access')) {
                    $response = array(
                        "draw" => intval(0),
                        "iTotalRecords" => 0,
                        "iTotalDisplayRecords" => 0,
                        "aaData" => []
                    );
                    return response()->json($response, 200);
                }
                if ($request->draw) {
                    return $this->adminData($request, $user);
                    break;
                } else {
                    return $this->adminDataNew($request, $user);
                    break;
                }
            case 'waiting_list':
                if (!$user->userCan('admin/organizations-onboard/page-access')) {
                    $response = array(
                        "draw" => intval(0),
                        "iTotalRecords" => 0,
                        "iTotalDisplayRecords" => 0,
                        "aaData" => []
                    );
                    return response()->json($response, 200);
                }
                return $this->waitingList($request, $user);
                break;
            default:
                $response = array(
                    "draw" => intval(0),
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => []
                );
                return response()->json($response, 200);
                break;

        }
    }

    private function waitingList($request, $auth_user)
    {
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length : 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $data = User::whereNotNull('is_waiting');

        $totalRecords = with(clone $data)->get()->count();

        $search_columns = [
            "name",
            "email",
            //"tel",
        ];

        if (!empty($searchValue)) {
            $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                $query->where('name', 'like', '%' . $searchValue . '%');
                foreach ($search_columns as $search_column) {
                    switch ($search_column) {
                        case 'email':
                            $query->orWhere('email', 'like', '%' . $searchValue . '%');
                            break;
                            // case 'tel':
                            //     $query->orWhere('demographicData.phone', 'like', '%' . $searchValue . '%');
                            //     break;

                    }
                }
            });
        }

        if (!$columnIndex && !is_numeric($columnIndex)) {
            $data = $data->orderBy('id', 'DESC');
        } else {
            switch ($columnName) {
                case 'name':
                    $data = $data->orderBy('name', strtoupper($columnSortOrder));
                    break;
                    // case 'tel':
                    //     $data = $data->orderBy('demographicData.phone',strtoupper($columnSortOrder));
                    //     break;
                case 'email':
                    $data = $data->orderBy('email', strtoupper($columnSortOrder));
                    break;

                default:
                    $data = $data->orderBy('id', 'DESC');
                    break;
            }
        }


        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        $i = 1;
        foreach ($data as $record) {
            $data_arr[] = array(
                "sno" => $i++,
                "name" => $record->name,
                "email" => $record->email,
                "tel" => $record->demographicData->phone,
                "reason" => str_replace('_', ' ', $record->is_waiting)
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response, 200);
    }

    private function adminDataNew($request, $auth_user)
    {

        $users = User::select([
            'users.*',
            'demographic_user_data.timezone as timezone',
            'demographic_user_data.phone as phone',
            'demographic_user_data.city as city',
            'demographic_user_data.province as province',
            'demographic_user_data.department as department',
            'demographic_user_data.job_title as job_title',
            'creator.firstname as creator_firstname',
            'creator.lastname as creator_lastname',
            'roles.display_name as role_name'
        ])->leftJoin('users as creator', 'creator.id', '=', 'users.created_by');
        //->join('users_organizations','users_organizations.user_id','=','users.id');
        //$users = $users->where('users_organizations.organization_id',0);
        $users = $users->where(function ($query) {
            $query->where('roles.name', '=', 'system-administrator')
                ->orWhere('users.is_system_admin', 1);
        });
        $users = $users->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->Join('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftJoin('demographic_user_data', 'users.id', '=', 'demographic_user_data.user_id');
        $users = $users->whereIn('users.account_status', systemActiveUsersStatuses())
            ->groupBy('users.id');

        $data = $users;

        $totalRecords = with(clone $data)->get()->count();
        $searchValue = request('search');
        $getColumns = explode(',', request('columns'));
        // dd( $getColumns);
        if (!empty($searchValue)) {
            $data = $data->where(function ($query) use ($searchValue, $getColumns, $data) {
                $query->where('users.name', 'like', '%' . $searchValue . '%');
                foreach ($getColumns as $search_column) {
                    switch ($search_column) {
                        case 'email':
                            $query->orWhere('users.email', 'like', '%' . $searchValue . '%');
                            break;
                        case 'status':
                            $query->orWhere('users.account_status', 'like', '%' . $searchValue . '%');
                            break;
                    }
                }
            });
        }


        //Order Query
        $sortOrder = request('sortOrder');
        $sortColumn = request('sortColumn');

        if (!$sortColumn && !$sortOrder) {
            $data = $data->orderBy('users.id', 'DESC');
        } else {
            foreach ($getColumns as $column) {
                if ($sortColumn  == $column) {
                    $data = $data->orderBy('users.' . $column, strtoupper($sortOrder));
                }
            }
        }

        $totalRecordswithFilter = with(clone $data)->get()->count();


        $start =  request('startQueryFrom');
        $draw = 1;
        $rowperpage = request('rowParPage');

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $roles = Role::all();

        $i = 1;
        foreach ($data as $record) {


            $status = $record['account_status'];
            switch ($status) {
                case "ACTIVE":
                    $status = "<span class='badge badge-success'>" . $status . "</span>";
                    break;
                case "PENDING":
                    $status = "<span class='badge badge-info'>" . $status . "</span>";
                    break;
                case "SUSPENDED":
                case "LOCKED":
                    $status = "<span class='badge badge-warning'>" . $status . "</span>";
                    break;
                case "CLOSED":
                case "REJECTED":
                    $status = "<span class='badge badge-secondary'>" . $status . "</span>";
                    break;
            }

            $data_arr[] = array(
                "sno" => $i++,
                "id" => $record->id,
                "name" => $record->name,
                "email" => $record->email,
                "role_name" => $record->role_name,
                "status" => $status,
                "data" => $record
            );
        }

        $response = array(
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response, 200);
    }

    private function adminData($request, $auth_user)
    {
        ## Read value
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length : 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        /*$data = User::join('role_user','role_user.user_id','=','users.id')
            ->join('roles','role_user.role_id','=','roles.id')
            ->where('roles.name','=','system-administrator')
            ->select([
                'users.*'
            ]);
            */
        $users = User::select([
            'users.*',
            'demographic_user_data.timezone as timezone',
            'demographic_user_data.phone as phone',
            'demographic_user_data.city as city',
            'demographic_user_data.province as province',
            'demographic_user_data.department as department',
            'demographic_user_data.job_title as job_title',
            'creator.firstname as creator_firstname',
            'creator.lastname as creator_lastname',
            'roles.display_name as role_name'
        ])->leftJoin('users as creator', 'creator.id', '=', 'users.created_by');
        //->join('users_organizations','users_organizations.user_id','=','users.id');
        //$users = $users->where('users_organizations.organization_id',0);
        $users = $users->where(function ($query) {
            $query->where('roles.name', '=', 'system-administrator')
                ->orWhere('users.is_system_admin', 1);
        });
        $users = $users->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->Join('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftJoin('demographic_user_data', 'users.id', '=', 'demographic_user_data.user_id');
        $users = $users->whereIn('users.account_status', systemActiveUsersStatuses())
            ->groupBy('users.id');

        $data = $users;

        $totalRecords = with(clone $data)->get()->count();

        $search_columns = [
            "name",
            "email",
            "status",
        ];

        if (!empty($searchValue)) {
            $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                $query->where('users.name', 'like', '%' . $searchValue . '%');
                foreach ($search_columns as $search_column) {
                    switch ($search_column) {
                        case 'email':
                            $query->orWhere('users.email', 'like', '%' . $searchValue . '%');
                            break;
                        case 'status':
                            $query->orWhere('users.account_status', 'like', '%' . $searchValue . '%');
                            break;
                    }
                }
            });
        }

        if (!$columnIndex && !is_numeric($columnIndex)) {
            $data = $data->orderBy('users.id', 'DESC');
        } else {
            switch ($columnName) {
                case 'name':
                    $data = $data->orderBy('users.name', strtoupper($columnSortOrder));
                    break;
                case 'email':
                    $data = $data->orderBy('users.email', strtoupper($columnSortOrder));
                    break;
                case 'status':
                    $data = $data->orderBy('users.account_status', strtoupper($columnSortOrder));
                    break;
                default:
                    $data = $data->orderBy('users.id', 'DESC');
                    break;
            }
        }

        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $roles = Role::whereIn('name', ['system-administrator'])->orWhere('for_system_admin', true)->get();

        $i = 1;
        foreach ($data as $record) {
            $close_modal = "";

            $action = "";

            if ($auth_user->userCan('admin/manage-admins/edit')) {
                $action = '<div id="edit-user-' . $record->id . '" class="modal fade" role="dialog">
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
                                            <form action="" method="post" autocomplete="off" id="CreateUser">
                                            <input type="hidden" name="user_id" value="' . $record->id . '" />
                                            ' . csrf_field() . '
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
                                                            <input type="text" value="' . $record->phone . '" id="phone" name="phone" onkeyup="checkPhoneLength(this)" placeholder="Enter Phone" maxlength="11" class="form-control col-md-12 edit_phone" required/>
                                                        </div>
                                                        <div class="edit_phoneError" ></div>
                                                        <h5>Email</h5>
                                                        <div class="form-group">
                                                            <input type="email" value="' . $record->email . '" id="email" maxlength="51"  name="email" onkeyup="checkEmailLength(this)" placeholder="Enter Email"  class="form-control col-md-12 edit_email" required/>
                                                        </div>
                                                        <div class="edit_emailError" ></div>
                                                        <h5>Department</h5>
                                                        <div class="form-group">
                                                            <input type="text" value="' . $record->department . '" id="department" maxlength="51" name="department" onkeyup="checkDepartmentLength(this)" placeholder="Enter Department"  class="form-control col-md-12 edit_department" required/>
                                                        </div>
                                                        <div class="edit_departmentError" ></div>
                                                        
                                                        </div>
                                                        <div class="col-md-6 well">
                                                        
                                                        <h5>Job Title</h5>
                                                        <div class="form-group">
                                                            <input type="text" value="' . $record->job_title . '" id="job_title" name="job_title" onkeyup="checkJobTitleLength(this)" maxlength="51" placeholder="Enter Job Title"  class="form-control col-md-12 edit_job_title" required/>
                                                        </div>
                                                        <div class="edit_jobtitleError" ></div>
                                                        <h5>City</h5>
                                                        <div class="form-group">
                                                            <input type="text" value="' . $record->city . '" id="city" name="city"  onkeyup="checkCityLength(this)" placeholder="Enter City" maxlength="51"  class="form-control col-md-12 edit_city" required/>
                                                        </div>
                                                        <div class="edit_cityError" ></div>
                                                        <h5>Location</h5>
                                                        <div class="form-group">
                                                    
                                                            <select name="province" id="province" class="form-control select2" required>
                                                                <option value="">Select Province</option>';

                $provinces = provinces();

                foreach ($provinces as $province) :
                    $action .= '<option ' . ($record->province == $province ? "selected" : "") . ' value="' . $province . '">' . $province . '</option>';
                endforeach;

                $action .= '</select>
                                                        </div>
                                                        <h5>Timezone</h5>
                                                        <div class="form-group">
                                        
                                                            <select name="timezone" id="timezone" class="form-control select2" required>
                                                                <option value="">Select Location</option>';

                $timezones = timezonesList();

                foreach ($timezones as $key => $timezone) :
                    $action .= '<option ' . ($record->timezone == $key ? "selected" : "") . ' value="' . $key . '">' . $timezone . '</option>';
                endforeach;

                $action .= '</select>
                                                        </div><h5>Roles</h5>
                                                        <div class="form-group">
                                                            
                                                            <select name="role_id" class="form-control select2" required>
                                                                <option value="">Select Role</option>';

                foreach ($roles as $role) :
                    $action .= '<option ' . ($role->id == $record->role_id ? "selected" : "") . ' value="' . $role->id . '">' . $role->display_name . '</option>';
                endforeach;

                $action .= '</select>
                                                        </div>
                                                        </div>
                                                        </div>

                                                    <div class="row" align="center">
                                                        <div class="col-md-12 well">

                                                        <div class="form-group">
                                                            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancel">
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
            if (!in_array($record['account_status'], ["CLOSED"]) && \auth()->id() != $record->id) {
                $action .= '<div class="dropdown">
                        <button class="btn custom-primary round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                if ($auth_user->userCan('admin/manage-admins/edit')) {
                    $action .= ' <a class="dropdown-item "  href="javascript:void()" data-toggle="modal" data-target="#edit-user-' . $record->id . '">Edit</a> ';
                }

                if ($auth_user->userCan('admin/manage-admins/suspend') && in_array($record['account_status'], ["ACTIVE", "LOCKED"])) {
                    $action .= '<a class="dropdown-item admin-action-on-admin" href="' . route('org.update-users-status', ['action' => 'suspend', 'user_id' => $user_id_encoded]) . '">Suspend</a>';
                }
                if ($auth_user->userCan('admin/manage-admins/suspend') && in_array($record['account_status'], ["SUSPENDED", "LOCKED"])) {
                    $action .= '<a class="dropdown-item admin-action-on-admin" href="' . route('org.update-users-status', ['action' => 'activate', 'user_id' => $user_id_encoded]) . '">Activate</a>';
                }

                if ($auth_user->userCan('admin/manage-admins/close')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item"  user-id="' . CustomEncoder::urlValueEncrypt($record->id) . '" onclick="return closeUser(this)">Close</a>
                            </div>
                    </div>';
                }

                $close_modal = '';
                if ($auth_user->userCan('admin/manage-admins/close')) {
                    $close_modal = ' <div id="myModal' . $user_id_encoded . '" class="modal fade admin-close-user-form" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <form action="#" method="post">
                                                        ' . csrf_field() . '
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Are you sure to Close this admin account?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>Reason for closing the account</label>
                                                                <select name="reason" class="form-control" required>';

                    $reasons = DB::table('account_closure_reasons')->get();
                    foreach ($reasons as $reason) {
                        $close_modal .= '<option value="' . $reason->reason . '">' . $reason->reason . '</option>';
                    }

                    $close_modal .= ' </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn custom-primary round">Close Account</button>
                                                        <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>';
                }
            }

            $status = $record['account_status'];
            switch ($status) {
                case "ACTIVE":
                    $status = "<span class='badge badge-success'>" . $status . "</span>";
                    break;
                case "PENDING":
                    $status = "<span class='badge badge-info'>" . $status . "</span>";
                    break;
                case "SUSPENDED":
                case "LOCKED":
                    $status = "<span class='badge badge-warning'>" . $status . "</span>";
                    break;
                case "CLOSED":
                case "REJECTED":
                    $status = "<span class='badge badge-secondary'>" . $status . "</span>";
                    break;
            }

            $data_arr[] = array(
                "sno" => $i++,
                "name" => $record->name,
                "email" => $record->email,
                "role_name" => $record->role_name,
                "status" => $status,
                "action" => $action . $close_modal
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response, 200);
    }

    private function usersOnBoardData($request, $auth_user)
    {

        ## Read value
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length : 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        $user=Auth::user();
        $logdetails = UsersIPAddress::where("user_id",$user->id)->where("status","ACTIVE")->first();
         $data = Organization::with(['industry'])->whereIn('organizations.status', ['PENDING', 'REVIEWING', 'INVITED'])
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating', 'credit_rating.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating_type', 'credit_rating_type.id', '=', 'credit_rating.credit_rating_type_id')
            ->leftJoin('deposit_insurance', 'deposit_insurance.id', '=', 'credit_rating.deposit_insurance_id')
            ->leftJoin('users', 'users.id', '=', 'organizations.admin_user_id')
            ->select([
                'organizations.*',
                'users.name as admin_name',
                'organizations.is_test as test_or_not',
                //          'demographic_organization_data.telephone as tel',
                'demographic_organization_data.city as city',
                'deposit_insurance.description as deposit_insurance',
                'credit_rating_type.description as credit_rating'
            ]);

        $totalRecords = with(clone $data)->get()->count();

        $search_columns = [
            //                "sno",
            //                "role",
            "name",
            "city",
            "admin_name",
            //                "email",
            //                "tel",
            "created_at",
            //                "action"
        ];

        if (!empty($searchValue)) {

            $search_is_date = false;
            try {
                try {
                    $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                    $account_opening_date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT, Constants::DATE_FORMAT);
                } catch (\Exception $exception) {
                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                    $account_opening_date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT, Constants::DATE_FORMAT);
                }
                $data = $data->where('organizations.created_at', 'like', '%' . $account_opening_date_in_utc . '%');
                $search_is_date = true;
            } catch (\Exception $exception) {
            }

            if (!$search_is_date) {
                $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                    $query->where('organizations.name', 'like', '%' . $searchValue . '%');
                    foreach ($search_columns as $search_column) {
                        switch ($search_column) {
                                //                                case 'tel':
                                //                                    $query->orWhere('demographic_organization_data.telephone', 'like', '%' . $searchValue . '%');
                                //                                    break;
                            case 'city':
                                $query->orWhere('demographic_organization_data.city', 'like', '%' . $searchValue . '%');
                                break;
                            case 'admin_name':
                                $query->orWhere('users.name', 'like', '%' . $searchValue . '%');
                                break;
                        }
                    }
                });
            }
        }

        if (!$columnIndex && !is_numeric($columnIndex)) {
            $data = $data->orderBy('organizations.id', 'DESC');
        } else {
            switch ($columnName) {
                case 'name':
                    $data = $data->orderBy('organizations.name', strtoupper($columnSortOrder));
                    break;
                    //                    case 'tel':
                    //                        $data = $data->orderBy('demographic_organization_data.telephone',strtoupper($columnSortOrder));
                    //                        break;
                case 'city':
                    $data = $data->orderBy('demographic_organization_data.city', strtoupper($columnSortOrder));
                    break;
                case 'admin_name':
                    $data = $data->orderBy('users.name', strtoupper($columnSortOrder));
                    break;
                case 'created_at':
                    $data = $data->orderBy('organizations.created_at', strtoupper($columnSortOrder));
                    break;
                default:
                    $data = $data->orderBy('organizations.id', 'DESC');
                    break;
            }
        }

        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        $i = 1;
        foreach ($data as $record) {
            $organization_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
            $org_type = $record->type;
            $close_modal = '';
            $aprrovemodal = '';

            $action =  $this->industryView($organization_id_encoded, $record->name) . '<div class="dropdown">
                        <button class="btn custom-primary round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="' . route('user.account-setting', ['update_for' => $record->type, 'organization_id' => $organization_id_encoded, 'fromPage' => 'yie-admin/users/users_onboard']) . '">Edit</a>';

            if ($auth_user->userCan('admin/industries/assign')) {
                $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#assignIndustryModal' . $organization_id_encoded . '">Update Industry</a>';
            }

            if ($auth_user->userCan('admin/login-as-client/full-access')) {
                $action .= '<a href="/login-as-client/'.$organization_id_encoded.'/'.CustomEncoder::urlValueEncrypt($user->id).'/'.$logdetails->login_as_admin_token.'" class="dropdown-item">Login as client </a>';
            }

            if ($auth_user->userCan('admin/organizations-onboard/mark-as-test')) {
                if ($record->test_or_not == 0) {
                    $action .= '<a class="dropdown-item" href="mark_as_test/users_onboard/' . $organization_id_encoded . '" >Mark As Test</a>';
                } else {
                    $action .= '<a class="dropdown-item" href="mark_as_test/users_onboard/' . $organization_id_encoded . '" >Unmark As Test</a>';
                }
            }

            if ($auth_user->userCan('universal/users/page-access')) {
                $action .= '<a href="' . route('admin.organizations.users.index', ['organization_id' => $organization_id_encoded, 'fromPage' => 'yie-admin/users/users_onboard']) . '" class="dropdown-item">Users</a>';
            }

            if ($auth_user->userCan('admin/organizations-onboard/enable-multi-organization')) {
                if ($record->allow_multi_organizations == 0) {
                    $action .= '<a class="dropdown-item" href="' . route('admin.allow-multi-organizations', ['organization_id' => $organization_id_encoded]) . '" >Enable Multi Organization</a>';
                } else {
                    $action .= '<a class="dropdown-item" href="' . route('admin.allow-multi-organizations', ['organization_id' => $organization_id_encoded]) . '" >Disable Multi Organization</a>';
                }
            }

            if ($record['status'] == "PENDING") {
                if (!empty($record['deposit_insurance']) && !empty($record['credit_rating']) || ($record['type'] != "BANK")) {
                    if ($auth_user->userCan('admin/organizations-onboard/approve')) {
                        //  $action .= '<a class="dropdown-item admin-action-to-user"  href="' . route('admin.users.update-status', ['action' => 'approve', 'organization_id' => $organization_id_encoded]) . '">Approve</a>';
                        $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#approvalmodal' . $organization_id_encoded . '">Approve</a>';
                    }
                } else {
                    if ($auth_user->userCan('admin/organizations-onboard/approve')) {
                        $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#approvalmodal' . $organization_id_encoded . '">Approve</a>';
                    }
                }
                if ($auth_user->userCan('admin/organizations-onboard/reject')) {
                    $action .= '<a class="dropdown-item admin-action-to-user" href="' . route('admin.users.update-status', ['action' => 'reject', 'organization_id' => $organization_id_encoded]) . '">Reject</a>';
                }

                if ($auth_user->userCan('admin//organizations-onboard/close')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal' . $organization_id_encoded . '">Close</a>';
                }
                if ($auth_user->userCan('admin/organizations-onboard/approve')) {
                    $aprrovemodal = ' <div id="approvalmodal' . $organization_id_encoded . '" class="modal fade" role="dialog">
                           <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                            <form action="' . route('admin.users.update-status', ['action' => 'approve', 'organization_id' => $organization_id_encoded]) . '" class="" method="post">
                            ' . csrf_field() . '
                                <div class="modal-header">
                                <h4 class="modal-title">Approving Organization?</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="row">
                                    <div class="form-group col-12">
                                        <label>Do you want to Enable Campaigns for this Organization? </label>
                                        &nbsp;&nbsp; <input required type="radio"  name="enable_campaigns" value="Yes" > &nbsp; Yes
                                       &nbsp;&nbsp; <input required type="radio" name="enable_campaigns" value="No" > &nbsp; No  
                                    </div></div>';
                    if ($org_type == "DEPOSITOR") {
                        $aprrovemodal .= '<div class="row">
                                                                <div class="form-group col-12">
                                                                    <label>Add this depositor to all depositors group? </label>
                                                                    &nbsp;&nbsp; <input  required type="radio" name="add_to_all_depos_group" value="Yes"> &nbsp; Yes
                                                                &nbsp;&nbsp; <input required type="radio" name="add_to_all_depos_group" value="No"> &nbsp; No  
                                                                </div>
                                                        </div>';
                    }
                    $aprrovemodal .= '
                                </div>
                                <div class="modal-footer">
                                <button type="submit" class="btn custom-primary round">Proceed</button>
                                <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                            </div>
                                </form>
                            </div>
                            </div>
                            </div>';
                }

                if ($auth_user->userCan('admin//organizations-onboard/close')) {
                    $close_modal = ' <div id="myModal' . $organization_id_encoded . '" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">
                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <form action="' . route('admin.users.update-status', ['action' => 'close', 'organization_id' => $organization_id_encoded]) . '" class="admin-close-user-form" method="post">
                                                                        ' . csrf_field() . '
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Are you sure to Close this account?</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="form-group col-12">
                                                                                    <label>Reason for closing the account</label>
                                                                                    <select name="reason" class="form-control" required>';

                    $reasons = DB::table('account_closure_reasons')->get();
                    foreach ($reasons as $reason) {
                        $close_modal .= '<option value="' . $reason->reason . '">' . $reason->reason . '</option>';
                    }

                    $close_modal .= ' 
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group col-12">
                                                                                    <label>Notify User via Email</label>
                                                                                    <select name="send_mail" class="form-control" required>
                                                                                        <option value="yes">Yes</option>
                                                                                        <option value="no">No</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn custom-primary round">Close Account</button>
                                                                            <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>';
                }
            }


            $action .= '</div>
                    </div>';

            $data_arr[] = array(
                "sno" => $i++,
                //                    "role" => $record->role_name,
                "name" => $record->name,
                "city" => $record->city,
                "admin" => $record->admin_name,
                //                    "email" => $record->email,
                //                    "tel" => $record->tel,
                "industry" => optional($record->industry)->name,
                "is_test" => ($record->test_or_not == 0) ? 'No' : 'Yes',
                "partially_approved" => ($record->is_partially_approved == 1) ? 'Yes' : 'No',
                "created_at" => $record->created_at ? changeDateFromUTCtoLocal($record->created_at, Constants::DATE_TIME_FORMAT_NO_SECONDS) : '-',
                //                    "status" => '<span class="badge badge-info">'.str_replace("_"," ",$record->account_status).'</span>',
                "action" => $action . $close_modal . $aprrovemodal
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response, 200);
    }

    private function depositorsData($request, $auth_user)
    {
        $clientIp = $request->header('X-Client-IP');
        $user=Auth::user();
        $logdetails = UsersIPAddress::where("user_id",$auth_user->id)->where("status","ACTIVE")->first();
        $request->session()->put('my_ip', $clientIp);

        ## Read value
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length : 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $data = Organization::with(['industry'])->whereIn('organizations.status', ['ACTIVE', 'SUSPENDED', 'CLOSED'])
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating', 'credit_rating.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating_type', 'credit_rating_type.id', '=', 'credit_rating.credit_rating_type_id')
            ->leftJoin('deposit_insurance', 'deposit_insurance.id', '=', 'credit_rating.deposit_insurance_id')
            ->leftJoin('users', 'users.id', '=', 'organizations.admin_user_id')
            ->leftJoin('a_w_s_file_routings', 'a_w_s_file_routings.organization_id', '=', 'organizations.id')
            ->where('organizations.type', 'DEPOSITOR')
            ->select([
                'organizations.*',
                'a_w_s_file_routings.file_type',
                'a_w_s_file_routings.routing_agent',
                'a_w_s_file_routings.delivery_method',
                'demographic_organization_data.telephone as tel',
                'demographic_organization_data.city as city',
                'users.name as admin_name',
                'organizations.is_test as test_or_not'
            ]);

        $totalRecords = with(clone $data)->get()->count();

        $search_columns = [
            //                "sno",
            "name",
            "city",
            "admin",
            "tel",
            "status",
            //                "action"
        ];

        if (!empty($searchValue)) {
            $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                $query->where('organizations.name', 'like', '%' . $searchValue . '%');
                foreach ($search_columns as $search_column) {
                    switch ($search_column) {
                        case 'admin':
                            $query->orWhere('users.name', 'like', '%' . $searchValue . '%');
                            break;
                        case 'tel':
                            $query->orWhere('demographic_organization_data.telephone', 'like', '%' . $searchValue . '%');
                            break;
                        case 'city':
                            $query->orWhere('demographic_organization_data.city', 'like', '%' . $searchValue . '%');
                            break;
                        case 'status':
                            $query->orWhere('organizations.status', 'like', '%' . $searchValue . '%');
                            break;
                    }
                }
            });
        }

        if (!$columnIndex && !is_numeric($columnIndex)) {
            $data = $data->orderBy('organizations.id', 'DESC');
        } else {
            switch ($columnName) {
                case 'name':
                    $data = $data->orderBy('organizations.name', strtoupper($columnSortOrder));
                    break;
                case 'tel':
                    $data = $data->orderBy('demographic_organization_data.telephone', strtoupper($columnSortOrder));
                    break;
                case 'admin':
                    $data = $data->orderBy('users.name', strtoupper($columnSortOrder));
                    break;
                case 'city':
                    $data = $data->orderBy('demographic_organization_data.city', strtoupper($columnSortOrder));
                    break;
                case 'status':
                    $data = $data->orderBy('organizations.status', strtoupper($columnSortOrder));
                    break;
                default:
                    $data = $data->orderBy('organizations.id', 'DESC');
                    break;
            }
        }

        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        $i = 1;
        foreach ($data as $record) {
            $assignpers = "";
            $action = "";
            $close_modal = "";
            $organization_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
            if (!in_array($record['status'], ["CLOSED"])) {
                $action = $this->changeOrganizationSuperAdminUser(User::find($record->admin_user_id), $organization_id_encoded, $record->name) . $this->usersLimitView($record->users_limit, $organization_id_encoded, $record->name) .
                    $this->industryView($organization_id_encoded, $record->name) . $this->awsIntegrate($organization_id_encoded, $record->name) . '<div class="dropdown">
                        <button class="btn custom-primary round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                if ($auth_user->userCan('admin/login-as-client/full-access')) {
                    // $action .= '<a href="/' . route('login-as-client', $organization_id_encoded) . '" class="dropdown-item">Login as client'.$clientIp.'</a>';
                    $action .= '<a href="/login-as-client/'.$organization_id_encoded.'/'.CustomEncoder::urlValueEncrypt($user->id).'/'.$logdetails->login_as_admin_token.'" class="dropdown-item">Login as client </a>';
               
                }

                if ($auth_user->userCan('admin/gic-investors/edit')) {
                    $action .= '<a class="dropdown-item" href="' . route('user.account-setting', ['update_for' => 'Depositor', 'organization_id' => $organization_id_encoded, 'fromPage' => 'yie-admin/users/depositors']) . '">Edit</a>';
                }

                if ($auth_user->userCan('admin/industries/assign')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#assignIndustryModal' . $organization_id_encoded . '">Update Industry</a>';
                }

                if ($auth_user->userCan('admin/gic-investors/enable-campaign')) {
                    $action .= '<a class="dropdown-item" href="' . route('admin.enable-campaigns', ['organization_id' => $organization_id_encoded]) . '" >' . (!$record->enable_campaigns ? "Enable" : "Disable") . ' Campaign</a>';
                }

                if ($auth_user->userCan('admin/industries/assign')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#addAwsIntegrationModal' . $organization_id_encoded . '">AWS Integration</a>';
                }


                //                if($auth_user->userCan('admin/admin-posting-request/post-request')) {
                //                    $action .= '<a class="dropdown-item" href="/yie-admin/post-request/' . $organization_id_encoded . '" >Post Request</a>';
                //                }
                if ($auth_user->userCan('admin/gic-investors/update-users-limit')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#usersLimitModal' . $organization_id_encoded . '">Update user\'s limit</a>';
                }
                if ($auth_user->userCan('admin/gic-investors/update-super-admin')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#updateSuperAdmin' . $organization_id_encoded . '">Update Super Admin</a>';
                }

                if ($auth_user->userCan('admin/gic-investors/enable-multi-organization')) {
                    if ($record->allow_multi_organizations == 0) {
                        $action .= '<a class="dropdown-item" href="' . route('admin.allow-multi-organizations', ['organization_id' => $organization_id_encoded]) . '" >Enable Multi Organization</a>';
                    } else {
                        $action .= '<a class="dropdown-item" href="' . route('admin.allow-multi-organizations', ['organization_id' => $organization_id_encoded]) . '" >Disable Multi Organization</a>';
                    }
                }

                if ($auth_user->userCan('admin/gic-investors/mark-as-test')) {
                    if ($record->test_or_not == 0) {
                        $action .= '<a class="dropdown-item" href="mark_as_test/depositors/' . $organization_id_encoded . '" >Mark As Test</a>';
                    } else {
                        $action .= '<a class="dropdown-item" href="mark_as_test/depositors/' . $organization_id_encoded . '" >Unmark As Test</a>';
                    }
                }

                if ($auth_user->userCan('universal/users/page-access')) {
                    $action .= '<a href="' . route('admin.organizations.users.index', ['organization_id' => $organization_id_encoded, 'fromPage' => 'yie-admin/users/depositors']) . '" class="dropdown-item">Users</a>';
                }

                if ($auth_user->userCan('admin/gic-investors/suspend')) {
                    if (in_array($record['status'], ["ACTIVE"])) {
                        $action .= '<a class="dropdown-item admin-action-to-user" href="' . route('admin.users.update-status', ['action' => 'suspend', 'organization_id' => $organization_id_encoded]) . '">Suspend</a>';
                    }
                }

                if ($auth_user->userCan('admin/gic-investors/suspend')) {
                    if (in_array($record['status'], ["SUSPENDED"])) {
                        $action .= '<a class="dropdown-item admin-action-to-user" href="' . route('admin.users.update-status', ['action' => 'activate', 'organization_id' => $organization_id_encoded]) . '">Activate</a>';
                    }
                }
                if ($auth_user->userCan('admin/gic-investors/enable-campaign')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#modalAssignPermissions' . $organization_id_encoded . '">Assign Permissions</a>
                          ';
                }



                if ($auth_user->userCan('admin/gic-investors/close')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal' . $organization_id_encoded . '">Close</a>
                            </div>
                    </div>';
                }

                if ($auth_user->userCan('admin/gic-investors/close')) {
                    $close_modal = ' <div id="myModal' . $organization_id_encoded . '" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <form action="' . route('admin.users.update-status', ['action' => 'close', 'organization_id' => $organization_id_encoded]) . '" class="admin-close-user-form" method="post">
                                                                     ' . csrf_field() . '
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Are you sure to Close this account?</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-12">
                                                                                <label>Reason for closing the account</label>
                                                                                <select name="reason" class="form-control" required>';

                    $reasons = DB::table('account_closure_reasons')->get();
                    foreach ($reasons as $reason) {
                        $close_modal .= '<option value="' . $reason->reason . '">' . $reason->reason . '</option>';
                    }

                    $close_modal .= ' </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn custom-primary round">Close Account</button>
                                                                        <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>';
                }


                if ($auth_user->userCan('admin/gic-investors/enable-campaign')) {
                    //get all the depositor permissions
                    $permissions =  DB::table('org_permissions_lists')
                        ->where("type", "DEPOSITOR")
                        ->orwhere("type", "UNIVERSAL")->get();
                    //get all the depositor permissions
                    $allcurrents = OrganizationLevelPermission::where('organization_id', $record->id)
                        ->whereIn("status", ['Active'])
                        ->pluck('org_permissions_list_permission_id')->toArray();
                    $org = Organization::where("id", $record->id)->first();
                    $assignpers = ' <div id="modalAssignPermissions' . $organization_id_encoded . '" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <form action="' . route('admin.update-organization-level') . '" class="admin-update-organization-level-form" method="post">
                                                                     ' . csrf_field() . '
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Organization Level Permissions</h4>
                                                                    </div>
                                                                    <div class="modal-body"  style="max-height: 600px; overflow-y: auto;" >
                                                                        <div class="row">
                                                                        <div class="form-group col-12">
                                                                          <p>Please select or deselect the permissions you want to assign or reeassign</p>
                                                                          </div>
                                                                        </div>
                                                                        <div class="row">
                                                                      
                                                                            <div class="form-group col-12" >
                                                                            <input type="hidden" name="organization" id="organization"   value="' . $organization_id_encoded . '" />';

                    foreach ($permissions as $permission) {


                        $assignpers .= '<div  style="display:flex; flex-column:row; align-items:center !important; gap:5px; margin-left:25px; margin-top:15px;">';
                        if (in_array($permission->id, $allcurrents)) {
                            $assignpers .= '<input class="form-check-input" type="checkbox" value="' . $permission->id . '" name="permissions[]" style="margin-top:-2px !important;" checked="true">';
                        } else {
                            $assignpers .= '<input class="form-check-input" type="checkbox" value="' . $permission->id . '" name="permissions[]" style="margin-top:-2px !important;">';
                        }


                        $assignpers .= '<b>' . ucfirst($permission->name) . '</b>
                                                                                        
                                                                                    </div>';
                    }

                    $assignpers .= ' </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn custom-primary round">Save Permmissions</button>
                                                                        <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>';
                }
            }

            $status = $record['status'];
            switch ($status) {
                case "ACTIVE":
                    $status = "<span class='badge badge-success'>" . $status . "</span>";
                    break;
                case "PENDING":
                    $status = "<span class='badge badge-info'>" . $status . "</span>";
                    break;
                case "SUSPENDED":
                    $status = "<span class='badge badge-warning'>" . $status . "</span>";
                    break;
                case "CLOSED":
                case "REJECTED":
                    $status = "<span class='badge badge-secondary'>" . $status . "</span>";
                    break;
            }
            $locked = $record->users("SUSPENDED")->count() + $record->users("LOCKED")->count();

            switch ($locked) {
                case 0:
                    $locked = "<span class='badge badge-success'>" . $locked . " User(s)</span>";
                    break;
                case $record->users_count:
                    $locked = "<span class='badge badge-danger'>All User(s)</span>";
                    break;
                case ($locked > 0):
                    $locked = "<span class='badge badge-warning'>" . $locked . " User(s)</span>";
                    break;
            }

            $data_arr[] = array(
                "sno" => $i++,
                "name" => $record->name,
                "city" => $record->city,
                "admin" => $record->admin_name,
                "users_limit" => $record->users_count . "/" . $record->organization_users_limit,
                "tel" => $record->tel,
                "industry" => optional($record->industry)->name,
                'is_test' => ($record->test_or_not == 0) ? 'No' : 'Yes',
                "locked" => $locked,
                "status" => $status,
                "aws_routing_agent" => $record->routing_agent,
                "aws_file_type" => $record->file_type,
                "aws_delivery_method" => str_replace("_", " ", $record->delivery_method),
                "action" => $action . $close_modal . $assignpers
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response, 200);
    }

    private function banksData($request, $auth_user)
    {

        $clientIp = $request->header('X-Client-IP');
        $user=Auth::user();
        $logdetails = UsersIPAddress::where("user_id",$user->id)->where("status","ACTIVE")->first();
        $request->session()->put('my_ip', $clientIp);
        
        ## Read value
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length : 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        $data = Organization::with(['industry'])->whereIn('organizations.status', ['ACTIVE', 'SUSPENDED', 'CLOSED'])
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating', 'credit_rating.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating_type', 'credit_rating_type.id', '=', 'credit_rating.credit_rating_type_id')
            ->leftJoin('deposit_insurance', 'deposit_insurance.id', '=', 'credit_rating.deposit_insurance_id')
            ->leftJoin('users', 'users.id', '=', 'organizations.admin_user_id')
            ->leftJoin('a_w_s_file_routings', 'a_w_s_file_routings.organization_id', '=', 'organizations.id')
            ->where('organizations.is_non_partnered_fi', '!=', 1)
            ->where('organizations.type', 'BANK')
            ->select([
                'organizations.*',
                'a_w_s_file_routings.file_type',
                'a_w_s_file_routings.routing_agent',
                'a_w_s_file_routings.delivery_method',
                'demographic_organization_data.telephone as tel',
                'demographic_organization_data.city as city',
                'users.name as admin_name',
                'organizations.is_test as test_or_not'
            ]);

        $totalRecords = with(clone $data)->get()->count();

        $search_columns = [
            //                "sno",
            "name",
            "city",
            "admin",
            "tel",
            "industry",
            "status",
            //                "action"
        ];

        if (!empty($searchValue)) {
            $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                $query->where('organizations.name', 'like', '%' . $searchValue . '%');
                foreach ($search_columns as $search_column) {
                    switch ($search_column) {
                        case 'admin':
                            $query->orWhere('users.name', 'like', '%' . $searchValue . '%');
                            break;
                        case 'tel':
                            $query->orWhere('demographic_organization_data.telephone', 'like', '%' . $searchValue . '%');
                            break;
                        case 'city':
                            $query->orWhere('demographic_organization_data.city', 'like', '%' . $searchValue . '%');
                            break;
                        case 'status':
                            $query->orWhere('organizations.status', 'like', '%' . $searchValue . '%');
                            break;
                    }
                }
            });
        }

        if (!$columnIndex && !is_numeric($columnIndex)) {
            $data = $data->orderBy('organizations.id', 'DESC');
        } else {
            switch ($columnName) {
                case 'name':
                    $data = $data->orderBy('organizations.name', strtoupper($columnSortOrder));
                    break;
                case 'tel':
                    $data = $data->orderBy('demographic_organization_data.telephone', strtoupper($columnSortOrder));
                    break;
                case 'admin':
                    $data = $data->orderBy('users.name', strtoupper($columnSortOrder));
                    break;
                case 'city':
                    $data = $data->orderBy('demographic_organization_data.city', strtoupper($columnSortOrder));
                    break;
                case 'status':
                    $data = $data->orderBy('organizations.status', strtoupper($columnSortOrder));
                    break;
                default:
                    $data = $data->orderBy('organizations.id', 'DESC');
                    break;
            }
        }

        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        $i = 1;
        foreach ($data as $record) {
            $action = "";
            $close_modal = "";
            $organization_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
            if (!in_array($record['status'], ["CLOSED"])) {
                $action = $this->changeOrganizationSuperAdminUser(User::find($record->admin_user_id), $organization_id_encoded, $record->name) . $this->usersLimitView($record->organization_users_limit, $organization_id_encoded, $record->name) . $this->enableVisibilityView($record) .
                    $this->industryView($organization_id_encoded, $record->name) . $this->awsIntegrate($organization_id_encoded, $record->name) . '<div class="dropdown">
                        <button class="btn custom-primary round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                if ($auth_user->userCan('admin/login-as-client/full-access')) {
                    // $action .= '<a href="/' . route('login-as-client', $organization_id_encoded) . '" class="dropdown-item">Login as client</a>';
                    $action .= '<a href="/login-as-client/'.$organization_id_encoded.'/'.CustomEncoder::urlValueEncrypt($user->id).'/'.$logdetails->login_as_admin_token.'" class="dropdown-item">Login as client </a>';
                }

                if ($auth_user->userCan('admin/banks/page-access')) {
                    $action .= '<a class="dropdown-item" href="' . route('user.account-setting', ['update_for' => 'Bank', 'organization_id' => $organization_id_encoded, 'fromPage' => 'yie-admin/users/banks']) . '">Edit</a>';
                }

                if ($auth_user->userCan('admin/industries/assign')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#assignIndustryModal' . $organization_id_encoded . '">Update Industry</a>';
                }

                if ($auth_user->userCan('admin/banks/edit')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#usersLimitModal' . $organization_id_encoded . '">Update user\'s limit</a>';
                }
                if ($auth_user->userCan('admin/banks/update-super-admin')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#updateSuperAdmin' . $organization_id_encoded . '">Update Super Admin</a>';
                }
                if ($auth_user->userCan('admin/banks/mark-as-test')) {
                    if ($record->test_or_not == 0) {
                        $action .= '<a class="dropdown-item" href="mark_as_test/banks/' . $organization_id_encoded . '" >Mark As Test</a>';
                    } else {
                        $action .= '<a class="dropdown-item" href="mark_as_test/banks/' . $organization_id_encoded . '" >Unmark As Test</a>';
                    }
                }
                if ($auth_user->userCan('universal/users/page-access')) {
                    $action .= '<a href="' . route('admin.organizations.users.index', ['organization_id' => $organization_id_encoded, 'fromPage' => 'yie-admin/users/banks']) . '" class="dropdown-item">Users</a>';
                }

                if ($auth_user->userCan('admin/banks/enable-multi-organization')) {
                    if ($record->allow_multi_organizations == 0) {
                        $action .= '<a class="dropdown-item" href="' . route('admin.allow-multi-organizations', ['organization_id' => $organization_id_encoded]) . '" >Enable Multi Organization</a>';
                    } else {
                        $action .= '<a class="dropdown-item" href="' . route('admin.allow-multi-organizations', ['organization_id' => $organization_id_encoded]) . '" >Disable Multi Organization</a>';
                    }
                }

                if ($auth_user->userCan('admin/banks/enable-campaign')) {
                    $action .= '<a class="dropdown-item" href="' . route('admin.enable-campaigns', ['organization_id' => $organization_id_encoded]) . '" >' . (!$record->enable_campaigns ? "Enable" : "Disable") . ' Campaign</a>';
                }
                if ($auth_user->userCan('admin/banks/enable-campaign')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#modalAssignPermissions' . $organization_id_encoded . '">Assign Permissions</a>
                          ';
                }

                if ($auth_user->userCan('admin/industries/assign')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#addAwsIntegrationModal' . $organization_id_encoded . '">AWS Integration</a>';
                }

                //                if($auth_user->userCan('admin/banks/enable-visibility')) {
                //                    $action .= '<a class="dropdown-item" data-toggle="modal" data-target="#enableVisibilityModal'.$record->id.'" href="javascript:void()" >Enable Visibility</a>';
                //                }

                if ($auth_user->userCan('admin/banks/suspend')) {
                    if (in_array($record['status'], ["ACTIVE"])) {
                        $action .= '<a class="dropdown-item admin-action-to-user" href="' . route('admin.users.update-status', ['action' => 'suspend', 'organization_id' => $organization_id_encoded]) . '">Suspend</a>';
                    }
                }
                if ($auth_user->userCan('admin/banks/suspend')) {
                    if (in_array($record['status'], ["SUSPENDED"])) {
                        $action .= '<a class="dropdown-item admin-action-to-user" href="' . route('admin.users.update-status', ['action' => 'activate', 'organization_id' => $organization_id_encoded]) . '">Activate</a>';
                    }
                }
                if ($auth_user->userCan('admin/banks/close')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal' . $organization_id_encoded . '">Close</a>
                            </div>
                    </div>';
                }

                ///assign perms
                if ($auth_user->userCan('admin/banks/enable-campaign')) {
                    //get all the depositor permissions
                    $permissions =  DB::table('org_permissions_lists')
                        ->where("type", "FI")
                        ->orwhere("type", "UNIVERSAL")->get();
                    //get all the depositor permissions
                    $allcurrents = OrganizationLevelPermission::where('organization_id', $record->id)
                        ->whereIn("status", ['Active'])
                        ->pluck('org_permissions_list_permission_id')->toArray();
                    $org = Organization::where("id", $record->id)->first();
                    $assignpers = ' <div id="modalAssignPermissions' . $organization_id_encoded . '" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <form action="' . route('admin.update-organization-level') . '" class="admin-update-organization-level-form" method="post">
                                                                     ' . csrf_field() . '
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Organization Level Permissions</h4>
                                                                    </div>
                                                                    <div class="modal-body"  style="max-height: 600px; overflow-y: auto;" >
                                                                        <div class="row">
                                                                        <div class="form-group col-12">
                                                                          <p>Please select or deselect the permissions you want to assign or reeassign</p>
                                                                          </div>
                                                                        </div>
                                                                        <div class="row">
                                                                      
                                                                            <div class="form-group col-12" >
                                                                            <input type="hidden" name="organization" id="organization"   value="' . $organization_id_encoded . '" />';

                    foreach ($permissions as $permission) {


                        $assignpers .= '<div  style="display:flex; flex-column:row; align-items:center !important; gap:5px; margin-left:25px; margin-top:15px;">';
                        if (in_array($permission->id, $allcurrents)) {
                            $assignpers .= '<input class="form-check-input" type="checkbox" value="' . $permission->id . '" name="permissions[]" style="margin-top:-2px !important;" checked="true">';
                        } else {
                            $assignpers .= '<input class="form-check-input" type="checkbox" value="' . $permission->id . '" name="permissions[]" style="margin-top:-2px !important;">';
                        }


                        $assignpers .= '<b>' . ucfirst($permission->name) . '</b>
                                                                                        
                                                                                    </div>';
                    }

                    $assignpers .= ' </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn custom-primary round">Save Permmissions</button>
                                                                        <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>';
                }
                ///assign perms

                if ($auth_user->userCan('admin/banks/close')) {
                    $close_modal = ' <div id="myModal' . $organization_id_encoded . '" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <form action="' . route('admin.users.update-status', ['action' => 'close', 'organization_id' => $organization_id_encoded]) . '" class="admin-close-user-form" method="post">
                                                                     ' . csrf_field() . '
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Are you sure to Close this account?</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-12">
                                                                                <label>Reason for closing the account</label>
                                                                                <select name="reason" class="form-control" required>';

                    $reasons = DB::table('account_closure_reasons')->get();
                    foreach ($reasons as $reason) {
                        $close_modal .= '<option value="' . $reason->reason . '">' . $reason->reason . '</option>';
                    }

                    $close_modal .= ' </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn custom-primary round">Close Account</button>
                                                                        <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>';
                }
            }

            $status = $record['status'];
            switch ($status) {
                case "ACTIVE":
                    $status = "<span class='badge badge-success'>" . $status . "</span>";
                    break;
                case "PENDING":
                    $status = "<span class='badge badge-info'>" . $status . "</span>";
                    break;
                case "SUSPENDED":
                case "CLOSED":
                    $status = "<span class='badge badge-warning'>" . $status . "</span>";
                    break;
                case "REJECTED":
                    $status = "<span class='badge badge-secondary'>" . $status . "</span>";
                    break;
            }

            $data_arr[] = array(
                "sno" => $i++,
                "name" => $record->name,
                "city" => $record->city,
                "admin" => $record->admin_name,
                "users_limit" => $record->users_count . "/" . $record->organization_users_limit,
                "tel" => $record->tel,
                "industry" => ($record->industry != null) ? $record->industry->name : '',
                "is_test" => ($record->test_or_not == 0) ? 'No' : 'Yes',
                "status" => $status,
                "aws_routing_agent" => $record->routing_agent,
                "aws_file_type" => $record->file_type,
                "aws_delivery_method" => str_replace("_", " ", $record->delivery_method),
                "action" => $action . $close_modal . $assignpers
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response, 200);
    }

    public function enableVisibility(Request $request, $organization_id)
    {
        $auth_user = auth()->user();
        if (!$auth_user->userCan('admin/banks/enable-visibility')) {
            $response = ['data' => [], 'message' => 'Permission Denied', 'success' => false];
            return response()->json($response, 403);
        }

        $organization_data = Organization::find(CustomEncoder::urlValueDecrypt($organization_id));
        if (!$organization_data) {
            $response = ['data' => [], 'message' => 'Organization not found', 'success' => false];
            return response()->json($response, 400);
        }

        archiveTable($organization_data->id, 'organizations', \auth()->id(), 'enable-visibility');

        if ($request->filled('visibility')) {
            $organization_data->show_province_visibility = in_array('show_province_visibility', $request->visibility);
            $organization_data->show_naics_codes_visibility = in_array('show_naics_codes_visibility', $request->visibility);
            $organization_data->show_customers_visibility = in_array('show_customers_visibility', $request->visibility);
            $organization_data->save();
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Admin updated organization enable visibility settings");
        $response = array("success" => true, "message" => "Visibility settings updated successfully", "data" => []);
        return response()->json($response, 200);
    }

    private function noPartneredFiData($request, $auth_user)
    {
        ## Read value
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length : 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $data = Organization::with(['industry'])->/*whereIn('status',['ACTIVE','SUSPENDED','CLOSED'])
            ->*/leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating', 'credit_rating.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating_type', 'credit_rating_type.id', '=', 'credit_rating.credit_rating_type_id')
            ->leftJoin('deposit_insurance', 'deposit_insurance.id', '=', 'credit_rating.deposit_insurance_id')
            ->leftJoin('users', 'users.id', '=', 'organizations.admin_user_id')
            ->leftJoin('users AS inviter', 'inviter.id', '=', 'users.created_by')
            ->where('organizations.is_non_partnered_fi', 1)
            ->where('organizations.type', 'BANK')
            ->select([
                'organizations.*',
                'demographic_organization_data.telephone as tel',
                'demographic_organization_data.city as city',
                'users.name as admin_name',
                'users.is_test as test_or_not',
                'inviter.name as inviter_name'
            ]);

        $totalRecords = with(clone $data)->get()->count();

        $search_columns = [
            //                "sno",
            "name",
            "account_manager",
            "invited_by",
            "city",
            "admin",
            "tel",
            "status",
            //                "action"
        ];

        if (!empty($searchValue)) {
            $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                $query->where('organizations.name', 'like', '%' . $searchValue . '%');
                foreach ($search_columns as $search_column) {
                    switch ($search_column) {
                        case 'admin':
                            $query->orWhere('users.name', 'like', '%' . $searchValue . '%');
                            break;
                        case 'tel':
                            $query->orWhere('demographic_organization_data.telephone', 'like', '%' . $searchValue . '%');
                            break;
                        case 'city':
                            $query->orWhere('demographic_organization_data.city', 'like', '%' . $searchValue . '%');
                            break;
                        case 'status':
                            $query->orWhere('organizations.status', 'like', '%' . $searchValue . '%');
                            break;
                        case 'account_manager':
                            $query->orWhere('organizations.account_manager', 'like', '%' . $searchValue . '%');
                            break;
                        case 'invited_by':
                            $query->orWhere('inviter.name', 'like', '%' . $searchValue . '%');
                            break;
                    }
                }
            });
        }

        if (!$columnIndex && !is_numeric($columnIndex)) {
            $data = $data->orderBy('organizations.id', 'DESC');
        } else {
            switch ($columnName) {
                case 'name':
                    $data = $data->orderBy('organizations.name', strtoupper($columnSortOrder));
                    break;
                case 'tel':
                    $data = $data->orderBy('demographic_organization_data.telephone', strtoupper($columnSortOrder));
                    break;
                case 'admin':
                    $data = $data->orderBy('users.name', strtoupper($columnSortOrder));
                    break;
                case 'city':
                    $data = $data->orderBy('demographic_organization_data.city', strtoupper($columnSortOrder));
                    break;
                case 'status':
                    $data = $data->orderBy('organizations.status', strtoupper($columnSortOrder));
                    break;
                case 'account_manager':
                    $data = $data->orderBy('organizations.account_manager', strtoupper($columnSortOrder));
                    break;
                case 'invited_by':
                    $data = $data->orderBy('inviter.name', strtoupper($columnSortOrder));
                    break;
                default:
                    $data = $data->orderBy('organizations.id', 'DESC');
                    break;
            }
        }

        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        $i = 1;
        foreach ($data as $record) {
            $action = "";
            $close_modal = "";
            $organization_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
            if (!in_array($record['status'], ["CLOSED"])) {
                $action = $this->changeOrganizationSuperAdminUser(User::find($record->admin_user_id), $organization_id_encoded, $record->name) . $this->usersLimitView($record->organization_users_limit, $organization_id_encoded, $record->name) .
                    $this->industryView($organization_id_encoded, $record->name) . $this->awsIntegrate($organization_id_encoded, $record->name) . '<div class="dropdown">
                        <button class="btn custom-primary round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                if ($auth_user->userCan('admin/non-partnered-fi/edit')) {
                    $action .= '<a class="dropdown-item" href="' . route('user.account-setting', ['update_for' => 'Bank', 'organization_id' => $organization_id_encoded, 'fromPage' => 'yie-admin/users/non_partnered_fi']) . '">Edit</a>';
                }

                if ($auth_user->userCan('admin/industries/assign')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#assignIndustryModal' . $organization_id_encoded . '">Update Industry</a>';
                }

                if ($auth_user->userCan('admin/non-partnered-fi/update-users-limit')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#usersLimitModal' . $organization_id_encoded . '">Update user\'s limit</a>';
                }
                if ($auth_user->userCan('admin/non-partnered-fi/update-super-admin')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#updateSuperAdmin' . $organization_id_encoded . '">Update Super Admin</a>';
                }
                if ($auth_user->userCan('admin/non-partnered-fi/mark-as-test')) {
                    if ($record->test_or_not == 0) {
                        $action .= '<a class="dropdown-item" href="mark_as_test/non_partnered_fi/' . $organization_id_encoded . '" >Mark As Test</a>';
                    } else {
                        $action .= '<a class="dropdown-item" href="mark_as_test/non_partnered_fi/' . $organization_id_encoded . '" >Unmark As Test</a>';
                    }
                }
                if ($auth_user->userCan('universal/users/page-access')) {
                    $action .= '<a href="' . route('admin.organizations.users.index', ['organization_id' => $organization_id_encoded, 'fromPage' => 'yie-admin/users/non_partnered_fi']) . '" class="dropdown-item">Users</a>';
                }

                if ($auth_user->userCan('admin/non-partnered-fi/enable-multi-organization')) {
                    if ($record->allow_multi_organizations == 0) {
                        $action .= '<a class="dropdown-item" href="' . route('admin.allow-multi-organizations', ['organization_id' => $organization_id_encoded]) . '" >Enable Multi Organization</a>';
                    } else {
                        $action .= '<a class="dropdown-item" href="' . route('admin.allow-multi-organizations', ['organization_id' => $organization_id_encoded]) . '" >Disable Multi Organization</a>';
                    }
                }

                if ($auth_user->userCan('admin/non-partnered-fi/suspend')) {
                    if (in_array($record['status'], ["ACTIVE"])) {
                        $action .= '<a class="dropdown-item admin-action-to-user" href="' . route('admin.users.update-status', ['action' => 'suspend', 'organization_id' => $organization_id_encoded]) . '">Suspend</a>';
                    }
                }
                if ($auth_user->userCan('admin/non-partnered-fi/suspend')) {
                    if (in_array($record['status'], ["SUSPENDED"])) {
                        $action .= '<a class="dropdown-item admin-action-to-user" href="' . route('admin.users.update-status', ['action' => 'activate', 'organization_id' => $organization_id_encoded]) . '">Activate</a>';
                    }
                }

                if ($auth_user->userCan('admin/non-partnered-fi/close')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal' . $organization_id_encoded . '">Close</a>
                            </div>
                    </div>';
                }

                if ($auth_user->userCan('admin/non-partnered-fi/close')) {
                    $close_modal = ' <div id="myModal' . $organization_id_encoded . '" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <form action="' . route('admin.users.update-status', ['action' => 'close', 'organization_id' => $organization_id_encoded]) . '" class="admin-close-user-form" method="post">
                                                                   ' . csrf_field() . '
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Are you sure to Close this account?</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-12">
                                                                                <label>Reason for closing the account</label>
                                                                                <select name="reason" class="form-control" required>';

                    $reasons = DB::table('account_closure_reasons')->get();
                    foreach ($reasons as $reason) {
                        $close_modal .= '<option value="' . $reason->reason . '">' . $reason->reason . '</option>';
                    }

                    $close_modal .= ' </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn custom-primary round">Close Account</button>
                                                                        <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>';
                }
            }

            $status = $record['status'];
            switch ($status) {
                case "ACTIVE":
                    $status = "<span class='badge badge-success'>" . $status . "</span>";
                    break;
                case "PENDING":
                    $status = "<span class='badge badge-info'>" . $status . "</span>";
                    break;
                case "SUSPENDED":
                    $status = "<span class='badge badge-warning'>" . $status . "</span>";
                    break;
                case "CLOSED":
                case "REJECTED":
                    $status = "<span class='badge badge-secondary'>" . $status . "</span>";
                    break;
            }

            $data_arr[] = array(
                "sno" => $i++,
                "name" => $record->name,
                "account_manager" => $record->account_manager,
                "invited_by" => $record->inviter_name,
                "city" => $record->city,
                "admin" => $record->admin_name,
                "industry" => ($record->industry != null) ? $record->industry->name : '',
                "users_limit" => $record->users_count . "/" . $record->organization_users_limit,
                "tel" => $record->tel,
                'is_test' => ($record->test_or_not) ? 'No' : 'Yes',
                "status" => $status,
                "action" => $action . $close_modal
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response, 200);
    }

    public function doAction(Request $request, $action)
    {
        $user = \auth()->user();
        $organization_data = Organization::find(CustomEncoder::urlValueDecrypt($request->organization_id));
        if (!$organization_data) {
            $response = ['data' => [], 'message' => 'Organization not found', 'success' => false];
            return response()->json($response, 400);
        }

        switch ($action) {
            case "reject":
                if (!$user->userCan('admin/organizations-onboard/reject')) {
                    $response = ['data' => [], 'message' => 'Permission Denied', 'success' => false];
                    return response()->json($response, 403);
                }
                systemActivities(Auth::id(), json_encode($request->query()), "Admin rejected an organization account approval request");
                $subject = "Your account was not approved.";

                $message = "Unfortunately your account was not approved. ";
                $message .= "If you have any further questions please contact <a href='mailto:info@yieldexchange.ca'>info@yieldexchange.ca</a>.";
                $organization_data->status = 'REJECTED';
                archiveTable($organization_data->id, 'organizations', $user->id, 'Admin Control > REJECTED');
                $organization_data->is_partially_approved = 0;
                $organization_data->save();
                $this->updateOrganizationUsers($organization_data, 'REJECTED');

                Mail::to([$organization_data->admin->email])->queue(new AdminUserActionMail([
                    'message' => $message,
                    'user_type' => $organization_data->type,
                    'subject' => $subject
                ]));

                $response_message = "Account rejected successfully";
                break;
            case "activate":
                if (
                    !$user->userCan('admin/gic-investors/suspend') &&
                    !$user->userCan('admin/banks/suspend') &&
                    !$user->userCan('admin/non-partnered-fi/suspend') &&
                    !$user->userCan('admin/manage-admins/suspend')
                ) {
                    $response = ['data' => [], 'message' => 'Permission Denied', 'success' => false];
                    return response()->json($response, 403);
                }
                systemActivities(Auth::id(), json_encode($request->query()), "Admin unlocked an account");

                $subject = "Unlocked account";
                $header = "Your account has been unlocked.";
                $message = "Your account has been unlocked and is ready for use.";

                $organization_data->status = 'ACTIVE';
                //                $user_data->failed_login_attempts=0;
                archiveTable($organization_data->id, 'organizations', $user->id, 'Admin Control > Unlock Account');
                $organization_data->save();
                $this->updateOrganizationUsers($organization_data, 'ACTIVE', true);
                Mail::to([$organization_data->admin->email])->queue(new AdminUserActionMail([
                    'message' => $message,
                    'user_type' => $organization_data->type,
                    'subject' => $subject,
                    'show_login' => true
                ]));


                $response_message = "Account activated successfully";
                break;
            case "approve":
                if (!$user->userCan('admin/organizations-onboard/approve')) {
                    $response = ['data' => [], 'message' => 'Permission Denied', 'success' => false];
                    return response()->json($response, 403);
                }

                systemActivities(Auth::id(), json_encode($request->query()), "Admin approved an account");
                $subject = "Your account is ready to use";

                $message = "<center>Your Yield Exchange account is now ready for use. You can sign in and familiarize yourself with Yield Exchange. If you require a demo on how to use Yield Exchange please <a href='mailto:info@yieldexchange.ca'>email</a> us.</center><p><center>Additional resources can be found in our <a href='https://yieldexchange.tawk.help/'>FAQ</a> or you can use our <a href='" . url('/') . "'>Chat</a>.</center></p>";
                $header = "Your account setup is complete";

                $organization_data->status = 'ACTIVE';
                if ($request->enable_campaigns == "Yes") {
                    $organization_data->enable_campaigns = true;
                }

                archiveTable($organization_data->id, 'organizations', $user->id, 'Admin Control > APPROVE');
                if ($organization_data->is_partially_approved) {
                    $this->resolvePendingDepositRequests($organization_data);
                    $organization_data->is_partially_approved = NULL;
                }
                $organization_data->save();
                $responseaddingtoallgroups = $this->appendToAllDepositorsGroup(CustomEncoder::urlValueDecrypt($request->organization_id));

                $this->updateOrganizationUsers($organization_data, 'ACTIVE', true);
                if ($organization_data->is_from_conference) {
                    $password_ = getRandomNumberBetween(90000, 9999999);
                    // $password = password_hash($password_, PASSWORD_BCRYPT);
                    // UserPassword::create([
                    //     'hash' => $password,
                    //     'created_at' => getUTCDateNow(),
                    //     'user_id' => $organization_data->admin->id
                    // ]);
                    // $code=234567;
                    Mail::to([$organization_data->admin->email])->queue(new VerifyConferenceSignupMail([
                        'message' => $message,
                        'user_type' => $organization_data->type,
                        'code' => $organization_data->admin->id,
                        'password' => $password_,
                        'subject' => $subject,
                        'header' => $header,
                        'other_buttons' => [['linkName' => 'Visit FAQ', 'link' => 'https://yieldexchange.tawk.help/']],
                        'show_login' => true
                    ]));
                }
                // return $organization_data;

                $response_message = "Account approved successfully";
                break;
            case "suspend":
                if (
                    !$user->userCan('admin/gic-investors/suspend') &&
                    !$user->userCan('admin/banks/suspend') &&
                    !$user->userCan('admin/non-partnered-fi/suspend') &&
                    !$user->userCan('admin/manage-admins/suspend')
                ) {
                    $response = ['data' => [], 'message' => 'Permission Denied', 'success' => false];
                    return response()->json($response, 403);
                }
                systemActivities(Auth::id(), json_encode($request->query()), "Admin suspended an account");

                $subject = "Account suspended";
                $header = "Your account has been suspended.";
                $message = "To re-activate your account or for more information on ";
                $message .= "why your account has been suspended please contact your organization admin or <a href='mailto:nfo@yieldexchange.ca'>info@yieldexchange.ca.</a>";

                $organization_data->status = 'SUSPENDED';
                archiveTable($organization_data->id, 'organizations', $user->id, 'Admin Control > SUSPEND');
                $organization_data->save();
                $this->updateOrganizationUsers($organization_data, 'SUSPENDED');

                Mail::to([$organization_data->admin->email])->queue(new AdminUserActionMail([
                    'message' => $message,
                    'user_type' => $organization_data->type,
                    'subject' => $subject
                ]));

                $response_message = "Account suspended successfully";
                break;
            case "close":
                if (
                    !$user->userCan('admin/gic-investors/close') ||
                    !$user->userCan('admin/banks/close') ||
                    !$user->userCan('admin/non-partnered-fi/close') ||
                    !$user->userCan('admin/manage-admins/close')
                ) {
                    $response = ['data' => [], 'message' => 'Permission Denied', 'success' => false];
                    return response()->json($response, 403);
                }
                $send_mail = request('send_mail') ? request('send_mail') : 'yes';
                systemActivities(Auth::id(), json_encode($request->query()), "Admin closed an account");
                $reason = $request->reason;
                $organization_data->status = 'CLOSED';
                $organization_data->account_closure_date = getUTCDateNow();
                $organization_data->account_closure_reason = $reason;
                archiveTable($organization_data->id, 'organizations', $user->id, 'Admin Control > Closed');
                $organization_data->save();
                $this->updateOrganizationUsers($organization_data, 'CLOSED');
                //                if ($user_data->user_type== "Admin"){
                //                    $subject = "Admin Account Closure";
                //
                //                    $message = "This is to notify you that you are no longer an admin on Yield Exchange Site, terminated by ".$user->name;
                //                    $message .= "<br />";
                //                    $message .= "Thank you";
                //                }else{
                $subject = "Your Yield Exchange account has been closed.";
                $header = "Account closed";

                $message = "Your Yield Exchange account has been closed. ";
                $message .= "Please contact us at <a href='mailto:info@yieldexchange.ca'>info@yieldexchange.ca</a> for more information.";
                //                }

                if ($send_mail == "yes") {
                    Mail::to([$organization_data->admin->email])->queue(new AdminUserActionMail([
                        'message' => $message,
                        'header' => $header,
                        'user_type' => $organization_data->type,
                        'subject' => $subject
                    ]));
                }


                $response_message = "Account closed successfully";
                break;
            default:
                $response_message = "Unknown action, failed.";
                break;
        }

        if ($request->ajax()) {
            $response = ['data' => [], 'message' => $response_message, 'success' => true];
            return response()->json($response, 200);
        }
        alert()->success($response_message);
        return redirect()->back();
    }
    private function appendToAllDepositorsGroup($organization_id)
    {
        $response = [];
        try {

            DB::beginTransaction();
            $group = FICampaignGroup::where("group_name", 'All Depositors Group')->where("fi_id", 0)->first();
            //create group
            //assign group members
            $thisdepositor = Organization::where("id", $organization_id)->where("type", "DEPOSITOR")->first();
            if ($thisdepositor != null) {
                $group->depositors()->attach($thisdepositor);
                //assign member           
                $response = ["success" => true, "message" => "Organization Added successfully to the all depositors group."];
                DB::commit();
            } else {
                $response = ["success" => true, "message" => "Organization was not added successfully to the all depositors group."];
            }

            return $response;
        } catch (\Exception $exp) {
            DB::rollBack();
            return [
                "success" => false,
                'message' => 'Organization has not been added to the all depositors group.' . $exp->getMessage()
            ];
        }
    }
    private function updateOrganizationUsers($organization, $status, $only_admin = false)
    {
        try {
            if (!$only_admin) {
                $users = $organization->users();
                if ($users) {
                    foreach ($users as $user) {
                        archiveTable($user->id, 'users', auth()->id(), 'Admin Organization Control > ' . $status);
                        DB::table("users")->where('id', $user->id)->update(['account_status' => $status,/*'name'=>'qwsdas'*/]);
                    }
                }
                return;
            }
            $admin = $organization->admin;
            if ($admin) {
                $admin->account_status = $status;
                $admin->save();
            }
            DB::commit();

            archiveTable($admin->id, 'users', auth()->id(), 'Admin Organization Control > ' . $status);
        } catch (\Exception $exception) {
            Log::alert("updateOrganizationUsers");
            Log::alert($exception->getMessage());
        }
    }

    private function changeOrganizationSuperAdminUser($current_admin, $organization_id, $organization_name)
    {

        $auth_user = auth()->user();
        if (
            !$auth_user->userCan('admin/gic-investors/update-super-admin') &&
            !$auth_user->userCan('admin/banks/update-super-admin') &&
            !$auth_user->userCan('admin/non-partnered-fi/update-super-admin')
        ) {
            return '';
        }

        try {

            $users = User::select([
                'users.*',
                'creator.name as creator_name',
                //            'roles.name as role_name'
            ])->leftJoin('users as creator', 'creator.id', '=', 'users.created_by')
                ->join('users_organizations', 'users_organizations.user_id', '=', 'users.id')
                ->join('organizations', 'users_organizations.organization_id', '=', 'organizations.id')
                ->where('users_organizations.organization_id', CustomEncoder::urlValueDecrypt($organization_id))
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id');

            if ($current_admin) {
                $users = $users->where('users.id', '!=', $current_admin->id);
            }
            $users = $users->whereIn('users.account_status', systemActiveUsersStatuses())
                ->groupBy('users.id')
                ->get();
            return view('dashboard.admin.users.components.update-organization-super-admin-modal', compact('current_admin', 'organization_id', 'organization_name', 'users'))->render();
        } catch (\Throwable $e) {
            Log::alert("changeOrganizationSuperAdminUser");
            Log::alert($e->getMessage());
            return '';
        }
    }

    private function awsIntegrate($organization_id, $organization_name)
    {
        try {
            return view('dashboard.admin.users.components.add-aws-integration', compact('organization_id', 'organization_name'))->render();
        } catch (\Throwable $e) {
            return '';
        }
    }

    private function industryView($organization_id, $organization_name)
    {

        try {
            return view('dashboard.admin.users.components.update-industry-modal', compact('organization_id', 'organization_name'))->render();
        } catch (\Throwable $e) {
            return '';
        }
    }

    private function usersLimitView($current_limit, $organization_id, $organization_name)
    {
        $auth_user = \auth()->user();
        if (
            !$auth_user->userCan('admin/gic-investors/update-users-limit') &&
            !$auth_user->userCan('admin/banks/update-users-limit') &&
            !$auth_user->userCan('admin/non-partnered-fi/update-users-limit')
        ) {
            return '';
        }

        try {
            return view('dashboard.admin.users.components.update-users-limit-modal', compact('current_limit', 'organization_id', 'organization_name'))->render();
        } catch (\Throwable $e) {
            return '';
        }
    }

    public function updateIndustry(Request $request, $organization_id)
    {
        $auth_user = auth()->user();
        if (
            !$auth_user->userCan('admin/gic-investors/update-industry') &&
            !$auth_user->userCan('admin/banks/update-industry') &&
            !$auth_user->userCan('admin/non-partnered-fi/update-industry')
        ) {
            alert()->error("Permission Denied");
            return redirect()->back();
        }

        $organization_data = Organization::find(CustomEncoder::urlValueDecrypt($organization_id));
        if (!$organization_data) {
            alert()->error("Organization not found");
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'industry_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            alert()->error("Failed to update");
            return redirect()->back();
        }

        archiveTable($organization_data->id, 'organizations', \auth()->id(), 'industry_id');
        $organization_data->industry_id = $request->industry_id;
        $organization_data->save();

        systemActivities(Auth::id(), json_encode($request->query()), "Admin updated organization Industry");
        alert()->success("Industry updated successfully");
        return redirect()->back();
    }

    public function updateUsersLimit(Request $request, $organization_id)
    {
        $auth_user = auth()->user();
        if (
            !$auth_user->userCan('admin/gic-investors/update-users-limit') &&
            !$auth_user->userCan('admin/banks/update-users-limit') &&
            !$auth_user->userCan('admin/non-partnered-fi/update-users-limit')
        ) {
            $response = ['data' => [], 'message' => 'Permission Denied', 'success' => false];
            return response()->json($response, 403);
        }

        $organization_data = Organization::find(CustomEncoder::urlValueDecrypt($organization_id));
        if (!$organization_data) {
            $response = ['data' => [], 'message' => 'Organization not found', 'success' => false];
            return response()->json($response, 400);
        }

        $validator = Validator::make($request->all(), [
            'users_limit' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        archiveTable($organization_data->id, 'organizations', \auth()->id(), 'users_limit');
        $organization_data->users_limit = $request->users_limit;
        $organization_data->save();

        systemActivities(Auth::id(), json_encode($request->query()), "Admin updated organization users limit");
        $response = array("success" => true, "message" => "User's limit updated successfully", "data" => []);
        return response()->json($response, 200);
    }

    public function assignSuperAdmin(Request $request, $organization_id)
    {
        $auth_user = auth()->user();
        if (
            !$auth_user->userCan('admin/gic-investors/update-super-admin') &&
            !$auth_user->userCan('admin/banks/update-super-admin') &&
            !$auth_user->userCan('admin/non-partnered-fi/update-super-admin')
        ) {
            $response = ['data' => [], 'message' => 'Permission Denied', 'success' => false];
            return response()->json($response, 403);
        }

        $organization_data = Organization::find(CustomEncoder::urlValueDecrypt($organization_id));
        if (!$organization_data) {
            $response = ['data' => [], 'message' => 'Organization not found', 'success' => false];
            return response()->json($response, 400);
        }

        $validator = Validator::make($request->all(), [
            'new_admin_id' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        archiveTable($organization_data->id, 'organizations', \auth()->id(), 'admin_user_id');
        $organization_data->admin_user_id = $request->new_admin_id;
        $organization_data->save();

        $role = Role::where('name', 'organization-administrator')->first();
        if (!$role) {
            $role = Role::create([
                'name' => 'organization-administrator',
                'display_name' => 'Organization Administrator',
                'description' => 'The Overall Organization managers',
                'organization_id' => 0
            ]);
        }

        DB::table('role_user')->where('user_id', $request->new_admin_id)->update([
            'role_id' => $role->id,
            'user_type' => $role->display_name
        ]);

        systemActivities(Auth::id(), json_encode($request->query()), "Admin updated organization users admin");
        $response = array("success" => true, "message" => "Organization admin updated successfully", "data" => []);
        return response()->json($response, 200);
    }
    public function markUnmarkAstest(Request $request, $user_type, $organization_id)
    {
        $auth_user = auth()->user();
        if (
            !$auth_user->userCan('admin/organizations-onboard/mark-as-test') &&
            !$auth_user->userCan('admin/gic-investors/mark-as-test') &&
            !$auth_user->userCan('admin/non-partnered-fi/mark-as-test') &&
            !$auth_user->userCan('admin/banks/mark-as-test')
        ) {
            $response = ['data' => [], 'message' => 'Permission Denied', 'success' => false];
            return response()->json($response, 403);
        }

        if (!empty($user_type) && !empty($organization_id)) {
            $organization_id = CustomEncoder::urlValueDecrypt($organization_id);
            $org = Organization::find($organization_id);
            if ($org) {
                $is_test = $org->is_test ? 0 : 1;

                User::where('organizations.id', $org->id)
                    ->join('users_organizations', 'users_organizations.user_id', '=', 'users.id')
                    ->join('organizations', 'users_organizations.organization_id', '=', 'organizations.id')
                    //                    ->whereIn('account_status',systemActiveUsersStatuses())
                    ->update([
                        'users.is_test' => $is_test
                    ]);

                $org->is_test = $is_test;
                $org->save();

                //            $user = User::where('id',$user_org->admin_user_id)->first();
                //            if($user->is_test == 0  || $user_org->is_test == 0){
                //                $user->is_test =1;
                //                $user_org->is_test =1;
                //            } else if($user->is_test == 1 || $user_org->is_test == 1){
                //                $user->is_test =0;
                //                $user_org->is_test =0;
                //            }

                //            $user->save();
            }
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Marking/Unmarking as test");
        return redirect()->back();
    }

    public function enable_campaigns(Request $request)
    {
        $user = \auth()->user();
        if (!$user || !$user->is_super_admin) {
            alert()->error("Permission denied");
            return redirect()->back();
        }

        $success = false;
        $action = '';
        if ($request->filled('organization_id')) {
            $organization_id = CustomEncoder::urlValueDecrypt($request->organization_id);
            $org = Organization::find($organization_id);
            if ($org) {
                $action = $org->enable_campaigns ? "Disabled" : "Enabled";
                $org->enable_campaigns = $org->enable_campaigns ? 0 : 1;
                $org->save();
                $success = true;
            }
        }

        if ($success) {
            alert()->success($action . " campaigns successfully");
        } else {
            alert()->error("Failed $action campaigns");
        }

        systemActivities(Auth::id(), json_encode($request->query()), "$action campaigns");
        return redirect()->back();
    }
    public function updateOrganizationLevel(Request $request)
    {
        $user = \auth()->user();
        if (!$user || !$user->is_super_admin) {
            alert()->error("Permission denied");
            return redirect()->back();
        }

        $success = false;
        $action = '';
        if ($request->filled('organization_id')) {
            $organization_id = CustomEncoder::urlValueDecrypt($request->organization_id);
            $org = Organization::find($organization_id);
            if ($org) {
                $action = $org->enable_campaigns ? "Disabled" : "Enabled";
                $org->enable_campaigns = $org->enable_campaigns ? 0 : 1;
                $org->save();
                $success = true;
            }
        }
        // Disable unselected
        $organization_id = CustomEncoder::urlValueDecrypt($request->organization);
        if (!$request->filled("permissions")) {
            OrganizationLevelPermission::where('organization_id', $organization_id)
                ->update(['status' => 'Inactive']);
            $success = true;
        } else {
            $allcurrents = OrganizationLevelPermission::where('organization_id', $organization_id)
                ->pluck('org_permissions_list_permission_id')->toArray();

            foreach ($allcurrents as $current) {

                if (!in_array($current, $request->permissions)) {
                    OrganizationLevelPermission::where('organization_id', $organization_id)
                        ->where('org_permissions_list_permission_id', $current)
                        ->update(['status' => 'Inactive']);
                } else {
                    OrganizationLevelPermission::where('organization_id', $organization_id)
                        ->where('org_permissions_list_permission_id', $current)
                        ->update(['status' => 'Active']);
                }
                $success = true;
            }
            foreach ($request->permissions as $permission) {
                $permlevel = [
                    'organization_id' => $organization_id,
                    'org_permissions_list_permission_id' => $permission,
                ];
                if (OrganizationLevelPermission::where($permlevel)->exists()) {
                    OrganizationLevelPermission::where($permlevel)->update(['status' => 'Active']);
                } else {
                    OrganizationLevelPermission::create(array_merge($permlevel, ['status' => 'Active']));
                }
                $success = true;
            }

            // Update master table

        }



        if ($success) {

            return response()->json(['message' => 'Permissions updated successfully'], 200);
            // alert()->success(" Permissions Updated successfully");
        } else {
            return response()->json(['message' => 'Failed To Update Permissions'], 400);
            // alert()->error("Failed To Update Permissions");
        }

        systemActivities(Auth::id(), json_encode($request->query()), "$action campaigns");
    }

    public function allow_multi_organizations(Request $request)
    {
        $user = \auth()->user();
        if (!$user || !$user->is_super_admin) {
            alert()->error("Permission denied");
            return redirect()->back();
        }

        $success = false;
        if ($request->filled('organization_id')) {
            $organization_id = CustomEncoder::urlValueDecrypt($request->organization_id);
            $org = Organization::find($organization_id);
            if ($org) {
                $org->allow_multi_organizations = $org->allow_multi_organizations ? 0 : 1;;
                $org->save();
                $success = true;
            }
        }

        if ($success) {
            if ($org->allow_multi_organizations == 1) {
                alert()->success("Allowed multi organization successfully");
            } else {
                alert()->success("Disallowed multi organization successfully");
            }
        } else {
            alert()->error("Failed to allow multi organization");
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Setting multi organization ");
        return redirect()->back();
    }

    public function adminCreate(Request $request)
    {
        $user = auth()->user();
        if (!$user->userCan('admin/manage-admins/edit') && !$user->userCan('admin/manage-admins/create')) {
            $response = array("success" => false, "message" => "Access Denied", "data" => []);
            return response()->json($response, 403);
        }

        $validation_data = [
            'firstname' => 'required|string|max:25',
            'lastname' => 'required|string|max:25',
            'timezone' => 'required|string',
            'email' => 'required|email|max:50',
            'department' => 'nullable|max:50',
            'job_title' => 'required|string|max:50',
            'role_id' => 'required|integer'
        ];


        $validator = Validator::make($request->all(), $validation_data);
        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        if ($request->filled('user_id')) {
            if (!User::find($request->user_id)) {
                $response = array("success" => false, "message" => "Admin not found", "data" => []);
                return response()->json($response, 400);
            }
        }

        $role = Role::find($request->role_id);
        if (!$role) {
            $response = array("success" => false, "message" => "Role does not exist", "data" => []);
            return response()->json($response, 400);
        }

        $email = trim(strtolower($request->email));

        $user_exits = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where(function ($query) {
                $query->where('users.is_system_admin', 1)
                    ->orWhere('roles.name', '=', 'system-administrator');
            })
            ->where('email', '=', $email)
            ->whereNotIn('account_status', ['CLOSED']);

        if ($request->filled('user_id')) {
            $user_exits = $user_exits->where('users.id', '!=', $request->user_id);
        }
        $user_exits = $user_exits->first();

        if ($user_exits) {
            $response = array("success" => false, "message" => "Admin already exists", "data" => []);
            return response()->json($response, 409);
        }

        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            if (!$user) {
                $response = array("success" => true, "message" => "Admin not found", "data" => []);
                return response()->json($response, 400);
            }

            $user->update([
                'name' => $request->firstname . ' ' . $request->lastname,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $email,
                'modified_by' => auth()->user()->id,
                'modified_date' => getUTCDateNow()
            ]);

            if (DB::table('role_user')
                ->where('user_id', $request->user_id)
                ->first()
            ) {
                DB::table('role_user')
                    ->where('user_id', $request->user_id)
                    ->update([
                        'role_id' => $role->id,
                        'user_type' => $role->display_name
                    ]);
            } else {
                DB::table('role_user')->insert([
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                    'user_type' => $role->display_name,
                ]);
            }

            //$user->roles()->sync([$role->id]);

            $user_demo = UsersDemoGraphicData::where('user_id', $request->user_id)->first();
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
            } else {
                UsersDemoGraphicData::create([
                    'phone' => $request->phone,
                    'department' => $request->department,
                    'timezone' => $request->timezone,
                    'job_title' => $request->job_title,
                    'city' => $request->city,
                    'province' => $request->province,
                    'updated_at' => getUTCDateNow(),
                    'user_id' => $user->id
                ]);
            }

            $response = array("success" => true, "message" => "User updated", "data" => []);
            return response()->json($response, 200);
        }

        try {
            Db::beginTransaction();
            $created_user = User::create([
                'name' => $request->firstname . ' ' . $request->lastname,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $email,
                'created_by' => auth()->user()->id,
                'account_opening_date' => getUTCDateNow(),
                'account_status' => 'ACTIVE',
                'is_non_partnered_fi' => false,
                'failed_login_attempts' => 0,
                'is_system_admin' => 1,
                'requires_password_update' => true
            ]);

            if (!$created_user) {
                $response = array("success" => false, "message" => "Failed, unable to create the user", "data" => []);
                return response()->json($response, 400);
            }

            UsersDemoGraphicData::create([
                'user_id' => $created_user->id,
                'phone' => $request->phone,
                'department' => $request->department,
                'timezone' => $request->timezone,
                'job_title' => $request->job_title,
                'city' => $request->city,
                'province' => $request->province,
                'modified_date' => getUTCDateNow()
            ]);

            $password_ = getRandomNumberBetween(90000, 9999999);
            $password = password_hash($password_, PASSWORD_BCRYPT);
            UserPassword::create([
                'hash' => $password,
                'created_at' => getUTCDateNow(),
                'user_id' => $created_user->id
            ]);

            //            $super_admin_role = DB::table('roles')->where('name','system-administrator')->first();
            DB::table('role_user')->insert([
                'role_id' => $role->id,
                'user_id' => $created_user->id,
                'user_type' => $role->display_name
            ]);

            //            $user->roles()->attach([$role->id]);

            try {
                Artisan::call("cache:clear");
            } catch (\Exception $exception) {
            }

            Mail::to($created_user->email)->queue(new NewUserPasswordMail([
                'password' => $password_,
                'user_type' => "Admin"
            ]));

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $timestamp = time();
            Log::error($timestamp . ': ' . $exception->getTraceAsString());
            loginActivities("Admin sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            $response = array("success" => true, "message" => "Unable to create admin", "data" => []);
            return response()->json($response, 400);
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Admin created");
        $response = array("success" => true, "message" => "Admin created", "data" => []);
        return response()->json($response, 200);
    }

    private function enableVisibilityView($organization)
    {
        $auth_user = \auth()->user();
        if (!$auth_user->userCan('admin/banks/enable-visibility')) {
            return '';
        }

        try {
            return view('dashboard.admin.users.components.enable-visibility', compact('organization'))->render();
        } catch (\Throwable $e) {
            return '';
        }
    }

    private function resolvePendingDepositRequests($organization_data)
    {
        DepositRequest::where('organization_id', $organization_data->id)
            ->where('request_status', 'ON_REVIEW')
            ->chunkById(100, function ($requests) {
                foreach ($requests as $request) {
                    foreach ($request->invited as $invited) {
                        $bank = $invited->organization;
                        $user_objects = $bank->notifiableUsersEmails($return_emails = false);
                        for ($i = 0; $i < count($user_objects); $i++) {
                            $bank_timezone = $user_objects[$i]->demographicData->timezone;
                            $datetime_from_utc = changeDateFromUTCtoLocal($request->closing_date_time, $format = 'Y-m-d h:i a', null, $bank_timezone) . ' ' . $bank_timezone;
                            $message = "<p><center>" . $request->name . " has invited you to a deposit of " . $request->currency . " " . number_format($request->amount) . ". If you are interested in putting in an offer, please respond before " . $datetime_from_utc . "</center></p>";
                            Mail::to($user_objects[$i]->email)->queue(new BankMails([
                                'subject' => "You have a deposit opportunity",
                                'new_post_request' => ['deposit' => $request, 'depositor' => $request->organization, 'closing_date' => $datetime_from_utc],
                                'user_type' => "Bank"
                            ], 'new_post_request_offer'));
                        }

                        $invited->invitation_status = 'INVITED';
                        $invited->save();
                    }

                    $request->request_status = 'ACTIVE';
                    $request->save();
                }
            });
    }
    public function awsIntegrte(Request $request, $organization_id)
    {
        $organization_data = Organization::find(CustomEncoder::urlValueDecrypt($organization_id));
        if (!$organization_data) {
            alert()->error("Organization not found");
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'file_type' => 'required',
            'routing_agent' => 'required',
            'delivery_method' => 'required'
        ]);

        if ($validator->fails()) {
            alert()->error("Failed to update");
            return redirect()->back();
        }

        AWSFileRouting::create([
            'file_type' => $request->file_type,
            'routing_agent' => $request->routing_agent,
            'delivery_method' => $request->delivery_method,
            'organization_id' => CustomEncoder::urlValueDecrypt($organization_id)
        ]);

        systemActivities(Auth::id(), json_encode($request->query()), "Added AWS integration");
        alert()->success("Added aws integration successfully");
        return redirect()->back();
    }
}

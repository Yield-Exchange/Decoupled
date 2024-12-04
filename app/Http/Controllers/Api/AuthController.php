<?php
namespace App\Http\Controllers\Api;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Mail\NewWelcomeMail;
use App\Mail\ResetPasswordConfirmation;
use App\Mail\ResetPasswordMail;
use App\Mail\VerifyEmailMail;
use App\Models\OrganizationDemoGraphicData;
use App\Models\DepositCreditRating;
use App\Models\Organization;
use App\Models\OTP;
use App\Models\PasswordResets;
use App\Models\Preference;
use App\Models\UserOrganization;
use App\Models\UsersDemoGraphicData;
use App\Role;
use App\User;
use App\Models\UserPassword;
use App\Models\UserPreference;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\AccountLockedMail;
use App\Mail\AdminMail;
use App\Mail\AdminMails;
use App\Mail\OTPMail;
use App\Mail\RegistrationMail;
use App\CustomEncoder;
use App\Models\UsersIPAddress;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function loginPost(Request $request){

        $clientIp = $request->header('X-Client-IP');       
     
        $validation_rules = [
            'email' => 'required|email',
            'password' => 'required',
            // 'loginAs' => 'required|in:Bank,Depositor'
        ];

        if (is_admin_route($request)) {
            $validation_rules['loginAs'] = 'required|in:Admin';
        } else {
            if ($request->filled('loginAs') && $request->loginAs == "Admin") {
                $response = array("success" => false, "message" => "Unauthorized access", "data" => []);
                return response()->json($response, 401);
            }
        }

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        if (getCookie($request, Constants::COOKIE_FAILED_LOGIN_ATTEMPTS) > Constants::FAILED_LOGIN_ATTEMPT_LIMIT) {
            loginActivities("User login attempt failed, Exceeded the failed login attempts limit", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => "Your account has been locked, " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 400);
        }

        $email = trim(strtolower($request->email));

        $user = User::select([
            'users.*',
            'organizations.is_non_partnered_fi as organization_is_non_partnered_fi',
            'organizations.status as organization_status',
            'organizations.id as organization_logged_in'
        ])->with(['userPassword'])
            ->leftJoin('users_organizations', 'users_organizations.user_id', '=', 'users.id')
            ->leftJoin('organizations', 'users_organizations.organization_id', '=', 'organizations.id')
            ->whereIn('account_status', systemActiveUsersStatuses())
            ->where('email', $email);

        if (is_admin_route($request)) {
            $user = $user->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->where(function ($query) {
                    $query->where('roles.name', '=', 'system-administrator')
                        ->orWhere('users.is_system_admin', 1);
                });
        } else {
            $user = $user->whereIn('organizations.status', systemActiveOrganizationStatuses(true))
                 ->where('users_organizations.is_default', 1);
        }
        $user = $user->first();

        if (!$user) {
            $attempts = getCookie($request, Constants::COOKIE_FAILED_LOGIN_ATTEMPTS) > 0 ? getCookie($request, Constants::COOKIE_FAILED_LOGIN_ATTEMPTS) : 0;
            switch ($attempts) {
                case Constants::FAILED_LOGIN_ATTEMPT_LIMIT:
                    $response_message = "Your account has been locked, " . Constants::RESPONSE_MESSAGE_CONTACT_US;
                    $alert_class = "alert-danger";
                    break;
                default:
                    $attempts++;
                    if ($attempts == Constants::FAILED_LOGIN_ATTEMPT_LIMIT) {
                        $response_message = "Incorrect Email or Password. This is your last attempt until your account is locked.";
                        $alert_class = "alert-danger";
                    } else if ($attempts > Constants::FAILED_LOGIN_ATTEMPT_LIMIT) {
                        $response_message = "Your account has been locked, " . Constants::RESPONSE_MESSAGE_CONTACT_US;
                        $alert_class = "alert-danger";
                    } else {
                        $response_message = " Incorrect Email or Password. You have " . (Constants::FAILED_LOGIN_ATTEMPT_LIMIT - $attempts) . " more tries until your account is locked.";
                        $alert_class = "alert-warning";
                    }
            }

            loginActivities("User login attempt failed, email not found in the database ", $request->server('HTTP_USER_AGENT'), 0);
            $response = ["message" => $response_message, "data" => [], "success" => false, "alert_class" => $alert_class];
            if ($attempts > 1) {
                return response()->json($response, 401)->cookie(Constants::COOKIE_FAILED_LOGIN_ATTEMPTS, $attempts);
            } else {
                return response()->json($response, 401)->cookie(Constants::COOKIE_FAILED_LOGIN_ATTEMPTS, $attempts, time() + (3600 * 10)/* expire 10 minutes from now*/);
            }
        }

        if (empty($user->userPassword[0])) {
            loginActivities("User login attempt failed, no password set in the database", $request->server('HTTP_USER_AGENT'), $user->id);
            return response()->json(["message" => 'Invalid username or password', "data" => [], "success" => false], 401);
        }

        $latestPassWord = $user->userPassword[0];
        if (!password_verify($request->password, $latestPassWord->hash)) {
            $attempts = $user->failed_login_attempts;
            switch ($attempts) {
                case Constants::FAILED_LOGIN_ATTEMPT_LIMIT:
                    archiveTable($user->id, "users", $user->id, "LOCKED");
                    $user->failed_login_attempts = Constants::FAILED_LOGIN_ATTEMPT_LIMIT;
                    $response_message = "Your account has been locked, " . Constants::RESPONSE_MESSAGE_CONTACT_US;
                    $alert_class = "alert-danger";
                    if ($user->account_status != "LOCKED") {
                        Mail::to($user->email)->queue(new AccountLockedMail([
                            'user_type' => $request->loginAs,
                            'message' => 'Your account has been locked'
                        ]));

                        $adminUsersEmails = [];
                        $user_organization = $user->organization;
                        if (!empty($user_organization)) {
                            $adminUsersEmails = $user_organization->adminUsersEmails($user->id);
                        }

                        Mail::to(array_merge(getAdminEmails($user), $adminUsersEmails))->queue(new AdminMail([
                            'subject' => 'User account has been locked!',
                            'message' => "User account with email " . $user->email . " is locked! Please do a followup on the account.",
                        ]));
                        $user->account_status = "LOCKED";
                        $user->save();
                    }
                    break;
                default:
                    archiveTable($user->id, "users", $user->id, "failed_login_attempts");
                    $user->failed_login_attempts++;
                    $user->save();

                    if ($user->failed_login_attempts == Constants::FAILED_LOGIN_ATTEMPT_LIMIT) {
                        $alert_class = "alert-danger";
                        $response_message = "Incorrect Email or Password. This is your last attempt until your account is locked.";
                    } else if ($user->failed_login_attempts > Constants::FAILED_LOGIN_ATTEMPT_LIMIT) {
                        if ($user->account_status != "LOCKED") {
                            archiveTable($user->id, "users", $user->id, "LOCKED");
                            $user->failed_login_attempts = Constants::FAILED_LOGIN_ATTEMPT_LIMIT;
                            $user->account_status = "LOCKED";
                            $user->save();
                        }
                        $response_message = "Your account has been locked, " . Constants::RESPONSE_MESSAGE_CONTACT_US;
                        $alert_class = "alert-danger";
                    } else {
                        $response_message = " Incorrect Email or Password. You have " . (Constants::FAILED_LOGIN_ATTEMPT_LIMIT - $user->failed_login_attempts) . " more tries until your account is locked.";
                        $alert_class = "alert-warning";
                    }
            }

            loginActivities("User login attempt failed, password is incorrect", $request->server('HTTP_USER_AGENT'), $user->id);
            return response()->json(["message" => $response_message, "data" => [], "success" => false, "alert_class" => $alert_class], 401);
        }
        if ($user->account_status == "LOCKED") {
            loginActivities("User login attempt failed, account status is locked", $request->server('HTTP_USER_AGENT'), $user->id);
            return response()->json(["message" => "Your account has been locked, " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 401);
        }
        if ($user->isAbleToLogin()) {
            // The account is OKAY!
            $user->failed_login_attempts = 0;
            if (!empty($user->organization_logged_in)) { // log in the user to correct requested organization type
                $user->switched_organization_id = $user->organization_logged_in;
            }
            $user->admin_loggedin_as=NULL;
            $user->save();
            session()->put('user_id', $user->encoded_user_id);
            // delete all old OTP attempts before generating a new one
            OTP::where('user_id', $user->id)->delete();

            if (!can_skip_robot_check_and_otp()) {
                $pin = getRandomNumberBetween(9999, 99999);
                OTP::create([
                    'pin' => password_hash($pin, PASSWORD_BCRYPT),
                    'user_id' => $user->id,
                    'created_at' => getUTCDateNow()
                ]);

                Mail::to($user->email)->send(new OTPMail([
                    'pin' => $pin,
                    'user_type' => $request->loginAs
                ]));

                loginActivities("User login attempt successful", $request->server('HTTP_USER_AGENT'), $user->id);
                $user->unsetRelation('userPassword'); // do not return any password to the client
                return response()->json(["message" => "Kindly check your Email. Pin code has been sent.", "data" => $user, "success" => true], 200);
            } else {

                $utc_now = getUTCTimeNow();
                // now authenticate the user!!
                Auth::login(User::find($user->id)); //The given User object must be an implementation of the Illuminate\Contracts\Auth\Authenticatable
                archiveTable($user->id, 'users', $user->id, "last_login");
                $user->last_login = $utc_now;
                $user->save();

                $organization = $user->organization;
                if ($organization) {
                    archiveTable($organization->id, 'organizations', $user->id, "last_login");
                    $organization->update([
                        'last_login' => $utc_now
                    ]);
                }
                $request->session()->put('user_id', CustomEncoder::urlValueEncrypt($user->id));
                $request->session()->put('my_ip', $clientIp);
                loginActivities("User logged successfully",$request->server('HTTP_USER_AGENT'),$user->id);

             
                return response()->json(["message" => 'User logged successfully', "data" => $user, "success" => true], 200);

            }

        }

        $message = "Your account has not been activated, " . Constants::RESPONSE_MESSAGE_CONTACT_US;
//        if ($user->account_status != "PENDING") {
//            $message = Constants::RESPONSE_MESSAGE_CONTACT_US . ' to sign in.';
//        }

        loginActivities("User login attempt failed, account status is " . strtolower($user->account_status), $request->server('HTTP_USER_AGENT'), $user->id);
        return response()->json(["message" => $message, "data" => [], "success" => false], 403);
    }

    public function resendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        if( $request->user_id != $request->session()->get('user_id') ){
            loginActivities("User login resend OTP attempt failed, User has not been authenticated yet ",$request->server('HTTP_USER_AGENT'),0);
            return response()->json(["message"=>"User has not been authenticated yet","data"=>[],"success"=>false],401);
        }

        $user = User::select([
            'users.*',
            'organizations.is_non_partnered_fi as organization_is_non_partnered_fi',
            'organizations.status as organization_status',
            'organizations.type as organization_type'
        ])->with(['otp'])
            ->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('account_status',systemActiveUsersStatuses());

        if(is_admin_route($request)){
            $user = $user->join('role_user','role_user.user_id','=','users.id')
                ->join('roles','role_user.role_id','=','roles.id')
               ->where(function($query){
                    $query->where('roles.name','=','system-administrator')
                        ->orWhere('users.is_system_admin',1);
                });
        }else{
            $user = $user->whereIn('organizations.status',systemActiveOrganizationStatuses(true))
                ->whereIn('organizations.type',['BANK','DEPOSITOR']);
        }
        $user= $user->where('users.id',CustomEncoder::urlValueDecrypt($request->user_id))->first();

        if(!$user){
            loginActivities("User login resend OTP attempt failed, user_id provided does not exist in the database",$request->server('HTTP_USER_AGENT'),0);
            return response()->json(["message"=>"User not found","data"=>[],"success"=>false],401);
        }


        // check if account is locked
        if($user->account_status == "LOCKED"){
            loginActivities("User login resend OTP attempt failed, account status is locked",$request->server('HTTP_USER_AGENT'),$user->id);
            return response()->json(["message"=>"Your account has been locked, ".Constants::RESPONSE_MESSAGE_CONTACT_US,"data"=>[],"success"=>false],401);
        }

        if( $user->isAbleToLogin()){
            // The account is OKAY!
            $user->failed_login_attempts=0;
            $user->save();

            // delete all old OTP attempts before generating a new one
            OTP::where('user_id',$user->id)->delete();

            $pin = getRandomNumberBetween(9999,99999);
            OTP::create([
                'pin'=> password_hash($pin,PASSWORD_BCRYPT),
                'user_id'=> $user->id,
                'created_at'=> getUTCDateNow()
            ]);


            Mail::to($user->email)->send(new OTPMail([
                'pin'=>$pin,
                'user_type'=>get_user_type($user)
            ]));

            loginActivities("User resend pin attempt successful",$request->server('HTTP_USER_AGENT'),$user->id);
            $user->unsetRelation('otp'); // do not return any password to the client
            return response()->json(["message"=>"Pin resent successfully to your email","data"=>$user,"success"=>true],200);
        }

        $message = "Your account has not been activated, ".Constants::RESPONSE_MESSAGE_CONTACT_US;
//        if($user->account_status != "PENDING"){
//            $message = Constants::RESPONSE_MESSAGE_CONTACT_US.' to sign in.';
//        }

        loginActivities("User login attempt failed, account status is ".strtolower($user->account_status),$request->server('HTTP_USER_AGENT'),$user->id);
        return response()->json(["message"=>$message,"data"=>[],"success"=>false],403);

    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'pin' => 'required'
        ]);
        
        $clientIp = $request->header('X-Client-IP'); 

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        if ($request->user_id != $request->session()->get('user_id')) {
            loginActivities("User login verify OTP attempt failed, User has not been authenticated yet ", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => "User has not been authenticated yet", "data" => [$request->session()->get('user_id')], "success" => false], 401);
        }

        $user = User::select([
            'users.*',
            'organizations.is_non_partnered_fi as organization_is_non_partnered_fi',
            'organizations.status as organization_status',
            'organizations.type as organization_type'
        ])->with(['otp'])
            ->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('account_status',systemActiveUsersStatuses());

            if(is_admin_route($request)){
                $user = $user->join('role_user','role_user.user_id','=','users.id')
                    ->join('roles','role_user.role_id','=','roles.id')
                    ->where(function($query){
                        $query->where('roles.name','=','system-administrator')
                            ->orWhere('users.is_system_admin',1);
                    });
            }else{
                $user = $user->whereIn('organizations.status',systemActiveOrganizationStatuses(true))
                    ->whereIn('organizations.type',['BANK','DEPOSITOR']);
            }
            $user = $user->where('users.id',CustomEncoder::urlValueDecrypt($request->user_id))->first();

        if (!$user) {
            loginActivities("User login verify OTP attempt failed, user_id provided does not exist in the database", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => "User not found", "data" => [], "success" => false], 401);
        }


        // check if account is locked
        if ($user->account_status == "LOCKED") {
            loginActivities("User login verify OTP attempt failed, account status is locked", $request->server('HTTP_USER_AGENT'), $user->id);
            return response()->json(["message" => "Your account has been locked, " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 401);
        }

        if ( $user->isAbleToLogin() ){

            if( empty($user->otp[0]) ){
                loginActivities("User login attempt failed, no pin set in the database",$request->server('HTTP_USER_AGENT'),$user->id);
                return response()->json(["message" => "Invalid pin, resend the pin." . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 401);
            }

            $usertOtp = $user->otp[0];
            if( !password_verify($request->pin,$usertOtp->pin) ){
                $attempts = $user->failed_login_attempts;

                switch($attempts){
                    case Constants::FAILED_LOGIN_ATTEMPT_LIMIT:
                        archiveTable($user->id,"users",$user->id,"LOCKED");
                        $user->failed_login_attempts=Constants::FAILED_LOGIN_ATTEMPT_LIMIT;
                        $response_message="Your account has been locked, ".Constants::RESPONSE_MESSAGE_CONTACT_US;
                        $alert_class="alert-danger";
                        if ($user->account_status != "LOCKED") {
                            Mail::to($user->email)->queue(new AccountLockedMail([
                                'user_type' => get_user_type($user),
                                'message' => 'Your account has been locked'
                            ]));

                            $adminUsersEmails=[];
                            $user_organization = $user->organization;
                            if(!empty($user_organization)){
                                $adminUsersEmails = $user_organization->adminUsersEmails($user->id);
                            }

                            Mail::to(array_merge(getAdminEmails($user),$adminUsersEmails))->queue(new AdminMail([
                                'subject' => 'User account has been locked!',
                                'message' => "User account with email " . $user->email . " is locked! Please do a followup on the account.",
                            ]));
                            $user->account_status = "LOCKED";
                            $user->save();
                        }
                        break;
                    default:
                        archiveTable($user->id,"users",$user->id,"failed_login_attempts");
                        $user->failed_login_attempts++;
                        $user->save();

                        if( $attempts == Constants::FAILED_LOGIN_ATTEMPT_LIMIT ){
                            $alert_class="alert-danger";
                            $response_message="Incorrect pin. This is your last attempt until your account is locked.";
                        }else if($attempts > Constants::FAILED_LOGIN_ATTEMPT_LIMIT){
                            if( $user->account_status!="LOCKED" ){
                                archiveTable($user->id,"users",$user->id,"LOCKED");
                                $user->failed_login_attempts=Constants::FAILED_LOGIN_ATTEMPT_LIMIT;
                                $user->account_status="LOCKED";
                                $user->save();
                            }
                            $response_message="Your account has been locked, ".Constants::RESPONSE_MESSAGE_CONTACT_US;
                            $alert_class="alert-danger";
                        }else{
                            $response_message="Incorrect pin. You have " .(Constants::FAILED_LOGIN_ATTEMPT_LIMIT-$user->failed_login_attempts). " more tries until your account is locked.";
                            $alert_class="alert-warning";
                        }
                        break;
                }

                loginActivities("User login attempt failed, ".$response_message,$request->server('HTTP_USER_AGENT'),$user->id);
                return response()->json(["message" => $response_message, "data" => [$user], "success" => false,"alert_class"=>$alert_class], 403);
            }

            // The account is OKAY!
            archiveTable($user->id,'users',$user->id,"failed_login_attempts");
            $user->failed_login_attempts = 0;
            $user->save();

            $otp_created_at = Carbon::parse($usertOtp->created_at);
            $utc_now = getUTCTimeNow();

            if ( $utc_now->diffInMinutes($otp_created_at) > 30 ){ // updated to 30 minutes from 5 minutes
                loginActivities("User login attempt failed, pin has expired",$request->server('HTTP_USER_AGENT'),$user->id);
                return response()->json(["message" => 'The pin has expired, get a new one', "data" => [], "success" => false], 403);
            }

            // delete all old OTP attempts for authentication
            OTP::where('user_id',$user->id)->delete();

            // now authenticate the user!!
            Auth::login(User::find($user->id)); //The given User object must be an implementation of the Illuminate\Contracts\Auth\Authenticatable
            archiveTable($user->id,'users',$user->id,"last_login");
            $user->last_login = $utc_now;
            $user->save();

            $organization = $user->organization;
            if($organization){
                archiveTable($organization->id,'organizations',$user->id,"last_login");
                $organization->update([
                    'last_login'=>$utc_now
                ]);
                 //update Login Count
                $thisorg = Organization::find($organization->id);
                $thisorg->increment('login_count');
                //update Login Count
            }
           if($user->is_system_admin){
                UsersIPAddress::where("user_id",$user->id)->where("status","ACTIVE")->update(['status'=>'EXPIRED','logged_out_at'=>Carbon::now()->utc()]);
                UsersIPAddress::create([
                    'user_id' => $user->id,  
                    'logged_in_at' => Carbon::now()->utc(),  
                    'logged_ip' => $clientIp,  
                    'login_as_admin_token' => getRandomNumberBetween(9999,99999),
                    'status' => 'ACTIVE',
                ]);
            }   
            $request->session()->put('my_ip', $clientIp);
            loginActivities("User verified pin successfully",$request->server('HTTP_USER_AGENT'),$user->id);
            return response()->json(["message" => 'Pin verified successfully', "data" => $user, "success" => true], 200);
        }

        $message = "Your account has not been activated, ".Constants::RESPONSE_MESSAGE_CONTACT_US;
//        if($user->account_status != "PENDING"){
//            $message = Constants::RESPONSE_MESSAGE_CONTACT_US.' to sign in.';
//        }

        loginActivities("User login attempt failed, account status is ".strtolower($user->account_status),$request->server('HTTP_USER_AGENT'),$user->id);
        return response()->json(["message"=>$message,"data"=>[],"success"=>false],403);
    }

    public function logout(Request $request)
    {
        if(!Auth::check()){
            return response()->json(["message" => 'Not logged in yet', "data" => [], "success" => false], 401);
        }

        $this->abandonMessageDepositor(\auth()->user());

        $user_id=Auth::id();
        $request->session()->flush();
        Auth::logout();

        loginActivities("User Logged Out successfully",$request->server('HTTP_USER_AGENT'),$user_id);
        return response()->json(["message" => 'Logged Out successfully', "data" => [], "success" => true], 200);
    }

    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userType' => 'required|in:Bank,Depositor',
            'timezone' => 'required',
            'institution_name' => 'required|max:50',
            'institution_type' => 'required_if:userType,in:Bank',
            'naics_code' => 'required',
            'address' => 'required|max:150',
            'city' => 'required|max:50',
            'province' => 'required',
            'postal' => 'required|max:10',
            'email' => 'required|email|max:50',
            'telephone' => 'required',
            'description' => 'string|nullable',
            'potential_deposit' => 'required_if:userType,in:Depositor',
            'wholesale_deposit_portfolio'=> 'required_if:userType,in:Bank',
            'pass' => 'required|required_with:cpass|same:conf_password',
            'last_name'=>'required|max:25',
            'first_name'=>'required|max:25',
            'digital_account_opening'=>'required_if:userType,==,BANK'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

//        if ( !$request->filled('recaptcha') ){
//            $response = array("success"=>false, "message"=>"You need to verify the recaptcha", "data"=>[]);
//            return response()->json($response, 400);
//        }

        if ( !verifyCaptcha($request->recaptcha) ){
            $response = array("success"=>false, "message"=>"Invalid recaptcha", "data"=>[]);
            return response()->json($response, 400);
        }

        $userType = trim(ucwords($request->userType));
        $email = trim(strtolower($request->email));

        $user = User::select([
            'users.*'
        ])->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('account_status',systemActiveUsersStatuses())
            ->where('email',$email)
//            ->where('organizations.type',$userType)
            ->whereIn('organizations.type',['BANK','DEPOSITOR'])
            ->first();

//        $user = User::whereIn('account_status',systemActiveUsersStatuses())->where('email',$request->email)->first();
        if( $user && !can_switch_to_organizations($user) ){
//            $organization = $user->organization;
//            if( !$user->is_super_admin && $organization->type == $userType ) {
                loginActivities("User sign up attempt failed, User with that email already exists", $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'User with that email already exists', "data" => [], "success" => false], 409);
//            }
        }

//        if( $userType == "BANK" ){
            $organization = Organization::where('name',$request->institution_name)
                ->where('type',$userType)
                ->first();

            if( $organization ){
                loginActivities("User sign up attempt failed, institution with same name already exists ",$request->server('HTTP_USER_AGENT'),0);
                return response()->json(["message" => 'An error occurred, institution with same name already exists', "data" => [], "success" => false], 409);
            }

//        }

        $profile_image = null;
        if($request->hasFile('profile_image')){
            $file = $request->file('profile_image');
            $destinationPath=public_path() . '/image/';
            $file_name = time().sanitize_file_name($request->institution_name).'.png';
            if($file->move($destinationPath,$file_name)){
                $profile_image = $file_name;
            }
        }

        try {
            DB::beginTransaction();

            if( !$user ) {
                $created_user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'firstname' => $request->first_name,
                    'lastname' => $request->last_name,
                    'email' => $request->email,
                    'account_opening_date' => getUTCDateNow(),
                    'account_status' => 'ACTIVE', // default user to active
                    'is_non_partnered_fi' => false,
                    'failed_login_attempts' => 0
                ]);
            }else{
                $created_user = $user;
            }

            // create organizations
            $created_organization = Organization::create([
                'users_limit'=>5,
                'name'=>$request->institution_name,
                'logo'=>$profile_image,
                'type'=>strtoupper(trim($request->userType)),
                'admin_user_id'=>$created_user->id,
                'is_non_partnered_fi'=>0,
                'created_by'=>$created_user->id,
                'is_temporary'=>0,
                'account_manager'=>NULL,
                'inviter_name'=>NULL,
                'status'=>'PENDING',
                'potential_yearly_deposit_id'=>strtoupper(trim($request->userType)) == "DEPOSITOR" ? $request->potential_deposit : 0, // for Depositor
                'naics_code_id'=>strtoupper(trim($request->userType)) == "DEPOSITOR" ? $request->naics_code : NULL,
                'wholesale_deposit_portfolio_id'=>strtoupper(trim($request->userType)) == "BANK" ? $request->wholesale_deposit_portfolio_id : 0, // For Bank
                'fi_type_id'=> strtoupper(trim($request->userType)) == "BANK" ? $request->institution_type : 0, // for Bank
                'requires_to_confirm_users_seats'=>false,
                'accepted_terms_and_conditions'=>strtoupper(trim($request->userType)) == "DEPOSITOR" ? 0 : 1,
                'sign_up_from'=>$request->filled('referral') ? $request->referral : NULL,
                'digital_account_opening' => $request->digital_account_opening ? $request->digital_account_opening : NULL,
            ]);

            // link users to organizations
            UserOrganization::create([
                'user_id'=>$created_user->id,
                'organization_id'=>$created_organization->id,
                'status'=>'ACTIVE',
                'is_default'=>1,
                'switched_organization_type'=>NULL
            ]);

            if( !$user ) {
                UsersDemoGraphicData::create([
                    'user_id' => $created_user->id,
                    'city' => $request->location,
                    'province' => $request->province2,
                    'job_title' => $request->job_title,
                    'department' => $request->department,
                    'phone' => $request->user_telephone,
                    'timezone' => $request->timezone,
                    'created_at' => getUTCDateNow(),
                    'organization_id'=>$created_organization->id
                ]);
            }

            OrganizationDemoGraphicData::create([
                'user_id' => $created_user->id,
                'address1' => $request->address,
                'address2' => $request->filled("address2") ? $request->address2 : "",
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal,
               'timezone' => $request->timezone,
               'description' => $request->description,
               'telephone' => $request->telephone,
                'created_at' => getUTCDateNow(),
                'organization_id' => $created_organization->id,
                'website'=>$request->website,
            ]);

            if( !$user ) {
                $password = password_hash($request->pass, PASSWORD_BCRYPT);
                UserPassword::create([
                    'hash' => $password,
                    'created_at' => getUTCDateNow(),
                    'user_id' => $created_user->id
                ]);
            }

            $role_type = Organization::where('type', strtoupper(trim($request->userType)))->first();
            if (!$role_type) {
                DB::rollBack();
                loginActivities("User sign up attempt failed, The role selected does not exists.", $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'Failed, The organization type selected does not exists.', "data" => [], "success" => false], 409);
            }

            $role = Role::where('name','administrator')->first();
            if(!$role){
                $role = Role::create([
                    'name'=>'administrator',
                    'display_name'=>'Organization Administrator',
                    'description'=>'The Overall Organization managers',
                    'organization_id'=>0
                ]);
            }

            DB::table('role_user')->insert([
                'role_id' => $role->id,
                'user_id' => $created_user->id,
                'user_type' => $role->display_name,
                'organization_id' => $created_organization->id
            ]);

//            UserRoleType::create([
//                'user_id' => $created_user->id,
//                'role_type_id' => $role_type->id,
//            ]);

            if(!$user) {
                $preference = Preference::where("name", "mute_notification")->first();
                if ($preference) {
                    UserPreference::create([
                        'value' => 0,
                        'preference_id' => $preference->id,
                        'user_id' => $created_user->id
                    ]);
                }
            }

            if ($userType == "Bank") {
                if ($request->filled('short_term_credit') && $request->filled('deposit_insurance')) {
                    DepositCreditRating::create([
                        'user_id' => $created_user->id,
                        'credit_rating_type_id' => $request->short_term_credit,
                        'deposit_insurance_id' => $request->deposit_insurance,
                        'organization_id' => $created_organization->id
                    ]);
                }
                $admin_message = "Financial Institution <strong>" . $created_organization->name . "</strong> created a new account with email: " . $created_user->email;
            } else {
                $admin_message = "Depositor <strong>" . $created_organization->name . "</strong> created a new account with email: " . $created_user->email;
            }

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            $timestamp= time();
            Log::error($timestamp.': '.$exception->getMessage().': '.$exception->getTraceAsString());
            loginActivities("User sign up attempt failed, check with the developer. Error No: ".$timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, '.Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 409);
        }

        Mail::to(getAdminEmails())->queue(new AdminMail([
            'subject'=>"New Sign Up!",
            'message'=>$admin_message,
        ]));

//        Mail::to($created_user->email)->queue(new RegistrationMail([
//            'user_type'=>$request->userType
//        ]));

//        if(can_switch_to_organizations($created_user)) {
            $created_user->switched_organization_id = $created_organization->id; //ensure the user is switched to this organization
            $created_user->save();
//        }
        /*
         * Login the user
         */
        \auth()->loginUsingId($created_user->id);

        loginActivities("Registration successful",$request->server('HTTP_USER_AGENT'),$created_user->id);
        return response()->json(["message" => 'Thank you for registering for an account with Yield Exchange.'/* One of our representatives will get back to you shortly regarding the next steps for your new account.*/, "data" => [], "success" => true], 201);
    }

    public function resetPasswordRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if( is_admin_route($request) ){
            $validation_rules['loginAs']='required|in:Admin';
        }else{
            if($request->filled('loginAs') && $request->loginAs == "Admin"){
                $response = array("success"=>false, "message"=>"Unauthorized access", "data"=>[]);
                return response()->json($response, 401);
            }
        }

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

//        if ( !$request->filled('recaptcha') && !$request->has('fromLoggedInUser') ){
//            $response = array("success"=>false, "message"=>"You need to verify the recaptcha", "data"=>[]);
//            return response()->json($response, 400);
//        }

        $email = trim(strtolower($request->email));

        $user = User::select([
            'users.*',
            'organizations.is_non_partnered_fi as organization_is_non_partnered_fi',
            'organizations.status as organization_status',
            'organizations.type as organization_type'
        ])->with(['userPassword'])
            ->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('account_status',systemActiveUsersStatuses())->where('email',$email);

        if(is_admin_route($request)){
            $user = $user->join('role_user','role_user.user_id','=','users.id')
                ->join('roles','role_user.role_id','=','roles.id')
                ->where(function($query){
                    $query->where('roles.name','=','system-administrator')
                        ->orWhere('users.is_system_admin',1);
                });
        }else{
            $user = $user->whereIn('organizations.status',systemActiveOrganizationStatuses(true))
                ->where('users_organizations.is_default', 1);
        }

        $user = $user->first();
        if(!$user){
            loginActivities("User reset password attempt failed, user_id does not exist in the database", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message_title"=>"Password update.", "message" => "If email exist in our system, you will get a reset link", "data" => [], "success" => true], 200);
        }

        if($user->account_status == "LOCKED"){
            loginActivities("User reset password attempt failed, account status is locked",$request->server('HTTP_USER_AGENT'),$user->id);
            return response()->json(["message"=>"Your account has been locked, ".Constants::RESPONSE_MESSAGE_CONTACT_US,"data"=>[],"success"=>false],401);
        }

        if($user->isAbleToLogin()) {

            $code = password_hash(time() . '_' . $user->email, PASSWORD_BCRYPT);
            $expiration_date = getUTCTimeNow()->addHours(2)->format(Constants::DATE_TIME_FORMAT); // 2 hours UTC time

            PasswordResets::where('user_id',$user->id)->delete();
            PasswordResets::create([
                'user_id'=>$user->id,
                'expiration_date'=>$expiration_date,
                'token'=>$code,
                'created_at'=>getUTCTimeNow()
            ]);
            $link_reset_password = url((is_admin_route($request) ? 'yie-admin/' : '').'login?action=changePassword&code='.$code);
             $other_buttons = [['linkName'=>'Reset Password','link'=>$link_reset_password]];

            Mail::to($user->email)->queue(new ResetPasswordMail([
                'other_buttons'=>$other_buttons,
                'user_type'=>get_user_type($user)
            ]));

            loginActivities("Password reset request successful",$request->server('HTTP_USER_AGENT'),$user->id);
            $message='If email exist in our system, you will get a reset link';
            if($request->has('fromLoggedInUser')){
                $message="Your password reset link has been sent to your email.";
            }
            return response()->json(["message" => $message, "message_title"=>"Password update.", "data" => [], "success" => true], 200);
        }

        $message = "Your account has not been activated, ".Constants::RESPONSE_MESSAGE_CONTACT_US;
//        if($user->account_status != "PENDING"){
//            $message = Constants::RESPONSE_MESSAGE_CONTACT_US.' to sign in.';
//        }

        loginActivities("User reset password attempt failed, account status is ".strtolower($user->account_status),$request->server('HTTP_USER_AGENT'),$user->id);
        return response()->json(["message_title"=>"Password Update.","message"=>$message,"data"=>[],"success"=>false],403);
    }

    public function resendResetPasswordRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        $password_reset = PasswordResets::where('token',$request->code)->first();
        if( !$password_reset ){
            loginActivities("User reset password attempt failed, reset code invalid",$request->server('HTTP_USER_AGENT'),0);
            return response()->json(["message"=>"Reset password attempt failed, go to login page and retry","data"=>[],"success"=>false],401);
        }

        $user = User::select([
            'users.*',
            'organizations.is_non_partnered_fi as organization_is_non_partnered_fi',
            'organizations.status as organization_status',
            'organizations.type as organization_type'
        ])->with(['userPassword'])
            ->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('account_status',systemActiveUsersStatuses());

        if(is_admin_route($request)){
            $user = $user->join('role_user','role_user.user_id','=','users.id')
                ->join('roles','role_user.role_id','=','roles.id')
                ->where(function($query){
                    $query->where('roles.name','=','system-administrator')
                        ->orWhere('users.is_system_admin',1);
                });
        }else{
            $user = $user->whereIn('organizations.status',systemActiveOrganizationStatuses(true))
                ->whereIn('organizations.type',['BANK','DEPOSITOR']);
        }
        $user = $user->where('users.id',$password_reset->user_id)->first();

        if(!$user){
            loginActivities("User reset password attempt failed, user_id does not exist in the database", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => "User not found", "data" => [], "success" => false], 401);
        }

        if($user->account_status == "LOCKED"){
            loginActivities("User reset password attempt failed, account status is locked",$request->server('HTTP_USER_AGENT'),$user->id);
            return response()->json(["message"=>"Your account has been locked, ".Constants::RESPONSE_MESSAGE_CONTACT_US,"data"=>[],"success"=>false],401);
        }

        if( $user->isAbleToLogin() ){

            $code = password_hash(time() . '_' . $user->email, PASSWORD_BCRYPT);
            $expiration_date = getUTCTimeNow()->addHours(2)->format(Constants::DATE_TIME_FORMAT); // 2 hours UTC time

            PasswordResets::where('user_id',$user->id)->delete();
            PasswordResets::create([
                'user_id'=>$user->id,
                'expiration_date'=>$expiration_date,
                'token'=>$code,
                'created_at'=>getUTCTimeNow()
            ]);
            $link_reset_password = url((is_admin_route($request) ? 'yie-admin/' : '').'login?action=changePassword&code='.$code);
            $link_resend_password = url((is_admin_route($request) ? 'yie-admin/' : '').'resend-reset-password?code='.$code);
            $other_buttons = [['linkName'=>'Resend Password Link','link'=>$link_resend_password],['linkName'=>'Click Here To Reset Password','link'=>$link_reset_password]];

            Mail::to($user->email)->queue(new ResetPasswordMail([
                'other_buttons'=>$other_buttons,
                'user_type'=>get_user_type($user)
            ]));

            loginActivities("Password reset request successful",$request->server('HTTP_USER_AGENT'),$user->id);
            return response()->json(["message_title"=>"Password Update.","message" => 'Password reset request successful, check your email for more info', "data" => [], "success" => true], 200);
        }

        $message = "Your account has not been activated, ".Constants::RESPONSE_MESSAGE_CONTACT_US;
//        if($user->account_status != "PENDING"){
//            $message = Constants::RESPONSE_MESSAGE_CONTACT_US.' to sign in.';
//        }

        loginActivities("User reset password attempt failed, account status is ".strtolower($user->account_status),$request->server('HTTP_USER_AGENT'),$user->id);
        return response()->json(["message"=>$message,"data"=>[],"success"=>false],403);
    }

    public function resetPasswordFinalStepRequest(Request $request){

        $customMessages = [
            'same' => 'The passwords you have entered do not match.',
            'required'=>'The Password field should not be empty.',
            'required_with'=>'The confirm password field should not be empty.'
        ];
        $validator = Validator::make($request->all(), [
            'pass' => 'required|required_with:cpass|same:cpass'
        ],$customMessages);

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        if(!Auth::check()) {
            $password_reset = PasswordResets::where('token', $request->code)->first();
            if (!$password_reset) {
                loginActivities("User reset password attempt failed, reset code invalid", $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => "The link is invalid, please check your email and try again", "data" => [], "success" => false, "alert_class" => "alert-warning"], 401);
            }
        }else{ // creating an instance of PasswordResets for the logged in user for the below logic
            $password_reset = (object) [];
            $password_reset->user_id = \auth()->id();
            $password_reset->created_at = Carbon::now();
        }

        $user = User::select([
            'users.*',
            'organizations.is_non_partnered_fi as organization_is_non_partnered_fi',
            'organizations.status as organization_status',
            'organizations.type as organization_type'
        ])->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('account_status',systemActiveUsersStatuses());

        if(is_admin_route($request)){
            $user = $user->join('role_user','role_user.user_id','=','users.id')
                ->join('roles','role_user.role_id','=','roles.id')
                ->where(function($query){
                    $query->where('roles.name','=','system-administrator')
                        ->orWhere('users.is_system_admin',1);
                });
        }else{
            $user = $user->whereIn('organizations.status',systemActiveOrganizationStatuses(true))
                ->whereIn('organizations.type',['BANK','DEPOSITOR']);
        }
        $user = $user->where('users.id',$password_reset->user_id)->first();

        if(!$user){
            loginActivities("User reset password attempt failed, user_id does not exist in the database", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => "User not found", "data" => [], "success" => false, "alert_class"=>"alert-warning"], 401);
        }

        if($user->account_status == "LOCKED"){
            loginActivities("User reset password attempt failed, account status is locked",$request->server('HTTP_USER_AGENT'),$user->id);
            return response()->json(["message"=>"Your account has been locked, ".Constants::RESPONSE_MESSAGE_CONTACT_US,"data"=>[],"success"=>false],401);
        }

        if($user->isAbleToLogin() || ($request->filled('from') && $request->from == 'sign_up') ){
            $hashed_password = password_hash($request->pass, PASSWORD_BCRYPT);
            $created_at = Carbon::parse($password_reset->created_at);
            $utc_now = getUTCTimeNow();

            if ( $utc_now->diffInMinutes($created_at) > 30 ){ // updated to 30 minutes
                loginActivities("User login attempt failed, pin has expired",$request->server('HTTP_USER_AGENT'),$user->id);
                return response()->json(["message" => 'The link has expired, please try to reset the password again', "data" => [], "success" => false,"alert_class"=>"alert-warning"], 403);
            }

            if( $this->checkIfProvidedPasswordHasBeenUsedBefore($user->id,$request->pass) ){
                loginActivities("User reset password attempt failed. Password has been used before.",$request->server('HTTP_USER_AGENT'),$user->id);
                return response()->json(["message" => 'Password has been used before, please enter a new one', "data" => [], "success" => false, "alert_class"=>"alert-danger"], 403);
            }

            $passwords = UserPassword::where('user_id',$user->id)->orderBy('id','DESC')->limit(10)->get();
            if( $passwords->count() > 10 ) {
                UserPassword::where('id', '<', $passwords[10])->where('user_id',$user->id)->delete();
            }

            UserPassword::create(
                [
                    'hash'=>$hashed_password,
                    'user_id'=>$user->id,
                    'created_at'=>getUTCTimeNow()
                ]
            );

            PasswordResets::where('user_id',$user->id)->delete();
            if ($request->filled('from') && $request->from == 'sign_up') {  //// send welcome email
                // Mail::to($user->email)->queue(new RegistrationMail([
                //     'user_type'=>!empty($user->roleType[0]) ? $user->roleType[0]->description : "",
                //     'count'=>Organization::where('type','BANK')->where('status','ACTIVE')->count()
                // ])); //// avoid sending email
            }else if( $user->requires_password_update == 1 ) {
                $this->welcomeMessageDepositor($user);
            }else{
                Mail::to($user->email)->queue(new ResetPasswordConfirmation([
                    'user_type'=>!empty($user->roleType[0]) ? $user->roleType[0]->description : ""
                ]));
            }

            $user->requires_password_update = false;
            $user->save();

            loginActivities("Password reset request successful",$request->server('HTTP_USER_AGENT'),$user->id);
            return response()->json(["message_title"=>"Set Password.","message" => 'Password set successfully', "data" => [], "success" => true], 200);
        }

        $message = "Your account has not been activated, ".Constants::RESPONSE_MESSAGE_CONTACT_US;
//        if($user->account_status != "PENDING"){
//            $message = Constants::RESPONSE_MESSAGE_CONTACT_US.' to sign in.';
//        }

        loginActivities("User reset password attempt successful",$request->server('HTTP_USER_AGENT'),$user->id);
        return response()->json(["message"=>$message, "message_title"=>"Password Update.", "data"=>[],"success"=>false],403);
    }

    private function welcomeMessageDepositor($user){
        if( $user->organization && $user->organization->type=='DEPOSITOR' && $user->organization->status=='PENDING' ){

//            Mail::to($user->email)->queue(new RegistrationMail([
//                'user_type' => 'DEPOSITOR'
//            ]));

            $status = $user->organization->is_partially_approved ? 'Partially Completed ' . Carbon::now()->toDateString() : 'Pending';

            $admin_message = "Dear Admin,
                
                Name: {$user->name}  
                
                Business Name: {$user->organization->name} 
                
                Status: {$status}
                
                Kindly review the aforementioned account within the next 01 hour to ensure a swift progression through our onboarding process.
                
                <br/>Warm regards,";

            Mail::to(getAdminEmails())->queue(new AdminMail([
                'subject' => $user->organization->is_partially_approved ? "Partially approved New Sign Up!" : "New Sign Up!",
                'message' => $admin_message,
            ]));
        }
    }

    private function abandonMessageDepositor($user){
        if(!$user->organization){
            return;
        }
        if( ($user->accepted_terms_and_conditions == 1
                || $user->requires_password_update == 1
//                || $user->organization->requires_to_confirm_users_seats == 1
                || $user->organization->needs_update=='yes' )
            && $user->organization
            && $user->organization->type=='DEPOSITOR'
            && $user->organization->status=='PENDING' ){

            if( $user->requires_password_update ) {
                $password_ = getRandomNumberBetween(90000, 9999999);
                $password = password_hash($password_, PASSWORD_BCRYPT);
                UserPassword::create([
                    'hash' => $password,
                    'created_at' => getUTCDateNow(),
                    'user_id' => $user->id
                ]);

                $user_message = "Dear {$user->name},
                We noticed that your sign-up process was left incomplete. 
                To assist you in picking up right where you left off, we’ve generated a one-time passcode for you: {$password_}. 
                Simply return to YieldExchange, enter this passcode and you’ll be a step closer to discovering all the fantastic features awaiting you.
                We're looking forward to having you onboard!";

                Mail::to($user->email)->queue(new RegistrationMail([
                    'user_type' => 'DEPOSITOR',
                    'message' => $user_message
                ]));
            }

            $stage="";
            if (!$user->organization->accepted_terms_and_conditions){
                $stage = ' Accept Terms and conditions';
            }else if($user->requires_password_update){
                $stage = ' Setting Password';
//            }else if($user->organization->requires_to_confirm_users_seats){
//                $stage = ' Confirm users seats';
            }else if($user->organization->needs_update=='yes'){
                $stage = ' Account Setting update';
            }
            $data = [
                'name' => $user->name,
                'business_name'=>$user->organization->name,
                'stage'=> $stage,
            ];



            Mail::to(getAdminEmails())->queue(new AdminMails([
                'data' => $data,
                'subject'=>$user->organization->is_partially_approved ? "Partially approved New Sign Up!" : "New Sign Up!",
            ],'incomplete_registeration',));
        }
    }

    private function checkIfProvidedPasswordHasBeenUsedBefore($user_id,$pass){
        $passwords = UserPassword::where('user_id',$user_id)->get();
        if (!empty($passwords)){
            foreach ($passwords as $password) {
                if (password_verify($pass,$password->hash)){
                    return true;
                }
            }
        }

        return false;
    }

    public function verificationDSignUpPost(Request $request,$code){
        $code = CustomEncoder::urlValueDecrypt($code);
        $signup_temporary_data_builder = DB::table('signup_temporary_data')->where('verification_code',$code);
        $signup_temporary_data = with(clone $signup_temporary_data_builder)->first();
        if(!$signup_temporary_data){
            $response = array("success"=>false, "message"=>"Failed to verify you email", "data"=>[]);
            return response()->json($response, 400);
        }

        $signup_temporary_data = with(clone $signup_temporary_data_builder)->where('verified',false)->first();
        if(!$signup_temporary_data){
            $response = array("success"=>false, "message"=>"Verification already done", "data"=>[]);
            return response()->json($response, 400);
        }

        $request_data = json_decode($signup_temporary_data->request_data, true);
        $request_data = new Request($request_data);

        DB::table('signup_temporary_data')->where('verification_code',$code)->update(['verified'=>true]);
        return $this->DSignUpPost($request_data,true);
    }

    public function DSignUpPost(Request $request, $verified=false) {

        $validator = Validator::make($request->all(), [
            'userType' => 'required|in:Bank,Depositor',
            'institution_name' => 'required|max:50',
            'email' => 'required|email|max:50',
            'last_name'=>'required|max:25',
            'first_name'=>'required|max:25'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        if(!$verified) {
//            if ( !$request->filled('recaptcha') && !$request->has('conference_form') ) {
//                $response = array("success" => false, "message" => "You need to verify the recaptcha", "data" => []);
//                return response()->json($response, 400);
//            }

//            if (!verifyCaptcha($request->recaptcha)) { remove for now
//                $response = array("success" => false, "message" => "Invalid recaptcha", "data" => []);
//                return response()->json($response, 400);
//            }
        }

        $userType = trim(ucwords($request->userType));
        $email = trim(strtolower($request->email));

        $user = User::select([
            'users.*'
        ])->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('account_status',systemActiveUsersStatuses())
            ->where('email',$email)
            ->whereIn('organizations.type',['BANK','DEPOSITOR'])
            ->first();

        if( $user && !can_switch_to_organizations($user) ){
                loginActivities("User sign up attempt failed, User with that email already exists", $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'User with that email already exists', "data" => [], "success" => false], 409);
        }

        $organization = Organization::where('name',$request->institution_name)
            ->where('type',$userType)
            ->first();

        if( $organization ){
            loginActivities("User sign up attempt failed, institution with same name already exists ",$request->server('HTTP_USER_AGENT'),0);
            return response()->json(["message" => 'An error occurred, institution with same name already exists', "data" => [], "success" => false], 409);
        }
        $profile_image = null;

        if( !$verified && !$request->has('conference_form') ) {
            try {
                $code = getRandomNumberBetween(10000, 99999999);
                DB::table('signup_temporary_data')->insert([
                    'verification_code' => $code,
                    'email' => $request->email,
                    'ip_address' => $request->header('X-Client-IP'),
                    'request_data' => json_encode($request->all()),
                    'created_at' => getUTCDateNow(),
                    'updated_at' => NULL
                ]);

                Mail::to($request->email)->send(new VerifyEmailMail([
                    'code' => $code,
                    'user_type' => strtoupper(trim($request->userType))
                ]));

                loginActivities("User sign up pending email verification", $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'Please Check your email for next steps', "data" => [], "success" => true], 200);
            }catch (\Exception $exception){
                loginActivities("User sign up attempt failed, check with the developer.", $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'Failed, unable to complete registration, '.Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => $exception->getMessage(), "success" => false], 409);
            }
        }

        try {
            DB::beginTransaction();

            if( !$user ) {
                $created_user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'firstname' => $request->first_name,
                    'lastname' => $request->last_name,
                    'email' => $request->email,
                    'account_opening_date' => getUTCDateNow(),
                    'account_status' => 'ACTIVE', // default user to active
                    'is_non_partnered_fi' => false,
                    'failed_login_attempts' => 0,
                    'requires_password_update'=>$request->has('conference_form') ? false : true
                ]);
            }else{
                $created_user = $user;
            }

            // create organizations
            $created_organization = Organization::create([
                'users_limit'=>5,
                'name'=>$request->institution_name,
                'logo'=>$profile_image,
                'type'=>strtoupper(trim($request->userType)),
                'admin_user_id'=>$created_user->id,
                'is_non_partnered_fi'=>0,
                'created_by'=>$created_user->id,
                'is_temporary'=>0,
                'account_manager'=>NULL,
                'inviter_name'=>NULL,
                'status'=>'PENDING',
                'potential_yearly_deposit_id'=>$request->potential_deposit ? $request->potential_deposit : null, // for Depositor
                'naics_code_id'=>$request->naics_code ? $request->naics_code : NULL,
                'wholesale_deposit_portfolio_id'=> 0, // For Bank
                'fi_type_id'=> 0, // for Bank
                'requires_to_confirm_users_seats'=>false,
                'accepted_terms_and_conditions'=> 0 ,
                'sign_up_from'=>$request->filled('referral') ? $request->referral : NULL,
                'is_from_conference'=>$request->filled('conference_form') ? true : false,
                'digital_account_opening' => $request->digital_account_opening ? $request->digital_account_opening : NULL,
                'needs_update' => 'yes'
            ]);

            // link users to organizations
            UserOrganization::create([
                'user_id'=>$created_user->id,
                'organization_id'=>$created_organization->id,
                'status'=>'ACTIVE',
                'is_default'=>1,
                'switched_organization_type'=>NULL
            ]);

            if( !$user ) {
                UsersDemoGraphicData::create([
                    'user_id' => $created_user->id,
                    'created_at' => getUTCDateNow(),
                    'phone' => $request->telephone,
                    'timezone' => "Central",
                    'organization_id'=>$created_organization->id
                ]);
            }

            OrganizationDemoGraphicData::create([
                'user_id' => $created_user->id,
                'address2' => "",
               'timezone' => "Central",
                'created_at' => getUTCDateNow(),
                'organization_id' => $created_organization->id,
                "seen_summary" => 'yes',
                "telephone"=>$request->telephone,
                "website"=>$request->website
            ]);

            if(!$user) {
                $password_ = getRandomNumberBetween(90000, 9999999);
                // $password = password_hash($password_, PASSWORD_BCRYPT);
                // UserPassword::create([
                //     'hash' => $password,
                //     'created_at' => getUTCDateNow(),
                //     'user_id' => $created_user->id
                // ]);

                if( $request->has('conference_form') ) {
                    Mail::to($created_user->email)->queue(new NewWelcomeMail([
                        'password' => $password_,
                        'user_type' => get_user_type($created_user)
                    ]));
                }
            }

            $role_type = Organization::where('type', strtoupper(trim($request->userType)))->first();
            if (!$role_type) {
                DB::rollBack();
                loginActivities("User sign up attempt failed, The role selected does not exists.", $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'Failed, The organization type selected does not exists.', "data" => [], "success" => false], 409);
            }

            $role = Role::where('name','organization-administrator')->first();
            if(!$role){
                $role = Role::create([
                    'name'=>'organization-administrator',
                    'display_name'=>'Organization Administrator',
                    'description'=>'The Overall Organization managers',
                    'organization_id'=>0
                ]);
            }

            DB::table('role_user')->insert([
                'role_id' => $role->id,
                'user_id' => $created_user->id,
                'user_type' => $role->display_name,
                'organization_id' => $created_organization->id
            ]);

            if(!$user) {
                $preference = Preference::where("name", "mute_notification")->first();
                if ($preference) {
                    UserPreference::create([
                        'value' => 0,
                        'preference_id' => $preference->id,
                        'user_id' => $created_user->id
                    ]);
                }
            }

            if($request->filled("website") && $created_organization->type=="DEPOSITOR") {
                $this->partiallyApproval($request->email, $request->website, $created_organization);
            }

            if( app()->environment('production') ) {
                $this->registerToHubSpot($request->all());
            }

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            $timestamp= time();
            Log::error($timestamp.': '.$exception->getMessage().': '.$exception->getTraceAsString());
            loginActivities("User sign up attempt failed, check with the developer. Error No: ".$timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, '.Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 409);
        }

        $created_user->switched_organization_id = $created_organization->id; //ensure the user is switched to this organization
        $created_user->save();

        /*
         * Login the user
         */
        \auth()->loginUsingId($created_user->id);

        loginActivities("Registration successful",$request->server('HTTP_USER_AGENT'),$created_user->id);
        return response()->json(["message" => 'Thank you for registering for an account with Yield Exchange.'/* One of our representatives will get back to you shortly regarding the next steps for your new account.*/, "data" => [], "success" => true], 201);
    }

    private function getDomainFromURL($url){
        if (!preg_match("~^(?:https?://|www\.)~i", $url)) {
            // If not, add "https://www."
            $url = "https://www." . $url;
        }else if(Str::startsWith($url,"www.")){
            $url = "https://www." . Str::replace("www.","",$url);
        }

        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)){
            return $regs['domain'];
        }
        return "";
    }

    private function partiallyApproval($email, $website, $organization){
        $website_domain = $this->getDomainFromURL($website);

        $email_domain = explode('@',$email);
        $email_domain = array_pop($email_domain);

        if( trim($website_domain) == trim($email_domain)) {
//            $organization->is_partially_approved=1;
//            $organization->save();
        }

    }

    public function registerToHubSpot($data){
       
        $api_url = 'https://api.hubapi.com/crm/v3/objects/contacts';

        $properties = array(
            "email"=> $data['email'],
            "firstname" => $data['first_name'],
            "lastname" => $data['last_name'],
            "phone" =>$data['telephone'],
            "company" => $data['institution_name'],
        );

        if(isset($data['website'])){
            $properties['website'] = $data['website'];
        }

        $data = array(
            'properties' => $properties
        );

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ',
        );

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        // Handle the response as needed
        return $response;
    }

}

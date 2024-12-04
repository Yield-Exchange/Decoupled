<?php

namespace App\Http\Controllers;

use App\CustomEncoder;
use App\Mail\Admin\AdminUserActionMail;
use App\Mail\AdminMail;
use App\Mail\NewUserPasswordMail;
use App\Mail\OTPMail;
use App\Mail\RegistrationMail;
use App\Models\FITypes;
use App\Models\NAICS;
use App\Models\Organization;
use App\Models\OrganizationDemoGraphicData;
use App\Models\OTP;
use App\Models\PasswordResets;
use App\Models\PotentialYearlyDeposits;
use App\Models\SeatsBilling;
use App\Models\UserOrganization;
use App\Models\UserPassword;
use App\Models\UsersDemoGraphicData;
use App\Models\WholeSaleDepositsPortfolio;
use App\Role;
use App\Services\NewSignUpService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function GuzzleHttp\json_decode;

class AuthController extends Controller
{
    private $newSignUpService;
    public function __construct()
    {
        $this->newSignUpService = new NewSignUpService();
    }
    public function signUp(Request $request, $referral = null)
    {

        if ($request->has("submissionGuid")) {
            return redirect('/login?type=Depositor&from_signup=1');
        }

        $fis = json_encode(getFIs());
        $provinces = json_encode(provinces());
        $timezones_lists = timezonesList();
        $key_value_timezone_list = [];
        foreach ($timezones_lists as $key => $timezones_list) {
            array_push($key_value_timezone_list, [
                'value' => $key,
                'label' => $timezones_list
            ]);
        }

        $timezones = json_encode($key_value_timezone_list);
        $naics_codes = json_encode(NAICS::all());
        $potential_deposits = json_encode(PotentialYearlyDeposits::all());
        $deposit_portfolio = json_encode(WholeSaleDepositsPortfolio::all());
        $fitypes = json_encode(FITypes::all());
        return view('auth.sign-up', compact('request', 'fis', 'provinces', 'timezones', 'naics_codes', 'potential_deposits', 'deposit_portfolio', 'fitypes', 'referral'));
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            if (is_admin_route($request)) {
                return redirect()->route('admin.home');
            }
            return redirect('/dashboard');
        }

        $loginAs = $request->has('loginAs') ? $request->loginAs : 'inv';
        $action = $request->has('action') ? $request->action : null;
        $user = User::find(CustomEncoder::urlValueDecrypt($request->session()->get('user_id')));
        $hashed_user_id = $request->session()->get('user_id');

        if ($action == "verifyOtp" && !$user) {
            alert()->warning("Unable to complete login verification, please retry");
            if (is_admin_route($request)) {
                return redirect()->route('admin.login');
            }
            return redirect('login');
        }

        $code = "";
        if ($action == "changePassword") {
            $code = $request->code;
            $password_reset = PasswordResets::where('token', $code)->first();
            if (!$password_reset) {
                alert()->warning("Unable to complete password reset, invalid code. Please retry.");
                if (is_admin_route($request)) {
                    return redirect()->route('admin.login');
                }
                return redirect('login');
            }
        }

        $isFromLoggedInUser = false; // added after re-use by change password
        return view('auth.index', compact('loginAs', 'action', 'user', 'hashed_user_id', 'code', 'request', 'isFromLoggedInUser'));
    }

    public function loginPost(Request $request)
    {
        return (new Api\AuthController())->loginPost($request);
    }

    public function verifyOTP(Request $request)
    {
        return (new Api\AuthController())->verifyOTP($request);
    }

    public function resendOTP(Request $request)
    {
        return (new Api\AuthController())->resendOTP($request);
    }

    public function signUpPost(Request $request)
    {
        return (new Api\AuthController())->signUp($request);
    }

    public function resetPasswordRequest(Request $request)
    {
        return (new Api\AuthController())->resetPasswordRequest($request);
    }

    public function resendResetPasswordRequest(Request $request)
    {
        $response = (new Api\AuthController())->resendResetPasswordRequest($request);
        $data = json_decode($response->content(), TRUE);
        if ($data['success']) {
            alert()->success($data['message']);
        } else {
            alert()->warning($data['message']);
        }

        if (is_admin_route($request)) {
            return redirect()->route('admin.login');
        }
        return redirect('login');
    }

    public function logout(Request $request)
    {
        $response = (new Api\AuthController())->logout($request);
        $data = json_decode($response->content(), TRUE);
        if ($data['success']) {
            alert()->success($data['message']);
        } else {
            alert()->warning($data['message']);
        }
        return redirect('/dashboard'); // take the user back to dashboard so that he can de redirected with no back page reference
    }

    public function resetPasswordFinalStepRequest(Request $request)
    {
        return (new Api\AuthController())->resetPasswordFinalStepRequest($request);
    }

    public function viewInvitation(Request $request, $token)
    {
        if (empty($token)) {
            $data['message'] = "There seems to be an issue with the invitation, reach out to the support team on info@yieldexchange.ca";
            $data['hide_login'] = true;
            $data['success'] = false;

            return view('auth.auth-status', compact('data'));
        }

        $id = CustomEncoder::urlValueDecrypt($token);
        $user = User::with(['roleType'])->whereHas('roleType', function ($query) use ($request) {
            $query->where('description', 'Bank');
        })->find($id);

        if (empty($user)) {
            $data['message'] = "There seems to be an issue with the invitation, reach out to the support team on info@yieldexchange.ca";
            $data['hide_login'] = true;
            $data['success'] = false;

            return view('auth.auth-status', compact('data'));
        }

        if (($user['is_non_partnered_fi'] == 1 && !in_array($user['account_status'], ['REVIEWING', 'INVITED'])) || $user['is_non_partnered_fi'] == 0) {
            alert()->error("Unauthorized access, please login");
            return redirect()->to('/login');
        }

        $user->account_status = 'REVIEWING';
        $user->last_login = getUTCDateNow(true);
        $user->save();

        // now authenticate the user!!
        Auth::login(User::find($user->id)); //The given User object must be an implementation of the Illuminate\Contracts\Auth\Authenticatable

        alert()->success("Authenticated successfully");
        return redirect()->to('/new-requests');
    }

    public function forceUpdateUserPassword(Request $request)
    {
        $user = \auth()->user();
        $loginAs = ucfirst(strtolower(get_user_type($user)));
        $code = null;
        $isFromLoggedInUser = true;
        return view('auth.force-update-password', compact('request', 'loginAs', 'code', 'isFromLoggedInUser'));
    }

    public function signUpConfirmSeats(Request $request)
    {
        $user = \auth()->user();
        if (!$user->organization) {
            alert()->error("Access Denied");
            return redirect()->route('logout');
        }

        if (true) {
            return redirect('/dashboard');
        }

        $organization = $user->organization;

        if ($organization && $organization->type == "DEPOSITOR" && !$organization->accepted_terms_and_conditions) {
            return view('dashboard.depositor.terms-and-conditions.accept-invitation');
        }

        //        if ($organization->demographicData->seen_summary == "no") {
        //            $summary = ai_summary($organization);
        //            if($summary != false) {
        //                $organization->demographicData->update([
        //                    'summary' => $summary,
        //                    'seen_summary' => 'yes',
        //                ]);
        //                return view('dashboard.summary', compact("organization"));
        //            }
        //        }

        if ($user->requires_password_update && $organization->accepted_terms_and_conditions) {
            return redirect()->route('force-update-password');
        }

        if (!$organization->requires_to_confirm_users_seats) {
            return redirect('/dashboard');
        }

        $organization = $user->organization;
        // if ($organization && $organization->type == "DEPOSITOR" && !$organization->accepted_terms_and_conditions) {
        //     return view('dashboard.depositor.terms-and-conditions.accept-invitation');
        // }

        // if ($user->requires_password_update) {
        //     return redirect()->route('force-update-password');
        // }

        // if (!$organization->requires_to_confirm_users_seats) {
        //     return redirect('/dashboard');
        // }

        //        if( !$organization->admin || $organization->admin->id != $user->id ){ // the user who created the organization will need to finish the step 3
        //            alert()->error("Your organization setup has not been completed. Please contact your admin.");
        //            return redirect()->route('logout');
        //        }

        $auth_user = $user;
        $users = $organization->users();
        $organization_seat_rate = getSystemSettings('organization_seat_rate');
        $organization_seat_rate = $organization_seat_rate ? $organization_seat_rate->value : '';
        return view('auth.confirm-organization-seats', compact('organization', 'auth_user', 'users', 'organization_seat_rate'));
    }

    public function requestOrganizationSeats(Request $request)
    {
        $user = \auth()->user();
        if (!$user->organization) {
            $response = array("success" => false, "message" => "Access Denied", "data" => []);
            return response()->json($response, 401);
        }

        $organization = $user->organization;
        //        if( !$organization->admin || $organization->admin->id != $user->id ){ // the user who created the organization will need to finish the step 3
        //            $response = array("success"=>false, "message"=>"Your organization setup has not been completed. Please contact your admin.", "data"=>[]);
        //            return response()->json($response, 400);
        //        }

        // process transactions
        $organization_seat_rate = getSystemSettings('organization_seat_rate')->value;
        $data['seats'] = $request->seats;
        if ($request->seats < $organization->users_limit) {
            $users_count = $organization->users()->count();
            if ($request->seats < $users_count) {
                $data['seats'] = $users_count;
            }
        }

        $data['organization_id'] = $organization->id;
        $data['ref_no'] = date('Ymdhis');
        $data['rate'] = $organization_seat_rate;
        $data['created_by'] = $user->id;
        $data['total_amount'] = $data['seats'] > 1 ? $data['seats'] * $data['rate'] : 0; // no payment required if the seat is 1
        SeatsBilling::create($data);

        archiveTable($organization->id, 'organizations', $user->id, 'SEATS UPDATED');
        $organization->users_limit =  $data['seats'];
        $organization->save();

        $response = array(
            "success" => true,
            "message" => "Seats updated successfully",
            "data" => [],
            'organization' => $organization,
            'users' => $organization->users(),
            'message_title' => 'Request seat'
        );

        return response()->json($response, 200);
    }

    public function completeOrganizationRegistration(Request $request)
    {
        $user = \auth()->user();
        if (!$user->organization) {
            $response = array("success" => false, "message" => "Access Denied", "data" => []);
            return response()->json($response, 401);
        }

        $organization = $user->organization;
        //        if( !$organization->admin || $organization->admin->id != $user->id ){ // the user who created the organization will need to finish the step 3
        //            $response = array("success"=>false, "message"=>"Your organization setup has not been completed. Please contact your admin.", "data"=>[]);
        //            return response()->json($response, 400);
        //        }

        if ($organization->status != 'ACTIVE') {
            if (!$organization->is_partially_approved) {
                $response = array("success" => false, "message" => "Your organization has not been approved yet.", "data" => []);
                return response()->json($response, 400);
            }
        }

        $organization->requires_to_confirm_users_seats = false;
        $organization->save();

        $response = array("success" => true, "message" => "Completed successfully", "data" => []);
        return response()->json($response, 200);
    }

    public function accept_terms_and_conditions_render(Request $request)
    {
        $user_data = \auth()->user();
        $organization = $user_data->organization;
        if ($organization->accepted_terms_and_conditions) {
            alert()->error("You have already accepted the terms and conditions");
            return redirect()->to('/dashboard');
        }
        return view('auth.terms-and-conditions');
    }
    public function accept_terms_and_conditions(Request $request)
    {
        $user = \auth()->user();
        //        if (!$user->isOrganizationAdmin()) {
        if (!\auth()->check()) {
            $response = array("success" => false, "message" => 'Access Denied', "data" => []);
            return response()->json($response, 403);
        }

        $validator = Validator::make($request->all(), [
            'action' => 'required'
        ]);

        if ($validator->fails()) {
            systemActivities(Auth::id(), json_encode($request->query()), "Accept terms and conditions failed");
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }

        $action = $request->action;
        switch ($action) {
            case 'DECLINE_TERMS_AND_CONDITIONS':
                $user_data = \auth()->user();
                loginActivities("Decline T&C successfully", $request->server('HTTP_USER_AGENT'), $user_data->id);

                $organization = $user_data->organization;
                Mail::to(getAdminEmails())->queue(new AdminUserActionMail([
                    'message' => $user->name . ' declined the terms and conditions for the organization ' . $organization->name,
                    'header' => $organization->name . ' declined the terms and conditions',
                    'subject' => 'Declined Terms And Conditions'
                ]));
                Organization::where('id', $organization->id)->update(['is_waiting' => 'DECLINE_TERMS', 'needs_update' => 'no']);
                User::where('id', $user->id)->update(['is_waiting' => 'DECLINE_TERMS', 'account_status' => 'PENDING']);

                $request->session()->flush();
                Auth::logout();

                // Output to JSON format
                return response()->json(['data' => [], 'message' => "Declined T&C successfully", 'success' => true], 200);
            case 'ACCEPT_TERMS_AND_CONDITIONS':
                $user_data = \auth()->user();
                $organization = $user_data->organization;
                loginActivities("Accepted terms and conditions by clicking YES to terms and conditions", $request->server('HTTP_USER_AGENT'), $user_data->id);

                Mail::to(getAdminEmails())->queue(new AdminUserActionMail([
                    'message' => $user->name . ' accepted the terms and conditions for the organization ' . $organization->name,
                    'header' => $organization->name . ' accepted the terms and conditions',
                    'subject' => 'Accepted Terms And Conditions'
                ]));


                if ($request->filled('from') && $request->from == 'sign_up' && !$request->oldonboardingstate) {  //// send welcome email
                    Mail::to($user->email)->queue(new RegistrationMail([
                        'user_type' => !empty($user->roleType[0]) ? $user->roleType[0]->description : "",
                        'count' => Organization::where('type', 'BANK')->where('status', 'ACTIVE')->count()
                    ]));
                }


                Organization::where('id', $organization->id)->update(['accepted_terms_and_conditions' => 1, 'needs_update' => 'no']);
                if (Organization::where('id', $organization->id)->update(['accepted_terms_and_conditions' => 1])) {
                    //register on hubspot
                    if (app()->environment('production')) {
                        if ($user != null && $organization != null) {
                            $hubproperties = array(
                                "email" => $user->email,
                                "first_name" => $user->first_name,
                                "last_name" => $user->last_name,
                                "telephone" => $user->demographicData->phone,
                                "institution_name" =>  $organization->name,
                            );
                            $hubresponse = $this->newSignUpService->registerToHubSpot($hubproperties);
                            Log::info("HUBSPOT RESPONSE: " . json_encode($hubresponse) . " User: " . json_encode($hubproperties));
                        } else {
                            Log::info("HUBSPOT RESPONSE: Org:" . json_encode($organization) . " User:" . json_encode($user));
                        }
                    }
                    // register on hubspot

                }

                Organization::where('id', $organization->id)->update(['accepted_terms_and_conditions' => 1,'needs_update'=>'no']);


                return response()->json(['data' => [], 'message' => "Accepted the terms and conditions successfully", 'success' => true], 200);
            default:
                break;
        }
    }

    public function depositorSignUp()
    {
        return view('auth.depositor-sign-up');
    }

    public function depositorSignUpPost()
    {
        return (new Api\AuthController())->DSignUpPost(request());
    }

    public function verificationDSignUpPost(Request $request, $code)
    {
        return (new Api\AuthController())->verificationDSignUpPost($request, $code);
    }

    public function loginAsAdmin(Request $request)
    {
        $admin_id = $request->code;
        $admin_id = CustomEncoder::urlValueDecrypt($admin_id);
        $explode = explode("_", $admin_id);
        $admin_id = $explode[0];
        $pin = $explode[1];
        $ipAddress = getIp(CustomEncoder::urlValueEncrypt($admin_id));


        User::where("admin_loggedin_as", $admin_id)->update(["admin_loggedin_as" => NULL]);
        if(Auth::user()){
            \auth()->logout();
        }      

        $user = User::find($admin_id);
        if (!$user) {
            alert()->error("Failed, unauthorised.");
            return redirect()->to('/login');
        }

        if (!$user->is_super_admin) {
            alert()->error("Failed, access denied.");
            return redirect()->to('/login');
        }

   
        $latestPassWord = DB::table("authentication")->where("user_id", $admin_id)->orderBy("id", "DESC")->first();
        if ($latestPassWord) {
            if (!password_verify($pin, $latestPassWord->pin)) {
                OTP::create([
                    'pin' => password_hash($pin, PASSWORD_BCRYPT),
                    'user_id' => $user->id,
                    'created_at' => getUTCDateNow()
                ]);

                Mail::to($user->email)->send(new OTPMail([
                    'pin' => $pin,
                    'user_type' => 'Admin'
                ]));
            }
        } else {

            OTP::create([
                'pin' => password_hash($pin, PASSWORD_BCRYPT),
                'user_id' => $user->id,
                'created_at' => getUTCDateNow()
            ]);

            Mail::to($user->email)->send(new OTPMail([
                'pin' => $pin,
                'user_type' => 'Admin'
            ]));

        }

        $request->session()->put('user_id', $user->encoded_user_id);
        $request->headers->add(['X-User-IP' => $ipAddress]);
        $request->headers->add(['X-Client-IP' => $ipAddress]);
        return redirect()->to('/yie-admin/login?action=verifyOtp');
    }

    public function firstStepSignUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
            'last_name' => 'required|max:25',
            'first_name' => 'required|max:25',
            'phone_number' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        //        if (!$request->filled('recaptcha')) {
        //            $response = array("success" => false, "message" => "You need to verify the recaptcha", "data" => []);
        //            return response()->json($response, 400);
        //        }

        if (!verifyCaptcha($request->recaptcha)) {
            $response = array("success" => false, "message" => "Invalid recaptcha", "data" => []);
            return response()->json($response, 400);
        }


        return $this->newSignUpService->FirstStepSignUp($request);
    }
    public function KeepMeInformed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'email' => 'required|email|max:50',
            'last_name' => 'required|max:25',
            'first_name' => 'required|max:25',
            'phone_number' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }
        return $this->newSignUpService->KeepMeInformed($request);
    }

    public function DPersonalOrganization(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|max:25',
            'type' => 'required|max:25',
            'is_individual' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }
        return $this->newSignUpService->DPersonalOrganization($request);
    }
    public function DBusinessOrganisation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'is_individual' => 'required|boolean',
            'organization_name' => 'required',
            'organization_type' => 'required',
            'industry_id' => 'required',
            'incoporation_province' => 'required',
            'intended_use' => 'required',
            'street' => 'required'
            // 'province' => 'required',
            // 'city' => 'required',
            // 'postal_code' => 'required'

        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }
        $exists = UserOrganization::where('user_id', $request->user_id)->exists();
        if ($exists) {
            $organization_id = UserOrganization::where('user_id', $request->user_id)->first()->organization_id;
            // return 
            if (Organization::find($organization_id)->name == $request->organization_name) {

                return $this->newSignUpService->UpdateDBusinessOrganisation($request);
            } else {
                $validator = Validator::make($request->all(), [
                    'organization_name' => 'required|unique:organizations,name',
                ]);
                if ($validator->fails()) {
                    $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                    return response()->json($response, 400);
                } else {
                    // return $this->newSignUpService->DBusinessOrganisation($request);
                    return $this->newSignUpService->UpdateDBusinessOrganisation($request);
                }
            }
        } else {
            $validator = Validator::make($request->all(), [
                'organization_name' => 'required|unique:organizations,name',
            ]);
            if ($validator->fails()) {
                $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                return response()->json($response, 400);
            } else {
                return $this->newSignUpService->DBusinessOrganisation($request);
            }
        }
    }

    public function UpdateUserInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'telephone' => 'required',
            'job_title' => 'required',
            'timezone' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }
        return $this->newSignUpService->UpdateUserInfo($request);
    }
    public function addOrUpdateEntity(Request $request)
    {
        return $this->newSignUpService->addOrUpdateEntity($request);
    }
    public function addOrUpdateKeyIndividuals(Request $request)
    {
        return $this->newSignUpService->addOrUpdateKeyIndividuals($request);
    }
    public function deleteKeyIndividuals(Request $request)
    {
        return $this->newSignUpService->deleteKeyIndividuals($request);
    }
    public function deleteEntity(Request $request)
    {
        return $this->newSignUpService->deleteEntity($request);
    }
    public function createOrganizationDocuments(Request $request)
    {
        return $this->newSignUpService->createOrganizationDocuments($request);
    }
    public function verifyConferenceSignupAfterApproval($userid)
    {
        return $this->newSignUpService->verifyConferenceSignupAfterApproval($userid);
    }
}

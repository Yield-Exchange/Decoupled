<?php

namespace App\Http\Controllers\Dashboard\Depositor;

use App\Constants;
use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Mail\AdminMail;
use App\Mail\Bank\NonPartneredInvitationRequest;
use App\Mail\Bank\RequestInvitationMail;
use App\Mail\Bank\WithdrawRequestMail;
use App\Mail\Depositor\PostRequestMail;
use App\Models\CreditRatingType;
use App\Models\Deposit;
use App\Models\Campaign;
use App\Models\Offer;
use App\Models\OrganizationDemoGraphicData;
use App\Models\DepositCreditRating;
use App\Models\DepositInsuranceType;
use App\Models\DepositRequest;
use App\Models\InvitedBank;
use App\Models\Organization;
use App\Models\Preference;
use App\Models\Product;
use App\Models\UserOrganization;
use App\Models\UserPassword;
use App\Models\UserPreference;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Util\UrlEncoder;
use function GuzzleHttp\json_decode;

use App\Mail\AdminMails;
use App\Mail\BankMails;
use App\Mail\DepositorMails;

class PostRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        //        $this->middleware('auth.depositor');
        $this->loadUserObject();
    }

    //    public function adminPostRequest(Request $request, $organization_id){
    //        $this->middleware('auth.admin');
    //        $org = Organization::with(['demographicData'])->find(CustomEncoder::urlValueDecrypt($organization_id));
    //
    //        if(!$org){
    //            alert()->error("Failed, organization not found.");
    //            return redirect()->back();
    //        }
    //
    //        $this->organization = $org;
    //        return $this->index($request,null);
    //    }

    public function index(Request $request, $request_id = null)
    {
        $user = $this->user; //auth()->user();
        if (!$user->userCan('depositor/post-request/page-access')) {
            return view('dashboard.403');
        }

        $request->session()->remove('CACHE_INVITES');
        systemActivities(Auth::id(), json_encode($request->query()), $request_id == null ? "Depositor Post Request -> Form page" : "Depositor Edit Post Request -> Form page");

        $deposit_request = null;
        if ($request_id != null) {

            $deposit_request = DepositRequest::with(['invited', 'product'])
                ->where('depositor_requests.organization_id', $this->organization->id)->whereIn('depositor_requests.request_status', ['ACTIVE'])
                ->find(CustomEncoder::urlValueDecrypt($request_id));

            // find(CustomEncoder::urlValueDecrypt($request_id));

            // // $deposit_request->whereHas('invited', function ($query4) use ($request, $request_id) {
            //     $query4->where()
            //     // if ($request->filled("financialOrganizations")) {
            //     //     $orgs = explode(",", $request->financialOrganizations);
            //     //     $query4->whereIn("organizations.name", $orgs);
            //     // }
            // });
            // $deposit_request = DepositRequest::select("depositor_requests.*")->leftJoin('products', 'products.id', '=', 'depositor_requests.product_id')
            // ->leftJoin('invited', 'invited.depositor_request_id', '=', 'depositor_requests.id')
            // ->where('depositor_requests.organization_id', $this->organization->id)->whereIn('depositor_requests.request_status', ['ACTIVE'])
            // ->find(CustomEncoder::urlValueDecrypt($request_id));
            // dd($deposit_request);
        }

        $market_place_offer = null;
        if ($request->filled('market_place_offer')) {
            $market_place_offer = Campaign::find(CustomEncoder::urlValueDecrypt($request->market_place_offer));
            if (!$market_place_offer) {
                alert()->error("Failed, selected market place offer not found.");
                return redirect()->back();
            }

            if ($market_place_offer->status != "ACTIVE") {
                alert()->error("Failed, market place offer is not active.");
                return redirect()->back();
            }
        }
        $organization = $this->organization;
        if ($request->has('demo_setup')) {
            if (!in_array(getEnvironmentNameEmailTag(), ['UAT', 'DEV', 'LOCALHOST'])) {
                alert()->error("Failed, demo setup only allowed in uat,dev or local");
                return redirect()->back();
            }
        }
        $deposit_insurances = DepositInsuranceType::pluck('description')->toArray();
        $credit_rating_types = CreditRatingType::where("status","ACTIVE")->orderBy('id', 'ASC')->pluck('description')->toArray();
        $products = Product::all();

        $unformattedusertimezone = Auth::user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);

        $caninvitefis = $organization->organizationHas('enable_invite_institutions');
        // dd($caninvitefis);
        return view('dashboard.depositor.post_request.index', compact('deposit_request', 'caninvitefis', 'products', 'formattedtimezone', 'market_place_offer', 'organization', 'deposit_insurances', 'credit_rating_types'));
    }

    public function getRequestInvites(Request $request)
    {
        $user = $this->user; //\auth()->user();
        if (!$user->userCan('depositor/post-request/page-access')) {
            $response = array(
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            );
            return response()->json($response);
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Depositor Post Request -> Choose Invites page");
        $draw = $request->draw;

        $data = Organization::select([
            'organizations.*',
        ])->join('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating', 'credit_rating.organization_id', '=', 'organizations.id')
            ->leftJoin('credit_rating_type', 'credit_rating_type.id', '=', 'credit_rating.credit_rating_type_id')
            ->leftJoin('deposit_insurance', 'deposit_insurance.id', '=', 'credit_rating.deposit_insurance_id')
            ->whereIn('organizations.status', ['ACTIVE', 'INVITED', 'REVIEWING'])
            ->where('organizations.type', 'BANK');

        $data = $data->where('organizations.is_test', '=', $user->organization->is_test);

        if ($request->search && $request->search['value']) {
            $searchValue = $request->search['value'];
            $data = $data->where(function ($query) use ($searchValue) {
                $query->where('organizations.name', 'like', "%$searchValue%")
                    ->orWhere('demographic_organization_data.province', 'like', "%$searchValue%")
                    ->orWhere('deposit_insurance.description', 'like', "%$searchValue%");
            });
        }
        $data = $data->where(function ($query) {
            $query->where(function ($query1) {
                $query1->where('organizations.is_non_partnered_fi', 1);
                $query1->where('organizations.created_by', auth()->id());
            });
            $query->orWhere(function ($query1) {
                $query1->where('organizations.is_non_partnered_fi', 0);
            });
        });

        if ($request->credit && is_array($request->credit) && strpos_arr($request->credit, 'Any') === false) {
            $data = $data->whereHas('depositCreditRating.creditRating', function ($query) use ($request) {


                if (in_array('Strong', $request->credit)) {
                    $a[] = 'Very Strong';
                    $b = array_merge($request->credit, $a);
                    $query->whereIn('description', $b);
                } else if (in_array('Adequate', $request->credit)) {
                    $c[] = 'Any/Not Rated';
                    $query->whereNotIn('description', $c);
                } else if (in_array('Very Strong', $request->credit)) {
                    $c[] = 'Very Strong';
                    $query->whereIn('description', $c);
                } else {
                    $query->whereIn('description', $request->credit);
                }
            });
        }

        if ($request->debit && is_array($request->debit) && strpos_arr($request->debit, 'Any') === false) {
            $data = $data->whereHas('depositCreditRating.insuranceRating', function ($query) use ($request) {
                $query->whereIn('description', $request->debit);
            });
        }

        if ($request->action == "confirm-list-banks") {
            if ($request->filled('bank_ids') && is_array($request->bank_ids)) {
                $data = $data->whereIn('organizations.id', $request->bank_ids)->get();
            } else {
                $data = $data->get();
            }
            $response = array("success" => true, "message" => "Successful", "data" => $data);
            return response()->json($response, 200);
        }

        $data = $data->get();
        $data_arr = [];
        $depositor_request_id = $request->filled('req_id') ? $request->req_id : 0;

        foreach ($data as $record) {
            $is_just_invited = in_array($record->id, $request->session()->get('new_invited_fi', []));
            //            if($record->visible_for_provinces){
            //                $record->visible_for_provinces = explode(',',$record->visible_for_provinces);
            //                if(!in_array($record->demographicData->province,$record->visible_for_provinces)){
            //                    continue;
            //                }
            //            }
            //
            //            if($record->visible_for_customers){
            //                if($record->visible_for_customers=="Only Financial Institutions"){
            //                    continue;
            //                }
            //            }
            //
            //            if($record->visible_for_naics_codes){
            //                $record->visible_for_naics_codes = explode(',',$record->visible_for_naics_codes);
            //                if(in_array($record->NAICSCode->code_description,$record->visible_for_naics_codes)){
            //                    continue;
            //                }
            //            }

            $invited_bank = InvitedBank::where('organization_id', $record->id)
                ->where('depositor_request_id', $depositor_request_id)
                ->whereNotIn('invitation_status', ['UNINVITED'])->first();
            if ($invited_bank && in_array($invited_bank->invitation_status, ["INVITED", "PARTICIPATED"])) {

                $is_only_this_invite = false;
                if ($record->created_by == \auth()->id()) {
                    $is_only_this_invite = InvitedBank::where('organization_id', $record->id)->count() == 1;
                }

                if ($record->is_non_partnered_fi == 1 && $is_only_this_invite) {
                    $checkbox = ' <input type="checkbox" id="' . $record->id . '" class="select_row_non_fi" checked disabled />';
                } else {
                    $checkbox = ' <input type="checkbox" id="' . $record->id . '" class="select_row" checked />';
                }
            } else {
                if ($is_just_invited) {
                    $checkbox = ' <input type="checkbox" id="' . $record->id . '" class="select_row_non_fi" checked disabled />';
                } else {
                    $CACHE_INVITES = $request->session()->get('CACHE_INVITES', "");
                    if (!empty($CACHE_INVITES) && ($CACHE_INVITES == 'all' || is_array($CACHE_INVITES) && in_array($record->id, $CACHE_INVITES))) {
                        $checkbox = ' <input type="checkbox" id="' . $record->id . '" class="select_row" checked  />';
                    } else {
                        $checkbox = ' <input type="checkbox" id="' . $record->id . '" class="select_row"  />';
                    }
                }
            }

            $organization = $record;
            $data_arr[] = array(
                "id" => $organization->id,
                "name" => $organization->name,
                "province" => $organization->demographicData ? $organization->demographicData->province : '',
                "credit_rating" => $organization->depositCreditRating && $organization->depositCreditRating->creditRating ? $organization->depositCreditRating->creditRating->description : '',
                "deposit_insurance" => $organization->depositCreditRating && $organization->depositCreditRating->insuranceRating ? $organization->depositCreditRating->insuranceRating->description : '',
                "checkbox" => $checkbox,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $data->count(),
            "iTotalDisplayRecords" => $data->count(),
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function postRequestNonPartnered(Request $request)
    {
        $user = $this->user; //\auth()->user();
        if (!$user->userCan('depositor/post-request/page-access')) {
            $response = ['data' => [], 'message' => "Access denied", 'success' => false];
            return response()->json($response, 403);
        }

        $action = $request->filled('action') ? $request->action : "";
        switch ($action) {
            case 'CLEAR_CACHED_INVITES':
                if ($request->CACHE_INVITES == 'all') {
                    $request->session()->remove('CACHE_INVITES');
                } else {
                    if ($request->session()->get('CACHE_INVITES') == 'all') {
                        $new_request = new Request();
                        $new_request->request->add([
                            'confirm-list-banks' => 1,
                            'debit' => $request->debit,
                            'credit' => $request->credit
                        ]);
                        $banks = $this->getRequestInvites($new_request);
                        $data = json_decode($banks->content(), TRUE);
                        $ids = collect($data['data'])->pluck("id")->toArray();
                        $request->session()->put('CACHE_INVITES', $ids);
                    } else {
                        $request->session()->put('CACHE_INVITES', array_diff($request->session()->get('CACHE_INVITES'), array($request->CACHE_INVITES)));
                    }
                }
                return response()->json(['data' => [], 'message' => "Cleared successfully", 'success' => true], 200);
            case 'CACHE_INVITES':
                if ($request->filled('CACHE_INVITES')) {
                    if ($request->CACHE_INVITES == 'all') {
                        $request->session()->put('CACHE_INVITES', $request->CACHE_INVITES);
                    } else {
                        $cached_invites = $request->session()->get('CACHE_INVITES', []);
                        array_push($cached_invites, $request->CACHE_INVITES);
                        $request->session()->put('CACHE_INVITES', $cached_invites);
                    }
                }
                return response()->json(['data' => [], 'message' => "Cached successfully", 'success' => true], 200);
            case 'CANCEL_NON_PARTNERED_INVITES':
                systemActivities(Auth::id(), json_encode($request->query()), "Newly created non partnered FI discarded on navigation to other pages");

                $organizations = Organization::where('type', 'BANK')
                    ->where('is_temporary', 1)
                    ->where('created_by', auth()->id())->get();

                $organization_ids = $organizations->pluck('id')->toArray();

                User::join('users_organizations', 'users_organizations.user_id', '=', 'users.id')
                    ->whereIn('users_organizations.organization_id', $organization_ids)
                    ->delete();

                UserOrganization::whereIn('organization_id', $organization_ids)->delete();

                $request->session()->remove("new_invited_fi");
                $request->session()->remove("new_invited_fi_account_managers");

                return response()->json(['data' => [], 'message' => "Invites discarded successfully", 'success' => true], 200);
                break;
            case 'INVITE_NEW_FI':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                    're_enter_email' => 'required|email',
                ]);

                if ($validator->fails()) {
                    $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                    return response()->json($response, 400);
                }

                systemActivities(Auth::id(), json_encode($request->query()), "Newly created a non partnered FI for their offer");

                $pass = getRandomNumberBetween(11024, 103540);
                if ($request->email != $request->re_enter_email) {
                    return response()->json(['data' => [], 'message' => "The emails do not match.", 'success' => false]);
                }

                $user = User::select([
                    'users.*',
                    'organizations.is_non_partnered_fi as organization_is_non_partnered_fi',
                    'organizations.status as organization_status'
                ])->with(['userPassword'])
                    ->leftJoin('users_organizations', 'users_organizations.user_id', '=', 'users.id')
                    ->leftJoin('organizations', 'users_organizations.organization_id', '=', 'organizations.id')
                    ->whereIn('account_status', systemActiveUsersStatuses())->where('email', $request->email);

                $user = $user->where('organizations.type', "BANK")->first();
                if ($user) {
                    systemActivities(Auth::id(), json_encode($request->query()), "User with that email already exist.");
                    return response()->json(["message" => 'User with that email already exist', "data" => [], "success" => false], 409);
                }

                if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                    return response()->json(["message" => 'Invalid email.', "data" => [], "success" => false], 400);
                }

                // Check the Institution if has been added within the few seconds/minutes
                $organization = Organization::where('name', $request->name)
                    ->where('type', 'BANK')
                    ->whereIn('status', ['PENDING', 'ACTIVE', 'INVITED', 'REVIEWING'])
                    ->first();
                if ($organization) {
                    return response()->json(["message" => 'Failed, the institution is already invited.', "data" => [], "success" => false], 400);
                }

                //                $organization = Organization::where('name',$request->name)
                //                    ->where('type','BANK')
                //                    ->whereIn('status',['DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS'])
                //                    ->first();
                //                if ($organization && $organization->status=="DECLINED_INVITATION"){
                //                    $message = "Please invite a different Account Manager at ".$organization->name.". If you do not know another Account Manager please contact Yield Exchange for more information";
                //                    return response()->json(["message" =>$message, "data" => [], "success" => false], 400);
                //                }
                //
                //                if ($organization && $organization->status=="DECLINED_TERMS_AND_CONDITIONS"){
                //                    return response()->json(["message" =>"Failed, ".$organization->name." has declined to participate at Yield Exchange. Please invite another FI to yield exchange.", "data" => [], "success" => false], 400);
                //                }
                //
                //                if ( $request->filled('stage') && $request->stage=="1" ){
                //                    return response()->json(["message" =>"Next Stage", "data" => [], "success" => true], 200);
                //                }
                //
                //                if ( !$request->filled('account_manager_name') ) {
                //                    $your_name=$user->name;
                //                } else{
                //                    $your_name = $request->account_manager_name;
                //                }
                //
                //                $account_manager=$request->filled('account_manager_name')  ? $request->account_manager_name : '';

                try {
                    DB::beginTransaction();

                    $created_user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'account_opening_date' => getUTCDateNow(true),
                        'account_status' => 'INVITED',
                        'failed_login_attempts' => 0,
                        'timezone' => "Central",
                        'created_by' => auth()->id(),
                    ]);

                    // create organizations
                    $created_organization = Organization::create([
                        'name' => $created_user->name,
                        'logo' => NULL,
                        'type' => "BANK",
                        'admin_user_id' => $created_user->id,
                        'is_non_partnered_fi' => true,
                        'created_by' => auth()->id(),
                        'is_temporary' => 1,
                        'account_manager' => '',
                        'inviter_name' => '',
                        'status' => 'INVITED'
                    ]);

                    // link users to organizations
                    UserOrganization::create([
                        'user_id' => $created_user->id,
                        'organization_id' => $created_organization->id,
                        'status' => 'ACTIVE',
                        'switched_organization_type' => NULL,
                    ]);

                    OrganizationDemoGraphicData::create([
                        'address1' => "",
                        'address2' => "",
                        'city' => "",
                        'province' => "",
                        'postal_code' => "",
                        'telephone' => "",
                        'created_at' => getUTCDateNow(),
                        'organization_id' => $created_organization->id,
                    ]);

                    $password = password_hash($pass, PASSWORD_BCRYPT);
                    UserPassword::create([
                        'hash' => $password,
                        'created_at' => getUTCDateNow(true),
                        'user_id' => $created_user->id
                    ]);

                    $role = Role::where('name', 'organization-administrator')->first();
                    if (!$role) {
                        $role = Role::create([
                            'name' => 'organization-administrator',
                            'display_name' => 'Organization Administrator',
                            'description' => 'The Overall Organization managers',
                            'organization_id' => 0
                        ]);
                    }

                    DB::table('role_user')->insert([
                        'role_id' => $role->id,
                        'user_id' => $created_user->id,
                        'user_type' => $role->display_name
                    ]);

                    $preference = Preference::where("name", "mute_notification")->first();
                    if ($preference) {
                        UserPreference::create([
                            'value' => 0,
                            'preference_id' => $preference->id,
                            'user_id' => $created_user->id
                        ]);
                    }

                    $short_term_credit = CreditRatingType::where('description', 'Any/Not Rated')->first();
                    $deposit_insurance = DepositInsuranceType::where('description', 'Any')->first();
                    DepositCreditRating::create([
                        'credit_rating_type_id' => $short_term_credit ? $short_term_credit->id : 0,
                        'deposit_insurance_id' => $deposit_insurance ? $deposit_insurance->id : 0,
                        'organization_id' => $created_organization->id
                    ]);

                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    $timestamp = time();
                    Log::error($timestamp . ': ' . $exception->getTraceAsString());
                    return response()->json(["message" => 'Failed, unable to complete invitation, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 409);
                }

                $new_invited_fi = [];
                $new_invited_fi_account_managers = [];
                if ($request->session()->has('new_invited_fi')) {
                    $new_invited_fi = $request->session()->get('new_invited_fi');
                    $new_invited_fi_account_managers = $request->session()->get('new_invited_fi_account_managers');
                }

                $new_invited_fi_account_managers[$created_user->id] = [
                    'account_manager' => '',
                    'your_name' => ''
                ];

                array_push($new_invited_fi, $created_user->id);
                $request->session()->put('new_invited_fi', $new_invited_fi);
                $request->session()->put('new_invited_fi_account_managers', $new_invited_fi_account_managers);

                return response()->json(['data' => [], 'message' => "FI invited successfully.", 'success' => true], 200);
            default:
                return response()->json(['data' => [], 'message' => 'Unknown action, please retry', 'success' => false], 400);
        }
    }

    public function postRequestSubmit(Request $request)
    {
        $user = $this->user; //\auth()->user();

        if (\request()->filled('depositor_demo_setup')) {
            if (!in_array(getEnvironmentNameEmailTag(), ['UAT', 'DEV', 'LOCALHOST'])) {
                return response()->json(['data' => [], 'message' => "Failed, demo setup only allowed in uat,dev or local", 'success' => false], 400);
            }
        }

        if ($user->is_super_admin) {
            if (!$request->filled('organization_id')) {
                return response()->json(['data' => [], 'message' => "Failed, organization is missing", 'success' => false], 400);
            }

            $this->organization = Organization::with(['demographicData'])->find($request->organization_id);
        }

        if (!$user->userCan('depositor/post-request/post-request-button')) {
            $response = ['data' => [], 'message' => "Access denied", 'success' => false];
            return response()->json($response, 403);
        }

        if ($request->has('closing_date_time') && is_array($request->closing_date_time)) {
            $closing_date_times = [];
            foreach ($request->closing_date_time as $closing_date_time) {
                array_push($closing_date_times, removeAmPm($closing_date_time));
            }
            $request->merge(['closing_date_time' => $closing_date_times]);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required',
            //            'lockout_period' => 'required',
            'deposit_currency' => 'required|array',
            'deposit_currency.*' => 'required|in:CAD,USD',
            'deposit_amount' => "required|array",
            'deposit_amount.*' => "required",
            //            'term_type' => 'required|in:months,days',
            //            'term_length' =>'required',
            'closing_date_time' => 'required|array',
            // 'closing_date_time.*' => 'required|date_format:Y-m-d H:i',
            'closing_date_time.*' => 'required|date_format:Y-m-d',
            // 'date_of_deposit' => 'required|array',
            // 'date_of_deposit.*' => 'required|date_format:Y-m-d',
            'compound_frequency' => 'required|array',
            'compound_frequency.*' => 'required',
            //            'requested_rate' => 'required',
            'credit_rating' => 'required|array',
            'credit_rating.*' => 'required',
            'deposit_insurance' => 'required|array',
            'deposit_insurance.*' => 'required',
            //            'special_instructions',
            'invited' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        if (count($request->product_id) > 10) {
            $response = array("success" => false, "message" => "You have exceeded the maximum number of request you can post at a go", "data" => []);
            return response()->json($response, 400);
        }

        if ($request->filled('market_place_offer_id')) {
            $market_place_offer = Campaign::find($request->market_place_offer_id);
            if (!$market_place_offer) {
                $response = array("success" => false, "message" => "Failed, selected market place offer not found.", "data" => []);
                return response()->json($response, 404);
            }
        }

        $request->invited = explode(",", $request->invited);
        $product_does_not_exist_error = 0;
        $closing_date_time_greater_than_now = 0;
        $closing_date_time_greater_than_deposit_date = 0;
        $high_interest_product_validation_failed = 0;
        $failed_creation = 0;
        $success = 0;
        $demo_value_greater_than = 0;
        $bulk_reference_no = count($request->product_id) > 1 ? generateDepositRequestBulkReference() : NULL;
        $admin_loggedin_as = $user->admin_loggedin_as;

        /// dont send asingle email per product 
        $created_products = [];
        $created = false;

        foreach ($request->product_id as $key => $item) {
            $product = Product::where('description', $item)->first();
            if (!$product) {
                if ($request->filled('deposit_request_id')) {
                    $response = array("success" => false, "message" => "Product selected does not exist.", "data" => []);
                    return response()->json($response, 400);
                }
                $product_does_not_exist_error++;
                continue;
            }

            $closing_date_time = Carbon::parse(removeAmPm($request->closing_date_time[$key]));
            $closing_date_time->setTime(23, 59);
            $date_time_now = Carbon::now();
            if ($closing_date_time->lessThan($date_time_now)) {
                if ($request->filled('deposit_request_id')) {
                    $response = array("success" => false, "message" => "Closing date and time should not be less than now", "data" => []);
                    return response()->json($response, 400);
                }
                $closing_date_time_greater_than_now++;
                continue;
            }

            $date_of_deposit = Carbon::parse($request->closing_date_time[$key]);
            $date_of_deposit->setTime(23, 59);

            // if ($closing_date_time->greaterThan($date_of_deposit)) {
            //     if ($request->filled('deposit_request_id')) {
            //         $response = array("success" => false, "message" => "Closing date and time should not be greater than the date of deposit", "data" => []);
            //         return response()->json($response, 400);
            //     }
            //     $closing_date_time_greater_than_deposit_date++;
            //     continue;
            // }

            $is_demo = false;
            if (\request()->filled('depositor_demo_setup')) {
                $is_demo = true;
            }

            if ($is_demo) {
                $rates_and_deposits = \json_decode($request->rates_and_deposits);
                if ($rates_and_deposits) {
                    $rates_and_deposits =  collect($rates_and_deposits);
                    $amount = str_replace(",", "", trim($request->deposit_amount[$key]));

                    foreach ($rates_and_deposits as $rates_and_deposit) {
                        $max_amount = (float)str_replace(",", "", trim($rates_and_deposit->max_amount));
                        $min_amount = (float)str_replace(",", "", trim($rates_and_deposit->min_amount));

                        $is_max_greater = $max_amount > $amount;
                        $is_min_greater = $min_amount > $amount;

                        if ($is_max_greater || $is_min_greater) {

                            $response = array("success" => false, "message" => "Min or Max should not be greater than request amount", "data" => []);
                            return response()->json($response, 400);
                        }
                    }
                }
            }

            $lockout_period = $request->filled('lockout_period') && in_array(trim(strtolower($product->description)), ['notice deposit', 'cashable', 'high interest savings']) ? $request->lockout_period[$key] : 0;
            $term_type = 'HISA';
            $term_length = 0;
            if (strpos($product->description, 'High Interest Savings') === false) {
                $validation_data = [
                    'term_type' => $request->term_type[$key],
                    'term_length' => $request->term_length[$key],
                ];
                $validator = Validator::make($validation_data, [
                    'term_type' => 'required|in:months,days',
                    'term_length' => 'required|min:0',
                ]);
                if ($validator->fails()) {
                    if ($request->filled('deposit_request_id')) {
                        $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                        return response()->json($response, 400);
                    }
                    $high_interest_product_validation_failed++;
                    continue;
                }

                if (strtolower($request->term_type[$key]) == "months") {
                    if ($request->term_length[$key] > 120) {
                        $response = array("success" => false, "message" => "Term length can not exceed 120 months", "data" => []);
                        return response()->json($response, 400);
                    }
                }

                if (strtolower($request->term_type[$key]) == "days") {
                    if ($request->term_length[$key] > 3650) {
                        $response = array("success" => false, "message" => "Term length can not exceed 3650 days ", "data" => []);
                        return response()->json($response, 400);
                    }
                }

                $term_type = $request->term_type[$key];
                $term_length = $request->term_length[$key];
            }
            $deposit_created = null;
            if ($request->filled('deposit_request_id')) {
                $deposit_created = DepositRequest::find($request->deposit_request_id);
                if (!$deposit_created) {
                    $response = array("success" => false, "message" => "Unable to edit the post request.. Please retry", "data" => []);
                    return response()->json($response, 400);
                }
            }
            $created = true;
            $organization = $this->organization;

            $date_of_deposit = Carbon::parse($date_of_deposit)->setTime(23, 59, 59);
            if (!empty($deposit_created)) {
                $deposit_created->update([
                    'term_length_type' => $term_type,
                    'term_length' => $term_length,
                    'lockout_period_days' => $lockout_period,
                    'closing_date_time' => changeDateFromLocalToUTC($closing_date_time->format(Constants::DATE_TIME_FORMAT), Constants::DATE_TIME_FORMAT),
                    'amount' => str_replace(",", "", trim($request->deposit_amount[$key])),
                    'currency' => $request->deposit_currency[$key],
                    'date_of_deposit' => changeDateFromLocalToUTC($date_of_deposit->format(Constants::DATE_TIME_FORMAT), Constants::DATE_TIME_FORMAT),
                    'compound_frequency' => $request->compound_frequency[$key],
                    // 'requested_rate' => !empty($request->requested_rate[$key]) ? $request->requested_rate[$key] : 0,
                    'requested_short_term_credit_rating' => $request->credit_rating[$key],
                    'requested_deposit_insurance' => $request->deposit_insurance[$key],
                    'special_instructions' => !empty($request->special_instructions[$key]) ? $request->special_instructions[$key] : '',
                    'product_id' => $product->id,
                    'admin_loggedin_as' => $admin_loggedin_as
                ]);
                $deposit_created = DepositRequest::find($request->deposit_request_id);
                $created = false;
            } else {
                $deposit_created = DepositRequest::create([
                    'reference_no' => generateDepositRequestReference(),
                    'term_length_type' => $term_type,
                    'term_length' => strtoupper(trim($term_length)),
                    'lockout_period_days' => $lockout_period,
                    'closing_date_time' => $is_demo ? changeDateFromLocalToUTC(now()->format(Constants::DATE_TIME_FORMAT), Constants::DATE_TIME_FORMAT) : changeDateFromLocalToUTC($closing_date_time->format(Constants::DATE_TIME_FORMAT), Constants::DATE_TIME_FORMAT),
                    'amount' => str_replace(",", "", trim($request->deposit_amount[$key])),
                    'currency' => $request->deposit_currency[$key],
                    'date_of_deposit' => changeDateFromLocalToUTC($date_of_deposit->format(Constants::DATE_TIME_FORMAT), Constants::DATE_TIME_FORMAT),
                    'compound_frequency' => $request->compound_frequency[$key],
                    // 'requested_rate' => !empty($request->requested_rate[$key]) ? $request->requested_rate[$key] : 0,
                    'requested_short_term_credit_rating' => $request->credit_rating[$key],
                    'requested_deposit_insurance' => $request->deposit_insurance[$key],
                    'special_instructions' => !empty($request->special_instructions[$key]) ? $request->special_instructions[$key] : '',
                    'request_status' => $organization->is_partially_approved ? 'ON_REVIEW' : 'ACTIVE'/*($user->is_super_admin ? 'COMPLETED' : 'ACTIVE')*/,
                    'created_date' => getUTCDateNow(true),
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'bulk_reference_no' => $bulk_reference_no,
                    'organization_id' => $organization->id,
                    'market_place_offer_id' => $request->filled('market_place_offer_id') ? $request->market_place_offer_id : NULL, // for requests that are created from market place offers {Shop Rate Button}
                    'admin_loggedin_as' => $admin_loggedin_as
                ]);
            }
            array_push($created_products, $deposit_created);

            if (!$deposit_created) {
                $failed_creation++;
                continue;
            }


            // Make the invites
            //  $this->sendInvites($request, $deposit_created);     send to fi email
            $success++;
        }

        //// send emails here
        // $organization=$this->organization;
        $notify_users = $organization->notifiableUsersEmails($return_emails = false);
        foreach ($notify_users as $user) {
            //Email to Depositor
            $datetime_from_utc = changeDateFromUTCtoLocal($created_products[0]->closing_date_time, $format = 'M d Y', null, $user->timezone) . ' ' . $user->timezone;
            if (!empty($organization) && $organization->is_partially_approved) {
                Mail::to($user->email)->queue(new DepositorMails([
                    'subject' => "Your request is now Live",
                    'new_request_details' => ['product' => $product, 'term_length' => $term_type, 'term_length_type' => $term_length, 'lockout_period' => $lockout_period, 'closing_date' => $datetime_from_utc],
                    'user_type' => "Depositor"
                ], 'new_post_request'));
            } else {

                Mail::to($user->email)->queue(new DepositorMails([
                    'subject' => "Your request is now Live",
                    'new_request_details' => ['product' => $product, 'term_length' => $term_type, 'term_length_type' => $term_length, 'lockout_period' => $lockout_period, 'closing_date' => $datetime_from_utc],
                    'user_type' => "Depositor"
                ], 'new_post_request'));
            }
        }

        if ($created) {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => 'New Deposit Request from ' . $created_products[0]->organization->name,
                'new_request_details' => ['products' => $created_products, 'created_deposit' => $deposit_created, 'product' => $product, 'currency' => $request->deposit_currency[$key], 'user' => $user],
            ], 'new_post_request'));
        }      /// consolidated and send to admin

        $this->sendInvites($request, $created_products);    /// send to fi email

        // Unset sessions
        $request->session()->remove('new_invited_fi');
        $request->session()->remove('new_invited_fi_account_managers');
        $request->session()->remove('CACHE_INVITES');

        if (!empty($organization) && $organization->is_partially_approved) {
            $response_title = "Your request has been posted and is currently on review.";
            $response = "The selected Financial institutions will be notified once approved.";
        } else {
            $response = "The selected Financial institutions are being notified.";
            $response_title = "Your Request has been updated.";
        }

        $total_requests = count($request->product_id);
        if (!$request->filled('deposit_request_id')) {
            $response = "";
            $response_title = "";
            if ($success > 0) {
                if (!empty($organization) && $organization->is_partially_approved) {
                    $response_title = $success . "/" . $total_requests . " requests have been posted and is currently on review. ";
                } else {
                    $response_title = $success . "/" . $total_requests . " requests have been posted. ";
                }
                if ($total_requests == 1) {
                    if (!empty($organization) && $organization->is_partially_approved) {
                        $response_title = "Your request has been posted and is currently on review.";
                        $response = "The selected Financial institutions will be notified once approved.";
                    } else {
                        $response_title = "Your request has been posted.";
                        $response = "The selected Financial institutions are being notified.";
                    }
                } else {
                    if ($success == $total_requests) {
                        if (!empty($organization) && $organization->is_partially_approved) {
                            $response = "The selected Financial institutions will be notified once approved.";
                        } else {
                            $response = "The selected Financial institutions are being notified.";
                        }
                    }
                }
            }

            $multiple_fails = false;
            if ($failed_creation > 0) {
                if ($total_requests == 1) {
                    $response_title = "Your request failed to create.";
                    $response = "Please try again or contact a system admin.";
                } else {
                    $response = $failed_creation . " of your requests failed to create. ";
                    $multiple_fails = true;
                }
            }

            if ($product_does_not_exist_error > 0) {
                if ($total_requests == 1) {
                    $response_title = "Your request failed as product does not exists.";
                    $response = "Please try again or contact a system admin.";
                } else {
                    $response = $product_does_not_exist_error . " of your requests failed as product does not exist. ";
                    $multiple_fails = true;
                }
            }

            if ($closing_date_time_greater_than_now > 0) {
                if ($total_requests == 1) {
                    $response_title = "Your request failed as closing date and time should not be less than now.";
                    $response = "Please try again or contact a system admin.";
                } else {
                    $response = $closing_date_time_greater_than_now . " of your requests failed as closing date and time should not be less than now. ";
                    $multiple_fails = true;
                }
            }

            if ($closing_date_time_greater_than_deposit_date > 0) {
                if ($total_requests == 1) {
                    $response_title = "Your request failed as closing date and time should not be greater than the date of deposit.";
                    $response = "Please try again or contact a system admin.";
                } else {
                    $response = $closing_date_time_greater_than_deposit_date . " of your request failed as closing date and time should not be greater than the date of deposit. ";
                    $multiple_fails = true;
                }
            }

            if ($demo_value_greater_than > 0) {
                if ($total_requests == 1) {
                    $response_title = "Your request failed as Min or Max should not be greater than request amount.";
                    $response = "Please try again or contact a system admin.";
                } else {
                    $response = $demo_value_greater_than . " of your request failed as Min or Max should not be greater than request amount. ";
                    $multiple_fails = true;
                }
            }

            if ($high_interest_product_validation_failed > 0) {
                if ($total_requests == 1) {
                    $response_title = "Your request failed as term length should only be in month/days.";
                    $response = "Please try again or contact a system admin.";
                } else {
                    $response = $high_interest_product_validation_failed . " of your request failed as term length should only be in month/days. ";
                    $multiple_fails = true;
                }
            }

            if ($multiple_fails) {
                $response .= " Please try again or contact a system admin.";
            }
        }
        $response = array("success" => $success > 0, "message" => $response, "message_title" => $response_title, "data" => []);
        return response()->json($response, $success > 0 ? 200 : 400);
    }

    private function sendInvites($request, $deposit_created)
    {
        $user = $this->user; //auth()->user();

        if ($user->is_super_admin || \request()->filled('depositor_demo_setup')) {
            foreach ($deposit_created as $deposit) {
                return $this->requestToDeposit($request, $deposit);
            }
        }

        $banks = Organization::whereIn('status', ['ACTIVE', 'REVIEWING'])
            ->where('organizations.type', 'BANK')
            ->select([
                'organizations.*'
            ])->find($request->invited);
            foreach ($deposit_created as $deposit) {
                InvitedBank::where('depositor_request_id', $deposit->id)
                    ->whereNotIn('organization_id', $request->invited)->update([
                        'invitation_status' => 'UNINVITED'
                    ]);
            }


        $depositor_organization = $this->organization;
        foreach ($banks as $bank) {

            foreach ($deposit_created as $deposit) {
                $invitation = InvitedBank::where('depositor_request_id', $deposit->id)
                    ->where('organization_id', $bank->id)
                    ->first();
                if (!$invitation || $invitation->invitation_status == 'UNINVITED') {

                    $invitation_data = [
                        'invitation_date' => getUTCDateNow(true),
                        'depositor_request_id' => $deposit->id,
                        'organization_id' => $bank->id,
                        'invited_user_id' => $bank->admin_user_id,
                        'invitation_status' => $deposit->organization->is_partially_approved ? 'ON_REVIEW' : 'INVITED'
                    ];

                    if ($invitation) {
                        $invitation->update($invitation_data);
                    } else {
                        InvitedBank::create($invitation_data);
                    }

                    if ($deposit->organization->is_partially_approved) {
                        return;
                    }

                    $new_invited_fi = $request->session()->get('new_invited_fi', []);
                    if ($bank->is_non_partnered_fi && in_array($bank->id, $new_invited_fi)) {
                        $account_manager = trim($bank->account_manager);
                        $your_name = trim($bank->inviter_name);

                        if ($your_name != $user->name) {
                            $subject = $your_name . ' at ' . $depositor_organization->name . ' has invited you to join Yield Exchange';
                            $header =  $your_name . ' has invited you to join Yield Exchange';
                        } else {
                            $subject = trim($depositor_organization->name) . ' has invited you to join Yield Exchange.';
                            $header =  trim($depositor_organization->name) . ' has invited you to join Yield Exchange.';
                        }

                        $links = route('user.non-fi-view-invitation', CustomEncoder::urlValueEncrypt($bank->id));
                        // Mail::to($bank->notifiableUsersEmails())->queue(new NonPartneredInvitationRequest([    ////consort with David about this 
                        //     'subject'=>$subject,
                        //     'header'=>$header,
                        //     'message'=>[
                        //         'account_manager'=>$account_manager,
                        //         'your_name'=>$your_name,
                        //         'email'=>$user->email,
                        //         'telephone'=>$user->demographicData->telephone
                        //     ],
                        //     'other_buttons'=>[['linkName'=>'View Invitation','link'=>$links]]
                        // ]));

                        if ($bank->is_temporary) {
                            $bank->is_temporary = 0;
                            $bank->save();
                        }

                        notify([
                            'type' => 'POST REQUEST',
                            'details' => "A Request has been Posted, Ref: " . $deposit->reference_no,
                            'date_sent' => getUTCDateNow(true),
                            'status' => 'ACTIVE',
                            'organization_id' => $bank->id
                        ]);
                    }
                }
            }

            $user_objects = $bank->notifiableUsersEmails($return_emails = false);

            for ($i = 0; $i < count($user_objects); $i++) {

                $bank_timezone = $user_objects[$i]->demographicData->timezone;
                $datetime_from_utc = changeDateFromUTCtoLocal($deposit->closing_date_time, 'M d Y', null, $bank_timezone) . ' ' . $bank_timezone;
                //$message ="<p><center>".$depositor_organization->name." has invited you to a deposit of ".$deposit_created->currency." ".number_format($deposit_created->amount).". If you are interested in putting in an offer, please respond before ".$datetime_from_utc."</center></p>";

                Mail::to($user_objects[$i])->queue(new BankMails([
                    'subject' => "You have a deposit opportunities",
                    'new_request_details' => ['products' => $deposit_created, 'depositor' => $depositor_organization, 'bank' => $bank, 'closing_date' => $datetime_from_utc],
                    'user_type' => "Bank"
                ], 'new_post_request'));
            }
        }
    }

    public function postRequestWithdraw(Request $request, $request_id)
    {
        $user = $this->user; //\auth()->user();
        if (!$user->userCan('depositor/review-offers/withdraw-request')) {
            $response = ['data' => [], 'message' => "Access denied", 'success' => false];
            return response()->json($response, 403);
        }

        $validator = Validator::make($request->all(), [
            'req_id' => 'required',
            'reason' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $deposit_request = DepositRequest::find(CustomEncoder::urlValueDecrypt($request_id));
        if (!$deposit_request) {
            systemActivities(Auth::id(), json_encode($request->query()), "Depositor Review Offers -> Withdraw, Failed.. deposit not found");
            $response = array("success" => false, "message" => "Deposit not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => []);
            return response()->json($response, 400);
        }

        if ($deposit_request->request_status == "ACTIVE") {
            archiveTable($deposit_request->id, "depositor_requests", \auth()->id(), "WITHDRAWN");
            $deposit_request->request_status = 'WITHDRAWN';
            $deposit_request->request_withdrawal_reason = $request->reason;
            $deposit_request->save();

            $invitations = InvitedBank::where('depositor_request_id', $deposit_request->id)->get();
            /// notify depositor
            $depositor = Organization::find($deposit_request->organization_id);
            Mail::to($depositor->notifiableUsersEmails())->queue(new WithdrawRequestMail([
                'message' => $deposit_request->reference_no
            ]));
            foreach ($invitations as $invitation) {
                $bank = $invitation->organization;
                if ($bank) {
                    //$message = "Request ID " . $deposit_request->reference_no . " has been withdrawn.";
                    Mail::to($bank->notifiableUsersEmails())->queue(new WithdrawRequestMail([
                        'message' => $deposit_request->reference_no
                    ]));
                }

                if ($invitation->offer) {
                    $offer = $invitation->offer;
                    archiveTable($offer->id, "offers", \auth()->id(), "REQUEST_WITHDRAWN");
                    $offer->offer_status = "REQUEST_WITHDRAWN";
                    $offer->save();
                }
            }

            $response = array("success" => true, "message" => "Request withdrawn successfully", "data" => []);
            return response()->json($response, 200);
        }

        $response = array("success" => false, "message" => "Request can not be withdrawn, its no longer active", "data" => []);
        return response()->json($response, 400);
    }

    private function requestToDeposit(Request $request, $deposit_created)
    {
        $rates_and_deposits = \json_decode($request->rates_and_deposits);
        $rates_and_deposits = collect($rates_and_deposits);

        $banks = Organization::whereIn('status', ['ACTIVE', 'REVIEWING', 'INVITED'])
            ->where('organizations.type', 'BANK')
            ->select([
                'organizations.*'
            ])->find($request->invited);

        InvitedBank::where('depositor_request_id', $deposit_created->id)
            ->whereNotIn('organization_id', $request->invited)->update([
                'invitation_status' => 'UNINVITED'
            ]);

        foreach ($banks as $bank) {
            $invitation = InvitedBank::where('depositor_request_id', $deposit_created->id)
                ->where('organization_id', $bank->id)
                ->first();
            if (!$invitation || $invitation->invitation_status == 'UNINVITED') {

                $invitation_data = [
                    'invitation_status' => 'PARTICIPATED',
                    'invitation_date' => getUTCDateNow(true),
                    'depositor_request_id' => $deposit_created->id,
                    'organization_id' => $bank->id,
                    'invited_user_id' => $bank->admin_user_id
                ];

                if ($invitation) {
                    $invitation->update($invitation_data);
                    $invited = InvitedBank::find($invitation->id);
                } else {
                    $invited = InvitedBank::create($invitation_data);
                }

                $new_invited_fi = $request->session()->get('new_invited_fi', []);
                if ($bank->is_non_partnered_fi && in_array($bank->id, $new_invited_fi)) {
                    if ($bank->is_temporary) {
                        $bank->is_temporary = 0;
                        $bank->save();
                    }

                    notify([
                        'type' => 'POST REQUEST',
                        'details' => "A Request has been Posted, Ref: " . $deposit_created->reference_no,
                        'date_sent' => getUTCDateNow(true),
                        'status' => 'ACTIVE',
                        'organization_id' => $bank->id
                    ]);
                }

                $current_rates_and_deposits = $rates_and_deposits->where('organization_id', "$bank->id")->first();
                $create_array = [
                    'invitation_id' => $invited->id,
                    'reference_no' => generateOfferReference(),
                    'offer_status' => 'ACTIVE',
                    'created_date' => getUTCDateNow(),
                    'maximum_amount' => $current_rates_and_deposits ? str_replace(",", "", $current_rates_and_deposits->max_amount) : 0,
                    'minimum_amount' => $current_rates_and_deposits ? str_replace(",", "", $current_rates_and_deposits->min_amount) : 0,
                    'interest_rate_offer' => $current_rates_and_deposits ? $current_rates_and_deposits->rate : 0,
                    'rate_held_until' => Carbon::createFromFormat('Y-m-d H:i:s', $deposit_created->date_of_deposit)->addDays(1),
                    'product_disclosure_url' => NULL,
                    'special_instructions' => '',
                    'rate_type' => 'FIXED',
                    'prime_rate' => NULL,
                    'rate_operator' => NULL,
                    'fixed_rate' => NULL,
                    'user_id' => \auth()->id(),
                ];

                if (!empty($product_disclosure_statement)) {
                    $create_array['product_disclosure_statement'] = $product_disclosure_statement;
                }

                $created = Offer::create($create_array);
                if (!\request()->filled('depositor_demo_setup')) {
                    if (!$deposit_created->is_demo) {
                        Deposit::create([
                            'reference_no' => generateOfferContractID($deposit_created->reference_no),
                            'offer_id' => $created->id,
                            'offered_amount' => $current_rates_and_deposits ? str_replace(",", "", $current_rates_and_deposits->amount) : 0,
                            'status' => 'ACTIVE',
                            'created_at' => getUTCDateNow(),
                            'gic_number' => $current_rates_and_deposits ? $current_rates_and_deposits->gic_number : 0,
                            'gic_start_date' => $current_rates_and_deposits ? $current_rates_and_deposits->start_date : 0,
                            'is_test' => $this->organization->is_test,
                        ]);
                    }
                }
            }
        }
    }
}

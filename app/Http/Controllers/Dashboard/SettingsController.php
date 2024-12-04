<?php

namespace App\Http\Controllers\Dashboard;

use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\OrganizationDemoGraphicData;
use App\Models\DepositCreditRating;
use App\Models\FITypes;
use App\Models\NAICS;
use App\Models\Organization;
use App\Models\PotentialYearlyDeposits;
use App\Models\Preference;
use App\Models\UserPreference;
use App\Models\UsersDemoGraphicData;
use App\Models\WholeSaleDepositsPortfolio;
use App\Models\Industry;
use App\Models\Product;
use App\User;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Services\SettingsService;


class SettingsController extends Controller
{
    protected $settingsservice;
    public function __construct(SettingsService $settingsService)
    {
        $this->settingsservice = $settingsService;
        $this->middleware(['auth']);
    }

    private function updateVisibility(Request $request, $organization)
    {
        Organization::where('id', $organization->id)->update([
            'visible_for_provinces' => $request->visible_for_provinces,
            'visible_for_customers' => $request->visible_for_customers,
            'visible_for_naics_codes' => $request->visible_for_naics_codes
        ]);

        $message = "Visibility update successful";

        systemActivities(Auth::id(), json_encode($request->query()), $message);
        $response = array("success" => true, "message" => $message, "data" => []);
        return response()->json($response, 200);
    }

    public function accountSetting(Request $request)
    {
        $user = \auth()->user();
        if ($user->is_super_admin) {
            if (!$user->userCan('admin/gic-investors/edit') && !$user->userCan('admin/banks/edit') && !$user->userCan('admin/non-partnered-fi/edit')) {
                return view('dashboard.403');
            }
        } else {
            if (!$user->userCan('universal/organization-setting/page-access')) {
                return view('dashboard.403');
            }
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Account settings page");
        $user = auth()->user();
        $data = [];
        if ($user->is_super_admin && $request->filled("update_for")) {
            $data['update_for'] = $request->update_for;
            $data['organization_id'] = CustomEncoder::urlValueDecrypt($request->organization_id);
            $organization = Organization::with(['document', 'WholeSaleDepositsPortfolio', 'NAICSCode', 'PotentialYearlyDeposit', 'demographicData', 'depositCreditRating'])
                ->find($data['organization_id']);
            if (!$organization) {
                alert()->error("Organization not found");
                return redirect()->back();
            }
        } else {
            // user cannot updated for anyone
            unset($request->update_for);
            unset($request->organization_id);
            $organization = Organization::with(['document', 'WholeSaleDepositsPortfolio', 'NAICSCode', 'PotentialYearlyDeposit', 'demographicData', 'depositCreditRating'])
                ->find($user->organization->id);
            if (!$organization) {
                alert()->error("Organization not found");
                return redirect()->back();
            }
        }

        $naics = NAICS::all();
        $potential_yearly_deposits = PotentialYearlyDeposits::all();
        $fi_types = json_encode(FITypes::all());
        $wholesale_deposits_portfolio = WholeSaleDepositsPortfolio::all();
        $provinces = json_encode(provinces());
        $credit_rating_types = \App\Models\CreditRatingType::where("status","ACTIVE")->orderBy('id', 'ASC')->get();
        $deposit_insurances = \App\Models\DepositInsuranceType::where('description', '!=', 'Any Provincial Insurance')->get()->partition(function ($item) {
            return strpos($item->description, 'Any') !== false; // This is move Any deposit insurance at the top
        })->flatten();

        $permittedSubmitButton = false;
        if ($user->is_super_admin) {
            if ($user->userCan('admin/gic-investors/edit') || $user->userCan('admin/banks/edit') || $user->userCan('admin/non-partnered-fi/edit')) {
                $permittedSubmitButton = true;
            }
        } else {
            $permittedSubmitButton = $user->userCan('universal/organization-setting/save-button');
        }

        $organizations_list = Organization::with(['NAICSCode', 'demographicData'])
            ->where('id', '!=', $organization->id)->get();
        return view($user->user_type == "Admin" ? "dashboard.admin.account-setting" : 'dashboard.account-setting', compact('user', 'data', 'organization', 'naics', 'potential_yearly_deposits', 'fi_types', 'wholesale_deposits_portfolio', 'provinces', 'credit_rating_types', 'deposit_insurances', 'permittedSubmitButton', 'organizations_list'));
    }

    public function profileSetting(Request $request)
    {
        systemActivities(Auth::id(), json_encode($request->query()), "Profile settings page");
        $user = auth()->user();
        $data = [];
        if ($user->is_super_admin && $request->filled("update_for")) {
            $data['update_for'] = $request->update_for;
            $data['user_id'] = CustomEncoder::urlValueDecrypt($request->user_id);
            $user = User::find($data['user_id']);
            if (!$user) {
                alert()->error("User not found");
                return redirect()->back();
            }
        } else {
            // user cannot updated for anyone
            unset($request->update_for);
            unset($request->user_id);
        }

        $provinces = json_encode(provinces());
        $timezones_lists = timezonesList();
        $key_value_timezone_list = [];
        foreach ($timezones_lists as $key => $timezones_list) {
            array_push($key_value_timezone_list, [
                'value' => $key,
                'label' => $timezones_list,
            ]);
        }
        $user->current_timezone = [
            'value' => $user->timezone,
            'label' => !empty(timezonesList()[$user->timezone]) ? timezonesList()[$user->timezone] : "",
        ];
        $timezones = json_encode($key_value_timezone_list);
        return view( /*$user->is_super_admin ? "dashboard.admin.account-setting" :*/'dashboard.profile-settings', compact('user', 'data', 'provinces', 'timezones'));
    }

    public function updateAccountSetting(Request $request)
    {
        $user = \auth()->user();
        if ($user->is_super_admin) {
            if (!$user->userCan('admin/gic-investors/edit') && !$user->userCan('admin/banks/edit') && !$user->userCan('admin/non-partnered-fi/edit')) {
                return view('dashboard.403');
            }
        } else {
            if (!$user->userCan('universal/organization-setting/save-button')) {
                $response = array("success" => false, "message" => 'Access Denied', "data" => []);
                return response()->json($response, 403);
            }
        }

        $user = \auth()->user();
        $validation_rules = [
            'address' => 'required|max:150',
            'city' => 'required|max:50',
            'province' => 'required',
            'postal' => 'required|max:10',
            'telephone' => 'required',
            'description' => 'nullable',
            'credit_rating' => 'required_if:userType,==,BANK', //|integer|min:0
            'deposit_insurance' => 'required_if:userType,==,BANK', //|integer|min:0
            'digital_account_opening' => 'required_if:userType,==,BANK',
        ];

        if ($user->is_super_admin && $request->userType == "Depositor") {
            $validation_rules = [
                'organization_name' => 'required|max:50',
            ];
        }

        if ($request->userType == "Depositor") {
            $validation_rules = [
                'potential_yearly_deposit' => 'required',
                'naics_code' => 'required',
            ];
        }

        if ($request->userType == "Bank") {
            $validation_rules = [
                'fi_type' => 'required',
                'wholesale_deposit_portfolio' => 'required',
            ];
        }

        if ($user->is_super_admin) {
            $validation_rules['organization_id'] = 'required|integer';
        } else {
            unset($request->update_for); // user cannot update for anyone
            unset($request->organization_id);
        }

        if ($request->has('update_visibility')) {
            $validation_rules = [
                //                'visible_for_provinces',
                //                'visible_for_customers',
                //                'visible_for_naics_codes'
            ];
        }

        $validator = Validator::make($request->all(), $validation_rules);

        if ($validator->fails()) {
            systemActivities(Auth::id(), json_encode($request->query()), "Organization setting update failed");
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $user_id = $user->id;
        $organization = $user->organization;
        if ($user->is_super_admin) {
            $organization = Organization::find($request->organization_id);
            $demographic_data = OrganizationDemoGraphicData::where(['organization_id' => $organization->id])->first();
            $user_id = $organization->admin_user_id;
            //            $user = User::find($user_id);
        } else {
            $demographic_data = OrganizationDemoGraphicData::where(['organization_id' => $organization->id])->first();
        }

        if ($request->has('update_visibility')) {
            return $this->updateVisibility($request, $organization);
        }

        if ($demographic_data) {
            $demographic_data->update([
                'address1' => $request->address,
                'address2' => $request->filled("address2") ? $request->address2 : "",
                'city' => $request->city,
                'province' => $request->province,
                'website' => $request->website,
                'postal_code' => $request->postal,
                'description' => $request->description,
                'org_bio' => $request->description,
                'telephone' => $request->telephone,
                'updated_at' => getUTCDateNow(),
            ]);
        } else {
            OrganizationDemoGraphicData::create([
                'address1' => $request->address,
                'address2' => $request->filled("address2") ? $request->address2 : "",
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal,
                'website' => $request->website,
                'telephone' => $request->telephone,
                'updated_at' => getUTCDateNow(),
                'organization_id' => $organization->id,
            ]);
        }

        if ($request->userType == "BANK") {
            $deposit_rating = DepositCreditRating::where(['organization_id' => $organization->id])->first();
            if ($deposit_rating) {
                $deposit_rating->update([
                    'credit_rating_type_id' => $request->credit_rating,
                    'deposit_insurance_id' => $request->deposit_insurance,
                ]);
            } else {
                DepositCreditRating::create([
                    'credit_rating_type_id' => $request->credit_rating,
                    'deposit_insurance_id' => $request->deposit_insurance,
                    'organization_id' => $organization->id,
                ]);
            }
        }

        //Update Organization Details
        if ($organization) {
            if ($request->userType == "BANK") {
                $organization->fi_type_id = $request->institution_type;
                $organization->wholesale_deposit_portfolio_id = $request->wholesale_deposit_portfolio;
                $organization->digital_account_opening = $request->digital_account_opening;
            }
            if ($request->userType == "DEPOSITOR") {
                $organization->potential_yearly_deposit_id = $request->potential_yearly_deposit;
                $organization->naics_code_id = $request->naics_code;
            }

            $organization->needs_update = NULL;
            $organization->save();
        }

        //        if( !$request->filled('profile_url') ){
        //            if ($organization) {
        //                $organization->logo = NULL;
        //                $organization->save();
        //                archiveTable($organization->id, "organizations", $user_id, "Removed Profile Picture");
        //            }
        //        }

        //        $loggedin_user = \auth()->user();
        //        if($loggedin_user->is_super_admin && $request->userType =="DEPOSITOR"){
        //            if ($organization) {
        //                $organization->name =$request->organization_name;
        //                $organization->save();
        //                archiveTable($organization->id, "organizations", $user_id, "Edited Organization Name");
        //
        //            }
        //        }

        $profile_update = false;
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $destinationPath = public_path() . '/image/';
            $file_name = time() . sanitize_file_name($organization->name) . '.png';
            if ($file->move($destinationPath, $file_name)) {
                $profile_update = true;
                if ($organization) {
                    archiveTable($organization->id, "organizations", $user_id, "Updating Profile Picture");
                    $organization->logo = $file_name;
                    $organization->save();
                }
            }
        }

        $message = "Your organization settings have been updated.";
        if ($request->filled('profile_image') && !$profile_update) {
            $message .= "Failed to update the profile picture.";
        }

        systemActivities(Auth::id(), json_encode($request->query()), $message);
        $response = array("success" => true, "message" => $message, "data" => []);
        return response()->json($response, 200);
    }

    public function updateProfileSetting(Request $request)
    {
        $user = \auth()->user();
        $validation_rules = [
            'timezone' => 'required',
            'firstname' => 'required|max:25',
            'lastname' => 'required|max:25',
            'job_title' => 'required|max:50',
            'department' => 'nullable|max:50',
            'phone' => 'required',
            'city' => 'nullable|max:50',
            'email' => 'required|email|max:50',
        ];

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            systemActivities(Auth::id(), json_encode($request->query()), "Profile setting update failed");
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $user_id = $user->id;
        $message = "Your Profile settings have been updated.";
        archiveTable($user_id, "users", $user_id, "Updating Profile Information");

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->name = $request->firstname . ' ' . $request->lastname;
        $user->save();

        $demographic_data = UsersDemoGraphicData::where('user_id', $user->id);
        if (!$user->is_super_admin && $user->organization) {
            $organization = $user->organization;
            $demographic_data = $demographic_data->where('organization_id', $organization->id);
        }
        $demographic_data = $demographic_data->first();
        if ($demographic_data) {
            $demographic_data->update([
                'timezone' => $request->timezone,
                'department' => $request->filled("department") ? $request->department : "",
                'province' => $request->location,
                'city' => $request->city,
                'job_title' => $request->job_title,
                'phone' => $request->phone,
                'updated_at' => getUTCDateNow(),
            ]);
        } else {
            $organization = $user->organization;
            UsersDemoGraphicData::create([
                'timezone' => $request->timezone,
                'department' => $request->filled("department") ? $request->department : "",
                'province' => $request->location,
                'city' => $request->city,
                'job_title' => $request->job_title,
                'phone' => $request->phone,
                'created_at' => getUTCDateNow(),
                'user_id' => $user->id,
                'organization_id' => !$user->is_super_admin && $organization ? $organization->id : null,
            ]);
        }

        systemActivities(Auth::id(), json_encode($request->query()), $message);
        $response = array("success" => true, "message" => $message, "data" => []);
        return response()->json($response, 200);
    }

    public function updateTimezone(Request $request)
    {
        //        $user=\auth()->user();
        //        if(!$user/*->userCan('Update-Account-Settings')*/){
        //            $response = array("success" => false, "message" => 'User Not found', "data" => []);
        //            return response()->json($response, 404);
        //        }
        $validator = Validator::make($request->all(), [
            'timezone' => 'required',
        ]);

        if ($validator->fails()) {
            systemActivities(Auth::id(), json_encode($request->query()), "Update timezone failed");
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $user = auth()->user();
        if (!$user) {
            systemActivities(Auth::id(), json_encode($request->query()), "Update timezone failed");
            $response = array("success" => false, "message" => "Unable to update the timezone", "data" => []);
            return response()->json($response, 400);
        }

        //        $user_demographic = $user->demographicData;
        $demographic_data = UsersDemoGraphicData::where('user_id', $user->id);
        if (!$user->is_super_admin && $user->organization) {
            $organization = $user->organization;
            $demographic_data = $demographic_data->where('organization_id', $organization->id);
        }
        $user_demographic = $demographic_data->first();
        if ($user_demographic) {
            $user_demographic->update(['timezone' => $request->timezone]);
        } else {
            $organization = $user->organization;
            UsersDemoGraphicData::create([
                'user_id' => $user->id,
                'location' => '',
                'job_title' => "",
                'department' => '',
                'phone' => '',
                'timezone' => $request->timezone,
                'created_at' => getUTCDateNow(),
                'organization_id' => $user->is_super_admin ? null : $organization->id,
            ]);
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Updated timezone successfully");
        $response = array("success" => true, "message" => "Your selected timezone has been updated.", "data" => [$user]);
        return response()->json($response, 200);
    }

    public function updatePreference(Request $request)
    {
        $pref = Preference::all()->pluck('name')->toArray();
        $validator = Validator::make($request->all(), [
            'preference' => 'required|in:' . implode(",", $pref),
            'preference_value' => 'required',
        ]);

        if ($validator->fails()) {
            systemActivities(Auth::id(), json_encode($request->query()), "Update notification setting failed");
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $user = auth()->user();
        if (!$user) {
            systemActivities(Auth::id(), json_encode($request->query()), "Update notification setting failed");
            $response = array("success" => false, "message" => "Unable to update notification setting", "data" => []);
            return response()->json($response, 400);
        }

        $preference = Preference::where('name', $request->preference)->first();
        $value = intval($request->preference_value);
        $preference = UserPreference::where([
            'preference_id' => $preference->id,
            'user_id' => $user->id,
        ])->update([
            'value' => $value == 1 ? 0 : 1,
        ]);

        systemActivities(Auth::id(), json_encode($request->query()), "Updated timezone successfully");
        $response = array("success" => true, "message" => ($value == 1 ? "Enabled" : "Disabled") . " notification setting successfully", "data" => $preference);
        return response()->json($response, 200);
    }
    public function getAllProvinces()
    {
        $provinces = Province::all();
        return response()->json($provinces);
    }

    public function getAllIndustries()
    {
        $industries = Industry::all();
        return response()->json($industries);
    }

    public function updateProfileInfo(Request $request)
    {
        $user = \auth()->user();

        if ($user->is_super_admin) {
            if (!$user->userCan('admin/gic-investors/edit') && !$user->userCan('admin/banks/edit') && !$user->userCan('admin/non-partnered-fi/edit')) {
                return view('dashboard.403');
            }
        } else {
            if (!$user->userCan('universal/organization-setting/save-button')) {
                $response = array("success" => false, "message" => 'Access Denied', "data" => []);
                return response()->json($response, 403);
            }
        }

        if ($request->type == "gen") {
            return $this->settingsservice->updateOrganizationGeneralInfo($request);
        } else if ($request->type == "addtional") {
            return $this->settingsservice->updateAdditionalInfo($request);
        } else if ($request->type == "contact") {
            return $this->settingsservice->updateOrganizationContactInfo($request);
        } else if ($request->type == "bio") {
            return $this->settingsservice->updateOrganizationBioInfo($request);
        } else if ($request->type == "transfer") {
            return $this->settingsservice->updateOrganizationTransferDetails($request);
        }
    }
    public function deleteFIFile(Request $request)
    {
        return $this->settingsservice->deleteFIFile($request);
    }
    public function loadProvinceCity(Request $request)
    {

        $province = Province::where("province_name", $request->province)->first();
        $cities = [];
        if ($province) {
            $cities = City::where("province_id", $province->id)->get();
        }
        return response()->json($cities);
    }

    public function DepositorsFICount()
    {
        return DB::table('organizations')
            ->select('type', DB::raw('COUNT(*) as count'))
            ->where('status', 'ACTIVE')
            ->groupBy('type')
            ->get();
    }
    public function getOrganization($type = "BANK")
    {
        $user = \auth()->user();
        $organizations = DB::table('organizations')
            ->select('name')
            ->where(['status' => 'ACTIVE', 'is_test' => $user->organization->is_test, 'type' => $type])
            ->get();
        return response($organizations);
    }
    public function getProducts()
    {
        $products = Product::all()->toArray();
        return $products;
    }
}

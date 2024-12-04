<?php

namespace App\Services;

use App\Constants;
use App\Mail\JoinWaitList;
use App\Models\KeepMeInformed;
use App\Models\Organization;
use App\Models\OrganizationDemoGraphicData;
use App\Models\OrganizationDocument;
use App\Models\OrganizationEntity;
use App\Models\OrganizationKeyIndividual;
use Illuminate\Support\Facades\Validator;
use App\Models\OtherAddress;
use App\Models\UserOrganization;
use App\Models\UsersDemoGraphicData;
use App\Role;
use App\User;
use Cake\Database\Query\InsertQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use App\CustomEncoder;
use App\Mail\AdminMails;
use App\Models\OrgPermissionsList;
use App\Models\OrgRequestAccess;
use App\Models\UserPassword;
use App\Services\Auth\UserAccountManagerService;

class NewSignUpService
{

    public function FirstStepSignUp(Request $request)
    {

        $email = trim(strtolower($request->email));
        $user = User::select([
            'users.*'
        ])->whereIn('account_status', systemActiveUsersStatuses())
            ->where('email', $email)
            ->first();

        if ($user) {
            loginActivities("User sign up attempt failed, User with that email already exists", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'User with that email already exists', "data" => [], "success" => false], 409);
        }

        try {
            DB::beginTransaction();

            if (!$user) {
                $created_user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'firstname' => $request->first_name,
                    'lastname' => $request->last_name,
                    'email' => $request->email,
                    'account_opening_date' => getUTCDateNow(),
                    'account_status' => 'PENDING',
                    'is_non_partnered_fi' => false,
                    'failed_login_attempts' => 0,
                    'requires_password_update' => false
                ]);
                UsersDemoGraphicData::create([
                    'user_id' => $created_user->id,
                    'phone' => $request->phone_number,
                    'created_at' => getUTCDateNow()
                ]);
            }
            Auth::login(User::find($created_user->id));
            archiveTable($created_user->id, 'users', $created_user->id, "last_login");
            $created_user->last_login = getUTCDateNow();
            $created_user->save();

            DB::commit();
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            Log::emergency($error->getMessage());
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
        }
        loginActivities("Registration successful", $request->server('HTTP_USER_AGENT'), $created_user->id);
        return response()->json(["message" => 'Thank you for registering for an account with Yield Exchange. Please proceed with other steps', "data" => $created_user, "success" => true], 201);
    }

    public function verifyConferenceSignupAfterApproval($userid)
    {
        $userid = CustomEncoder::urlValueDecrypt($userid);
        $user = User::find($userid);

        if (UserPassword::where('user_id', $userid)->exists()) {
            return redirect('login');
        } else {
            Auth::login($user);
            return redirect()->route('proceed-to-reg', ['isconfrence' => 'isconfrence']);
        }
        // return $user;
    }
    public function KeepMeInformed(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(["message" => 'No User found with that email ', "data" => [], "success" => false], 404);
        }

        try {

            DB::beginTransaction();
            $user['is_waiting'] = 'KEEP_ME_INFORMED';
            $user['account_status'] = 'PENDING';
            $user['modified_date'] = getUTCDateNow();
            $user['modified_section'] = 'waiting list ';
            $user['modified_by'] = $user->id;
            $user['name'] = $request->first_name . ' ' . $request->last_name;
            $user['firstname'] = $request->first_name;
            $user['lastname'] = $request->last_name;
            $user->save();

            KeepMeInformed::create([
                'user_id' => $user->id,
                'created_at' => getUTCDateNow(),
                'platform' => $request->platform,
                'country' => $request->country,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'specify_platform' => $request->specify_platform,
                'email' => $request->email,
            ]);

            UsersDemoGraphicData::create([
                'user_id' => $user->id,
                'phone' => $request->phone_number,
                'created_at' => getUTCDateNow(),
                'platform' => $request->platform,
                'country' => $request->country,
            ]);
            DB::commit();
            try {
                Mail::to($user->email)->send(new JoinWaitList([], 'KEEP_ME_INFORMED'));
            } catch (\Throwable $th) {
                loginActivities("User waiting email failled", $request->server('HTTP_USER_AGENT'), 0);
            }
            Auth::logout();
            loginActivities("Registration successful", $request->server('HTTP_USER_AGENT'), $user->id);
            return response()->json(["message" => 'Registration Complete! You will receive emails to keep you in the loop', "data" => $user, "success" => true], 201);
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
        }
    }
    public function DPersonalOrganization(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            try {

                DB::beginTransaction();
                $user['is_waiting'] = 'PERSONAL_DEPOSITOR';
                $user['account_status'] = 'PENDING';
                $user['modified_date'] = getUTCDateNow();
                $user['modified_section'] = 'waiting list ';
                $user['modified_by'] = $user->id;
                $user->save();

                if (!$user->organization) {

                    $created_organization = Organization::create([
                        'users_limit' => 5,
                        'name' => $user->name,
                        'type' => 'DEPOSITOR',
                        'admin_user_id' => $user->id,
                        'is_non_partnered_fi' => 0,
                        'created_by' => $user->id,
                        'is_temporary' => 1,
                        'account_manager' => NULL,
                        'inviter_name' => NULL,
                        'status' => 'PENDING',
                        'potential_yearly_deposit_id' => null,
                        'naics_code_id' => NULL,
                        'requires_to_confirm_users_seats' => false,
                        'accepted_terms_and_conditions' => 0,
                        'sign_up_from' => NULL,
                        'digital_account_opening' =>  NULL,
                        'needs_update' => 'yes',
                        'is_individual' => '1',
                        'is_waiting' => 'PERSONAL_DEPOSITOR'
                    ]);


                    UserOrganization::create([
                        'user_id' => $user->id,
                        'organization_id' => $created_organization->id,
                        'status' => 'PENDING',
                        'is_default' => 1,
                        'switched_organization_type' => NULL
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
                        'user_id' => $user->id,
                        'user_type' => $role->display_name,
                        'organization_id' => $created_organization->id
                    ]);
                }

                DB::commit();
                Auth::logout();
                try {
                    Mail::to($user->email)->send(new JoinWaitList([], 'PERSONAL_DEPOSITOR'));
                } catch (\Throwable $th) {
                    loginActivities("User waiting email failled", $request->server('HTTP_USER_AGENT'), 0);
                }
                loginActivities("User Data saved successfully", $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'User added to waiting list', "data" => $user, "success" => true], 201);
            } catch (\Exception $error) {
                DB::rollback();
                $timestamp = time();
                Log::alert($error->getMessage());
                loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
            }
        }
        return response()->json(["message" => 'No User found with that email ', "data" => [], "success" => true], 404);
    }
    public function DBusinessOrganisation(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(["message" => 'No User found with that email ', "data" => [], "success" => false], 404);
        }
        $logo = null;
        if ($request->filled('logo')) {
            $imageData = $request->input('logo');
            list($type, $imageData) = explode(';', $imageData);
            list(, $imageData) = explode(',', $imageData);
            $imageData = base64_decode($imageData);
            $uniqueId = uniqid();

            $randomText = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

            $fileName = $uniqueId . '_' . $randomText . '.png';

            try {
                file_put_contents(public_path('image/' . $fileName), $imageData);
                $logo = $fileName;
            } catch (\Exception $e) {
                Log::alert($e->getMessage());
            }
        }

        try {
            DB::beginTransaction();

            $created_organization = Organization::create([
                'intended_use' => $request->intended_use ? json_encode($request->intended_use) : '',
                'users_limit' => 5,
                'logo' => $logo,
                'name' => $request->organization_name,
                'type' => $request->institutionType,
                'admin_user_id' => $user->id,
                'is_non_partnered_fi' => 0,
                'created_by' => $user->id,
                'is_temporary' => 1,
                'account_manager' => NULL,
                'inviter_name' => NULL,
                'status' => $request->status,
                'potential_yearly_deposit_id' => null,
                'naics_code_id' => NULL,
                'requires_to_confirm_users_seats' => false,
                'accepted_terms_and_conditions' => 0,
                'sign_up_from' => NULL,
                'digital_account_opening' =>  NULL,
                'needs_update' => 'yes',
                'is_individual' => NULL,
                'industry_id' => $request->industry_id,
                // 'registration_type' => $request->organization_type,
                'trade_name' => $request->trade_name,
                'incoporation_number' => $request->incoporation_number,
                'incoporation_date' => $request->incoporation_date,
                'CRA_business_number' => $request->cra_business_number,
                'province_of_incorporation' => $request->incoporation_province,
                'fi_type_id' => $request->institutionType == "BANK" ? $request->organization_type : null
            ]);

            if ($request->use_different_address) {
                OtherAddress::create([
                    'name' => $request->other_street,
                    'city' => $request->other_city,
                    'province' => $request->other_province,
                    'postal_code' => $request->other_postal_code,
                    'user_id' => $user->id,
                    'organization_id' => $created_organization->id,
                ]);
            }

            OrganizationDemoGraphicData::create([
                'user_id' => $user->id,
                'address1' => $request->street,
                'province' => $request->province,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'address2' => $request->other_street,
                'timezone' => "Central",
                'created_at' => getUTCDateNow(),
                'organization_id' => $created_organization->id,
                "seen_summary" => 'yes',
                "telephone" => $request->telephone,
                "website" => $request->website,
                'description' => $request->description,
            ]);
            UserOrganization::create([
                'user_id' => $user->id,
                'organization_id' => $created_organization->id,
                'status' => 'PENDING',
                'is_default' => 1,
                'switched_organization_type' => NULL
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
                'user_id' => $user->id,
                'user_type' => $role->display_name,
                'organization_id' => $created_organization->id
            ]);

            //// send a request 
            foreach ($request->intended_use as $name) {
                $org_permissions_list = OrgPermissionsList::where('slug', $name)->first();
                if ($org_permissions_list) {
                    $orgRequestAccess = new OrgRequestAccess();
                    $orgRequestAccess->org_permissions_list_id = $org_permissions_list->id;
                    $orgRequestAccess->organization_id = $user->organization->id;
                    $orgRequestAccess->user_id = $user->id;

                    if ($orgRequestAccess->save()) {

                        $orgRequestAccess->load(['organization', 'permissionDetails']);
                        Mail::to(getAdminEmails())->queue(new AdminMails([
                            'subject' => "Feature Access Request!",
                            'access_request' => $orgRequestAccess,
                            'user_type' => "CG"
                        ], 'accessRequesShared'));
                    }
                }
            }

            DB::commit();
            loginActivities("User Data saved successfully", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Business organization created', "data" => $user, "success" => true], 201);
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
        }
    }
    public function UpdateDBusinessOrganisation(Request $request)
    {
        $user = User::find($request->user_id);

        $organization_id = UserOrganization::where('user_id', $request->user_id)->value('organization_id');
        $current_organization = Organization::find($organization_id);

        if (!$user) {
            return response()->json(["message" => 'No User found with that email ', "data" => [], "success" => false], 404);
        }
        $logo = $current_organization->logo;
        if ($request->filled('logo')) {
            $imageData = $request->input('logo');
            list($type, $imageData) = explode(';', $imageData);
            list(, $imageData) = explode(',', $imageData);
            $imageData = base64_decode($imageData);
            $uniqueId = uniqid();

            $randomText = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

            $fileName = $uniqueId . '_' . $randomText . '.png';

            try {
                file_put_contents(public_path('image/' . $fileName), $imageData);
                $logo = $fileName;
            } catch (\Exception $e) {
                Log::alert($e->getMessage());
            }
        }

        try {
            DB::beginTransaction();
            Organization::where('id', $organization_id)
                ->update([
                    'intended_use' => $request->intended_use ? json_encode($request->intended_use) : "",
                    'users_limit' => 5,
                    'logo' => $logo,
                    'name' => $request->organization_name,
                    'type' => $request->institutionType,
                    'admin_user_id' => $user->id,
                    'is_non_partnered_fi' => 0,
                    'created_by' => $user->id,
                    'is_temporary' => 1,
                    'account_manager' => NULL,
                    'inviter_name' => NULL,
                    'status' => $request->status,
                    'potential_yearly_deposit_id' => null,
                    'naics_code_id' => NULL,
                    'requires_to_confirm_users_seats' => false,
                    'accepted_terms_and_conditions' => 0,
                    'sign_up_from' => NULL,
                    'digital_account_opening' =>  NULL,
                    'needs_update' => 'yes',
                    'is_individual' => NULL,
                    // 'registration_type' => $request->organization_type,
                    'trade_name' => $request->trade_name,
                    'industry_id' => $request->industry_id,
                    'incoporation_number' => $request->incoporation_number,
                    'incoporation_date' => $request->incoporation_date,
                    'CRA_business_number' => $request->cra_business_number,
                    'province_of_incorporation' => $request->incoporation_province,
                    'fi_type_id' => $request->institutionType == "BANK" ? $request->organization_type : null
                ]);

            if ($request->use_different_address) {
                OtherAddress::where([['organization_id', $organization_id], ['user_id', $request->user_id]])->update([
                    'name' => $request->other_street,
                    'city' => $request->other_city,
                    'province' => $request->other_province,
                    'postal_code' => $request->other_postal_code,
                    'user_id' => $user->id,
                    'organization_id' => $organization_id,
                ]);
            }

            OrganizationDemoGraphicData::where([['organization_id', $organization_id], ['user_id', $request->user_id]])->update([
                'user_id' => $user->id,
                'address1' => $request->street,
                'province' => $request->province,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'address2' => $request->other_street,
                'timezone' => "Central",
                'updated_at' => getUTCDateNow(),
                'created_at' => getUTCDateNow(),
                'organization_id' => $organization_id,
                "seen_summary" => 'yes',
                // "telephone" => $request->telephone,
                "website" => $request->website,
                'description' => $request->description,
            ]);


            DB::commit();
            loginActivities("User Data saved successfully", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Business organization created', "data" => $user, "success" => true], 201);
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
        }
    }

    public function UpdateUserInfo(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(["message" => 'No User found with that email ', "data" => [], "success" => false], 404);
        }
        try {
            DB::beginTransaction();

            $user['modified_date'] = getUTCDateNow();
            $user['modified_by'] = $user->id;
            $user['firstname'] = $request->first_name;
            $user['lastname'] = $request->last_name;
            $user['name'] = $request->first_name . ' ' . $request->last_name;
            $user['modified_section'] = 'updated info ';

            $user_organization = $user->organization;
            $user_demographic_data = UsersDemoGraphicData::where('user_id', $user->id)->first();
            if ($user_demographic_data) {
                $user_demographic_data->update(
                    [
                        'phone' => $request->telephone,
                        'job_title' => $request->job_title,
                        'timezone' => $request->timezone,
                        'linkedin' => $request->linkedin,
                    ]
                );
            } else {
                UsersDemoGraphicData::create([
                    'phone' => $request->telephone,
                    'job_title' => $request->job_title,
                    'timezone' => $request->timezone,
                    'linkedin' => $request->linkedin,
                    'user_id' => $user->id,
                    'organization_id' => $user_organization->id,
                ]);
            }
            if ($request->join_waiting) {
                $user['is_waiting'] = 'NOT_IN_TIMEZONE';
                $user['account_status'] = 'PENDING';
                $user['modified_section'] = 'waiting list ';

                $user_organization['is_waiting'] = 'NOT_IN_TIMEZONE';
                $user_organization->save();
                Auth::logout();
                try {
                    Mail::to($user->email)->send(new JoinWaitList([], 'NOT_IN_TIMEZONE'));
                } catch (\Throwable $th) {
                    loginActivities("User waiting email failled", $request->server('HTTP_USER_AGENT'), 0);
                }
            }
            $user->save();
            DB::commit();
            loginActivities("User Data saved successfully", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'User info updated successfully', "data" => $user, "success" => true], 201);
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            Log::alert($error->getMessage());
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
        }
    }

    public function registerToHubSpot($data)
    {

        $api_url = 'https://api.hubapi.com/crm/v3/objects/contacts';

        $properties = array(
            "email" => $data['email'],
            "firstname" => $data['first_name'],
            "lastname" => $data['last_name'],
            "phone" => $data['telephone'],
            "company" => $data['institution_name'],
        );

        if (isset($data['website'])) {
            $properties['website'] = $data['website'];
        }

        $data = array(
            'properties' => $properties
        );

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' ,
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
    public function addOrUpdateEntity(Request $request)
    {
        $entities = $request->entities;
        try {
            DB::beginTransaction();
            foreach ($entities as $entity) {
                $entity = json_decode(json_encode($entity));
                // return $entity->organizationname;
                if ($entity->id != null) {
                    $entity_being_edited = OrganizationEntity::find($entity->id);

                    DB::table('organization_entity_archives')->insertOrIgnore(
                        [
                            'organization_id' => $entity_being_edited->organization_id,
                            'organization_entity_id' => $entity_being_edited->id,
                            'modified_by_user_id' => auth()->user()->id,
                            'organization_name' => $entity_being_edited->organization_name,
                            'organization_type' => $entity_being_edited->organization_type,
                            'incorporation_province' => $entity_being_edited->incorporation_province,
                            'owns_over_twenty_five' => $entity_being_edited->owns_over_twenty_five,
                            'percentage_ownership' => $entity_being_edited->percentage_ownership,
                            'cra_business_number' => $entity_being_edited->cra_business_number,
                            'inc_business_number' => $entity_being_edited->inc_business_number,
                            'orperating_for_entity' => $entity_being_edited->orperating_for_entity,
                            'relationship_with_entity' => $entity_being_edited->relationship_with_entity,
                            'modified_section' => "Update entity details",
                        ]
                    );

                    OrganizationEntity::where('id', $entity->id)->update(
                        [
                            'organization_id' => $request->organization_id,
                            'organization_name' => $entity->organizationname,
                            'organization_type' => $entity->incorptype,
                            'incorporation_province' => $entity->provinceofincorp,
                            'owns_over_twenty_five' => $entity->is_owenershipabovetwentyfive,
                            'percentage_ownership' => $entity->percentage_ownwership,
                            'cra_business_number' => $entity->cra_number,
                            'inc_business_number' => $entity->business_number,
                            'orperating_for_entity' => $entity->is_actingforindividual,
                            'relationship_with_entity' => $entity->nature_of_relationship,
                            'modified_section' => "Update entity details",
                        ]
                    );
                } else {
                    OrganizationEntity::create(
                        [
                            'organization_id' => $request->organization_id,
                            'organization_name' => $entity->organizationname,
                            'organization_type' => $entity->incorptype,
                            'incorporation_province' => $entity->provinceofincorp,
                            'owns_over_twenty_five' => $entity->is_owenershipabovetwentyfive,
                            'percentage_ownership' => $entity->percentage_ownwership,
                            'cra_business_number' => $entity->cra_number,
                            'inc_business_number' => $entity->business_number,
                            'orperating_for_entity' => $entity->is_actingforindividual,
                            'relationship_with_entity' => $entity->nature_of_relationship,
                            'modified_section' => "Create entity",
                        ]
                    );
                }
            }


            DB::commit();
            loginActivities("Organization entity added successfully saved successfully", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Organization entity created', "data" => OrganizationEntity::where('organization_id', $request->organization_id)->get(), "success" => true], 201);
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
        }
    }
    public function deleteEntity(Request $request)
    {
        $entity = $request->id;
        try {
            DB::beginTransaction();

            $entity = json_decode(json_encode($entity));

            $entity_being_edited = OrganizationEntity::find($entity);

            DB::table('organization_entity_archives')->insertOrIgnore(
                [
                    'organization_id' => $entity_being_edited->organization_id,
                    'organization_entity_id' => $entity_being_edited->id,
                    'modified_by_user_id' => auth()->user()->id,
                    'organization_name' => $entity_being_edited->organization_name,
                    'organization_type' => $entity_being_edited->organization_type,
                    'incorporation_province' => $entity_being_edited->incorporation_province,
                    'owns_over_twenty_five' => $entity_being_edited->owns_over_twenty_five,
                    'percentage_ownership' => $entity_being_edited->percentage_ownership,
                    'cra_business_number' => $entity_being_edited->cra_business_number,
                    'inc_business_number' => $entity_being_edited->inc_business_number,
                    'orperating_for_entity' => $entity_being_edited->orperating_for_entity,
                    'relationship_with_entity' => $entity_being_edited->relationship_with_entity,
                    'modified_section' => "Deleted entity details",
                ]
            );
            if (!$entity_being_edited->forceDelete()) {
                DB::rollback();
                $timestamp = time();
                loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
            }


            DB::commit();
            loginActivities("Organization entity added successfully saved successfully", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Organization entity created', "data" => OrganizationEntity::where('organization_id', $request->organization_id)->get(), "success" => true], 201);
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
        }
    }
    public function addOrUpdateKeyIndividuals(Request $request)
    {
        $individuals = $request->individuals;
        try {
            DB::beginTransaction();
            foreach ($individuals as $individual) {
                $individual = json_decode(json_encode($individual));
                // return $individual->organizationname;
                if ($individual->id != null) {
                    $individualtoupdate = OrganizationKeyIndividual::find($individual->id);

                    DB::table('organization_key_individual_archive')->insertOrIgnore([

                        'organization_id' => $individualtoupdate->organization_id,
                        'organization_key_individual_id' => $individualtoupdate->id,
                        'user_id' => $individualtoupdate->user_id,
                        'modified_by_user_id' => auth()->user()->id,
                        'first_name' => $individualtoupdate->first_name,
                        'last_name' => $individualtoupdate->last_name,
                        'job_title' => $individualtoupdate->job_title,
                        'is_director' => $individualtoupdate->is_director,
                        'owns_over_twenty_five' => $individualtoupdate->owns_over_twenty_five,
                        'percentage_ownership' => $individualtoupdate->percentage_ownership,
                        'is_signingofficer' => $individualtoupdate->is_signingofficer,
                        'is_politicallyexposed' => $individualtoupdate->is_politicallyexposed,
                        'is_actingonattorneypower' => $individualtoupdate->is_actingonattorneypower,
                        'orperating_for_entity' => $individualtoupdate->orperating_for_entity,
                        'operating_for_corporation' => $individualtoupdate->operating_for_corporation,
                        'relationship_with_corporation' => $individualtoupdate->relationship_with_corporation,
                        'modified_section' => "Update entity details",
                    ]);
                    OrganizationKeyIndividual::where('id', $individual->id)->update(
                        [
                            'organization_id' => $request->organization_id,
                            'user_id' => $individual->user_id,
                            'first_name' => $individual->first_name,
                            'last_name' => $individual->last_name,
                            'job_title' => $individual->job_title,
                            'is_director' => $individual->is_director,
                            'owns_over_twenty_five' => $individual->is_owenershipabovetwentyfive,
                            'percentage_ownership' => $individual->percentage_ownwership,
                            'is_signingofficer' => $individual->is_signingofficer,
                            'is_politicallyexposed' => $individual->is_poliliticallyexposed,
                            'is_actingonattorneypower' => $individual->is_actingonpowerofattorney,
                            'orperating_for_entity' => $individual->is_actingforindividual,
                            'operating_for_corporation' => $individual->is_actingforcorporation,
                            'relationship_with_corporation' => $individual->nature_of_relationship,
                            'modified_section' => "Update entity details",
                        ]
                    );
                } else {
                    OrganizationKeyIndividual::create(
                        [
                            'organization_id' => $request->organization_id,
                            'user_id' => $individual->user_id,
                            'first_name' => $individual->first_name,
                            'last_name' => $individual->last_name,
                            'job_title' => $individual->job_title,
                            'is_director' => $individual->is_director,
                            'owns_over_twenty_five' => $individual->is_owenershipabovetwentyfive,
                            'percentage_ownership' => $individual->percentage_ownwership,
                            'is_signingofficer' => $individual->is_signingofficer,
                            'is_politicallyexposed' => $individual->is_poliliticallyexposed,
                            'is_actingonattorneypower' => $individual->is_actingonpowerofattorney,
                            'orperating_for_entity' => $individual->is_actingforindividual,
                            'operating_for_corporation' => $individual->is_actingforcorporation,
                            'relationship_with_corporation' => $individual->nature_of_relationship,
                            'modified_section' => "Create entity",
                        ]
                    );
                }
            }


            DB::commit();
            loginActivities("Organization individual added successfully saved successfully", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Organization entity created', "data" => OrganizationKeyIndividual::where('organization_id', $request->organization_id)->get(), "success" => true], 201);
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
        }
    }
    public function deleteKeyIndividuals(Request $request)
    {
        $individual = $request->id;
        try {
            DB::beginTransaction();
            // $individual = json_deco$individual));
            // return $individual->organizationname;

            $individualtoupdate = OrganizationKeyIndividual::find($individual);

            DB::table('organization_key_individual_archive')->insertOrIgnore([

                'organization_id' => $individualtoupdate->organization_id,
                'organization_key_individual_id' => $individualtoupdate->id,
                'user_id' => $individualtoupdate->user_id,
                'modified_by_user_id' => auth()->user()->id,
                'first_name' => $individualtoupdate->first_name,
                'last_name' => $individualtoupdate->last_name,
                'job_title' => $individualtoupdate->job_title,
                'is_director' => $individualtoupdate->is_director,
                'owns_over_twenty_five' => $individualtoupdate->owns_over_twenty_five,
                'percentage_ownership' => $individualtoupdate->percentage_ownership,
                'is_signingofficer' => $individualtoupdate->is_signingofficer,
                'is_politicallyexposed' => $individualtoupdate->is_politicallyexposed,
                'is_actingonattorneypower' => $individualtoupdate->is_actingonattorneypower,
                'orperating_for_entity' => $individualtoupdate->orperating_for_entity,
                'operating_for_corporation' => $individualtoupdate->operating_for_corporation,
                'relationship_with_corporation' => $individualtoupdate->relationship_with_corporation,
                'modified_section' => "Deleted Key individual",
            ]);

            if (!$individualtoupdate->forceDelete()) {
                DB::rollback();

                // return response("Not deleted");

                loginActivities("Organization individual removed", $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'Organization individual removed', "data" => OrganizationKeyIndividual::where('organization_id', $request->organization_id)->get(), "success" => true], 201);
            }




            DB::commit();
            loginActivities("Organization individual removed", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Organization individual removed', "data" => OrganizationKeyIndividual::where('organization_id', $request->organization_id)->get(), "success" => true], 201);
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false, 'error' => $error->getMessage() . $error->getTraceAsString()], 409);
        }
    }
    public function createOrganizationDocuments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_id' => 'required',
            'files.*' => 'required|file',
            'types_id.*' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->jsonErrorFailure($response);
        }
        // $organization = Organization::find($request->organization_id);
        // if (!$organization) {
        //     $response = array("success" => false, "message" => "Organization not found", "data" => []);
        //     return response()->jsonErrorFailure($response);
        // }


        $files = $request->file('files');
        $typesIds = $request->input('types_id');
        foreach ($files as $index => $file) {
            $typeId = $typesIds[$index] ?? null;

            if ($typeId !== null) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = 'documents/depositor/';
                $file->move($destinationPath, $fileName);
                $document_exists = OrganizationDocument::where('organization_id', $request->organization_id)->where('type_id', $typeId)->first();

                if (!$document_exists) {
                    OrganizationDocument::create([
                        'organization_id' => $request->organization_id,
                        'type_id' => $typeId,
                        'file_name' => $destinationPath . $fileName
                    ]);
                } else {
                    $document_exists['file_name'] = $destinationPath . $fileName;
                    $document_exists->save();
                }
            }
        }

        $response = array("success" => true, "message" => "Documents Uploaded Successfully", "data" => []);
        return response()->json($response, 201);
    }
}

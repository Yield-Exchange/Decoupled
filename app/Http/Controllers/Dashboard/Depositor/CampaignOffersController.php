<?php

namespace App\Http\Controllers\Dashboard\Depositor;

use App\Constants;
use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Mail\BankMails;
use App\Models\DocumentType;
use App\Models\Industry;
use App\Models\Offer;
use App\Models\Product;
use App\Services\Depositors\CampaignOffersService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Organization;
use App\Models\OrganizationBankDetails;
use App\Models\OrganizationDemoGraphicData;
use App\Models\OrganizationDocument;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UsersDemoGraphicData;
use App\Services\Depositors\DepositorCampaignAnalyticsService;

use  Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\OtherAddress;
use App\Services\NewSignUpService;

class CampaignOffersController extends Controller
{
    private $campaignOffersService, $campaignanalyticsService, $newSignupService;

    public function __construct(DepositorCampaignAnalyticsService $depositorCampaignAnalyticsService)
    {

        $this->middleware('auth');
        $this->middleware('auth.depositor');
        $this->campaignOffersService = new CampaignOffersService();
        $this->campaignanalyticsService = $depositorCampaignAnalyticsService;
        $this->newSignupService = new NewSignUpService;
    }

    public function index(Request $request, $ref_id = null)
    {
        systemActivities(Auth::id(), json_encode($request->query()), "View depositor campaign offer");
        $is_summary = $ref_id;
        // print_r($is_summary);
        $products = Product::all()->toArray();
        $industries = Industry::all()->toArray();
        $fiorganizations = DB::table('organizations')->select('name')->where('type', 'BANK')->get()->toArray();
        // return $fiorganizations;
        // return count($industries);
        $organization_id = auth()->user()->organization->id;
        $unformattedusertimezone=Auth::user()->timezone;
        $formattedtimezone=formattedTimezone($unformattedusertimezone);
        return view('dashboard.depositor.campaigns.index', compact('unformattedusertimezone','formattedtimezone','is_summary', 'products', 'industries', 'fiorganizations', 'organization_id'));
    }

    public function getOffers(Request $request)
    {
        return $this->campaignOffersService->fetch($request);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->jsonErrorFailure($response);
        }
        return $this->campaignOffersService->store($request->all());
    }
    public function registerDepositorCampaignProductView(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "user viewed product  " . $request->campaign_product);
        $record = $this->campaignOffersService->registerDepositorCampaignProductView($request);
        return $record;
    }
    public function registerDepositorCampaignView(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "user viewed campaign  " . $request->campaign);
        $record = $this->campaignOffersService->registerDepositorCampaignView($request);
        return $record;
    }
    public function getTrendData(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "user viewed campaign  analytics");
        $record = $this->campaignanalyticsService->campaignInsights($request);
        return $record;
    }
    public function getPuchasedOffer($offer_id)
    {
        $offer = Offer::whereHas('invited.organization')->find(CustomEncoder::urlValueDecrypt($offer_id));
        if (!$offer) {
            // dd('offer not found');
        }
        $organizationid = $offer->invited->organization->id;
        // echo $organizationid;
        $organizationdetails = OrganizationBankDetails::where('organization_id', $organizationid)->get()->toArray();
        $fi_admin = User::find($offer->user_id);
        return view('dashboard.depositor.campaigns.purchasegic', compact('offer', 'organizationdetails', 'fi_admin'));
    }

    public function getDocumentTypes()
    {
        $document_types = DocumentType::all();
        $response = array("success" => true, "message" => "Document types fetched successfuly", "data" => $document_types);
        return response()->json($response, 200);
    }

    public function getOrganizationData($organization_id)
    {

        $organization = Organization::with(['document', 'entities', 'entities', 'keyIndividuals'])->find($organization_id);
        $organization['intended_use'] = json_decode($organization->intended_use);
        return response()->json(array("data" => $organization, 'message' => 'Organization data', 'success' => true), 200);
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
        $organization = Organization::find($request->organization_id);
        if (!$organization) {
            $response = array("success" => false, "message" => "Organization not found", "data" => []);
            return response()->jsonErrorFailure($response);
        }


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

    public function editDocument(Request $request, $document_id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
            'type_id' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->jsonErrorFailure($response);
        }
        $document = OrganizationDocument::find($document_id);
        if ($document) {
            if ($request->hasFile('file')) {
                $file = $request->file;
                $fileName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = 'documents/depositor/';
                $file->move($destinationPath, $fileName);
                $document->update([
                    'file_name' => $destinationPath . '/' . $fileName,
                    'type_id' => $request->type_id
                ]);
            }
        } else {
            return response()->json(["message" => 'Document Not found', "data" => [], "success" => true], 404);
        }
        loginActivities("Document Updated successfully", $request->server('HTTP_USER_AGENT'), 0);
        return response()->json(["message" => 'Document updated successfully', "data" => $document, "success" => true], 201);
    }
    public function updateUserDetails(Request $request)
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
                        'linkedin' => $request->linkedin ? $request->linkedin : NULL,
                    ]
                );
            } else {
                UsersDemoGraphicData::create([
                    'phone' => $request->telephone,
                    'job_title' => $request->job_title,
                    'timezone' => $request->timezone,
                    'linkedin' => $request->linkedin ? $request->linkedin : NULL,
                    'user_id' => $user->id,
                    'organization_id' => $user_organization->id,
                ]);
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
            return response()->json(["message" => 'Failed, Update user data, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 409);
        }
    }
    public function updateOrganizationData(Request $request, $organization_id)
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(["message" => 'No User found with that email ', "data" => [], "success" => false], 404);
        }
        $organization = Organization::find($organization_id);
        if (!$organization) {
            return response()->json(["message" => 'Organization Not Found ', "data" => [], "success" => false], 404);
        }
        $logo = $organization->logo;
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
            $organization->update([
                'intended_use' => $request->intended_use ? json_encode($request->intended_use) : NULL,
                'users_limit' => 5,
                'logo' => $logo,
                'name' => $request->organization_name,
                'type' => 'DEPOSITOR',
                'admin_user_id' => $user->id,
                'is_non_partnered_fi' => 0,
                'created_by' => $user->id,
                'is_temporary' => 1,
                'account_manager' => NULL,
                'inviter_name' => NULL,
                'status' => 'ACTIVE',
                'potential_yearly_deposit_id' => null,
                'naics_code_id' => NULL,
                'requires_to_confirm_users_seats' => false,
                'accepted_terms_and_conditions' => 0,
                'sign_up_from' => NULL,
                'digital_account_opening' =>  NULL,
                'needs_update' => 'no',
                'is_individual' => NULL,
                'registration_type' => $request->organization_type,
                'trade_name' => $request->trade_name,
                'incoporation_number' => $request->incoporation_number,
                'incoporation_date' => $request->incoporation_date,
                'CRA_business_number' => $request->cra_business_number,
                'province_of_incorporation' => $request->incoporation_province,
                'industry_id' => $request->industry_id
            ]);

            $other_address = OtherAddress::where('organization_id', $organization_id)->first();

            if (!$other_address) {
                // if ($request->use_different_address) {
                OtherAddress::create([
                    'name' => $request->other_street,
                    'city' => $request->other_city,
                    'province' => $request->other_province,
                    'postal_code' => $request->other_postal_code,
                    'user_id' => $user->id,
                    'organization_id' => $organization->id,
                ]);
                // }
            } else {
                // if ($request->use_different_address) {
                $other_address->update([
                    'name' => $request->other_street,
                    'city' => $request->other_city,
                    'province' => $request->other_province,
                    'postal_code' => $request->other_postal_code,
                ]);
                // }
            }

            $demog = OrganizationDemoGraphicData::where('organization_id', $organization_id)->first();
            if ($demog) {
                $demog->update([
                    'address1' => $request->street,
                    'province' => $request->province,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'address2' => $request->other_street ?? "",
                    'timezone' => "Central",
                    'created_at' => getUTCDateNow(),
                    "seen_summary" => 'yes',
                    "telephone" => $request->telephone,
                    "website" => $request->website,
                    'description' => $request->description,
                ]);
            } else {
                OrganizationDemoGraphicData::create([
                    'user_id' => $user->id,
                    'address1' => $request->street,
                    'province' => $request->province,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'address2' => $request->other_street ?? "",
                    'timezone' => "Central",
                    'created_at' => getUTCDateNow(),
                    'organization_id' => $organization->id,
                    "seen_summary" => 'yes',
                    "telephone" => $request->telephone,
                    "website" => $request->website,
                    'description' => $request->description,
                ]);
            }

            DB::commit();
            loginActivities("User Data saved successfully", $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Business organization Updated', "data" => $organization, "success" => true], 200);
        } catch (\Exception $error) {
            DB::rollback();
            $timestamp = time();
            loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
            return response()->json(["message" => 'Failed, unable to complete registration, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 409);
        }
    }
    public function shareDepositorDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fi_id' => 'required',
            'depositor_id' => 'required'
        ]);


        if ($request->filled('mypdf')) {
            $pdfData = $request->input('mypdf');
            list($type, $pdfData) = explode(';', $pdfData);
            list(, $pdfData) = explode(',', $pdfData);
            $pdfData = base64_decode($pdfData);
            $uniqueId = uniqid();

            $randomText = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

            $fileName = $uniqueId . '_' . $randomText . '.pdf';

            try {
                file_put_contents(public_path('documents/depositor/' . $fileName), $pdfData);
                $mypdf = "documents/depositor/" . $fileName;


                OrganizationDocument::create([
                    'organization_id' => $request->depositor_id,
                    'type_id' => 7,
                    'file_name' => $mypdf,
                ]);
            } catch (\Exception $e) {
                Log::alert($e->getMessage());
            }
        }

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->jsonErrorFailure($response);
        }
        $user_notify = UserNotification::create([
            "type" => 'SHARE DOCUMENTS',
            "details" => 'This is to notify you that a depositor has shared some documents with you',
            "date_sent" => getUTCDateNow(),
            "status" => "ACTIVE",
            "sent_by_organization_id" => $request->depositor_id,
            "sent_to_organization_id" => $request->fi_id
        ]);

        $depositor = Organization::find($request->depositor_id);
        $bank = Organization::find($request->fi_id);

        // $documents = OrganizationDocument::where('organization_id', $request->depositor_id)
        //     ->groupBy('type_id')
        //     ->latest('id')
        //     ->orderBy('id','desc')
        //     ->get();

        $documents = OrganizationDocument::whereIn('id', function ($query) use ($request) {
            $query->selectRaw('MAX(id)')
                ->from('organization_documents')
                ->where('organization_id', $request->depositor_id)
                ->groupBy('type_id');
        })->get();
        


        try {
            $emails = $bank->notifiableUsersEmails(true);
            Mail::to($emails)->queue(new BankMails([
                'documents' => $documents,
                'name' => $depositor->name,
                'subject' => 'Depositor Shared Documents'
            ], 'share_documents'));
        } catch (\Throwable $th) {
            Log::alert('An error');
        }

        //  TODO: send a notification to bank

        loginActivities("Depositor shared some documents ", $request->server('HTTP_USER_AGENT'), 0);
        return response()->json(["message" => 'Documents Shared Successfully', "data" => $user_notify, "success" => true], 201);
    }
    public function getFiWireTransferDetails($organization_id)
    {
        $wiretranferid = DocumentType::where('type_name', 'Transfer Details')->value('id');
        // return  $wiretranferid;
        if ($wiretranferid) {
            $result = OrganizationDocument::where([
                ['organization_id', '=', $organization_id],
                ['type_id', '=', $wiretranferid],
            ])->orderBy('id', 'desc')->select('file_name', 'user_uploaded_file_name')->first();

            if ($result) {
                $fileName = $result->file_name;
                return response()->json(["message" => 'File found', "filename" => $result->user_uploaded_file_name, "data" => $fileName, "success" => true], 200);
            } else {
                $fileName = false;
                return response()->json(["message" => 'File not found', "data" => [], "success" => false], 200);
            }
        } else {
            $fileName = false;
            return response()->json(["message" => 'Document types not found', "data" => [], "success" => false], 200);
        }
    }

    public function addOrUpdateEntity(Request $request)
    {
        return $this->newSignupService->addOrUpdateEntity($request);
    }
    public function addOrUpdateKeyIndividuals(Request $request)
    {
        return $this->newSignupService->addOrUpdateKeyIndividuals($request);
    }
    public function deleteKeyIndividuals(Request $request)
    {
        return $this->newSignupService->deleteKeyIndividuals($request);
    }
    public function deleteEntity(Request $request)
    {
        return $this->newSignupService->deleteEntity($request);
    }
}

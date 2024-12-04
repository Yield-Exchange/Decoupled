<?php

namespace App\Http\Controllers\Dashboard\Bank;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Bank\FICampaignService;
use App\Services\Bank\FICampaignAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Validator;

class FICampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     protected $fiCampaignService, $fiCampaignAnalyticsService;

     public function __construct(FICampaignService $ficampaignservice, FICampaignAnalyticsService $fiCampaignAnalyticsService)
     {
         $this->fiCampaignService = $ficampaignservice;
         $this->fiCampaignAnalyticsService = $fiCampaignAnalyticsService;
     }
     public function index()
     {
 
         //
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Adding a campaign");

        //validation
        $validator = Validator::make($request->all(), [
            'campaignName' => 'required|max:255',
            'startDate' => 'required|date|date_format:Y-m-d H:i',
            'expiryDate' => 'required|date|date_format:Y-m-d H:i',
            'currency' => 'required|max:15',
            'subscriptionAmount' => 'required|numeric',
            'status' => 'required|in:DRAFT,ACTIVE,INACTIVE,SCHEDULED',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->saveCampaign($request, false);
        return $record;

        //
    }
    public function updateCampaign(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Adding a campaign");

        //validation
        $validator = Validator::make($request->all(), [
            'campaignName' => 'required|max:255',
            'startDate' => 'required|date|date_format:Y-m-d H:i',
            'expiryDate' => 'required|date|date_format:Y-m-d H:i',
            'currency' => 'required|max:15',
            'subscriptionAmount' => 'required|numeric',
            'status' => 'required|in:DRAFT,ACTIVE,INACTIVE,SCHEDULED',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->saveCampaign($request, true);
        return $record;

        //
    }
    public function deactivateCampaign(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Deactivting a campaign");

        //validation
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:DRAFT,ACTIVATE,INACTIVATE ',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->deactivateCampaign($request);
        return $record;

        //
    }
    public function featureFICampaignProduct(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Featuring/Unfeaturing Product from a capaign");

        //validation
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:FEATURE,UN FEATURE',
            //            'campaign' => 'required',
            'product' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->featureFICampaignProduct($request);
        return $record;

        //
    }
    public function deleteGroup(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Deleting a campaign group");
        //validation
        $validator = Validator::make($request->all(), [
            'group' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->deleteGroup($request);
        return $record;

        //
    }
    public function createFICampaignProduct(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Adding a campaign");
        //validation
        $allproducts = Product::get(['id']);
        $arrayData = json_decode($allproducts, true);
        $idValues = [];
        foreach ($arrayData as $item) {
            if (isset($item['id'])) {
                $idValues[] = $item['id'];
            }
        }
        $validator = Validator::make($request->all(), [
            'productType' => 'required|in:' . implode(',', $idValues),
            'termLengthType' => 'required|in:DAYS,MONTHS',
            'termLength' => 'required|numeric',
            //            'compoundFrequency' => 'required|in:AT MATURITY,ANNUALLY,SEMI ANNUALLY,QUATERLY,MONTHLY',
            'defaultProductName' => 'required ',
            // 'pds' => 'required|max:10000',
        ]);
        $fileName = "";
        if ($request->hasFile("pds")) {
            $file = $request->file("pds");
            $fileName = 'pds' . time() . rand(1, 99) . '.' . $file->extension();
            $file->move(public_path('uploads/pds'), $fileName);
        }

        if ($validator->fails()) {
            return response()->json(['message' => Arr::flatten($validator->messages()->get('*')), 'success' => false], 400);
        }
        //validation
        $record = $this->fiCampaignService->saveFICampaignProduct($request, $fileName, false);
        return $record;
    }
    public function updateFICampaignProduct(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Adding a campaign");
        //validation
        $allproducts = Product::get(['id']);
        $arrayData = json_decode($allproducts, true);
        $idValues = [];
        foreach ($arrayData as $item) {
            if (isset($item['id'])) {
                $idValues[] = $item['id'];
            }
        }
        $validator = Validator::make($request->all(), [
            'productType' => 'required|in:' . implode(',', $idValues),
            'termLengthType' => 'required|in:DAYS,MONTHS',
            'termLength' => 'required|numeric',
            //            'compoundFrequency' => 'required|in:ANNUALLY,SEMI ANNUALLY,QUATERLY,MONTHLY',
            'defaultProductName' => 'required ',
            // 'pds' => 'required|max:10000',
        ]);
        $fileName = "";
        if ($request->hasFile("pds")) {
            $file = $request->file("pds");
            $fileName = 'pds' . time() . rand(1, 99) . '.' . $file->extension();
            $file->move(public_path('uploads/pds'), $fileName);
        }

        if ($validator->fails()) {
            return response()->json(['message' => Arr::flatten($validator->messages()->get('*')), 'success' => false], 400);
        }
        //validation
        $record = $this->fiCampaignService->saveFICampaignProduct($request, $fileName, true);
        return $record;
    }
    public function deleteFIProduct(Request $request)
    {

        systemActivities($request->user, json_encode($request->query()), "Deleting a campaign product");
        //validation
        $validator = Validator::make($request->all(), [
            'product' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->deleteFIProduct($request);
        return $record;

        //
    }
    public function activateDeactivateFIProduct(Request $request)
    {

        systemActivities($request->user, json_encode($request->query()), "Deleting a campaign product");
        //validation
        $validator = Validator::make($request->all(), [
            'product' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->activateDeactivateFIProduct($request);
        return $record;

        //
    }
    public function createGroup(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "creating a campaign group");
        //validation

        $validator = Validator::make($request->all(), [
            'groupName' => 'required',
            //            'depositors' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation

        $record = $this->fiCampaignService->saveGroup($request, false);
        return $record;
    }
    public function updateGroup(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Updating a campaign group");
        //validation

        $validator = Validator::make($request->all(), [
            'groupName' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation

        $record = $this->fiCampaignService->saveGroup($request, true);
        return $record;
    }
    public function addGroupDepositor(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "adding depositor from a group");
        //validation
        $validator = Validator::make($request->all(), [
            'group' => 'required',
            'depositors' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->addGroupDepositor($request);
        return $record;
    }
    public function removeGroupDepositor(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Removing depositor from a group");
        //validation

        $validator = Validator::make($request->all(), [
            'group' => 'required',
            'depositors' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation

        $record = $this->fiCampaignService->removeGroupDepositor($request);
        return $record;
    }

    public function getMyCampaigns(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Getting my campaign ");
        return $this->fiCampaignService->getMyCampaigns($request);
    }
    public function getCampaignDetails(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Getting campaign details ");
        return $this->fiCampaignService->getCampaignDetails($request);
    }
    public function getMyCampaignProducts(Request $request)
    {

        systemActivities($request->user, json_encode($request->query()), "Getting all the campaign Products");
        return $this->fiCampaignService->getMyCampaignProducts($request);
    }
    public function getMyGroups(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Getting my groups");
        return $this->fiCampaignService->getMyGroups($request);
    }
    public function getMyGroup(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Getting my group");
        return $this->fiCampaignService->getMyGroup($request->groups);
    }
    public function getSelectedGroupsDepos(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Getting my group");
        return $this->fiCampaignService->getSelectedGroupsDepos($request->groups);
    }
    
    
    public function getMyProducts(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Getting my Products");
        if($request->filled("campaign_id")){
            return $this->fiCampaignService->getMyProductsForedit($request);
        }else{
            return $this->fiCampaignService->getMyProducts($request);
        }
       
    }

    public function deleteMyCampaign(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Deleting my campaign");
        //validation
        $validator = Validator::make($request->all(), [
            'campaign' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->deleteMyCampaign($request);
        return $record;
    }

    public function deactivateMyCampaign(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Deactivate my campaign");
        //validation
        $validator = Validator::make($request->all(), [
            'campaign' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->deactivateMyCampaign($request);
        return $record;
    }

    public function removeProductMyCampaign(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Remove Product my campaign");
        //validation
        $validator = Validator::make($request->all(), [
            'campaign_fi_campaign_product_id' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }
        //validation
        $record = $this->fiCampaignService->removeProductMyCampaign($request);
        return $record;
    }
    public function getGroupUnlinkedDepositors(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "accessing group unlinked depositors");
        //validation
        //validation
        $record = $this->fiCampaignService->getGroupUnlinkedDepositors($request);
        return $record;
    }
    public function updateCampaignProductInfo(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "updating campaign product details for " . $request->product);
        //validation
        //validation
        $record = $this->fiCampaignService->updateCampaignProductInfo($request);
        return $record;
    }

    public function campaignDashboardSummary(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Accesing campaign dashboard");
        $record = $this->fiCampaignAnalyticsService->campaignDashboardSummary($request, $this->fiCampaignService);
        return $record;
    }
    public function campaignInsights(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Accesing " . $request->campaign . "campaign insights");
        $record = $this->fiCampaignAnalyticsService->campaignInsights($request);
        return $record;
    }
    public function campaignProductInsights(Request $request)
    {
        systemActivities($request->user, json_encode($request->query()), "Accesing campaign " . $request->product . " insights");
        $record = $this->fiCampaignAnalyticsService->campaignProductInsights($request);
        return $record;
    }
    public function getMyDeposIds(Request $request){
        systemActivities($request->user, json_encode($request->query()), "Accesing campaign " . $request->product . " insights");
        $record = $this->fiCampaignService->getMyDeposIds($request);
        return $record;
    }
    public function getAllProductsTypes(){
        $record = $this->fiCampaignService->getAllProductsTypes();
        return $record;

    }

}

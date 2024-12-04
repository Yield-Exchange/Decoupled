<?php

namespace App\Http\Controllers\Dashboard;

use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Http\Requests\MarketPlace\MarketPlaceDeleteRequest;
use App\Http\Requests\MarketPlace\CampaignFetchRequest;
use App\Http\Requests\MarketPlace\CampaignPostRequest;
use App\Models\Campaign;
use App\Models\CampaignFICampaignProduct;
use App\Models\FICampaignProduct;
use App\Services\Bank\FICampaignService;
use Illuminate\Http\Request;
use App\Models\CampaignQuery;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;
use App\Models\Product;
use App\Services\CampaignService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CampaignsController extends Controller
{
    private $campaignService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->campaignService = new CampaignService();
    }

    /**
     * Display a listing of the resource.
     *
     * @param CampaignFetchRequest $request
     * @return Factory|View|JsonResponse
     */
    public function index(CampaignFetchRequest $request)
    {

        $user = $request->user();
        if( !$user->userCan('bank/my-campaigns/page-access') ){
            return view('dashboard.403');
        }
//

        systemActivities(Auth::id(), json_encode($request->query()), "Viewing market place page");
        $organization = $user->organization;
        //        $market_place_filters = CampaignQuery::where('organization_id',$organization->id)->orderBy('id','DESC')->first();
        //        $show_filter_page_1 = $request->has('has_filtered');
        //        if ($request->has('getData')) {
        //            if($show_filter_page_1) {
        //                $request['last_market_place_filter'] = $market_place_filters;
        //            }
        //            return $this->campaignService->fetch($request);
        //        }
        //
        //        $banks = [];
        $products =[];
        $products = Product::all()->toArray();

        
        //        if($organization->type == "DEPOSITOR"){
        //            $banks_ids_with_active_offers = Campaign::where('status','ACTIVE')->pluck('organization_id')->toArray();
        //            $banks = Organization::Banks()->whereIn('id',$banks_ids_with_active_offers)->get()->toArray();
        //            array_unshift($banks,['id'=>'all','name'=>'All Financial Institutions']);
        //            array_unshift($products,['id'=>'all','description'=>'All Products']);
        //        }

        $depositors = Organization::with('industry')->where('type', 'DEPOSITOR')
            ->where('is_test', \auth()->user()->is_test)
            ->where('enable_campaigns', true)
            ->whereIn('status',['ACTIVE'])
            ->get()->toArray();

        // set time zone
        $timezone = config('app.timezone');
        $provinces = json_encode(provinces());
        $unformattedusertimezone=Auth::user()->timezone;
        $formattedtimezone=formattedTimezone($unformattedusertimezone);
     
        
        return view('dashboard.campaigns', compact('unformattedusertimezone','formattedtimezone','organization', 'depositors', 'products', 'timezone','provinces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CampaignPostRequest $request
     * @return JsonResponse
     */
    public function store(CampaignPostRequest $request)
    {
        $user = $request->user();

        if( !$user->userCan('bank/my-campaigns/build-new-campaign') ){
            return response()->jsonErrorFailure("Permission denied");
        }

        $organization = $user->organization;
        $validated = $request->validated();

        if (!empty(trim($request->product_disclosure_url))) {
            if (strpos($request->product_disclosure_url, 'http') !== false) {
                $validated['product_disclosure_url'] = $request->product_disclosure_url;
            } else {
                $validated['product_disclosure_url'] = "https://" . $request->product_disclosure_url;
            }
        }

        if ($request->hasFile('product_disclosure_statement')) {
            $validated['product_disclosure_statement'] = time() . '_' . str_replace(" ", "_", $request->file('product_disclosure_statement')->getClientOriginalName());
            $request->file('product_disclosure_statement')->move(public_path('/uploads'), $validated['product_disclosure_statement']);
        }

        $log = "Created a market place offer";
        if ($organization->type == 'DEPOSITOR') {
            if ($request->has('is_shop_rate')) {
                $log = "Shopped a rate from a market place offer";
                systemActivities(Auth::id(), json_encode($request->query()), $log);
                $response = array(
                    "success" => true, "message" => "Please wait as you are redirected", "data" => [],
                    'url' => url('post-request?market_place_offer=' . CustomEncoder::urlValueEncrypt($validated['market_place_offer_id']))
                );
                return response()->json($response, 200);
            }

            $validated['select_offer'] = true;
            $log = "Selected a market place offer";
        }

        systemActivities(Auth::id(), json_encode($request->query()), $log);
        return $this->campaignService->save($validated, $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CampaignPostRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CampaignPostRequest $request, $id)
    {
        $validated = $request->validated();

        $market_place_offer = Campaign::find(CustomEncoder::urlValueDecrypt($id));
        if (!$market_place_offer) {
            $response = array("success" => false, "message" => "Failed, market place offer not found.", "data" => []);
            return response()->json($response, 404);
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Updated a market place offer");
        return $this->campaignService->update($validated, $market_place_offer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MarketPlaceDeleteRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(MarketPlaceDeleteRequest $request, $id)
    {
        $user = auth()->user();
        if( !$user->userCan('bank/my-campaigns/deactivate-campaign') ){
            return response()->jsonErrorFailure("Permission denied");
        }

        $market_place_offer = Campaign::find(CustomEncoder::urlValueDecrypt($id));
        if (!$market_place_offer) {
            $response = array("success" => false, "message" => "Failed, market place offer not found.", "data" => []);
            return response()->json($response, 404);
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Deleted a market place offer");
        return $this->campaignService->delete($market_place_offer);
    }

    public function featured(Request $request)
    {
        $user = Auth::user();
        $organization = $user->organization;
        if (!$user->userCan('bank/market-place-offer/create-market-place-offer')) {
            $response = ['data' => [], 'message' => "Permission denied", 'success' => false];
            return response()->json($response, 403);
        }

        try {
            if (!$request->has('new_featured')) {
                $featuredOffer = Campaign::where('organization_id', $organization->id)->where('is_featured', true)->where("status", "ACTIVE")->orderBy('id', 'DESC')->first();
                $response = ["success" => true, "message" => "Offer Not Available For Featuring", "data" => $featuredOffer];
                return response()->json($response, 200);
            }

            DB::beginTransaction();

            $old_featured = Campaign::IsFeatured()->where('organization_id', $organization->id)->first();
            if ($old_featured) {
                archiveTable($old_featured->id, $old_featured->getTable(), $user->id, "Removed featured");
                $old_featured->remove_featured();
            }

            systemActivities($user->id, json_encode($request->query()), "Updated a market place offer");
            $market_place_offer = Campaign::find(CustomEncoder::urlValueDecrypt(request('new_featured')));
            $market_place_offer->make_featured();

            DB::commit();

            $response = ["success" => true, "message" => "Market place offer made featured successfully"];
            return response()->json($response, 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = ["success" => false, "message" => "Fail to create featured offer", "data" => [$exception->getMessage()]];
            return response()->json($response, 400);
        }
    }

    public function products(Request $request)
    {
        $products = Product::all();
        return view('dashboard.bank.campaigns.products', compact('products'));
    }

    public function groups(Request $request)
    {
        $depositors = Organization::where('type', 'DEPOSITOR')
            ->where('is_test', \auth()->user()->is_test)
            ->whereIn('status',['ACTIVE'])
            ->where("enable_campaigns",true)
            ->get();
        return view('dashboard.bank.campaigns.groups', compact('depositors'));
    }

    public function drafts(Request $request)
    {
        $products = Product::all();
        return view('dashboard.bank.campaigns.drafts', compact('products'));
    }

    public function productsSummary(Request $request, $id)
    {
        $user = \auth()->user();
        $product = $products = FICampaignProduct::where("fi_id", $user->organization->id)->with("productType")->find($id);
        if (!$product) {
            return redirect()->back();
        }
        return view('dashboard.bank.campaigns.product-summary', compact('product'));
    }

    public function campaignSummary(Request $request, $id)
    {
        $user = \auth()->user();
        $campaign = $products = Campaign::where("fi_id", $user->organization->id)
            ->with(["campaignGroups", "campaignProducts", "campaignFI", "campaignProducts.product"])->find($id);
            $campaign['start_date']=convertBackToUTC($campaign->start_date);
            $campaign['expiry_date']=convertBackToUTC($campaign->expiry_date);
        if (!$campaign) {
            return redirect()->back();
        }
        $unformattedusertimezone=Auth::user()->timezone;
        $formattedtimezone=formattedTimezone($unformattedusertimezone);
        return view('dashboard.bank.campaigns.summary', compact('campaign','unformattedusertimezone','formattedtimezone'));
    }
    public function editCampaign(Request $request, $id){
        $user = \auth()->user();
        $campaign = Campaign::where("fi_id", $user->organization->id)
            ->with(["campaignGroups", "campaignProducts", "campaignFI", "campaignProducts.product"])->find($id);
            $campaign['start_date']=convertBackToUTC($campaign->start_date);
            $campaign['expiry_date']=convertBackToUTC($campaign->expiry_date);
        if (!$campaign) {
            return redirect()->back();
        }
        $organization = $request->user()->organization;
        //        $market_place_filters = CampaignQuery::where('organization_id',$organization->id)->orderBy('id','DESC')->first();
        //        $show_filter_page_1 = $request->has('has_filtered');
        //        if ($request->has('getData')) {
        //            if($show_filter_page_1) {
        //                $request['last_market_place_filter'] = $market_place_filters;
        //            }
        //            return $this->campaignService->fetch($request);
        //        }
        //
        //        $banks = [];
        $products = Product::all()->toArray();
        //        if($organization->type == "DEPOSITOR"){
        //            $banks_ids_with_active_offers = Campaign::where('status','ACTIVE')->pluck('organization_id')->toArray();
        //            $banks = Organization::Banks()->whereIn('id',$banks_ids_with_active_offers)->get()->toArray();
        //            array_unshift($banks,['id'=>'all','name'=>'All Financial Institutions']);
        //            array_unshift($products,['id'=>'all','description'=>'All Products']);
        //        }

        $depositors = Organization::where('type', 'DEPOSITOR')
            ->where('is_test', \auth()->user()->is_test)
            ->whereIn('status',['ACTIVE'])
            ->where("enable_campaigns",true)
            ->get()->toArray();

        // set time zone
        $timezone = config('app.timezone');
        $provinces = json_encode(provinces());
        
        return view('dashboard.bank.campaigns.edit-campaign', compact('campaign','depositors','products','timezone','organization'));
    }
    public function campaignDraftSummary(Request $request, $id){
        $user = \auth()->user();
        $campaign = $products = Campaign::where("fi_id", $user->organization->id)
            ->with(["campaignGroups", "campaignProducts", "campaignFI", "campaignProducts.product"])->find($id);
        if (!$campaign) {
            return redirect()->back();
        }
        return view('dashboard.bank.campaigns.draft-summary', compact('campaign'));
    }
    public function CampaignProductsSummary(Request $request, $id)
    {
        $request['campaign_product_id'] = $id;
        $campaign = $products = (new FICampaignService())->getMyCampaignProducts($request)->toArray();
      
        if (empty($campaign['data'][0])) {
             return redirect()->back();
        }
        $product = $campaign['data'][0];
        return view('dashboard.bank.campaigns.campaign-product-summary', compact('product'));
    }
}

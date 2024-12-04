<?php

namespace App\Services\Bank;

use App\Constants;
use App\Http\Resources\FICampaignResource;
use App\Models\Campaign;
use App\Models\CampaignFICampaignProduct;
use App\Models\CampaignProductView;
use App\Models\CampaignTargetGroup;
use App\Models\FICampaignGroup;
use App\Models\FICampaignGroupDepositor;
use App\Models\FICampaignProduct;
use App\Models\FICampaignTargetGroup;
use App\Models\Organization;
use App\Models\Product;
use App\Models\Province;
use App\Data\BankData;
use App\Models\Industry;
use App\User;
use DB;
use Illuminate\Http\Request;
use App\Traits\UserTrait;
use DateTime;
use App\Services\Bank\FICampaignService;

class FICampaignAnalyticsService
{
    use UserTrait;


    public function campaignDashboardSummary(Request $request, FICampaignService  $ficampaignservice)
    {
        try {
            DB::beginTransaction();
            $featured = $ficampaignservice->getMyCampaignProducts($request, 1);
            $productanalysis = $this->singleCampaignProductData($request);
            DB::commit();
            return response()->json([
                "success" => false,
                'message' => 'Loaded.',
                'campaign_subscription_amount' => 200, 000,
                'proddetails' => $productanalysis
            ], 200);
        } catch (\Exception  $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Failed.' . $exp->getMessage(),
            ], 400);
        }
    }
    public function singleCampaignProductData($campaignproductobject, $startdate = "", $enddate = "")
    {
        return CampaignFICampaignProduct::with(["campaignProductViews", "depositRequests"])->where("id", $campaignproductobject->product)->first();
    }
    public function campaignInsights(Request $request)
    {

        $enddate = getUTCTimeNow();
        $startdate = null;
        if ($request->filled("inTheLast")) {
            $startdate = getUTCTimeNowAddSubtractDays(($request->inTheLast) * -1);
        }
        $allmarkets = Industry::all();
        $allprovinces = Province::all();
        $allProducts = Product::all();
        $uniqueproductcampaigns = CampaignFICampaignProduct::join("f_i_campaign_products", "f_i_campaign_products.id", "=", "campaign_f_i_campaign_products.fi_campaign_product_id")
            ->join("products", "products.id", "=", "f_i_campaign_products.product_type_id")
            ->where("campaign_f_i_campaign_products.campaign_id", $request->campaign)
            ->pluck("products.description")->unique();
        $allprovinces = Province::all();

        $provinceswithclicks =  DB::table('campaign_product_views as t')
            ->join('demographic_organization_data as o', 't.viewer_organization_id', '=', 'o.organization_id')
            ->join('campaign_f_i_campaign_products as cp', 't.campaign_f_i_campaign_product_id', '=', 'cp.id')
            ->select(
                'o.province',
                DB::raw('COUNT(DISTINCT CONCAT(t.viewer_organization_id, "-", t.campaign_f_i_campaign_product_id)) as unique_count')
            );
        if ($request->filled("inTheLast")) {
            $provinceswithclicks->whereBetween(DB::raw('DATE(t.created_at)'), [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')]);
        }
        $provinceswithclicks = $provinceswithclicks->where('cp.campaign_id', $request->campaign)
            ->groupBy('o.province')
            ->get();

            $newprovincelist = [];
            foreach ($allprovinces as $province) {
                $found = false;
                foreach ($provinceswithclicks as $provclick) {
                    if ($province->province_name === $provclick->province) {
                        $found = true;
                    }
                }
                if ($found) {
                    array_push($newprovincelist, ['province' => $province->province_name, 'unique_count' => $provclick->unique_count]);
                } else {
    
                    array_push($newprovincelist, ['province' => $province->province_name, 'unique_count' => 0]);
                }
                // If not ound, push a default value
            }



        $marketsectorwithclicks =  DB::table('campaign_product_views as t')
            ->join('organizations as o', 't.viewer_organization_id', '=', 'o.id')
            ->join('industries as indu', 'o.industry_id', '=', 'indu.id')
            ->join('campaign_f_i_campaign_products as cp', 't.campaign_f_i_campaign_product_id', '=', 'cp.id')
            ->select(
                'indu.name',
                DB::raw('COUNT(DISTINCT CONCAT(t.viewer_organization_id, "-", t.campaign_f_i_campaign_product_id)) as unique_count')
            );
        if ($request->filled("inTheLast")) {
            $marketsectorwithclicks->whereBetween(DB::raw('DATE(t.created_at)'), [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')]);
        }
        $marketsectorwithclicks = $marketsectorwithclicks->where('cp.campaign_id', $request->campaign)
            ->groupBy('indu.name')
            ->get();
        $newmarketlist = [];
        foreach ($allmarkets as $market) {
            $found = false;
            foreach ($marketsectorwithclicks as $mclick) {
                if ($market->name == $mclick->name) {
                    $found = true;
                }
            }
            if ($found) {
                array_push($newmarketlist, ['name' => $market->name, 'unique_count' => $mclick->unique_count]);
            } else {

                array_push($newmarketlist, ['name' => $market->name, 'unique_count' => 0]);
            }
            // If not ound, push a default value
        }

        $productswithclicks =  DB::table('campaign_product_views as t')
            ->join('campaign_f_i_campaign_products as cp', 't.campaign_f_i_campaign_product_id', '=', 'cp.id')
            ->join('f_i_campaign_products as ficp', 'cp.fi_campaign_product_id', '=', 'ficp.id')
            ->join('products as p', 'ficp.product_type_id', '=', 'p.id')
            ->select(
                'p.description AS product',
                DB::raw('COUNT(DISTINCT CONCAT(t.viewer_organization_id, "-", t.campaign_f_i_campaign_product_id)) as unique_count')
            );
        if ($request->filled("inTheLast")) {
            $productswithclicks->whereBetween(DB::raw('DATE(t.created_at)'), [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')]);
        }
        $productswithclicks = $productswithclicks->where('cp.campaign_id', $request->campaign)
            ->groupBy('p.description')
            ->get();
            $newproductclicklist = [];
            foreach ($allProducts as $prod) {              
                $found = false;
                foreach ($productswithclicks as $pclick) {
                    if ($prod->description == $pclick->product) {
                        $found = true;
                    }
                }
                if ($found) {
                    array_push($newproductclicklist, ['product' => $prod->description, 'unique_count' => $pclick->unique_count]);
                } else {
    
                    array_push($newproductclicklist, ['product' => $prod->description, 'unique_count' => 0]);
                }
                // If not found, push a default value
            }


        // $campaigninsightsA = $this->campaignInsightsA($request);
        $userdetails = $this->getLoggedInUserDetails();

        $inprogress = BankData::campaignDepositData($request->campaign, "list", ['PENDING_DEPOSIT'], ($request->filled("inTheLast")) ? [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')] : null);
        $activebought = BankData::campaignDepositData($request->campaign, "list", ['ACTIVE'], ($request->filled("inTheLast")) ? [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')] : null);
        $activependingbought = BankData::campaignDepositData($request->campaign, "list", ['ACTIVE', 'PENDING_DEPOSIT'], ($request->filled("inTheLast")) ? [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')] : null);
        $rates = BankData::ratesComparison($request->campaign, "list", ['ACTIVE', 'PENDING_DEPOSIT'], ($request->filled("inTheLast")) ? [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')] : null, $userdetails['user_details']->organization->id);
        // $campaigninsightsB = $this->campaignInsightsB($request);

        $newmarketrates = [];
        $newmyrates = [];
        foreach ($allProducts as $prod) { 
            $marketratefound = false;
            $myratefound = false;
            //marketrates            
            foreach ($rates['market_rates'] as $markrate) {             

                if ($markrate!=null) {                 

                        if ($prod->description == $markrate->product) {
                            $marketratefound = true;
                            break;
                        }
                                   
                }else{
                    $marketratefound = false;                     
                }
            }
            if ($marketratefound) {
                array_push($newmarketrates, ['product' => $prod->description, 'unique_count' => $markrate->market_rates]);
            } else {

                array_push($newmarketrates, ['product' => $prod->description, 'unique_count' => 0]);
            }
            // market rates
            //marketrates
            
            foreach ($rates['my_rates'] as $myrate) {               

                if ($myrate!=null) {
                    if ($prod->description == $myrate->product) {
                        $myratefound = true;
                        break;
                    }
                   
                }else{
                    $myratefound = false;                     
                }
            }
            if ($myratefound) {
                array_push($newmyrates, ['product' => $prod->description, 'unique_count' => $myrate->my_rates]);
            } else {

                array_push($newmyrates, ['product' => $prod->description, 'unique_count' => 0]);
            }
            // market rates
        }




        return response()->json([
            "success" => false,
            'message' => 'Loaded.',
            'nowutc' => getUTCTimeNow()->format('Y-m-d'),
            'startdate' => $startdate->format('Y-m-d'),
            'marketSectorClicks' => $newmarketlist,
            'products' => $uniqueproductcampaigns, 'activePendingPurchases' => $activependingbought,
            'inProgressPurchases' => $inprogress,
            'activePurchases' => $activebought,
            'clicksByProduct' => $newproductclicklist,
            'rates' => ['my_rates' => $newmyrates, 'market_rates' => $newmarketrates],
            'clicksByProvince' => $newprovincelist,

        ], 200);
    }
    public function campaignInsightsA(Request $request, $update = false)
    {
        try {
            DB::beginTransaction();


            DB::commit();
            return response()->json([
                "success" => false,
                'message' => 'Loaded.',
                'campaign_subscription_amount' => 200, 000,
                'featured_product_details' => 'Product AA',
                'products_names' => $uniqueproductcampaigns
            ], 200);
        } catch (\Exception  $exp) {
            DB::rollBack();

            return response()->json([
                "success" => false,
                'message' => 'Failed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function campaignInsightsB(Request $request, $update = false)
    {
        try {
            DB::beginTransaction();
            DB::commit();
            return response()->json([
                "success" => false,
                'message' => 'Loaded.',
                'campaign_subscription_amount' => 200, 000,
                'featured_product_details' => 'Product AA',
                'today_clicks' => 450,
                'yesterday_clicks' => 0
            ], 200);
        } catch (\Exception  $exp) {
            DB::rollBack();

            return response()->json([
                "success" => false,
                'message' => 'Failed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function campaignProductInsights(Request $request, $update = false)
    {
        try {
            DB::beginTransaction();
            $allmarkets = Industry::all();
            $enddate = getUTCTimeNow();
            $startdate = null;
            if ($request->filled("inTheLast")) {
                $startdate = getUTCTimeNowAddSubtractDays(($request->inTheLast) * -1);
            }
            $product =  CampaignFICampaignProduct::with(["campaignProductViews", "depositRequests"])->where("id", $request->product)->first();
            $marketsectorwithclicks =  DB::table('campaign_product_views as t')
                ->join('organizations as o', 't.viewer_organization_id', '=', 'o.id')
                ->join('industries as indu', 'o.industry_id', '=', 'indu.id')
                ->join('campaign_f_i_campaign_products as cp', 't.campaign_f_i_campaign_product_id', '=', 'cp.id')
                ->select(
                    'indu.name',
                    DB::raw('COUNT(DISTINCT CONCAT(t.viewer_organization_id, "-", t.campaign_f_i_campaign_product_id)) as unique_count')
                );
            if ($request->filled("inTheLast")) {
                $marketsectorwithclicks->whereBetween(DB::raw('DATE(t.created_at)'), [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')]);
            }
            $marketsectorwithclicks = $marketsectorwithclicks->where('cp.id', $request->product)
                ->groupBy('indu.name')
                ->get();

                $newmarketlist = [];
                foreach ($allmarkets as $market) {
                    $found = false;
                    foreach ($marketsectorwithclicks as $mclick) {
                        if ($market->name == $mclick->name) {
                            $found = true;
                        }
                    }
                    if ($found) {
                        array_push($newmarketlist, ['name' => $market->name, 'unique_count' => $mclick->unique_count]);
                    } else {
        
                        array_push($newmarketlist, ['name' => $market->name, 'unique_count' => 0]);
                    }
                    // If not ound, push a default value
                }

            DB::commit();
            return response()->json([
                "success" => false,
                'message' => 'Loaded.',
                'product' => $product,
                'marketSectorClicks' => $newmarketlist
            ], 200);
        } catch (\Exception  $exp) {
            DB::rollBack();

            return response()->json([
                "success" => false,
                'message' => 'Failed.' . $exp->getMessage(),

            ], 400);
        }
    }
}

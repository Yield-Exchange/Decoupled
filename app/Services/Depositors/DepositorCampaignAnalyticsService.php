<?php

namespace App\Services\Depositors;

use App\Data\DepositorData;
use DB;
use Illuminate\Http\Request;
use App\Traits\UserTrait;
use Carbon\Carbon;
class DepositorCampaignAnalyticsService
{
    use UserTrait;

    public function campaignInsights(Request $request)
    {

        $enddate =  Carbon::now("UTC")->lastOfYear();
        $userdetails = $this->getLoggedInUserDetails();
     
        // $startdate = null;
        // if ($request->filled("inTheLast")) {
            $startdate = Carbon::now("UTC")->firstOfYear();
        // }
        // $campaigninsightsA = $this->campaignInsightsA($request);
        $marketSectorAnalysis = DepositorData::marketSectorAnalysis($request->campaign, "list", ['ACTIVE', 'PENDING_DEPOSIT'], ($request->filled("inTheLast")) ? [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')] : null);
        $termAnalysis = DepositorData::termAnalysis($request->campaign, "list", ['ACTIVE', 'PENDING_DEPOSIT'], ($request->filled("inTheLast")) ? [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')] : null);
        $productMonthTrend = DepositorData::productMonthTrend($request->campaign, "list", ['ACTIVE', 'PENDING_DEPOSIT'], ($request->filled("inTheLast")) ? [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')] : null);
        $totalinvestors = DepositorData::noOfInvestors($request->campaign, "list", ['ACTIVE', 'PENDING_DEPOSIT'], ($request->filled("inTheLast")) ? [$startdate->format('Y-m-d'), $enddate->format('Y-m-d')] : null);
        // $campaigninsightsB = $this->campaignInsightsB($request);
        return response()->json([         
            "success" => false,
            'message' => 'Loaded.',
            'productPurchaseTrend' => $marketSectorAnalysis,
            'termTrend' => $termAnalysis,
            'productByMonthTrend' => $productMonthTrend,
            'totalInvestors' => $totalinvestors[0]->org_count
        ], 200);
    }
}

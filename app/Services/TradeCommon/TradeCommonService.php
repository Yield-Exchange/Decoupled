<?php

namespace App\Services\TradeCommon;

use App\Models\InterestCalculationOption;
use App\Models\Organization;
use App\Models\TradeProduct;
use App\Models\PreferredCollateral;
use App\Models\TradeAllowedSettlementPeriod;
use App\Models\TradeBasketType;
use App\Models\TradeCollateral;
use App\Models\TradeCollateralBasket;
use App\Models\TradeTriBasketThirdParty;
use Illuminate\Http\Request;
use App\Traits\UserTrait;
use DB;
use Auth;

class TradeCommonService
{
    public function getProducts(Request $request)
    {
        $products = TradeProduct::query();
        if ($request->filled("disabled")) {
            $disabled = $request->input("disabled");
            $products->where("is_disabled", $disabled);
        }
        return $products->get();
    }
    public function getCollaterals(Request $request)
    {
        $products = TradeCollateral::query();
        if ($request->filled("disabled")) {
            $disabled = $request->input("disabled");
            $products->where("is_disabled", $disabled);
        }
        return $products->get();
    }
    public function getPreferredCollaterals(Request $request)
    {
        $colleterals = PreferredCollateral::query();
        if ($request->filled("status")) {
            $status = $request->status;
            $colleterals->whereIn("status", [$status]);
           
        }
        return $colleterals->get();
        
    }
    public function getSettlementDates(Request $request){
        $dates = TradeAllowedSettlementPeriod::query();
        if ($request->filled("status")) {
            $status = $request->status;
            $dates->whereIn("status", [$status]);
           
        }
        return $dates->get();
    }
    public function getCollateralGivers(Request $request){
        $organizations = Organization::with('industry')
        ->join('organization_level_permissions', 'organization_level_permissions.organization_id', '=', 'organizations.id')
        ->join('org_permissions_lists', 'org_permissions_lists.id', '=', 'organization_level_permissions.org_permissions_list_permission_id')
        
        ->whereIn('organizations.type', ['BANK'])
        ->whereIn('organization_level_permissions.status', ['Active'])
        ->select('organizations.*')
        ->whereIn('organizations.status', ['ACTIVE'])
        ->where('is_test', \auth()->user()->is_test)
        ->distinct()
        ->get();
        return $organizations;
    }
    public function getCollateralTakers(Request $request){
        $loggedOrg=Auth::user();
        $organizations = Organization::with("industry")->join("organization_level_permissions","organization_level_permissions.organization_id","=","organizations.id")
        ->join("org_permissions_lists","org_permissions_lists.id","=","organization_level_permissions.org_permissions_list_permission_id")
        ->whereIn("organizations.type",["DEPOSITOR"])
        ->whereIn("organization_level_permissions.status",["Active"])
        ->select("organizations.*")
        ->whereIn('organizations.status', ['ACTIVE'])
        ->where('is_test', \auth()->user()->is_test)
        ->distinct()
        ->get();
        $organizations = $organizations->map(function ($organization) use ($loggedOrg) {
            $triesCount = TradeTriBasketThirdParty::join("trade_collateral_baskets", "trade_collateral_baskets.id", "=", "trade_tri_basket_third_parties.trade_collateral_basket_id")
                ->where("trade_tri_basket_third_parties.organization_id", $organization->id)
                ->where("trade_collateral_baskets.organization_id", $loggedOrg->organization->id)
                ->where("trade_tri_basket_third_parties.is_dummy", 0)
                ->groupBy("trade_tri_basket_third_parties.organization_id")
                ->count();        
            // Dynamically append the count to the organization
            $organization->relationships = $triesCount;
        
            return $organization;
        });
        
        return $organizations;


return $organizations;

    }
    public function getCounterParties(Request $request){
        $organizations = Organization::with('industry')
        ->join('organization_level_permissions', 'organization_level_permissions.organization_id', '=', 'organizations.id')
        ->join('org_permissions_lists', 'org_permissions_lists.id', '=', 'organization_level_permissions.org_permissions_list_permission_id')
        ->whereIn('organizations.type', ['BANK','DEPOSITOR'])
        ->whereIn('organization_level_permissions.status', ['Active'])
        ->whereIn('organizations.status', ['ACTIVE'])
        ->whereNotIn("organizations.id",[Auth::user()->organization->id])
        // ->where('is_test', \auth()->user()->is_test)
        ->select('organizations.*')
        ->distinct()
        ->paginate();
        return $organizations;
    }
    public function getBasketTypes(Request $request)
    {
        $types = TradeBasketType::query();
        if ($request->filled("disabled")) {
            $disabled = $request->input("disabled");
            $types->where("is_disabled", $disabled);
        }
        $types = $types->get();
    
        return $types;
    }

    public function getAllInterestCalculationOptions(Request $request){
        $types = InterestCalculationOption::query();
        if ($request->filled("status")) {
            $disabled = $request->input("status");
            $types->where("status", $disabled);
        }
        $types = $types->get();
    
        return $types;
    }
}

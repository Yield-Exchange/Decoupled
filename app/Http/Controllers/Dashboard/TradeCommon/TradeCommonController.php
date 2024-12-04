<?php

namespace App\Http\Controllers\Dashboard\TradeCommon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Arr;
use App\Services\TradeCommon\TradeCommonService;

class TradeCommonController extends Controller
{

    protected $tradeservice;

    public function __construct(TradeCommonService $tradeservice)
    {
        $this->tradeservice = $tradeservice;
    }
    public function getProducts(Request $request)
    {
        return  $this->tradeservice->getProducts($request);
    }
    public function getCollateralGivers(Request $request){
        return  $this->tradeservice->getCollateralGivers($request);
    }
    public function getCollateralTakers(Request $request){
        return  $this->tradeservice->getCollateralTakers($request);
    }
    public function getPreferredCollaterals(Request $request){
      
        return  $this->tradeservice->getPreferredCollaterals($request);
    }
    public function getCollaterals(Request $request)
    {
        return  $this->tradeservice->getCollaterals($request);
    }
    public function getSettlementDates(Request $request)
    {
        return  $this->tradeservice->getSettlementDates($request);
    }    
    public function getBasketTypes(Request $request){
        return  $this->tradeservice->getBasketTypes($request);
    }
    public function getCounterParties(Request $request){
        return  $this->tradeservice->getCounterParties($request);  
    }
    public function getAllInterestCalculationOptions(Request $request){
        
        return  $this->tradeservice->getAllInterestCalculationOptions($request);  
    }

    
}

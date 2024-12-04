<?php

namespace App\Http\Controllers\Dashboard\Admin\Trade;

use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Services\Admin\Trade\TradeService;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Arr;

class TradeController extends Controller
{

    protected $tradeservice;
    public function addDayConvention(Request $request){
        
        return  $this->tradeservice->addDayConvention($request);
    }
    public function __construct(TradeService $tradeservice)
    {
        $this->tradeservice = $tradeservice;
    }
    public function getProducts(Request $request)
    {
        return  $this->tradeservice->getProducts($request);
    }
    public function getCollaterals(Request $request)
    {
        return  $this->tradeservice->getCollaterals($request);
    }
    public function addProduct(Request $request){
        $rules = [
            'productName' => 'required|max:255|unique:trade_products,product_name',
        ];
        $messages = [
            'productName.unique' => 'The product name already exists.',
        ];    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => false], 400);
        }
        return  $this->tradeservice->addProduct($request);
    }
    public function updateProduct(Request $request, $product_id){
        $rules = [
            'productName' => [
                'required',
                'max:255',
                Rule::unique('trade_products', 'product_name')->ignore(CustomEncoder::urlValueDecrypt($product_id)),
            ],
        ];
        
        $messages = [
            'productName.unique' => 'The product name already exists.',
        ]; 
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => false], 400);
        }
        return  $this->tradeservice->updateProduct($request,$product_id);
    }

    public function activateDeactivateProduct(Request $request, $product_id){
        $rules = [
            'disabled' => 'required|boolean',
        ];
        $messages = [
            'disabled' => 'The product disabled is required',
        ];    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => false], 400);
        }
        return  $this->tradeservice->activteDeactivateProduct($request,$product_id);
    }
    public function addNewBasketTypes(Request $request){

        return  $this->tradeservice->addNewBasketTypes($request);

    }
    public function addTradeCollateral(Request $request){

        return  $this->tradeservice->addTradeCollateral($request);
        
    }
    public function getAllBasketTypes(Request $request){
        
    }
}

<?php

namespace App\Services\Admin\Trade;

use App\CustomEncoder;
use App\Models\InterestCalculationOption;
use App\Models\TradeProduct;
use App\Models\TradeBasketType;
use App\Models\TradeCollateral;
use App\Models\TradeCollateralBasket;
use Illuminate\Http\Request;
use App\Traits\UserTrait;
use DB;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Validator;

class TradeService
{
    public function addDayConvention(Request $request)
    {
       
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $message=null;
            $product = [
                'label' => $request->label,
                'slug' => generateSlug($request->label),
                'used_no_of_days_in_a_non_leap_year' => $request->non_leap_year,
                'used_no_of_days_in_a_leap_year' => $request->leap_year,
                'status' => $request->status             
            ];
            // return  $product ;
            if(!InterestCalculationOption::where($product)->first()){
                $product['description']=$request->description;
                if($request->action=="add"){
                    InterestCalculationOption::create($product);
                }               
                $message="Day Convenion Created Successfully";
            } else{
                if($request->action=="update"){
                    $product['description']=$request->description;
                    InterestCalculationOption::where("id",$request->convention_id)->update($product);
                    $message="Day Convenion has been updated.";
                }else{
                    $message="Day Convenion has been updated.";
                }
                
            }
            DB::commit();
            return response()->json(['message'=>$message,'success'=>true], 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }

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
        $products = TradeCollateralBasket::all();

        if ($request->filled("disabled")) {
            $disabled = $request->input("disabled");
            $products->where("is_disabled", $disabled);
        }
        return $products;
    }
    public function addProduct(Request $request)
    {
       
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $message=null;
            $product = [
                'product_name' => $request->productName,
                'disabled_until' => changeDateFromLocalToUTC($request->disabledUntil),
                'is_disabled' => $request->disabled,
                'description' => $request->description
            ];
            // return  $product ;
            if(TradeProduct::create($product)){
                $message="Product Created Successfully";
            } else{
                $message="Product has not been Created.";
            }
            DB::commit();
            return response()->json(['message'=>$message,'success'=>true], 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function updateProduct(Request $request,$product_id){
        $product = TradeProduct::find(CustomEncoder::urlValueDecrypt($product_id));
        if(!$product){
            return response()->json([
                'success' => false,
                'message' => "Product not found"
            ],404);
        }

        try {
            DB::beginTransaction();
            $data = [
                'product_name' => $request->productName,
                'filter_key' =>$request->filter_key,
                'description' => $request->description
            ];
            if ($product->update($data)) {
                $message="Product Updated Successfully";
            }else{
                $message="Product could not be updated";
            }
            DB::commit();
            return response()->json(['message'=>$message,'success'=>true, 'data'=>$product], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }
    public function activteDeactivateProduct(Request $request,$product_id){
        $product = TradeProduct::find(CustomEncoder::urlValueDecrypt($product_id));
        if(!$product){
            return response()->json([
                'success' => false,
                'message' => "Product not found"
            ],404);
        }

        try {
            DB::beginTransaction();
            $data = [
                'is_disabled' => $request->disabled
            ];
            if ($request->filled("disabled_until")) {
                $data['disabled_until'] = changeDateFromLocalToUTC($request->disabledUntil);
            }

            $product->update($data);
            
            if ($request->disabled == 0) {
                $message="Product Deactivated Successfully";
            }else{
                $message="Product Activated Successfully";
            }
            DB::commit();
            return response()->json(['message'=>$message,'success'=>true, 'data'=>$product], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }
    public function addNewBasketTypes(Request $request){
        try {
            DB::beginTransaction();
            $message="";
            $status=true;
            $allTypes=[];
            $basketTypes = json_decode($request->basketTypes, true);           


            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['success' => false, 'error' => 'Invalid JSON format.'], 400);
            }
            $rules=[];
            if($request->action=="add"){
                $rules = [
                    '*.basketName' => 'required|string',
                ];
            }else if($request->action=="update"){
                $rules = [
                    '*.basketName' => 'required|string',
                    '*.id' => 'required',
                ];
            }    
            $validator = Validator::make($basketTypes, $rules);    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have errors in your data.',
                    'errors' => $validator->errors()
                ], 400);
            }            
            foreach($basketTypes as $basketType){
                if($request->action=="add"){
                    $tosave=[
                        'basket_name'=>$basketType['basketName'],
                        'basket_description'=>$basketType['basketDescription'],
                        'is_disabled'=>($basketType['disabled']) ? $basketType['disabled']:0,
                        'created_at'=>getUTCDateNow()
                       ];  
                     $cretaed = TradeBasketType::create($tosave);
                     array_push($allTypes,$cretaed);
                     $message = "Created ".sizeof($allTypes)." out of ".sizeof($basketTypes)." basket types";
                }else if($request->action=="update"){
                   $found=TradeBasketType::findOrFail(CustomEncoder::urlValueDecrypt($basketType['id']));
                   if($found){
                    $tosave=[
                        'basket_name'=>$basketType['basketName'],
                        'basket_description'=>$basketType['basketDescription'],
                        'is_disabled'=>$basketType['disabled'],
                        'updated_at'=>getUTCDateNow()
                       ]; 
                       if($found->update($tosave)){
                        array_push($allTypes,$found);
                       }
                   }
                   $message = "Updated ".sizeof($allTypes)." out of ".sizeof($basketTypes)." basket types";
                }
 

               
            }

         

            
            DB::commit();            
            return response()->json(['message'=>$message,'success'=>$status,'data'=>$allTypes,], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }
    public function addTradeCollateral(Request $request){
        try {
            DB::beginTransaction();
            $message="";
            $status=true;
            $allTypes=[];
            $basketTypes = json_decode($request->basketTypes, true);           


            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['success' => false, 'error' => 'Invalid JSON format.'], 400);
            }
            $rules=[];
            if($request->action=="add"){
                $rules = [
                    '*.collateralName' => 'required|string',
                ];
            }else if($request->action=="update"){
                $rules = [
                    '*.collateralName' => 'required|string',
                    '*.id' => 'required',
                ];
            }    
            $validator = Validator::make($basketTypes, $rules);    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have errors in your data.',
                    'errors' => $validator->errors()
                ], 400);
            }            
            foreach($basketTypes as $basketType){
                if($request->action=="add"){
                    $tosave=[
                        'collateral_name'=>$basketType['collateralName'],
                        'collateral_description'=>$basketType['collateralDescription'],
                        'is_disabled'=>($basketType['disabled']) ? $basketType['disabled']:0,
                        'created_at'=>getUTCDateNow()
                       ];  
                     $cretaed = TradeCollateral::create($tosave);
                     array_push($allTypes,$cretaed);
                     $message = "Created ".sizeof($allTypes)." out of ".sizeof($basketTypes)." Collaterals.";
                }else if($request->action=="update"){
                   $found=TradeCollateral::findOrFail(CustomEncoder::urlValueDecrypt($basketType['id']));
                   if($found){
                    $tosave=[
                        'collateral_name'=>$basketType['collateralName'],
                        'collateral_description'=>$basketType['collateralDescription'],
                        'is_disabled'=>$basketType['disabled'],
                        'updated_at'=>getUTCDateNow()
                       ]; 
                       if($found->update($tosave)){
                        array_push($allTypes,$found);
                       }
                   }
                   $message = "Updated ".sizeof($allTypes)." out of ".sizeof($basketTypes)." Collaterals";
                }
            }
            DB::commit();            
            return response()->json(['message'=>$message,'success'=>$status,'data'=>$allTypes,], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }
}

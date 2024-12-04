<?php

namespace App\Http\Controllers\Dashboard\Cg;

use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Services\CG\TradeCGService;
use App\Services\MTService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Validator;

class TradeCGController extends Controller
{

  protected $tradeservice;
  protected $mtService;
  public function __construct(TradeCGService $tradeservice, MTService $mtService)
  {
    $this->tradeservice = $tradeservice;
    $this->mtService = $mtService;
  }

  public function getTradeRequests(Request $request)
  {
    return $this->tradeservice->getTradeRequests($request);
  }
  public function getTradeRequest(Request $request)
  {
    return $this->tradeservice->getTradeRequest($request);
  }
  public function getOffers(Request $request)
  {

    return $this->tradeservice->getOffers($request);
  }
  public function getDeposits(Request $request)
  {
    return $this->tradeservice->getDeposits($request);
  }
  public function giveOffer(Request $request)
  {
    $requestdata = $request->all();
    $requestdata['invite'] = CustomEncoder::urlValueDecrypt($request->invite);

    $requestRules = [
      'offers' => 'required',
      'requestId' => 'required|string',
      'invite' => 'required|string|exists:c_t_trade_request_invited_c_g_s,id'
    ];
    $requestValidator = Validator::make($requestdata, $requestRules);
    if ($requestValidator->fails()) {
      return response()->json(['message' => 'Failed. You have errors in your data.', 'errors' => $requestValidator->errors()], 400);
    }

    // return $requestdata;
    // return $request->all();
    $offerss =  $this->tradeservice->giveOffer($request);
    return $offerss;
  }
  public function getTradeRequestOffer(Request $request)
  {
    return $this->tradeservice->getTradeRequestOffer($request);
  }
  public function getTradeRequestOffers(Request $request)
  {
    return $this->tradeservice->getTradeRequestOffers($request);
  }

  public function giveCounterOffer(Request $request)
  {
    $requestdata = $request->all();
    $requestdata['offerId'] = CustomEncoder::urlValueDecrypt($request->offerId);
    $requestRules = [
      'currency' => 'required|string',
      'investment_amount' => 'required|string',
      'settlementDate' => 'required|string',
      'convention_id'=> 'required',
      'rate_type' => 'required|string',
      'entered_rate' => 'required|string',
      'operator' => 'required|string',
      'offerId' => 'required|string|exists:c_t_trade_request_invited_c_g_s,id'
    ];

    $requestValidator = Validator::make($requestdata, $requestRules);
    if ($requestValidator->fails()) {
      return response()->json(['message' => 'Failed. You have errors in your data.', 'errors' => $requestValidator->errors()], 400);
    }

    // return $requestdata;
    // return $request->all();
    $offerss =  $this->tradeservice->giveCounterOffer($request);
    return $offerss;
  }
  public function respondCounterOffer(Request $request)
  {
    $offerss =  $this->tradeservice->actOnCounter($request);
    return $offerss;
  }
  public function editOffer(Request $request)
  {
    $requestdata = $request->all();
    $requestdata['offerId'] = CustomEncoder::urlValueDecrypt($request->offerId);
    $requestRules = [
      'collateralType' => 'required|string|in:bi,tri',
      'currency' => 'required|string',
      'rate_type' => 'required|string',
      'product' => 'required',
      'basket' => [
          'required_if:collateralType,tri',
          'numeric',
          function ($attribute, $value, $fail) {
              if ($value != 0 && !\DB::table('trade_tri_basket_third_parties')->where('id', $value)->exists()) {
                  $fail('The selected ' . $attribute . ' is invalid.');
              }
          },
      ],
      'collateral_id' => [
          'required_if:collateralType,bi',
          'numeric',
          function ($attribute, $value, $fail) {
              if ($value != 0 && !\DB::table('trade_organization_collateral_c_u_s_i_p_s')->where('id', $value)->exists()) {
                  $fail('The selected ' . $attribute . ' is invalid.');
              }
          },
      ],
      'term_length_type' => 'required',
      'term_length' => 'required',
      'entered_rate' => 'required|string',
      'operator' => 'required|string',
      'offerId' => 'required|string|exists:c_t_trade_request_invited_c_g_s,id',
  ];
  

    $requestValidator = Validator::make($requestdata, $requestRules);
    if ($requestValidator->fails()) {
      return response()->json(['message' => 'Failed. You have errors in your data.', 'errors' => $requestValidator->errors()], 400);
    }
    $offerss =  $this->tradeservice->editOffer($request);
    return $offerss;
  }


  public function withdrawOffer(Request $request)
  {
    $requestdata = $request->all();
    $requestdata['offerId'] = CustomEncoder::urlValueDecrypt($request->offerId);
    $requestRules = [
      'offerId' => 'required|string|exists:c_t_trade_request_invited_c_g_s,id'
    ];
    $requestValidator = Validator::make($requestdata, $requestRules);
    if ($requestValidator->fails()) {
      return response()->json(['message' => 'Failed. You have errors in your data.', 'errors' => $requestValidator->errors()], 400);
    }
    $offerss =  $this->tradeservice->withdrawOffer($request);
    return $offerss;
  }
  public function getPendingDeposits(Request $request)
  {
    $deposits =  $this->tradeservice->getPendingDeposits($request);
    return $deposits;
  }

  public function getPendingDeposit(Request $request)
  {
    $deposits =  $this->tradeservice->getPendingDeposit($request);
    return $deposits;
  }
  public function postTradeEvent(Request $request)
  {

    $tradeRequestRules = [
      'event_type' => 'required|string|in:cancel,all,extension,rate_change,exposure_change'
    ];
    $requestdata = $request->all();
    $requestValidator = Validator::make($requestdata, $tradeRequestRules);
    if ($requestValidator->fails()) {
      return response()->json(['success' => false, 'message' => 'Failed. You have errors in your data.', 'errors' => $requestValidator->errors()], 400);
    }
    if ($request->event_type == "extension") {
      $tradeRequestRules = [
        'old_maturity_date' => 'required',
        'new_maturity_date' => 'required'
      ];
    } else if ($request->event_type == "rate_change") {
      $tradeRequestRules = [
        'new_rate' => 'required',
        'old_rate' => 'required'
      ];
    } else if ($request->event_type == "cancel") {
      $tradeRequestRules = [
        'reason' => 'required'
      ];
    } else if ($request->event_type == "exposure_change") {
      $tradeRequestRules = [
        'old_purchase_value' => 'required',
        'new_purchase_value' => 'required'
      ];
    } else if ($request->event_type == "all") {
      $tradeRequestRules = [
        'old_maturity_date' => 'nullable',
        'new_maturity_date' => 'nullable',
        'old_rate' => 'nullable',
        'new_rate' => 'nullable',
        'old_purchase_value' => 'nullable',
        'new_purchase_value' => 'nullable',
      ];
      $requestValidator = Validator::make($request->all(), $tradeRequestRules);
      $requestValidator->after(function ($validator) use ($request) {
        $data = $request->all();
        $hasExtension = !empty($data['old_maturity_date']) && !empty($data['new_maturity_date']);
        $hasRateChange = !empty($data['old_rate']) && !empty($data['new_rate']);
        $hasExposureChange = !empty($data['old_purchase_value']) && !empty($data['new_purchase_value']);
        if (!$hasExtension && !$hasRateChange && !$hasExposureChange) {
          $validator->errors()->add('validation', 'Please provide atleast one valid option you want to change.');
        }
      });
      if ($requestValidator->fails()) {
        return response()->json([
          'success' => false,
          'message' => 'Failed. You have errors in your data.',
          'errors' => $requestValidator->errors()
        ], 400);
      }
    }
    $requestValidator = Validator::make($requestdata, $tradeRequestRules);
    if ($requestValidator->fails()) {
      return response()->json(['success' => false, 'message' => 'Failed. You have errors in your data.', 'errors' => $requestValidator->errors()], 400);
    }

    $deposits =  $this->tradeservice->postTradeEvent($request);
    return $deposits;
  }
  public function respondOnTradeEvent(Request $request)
  {
    $tradeRequestRules = [
      'batchNo' => 'required|string',
      'action' => 'required|string'
    ];
    $requestdata = $request->all();
    $requestValidator = Validator::make($requestdata, $tradeRequestRules);
    if ($requestValidator->fails()) {
      return response()->json(['success' => false, 'message' => 'Failed. You have errors in your data.', 'errors' => $requestValidator->errors()], 400);
    }
    $response =  $this->tradeservice->respondOnTradeEvent($request);
    return $response;
  }
  public function activateTrade(Request $request)
  {
    $tradeRequestRules = [
      'depositId' => 'required|string'
    ];
    $requestdata = $request->all();
    $requestValidator = Validator::make($requestdata, $tradeRequestRules);
    if ($requestValidator->fails()) {
      return response()->json(['success' => false, 'message' => 'Failed. You have errors in your data.', 'errors' => $requestValidator->errors()], 400);
    }
    $response =  $this->tradeservice->activateTrade($request); 
    return $response;
  }


  public function sendDepositMessage(Request $request)
  {
    $rules = [
      'depositId' => 'required',
    ];
    $messages = [
      'depositId.required' => 'Valid Deposit is required.',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => false], 400);
    }
    $response =  $this->tradeservice->sendDepositMessage($request);
    return $response;
  }
  public function getDepositMessages(Request $request)
  {

    $response =  $this->tradeservice->getDepositMessages($request);
    return $response;
  }

  public function markDepositMessageRead(Request $request)
  {
    $response =  $this->tradeservice->markDepositMessageRead($request);
    return $response;

  }

  public function sendOfferMessage(Request $request)
  {
    $rules = [
      'offerId' => 'required',
    ];
    $messages = [
      'offerId.required' => 'Valid offer is required.',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => false], 400);
    }
    $response =  $this->tradeservice->sendOfferMessage($request);
    return $response;
  }
  public function getOfferMessages(Request $request)
  {

    $response =  $this->tradeservice->getOfferMessages($request);
    return $response;
  }

  public function markOfferMessageRead(Request $request)
  {
    $response =  $this->tradeservice->markOfferMessageRead($request);
    return $response;

  }
  public function addBasket(Request $request){
    $response =  $this->tradeservice->addBasket($request);
    Log::info(json_encode($response));
    return $response;
  }
  public function addCounterPartiesToBasket(Request $request){
    $response =  $this->tradeservice->addCounterPartiesToBasket($request);
    return $response;
  }
  public function validateCounterPartyEntry(Request $request){
    $response =  $this->tradeservice->validateCounterPartyEntry($request);
    return $response;
  }
  public function validateBilateralCollateral(Request $request){
    $response =  $this->tradeservice->validateBilateralCollateral($request);
    return $response;
  }

  public function getBaskets(Request $request){
    $response =  $this->tradeservice->getBaskets($request);
    return $response;
  }

  public function getCollaterals(Request $request){
    $response =  $this->tradeservice->getCollaterals($request);
    return $response;
  }
  public function getBasket(Request $request){
    $response =  $this->tradeservice->getBasket($request);
    return $response;
  }
public function getBasketTriparty(Request $request){
  $response =  $this->tradeservice->getBasketTriparty($request);
  return $response;
}
  public function getCollateral(Request $request){
    $response =  $this->tradeservice->getCollateral($request);
    return $response;
  }
  
  public function getColleteralsIssuers(Request $request){
    $response =  $this->tradeservice->getColleteralsIssuers($request);
    return $response;
  }
  public function archiveThirdParty(Request $request){
    $response =  $this->tradeservice->archiveThirdParty($request);
    return $response;
  } 
  public function archiveOrganizationCollateral(Request $request){
    $response =  $this->tradeservice->archiveOrganizationCollateral($request);
    return $response;
  }

  public function addCusipToIssuer(Request $request){

    $response =  $this->tradeservice->addCusipToIssuer($request);
    return $response;
  }
  public function updateCusipToIssuer(Request $request){

    $response =  $this->tradeservice->updateCusipToIssuer($request);
    return $response;
  }
  
  public function requestForMoney(Request $request)
  {
   
    $response =  $this->tradeservice->requestForMoney($request);
    return $response;

  }
  public function getPublishedRequests(Request $request){
    $response =  $this->tradeservice->getPublishedRequests($request);
    return $response;
  }
  public function getPublishedRequestOffers(Request $request){
    $response =  $this->tradeservice->getPublishedRequestOffers($request);
    return $response;
  }

  public function getPublishedRequestsOffers(Request $request){
    $response =  $this->tradeservice->getPublishedRequestsOffers($request);
    return $response;
  }
  public function getPublishedRequestsOfferDetails(Request $request){
    $response =  $this->tradeservice->getPublishedRequestsOfferDetails($request);
    return $response;
  }

  public function getPublishedRequestsMaturedOffers(Request $request){
    $response =  $this->tradeservice->getPublishedRequestsMaturedOffers($request);
    return $response;
  }

  public function getOfferDetails(Request $request)
    {
        $response =  $this->tradeservice->getSingleMarketPlaceOffer($request);
        return $response;
    }   

    public function updateRequestOffer(Request $request)
    {
        $response =  $this->tradeservice->updateRequestOffer($request);
        return $response;
    }   
    public function withdrawRequestOffer(Request $request)
    {
        $response =  $this->tradeservice->withdrawRequestOffer($request);
        return $response;
    }  

    
  
  
  
}

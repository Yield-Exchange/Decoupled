<?php

namespace App\Http\Controllers\Dashboard\CT;

use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Services\CT\TradeCTService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Arr;

class TradeCTController extends Controller
{

    protected $tradeservice;

    public function __construct(TradeCTService $tradeservice)
    {
        $this->tradeservice = $tradeservice;
    }
    public function getTradeRequestOffer(Request $request)
    {
        return $this->tradeservice->getTradeRequestOffer($request);
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

    public function createRequest(Request $request)
    {
        $tradeRequests = json_decode($request->tradeRequests, true);
        $invited = json_decode($request->invited, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON format.'], 400);
        }
        $tradeRequestRules = [
            '*.currency' => 'required|string|in:CAD,USD,EUR',
            '*.investment_amount' => 'required|numeric|min:0',
            '*.preferred_collateral' => 'required|array|min:1',
            '*.term_length_type' => 'required|string|in:Days,Months,Years',
            '*.term_length' => 'required|integer|min:1'
            // '*.trade_date' => ['required', 'date_format:Y-m-d', function ($attribute, $value, $fail) {
            //     // if (strtotime($value) <= strtotime(date('Y-m-d'))) {
            //     //     $fail('The ' . $attribute . ' must be a date after today.');
            //     // }
            // }]
        ];
        $invitedRules = [
            '*' => 'integer|min:1',
        ];
        $tradeRequestValidator = Validator::make($tradeRequests, $tradeRequestRules);
        $invitedValidator = Validator::make($invited, $invitedRules);

        if ($tradeRequestValidator->fails() || $invitedValidator->fails()) {
            $message = "You have erros in your data.Please use the recomended data format.";

            $errors = array_merge(
                $tradeRequestValidator->errors()->toArray(),
                $invitedValidator->errors()->toArray()
            );
            // return response()->json(['error' => $errors], 400);

            return response()->json(['message' => $message, 'errors' => $errors], 400);
        }
        //save the data
        return $this->tradeservice->saveRequest($request);
        //save the data
    }
    public function updateRequest(Request $request)
    {
        //save the data
        //  return $request->all();
        return $this->tradeservice->updateRequest($request);
        //save the data
    }
    public function withdrawRequest(Request $request)
    {
        //save the data
        //    return $request->all();
        return $this->tradeservice->withdrawRequest($request);
        //save the data
    }
    public function getTradeRequestOffers(Request $request)
    {
        return $this->tradeservice->getTradeRequestOffers($request);
    }
    public function getTradeRequestInvitedCGS(Request $request)
    {
        return $this->tradeservice->getTradeRequestInvitedCGS($request);
    }

    public function giveCounterOffer(Request $request)
    {
        $requestdata = $request->all();
        $requestdata['offerId'] = CustomEncoder::urlValueDecrypt($request->offerId);

        $requestRules = [
            'currency' => 'required|string',
            'investment_amount' => 'required|string',
            'settlementDate' => 'required|string',
            'convention_id' => 'required',
            'rate_type' => 'required|string',
            'entered_rate' => 'required|string',
            'operator' => 'required|string',
            'offerId' => 'required|string|exists:c_t_trade_request_c_g_offers,id'
        ];

        $requestValidator = Validator::make($requestdata, $requestRules);
        if ($requestValidator->fails()) {
            return response()->json(['success' => false, 'message' => 'Failed. You have errors in your data.', 'errors' => $requestValidator->errors()], 400);
        }

        $offerss =  $this->tradeservice->giveCounterOffer($request);
        return $offerss;
    }
    public function selectOffers(Request $request)
    {
        $offerss =  $this->tradeservice->selectOffers($request);
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

    public function sendDepositMessage(Request $request)
    {
        $rules = [
            'depositId' => 'required',
        ];
        $messages = [
            'productName.required' => 'Deposit Id is required.',
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
    public function getPublishedRequests(Request $request)
    {
        $response =  $this->tradeservice->getPublishedRequests($request);
        return $response;
    }
    public function getPublishedRequestOffers(Request $request)
    {
        $response =  $this->tradeservice->getPublishedRequestOffers($request);
        return $response;
    }
    public function getPublishedRequestsOffers(Request $request)
    {
        $response =  $this->tradeservice->getPublishedRequestsOffers($request);
        return $response;
    }
    public function getOfferDetails(Request $request)
    {
        $response =  $this->tradeservice->getSingleMarketPlaceOffer($request);
        return $response;
    }    
    public function confirmMarketOffer(Request $request){
        $response =  $this->tradeservice->confirmMarketOffer($request);
        return $response;
    }
    public function counterOfferMarketOffer(Request $request){
        $response =  $this->tradeservice->counterOfferMarketOffer($request);
        return $response;
    }    
    public function  getOfferOtherRelatedProductsOffers(Request $request){
        $response =  $this->tradeservice->getOfferOtherRelatedProductsOffers($request);
        return $response;
    }
    public function  getOfferMyRelatedProductsOffers(Request $request){
        $response =  $this->tradeservice->getOfferMyRelatedProductsOffers($request);
        return $response;
    }
}

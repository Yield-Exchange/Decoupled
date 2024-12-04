<?php

namespace App\Services\CT;

use App\Constants;
use App\CustomEncoder;
use App\Events\CTTradeRequestOfferChatEvent;
use App\Events\CTTradeRequestOfferDepositChatEvent;
use App\Mail\AdminMails;
use App\Mail\CGSMails;
use App\Mail\CTSMails;
use App\Models\CGTradeRequest;
use App\Models\CGTradeRequestInvitedCTOffer;
use App\Models\CTRequestDepositTradeEvent;
use App\Models\CTTradeRequest;
use App\Models\CTTradeRequestCGOffer;
use App\Models\CTTradeRequestInvitedCG;
use App\Models\CTTradeRequestOfferChat;
use App\Models\CTTradeRequestOfferCounterOffer;
use App\Models\CTTradeRequestOfferDeposit;
use App\Models\CTTradeRequestOfferDepositChat;
use App\Models\CTTradeRequestPreferredCollateral;
use App\Models\Organization;
use App\Models\TradeProduct;
use App\Models\TradeCollateralBasket;
use Illuminate\Http\Request;
use App\Traits\UserTrait;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;

use Illuminate\Support\Facades\Log;

class CTHandleTradeMarketOfferService
{

    public function makeRequestFromOffer($offer, Request $request,$from)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $currentDateTime = Carbon::now('UTC');

           
            $tplusone = $currentDateTime->copy()->addHours(24);
            $tradereqsave['reference_no'] = generateTradeRequestReference();
            $tradereqsave['investment_amount'] = $request->investment_amount;
            $tradereqsave['term_length_type'] = $offer->offer_term_length_type;
            $tradereqsave['term_length'] = $offer->offer_term_length;
            $tradereqsave['trade_time'] = $tplusone;
            $tradereqsave['currency'] = $offer->currency;
            $tradereqsave['request_status'] =  ($from=="confirm")?"COMPLETED":"ACTIVE";
            $tradereqsave['organization_id'] = $offer->CGTradeRequestInvitedCT->organization_id;
            $tradereqsave['user_id'] = $user->id;
            $tradereqsave['settlement_date'] = $tplusone;
            $tradereqsave['interest_calculation_options_id'] = $offer->interest_calculation_options_id;
            $tradereqsave['c_g_trade_request_id'] = $offer->CGTradeRequest->id;
            $tradereqsave['c_g_trade_request_invited_c_t_offer_id'] = $offer->id;

            $ctreq = CTTradeRequest::create($tradereqsave);
        
            DB::commit();
            return $ctreq;
        } catch (\Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    public function makeRequestInvitedFromOffer($cgoffer, $ctrequest, $cgrequest,$from)
    {
        try {
            DB::beginTransaction();
            $invitedob['invitation_date'] = getUTCDateNow(true);
            $invitedob['organization_id'] =$cgoffer->CGTradeRequest->organization_id;
            $invitedob['c_t_trade_request_id'] = $ctrequest->id;
            $invitedob['c_g_trade_request_id'] = $cgoffer->c_g_trade_request->id;
            $invitedob['c_g_trade_request_invited_c_t_offer_id'] = $cgoffer->id;
            $invitedob['invitation_status'] =  "PARTICIPATED";
            $invitedRecord =  CTTradeRequestInvitedCG::create($invitedob);
            DB::commit();
            return $invitedRecord;
        } catch (\Exception $ex) {
            DB::rollback();
        }
    }
    public function makeRequestInvitedOfferFromOffer($cgoffer, $ctofferinvited, $ctRequest, Request $request,$from)
    {
        try {
            DB::beginTransaction();
            // return $cgoffer;
            $netInterestRate = 0;
            $offerobtosave['offer_reference_no'] = generateTradeOfferReference();
            $offerobtosave['invitation_id'] = $ctofferinvited->id;
            $offerobtosave['offer_minimum_amount'] = $cgoffer->offer_minimum_amount;
            $offerobtosave['offer_maximum_amount'] = $cgoffer->offer_maximum_amount;
            $offerobtosave['offer_trade_product_id'] = $cgoffer->offer_trade_product_id;
            $offerobtosave['trade_tri_basket_third_party_id'] = $cgoffer->trade_tri_basket_third_party_id;
            $offerobtosave['trade_organization_collateral_c_u_s_i_p_s_id'] = $cgoffer->trade_organization_collateral_c_u_s_i_p_s_id;
            $offerobtosave['offer_term_length_type'] = $cgoffer->offer_term_length_type;
            $offerobtosave['offer_term_length'] = $cgoffer->offer_term_length;
            $offerobtosave['trade_date'] = $cgoffer->trade_time;
            $offerobtosave['offer_status'] =($from=="confirm")? "SELECTED":"ACTIVE";
            $offerobtosave['rate_type'] = $cgoffer->rate_type;
            $offerobtosave['fixed_rate'] = $cgoffer->entered_rate;
            $netInterestRate = $cgoffer->offer_interest_rate;
            $offerobtosave['variable_rate_value'] = $cgoffer->variable_rate_value;
            if($cgoffer->rate_operator!=null){
                $offerobtosave['rate_operator'] = $cgoffer->rate_operator;
            }            
            $offerobtosave['fixed_rate'] = $cgoffer->fixed_rate;
            $offerobtosave['settlement_date'] = $ctRequest->settlement_date;
            $offerobtosave['interest_calculation_options_id'] = $cgoffer->interest_calculation_options_id;
            $offerobtosave['offer_interest_rate'] = $netInterestRate;
            $offerobtosave['c_g_trade_request_id'] = $cgoffer->CGTradeRequest->id;
            $offerobtosave['c_g_trade_request_invited_c_t_offer_id'] = $cgoffer->id;
            $savedoffer = CTTradeRequestCGOffer::create($offerobtosave);
            DB::commit();
            return $savedoffer;
        } catch (\Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    public function makeRequestInvitedOfferTradeFromOffer(Request $request, $ctRequestOffer,$ctRequest,$cgOffer,$from)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $tradedate = $ctRequestOffer->settlement_date;
            $maturityDate = Carbon::parse($tradedate);
            $newMaturityDate = null;

            if ($ctRequestOffer->offer_term_length_type == "MONTHS") {
                $newMaturityDate = $maturityDate->addMonths($ctRequestOffer->offer_term_length);
            } else if ($ctRequestOffer->offer_term_length_type == "DAYS") {
                $newMaturityDate = $maturityDate->addDays($ctRequestOffer->offer_term_length);
            }

            $depositob['c_t_trade_request_c_g_offer_id'] = $ctRequestOffer->id;
            $depositob['deposit_reference_no'] = generateRepoOfferContractID($ctRequest->reference_no);
            $depositob['offered_amount'] = $request->investment_amount;
            $depositob['trade_date'] = $tradedate;


            if ($ctRequestOffer->biColleteral != null) {
                if ($ctRequestOffer->biColleteral->is_dummy) {
                    $depositob['deposit_status'] = 'PENDING_DEPOSIT';
                } else {
                    $depositob['deposit_status'] = 'ACTIVE';
                    $depositob['maturity_date'] = $newMaturityDate;
                }
            }
            if ($ctRequestOffer->basket != null) {
                if ($ctRequestOffer->basket->is_dummy) {
                    $depositob['deposit_status'] = 'PENDING_DEPOSIT';
                } else {
                    $depositob['deposit_status'] = 'ACTIVE';
                    $depositob['maturity_date'] = $newMaturityDate;
                }
            }
            $depositob['created_by'] = $user->id;
            $depositob['c_g_trade_request_id'] = $cgOffer->CGTradeRequest->id;
            $depositob['c_g_trade_request_invited_c_t_offer_id'] = $cgOffer->id;
            $createdDepo =  CTTradeRequestOfferDeposit::create($depositob);
            if($createdDepo){
                CTTradeRequestCGOffer::where("invitation_id",$ctRequestOffer->invitation_id)->update(['offer_status'=>'SELECTED']);
            }
           if($from=="confirm"){
                $getOrg = CTTradeRequestInvitedCG::whereHas("offers", function ($query) {
                $query->whereIn("c_t_trade_request_c_g_offers.offer_status", ["SELECTED","ACTIVE"]);
            })->where('id', $ctRequestOffer->invitation_id)
                ->select(DB::raw('distinct organization_id'), 'id as invitation_id')
                ->first();

           $selectedoffers = CTTradeRequestCGOffer::with(['CTdeposit', 'product', 'basket', 'invitee', 'biColleteral'])->where("invitation_id", $ctRequestOffer->invitation_id)->where("offer_status", "SELECTED")->get();
           $cg = Organization::where("id", $getOrg->organization_id)->first();
           $this->sendCTRequestOfferSelectedNotificationToCG(['currency' => $ctRequestOffer->currency, 'selected_offers' => $selectedoffers, 'CTOrg' => $user->organization->name], $cg);

           //send mails
           $ct = Auth::user()->organization;
           $selectedoffers = CTTradeRequestCGOffer::with(['CTdeposit', 'product', 'basket', 'invitee', 'biColleteral'])->whereIn("id", [$ctRequestOffer->id])->where("offer_status", "SELECTED")->get();
           $this->sendCGRequestOfferSelectedNotificationToCT(['currency' => $ctRequestOffer->currency, 'selected_offers' => $selectedoffers, 'CTOrg' => $user->organization->name], $ct);
           //send mails
           $this->closeCGOfferOnClose($cgOffer->id);

           }

            DB::commit();
            return  $createdDepo;
        } catch (\Exception $ex) {
            DB::rollback();
            return $ex->getMessage();
        }
    }
    public function makeRequestInvitedOfferCounterOfferFromOffer($cgoffer, $ctOffer, $ctRequest, Request $request)
    {
        try {
            DB::beginTransaction();
            // return $cgoffer;
            $user = Auth()->user();
            $offer = CTTradeRequestCGOffer::with('invitee')->where("id",$ctOffer->id)->first();
        
            if ($offer) {
                $netInterestRate = 0;
                $offerobtosave['offer_id'] = $ctOffer->id;
                $offerobtosave['offer_reference_no'] = generateTradeOfferReference();
                $offerobtosave['offer_minimum_amount'] = $request->investment_amount;
                $offerobtosave['offer_maximum_amount'] = $request->investment_amount;
                $offerobtosave['requested_by_user_id'] = $user->id;
                $offerobtosave['requested_by_organization_id'] = $user->organization->id;
                $offerobtosave['status'] = "PENDING";
                $offerobtosave['rate_type'] = $request->rate_type;    
                $offerobtosave['settlement_date'] = $request->settlementDate;
                $offerobtosave['interest_calculation_options_id'] = $request->convention_id;    
                // $offerobtosave['trade_date'] = changeDateFromLocalToUTC($request->trade_date . " 11:59:59");
                // $offerobtosave['trade_settlement_period_id'] = $request->settlement_date;    
                if ($request->rate_type === 'fixed') {
                    $offerobtosave['variable_rate_value'] = 0.00;
                    $offerobtosave['fixed_rate'] = $request->entered_rate;
                    $netInterestRate = $request->entered_rate;
                } else {
                    $ratedetails = getSystemSettings($request->rate_type);
                    $offerobtosave['variable_rate_value'] = $ratedetails->value;
                    $offerobtosave['rate_operator'] = $request->operator;
                    $offerobtosave['fixed_rate'] = $request->entered_rate;
    
                    if ($request->operator == "+") {
                        $netInterestRate = floatval($ratedetails->value) + floatval($request->entered_rate);
                    } else if ($request->operator == "-") {
                        $netInterestRate = floatval($ratedetails->value) - floatval($request->entered_rate);
                    }
                }
                $offerobtosave['offer_interest_rate'] = $netInterestRate;
                $offerobtosave['created_at'] = getUTCDateNow();
                $offerobtosave['c_g_trade_request_id'] = $cgoffer->CGTradeRequest->id;
                $offerobtosave['c_g_trade_request_invited_c_t_offer_id'] = $cgoffer->id;
                CTTradeRequestOfferCounterOffer::where("offer_id", $offer->id)->where("status", "PENDING")->update(['status' => 'EDITED']);
                $counteroffer = CTTradeRequestOfferCounterOffer::create($offerobtosave);

              
                //send alert mail to CG
                $originaloffer = CTTradeRequestCGOffer::where("id", $ctOffer->id)->first();    
                $requestDetails = CTTradeRequest::join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
                    ->join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.invitation_id", "=", "c_t_trade_request_invited_c_g_s.id")
                    ->where("c_t_trade_request_c_g_offers.id",$ctOffer->id)
                    ->select("c_t_trade_requests.*", "c_t_trade_requests.organization_id as ct_org_id")->first();
                $ct = Organization::where("id", $requestDetails->ct_org_id)->first();
                $cg = $offer->invitee->organization;

                $theredetails = [
                    'currency' => $requestDetails->currency,
                    'counter_offer' => $counteroffer,
                    'original_offer' => $originaloffer,
                    'from' => $ct->name,
                    'to' => $cg->name
                ];    
             
                $this->sendCTRequestOfferCounterNotification($theredetails, $cg, "cg");
                $this->sendCTRequestOfferCounterNotification($theredetails, $ct, "ct");
                $this->sendCTRequestOfferCounterNotification($theredetails, $cg, "admin");

              

                DB::commit();
                return $theredetails ;
                //send alert mail to CG
                return response()->json(['success' => true, 'message' => 'Counter offer posted successfully', 'data' => $theredetails], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'offer not found'], 400);
            }
        
         
        } catch (\Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    public function sendCTRequestOfferCounterNotification($requestt, $cg, $type)
    {

        if ($type == "admin") {

            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Repo Counter Offer Received",
                'offerDetails' => $requestt,
                'user_type' => "CG"
            ], 'counterOfferRecieved'));
        } else if ($type == "cg") {

            Mail::to($cg->notifiableUsersEmails())->queue(new AdminMails([
                'subject' => "Repo Counter Offer Received",
                'offerDetails' => $requestt,
                'user_type' => "CG"
            ], 'counterOfferRecieved'));
        } else if ($type == "ct") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "New Trade Counter Offer Send",
                'offerDetails' => $requestt,
                'user_type' => "CG"
            ], 'counterOfferSent'));
        }
    }


    public function sendCTRequestOfferSelectedNotificationToCG($requestt, $cg)
    {

        Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
            'subject' => "Your Trade Offers Selected",
            'selectedOffers' => $requestt,
            'user_type' => "CG"
        ], 'offfersSelected'));
    }

    public function sendCGRequestOfferSelectedNotificationToCT($requestt, $ct)
    {

        Mail::to($ct->notifiableUsersEmails())->queue(new CTSMails([
            'subject' => "Offers You Selected",
            'selectedOffers' => $requestt,
            'user_type' => "CT"
        ], 'offfersSelected'));
    }
    public function CheckIfThereISExistingCounter($cgrequest,$checkactive=false){
          $found=CTTradeRequestOfferCounterOffer::where("c_g_trade_request_id",$cgrequest->CGTradeRequestInvitedCT->c_g_trade_request_id)
          ->where("c_g_trade_request_invited_c_t_offer_id",$cgrequest->id);
          if($checkactive){
            $found->whereIn("status",['PENDING',"ACCEPTED"]);
          }else{
            $found->where("status", "PENDING");
          }
          
          $found= $found->first();
          if( $found){
            return  $found;
          }
            return null;
          
    }
    public function closeCGOfferOnClose($cgOfferId){
        CGTradeRequestInvitedCTOffer::where("id",$cgOfferId)->update(['offer_status'=>'CLOSED_ON_PURCHASE']);
    }
}

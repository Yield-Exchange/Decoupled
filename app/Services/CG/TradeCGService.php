<?php

namespace App\Services\CG;

use App\Constants;
use App\CustomEncoder;
use App\Events\CTTradeRequestOfferChatEvent;
use App\Mail\AdminMails;
use App\Events\CTTradeRequestOfferDepositChatEvent;
use App\Mail\CGSMails;
use App\Mail\CTSMails;
use App\Models\CGTradeRequest;
use App\Models\CGTradeRequestInvitedCT;
use App\Models\CGTradeRequestInvitedCTOffer;
use App\Models\CTRequestDepositTradeEvent;
use App\Models\CTTradeRequest;
use App\Models\CTTradeRequestCGOffer;
use App\Models\CTTradeRequestInvitedCG;
use App\Models\CTTradeRequestOfferChat;
use App\Models\CTTradeRequestOfferCounterOffer;
use App\Models\CTTradeRequestOfferDeposit;
use App\Models\CTTradeRequestOfferDepositChat;
use App\Models\Organization;
use App\Models\TradeBasketType;
use App\Models\TradeCollateral;
use App\Models\TradeCollateralBasket;
use App\Models\TradeCollateralIssuer;
use App\Models\TradeOrganizationCollateral;
use App\Models\TradeOrganizationCollateralCUSIP;
use App\Models\TradeProduct;
use App\Models\TradeTriBasketThirdParty;
use App\Rules\BasketExistsIfNotZero;
use App\Rules\CollateralExistsIfNotZero;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\MTService;
use DateTime;
use Exception;

class TradeCGService
{
    protected $mtService;
    public function __construct(MTService $mtService)
    {
        $this->mtService = $mtService;
    }
    public function getTradeRequestOffer($request)
    {
        $user = Auth::user();
        $id = CustomEncoder::urlValueDecrypt($request->offerId);
        $offer = CTTradeRequestCGOffer::with(['product', 'basket', 'invitee'])
            ->where("id", $id)
            ->first();
        if ($offer) {
            return $offer;
        } else {
            return response()->json([], 404);
        }
    }
    public function getTradeRequests(Request $request)
    {
        $user = Auth::user();
        // return $user->organization->id;
        $type = $request->type;
        $reqs = CTTradeRequest::join("organizations", "organizations.id", "=", "c_t_trade_requests.organization_id")->with(['inviter' => function ($query) use ($user, $request) {}, 'invitedCGs' => function ($query) use ($user, $type) {

            $query->where('c_t_trade_request_invited_c_g_s.organization_id', $user->organization->id);
            if ($type == "new") {
                $query->whereIn('invitation_status', ['INVITED']);
            } else if ($type == "inProgress") {

                $query->whereIn('invitation_status', ['PARTICIPATED']);
            }
        }])
            ->whereHas('invitedCGs', function ($query) use ($user, $type) {
                $query->where('c_t_trade_request_invited_c_g_s.organization_id', $user->organization->id);
                if ($type == "new") {
                    $query->whereIn('invitation_status', ['INVITED']);
                } else if ($type == "inProgress") {
                    $query->whereIn('invitation_status', ['PARTICIPATED']);
                    $query->join('c_t_trade_request_c_g_offers','c_t_trade_request_c_g_offers.invitation_id','=','c_t_trade_request_invited_c_g_s.id');
                    $query->whereIn('c_t_trade_request_c_g_offers.offer_status', ['ACTIVE']);
                }
               
            });
        if ($request->filled("investor")) {
            $reqs->whereHas("inviter", function ($query) use ($request) {
                $query->whereIn("name", explode(",", $request->investor));
            });
        }
        //search
        if ($request->filled("search")) {
            $reqs->where(function ($query) use ($request) {

                $query->where("c_t_trade_requests.reference_no", "like", "%" . $request->search . "%");
                $query->orWhere("organizations.name", "like", "%" . $request->search . "%");
            });
        }
        //search
        if ($request->filled("trade_date")) {
            $trade_date_users = explode(",", $request->trade_date);
            if ($trade_date_users[0] != '0' && $trade_date_users[1] === '0') {
                $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                $reqs->where('c_t_trade_requests.trade_time', ">=", $startdate);
            } else if ($trade_date_users[0] === '0' && $trade_date_users[1] != '0') {
                $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                $reqs->where('c_t_trade_requests.trade_time', "<=", $enddate);
            } else if ($trade_date_users[0] != '0' && $trade_date_users[1] != '0') {
                $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                $reqs->whereBetween('c_t_trade_requests.trade_time', [$startdate, $enddate]);
            }
        }

        if ($request->filled("investment_amount")) {
            $investiment_amount = explode(",", $request->investment_amount);

            if ($investiment_amount[0] != '0' && $investiment_amount[1] === '0') {

                $reqs->where('c_t_trade_requests.investment_amount', ">=", $investiment_amount[0]);
            } else if ($investiment_amount[0] === '0' && $investiment_amount[1] != '0') {

                $reqs->where('c_t_trade_requests.investment_amount', "<=", $investiment_amount[1]);
            } else if ($investiment_amount[0] != '0' && $investiment_amount[1] != '0') {

                $reqs->whereBetween('c_t_trade_requests.investment_amount', [$investiment_amount[0], $investiment_amount[1]]);
            }
        }

        if ($request->filled("convention")) {
            $reqs->where("interest_calculation_options_id", $request->convention);
        }

        if ($request->filled("settlement")) {
            $settlementobject = explode(",", $request->settlement);
            if ((stripslashes($settlementobject[0]) != "") && (stripslashes($settlementobject[1]) != "")) {

                $dateString1 = $settlementobject[0];
                $dateString2 = $settlementobject[1];
                $mindate = new DateTime($dateString1);
                $maxdate = new DateTime($dateString2);
                $reqs->whereBetween(DB::raw('DATE(settlement_date)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
            } else {
                if (stripslashes($settlementobject[0]) != "") {
                    $dateString1 = $settlementobject[0];
                    $mindate = new DateTime($dateString1);
                    $reqs->where(DB::raw('DATE(settlement_date)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
                if (stripslashes($settlementobject[1]) != "") {
                    $dateString2 = $settlementobject[1];
                    $maxdate = new DateTime($dateString2);
                    $reqs->where(DB::raw('DATE(settlement_date)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
            }
        }

        if ($request->filled("term_length") && $request->filled("term_length_type")) {

            $termLengthType = $request->term_length_type;
            $explodedRequestInput = explode(",", $request->term_length);
            if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] === '0') {
                $closingdateFilter = [];
            } else {
                $reqs->where('c_t_trade_requests.term_length_type', $termLengthType);
                if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] === '0') {
                    $reqs->where('c_t_trade_requests.term_length', ">=", $explodedRequestInput[0]);
                } else if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] != '0') {
                    $reqs->where('c_t_trade_requests.term_length', "<=", $explodedRequestInput[1]);
                } else if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] != '0') {
                    $reqs->whereBetween('c_t_trade_requests.term_length', $explodedRequestInput);
                }
            }
        }
        if ($request->filled("posted_on")) {

            $posted_on_users = explode(",", $request->posted_on);

            if ($posted_on_users[0] != '0' && $posted_on_users[1] === '0') {
                $startdate = convertBackToUTC($posted_on_users[0] . " 23:59");
                $reqs->where('c_t_trade_requests.trade_time', ">=", $startdate);
            } else if ($posted_on_users[0] === '0' && $posted_on_users[1] != '0') {
                $enddate = convertBackToUTC($posted_on_users[1] . " 23:59");
                $reqs->where('c_t_trade_requests.trade_time', "<=", $enddate);
            } else if ($posted_on_users[0] != '0' && $posted_on_users[1] != '0') {
                $startdate = convertBackToUTC($posted_on_users[0] . " 23:59");
                $enddate = convertBackToUTC($posted_on_users[1] . " 23:59");
                $reqs->whereBetween('c_t_trade_requests.trade_time', [$startdate, $enddate]);
            }
        }
        $reqs->whereIn('request_status', ['ACTIVE', 'UNDER_REVIEW']);
        // $reqs->orderBy("c_t_trade_requests.id", "DESC");
        $reqs = $reqs->orderBy("c_t_trade_requests.id", "DESC")->select("c_t_trade_requests.*")->paginate($request->per_page ?? 10);
        return $reqs;
    }


    public function getTradeRequest(Request $request)
    {
        $user = Auth::user();
        // return $user->organization->id;
        $id = CustomEncoder::urlValueDecrypt($request->requestId);
        $req = CTTradeRequest::with(['inviter' => function ($query) use ($user) {}, 'invitedCGs' => function ($query) use ($user) {
            $query->where('c_t_trade_request_invited_c_g_s.organization_id', $user->organization->id);
            // $query->whereIn('invitation_status', ['INVITED', 'PARTICIPATED']);
        }])
            ->whereHas('invitedCGs', function ($query) use ($user) {
                $query->where('c_t_trade_request_invited_c_g_s.organization_id', $user->organization->id);
                // $query->whereIn('invitation_status', ['INVITED', 'PARTICIPATED']);
            })
            ->whereIn('request_status', ['ACTIVE', 'UNDER_REVIEW'])
            ->where('id', $id)
            ->first();

        if ($req) {
            return $req;
        } else {
            return response()->json([], 404);
        }
    }
    public function getDummyTripartyBasketId($ct, $primarybasketId)
    {
        $user = Auth::user();
        $doestheorgHaveThebasket = TradeCollateralBasket::with(['tradeBasketType'])->where("organization_id", $user->organization->id)
            ->where("trade_basket_type_id", $primarybasketId)->first();

        if ($doestheorgHaveThebasket != null) {

            //check if counterpartyExist            
            $triPartyEntry = generateDummyBasket($ct, $doestheorgHaveThebasket->id);
            return $triPartyEntry;
            //check if counterpartyExist

        } else {

            $basketInfo = [
                'trade_basket_type_id' => $primarybasketId,
                'currency' => "CAD",
                'type' => "tri",
                'organization_id' => $user->organization->id,
                'user_id' => $user->id,
                'rating' => "N/A"
            ];

            $basketInfo['created_at'] = getUTCTimeNow();
            $created_basket = TradeCollateralBasket::create($basketInfo);
            $triPartyEntry = generateDummyBasket($ct, $created_basket->id);
            return $triPartyEntry;
        }
    }
    public function getDummyBileteralId($primaryCollateralId)
    {
        $user = Auth::user();
        $col = TradeOrganizationCollateral::query();
        $col->where("organization_id", $user->organization->id);
        $col->whereHas("tradeOrganizationCUSSIP", function ($query) use ($primaryCollateralId) {
            $query->where("is_dummy", 1);
            $query->where("trade_collateral_id", $primaryCollateralId);
        });

        $doestheorgHaveThebasket = $col->first();

        if ($doestheorgHaveThebasket != null) {
            //check if counterpartyExist        
            $ob = $doestheorgHaveThebasket->tradeOrganizationCUSSIP;
            return  $ob[0];
            //check if counterpartyExist
        } else {
            $colObject = TradeCollateral::where("id", $primaryCollateralId)->first();

            $CUSIP_codegen = generateDummyOrgCollateral($colObject);

            return $CUSIP_codegen;
        }
    }
    public function giveOffer(Request $request)
    {


        $offers = json_decode($request->offers, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['success' => false, 'error' => 'Invalid JSON format.'], 400);
        }

        $rules = [
            '*.collateralType' => 'required|string|in:bi,tri',
            '*.product' => 'required|numeric|exists:trade_products,id',
            '*.basket' => [
                'required_if:*.collateralType,tri',
                'numeric',
                new BasketExistsIfNotZero,
            ],
            '*.collateral_id' =>  [
                'required_if:*.collateralType,bi',
                'numeric',
                new CollateralExistsIfNotZero,
            ],
            '*.rate_type' => "required|string|in:fixed,prime_rate,sofr,sonia,euribor,estr,tonar,saron,tibor,aonia",
            '*.currency' => 'required|string',
            '*.min' => 'required|numeric',
            '*.max' => 'required|numeric',
            '*.term_length_type' => 'required|string',
            '*.term_length' => 'required|numeric',
            '*.settlement_date' => 'required',
            '*.entered_rate' => 'required|numeric',
            '*.operator' => 'nullable|string'
        ];

        $validator = Validator::make($offers, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'You have errors in your data.',
                'errors' => $validator->errors()
            ], 400);
        }

        $validator = Validator::make($offers, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'You have errors in your data.',
                'errors' => $validator->errors()
            ], 400);
        }
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $offerobtosave['invitation_id'] = CustomEncoder::urlValueDecrypt($request->invite);
            $invitedetails = CTTradeRequestInvitedCG::where("id", $offerobtosave['invitation_id'])->first();
            $requestdetails = CTTradeRequest::with("inviter")->join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
                ->where("c_t_trade_request_invited_c_g_s.id", $offerobtosave['invitation_id'])
                ->select("c_t_trade_requests.*")
                ->first();
            $savedoffers = 0;
            $savedoffersArr = [];
            $savedoffersmessages = [];

            foreach ($offers as  $offer) {
                $netInterestRate = 0;
                $offerobtosave['offer_reference_no'] = generateTradeOfferReference();
                $offerobtosave['offer_minimum_amount'] = $offer['min'];
                $offerobtosave['offer_maximum_amount'] = $offer['max'];
                $offerobtosave['offer_trade_product_id'] = $offer['product'];
                if ($offer['collateralType'] == "tri") {

                    if ($offer['basket'] == 0 || $offer['basket'] == "0") {
                        $ct = $requestdetails->inviter;
                        $trade_tri_basket_third_party_id = $this->getDummyTripartyBasketId($ct, $offer['primaryBasket']);
                        $offerobtosave['trade_tri_basket_third_party_id'] = $trade_tri_basket_third_party_id->id;
                        // return  $trade_tri_basket_third_party_id;
                    } else {

                        $offerobtosave['trade_tri_basket_third_party_id'] = $offer['basket'];
                    }

                    $offerobtosave['trade_organization_collateral_c_u_s_i_p_s_id'] = "";
                } else if ($offer['collateralType'] == "bi") {

                    $offerobtosave['trade_tri_basket_third_party_id'] = "";

                    if ($offer['collateral_id'] == 0 || $offer['collateral_id'] == "0") {

                        $orgCollateralId = $this->getDummyBileteralId($offer['primaryCollateral']);
                        $offerobtosave['trade_organization_collateral_c_u_s_i_p_s_id'] = $orgCollateralId->id;
                    } else {

                        $offerobtosave['trade_organization_collateral_c_u_s_i_p_s_id'] = $offer['collateral_id'];
                    }
                }

                $offerobtosave['offer_term_length_type'] = $offer['term_length_type'];
                $offerobtosave['offer_term_length'] = $offer['term_length'];

                // $offerobtosave['trade_settlement_period_id'] = $offer['settlement_date'];
                $offerobtosave['trade_date'] = $requestdetails->trade_time;
                $offerobtosave['offer_status'] = "ACTIVE";
                $offerobtosave['rate_type'] = $offer['rate_type'];
                if ($offer['rate_type'] === 'fixed') {
                    $offerobtosave['variable_rate_value'] = 0.00;
                    $offerobtosave['fixed_rate'] = $offer['entered_rate'];
                    $netInterestRate = $offer['entered_rate'];
                } else {
                    $ratedetails = getSystemSettings($offer['rate_type']);
                    $offerobtosave['variable_rate_value'] = $ratedetails->value;
                    $offerobtosave['rate_operator'] = $offer['operator'];
                    $offerobtosave['fixed_rate'] = $offer['entered_rate'];

                    if ($offer['operator'] == "+") {
                        $netInterestRate =  floatval($ratedetails->value) + floatval($offer['entered_rate']);
                    } else if ($offer['operator'] == "-") {
                        $netInterestRate =  floatval($ratedetails->value) - floatval($offer['entered_rate']);
                    }
                }

                $offerobtosave['settlement_date'] = changeDateFromLocalToUTC($offer['settlement_date'] . " 11:59:59");
                $offerobtosave['interest_calculation_options_id'] = $offer['convention_id'];

                $offerobtosave['offer_interest_rate'] = $netInterestRate;
                $savedoffs = CTTradeRequestCGOffer::create($offerobtosave);
                if ($savedoffs) {

                    $savedoffers++;
                    array_push($savedoffersArr, $savedoffs);
                }
            }

            if ($savedoffers > 0) {
                //update initatation
                CTTradeRequestInvitedCG::where("id", $offerobtosave['invitation_id'])->update(['invitation_status' => 'PARTICIPATED']);
                //update invitation
            }

            //send offer update to CTs    
            $updatedrequest = CTTradeRequest::with(["invitedCGs"])->where("id", $invitedetails['c_t_trade_request_id'])->first();
            $ctdetails = Organization::where("id", $updatedrequest->organization_id)->first();

            $cgdetails = $user->organization;
            $offerDetails = [
                'request' => $requestdetails,
                'offers' => $savedoffersArr,
                'sender' => Auth::user()->organization,
                'ctdetails' => $ctdetails,
                'cgdetails' => $cgdetails
            ];
            $this->sendOfferMail($offerDetails, $ctdetails, "ct");
            $this->sendOfferMail($offerDetails, $cgdetails, "cg");
            $this->sendOfferMail($offerDetails, $cgdetails, "admin");
            //send offer update to CTS
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Saved ' . $savedoffers . ' out of ' . sizeof($offers),
                'data' =>  $offerDetails
            ]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }

    public function getOffers(Request $request)
    {
        $user = Auth()->user();
        $offers = CTTradeRequestCGOffer::with(['product', 'basket', 'invitee', 'counterOffers', 'biColleteral'])
            ->whereIn("offer_status", ["ACTIVE", "SELECTED", "NOT_SELECTED","OFFER_WITHDRAWN","REQUEST_WITHDRAWN"])
            ->whereHas("invitee", function ($query)  use ($user) {
                $query->whereHas("organization", function ($query) use ($user) {
                    $query->where("organizations.id", $user->organization->id);
                });
            });
        if ($request->type == "history") {

            $offers->whereIn('c_t_trade_request_c_g_offers.offer_status', ['REQUEST_WITHDRAWN', 'EXPIRED', 'OFFER_WITHDRAWN']);
        }
        //rate
        if ($request->filled("rate")) {

            $rate = explode(",", $request->rate);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $offers->where('c_t_trade_request_c_g_offers.offer_interest_rate', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $offers->where('c_t_trade_request_c_g_offers.offer_interest_rate', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {

                $offers->whereBetween('c_t_trade_request_c_g_offers.offer_interest_rate', [$rate[0], $rate[1]]);
            }
        }
        //rate
        if ($request->filled("trade_date")) {
            $trade_date_users = explode(",", $request->trade_date);
            if ($trade_date_users[0] != '0' && $trade_date_users[1] === '0') {
                $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                $offers->where('c_t_trade_request_c_g_offers.trade_date', ">=", $startdate);
            } else if ($trade_date_users[0] === '0' && $trade_date_users[1] != '0') {
                $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                $offers->where('c_t_trade_request_c_g_offers.trade_date', "<=", $enddate);
            } else if ($trade_date_users[0] != '0' && $trade_date_users[1] != '0') {
                $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                $offers->whereBetween('c_t_trade_request_c_g_offers.trade_date', [$startdate, $enddate]);
            }
        }

        if ($request->filled("convention")) {
            $offers->where("interest_calculation_options_id", $request->convention);
        }

        if ($request->filled("settlement")) {
            $settlementobject = explode(",", $request->settlement);
            if ((stripslashes($settlementobject[0]) != "") && (stripslashes($settlementobject[1]) != "")) {

                $dateString1 = $settlementobject[0];
                $dateString2 = $settlementobject[1];
                $mindate = new DateTime($dateString1);
                $maxdate = new DateTime($dateString2);
                $offers->whereBetween(DB::raw('DATE(settlement_date)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
            } else {
                if (stripslashes($settlementobject[0]) != "") {
                    $dateString1 = $settlementobject[0];
                    $mindate = new DateTime($dateString1);
                    $offers->where(DB::raw('DATE(settlement_date)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
                if (stripslashes($settlementobject[1]) != "") {
                    $dateString2 = $settlementobject[1];
                    $maxdate = new DateTime($dateString2);
                    $offers->where(DB::raw('DATE(settlement_date)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
            }
        }
        if ($request->filled("investment_amount")) {

            $offer_amount = explode(",", $request->investment_amount);

            if ($offer_amount[0] != '0') {

                $offers->where('c_t_trade_request_c_g_offers.offer_minimum_amount', ">=", $offer_amount[0]);
            }
            if ($offer_amount[1] != '0') {
                $offers->where('c_t_trade_request_c_g_offers.offer_maximum_amount', "<=", $offer_amount[1]);
                // $offers->where('c_t_trade_request_c_g_offers.offer_minimum_amount', ">=", $offer_amount[0]);
            }
        }
        // if ($request->filled("max_investment_amount")) {

        //     $offer_amount = explode(",", $request->max_investment_amount);

        //     if ($offer_amount[0] != '0' && $offer_amount[1] === '0') {

        //         $offers->where('c_t_trade_request_c_g_offers.offer_maximum_amount', ">=", $offer_amount[0]);
        //     } else if ($offer_amount[0] === '0' && $offer_amount[1] != '0') {

        //     } else if ($offer_amount[0] != '0' && $offer_amount[1] != '0') {

        //         $offers->whereBetween('c_t_trade_request_c_g_offers.offer_maximum_amount', [$offer_amount[0], $offer_amount[1]]);
        //     }
        // }
        $offers = $offers->orderBy("id", "DESC")->paginate($request->per_page ? $request->per_page : 10);
        return  $offers;
    }

    public function getTradeRequestOffers(Request $request)
    {
// return CustomEncoder::urlValueDecrypt($request->requestId);
        $user = Auth()->user();
        $offers = CTTradeRequestCGOffer::with(['product', 'basket', 'invitee', 'counterOffers', 'biColleteral']);
        if ($request->from == "review") {
            $offers->whereIn("offer_status", ['ACTIVE']);
        } else if ($request->from == "inprogress") {
        $offers->whereIn("offer_status", ['ACTIVE']);
        } else if ($request->from == "history") {
            $offers->whereIn("offer_status", ['REQUEST_WITHDRAWN', 'EXPIRED', 'OFFER_WITHDRAWN']);
        }
        $offers->whereHas('invitee', function ($query) use ($request, $user) {
            $query->where('c_t_trade_request_invited_c_g_s.c_t_trade_request_id', CustomEncoder::urlValueDecrypt($request->requestId));
            $query->where('c_t_trade_request_invited_c_g_s.organization_id', $user->organization->id);
        });

        if ($request->filled("convention")) {
            $offers->where("interest_calculation_options_id", $request->convention);
        }

        if ($request->filled("settlement")) {
            $settlementobject = explode(",", $request->settlement);
            if ((stripslashes($settlementobject[0]) != "") && (stripslashes($settlementobject[1]) != "")) {

                $dateString1 = $settlementobject[0];
                $dateString2 = $settlementobject[1];
                $mindate = new DateTime($dateString1);
                $maxdate = new DateTime($dateString2);
                $offers->whereBetween(DB::raw('DATE(settlement_date)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
            } else {
                if (stripslashes($settlementobject[0]) != "") {
                    $dateString1 = $settlementobject[0];
                    $mindate = new DateTime($dateString1);
                    $offers->where(DB::raw('DATE(settlement_date)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
                if (stripslashes($settlementobject[1]) != "") {
                    $dateString2 = $settlementobject[1];
                    $maxdate = new DateTime($dateString2);
                    $offers->where(DB::raw('DATE(settlement_date)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
            }
        }
        $offers = $offers->orderBy("id", "DESC")->get();
        return $offers;
    }
    public function giveCounterOffer(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth()->user();
            $offer = CTTradeRequestCGOffer::where("id", CustomEncoder::urlValueDecrypt($request->offerId))->first();
            $originalOffer = $offer;
            $invitedetails = CTTradeRequestInvitedCG::where("id", $offer['invitation_id'])->first();
            $requestdetails = CTTradeRequest::join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
                ->join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.invitation_id", "=", "c_t_trade_request_invited_c_g_s.id")
                ->where("c_t_trade_request_c_g_offers.id",  CustomEncoder::urlValueDecrypt($request->offerId))
                ->first();
            $responsedata = [];
            $code = 200;
            if ($offer &&  $requestdetails) {
                $netInterestRate = 0;
                $foundCounteroffer = CTTradeRequestOfferCounterOffer::where("offer_id", $offer->id)->first();


                $offerobtosave['offer_id'] = $offer->id;

                if ($foundCounteroffer) {
                    $offerobtosave['c_g_trade_request_id'] = $foundCounteroffer->c_g_trade_request_invited_c_t_offer_id;
                    $offerobtosave['c_g_trade_request_invited_c_t_offer_id'] = $foundCounteroffer->c_g_trade_request_invited_c_t_offer_id;
                }

                $offerobtosave['offer_reference_no'] = generateTradeOfferReference();
                $offerobtosave['offer_minimum_amount'] = $request->investment_amount;
                $offerobtosave['offer_maximum_amount'] = $request->investment_amount;
                $offerobtosave['requested_by_user_id'] = $user->id;
                $offerobtosave['requested_by_organization_id'] = $user->organization->id;
                $offerobtosave['status'] = "COUNTERED";
                $offerobtosave['rate_type'] = $request->rate_type;
                // $offerobtosave['trade_date'] = $requestdetails->trade_date;
                // $offerobtosave['trade_settlement_period_id'] = $request->settlement_date;
                $offerobtosave['settlement_date'] = $request->settlementDate;
                $offerobtosave['interest_calculation_options_id'] = $request->convention_id;

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
                        $netInterestRate =  floatval($ratedetails->value) + floatval($request->entered_rate);
                    } else if ($request->operator == "-") {
                        $netInterestRate =  floatval($ratedetails->value) - floatval($request->entered_rate);
                    }
                }
                $offerobtosave['offer_interest_rate'] = $netInterestRate;
                $offerobtosave['created_at'] = getUTCDateNow();
                CTTradeRequestOfferCounterOffer::where("offer_id", $offer->id)->where("status", "PENDING")->update(['status' => 'CLOSED_ON_COUNTERED']);
                $thecounter = CTTradeRequestOfferCounterOffer::create($offerobtosave);
                //update the offer
                $offerr = CTTradeRequestCGOffer::where("id", $thecounter->offer_id)->first();
                //
                if ($offerr) {
                    $actioning_user = Auth::user()->id;
                    archiveRepoTable($thecounter->offer_id, "trade_offers", $offerr, $actioning_user, "UPDATED FROM COUNTER OFFER ID.");
                }
                //
                $offerobtosave['offer_minimum_amount'] =  $thecounter->offer_minimum_amount;
                $offerobtosave['offer_maximum_amount'] = $thecounter->offer_minimum_amount;
                $offerobtosave['trade_settlement_period_id'] = $thecounter->trade_settlement_period_id;
                $offerobtosave['trade_date'] = $thecounter->trade_date;
                $offerobtosave['rate_type'] = $thecounter->rate_type;
                if ($thecounter->rate_type === 'fixed') {
                    $offerobtosave['variable_rate_value'] = 0.00;
                    $offerobtosave['fixed_rate'] = $thecounter->fixed_rate;
                    $netInterestRate =  $thecounter->offer_interest_rate;
                } else {
                    $offerobtosave['variable_rate_value'] =  $thecounter->variable_rate_value;
                    $offerobtosave['rate_operator'] =  $thecounter->rate_operator;
                    $offerobtosave['fixed_rate'] = $thecounter->fixed_rate;
                    $netInterestRate = $thecounter->offer_interest_rate;
                }
                $offerobtosave['offer_interest_rate'] = $netInterestRate;
                $offerr->update($offerobtosave);
                if ($offerr->c_g_trade_request_invited_c_t_offer_id != "") {
                    $this->updateCGOfferOnCounterAcceptance($thecounter, $offerr->c_g_trade_request_invited_c_t_offer_id);
                }
                //update the offer
                //send mails
                $ctdetails = Organization::where("id", $invitedetails->organization_id)->first();
                $originaloffer = CTTradeRequestCGOffer::where("id", CustomEncoder::urlValueDecrypt($request->offerId))->first();

                $requestDetails = CTTradeRequest::join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
                    ->join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.invitation_id", "=", "c_t_trade_request_invited_c_g_s.id")
                    ->where("c_t_trade_request_c_g_offers.id", CustomEncoder::urlValueDecrypt($request->offerId))
                    ->select("c_t_trade_requests.*", "c_t_trade_request_invited_c_g_s.organization_id as cg_org_id")->first();

                $cg = Organization::where("id", $requestDetails->cg_org_id)->first();
                //send counter offer mais
                $cg = $offer->invitee->organization;
                $ct = Organization::where("id", $requestDetails->organization_id)->first();
                $theredetails = [
                    'currency' => $requestDetails->currency,
                    'counter_offer' => $thecounter,
                    'original_offer' => $originalOffer,
                    'from' => $user->organization->name,
                    'to' => $ct->name,
                ];

                $this->sendCTRequestOfferCounterNotification($theredetails, $cg, "cg");
                $this->sendCTRequestOfferCounterNotification($theredetails, $ct, "ct");
                $this->sendCTRequestOfferCounterNotification($theredetails, $cg, "admin");

                // $this->sendCounterOfferMailToCT(['currency' => $requestDetails->currency, 'counter_offer' => $thecounter, 'original_offer' => $originalOffer], $ctdetails);
                //send mails

                $responsedata['success'] = true;
                $responsedata['message'] = 'Counter offer posted successfully';
                $responsedata['data'] = $theredetails;
                $code = 200;
            } else {
                $responsedata['success'] = true;
                $responsedata['message'] = 'Counter offer posted successfully';
                $responsedata['data'] = [];
                $code = 200;
            }
            DB::commit();
            return response()->json($responsedata, $code);
        } catch (\Exception $exp) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed.' . $exp->getMessage()], 400);
        }
    }
    public function updateCGOfferOnCounterAcceptance($thecounte, $cgID)
    {

        $offerobtosave['offer_minimum_amount'] =  $thecounte->offer_minimum_amount;
        $offerobtosave['offer_maximum_amount'] = $thecounte->offer_minimum_amount;
        $offerobtosave['trade_settlement_period_id'] = $thecounte->trade_settlement_period_id;
        $offerobtosave['trade_date'] = $thecounte->trade_date;
        $offerobtosave['rate_type'] = $thecounte->rate_type;
        if ($thecounte->rate_type === 'fixed') {
            $offerobtosave['variable_rate_value'] = 0.00;
            $offerobtosave['fixed_rate'] = $thecounte->fixed_rate;
            $netInterestRate =  $thecounte->offer_interest_rate;
        } else {
            $offerobtosave['variable_rate_value'] =  $thecounte->variable_rate_value;
            $offerobtosave['rate_operator'] =  $thecounte->rate_operator;
            $offerobtosave['fixed_rate'] = $thecounte->fixed_rate;
            $netInterestRate = $thecounte->offer_interest_rate;
        }
        $offerobtosave['offer_interest_rate'] = $netInterestRate;
        $offerr = CGTradeRequestInvitedCTOffer::where("id", $cgID)->first();
        if ($offerr) {
            $actioning_user = Auth::user()->id;
            archiveRepoTable($cgID, "cg_trade_offers", $offerr, $actioning_user, "UPDATED FROM COUNTER OFFER ACCEPTANCE.OFFER ID " . $thecounte->offer_id);
        }
        $offerr->update($offerobtosave);
        //update the offer


    }
    public function actOnCounter(Request $request)
    {
        $action = $request->action;
        $counterOfferId = CustomEncoder::urlValueDecrypt($request->counterOfferId);

        // return $counterOfferId;
        $thecounter = CTTradeRequestOfferCounterOffer::where("id", $counterOfferId)
            // ->where("status", "PENDING")
            ->first();
        if ($thecounter) {
            switch ($action) {
                case 'accept':
                    //change conter status
                    $thecounter->update(['status' => 'ACCEPTED']);
                    //change counter status
                    //archive the offer
                    //archive the offer
                    //update the offer
                    $offerr = CTTradeRequestCGOffer::where("id", $thecounter->offer_id)->first();
                    //
                    if ($offerr) {
                        $actioning_user = Auth::user()->id;
                        archiveRepoTable($thecounter->offer_id, "trade_offers", $offerr, $actioning_user, "UPDATED ON COUNTER OFFER ACCEPTANCE.OFFER ID " . $counterOfferId);
                    }
                    //
                    $offerobtosave['offer_minimum_amount'] =  $thecounter->offer_minimum_amount;
                    $offerobtosave['offer_maximum_amount'] = $thecounter->offer_minimum_amount;
                    $offerobtosave['trade_date'] = $thecounter->trade_date;
                    $offerobtosave['trade_settlement_period_id'] = $thecounter->trade_settlement_period_id;
                    $offerobtosave['rate_type'] = $thecounter->rate_type;

                    $offerobtosave['settlement_date'] = $thecounter->settlement_date;
                    $offerobtosave['interest_calculation_options_id'] = $thecounter->interest_calculation_options_id;

                    if ($thecounter->rate_type === 'fixed') {
                        $offerobtosave['variable_rate_value'] = 0.00;
                        $offerobtosave['fixed_rate'] = $thecounter->fixed_rate;
                        $netInterestRate =  $thecounter->fixed_rate;
                    } else {
                        $offerobtosave['variable_rate_value'] =  $thecounter->variable_rate_value;
                        $offerobtosave['rate_operator'] =  $thecounter->rate_operator;
                        $offerobtosave['fixed_rate'] = $thecounter->fixed_rate;
                        $netInterestRate = $thecounter->fixed_rate;
                    }
                    $offerobtosave['offer_interest_rate'] = $thecounter->offer_interest_rate;
                    $offerobtosave['c_g_trade_request_id'] = $thecounter->c_g_trade_request_id;
                    $offerobtosave['c_g_trade_request_invited_c_t_offer_id'] = $thecounter->c_g_trade_request_invited_c_t_offer_id;
                    $offerr->update($offerobtosave);
                    if ($thecounter->c_g_trade_request_invited_c_t_offer_id != "") {
                        $this->updateCGOfferOnCounterAcceptance($thecounter, $thecounter->c_g_trade_request_invited_c_t_offer_id);
                    }
                    //
                    $requestDetails = CTTradeRequest::join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
                        ->join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.invitation_id", "=", "c_t_trade_request_invited_c_g_s.id")
                        ->where("c_t_trade_request_c_g_offers.id",  $thecounter->offer_id)
                        ->select("c_t_trade_requests.*")->first();
                    $org = Auth::user()->organization;

                    $ctorg = Organization::where("id", $requestDetails->organization_id)->first();
                    $this->sendOfferAcceptanceEmail($offerr, $ctorg, "ct");
                    $this->sendOfferAcceptanceEmail($offerr, $org, "cg");

                    $this->sendOfferAcceptanceEmail($offerr, $org, "admin");
                    //
                    //update the offer
                    systemActivities(Auth::id(), json_encode($request->query()), "Counter offer accepted successfully");
                    return response()->json([
                        'success' => true,
                        'message' => 'Counter offer accepted successfully',
                        'data' => [
                            'offers' => $offerr,
                            'ctorg' => $ctorg,
                            'cgorg' => $org
                        ]
                    ], 200);
                    break;
                case 'decline':
                    $thecounter->update(['status' => 'DECLINED']);
                    systemActivities(Auth::id(), json_encode($request->query()), "Counter offer declined successfully");
                    $org = Auth::user()->organization;
                    $offerr = CTTradeRequestCGOffer::where("id", $thecounter->offer_id)->first();
                    $requestDetails = CTTradeRequest::join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
                        ->join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.invitation_id", "=", "c_t_trade_request_invited_c_g_s.id")
                        ->where("c_t_trade_request_c_g_offers.id",  $thecounter->offer_id)
                        ->select("c_t_trade_requests.*")->first();
                    $org = Auth::user()->organization;
                    $ctorg = Organization::where("id", $requestDetails->organization_id)->first();

                    $this->sendOfferDeclinedEmail($offerr, $ctorg, "ct");
                    $this->sendOfferDeclinedEmail($offerr, $org, "cg");


                    return response()->json([
                        'success' => true,
                        'message' => 'Counter offer declined successfully',
                        'data' => [
                            'offers' => $offerr,
                            'ctorg' => $ctorg,
                            'cgorg' => $org
                        ]
                    ], 200);
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Failed, action not found', 'data' => []]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'The counter offer is expired , already accepted or declined'], 400);
        }
    }
    public function editOffer(Request $request)
    {
        try {
            DB::beginTransaction();
            $offerid = CustomEncoder::urlValueDecrypt($request->offerId);

            $requestdetails = CTTradeRequest::join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
                ->join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.invitation_id", "=", "c_t_trade_request_invited_c_g_s.id")
                ->where("c_t_trade_request_c_g_offers.id", $offerid)
                ->first();
            $offerobtosave['offer_minimum_amount'] = $request['min'];
            $offerobtosave['offer_maximum_amount'] = $request['max'];
            $offerobtosave['offer_trade_product_id'] = $request['product'];

            if ($request['collateralType'] == "tri") {
                if ($request->basket == 0 || $request->basket == "0") {
                    $ct = $requestdetails->inviter;
                    $trade_tri_basket_third_party_id = $this->getDummyTripartyBasketId($ct, $request->primaryBasket);
                    $offerobtosave['trade_tri_basket_third_party_id'] = $trade_tri_basket_third_party_id->id;
                    // return  $trade_tri_basket_third_party_id;
                } else {

                    $offerobtosave['trade_tri_basket_third_party_id'] = $request->basket;
                }

                $offerobtosave['trade_organization_collateral_c_u_s_i_p_s_id'] = "";
            } else if ($request['collateralType'] == "bi") {
                if ($request->collateral_id == 0 || $request->collateral_id == "0") {

                    $orgCollateralId = $this->getDummyBileteralId($request->primaryCollateral);
                    $offerobtosave['trade_organization_collateral_c_u_s_i_p_s_id'] = $orgCollateralId->id;
                } else {

                    $offerobtosave['trade_organization_collateral_c_u_s_i_p_s_id'] = $request->collateral_id;
                }
                $offerobtosave['trade_tri_basket_third_party_id'] = "";
                $offerobtosave['trade_organization_collateral_id'] = $request['collateral_id'];
            }

            $offerobtosave['offer_term_length_type'] = $request['term_length_type'];
            $offerobtosave['offer_term_length'] = $request['term_length'];
            // $offerobtosave['trade_settlement_period_id'] = $request['settlement_date'];
            $offerobtosave['trade_date'] = $requestdetails->trade_time;
            $offerobtosave['offer_status'] = "ACTIVE";
            $offerobtosave['rate_type'] = $request['rate_type'];

            $offerobtosave['settlement_date'] = $request['settlementDate'];
            $offerobtosave['interest_calculation_options_id'] = $request['convention_id'];

            if ($request['rate_type'] === 'fixed') {
                $offerobtosave['variable_rate_value'] = 0.00;
                $offerobtosave['fixed_rate'] = $request['entered_rate'];
                $netInterestRate = $request['entered_rate'];
            } else {
                $ratedetails = getSystemSettings($request['rate_type']);
                $offerobtosave['variable_rate_value'] = $ratedetails->value;
                $offerobtosave['rate_operator'] = $request['operator'];
                $offerobtosave['fixed_rate'] = $request['entered_rate'];
                if ($request['operator'] == "+") {
                    $netInterestRate =  floatval($ratedetails->value) + floatval($request['entered_rate']);
                } else if ($request['operator'] == "-") {
                    $netInterestRate =  floatval($ratedetails->value) - floatval($request['entered_rate']);
                }
            }
            $offerobtosave['offer_interest_rate'] = $netInterestRate;
            $offertoup = CTTradeRequestCGOffer::with(['product'])->where("offer_status", "ACTIVE")->find($offerid);

            $oldoffer = CTTradeRequestCGOffer::with(['product'])->where("offer_status", "ACTIVE")->find($offerid);
            $message = "Offer updated Successfully";
            $resstatus = true;
            $emaildata = [];
            if ($offertoup == null) {
                $message = "Offer was not updated Successfully.Either the offer has expired or it does not exists.";
                $resstatus = false;
            } else {
                //
                if ($offertoup) {
                    $actioning_user = Auth::user()->id;
                    archiveRepoTable($offerid, "trade_offers", $offertoup, $actioning_user, "UPDATED ON EDITING OF OFFER");
                }
                //
                $offertoup->update($offerobtosave);
                //send mail  
                $message = "Offer was updated Successfully.";
                $resstatus = true;

                $emaildata['afterEdit'] = $offertoup;
                $emaildata['beforeEdit'] = $oldoffer;
                $cginfo = $offertoup->invitee->organization;
                $ctinfo = $offertoup->c_t_trade_request->inviter;
                $this->sendEditOfferMail($emaildata, $cginfo, "cg");
                $this->sendEditOfferMail($emaildata, $ctinfo, "ct");
                $this->sendEditOfferMail($emaildata, $cginfo, "admin");
                //send mail
            }
            DB::commit();
            return response()->json([
                'success' => $resstatus,
                'message' =>  $message,
                'data' => $emaildata
            ]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function withdrawOffer(Request $request)
    {
        try {
            DB::beginTransaction();
            $offerid = CustomEncoder::urlValueDecrypt($request->offerId);
            $offer = CTTradeRequestCGOffer::where("id", $offerid)
                // ->where("offer_status", "ACTIVE")
                ->first();
            $withdrawmessage = "Offer Withdrawn successfully";
            $withdrawstatus = true;
            if ($offer) {

                //
                if ($offer) {
                    $actioning_user = Auth::user()->id;
                    archiveRepoTable($request->offerId, "trade_offers", $offer, $actioning_user, "Offer Withdrawn.");
                }
                //
                //update status
                $offer->update(['offer_status' => 'OFFER_WITHDRAWN']);
                //update status

                //count remaining offers

                $existingoffers = CTTradeRequestCGOffer::where("invitation_id", $offer->invitation_id)
                    ->whereIn("offer_status", ['ACTIVE', 'EXPIRED', 'NOT_SELECTED'])->get();

                if (sizeof($existingoffers) < 1) {

                    CTTradeRequestInvitedCG::where("id", $offer->invitation_id)->update(['invitation_status' => 'INVITED']);
                }

                //count remaining offers

                //send mails

                $org = $offer->c_t_trade_request->inviter;
                $cg = $offer->invitee->organization;
                $this->sendOfferWithdrawalTo($offer, $org, "ct");
                $this->sendOfferWithdrawalTo($offer, $cg, "cg");
                $this->sendOfferWithdrawalTo($offer, $org, "admin");

                //send mails

            } else {
                systemActivities(Auth::id(), json_encode($request->query()), "Pending Repo Deposits  -> Withdraw, Failed.. offer not found");
                $withdrawmessage = "Either the offer is inactive or its no longer available.";
                $withdrawstatus - false;
            }

            DB::commit();
            return response()->json([
                'success' => $withdrawstatus,
                'message' => $withdrawmessage,
                'data' => []
            ]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function sendOfferWithdrawnEmailToCT($request, $org)
    {
        Mail::to($org->notifiableUsersEmails())->queue(new CTSMails([
            'subject' => "Counter Offer Accepted",
            'ct_Request' => $request,
            'user_type' => "CG"
        ], 'offerWithdrawn'));
    }

    public function sendAcceptedCounterEmailToCT($request, $org)
    {
        Mail::to($org->notifiableUsersEmails())->queue(new CTSMails([
            'subject' => "Counter Offer Accepted",
            'ct_Request' => $request,
            'user_type' => "CG"
        ], 'counterOfferAccepted'));
    }


    public function sendDeniedCounterEmailToCT($request, $org)
    {
        Mail::to($org->notifiableUsersEmails())->queue(new CTSMails([
            'subject' => "Counter Offer Denied",
            'ct_Request' => $request,
            'user_type' => "CG"
        ], 'counterOfferDenied'));
    }

    public function sendOfferMail($request, $org, $to)
    {

        if ($to == "cg") {
            Mail::to($org->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "Request Offer sent!",
                'ct_Request' => $request,
                'user_type' => "CG"
            ], 'sendOffers'));
        } else if ($to == "ct") {
            Mail::to($org->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "New Trade Offer Received.",
                'ct_Request' => $request,
                'user_type' => "CG"
            ], 'receivedOffers'));
        } else if ($to == "admin") {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "New Trade Offer Sent To CT",
                'ct_Request' => $request,
                'user_type' => "CG"
            ], 'receivedOffers'));
        }
    }
    public function sendEditOfferMail($request, $org, $to)
    {

        if ($to == "cg") {
            Mail::to($org->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "Repo Offer Editted!",
                'ct_Request' => $request,
                'user_type' => "CG"
            ], 'offerEditted'));
        } else if ($to == "ct") {
            Mail::to($org->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "Repo Offer Editted!",
                'ct_Request' => $request,
                'user_type' => "CG"
            ], 'offerEditted'));
        } else if ($to == "admin") {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Repo Offer Editted!",
                'ct_Request' => $request,
                'user_type' => "CG"
            ], 'offerEditted'));
        }
    }
    public function sendRequestProcessingMail($request, $org, $to)
    {

        if ($to == "cg") {
            Mail::to($org->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "Repo Processing Initiated!",
                'ct_Request' => $request,
                'user_type' => "CG"
            ], 'tradeProcessingStarted'));
        } else if ($to == "ct") {
            Mail::to($org->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "Repo Processing Initiated!",
                'ct_Request' => $request,
                'user_type' => "CG"
            ], 'tradeProcessingStarted'));
        } else if ($to == "admin") {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Repo Processing Initiated!",
                'ct_Request' => $request,
                'user_type' => "CG"
            ], 'tradeProcessingStarted'));
        }
    }
    public function sendCTRequestOfferCounterNotification($requestt, $cg, $type)
    {
        if ($type == "admin") {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Repo Counter Offer Shared",
                'offerDetails' => $requestt,
                'user_type' => "CG"
            ], 'counterOfferRecieved'));
        } else if ($type == "cg") {

            Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "Repo Counter Offer Send",
                'offerDetails' => $requestt,
                'user_type' => "CG"
            ], 'counterOfferSent'));
        } else if ($type == "ct") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "Repo Counter Offer Received",
                'offerDetails' => $requestt,
                'user_type' => "CG"
            ], 'counterOfferRecieved'));
        }
    }


    public function sendCounterOfferActionMailToCT($request, $org, $action)
    {
        Mail::to($org->notifiableUsersEmails())->queue(new CTSMails([
            'subject' => "New Trade Request",
            'ct_Request' => $request,
            'user_type' => "CG"
        ], 'receivedCounterOffer'));
    }
    public function getDeposits(Request $request)
    {
        $user = Auth()->user();
        $deposits = CTTradeRequestOfferDeposit::join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.id", "=", "c_t_trade_request_offer_deposits.c_t_trade_request_c_g_offer_id")
            ->join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.id", "=", "c_t_trade_request_c_g_offers.invitation_id")
            ->join("c_t_trade_requests", "c_t_trade_requests.id", "=", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id")



            ->join("organizations", "organizations.id", "=", "c_t_trade_requests.organization_id")
            ->with("CGOffer")
            ->whereHas("CGOffer", function ($query) use ($user, $request) {
                //rate
                if ($request->filled("rate")) {

                    $rate = explode(",", $request->rate);

                    if ($rate[0] != '0' && $rate[1] === '0') {

                        $query->where('c_t_trade_request_c_g_offers.offer_interest_rate', ">=", $rate[0]);
                    } else if ($rate[0] === '0' && $rate[1] != '0') {

                        $query->where('c_t_trade_request_c_g_offers.offer_interest_rate', "<=", $rate[1]);
                    } else if ($rate[0] != '0' && $rate[1] != '0') {

                        $query->whereBetween('c_t_trade_request_c_g_offers.offer_interest_rate', [$rate[0], $rate[1]]);
                    }
                }

                if ($request->filled("convention")) {
                    $query->where("interest_calculation_options_id", $request->convention);
                }

                if ($request->filled("investor")) {
                    $query->whereHas("invitee", function ($query1) use ($request) {
                        $query1->whereHas("ctTradeRequest", function ($query2) use ($request) {
                            $query2->whereHas("inviter", function ($query3) use ($request) {
                                $query3->whereIn("name", explode(",", $request->investor));
                            });
                        });
                    });
                }

                //rate
                if ($request->filled("settlement")) {
                    $settlementobject = explode(",", $request->settlement);
                    if ((stripslashes($settlementobject[0]) != "") && (stripslashes($settlementobject[1]) != "")) {

                        $dateString1 = $settlementobject[0];
                        $dateString2 = $settlementobject[1];
                        $mindate = new DateTime($dateString1);
                        $maxdate = new DateTime($dateString2);
                        $query->whereBetween(DB::raw('DATE(settlement_date)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
                    } else {
                        if (stripslashes($settlementobject[0]) != "") {
                            $dateString1 = $settlementobject[0];
                            $mindate = new DateTime($dateString1);
                            $query->where(DB::raw('DATE(settlement_date)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                        }
                        if (stripslashes($settlementobject[1]) != "") {
                            $dateString2 = $settlementobject[1];
                            $maxdate = new DateTime($dateString2);
                            $query->where(DB::raw('DATE(settlement_date)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                        }
                    }
                }
                //term_length
                if ($request->filled("term_length") && $request->filled("term_length_type")) {
                    $termLengthType = $request->term_length_type;
                    $explodedRequestInput = explode(",", $request->term_length);
                    if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] === '0') {
                        $closingdateFilter = [];
                    } else {
                        $query->where('c_t_trade_request_c_g_offers.offer_term_length', $termLengthType);
                        if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] === '0') {
                            $query->where('c_t_trade_request_c_g_offers.offer_term_length', ">=", $explodedRequestInput[0]);
                        } else if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] != '0') {
                            $query->where('c_t_trade_request_c_g_offers.offer_term_length', "<=", $explodedRequestInput[1]);
                        } else if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] != '0') {
                            $query->whereBetween('c_t_trade_request_c_g_offers.offer_term_length', $explodedRequestInput);
                        }
                    }
                }
                //term_length
                if ($request->filled("trade_date")) {
                    $trade_date_users = explode(",", $request->trade_date);
                    if ($trade_date_users[0] != '0' && $trade_date_users[1] === '0') {
                        $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                        $query->where('c_t_trade_request_c_g_offers.trade_date', ">=", $startdate);
                    } else if ($trade_date_users[0] === '0' && $trade_date_users[1] != '0') {
                        $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                        $query->where('c_t_trade_request_c_g_offers.trade_date', "<=", $enddate);
                    } else if ($trade_date_users[0] != '0' && $trade_date_users[1] != '0') {
                        $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                        $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                        $query->whereBetween('c_t_trade_request_c_g_offers.trade_date', [$startdate, $enddate]);
                    }
                }
                $query->whereHas("invitee", function ($query) use ($user) {
                    $query->whereHas("organization", function ($query) use ($user) {
                        $query->where("id", $user->organization->id);
                    });
                });
            });
        if ($request->type == "pending") {
            $deposits->whereIn('c_t_trade_request_offer_deposits.deposit_status', ['PENDING_DEPOSIT', 'INITIATED']);
        } elseif ($request->type == "active") {

            $deposits->whereIn('c_t_trade_request_offer_deposits.deposit_status', ['ACTIVE']);
        } elseif ($request->type == "history") {

            $deposits->whereIn('c_t_trade_request_offer_deposits.deposit_status', ['MATURED', 'WITHDRAWN', 'INCOMPLETE', 'EARLY_REDEMPTION', 'CANCELLED']);
        }
        //search
        if ($request->filled("search")) {
            $deposits->leftJoin("trade_organization_collateral_c_u_s_i_p_s", "trade_organization_collateral_c_u_s_i_p_s.id", "=", "c_t_trade_request_c_g_offers.trade_organization_collateral_c_u_s_i_p_s_id")
                ->leftJoin("trade_organization_collaterals", "trade_organization_collaterals.id", "=", "trade_organization_collateral_c_u_s_i_p_s.trade_organization_collateral_id")
                ->leftJoin("trade_collaterals", "trade_collaterals.id", "=", "trade_organization_collateral_c_u_s_i_p_s.trade_collateral_id")
                ->leftJoin("trade_tri_basket_third_parties", "trade_tri_basket_third_parties.id", "=", "c_t_trade_request_c_g_offers.trade_tri_basket_third_party_id")
                ->leftJoin("trade_collateral_baskets", "trade_collateral_baskets.id", "=", "trade_tri_basket_third_parties.trade_collateral_basket_id")
                ->leftJoin("trade_basket_types", "trade_basket_types.id", "=", "trade_collateral_baskets.trade_basket_type_id")
                ->join("trade_products", "trade_products.id", "=", "c_t_trade_request_c_g_offers.offer_trade_product_id")
                ->where(function ($query) use ($request) {
                    $query->where("c_t_trade_request_offer_deposits.deposit_reference_no", "like", "%" . $request->search . "%");
                    $query->orWhere("organizations.name", "like", "%" . $request->search . "%");
                    $query->orWhere("trade_collaterals.collateral_name", "like", "%" . $request->search . "%");
                    $query->orWhere("trade_basket_types.basket_name", "like", "%" . $request->search . "%");
                    $query->orWhere("organizations.name", "like", "%" . $request->search . "%");
                    $query->orWhere("trade_products.product_name", "like", "%" . $request->search . "%");
                });
        }
        //search

        if ($request->filled("investment_amount")) {

            $deposit_amount = explode(",", $request->investment_amount);

            if ($deposit_amount[0] != '0' && $deposit_amount[1] === '0') {

                $deposits->where('c_t_trade_request_offer_deposits.offered_amount', ">=", $deposit_amount[0]);
            } else if ($deposit_amount[0] === '0' && $deposit_amount[1] != '0') {

                $deposits->where('c_t_trade_request_offer_deposits.offered_amount', "<=", $deposit_amount[1]);
            } else if ($deposit_amount[0] != '0' && $deposit_amount[1] != '0') {

                $deposits->whereBetween('c_t_trade_request_offer_deposits.offered_amount', [$deposit_amount[0], $deposit_amount[1]]);
            }
        }

        if ($request->filled("awarded_at")) {
            $posted_on_users = explode(",", $request->awarded_at);
            if ($posted_on_users[0] != '0' && $posted_on_users[1] === '0') {
                $startdate = convertBackToUTC($posted_on_users[0] . " 23:59");
                $deposits->where('c_t_trade_request_offer_deposits.created_at', ">=", $startdate);
            } else if ($posted_on_users[0] === '0' && $posted_on_users[1] != '0') {
                $enddate = convertBackToUTC($posted_on_users[1] . " 23:59");
                $deposits->where('c_t_trade_request_offer_deposits.created_at', "<=", $enddate);
            } else if ($posted_on_users[0] != '0' && $posted_on_users[1] != '0') {
                $startdate = convertBackToUTC($posted_on_users[0] . " 23:59");
                $enddate = convertBackToUTC($posted_on_users[1] . " 23:59");
                $deposits->whereBetween('c_t_trade_request_offer_deposits.created_at', [$startdate, $enddate]);
            }
        }


        $deposits = $deposits->orderBy("c_t_trade_request_offer_deposits.id", "DESC")
            ->select("c_t_trade_request_offer_deposits.*")
            ->paginate($request->per_page ? $request->per_page : 10);
        return  $deposits;
    }
    public function getPendingDeposits(Request $request)
    {
        $user = Auth()->user();
        $deposits = CTTradeRequestOfferDeposit::with("CGOffer")
            ->whereHas("CGOffer", function ($query) use ($user, $request) {
                if ($request->filled("convention")) {
                    $query->where("interest_calculation_options_id", $request->convention);
                }
                if ($request->filled("investor")) {
                    $query->whereHas("invitee", function ($query1) use ($request) {
                        $query1->whereHas("ctTradeRequest", function ($query2) use ($request) {
                            $query2->whereHas("inviter", function ($query3) use ($request) {
                                $query3->whereIn("name", explode(",", $request->investor));
                            });
                        });
                    });
                }
                if ($request->filled("settlement")) {

                    $settlementobject = explode(",", $request->settlement);
                    if ((stripslashes($settlementobject[0]) != "") && (stripslashes($settlementobject[1]) != "")) {

                        $dateString1 = $settlementobject[0];
                        $dateString2 = $settlementobject[1];
                        $mindate = new DateTime($dateString1);
                        $maxdate = new DateTime($dateString2);
                        $query->whereBetween(DB::raw('DATE(settlement_date)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
                    } else {
                        if (stripslashes($settlementobject[0]) != "") {
                            $dateString1 = $settlementobject[0];
                            $mindate = new DateTime($dateString1);
                            $query->where(DB::raw('DATE(settlement_date)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                        }
                        if (stripslashes($settlementobject[1]) != "") {
                            $dateString2 = $settlementobject[1];
                            $maxdate = new DateTime($dateString2);
                            $query->where(DB::raw('DATE(settlement_date)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                        }
                    }
                }
                $query->whereHas("invitee", function ($query) use ($user) {
                    $query->whereHas("organization", function ($query) use ($user) {
                        $query->where("id", $user->organization->id);
                    });
                });
            });
        $deposits = $deposits->orderBy("id", "DESC")->select("c_t_trade_request_offer_deposits.*")->paginate($request->per_page ? $request->per_page : 10);
        return  $deposits;
    }
    public function getPendingDeposit(Request $request)
    {
        $user = Auth()->user();
        $deposit = CTTradeRequestOfferDeposit::with("CGOffer", "tradeEvents")
            ->where("id", CustomEncoder::urlValueDecrypt($request->depositId))
            ->whereHas("CGOffer", function ($query) use ($user) {
                $query->whereHas("invitee", function ($query) use ($user) {
                    $query->whereHas("organization", function ($query) use ($user) {
                        $query->where("id", $user->organization->id);
                    });
                });
            })
            ->first();
        if ($deposit) {
            return $deposit;
        } else {
            return response()->json([], 404);
        }
    }
    public function sendOfferEmail($requestt, $cg, $type)
    {
        if ($type == "admin") {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Repo Offer Shared",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'offerReceived'));
        } else if ($type == "cg") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "Repo Offer Shared",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'offerReceived'));
        } else if ($type == "ct") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "New Investment Offer",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'offerReceived'));
        }
    }
    public function sendOfferAcceptanceEmail($requestt, $cg, $type)
    {
        if ($type == "admin") {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Repo Counter Offer Accepted",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'counterOfferAccepted'));
        } else if ($type == "cg") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "Repo Counter Offer Accepted",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'counterOfferAccepted'));
        } else if ($type == "ct") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "Repo Counter Offer Accepted",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'counterOfferAccepted'));
        }
    }
    public function sendOfferDeclinedEmail($requestt, $cg, $type)
    {
        if ($type == "admin") {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Repo Counter Offer Denied",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'counterOfferDeclined'));
        } else if ($type == "cg") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "Repo Counter Offer Denied",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'counterOfferDeclined'));
        } else if ($type == "ct") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "Offer Withdrawn",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'counterOfferDeclined'));
        }
    }

    public function sendOfferWithdrawalTo($requestt, $cg, $type)
    {
        if ($type == "admin") {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Offer Withdrawn",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'offerWithdrawn'));
        } else if ($type == "cg") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "Offer Withdrawn",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'offerWithdrawn'));
        } else if ($type == "ct") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "Offer Withdrawn",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'offerWithdrawn'));
        }
    }

    public function postTradeEvent(Request $request)
    {
        $responseMessage = "";
        $responseStatus = true;

        try {
            DB::beginTransaction();

            $depositId = CustomEncoder::urlValueDecrypt($request->depositID);
            $foundRecord = CTTradeRequestOfferDeposit::find($depositId);
            $user = Auth::user();
            $eventreceivingOrg = eventReceivingOrganization($depositId, $user->organization);

            if ($foundRecord) {
                $foundRecord->update([
                    'active_trade_event' => $request->event_type,
                    'deposit_status' => 'INITIATED',
                ]);

                $tosave = [
                    'c_t_trade_request_offer_deposit_id' => $foundRecord->id,
                    'event_status' => 'INITIATED',
                    'initiating_organization_id' => $user->organization->id,
                    'receiving_organization_id' => $eventreceivingOrg->id,
                    'initiating_user_id' => $user->id,
                    'reason' => $request->reason,
                ];

                $combinedowstosave = [];

                switch ($request->event_type) {
                    case 'extension':
                        $responseMessage = "Maturity extension initiated successfully";
                        $tosaveb = [
                            'old_maturity_date' => $request->old_maturity_date,
                            'new_maturity_date' => $request->new_maturity_date,
                        ];
                        $combinedowstosave[] = array_merge($tosave, $tosaveb);
                        break;
                    case 'cancel':
                        $tosaveb = [
                            'reason' => $request->reason,
                            'event_type' => 'cancelletion'
                        ];
                        $combinedowstosave[] = array_merge($tosave, $tosaveb);
                        break;

                    case 'rate_change':
                        $responseMessage = "Rate change request initiated successfully";
                        $tosavec = [
                            'new_rate' => $request->new_rate,
                            'old_rate' => $request->old_rate,
                        ];
                        $combinedowstosave[] = array_merge($tosave, $tosavec);
                        break;

                    case 'exposure_change':
                        $responseMessage = "Exposure change request initiated successfully";
                        $tosavee = [
                            'old_purchase_value' => $request->old_purchase_value,
                            'new_purchase_value' => $request->new_purchase_value,
                        ];
                        $combinedowstosave[] = array_merge($tosave, $tosavee);
                        break;
                    case 'all':
                        if (!empty($request->old_purchase_value) && !empty($request->new_purchase_value)) {
                            $tosavee = [
                                'event_type' => ($request->old_purchase_value < $request->new_purchase_value) ? "increase_exposure" : "decrease_exposure",
                                'old_purchase_value' => $request->old_purchase_value,
                                'new_purchase_value' => $request->new_purchase_value,
                            ];
                            $combinedowstosave[] = array_merge($tosave, $tosavee);
                        }
                        if (!empty($request->old_rate) && !empty($request->new_rate)) {
                            $tosavec = [
                                'event_type' => "rate_change",
                                'old_rate' => $request->old_rate,
                                'new_rate' => $request->new_rate,
                            ];
                            $combinedowstosave[] = array_merge($tosave, $tosavec);
                        }
                        if (!empty($request->old_maturity_date) && !empty($request->new_maturity_date)) {
                            $tosaveb = [
                                'event_type' => "extension",
                                'old_maturity_date' => $request->old_maturity_date,
                                'new_maturity_date' => $request->new_maturity_date,
                            ];
                            $combinedowstosave[] = array_merge($tosave, $tosaveb);
                        }
                        break;

                    default:
                        $responseMessage = "Invalid event type";
                        $responseStatus = false;
                        break;
                }

                if ($request->event_type == "all") {
                    $batchno = generateTradeEventBatchNumber($foundRecord->id);
                    foreach ($combinedowstosave as $entry) {
                        $entry['batch_no'] = $batchno;
                        CTRequestDepositTradeEvent::create($entry);
                        CTRequestDepositTradeEvent::where("batch_no", "<>",  $entry['batch_no'])
                            ->where("c_t_trade_request_offer_deposit_id", $depositId)
                            ->update(["event_status" => "CLOSED_ON_NEW_EVENT"]);
                        //update deposit trade active_batch_number
                        $foundRecord->update([
                            'active_trade_events_batch_number' =>  $entry['batch_no']
                        ]);
                        //update deposit active_trade_events_batch_number
                    }
                } else {
                    //
                    if ($foundRecord) {
                        $actioning_user = Auth::user()->id;
                        archiveRepoTable($foundRecord->id, "trade_deposits", $foundRecord, $actioning_user, "Trade Event." . $request->event_type);
                    }
                    //
                    foreach ($combinedowstosave as $entry) {
                        $batchno = generateTradeEventBatchNumber($foundRecord->id);
                        $entry['batch_no'] = $batchno;
                        $created = CTRequestDepositTradeEvent::create($entry);
                        CTRequestDepositTradeEvent::where("id", "<>", $created->id)
                            ->where("c_t_trade_request_offer_deposit_id", $depositId)
                            ->update(["event_status" => "CLOSED_ON_NEW_EVENT"]);

                        $foundRecord->update([
                            'active_trade_events_batch_number' =>  $entry['batch_no']
                        ]);
                    }
                }
            } else {
                $responseStatus = false;
                $responseMessage = "The trade deposit was not found. Please retry again or contact admin.";
            }

            DB::commit();
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Request has not been successfully processed: ' . $exp->getMessage(),
            ], 400);
        }

        return response()->json([
            'success' => $responseStatus,
            'message' => $responseMessage,
            'data' => [],
        ]);
    }
    public function respondOnTradeEvent(Request $request)
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();
            $responsestatus = true;
            $responsemessage = "";
            if ($request->action === "accept") {
                $foundrecord = CTRequestDepositTradeEvent::whereIn("event_status", ["INITIATED"])->where("batch_no", $request->batchNo)->where("event_type", "cancelletion")->first();
                if ($foundrecord) {
                    $depositFound = CTTradeRequestOfferDeposit::where("id", $foundrecord->c_t_trade_request_offer_deposit_id)->first();
                    if ($depositFound) {

                        //
                        if ($depositFound) {
                            $actioning_user = Auth::user()->id;
                            archiveRepoTable($depositFound->id, "trade_deposits", $depositFound, $actioning_user, "Trade Cancelled.");
                        }
                        //
                        $depositFound->update(['deposit_status' => 'CANCELLED']);
                        $foundrecord->update(['event_status' => 'COMPLETED']);
                        //send accepted mail

                        //send accepted mail
                    } else {
                        $responsestatus = false;
                        $responsemessage = "No records found.Please try agin";
                    }
                } else {

                    $responsestatus = false;
                    $responsemessage = "No records found.Please try agin";
                }
            } else if ($request->action === "decline") {
                $foundrecord = CTRequestDepositTradeEvent::whereIn("event_status", ["INITIATED"])->where("batch_no", $request->batchNo)->where("event_type", "cancelletion")->first();
                if ($foundrecord) {
                    $depositFound = CTTradeRequestOfferDeposit::where("id", $foundrecord->c_t_trade_request_offer_deposit_id)->first();
                    if ($depositFound) {
                        $depositFound->update(['deposit_status' => 'PENDING_DEPOSIT']);
                        $foundrecord->update(['event_status' => 'COMPLETED']);
                    } else {
                        $responsestatus = false;
                        $responsemessage = "No records found.Please try agin";
                    }
                } else {

                    $responsestatus = false;
                    $responsemessage = "No records found.Please try agin";
                }
            }
            DB::commit();
            return  response()->json(['success' => $responsestatus, 'message' => $responsemessage, 'data' => []]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function replaceDummyWithExistingCollateral($depositEntry, $request)
    {
        $offer = $depositEntry->CGOffer;

        if ($offer->trade_organization_collateral_c_u_s_i_p_s_id == null) {
            $triexists = TradeTriBasketThirdParty::where("id", CustomEncoder::urlValueDecrypt($request->collateral_id))->first();
            if ($triexists) {
                $offer->update([
                    'trade_tri_basket_third_party_id' => CustomEncoder::urlValueDecrypt($request->collateral_id),
                    'trade_organization_collateral_c_u_s_i_p_s_id' => ''
                ]);
            }
        } else {
            $biexists = TradeOrganizationCollateralCUSIP::where("id", CustomEncoder::urlValueDecrypt($request->collateral_id))->first();
            if ($biexists) {
                $offer->update(
                    [
                        'trade_organization_collateral_c_u_s_i_p_s_id' => CustomEncoder::urlValueDecrypt($request->collateral_id),
                        'trade_tri_basket_third_party_id' => ''
                    ]

                );
            }
        }
    }
    public function replaceDummyWithNewCollateral($depositEntry, $request)
    {
        $user = Auth::user();

        $offer = CTTradeRequestCGOffer::where("id", $depositEntry->CGOffer->id)->first();
        $basketToUse = null;
        $basket = json_decode($request->collateral_details, true);
        if ($basket['type'] == "tri") {
            //create basket
            $basketInfo = [
                'trade_basket_type_id' => CustomEncoder::urlValueDecrypt($basket['basketType']),
                'currency' => $basket['currency'],
                'type' => $basket['type'],
                'organization_id' => $user->organization->id,
                'user_id' => $user->id,
                'rating' => $basket['rating']
            ];

            $updatedrec = TradeCollateralBasket::where($basketInfo)->first();
            if ($updatedrec != null) {
                $created_basket = $updatedrec;
            } else {
                $basketInfo['created_at'] = getUTCTimeNow();
                $created_basket = TradeCollateralBasket::create($basketInfo);
            }

            //create basket
            //create Third parties                    
            if (isset($basket['counterParty'])) {
                foreach ($basket['counterParty'] as $counterParty) {
                    $counterPartyInfo = [
                        'basket_id' => $counterParty['basketId'],
                        'organization_id' => CustomEncoder::urlValueDecrypt($counterParty['counterTyID']),
                        'trade_collateral_basket_id' => $created_basket->id
                    ];
                    $updatedrec1 = TradeTriBasketThirdParty::where($counterPartyInfo)->first();
                    $basketToUse = $updatedrec1;
                    if ($updatedrec1 == null) {
                        $counterPartyInfo['created_at'] = getUTCTimeNow();
                        $basketToUse = TradeTriBasketThirdParty::create($counterPartyInfo);
                    } else {
                        $basketToUse = $updatedrec1;
                    }
                }
            }
            //create Third Parties
            if ($basketToUse != null) {
                $offer->update([
                    'trade_tri_basket_third_party_id' => $basketToUse->id,
                    'trade_organization_collateral_c_u_s_i_p_s_id' => ''
                ]);
            }
        } else if ($basket['type'] == "bi") {
            $collateraltouse = null;
            $created_basket = null;
            $colInfo = [
                'trade_collateral_issuer_id' => CustomEncoder::urlValueDecrypt($basket['issuer']),
                'currency' => $basket['currency'],
                'rating' => $basket['rating'],
                'organization_id' => $user->organization->id
            ];

            $updatedrec1 = TradeOrganizationCollateral::where($colInfo)->first();
            if ($updatedrec1 == null) {
                $colInfo['created_at'] = getUTCTimeNow();
                $colInfo['collateral_status'] = "ACTIVE";
                $colInfo['user_id'] = $user->id;
                $created_basket = TradeOrganizationCollateral::create($colInfo);
                //create or update cusisp
                $colcusip['trade_organization_collateral_id'] = $created_basket->id;
                $colcusip['trade_collateral_id'] = CustomEncoder::urlValueDecrypt($basket['collateral']);
                $colcusip['CUSIP_code'] = $basket['cucipNumber'];
                $colcusip['maturity_date'] = $basket['maturityDate'];
                $colcusip['is_dummy'] = 0;
                $foundrecord = TradeOrganizationCollateralCUSIP::where($colcusip)->first();
                $colcusip['cusips_status'] = "ACTIVE";
                if ($foundrecord == null) {
                    $created_col = TradeOrganizationCollateralCUSIP::create($colcusip);
                    //  return $colcusip;
                    $collateraltouse = $created_col;
                } else {
                    $foundrecord->update($colcusip);
                    // $collateraltouse = $foundrecord;
                }
                //create or update cusips
            } else {
                //create or update cusisp

                $colcusip['trade_organization_collateral_id'] = $updatedrec1->id;
                $colcusip['trade_collateral_id'] = CustomEncoder::urlValueDecrypt($basket['collateral']);
                $colcusip['CUSIP_code'] = $basket['cucipNumber'];
                $colcusip['maturity_date'] = $basket['maturityDate'];
                $colcusip['is_dummy'] = 0;
                $foundrecord = TradeOrganizationCollateralCUSIP::where($colcusip)->first();
                $colcusip['cusips_status'] = "ACTIVE";
                if ($foundrecord == null) {
                    $createdcol = TradeOrganizationCollateralCUSIP::create($colcusip);
                    $collateraltouse = $createdcol;
                } else {
                    $foundrecord->update($colcusip);
                    $collateraltouse = $foundrecord;
                }
                //create or update cusips

            }


            if ($collateraltouse != null) {
                $offer->update(
                    [
                        'trade_organization_collateral_c_u_s_i_p_s_id' => $collateraltouse->id,
                        'trade_tri_basket_third_party_id' => ''
                    ]

                );
            }
        }
    }
    public function activateTrade(Request $request)
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();
            $responsestatus = true;
            $responsemessage = "";

            $deposit = CTTradeRequestOfferDeposit::with("CGOffer")
                ->where("id", CustomEncoder::urlValueDecrypt($request->depositId))
                ->whereIn("deposit_status", ['PENDING_DEPOSIT', 'INITIATED', ''])
                ->first();

            if ($deposit) {
                //                                 
                $actioning_user = Auth::user()->id;
                archiveRepoTable($deposit->id, "trade_deposits", $deposit, $actioning_user, "Before Trade Activation.");
                //check collateral
                if ($request->collateral_exists === 1 || $request->collateral_exists === "1") {
                    $this->replaceDummyWithExistingCollateral($deposit, $request);
                } else if ($request->collateral_exists === 0 || $request->collateral_exists === "0") {
                    $this->replaceDummyWithNewCollateral($deposit, $request);
                }
                //check collateral
                $deposit->deposit_status = "ACTIVE";
                $tradeDate = getUTCTimeNow();
                if ($deposit->CGOffer->offer_term_length_type == "MONTHS") {
                    $deposit->maturity_date = changeDateFromLocalToUTC($tradeDate->addMonths($deposit->CGOffer->offer_term_length));
                } else if ($deposit->CGOffer->offer_term_length_type == "DAYS") {
                    $deposit->maturity_date = changeDateFromLocalToUTC($tradeDate->addDays($deposit->CGOffer->offer_term_length));
                }
                $deposit->save();
                $responsestatus = true;
                $responsemessage = "Activated successfully.";

                $cginfo = $deposit->c_g_organization;
                $ctinfo = $deposit->c_t_organization;
                $this->sendRequestProcessingMail($deposit, $cginfo, "cg");
                $this->sendRequestProcessingMail($deposit, $ctinfo, "ct");
                $this->sendRequestProcessingMail($deposit, $cginfo, "admin");
            } else {
                $responsestatus = false;
                $responsemessage = "The trade has either expired, been cancelled, or does not exist.";
            }

            DB::commit();
            return response()->json(['success' => $responsestatus, 'message' => $responsemessage, 'data' => $deposit]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed. ' . $exp->getMessage(),
            ], 400);
        }
    }
    public function sendDepositMessage(Request $request)
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();
            $responsestatus = true;
            $responsemessage = "";
            $data = [];
            $messageFile = "";
            //crreate a message
            $deposit = CTTradeRequestOfferDeposit::where("id", CustomEncoder::urlValueDecrypt($request->depositId))->first();
            if ($deposit != null) {
                //upload message file
                if ($request->hasFile('file')) {
                    $validator = Validator::make($request->all(), [
                        'file' => 'required|mimes:jpeg,jpg,png,gif,pdf'
                    ]);

                    if ($validator->fails()) {
                        $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                        return response()->json($response, 400);
                    }

                    $file = $request->file('file');
                    $destinationPath = public_path() . '/uploads/CTRequestsDepositsmessages';
                    $file_name = time() . $file->getClientOriginalName() . '.' . $file->extension();
                    if ($file->move($destinationPath, $file_name)) {
                        $messageFile = $file_name;
                    }
                }
                //upload message file
                //message object
                $messageobject = [
                    'sent_by' => $user->id,
                    'message' => $request->message,
                    'c_t_trade_request_offer_deposit_id' => $deposit->id,
                    'sent_by_organization_id' => $user->organization->id,
                    'sent_to_organization_id' => $deposit->c_t_organization->id,
                    'file' => $messageFile
                ];
                //message object
                $createdchat = CTTradeRequestOfferDepositChat::create($messageobject);
                if ($createdchat != null) {
                    $data['deposit'] = $deposit;
                    $data['created_chat'] = $createdchat;
                    $data['get_all_deposit_chats'] = CTTradeRequestOfferDepositChat::with(['by', 'to'])->where("id", $createdchat->id)->get();
                    // broadcast event

                    Log::info('CTRequestsDepositsmessages Event broadcasted successfully. 11');
                    broadcast(new CTTradeRequestOfferDepositChatEvent($deposit, $createdchat))->toOthers();

                    // broadcast event
                    $responsestatus = true;
                    $responsemessage = "Message send";
                } else {
                    $responsestatus = false;
                    $responsemessage = "Message failed";
                }
                //crreate a message


            } else {
                $responsestatus = false;
                $responsemessage = "Message failed.The deposit was not found";
            }
            DB::commit();
            return  response()->json([
                'success' => $responsestatus,
                'message' => $responsemessage,
                'data' => $data
            ]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Message has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function getDepositMessages(Request $request)
    {
        $messages = CTTradeRequestOfferDepositChat::with(['by', 'to'])
            ->where("c_t_trade_request_offer_deposit_id", CustomEncoder::urlValueDecrypt($request->depositId))
            ->get();
        return $messages;
    }

    public function markDepositMessageRead(Request $request)
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();
            $responsestatus = true;
            $responsemessage = "";
            $foundmessage = CTTradeRequestOfferDepositChat::where("id", CustomEncoder::urlValueDecrypt($request->chatId))->first();
            if ($foundmessage) {
                $foundmessage->update(['status' => 'SEEN', 'seen_at' => getUTCTimeNow()]);
                $responsestatus = true;
                $responsemessage = "Updated";
            } else {
                $responsestatus = false;
                $responsemessage = "Failed";
            }
            DB::commit();
            return response()->json(['success' => $responsestatus, 'message' => $responsemessage, 'data' => $foundmessage]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed. ' . $exp->getMessage(),
            ], 400);
        }
    }




    public function sendOfferMessage(Request $request)
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();
            $responsestatus = true;
            $responsemessage = "";
            $data = [];
            $messageFile = "";
            //crreate a message
            $deposit = CTTradeRequestCGOffer::where("id", CustomEncoder::urlValueDecrypt($request->offerId))->first();
            if ($deposit != null) {
                //upload message file
                if ($request->hasFile('file')) {
                    $validator = Validator::make($request->all(), [
                        'file' => 'required|mimes:jpeg,jpg,png,gif,pdf'
                    ]);

                    if ($validator->fails()) {
                        $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                        return response()->json($response, 400);
                    }

                    $file = $request->file('file');
                    $destinationPath = public_path() . '/uploads/CTRequestsoffersmessages';
                    $file_name = time() . $file->getClientOriginalName() . '.' . $file->extension();
                    if ($file->move($destinationPath, $file_name)) {
                        $messageFile = $file_name;
                    }
                }
                //upload message file
                //message object
                $messageobject = [
                    'sent_by' => $user->id,
                    'message' => $request->message,
                    'c_t_trade_request_offer_id' =>  CustomEncoder::urlValueDecrypt($request->offerId),
                    'sent_by_organization_id' => $user->organization->id,
                    'sent_to_organization_id' => $deposit->c_t_trade_request->organization_id,
                    'file' => $messageFile
                ];
                //message object
                $createdchat = CTTradeRequestOfferChat::create($messageobject);
                if ($createdchat != null) {
                    $data['deposit'] = $deposit;
                    $data['created_chat'] = $createdchat;
                    $data['get_all_deposit_chats'] = CTTradeRequestOfferChat::with(['by', 'to'])->where("id", $createdchat->id)->get();
                    // broadcast event
                    Log::info('CTRequestsoffermessages Event broadcasted successfully. 11');
                    broadcast(new CTTradeRequestOfferChatEvent($deposit, $createdchat))->toOthers();

                    // broadcast event
                    $responsestatus = true;
                    $responsemessage = "Message send";
                } else {
                    $responsestatus = false;
                    $responsemessage = "Message failed";
                }
                //crreate a message


            } else {
                $responsestatus = false;
                $responsemessage = "Message failed.The deposit was not found";
            }
            DB::commit();
            return  response()->json([
                'success' => $responsestatus,
                'message' => $responsemessage,
                'data' => $data
            ]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Message has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function getOfferMessages(Request $request)
    {
        $messages = CTTradeRequestOfferChat::with(['by', 'to'])
            ->where("c_t_trade_request_offer_id", CustomEncoder::urlValueDecrypt($request->offerId))
            ->get();
        return $messages;
    }

    public function markOfferMessageRead(Request $request)
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();
            $responsestatus = true;
            $responsemessage = "";
            $foundmessage = CTTradeRequestOfferChat::where("id", CustomEncoder::urlValueDecrypt($request->chatId))->first();
            if ($foundmessage) {
                $foundmessage->update(['status' => 'SEEN', 'seen_at' => getUTCTimeNow()]);
                $responsestatus = true;
                $responsemessage = "Updated";
            } else {
                $responsestatus = false;
                $responsemessage = "Failed";
            }
            DB::commit();
            return response()->json(['success' => $responsestatus, 'message' => $responsemessage, 'data' => $foundmessage]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed. ' . $exp->getMessage(),
            ], 400);
        }
    }

    public function addBasket(Request $request)
    {

        try {
            DB::beginTransaction();
            $message = "";
            $status = true;
            $allTypes = [];
            $user = Auth::user();
            $baskets = json_decode($request->baskets, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['success' => false, 'error' => 'Invalid JSON format.'], 400);
            }
            if ($request->action == "add") {
                $rules = [
                    '*.currency' => 'required|string',
                    '*.type' => 'required|string'
                ];
            } else if ($request->action === "update") {
                $rules = [
                    '*.currency' => 'required|string',
                    '*.type' => 'required|string'
                ];
            }
            $validator = Validator::make($baskets, $rules);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have errors in your data.',
                    'errors' => $validator->errors()
                ], 400);
            }

            foreach ($baskets as $basket) {
                $created_basket = null;
                if ($basket['type'] == "tri") {
                    //create basket
                    $basketInfo = [
                        'trade_basket_type_id' => CustomEncoder::urlValueDecrypt($basket['basketType']),
                        'currency' => $basket['currency'],
                        'type' => $basket['type'],
                        'organization_id' => $user->organization->id,
                        'user_id' => $user->id,
                        'rating' => $basket['rating']
                    ];
                    if ($request->action == "add") {
                        $updatedrec = TradeCollateralBasket::where($basketInfo)->first();
                        if ($updatedrec != null) {
                            $created_basket = $updatedrec;
                        } else {
                            $basketInfo['created_at'] = getUTCTimeNow();
                            $created_basket = TradeCollateralBasket::create($basketInfo);
                        }
                    } else if ($request->action === "update") {
                        $foundrecord = TradeCollateralBasket::where("id", CustomEncoder::urlValueDecrypt($basket['basketUpdateId']))->first();
                        if ($foundrecord) {
                            //                 
                            $actioning_user = Auth::user()->id;
                            archiveRepoTable($foundrecord->id, "trade_collateral_baskets", $foundrecord, $actioning_user, "Update Request.");

                            //

                            $foundrecord->update($basketInfo);
                            $created_basket =  $foundrecord;
                        } else {
                            $message = "Record not found";
                        }


                        // $foundrecord->update($basketInfo);
                    }

                    //create basket
                    //create Third parties                    
                    if (isset($basket['counterParty'])) {
                        foreach ($basket['counterParty'] as $counterParty) {
                            $counterPartyInfo = [
                                'basket_id' => $counterParty['basketId'],
                                'organization_id' => CustomEncoder::urlValueDecrypt($counterParty['counterTyID']),
                                'trade_collateral_basket_id' => $created_basket->id
                            ];
                            $updatedrec1 = TradeTriBasketThirdParty::where($counterPartyInfo)->first();

                            if ($updatedrec1) {
                            } else {
                                $counterPartyInfo['created_at'] = getUTCTimeNow();
                                TradeTriBasketThirdParty::create($counterPartyInfo);
                            }
                        }
                    }
                    //create Third Parties
                    if ($created_basket != null) {
                        $c_basket = TradeCollateralBasket::with(['tradeTriBasketThirdParty'])->where("id", $created_basket->id)->first();
                        array_push($allTypes, $c_basket);
                    }
                } else if ($basket['type'] == "bi") {
                    $colInfo = [
                        'trade_collateral_issuer_id' => CustomEncoder::urlValueDecrypt($basket['issuer']),
                        'currency' => $basket['currency'],
                        'rating' => $basket['rating'],
                        'organization_id' => $user->organization->id
                    ];
                    if ($request->action == "add") {
                        $updatedrec1 = TradeOrganizationCollateral::where($colInfo)->first();
                        if ($updatedrec1 == null) {
                            $colInfo['created_at'] = getUTCTimeNow();
                            $colInfo['collateral_status'] = "ACTIVE";
                            $colInfo['user_id'] = $user->id;
                            $created_basket = TradeOrganizationCollateral::create($colInfo);
                            //create or update cusisp
                            $colcusip['trade_organization_collateral_id'] = $created_basket->id;
                            $colcusip['trade_collateral_id'] = CustomEncoder::urlValueDecrypt($basket['collateral']);
                            $colcusip['CUSIP_code'] = $basket['cucipNumber'];
                            $colcusip['maturity_date'] = $basket['maturityDate'];
                            $colcusip['is_dummy'] = 0;
                            $foundrecord = TradeOrganizationCollateralCUSIP::where($colcusip)->first();
                            $colcusip['cusips_status'] = "ACTIVE";
                            if ($foundrecord == null) {
                                TradeOrganizationCollateralCUSIP::create($colcusip);
                            } else {
                                $foundrecord->update($colcusip);
                            }
                            //create or update cusips
                        } else {
                            //create or update cusisp

                            $colcusip['trade_organization_collateral_id'] = $updatedrec1->id;
                            $colcusip['trade_collateral_id'] = CustomEncoder::urlValueDecrypt($basket['collateral']);
                            $colcusip['CUSIP_code'] = $basket['cucipNumber'];
                            $colcusip['maturity_date'] = $basket['maturityDate'];
                            $colcusip['is_dummy'] = 0;
                            $foundrecord = TradeOrganizationCollateralCUSIP::where($colcusip)->first();
                            $colcusip['cusips_status'] = "ACTIVE";
                            if ($foundrecord == null) {
                                TradeOrganizationCollateralCUSIP::create($colcusip);
                            } else {
                                $foundrecord->update($colcusip);
                            }
                            //create or update cusips

                        }
                    } else if ($request->action === "update") {
                        $foundrecord = TradeOrganizationCollateral::where("id", CustomEncoder::urlValueDecrypt($basket['collateralUpdateId']))->first();
                        if ($foundrecord) {
                            $foundrecord->update($colInfo);
                            //create cusisp
                            $colcusip['trade_organization_collateral_id'] = $foundrecord->id;
                            $colcusip['CUSIP_code'] = $basket['cucipNumber'];
                            $colcusip['maturity_date'] = $basket['maturityDate'];
                            $colcusip['is_dummy'] = 0;
                            $foundrecord = TradeOrganizationCollateralCUSIP::where($colcusip)->first();
                            $colcusip['cusips_status'] = "ACTIVE";
                            if ($foundrecord == null) {
                                TradeOrganizationCollateralCUSIP::create($colcusip);
                            } else {
                                $foundrecord->update($colcusip);
                            }
                            //create cusips
                        } else {
                            $message = "Record not found";
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(['message' => $message, 'success' => $status, 'data' => []], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }
    public function addCounterPartiesToBasket(Request $request)
    {
        try {
            DB::beginTransaction();
            $status = true;
            $allTypes = [];
            $user = Auth::user();
            $message = "Proceesed successfully";

            //create Third parties   

            if (isset($request->counterParties)) {
                $counterParties = json_decode($request->counterParties, true);
                if ($request->action === "add" || !isset($request->action)) {
                    $updatedrec = TradeCollateralBasket::where("id", CustomEncoder::urlValueDecrypt($request->basket))->first();

                    foreach ($counterParties as $counterParty) {
                        $counterPartyInfo = [
                            'basket_id' => $counterParty['basketId'],
                            'organization_id' => CustomEncoder::urlValueDecrypt($counterParty['counterTyID']),
                            'trade_collateral_basket_id' => $updatedrec->id
                        ];
                        $updatedrec1 = TradeTriBasketThirdParty::where($counterPartyInfo)->first();
                        if ($updatedrec1) {
                        } else {
                            $counterPartyInfo['created_at'] = getUTCTimeNow();
                            TradeTriBasketThirdParty::create($counterPartyInfo);
                        }
                    }
                    $message = "Added successfully.";
                } else if ($request->action === "update") {
                    $message = "Updated successfully.";
                    foreach ($counterParties as $counterParty) {
                        $counterPartyInfo = [
                            'basket_id' => $counterParty['basketId'],
                            'organization_id' => CustomEncoder::urlValueDecrypt($counterParty['counterTyID'])
                        ];
                        $updatedrec1 = TradeTriBasketThirdParty::where("id", CustomEncoder::urlValueDecrypt($counterParty['thirdPartyId']))->first();
                        if ($updatedrec1) {
                            $updatedrec1->update($counterPartyInfo);
                            $message = "Updated successfully.";
                        }
                    }
                }
                //create Third Parties
            }

            DB::commit();
            return response()->json(['message' => $message, 'success' => $status, 'data' => [$request->action]], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }
    public function archiveThirdParty(Request $request)
    {
        try {
            DB::beginTransaction();
            $message = "";
            $status = true;
            $allTypes = [];
            $user = Auth::user();

            $foundthirdparty = TradeTriBasketThirdParty::where("id", CustomEncoder::urlValueDecrypt($request->thirdPartyId))->first();
            if ($foundthirdparty) {
                $foundthirdparty->update([
                    "basket_status" => $request->action
                ]);

                $message = "Third Party details updated successfully";
                $status = true;
            } else {
                $message = "Record not found.";
                $status = false;
            }

            DB::commit();
            return response()->json(['message' => $message, 'success' => $status, 'data' => [$request->action]], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }

    public function archiveOrganizationCollateral(Request $request)
    {
        try {
            DB::beginTransaction();
            $message = "";
            $status = true;
            $allTypes = [];
            $user = Auth::user();
            $foundthirdparty = TradeOrganizationCollateral::where("id", CustomEncoder::urlValueDecrypt($request->orgCollateralId))->first();

            if ($foundthirdparty) {

                $foundthirdparty->update(["collateral_status" => $request->action]);
                $message = "Collateral updated successfully";
                $status = true;
            } else {
                $message = "Record not found.";
                $status = false;
            }


            DB::commit();
            return response()->json(['message' => $message, 'success' => $status, 'data' => [$request->action]], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }

    public function validateCounterPartyEntry(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $invalid = true;

            $foundRecord = TradeCollateralBasket::whereHas('tradeTriBasketThirdParty', function ($query) use ($request) {
                $query->where("basket_id", $request->basketId);
            })
                // where('currency', $request->currency)
                // ->where('trade_basket_type_id',  CustomEncoder::urlValueDecrypt($request->basketType))

                ->with(["tradeTriBasketThirdParty" => function ($query) use ($request) {
                    $query->where("basket_id", $request->basketId);
                }])
                // ->where('rating', $request->rating)
                // ->where('organization_id', $user->organization->id)
                ->first();


            $invalid = !is_null($foundRecord);
            DB::commit();
            return response()->json([
                'message' => '',
                'success' => true,
                'invalid' => $invalid,
                'data' => $foundRecord
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }
    public function validateBilateralCollateral(Request $request)
    {
        try {

            DB::beginTransaction();
            $user = Auth::user();
            $foundRecord = TradeOrganizationCollateral::whereHas('tradeOrganizationCUSSIP', function ($query) use ($request) {
                $query->where("CUSIP_code", $request->CUSIPCode);
            })
                ->with(["tradeOrganizationCUSSIP" => function ($query) use ($request) {
                    $query->where("CUSIP_code", $request->CUSIPCode);
                }])
                // where('currency', $request->currency)

                // ->where('rating', $request->rating)
                // ->where('trade_collateral_issuer_id', CustomEncoder::urlValueDecrypt($request->issuer_id))
                // ->where('organization_id', $user->organization->id)
                ->first();

            $invalid = !is_null($foundRecord);
            DB::commit();

            return response()->json([
                'message' => '',
                'success' => true,
                'invalid' => $invalid,
                'data' => $foundRecord
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Request has not been successfully processed. '
            ], 400);
        }
    }

    public function getBaskets(Request $request)
    {
        $user = Auth::user();
        $baskets = TradeCollateralBasket::with(['tradeBasketType']);
        if ($request->is_dummy == 0 || $request->is_dummy == "0") {
            $baskets->where("rating", "!=", "N/A");
        } else if ($request->is_dummy == 1 || $request->is_dummy == "1") {
            $baskets->where("rating", "N/A");
        }

        if ($request->filled("counterPartyStatus")) {
            $cstatus = explode(",", $request->counterPartyStatus);
            $baskets->with(['tradeTriBasketThirdParty' => function ($query) use ($cstatus, $request) {

                if ($request->filled("is_dummy")) {
                    $query->where('is_dummy', $request->is_dummy);
                }
                $query->whereIn('basket_status', $cstatus);
            }]);
        } else {
            $baskets->with(['tradeTriBasketThirdParty' => function ($query) use ($request) {
                if ($request->filled("is_dummy")) {
                    $query->where('is_dummy', $request->is_dummy);
                }
            }]);
        }
        if ($request->filled("status")) {
            $explodedstatus = explode(",", $request->status);
            $baskets->whereIn("basket_status", $explodedstatus);
        }
        if ($request->filled("rating")) {
            $baskets->where("rating", $request->rating);
        }
        if ($request->filled("counterPartyStatus")) {
        }
        if ($request->filled("basketType")) {
            $basketType = $request->basketType;
            $baskets->whereHas("tradeBasketType", function ($basketsquery) use ($basketType) {
                $basketsquery->where("basket_name", $basketType);
            });
        }
        if ($request->filled("search")) {
            $search = $request->search;
            $baskets->whereHas("tradeBasketType", function ($basketsquery) use ($search) {
                $basketsquery->where("basket_name", "like", "%{$search}%");
            });
        }

        $baskets->where("organization_id", $user->organization->id);
        $baskets =  $baskets->get();
        return  $baskets;
    }

    public function getCollaterals(Request $request)
    {
        $user = Auth::user();
        $cols = TradeOrganizationCollateral::query();
        if ($request->is_dummy == 0 || $request->is_dummy == "0") {
            $cols->where("rating", "!=", "N/A");
        } else if ($request->is_dummy == 1 || $request->is_dummy == "1") {
            $cols->where("rating", "N/A");
        }
        $cols->with(["organization", "collateralIssuer", "tradeOrganizationCUSSIP" => function ($query) use ($request) {
            if ($request->filled("is_dummy")) {
                $query->where('is_dummy', $request->is_dummy);
            }
        }]);

        if ($request->filled("status")) {
            $explodedstatus = explode(",", $request->status);
            $cols->whereIn("collateral_status", $explodedstatus);
        }

        if ($request->filled("issuer")) {
            $issuer = $request->issuer;
            $cols->whereHas("collateralIssuer", function ($basketsquery) use ($issuer) {
                $basketsquery->where("name", $issuer);
            });
        }
        if ($request->filled("search")) {
            $search = $request->search;
            $cols->where("CUSIP_code", "like", "%{$search}%")
                ->orWhere(function ($query) use ($search) {
                    $query->whereHas("tradeOrganizationCUSSIP", function ($basketsquery) use ($search) {
                        $basketsquery->where("collateral_name", "like", "%{$search}%");
                    })->orWhereHas("collateralIssuer", function ($basketsquery) use ($search) {
                        $basketsquery->where("name", "like", "%{$search}%");
                    });
                });
        }
        $cols->where("organization_id", $user->organization->id);
        $cols = $cols->get();
        return $cols;
    }

    public function getBasket(Request $request)
    {
        $user = Auth::user();
        $basketQuery = TradeCollateralBasket::with(['tradeBasketType']);
        if ($request->is_dummy == 0 || $request->is_dummy == "0") {
            $basketQuery->where("rating", "!=", "N/A");
        } else if ($request->is_dummy == 1 || $request->is_dummy == "1") {
            $basketQuery->where("rating", "N/A");
        }
        if ($request->filled("counterPartyStatus")) {

            $basketQuery->with(['tradeTriBasketThirdParty' => function ($query) use ($request) {
                $status = explode(",", $request->counterPartyStatus);
                if ($request->filled("is_dummy")) {
                    $query->where('is_dummy', $request->is_dummy);
                }
                $query->select('*')->with(['counterPartyDetails'])
                    ->whereIn('basket_status', $status);
                if ($request->filled("search")) {
                    $search = $request->search;
                    $query->where("basket_id", "like", "%{$search}%");
                    $query->orWhereHas("counterPartyDetails", function ($query2) use ($search) {
                        $query2->where("organizations.name", "like", "%{$search}%");
                    });
                }
                if ($request->filled("counterParty")) {
                    $query->where("trade_tri_basket_third_parties.organization_id", CustomEncoder::urlValueDecrypt($request->counterParty));
                }
            }]);
        } else {
            $basketQuery->with(['tradeTriBasketThirdParty' => function ($query) use ($request) {
                if ($request->filled("is_dummy")) {
                    $query->where('is_dummy', $request->is_dummy);
                }
                $query->select('*')->with(['counterPartyDetails']);
                if ($request->filled("search")) {
                    $search = $request->search;
                    $query->where("basket_id", "like", "%{$search}%");
                    $query->orWhereHas("counterPartyDetails", function ($query2) use ($search) {
                        $query2->where("organizations.name", "like", "%{$search}%");
                    });
                }
                if ($request->filled("counterParty")) {
                    $query->where("trade_tri_basket_third_parties.organization_id", CustomEncoder::urlValueDecrypt($request->counterParty));
                }
                if ($request->filled("is_dummy")) {
                    $query->where('is_dummy', $request->is_dummy);
                }
            }]);
        }
        $basket = $basketQuery->where("organization_id", $user->organization->id)
            ->where("trade_collateral_baskets.id", CustomEncoder::urlValueDecrypt($request->basket))
            ->first();
        return $basket;
    }

    public function getBasketTriparty(Request $request)
    {
        $response =   TradeTriBasketThirdParty::where("id", CustomEncoder::urlValueDecrypt($request->thirdPartyId))->first();
        return $response;
    }
    public function getCollateral(Request $request)
    {
        $user = Auth::user();
        $col = TradeOrganizationCollateral::with(["organization", "collateralIssuer", "tradeOrganizationCUSSIP" => function ($query) use ($request) {
            if ($request->filled("is_dummy")) {
                $query->where('is_dummy', $request->is_dummy);
            }
            $query->whereHas("collateralDetails", function ($query2) use ($request) {
                if ($request->filled("collateralName")) {
                    $query2->where("collateral_name", $request->collateralName);
                }
            });
            if ($request->filled("search")) {
                $search = $request->search;
                $query->where("CUSIP_code", "like", "%{$search}%")
                    ->orWhere(function ($query) use ($search) {
                        $query->whereHas("collateralDetails", function ($basketsquery) use ($search) {
                            $basketsquery->where("collateral_name", "like", "%{$search}%");
                        });
                    });
            }
            if ($request->filled("status")) {
                $status = explode(",", $request->status);
                $query->whereIn("cusips_status", $status);
            }
            if ($request->filled("maturityDate")) {
                $maturityDateOb = explode(",", $request->maturityDate);
                if ((stripslashes($maturityDateOb[0]) != "") && (stripslashes($maturityDateOb[1]) != "")) {

                    $dateString1 = $maturityDateOb[0];
                    $dateString2 = $maturityDateOb[1];
                    $mindate =  $dateString1;
                    $maxdate =  $dateString2;
                    $query->whereBetween(DB::raw('DATE(maturity_date)'), [$mindate, $maxdate]);
                } else {

                    if (stripslashes($maturityDateOb[0]) != "") {

                        $query->where(DB::raw('DATE(maturity_date)'), '>=', $maturityDateOb[0]);
                    }

                    if (stripslashes($maturityDateOb[1]) != "") {

                        $query->where(DB::raw('DATE(expiry_date)'), '<=', $maturityDateOb[1]);
                    }
                }
            }
        }]);
        $col->where("organization_id", $user->organization->id);
        $col = $col->where("trade_organization_collaterals.id", CustomEncoder::urlValueDecrypt($request->collateral))
            ->first();
        return  $col;
    }
    public function getColleteralsIssuers(Request $request)
    {
        $issuesrs = TradeCollateralIssuer::get();
        return  $issuesrs;
    }
    public function addCusipToIssuer(Request $request)
    {
        try {

            DB::beginTransaction();
            $message = "";
            $status = 0;
            $updatedrec1 = TradeOrganizationCollateral::where("id", CustomEncoder::urlValueDecrypt($request->issuerId))->first();
            if (isset($request->cusips)) {
                $cusps = json_decode($request->cusips, true);
                foreach ($cusps as $cusip) {
                    $colcusip['trade_organization_collateral_id'] = $updatedrec1->id;
                    $colcusip['trade_collateral_id'] = CustomEncoder::urlValueDecrypt($cusip['collateralId']);
                    $colcusip['CUSIP_code'] = $cusip['cucipNumber'];
                    $colcusip['maturity_date'] = $cusip['maturityDate'];
                    $colcusip['is_dummy'] = 0;
                    $foundrecord = TradeOrganizationCollateralCUSIP::where($colcusip)->first();
                    $colcusip['cusips_status'] = "ACTIVE";
                    if ($foundrecord == null) {
                        TradeOrganizationCollateralCUSIP::create($colcusip);
                    } else {
                        $foundrecord->update($colcusip);
                    }
                }
            }
            //create cusip
            DB::commit();
            return response()->json(['message' => $message, 'success' => $status, 'data' => [$request->action]], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
    }
    public function updateCusipToIssuer(Request $request)
    {
        try {
            DB::beginTransaction();

            $cusips = json_decode($request->cusips, true);
            $cusip = $cusips[0];

            $recordId = CustomEncoder::urlValueDecrypt($cusip['trade_organization_collateral_c_u_s_i_p']);
            $foundRecord = TradeOrganizationCollateralCUSIP::find($recordId);

            if (!$foundRecord) {
                return response()->json([
                    "success" => false,
                    'message' => 'Record not found.',
                ], 404);
            }

            $colcusip = [
                'trade_collateral_id' => isset($cusip['collateralId']) ? CustomEncoder::urlValueDecrypt($cusip['collateralId']) : $foundRecord->trade_collateral_id,
                'CUSIP_code' => isset($cusip['cucipNumber']) ? $cusip['cucipNumber'] : $foundRecord->CUSIP_code,
                'maturity_date' => isset($cusip['maturityDate']) ? $cusip['maturityDate'] : $foundRecord->maturity_date,
                'is_dummy' => 0,
            ];

            $anotherExists = TradeOrganizationCollateralCUSIP::where($colcusip)
                ->where('id', '!=', $recordId)
                ->first();

            if ($anotherExists) {
                return response()->json([
                    "success" => false,
                    'message' => 'Similar records exist.',
                ], 422);
            }

            $colcusip['cusips_status'] = isset($cusip['status']) ? $cusip['status'] : $foundRecord->cusips_status;
            $foundRecord->update($colcusip);

            DB::commit();

            return response()->json([
                'message' => 'CUSIP updated successfully.',
                'success' => true,
                'data' => [$request->action],
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed. ' . $th->getMessage(),
            ], 400);
        }
    }

    public function sendCTRequestNotificationToAdmin($requestt, $cg)
    {
        Mail::to(getAdminEmails())->queue(new AdminMails([
            'subject' => "New Trade Request",
            'ct_Request' => $requestt,
            'user_type' => "Admin"
        ], 'newRequest'));
    }
    public function sendCTRequestNotificationTo($requestt, $cg, $type)
    {
        $user = Auth::user();
        if ($type == "admin") {

            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "New Request For Money.",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'newRequestForMoneyFromCG'));
        } else if ($type == "cg") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "New Request For Money Shared.",
                'cg_Request' => $requestt,
                'user_type' => "CG"
            ], 'newRequestToCT'));
        } else if ($type == "ct") {

            Mail::to($cg->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "New Request For Money Received.",
                'cg_Request' => $requestt,
                'sender' => $user->organization,
                'user_type' => "CT"
            ], 'newRequestFromCG'));
        }
    }
    public function getTradeProductTypeBasedOnShortForm($shortform)
    {
        if ($shortform == "tri") {
            $prod =    TradeProduct::where("filter_key", 'tri')->first();
            return $prod->id;
        } else if ($shortform == "bi") {
            $prod =    TradeProduct::where("filter_key", 'bi')->first();
            return $prod->id;
        }
    }

    public function requestForMoney(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $tradeCGOffers = json_decode($request->tradeOffers, true);
            $tradeInvitedCGs = json_decode($request->invited, true);

            $equestdata = [
                'source' =>  $request->source,
                'trade_product_id' => $request->collateralType,
                'copied_from_id' => ($request->source == "copied") ? $request->source : "",
                'currency' => ($request->currency) ? $request->currency : "",
                'reference_no' => generateCGTradeRequestReference(),
                'organization_id' => $user->organization->id,
                'user_id' => $user->id
            ];
            $cgTradeRequest = CGTradeRequest::create($equestdata);
            $theinvited = [];
            if ($cgTradeRequest) {

                //save the offers
                foreach ($tradeCGOffers as $tradeCGOffer) {

                    $dataOffer = [
                        'offer_reference_no' => generateCGTradeRequestOfferReference(),
                        'offer_minimum_amount' => $tradeCGOffer['min'],
                        'offer_maximum_amount' =>  $tradeCGOffer['max'],
                        // 'offer_trade_product_id' =>  $tradeCGOffer['product'],
                        'offer_trade_product_id' =>  $this->getTradeProductTypeBasedOnShortForm($request->collateralType),
                        'offer_term_length_type' => $tradeCGOffer['term_length_type'],
                        'offer_term_length' => $tradeCGOffer['term_length'],
                        'currency' => $tradeCGOffer['currency'],
                        'trade_collateral_basket_id' => $tradeCGOffer['term_length'],
                        'rate_valid_until' => changeDateFromLocalToUTC($tradeCGOffer['rate_valid_until']),
                        'interest_calculation_options_id' =>  $tradeCGOffer['convention_id'],
                    ];
                    if ($tradeCGOffer['collateralType'] == "tri") {

                        if ($tradeCGOffer['basket'] == 0 || $tradeCGOffer['basket'] == "0") {
                            $ctobject = Organization::where("id", $tradeCGOffer['ct'])->first();
                            $ct = $tradeCGOffer['ct'];
                            $trade_tri_basket_third_party_id = $this->getDummyTripartyBasketId($ctobject, $tradeCGOffer['primaryBasket']);
                            $dataOffer['trade_tri_basket_third_party_id'] = $trade_tri_basket_third_party_id->id;
                        } else {

                            $dataOffer['trade_tri_basket_third_party_id'] = $tradeCGOffer['basket'];
                        }
                        $dataOffer['trade_organization_collateral_c_u_s_i_p_s_id'] = "";
                    } else if ($tradeCGOffer['collateralType'] == "bi") {

                        $dataOffer['trade_tri_basket_third_party_id'] = "";

                        if ($tradeCGOffer['collateral_id'] == 0 || $tradeCGOffer['collateral_id'] == "0") {

                            $orgCollateralId = $this->getDummyBileteralId($tradeCGOffer['primaryCollateral']);
                            //     $dataOffer['trade_organization_collateral_c_u_s_i_p_s_id'] = $orgCollateralId->id;

                        } else {

                            //   $dataOffer['trade_organization_collateral_c_u_s_i_p_s_id'] = $tradeCGOffer['collateral_id'];
                        }
                    }
                    $dataOffer['rate_type'] = $tradeCGOffer['rate_type'];
                    if ($tradeCGOffer['rate_type'] === 'fixed') {
                        $dataOffer['variable_rate_value'] = 0.00;
                        $dataOffer['fixed_rate'] = $tradeCGOffer['entered_rate'];
                        $netInterestRate = $tradeCGOffer['entered_rate'];
                    } else {
                        $ratedetails = getSystemSettings($tradeCGOffer['rate_type']);
                        $dataOffer['variable_rate_value'] = $ratedetails->value;
                        $dataOffer['rate_operator'] = $tradeCGOffer['operator'];
                        $dataOffer['fixed_rate'] = $tradeCGOffer['entered_rate'];

                        if ($tradeCGOffer['operator'] == "+") {
                            $netInterestRate =  floatval($ratedetails->value) + floatval($tradeCGOffer['entered_rate']);
                        } else if ($tradeCGOffer['operator'] == "-") {
                            $netInterestRate =  floatval($ratedetails->value) - floatval($tradeCGOffer['entered_rate']);
                        }
                    }
                    $dataOffer['offer_interest_rate'] = $netInterestRate;
                    $dataInvited = [
                        'c_g_trade_request_id' =>  $cgTradeRequest->id,
                        'organization_id' => $tradeCGOffer['ct']
                    ];
                    $invitedFound = CGTradeRequestInvitedCT::where($dataInvited)->first();
                    if ($invitedFound) {
                        $dataOffer['c_g_trade_request_invited_c_t_id'] = $invitedFound->id;
                    } else {
                        array_push($theinvited, $tradeCGOffer['ct']);
                        $dataInvited['invitation_date'] = getUTCDateNow();
                        $invitedFound = CGTradeRequestInvitedCT::create($dataInvited);
                        $dataOffer['c_g_trade_request_invited_c_t_id'] = $invitedFound->id;
                    }
                    //save offer
                    CGTradeRequestInvitedCTOffer::create($dataOffer);
                    //save offer
                }
                //send emails to invited CTS               
                $sendobjectsbyct = [];
                foreach ($theinvited as $invit) {
                    $org = Organization::where("id", $invit)->first();
                    $sendoffers = CGTradeRequestInvitedCTOffer::with(['product'])
                        ->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
                        ->where("c_g_trade_request_invited_c_t_s.organization_id", $invit)
                        ->where("c_g_trade_request_invited_c_t_s.c_g_trade_request_id", $cgTradeRequest->id)
                        ->select([
                            'c_g_trade_request_invited_c_t_id',
                            'c_g_trade_request_invited_c_t_offers.id',
                            'c_g_trade_request_invited_c_t_s.organization_id',
                            'offer_reference_no',
                            'offer_minimum_amount',
                            'offer_maximum_amount',
                            'offer_trade_product_id',
                            'offer_term_length_type',
                            'offer_term_length',
                            'rate_valid_until',
                            'interest_calculation_options_id',
                            'trade_organization_collateral_c_u_s_i_p_s_id',
                            'trade_tri_basket_third_party_id',
                            'rate_type',
                            'variable_rate_value',
                            'fixed_rate',
                            'offer_interest_rate',
                            'c_g_trade_request_invited_c_t_offers.currency',
                            'rate_valid_until'
                        ])
                        ->get();
                    array_push($sendobjectsbyct, $sendoffers);
                    $this->sendCTRequestNotificationTo($sendoffers, $org, "ct");
                }
                //send emails to invited CTS
                //send emails to owner
                $sendofferstocg = CGTradeRequestInvitedCTOffer::join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
                    ->join("c_g_trade_requests", "c_g_trade_requests.id", "=", "c_g_trade_request_invited_c_t_s.c_g_trade_request_id")
                    ->where("c_g_trade_request_invited_c_t_s.c_g_trade_request_id",  $cgTradeRequest->id)
                    ->select([
                        'c_g_trade_request_invited_c_t_id',
                        'c_g_trade_request_invited_c_t_offers.id',
                        'c_g_trade_request_invited_c_t_s.organization_id',
                        'offer_reference_no',
                        'offer_minimum_amount',
                        'offer_maximum_amount',
                        'offer_trade_product_id',
                        'offer_term_length_type',
                        'offer_term_length',
                        'rate_valid_until',
                        'interest_calculation_options_id',
                        'trade_organization_collateral_c_u_s_i_p_s_id',
                        'trade_tri_basket_third_party_id',
                        'rate_type',
                        'variable_rate_value',
                        'fixed_rate',
                        'offer_interest_rate',
                        'c_g_trade_request_invited_c_t_offers.currency',
                        'rate_valid_until'
                    ])
                    ->get();
                $this->sendCTRequestNotificationTo($sendofferstocg, $user->organization, "cg");
                //send emails to owner

                //save the offers 
                DB::commit();
                $savedObject = CGTradeRequest::with([
                    'CGTradeRequestInvitedCT' => function ($query) {
                        $query->select('id', 'c_g_trade_request_id', 'invited_user_id')
                            ->with(['CGTradeRequestInvitedCTOffer' => function ($offerQuery) {
                                $offerQuery->select('id', 'c_g_trade_request_invited_c_t_id');
                            }]);
                    }
                ])->where('id', $cgTradeRequest->id)->first();
                return response()->json(['message' => 'Data saved successfully!', 'savedObject' => $savedObject, 'theinvited' => $theinvited, 'tocgmail' => $sendofferstocg, 'toctmail' => $sendobjectsbyct]);
            } else {
                return response()->json(['message' => 'Failed to save data.'], 500);
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed. ' . $th->getMessage(),
            ], 400);
        }
    }

    public function getPublishedRequests(Request $request)
    {
        $user = Auth::user();
        $requests = CGTradeRequest::with([
            'CGTradeRequestInvitedCT' => function ($query) {
                $query->select('id', 'c_g_trade_request_id', 'invited_user_id')
                    ->with(['CGTradeRequestInvitedCTOffer' => function ($offerQuery) {
                        $offerQuery->select('id', 'c_g_trade_request_invited_c_t_id');
                    }]);
            }
        ])->where('organization_id', $user->organization->id)
            ->orderBy("id", "DESC")
            ->paginate($request->per_page ?? 10);

        return $requests;
    }

    public function getPublishedRequestOffers(Request $request)
    {
        $user = Auth::user();
        $requests = CGTradeRequestInvitedCTOffer::with([
            'product',
            'interestCalculationOption',
            'basket',
            'biColleteral',
            'CGTradeRequestInvitedCT' => function ($query) {
                $query->select('id', 'organization_id', 'c_g_trade_request_id');
            },
            'CGTradeRequestInvitedCT.ct' => function ($query) {
                $query->select("*");
            },
            'purchaseHistory' => function ($query) {
                $query->select('*');
            },
            'counters' => function ($query) {
                $query->select('*');
            },
        ])->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
            ->where("c_g_trade_request_invited_c_t_s.c_g_trade_request_id", CustomEncoder::urlValueDecrypt($request->req))
            ->whereIn('c_g_trade_request_invited_c_t_offers.offer_status', ['ACTIVE']);
        //search
        if ($request->filled("search")) {
            $requests->where(function ($query) use ($request) {
                $query->leftJoin("trade_organization_collaterals", "trade_organization_collaterals.id", "=", "c_g_trade_request_invited_c_t_offers.trade_organization_collateral_c_u_s_i_p_s_id");
                $query->leftJoin("trade_organization_collateral_c_u_s_i_p_s", "trade_organization_collateral_c_u_s_i_p_s.trade_organization_collateral_id", "=", "trade_organization_collaterals.id");
                $query->leftJoin("trade_collaterals", "trade_collaterals.id", "=", "trade_organization_collateral_c_u_s_i_p_s.trade_collateral_id");


                $query->where("c_g_trade_request_invited_c_t_offers.offer_reference_no", "like", "%" . $request->search . "%")
                    ->orWhere("c_g_trade_request_invited_c_t_offers.offer_minimum_amount", "like", "%" . $request->search . "%")
                    ->orWhere("c_g_trade_request_invited_c_t_offers.offer_maximum_amount", "like", "%" . $request->search . "%")
                    ->orWhere("trade_collaterals.collateral_name", "like", "%" . $request->search . "%");
            });
        }
        //search
        //filters here 
        //settlement date
        if ($request->filled("settlement")) {
            $settlementobject = explode(",", $request->settlement);
            if ((stripslashes($settlementobject[0]) != "") && (stripslashes($settlementobject[1]) != "")) {

                $dateString1 = $settlementobject[0];
                $dateString2 = $settlementobject[1];
                $mindate = new DateTime($dateString1);
                $maxdate = new DateTime($dateString2);
                $requests->whereBetween(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
            } else {
                if (stripslashes($settlementobject[0]) != "") {
                    $dateString1 = $settlementobject[0];
                    $mindate = new DateTime($dateString1);
                    $requests->where(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
                if (stripslashes($settlementobject[1]) != "") {
                    $dateString2 = $settlementobject[1];
                    $maxdate = new DateTime($dateString2);
                    $requests->where(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
            }
        }
        ///settlement date
        //rate
        if ($request->filled("rate")) {

            $rate = explode(",", $request->rate);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_interest_rate', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_interest_rate', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {

                $requests->whereBetween('c_g_trade_request_invited_c_t_offers.offer_interest_rate', [$rate[0], $rate[1]]);
            }
        }
        //rate
        //term_length
        if ($request->filled("term_length") && $request->filled("term_length_type")) {
            $termLengthType = $request->term_length_type;
            $explodedRequestInput = explode(",", $request->term_length);
            if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] === '0') {
                $closingdateFilter = [];
            } else {
                $requests->where('c_g_trade_request_invited_c_t_offers.term_length_type', $termLengthType);
                if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] === '0') {
                    $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length', ">=", $explodedRequestInput[0]);
                } else if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] != '0') {
                    $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length', "<=", $explodedRequestInput[1]);
                } else if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] != '0') {
                    $requests->whereBetween('c_g_trade_request_invited_c_t_offers.offer_term_length', $explodedRequestInput);
                }
            }
        }
        //term_length
        //rate
        if ($request->filled("investment_amount")) {

            $rate = explode(",", $request->investment_amount);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_minimum_amount', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_maximum_amount', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_minimum_amount', ">=", $rate[0]);
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_maximum_amount', "<=", $rate[1]);
            }
        }
        //rate
        //filters here
        $requests = $requests->select([
            'c_g_trade_request_invited_c_t_id',
            'c_g_trade_request_invited_c_t_s.id',
            'offer_reference_no',
            'offer_minimum_amount',
            'offer_maximum_amount',
            'offer_trade_product_id',
            'offer_term_length_type',
            'offer_term_length',
            'rate_valid_until',
            'interest_calculation_options_id',
            'trade_organization_collateral_c_u_s_i_p_s_id',
            'trade_tri_basket_third_party_id',
            'rate_type',
            'variable_rate_value',
            'fixed_rate',
            'offer_interest_rate',
            'c_g_trade_request_invited_c_t_offers.currency',
            'rate_valid_until'
        ])
            ->orderBy("id", "DESC")
            ->get();
        return $requests;
    }

    public function getPublishedRequestsOfferDetails(Request $request)
    {
        $user = Auth::user();
        $requests = CGTradeRequestInvitedCTOffer::with([
            'product',
            'interestCalculationOption',
            'basket',
            'biColleteral',
            'CGTradeRequestInvitedCT' => function ($query) {
                $query->select('id', 'organization_id', 'c_g_trade_request_id');
            },
            'CGTradeRequestInvitedCT.ct' => function ($query) {
                $query->select('id', 'name','logo');
            },
            'purchaseHistory' => function ($query) {
                $query->select('*');
            },
            'counters' => function ($query) {
                $query->select('*');
            }
        ])->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
            ->join("c_g_trade_requests", "c_g_trade_requests.id", "=", "c_g_trade_request_invited_c_t_s.c_g_trade_request_id")
            ->where('c_g_trade_requests.organization_id', $user->organization->id)
            ->whereIn('c_g_trade_request_invited_c_t_offers.offer_status', ['ACTIVE']);
        //search
        if ($request->filled("search")) {
            $requests->where(function ($query) use ($request) {
                $query->where("c_g_trade_request_invited_c_t_offers.offer_reference_no", "like", "%" . $request->search . "%")
                    ->orWhere("c_g_trade_request_invited_c_t_offers.offer_minimum_amount", "like", "%" . $request->search . "%")
                    ->orWhere("c_g_trade_request_invited_c_t_offers.offer_maximum_amount", "like", "%" . $request->search . "%");
            });
        }
        //search
        //filters here 
        //settlement date
        if ($request->filled("settlement")) {
            $settlementobject = explode(",", $request->settlement);
            if ((stripslashes($settlementobject[0]) != "") && (stripslashes($settlementobject[1]) != "")) {

                $dateString1 = $settlementobject[0];
                $dateString2 = $settlementobject[1];
                $mindate = new DateTime($dateString1);
                $maxdate = new DateTime($dateString2);
                $requests->whereBetween(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
            } else {
                if (stripslashes($settlementobject[0]) != "") {
                    $dateString1 = $settlementobject[0];
                    $mindate = new DateTime($dateString1);
                    $requests->where(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
                if (stripslashes($settlementobject[1]) != "") {
                    $dateString2 = $settlementobject[1];
                    $maxdate = new DateTime($dateString2);
                    $requests->where(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
            }
        }
        ///settlement date
        //rate
        if ($request->filled("rate")) {

            $rate = explode(",", $request->rate);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_interest_rate', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_interest_rate', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {

                $requests->whereBetween('c_g_trade_request_invited_c_t_offers.offer_interest_rate', [$rate[0], $rate[1]]);
            }
        }
        //rate
        //term_length
        if ($request->filled("term_length") && $request->filled("term_length_type")) {
            $termLengthType = $request->term_length_type;
            $explodedRequestInput = explode(",", $request->term_length);
            if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] === '0') {
                $closingdateFilter = [];
            } else {
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length', $termLengthType);
                if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] === '0') {
                    $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length', ">=", $explodedRequestInput[0]);
                } else if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] != '0') {
                    $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length', "<=", $explodedRequestInput[1]);
                } else if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] != '0') {
                    $requests->whereBetween('c_g_trade_request_invited_c_t_offers.offer_term_length', $explodedRequestInput);
                }
            }
        }
        //term_length
        //rate
        if ($request->filled("investment_amount")) {

            $rate = explode(",", $request->investment_amount);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_minimum_amount', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_maximum_amount', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_minimum_amount', ">=", $rate[0]);
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_maximum_amount', "<=", $rate[1]);
            }
        }
        //rate
        //filters here
        $requests->where('c_g_trade_request_invited_c_t_offers.id', CustomEncoder::urlValueDecrypt($request->offerId));
        $requests = $requests->select([
            'c_g_trade_request_invited_c_t_id',
            'c_g_trade_request_invited_c_t_s.id',
            'c_g_trade_request_invited_c_t_s.organization_id',
            'offer_reference_no',
            'offer_minimum_amount',
            'offer_maximum_amount',
            'offer_trade_product_id',
            'offer_term_length_type',
            'offer_term_length',
            'rate_valid_until',
            'interest_calculation_options_id',
            'trade_organization_collateral_c_u_s_i_p_s_id',
            'trade_tri_basket_third_party_id',
            'rate_type',
            'variable_rate_value',
            'fixed_rate',
            'offer_interest_rate',
            'c_g_trade_request_invited_c_t_offers.currency',
            'rate_valid_until'
        ])
            ->orderBy("id", "DESC")
            ->paginate($request->per_page ?? 10);
        return $requests;
    }
    public function getPublishedRequestsOffers(Request $request)
    {
        $user = Auth::user();
        $requests = CGTradeRequestInvitedCTOffer::with([
            'product',
            'interestCalculationOption',
            'basket',
            'biColleteral',
            'CGTradeRequestInvitedCT' => function ($query) {
                $query->select('id', 'organization_id', 'c_g_trade_request_id');
            },
            'CGTradeRequestInvitedCT.ct' => function ($query) {
                $query->select('id', 'name', 'logo');
            },
            'purchaseHistory' => function ($query) {
                $query->select('*');
            },
            'counters' => function ($query) {
                $query->select('*');
            }
        ])->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
            ->join("c_g_trade_requests", "c_g_trade_requests.id", "=", "c_g_trade_request_invited_c_t_s.c_g_trade_request_id")
            ->join("organizations", "organizations.id", "=", "c_g_trade_request_invited_c_t_s.organization_id")
            ->where('c_g_trade_requests.organization_id', $user->organization->id)
            ->whereIn('c_g_trade_request_invited_c_t_offers.offer_status', ['ACTIVE']);
        //search
        if ($request->filled("search")) {

            $requests->join("trade_tri_basket_third_parties", "trade_tri_basket_third_parties.id", "=", "c_g_trade_request_invited_c_t_offers.trade_tri_basket_third_party_id");
            $requests->join("trade_collateral_baskets", "trade_collateral_baskets.id", "=", "trade_tri_basket_third_parties.trade_collateral_basket_id");
            $requests->join("trade_basket_types", "trade_basket_types.id", "=", "trade_collateral_baskets.trade_basket_type_id");
            $requests->where(function ($query) use ($request) {
                $query->where("c_g_trade_request_invited_c_t_offers.offer_reference_no", "like", "%" . $request->search . "%")
                    ->orWhere("c_g_trade_request_invited_c_t_offers.offer_minimum_amount", "like", "%" . $request->search . "%")
                    ->orWhere("c_g_trade_request_invited_c_t_offers.offer_maximum_amount", "like", "%" . $request->search . "%")
                    ->orWhere("trade_basket_types.basket_name", "like", "%" . $request->search . "%")
                    ->orWhere("organizations.name", "like", "%" . $request->search . "%");
            });
        }
        //search
        //filters here 
        //settlement date
        if ($request->filled("settlement")) {
            $settlementobject = explode(",", $request->settlement);
            if ((stripslashes($settlementobject[0]) != "") && (stripslashes($settlementobject[1]) != "")) {

                $dateString1 = $settlementobject[0];
                $dateString2 = $settlementobject[1];
                $mindate = new DateTime($dateString1);
                $maxdate = new DateTime($dateString2);
                $requests->whereBetween(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
            } else {
                if (stripslashes($settlementobject[0]) != "") {
                    $dateString1 = $settlementobject[0];
                    $mindate = new DateTime($dateString1);
                    $requests->where(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
                if (stripslashes($settlementobject[1]) != "") {
                    $dateString2 = $settlementobject[1];
                    $maxdate = new DateTime($dateString2);
                    $requests->where(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
            }
        }
        ///settlement date
        //rate
        if ($request->filled("rate")) {

            $rate = explode(",", $request->rate);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_interest_rate', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_interest_rate', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {

                $requests->whereBetween('c_g_trade_request_invited_c_t_offers.offer_interest_rate', [$rate[0], $rate[1]]);
            }
        }
        //rate
        //term_length
        if ($request->filled("term_length") && $request->filled("term_length_type")) {
            $termLengthType = $request->term_length_type;
            $explodedRequestInput = explode(",", $request->term_length);
            if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] === '0') {
                $closingdateFilter = [];
            } else {
                $requests->where('c_g_trade_request_invited_c_t_offers.term_length_type', $termLengthType);
                if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] === '0') {
                    $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length', ">=", $explodedRequestInput[0]);
                } else if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] != '0') {
                    $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length', "<=", $explodedRequestInput[1]);
                } else if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] != '0') {
                    $requests->whereBetween('c_g_trade_request_invited_c_t_offers.offer_term_length', $explodedRequestInput);
                }
            }
        }
        //term_length
        //rate
        if ($request->filled("investment_amount")) {

            $rate = explode(",", $request->investment_amount);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_minimum_amount', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_maximum_amount', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_minimum_amount', ">=", $rate[0]);
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_maximum_amount', "<=", $rate[1]);
            }
        }
        //rate
        //filters here
        $requests = $requests->select([
            'c_g_trade_request_invited_c_t_id',
            'c_g_trade_request_invited_c_t_offers.id',
            'c_g_trade_request_invited_c_t_s.organization_id',
            'offer_reference_no',
            'offer_minimum_amount',
            'offer_maximum_amount',
            'offer_trade_product_id',
            'offer_term_length_type',
            'offer_term_length',
            'rate_valid_until',
            'interest_calculation_options_id',
            'trade_organization_collateral_c_u_s_i_p_s_id',
            'trade_tri_basket_third_party_id',
            'rate_type',
            'variable_rate_value',
            'fixed_rate',
            'offer_interest_rate',
            'c_g_trade_request_invited_c_t_offers.currency',
            'rate_valid_until'
        ])
            ->orderBy("id", "DESC")
            ->paginate($request->per_page ?? 10);
        return $requests;
    }

    public function getPublishedRequestsMaturedOffers(Request $request)
    {
        $user = Auth::user();
        $requests = CGTradeRequestInvitedCTOffer::with([
            'product',
            'interestCalculationOption',
            'basket',
            'biColleteral',
            'CGTradeRequestInvitedCT' => function ($query) {
                $query->select('id', 'organization_id', 'c_g_trade_request_id');
            },
            'CGTradeRequestInvitedCT.ct' => function ($query) {
                $query->select('id', 'name', 'logo');
            },
            'purchaseHistory' => function ($query) {
                $query->select('*');
            },
            'counters' => function ($query) {
                $query->select('*');
            }
        ])->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
            ->join("c_g_trade_requests", "c_g_trade_requests.id", "=", "c_g_trade_request_invited_c_t_s.c_g_trade_request_id")
            ->join("c_t_trade_request_offer_deposits", "c_t_trade_request_offer_deposits.c_g_trade_request_invited_c_t_offer_id", "=", "c_g_trade_request_invited_c_t_offers.id")
            ->where('c_g_trade_requests.organization_id', $user->organization->id)
            ->whereIn('c_t_trade_request_offer_deposits.deposit_status', ['MATURED']);
        //filters here 
        //search
        if ($request->filled("search")) {
            $requests->where(function ($query) use ($request) {
                $query->where("c_g_trade_request_invited_c_t_offers.offer_reference_no", "like", "%" . $request->search . "%")
                    ->orWhere("c_g_trade_request_invited_c_t_offers.offer_minimum_amount", "like", "%" . $request->search . "%")
                    ->orWhere("c_g_trade_request_invited_c_t_offers.offer_maximum_amount", "like", "%" . $request->search . "%");
            });
        }
        //search
        //settlement date
        if ($request->filled("settlement")) {
            $settlementobject = explode(",", $request->settlement);
            if ((stripslashes($settlementobject[0]) != "") && (stripslashes($settlementobject[1]) != "")) {

                $dateString1 = $settlementobject[0];
                $dateString2 = $settlementobject[1];
                $mindate = new DateTime($dateString1);
                $maxdate = new DateTime($dateString2);
                $requests->whereBetween(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
            } else {
                if (stripslashes($settlementobject[0]) != "") {
                    $dateString1 = $settlementobject[0];
                    $mindate = new DateTime($dateString1);
                    $requests->where(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
                if (stripslashes($settlementobject[1]) != "") {
                    $dateString2 = $settlementobject[1];
                    $maxdate = new DateTime($dateString2);
                    $requests->where(DB::raw('DATE(c_g_trade_request_invited_c_t_offers.rate_valid_until)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
            }
        }
        ///settlement date
        //rate
        if ($request->filled("rate")) {

            $rate = explode(",", $request->rate);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_interest_rate', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_interest_rate', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {

                $requests->whereBetween('c_g_trade_request_invited_c_t_offers.offer_interest_rate', [$rate[0], $rate[1]]);
            }
        }
        //rate
        //term_length
        if ($request->filled("term_length") && $request->filled("term_length_type")) {
            $termLengthType = $request->term_length_type;
            $explodedRequestInput = explode(",", $request->term_length);
            if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] === '0') {
                $closingdateFilter = [];
            } else {
                $requests->where('c_g_trade_request_invited_c_t_offers.term_length_type', $termLengthType);
                if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] === '0') {
                    $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length', ">=", $explodedRequestInput[0]);
                } else if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] != '0') {
                    $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length', "<=", $explodedRequestInput[1]);
                } else if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] != '0') {
                    $requests->whereBetween('c_g_trade_request_invited_c_t_offers.offer_term_length', $explodedRequestInput);
                }
            }
        }
        //term_length
        //rate
        if ($request->filled("investment_amount")) {

            $rate = explode(",", $request->investment_amount);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_minimum_amount', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $requests->where('c_g_trade_request_invited_c_t_offers.offer_maximum_amount', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_minimum_amount', ">=", $rate[0]);
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_maximum_amount', "<=", $rate[1]);
            }
        }
        //rate
        //filters here
        $requests = $requests->select([
            'c_g_trade_request_invited_c_t_id',
            'c_g_trade_request_invited_c_t_s.id',
            'c_g_trade_request_invited_c_t_s.organization_id',
            'offer_reference_no',
            'offer_minimum_amount',
            'offer_maximum_amount',
            'offer_trade_product_id',
            'offer_term_length_type',
            'offer_term_length',
            'rate_valid_until',
            'interest_calculation_options_id',
            'trade_organization_collateral_c_u_s_i_p_s_id',
            'trade_tri_basket_third_party_id',
            'rate_type',
            'variable_rate_value',
            'fixed_rate',
            'offer_interest_rate',
            'c_g_trade_request_invited_c_t_offers.currency',
            'rate_valid_until'
        ])
            ->orderBy("id", "DESC")
            ->paginate($request->per_page ?? 10);
        return $requests;
    }
    public function getSingleMarketPlaceOffer(Request $request)
    {
        $user = Auth::user();
        $requests = CGTradeRequestInvitedCTOffer::with([
            'product',
            'interestCalculationOption',
            'basket',
            'biColleteral',
            'CGTradeRequestInvitedCT' => function ($query) {
                $query->select('id', 'organization_id', 'c_g_trade_request_id');
            },
            'CGTradeRequestInvitedCT.ct' => function ($query) {
                $query->select('id', 'name','logo');
            },
            'purchaseHistory' => function ($query) {
                $query->select('*');
            },
            'counters.interestCalculationOption' => function ($query) {
                $query->select('*');
            }
        ])->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
            ->join("c_g_trade_requests", "c_g_trade_requests.id", "=", "c_g_trade_request_invited_c_t_s.c_g_trade_request_id")
            // ->where('c_g_trade_request_invited_c_t_s.organization_id', $user->organization->id)
            ->where('c_g_trade_request_invited_c_t_offers.id', CustomEncoder::urlValueDecrypt($request->offerId))
            ->whereIn('c_g_trade_request_invited_c_t_offers.offer_status', ['ACTIVE', 'CLOSED_ON_PURCHASE']);

        $requests = $requests->select([
            'c_g_trade_request_invited_c_t_id',
            'c_g_trade_request_invited_c_t_offers.id',
            'offer_reference_no',
            'offer_minimum_amount',
            'offer_maximum_amount',
            'offer_trade_product_id',
            'offer_term_length_type',
            'offer_term_length',
            'rate_valid_until',
            'interest_calculation_options_id',
            'trade_organization_collateral_c_u_s_i_p_s_id',
            'trade_tri_basket_third_party_id',
            'rate_type',
            'variable_rate_value',
            'fixed_rate',
            'offer_interest_rate',
            'c_g_trade_request_invited_c_t_offers.currency',
            'rate_valid_until',
            'rate_operator'
        ])->first();

        return  $requests;
    }
    public function updateRequestOffer(Request $request)
    {
        $dataOffer = [
            'offer_minimum_amount' => $request->min,
            'offer_maximum_amount' =>  $request->max,
            // 'offer_trade_product_id' =>  $tradeCGOffer['product'],
            'offer_trade_product_id' =>  $this->getTradeProductTypeBasedOnShortForm($request->collateralType),
            'offer_term_length_type' => $request->term_length_type,
            'offer_term_length' => $request->term_length,
            'currency' => $request->currency,
            'trade_collateral_basket_id' => $request->term_length,
            'rate_valid_until' => changeDateFromLocalToUTC($request->rate_valid_until),
            'interest_calculation_options_id' =>  $request->convention_id,
        ];
        if ($request->collateralType == "tri") {

            if ($request->basket == 0 || $request->basket == "0") {
                $ctobject = Organization::where("id", $request->ct)->first();
                $ct = $request->ct;
                $trade_tri_basket_third_party_id = $this->getDummyTripartyBasketId($ctobject, $request->primaryBasket);
                $dataOffer['trade_tri_basket_third_party_id'] = $trade_tri_basket_third_party_id->id;
            } else {

                $dataOffer['trade_tri_basket_third_party_id'] = $request->basket;
            }
            $dataOffer['trade_organization_collateral_c_u_s_i_p_s_id'] = "";
        } else if ($request->collateralType == "bi") {

            $dataOffer['trade_tri_basket_third_party_id'] = "";

            if ($request->collateral_id == 0 || $request->collateral_id == "0") {

                $orgCollateralId = $this->getDummyBileteralId($request->primaryCollateral);
                //     $dataOffer['trade_organization_collateral_c_u_s_i_p_s_id'] = $orgCollateralId->id;

            } else {

                //   $dataOffer['trade_organization_collateral_c_u_s_i_p_s_id'] = $tradeCGOffer['collateral_id'];
            }
        }
        $dataOffer['rate_type'] = $request->rate_type;
        if ($request->rate_type === 'fixed') {
            $dataOffer['variable_rate_value'] = 0.00;
            $dataOffer['fixed_rate'] = $request->entered_rate;
            $netInterestRate = $request->entered_rate;
        } else {
            $ratedetails = getSystemSettings($request->rate_type);
            $dataOffer['variable_rate_value'] = $ratedetails->value;
            $dataOffer['rate_operator'] = $request->operator;
            $dataOffer['fixed_rate'] = $request->entered_rate;

            if ($request->operator == "+") {
                $netInterestRate =  floatval($ratedetails->value) + floatval($request->entered_rate);
            } else if ($request->operator == "-") {
                $netInterestRate =  floatval($ratedetails->value) - floatval($request->entered_rate);
            }
        }
        $dataOffer['offer_interest_rate'] = $netInterestRate;
        try {
            DB::beginTransaction();
            //save offer
            $reponseData = [];
            $code = 200;
            $offerToUpdate = CGTradeRequestInvitedCTOffer::where('id', CustomEncoder::urlValueDecrypt($request->offerID))->first();
            if ($offerToUpdate) {
                $offerToUpdate->update($dataOffer);
                $reponseData['message'] = "Updated successfully";
                $reponseData['success'] = 1;
                $reponseData['data'] = [];
                $reponseData['found'] = $offerToUpdate;
                $reponseData['offerId'] =CustomEncoder::urlValueDecrypt($request->offerID);
                $code = 200;
            } else {
                $reponseData['message'] = "Failed";
                $reponseData['success'] = 0;
                $reponseData['data'] = [];
                $reponseData['found'] = $offerToUpdate;
                $reponseData['offerId'] =CustomEncoder::urlValueDecrypt($request->offerID);
                $code = 200;
            }
            DB::commit();
            return response()->json($reponseData, $code);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $th->getMessage(),

            ], 400);
        }
        //save offer
    }
    public function withdrawRequestOffer(Request $request)
    {
        $user = Auth::user();
        $offer = CGTradeRequestInvitedCTOffer::where('id', CustomEncoder::urlValueDecrypt($request->offerID))->first();
        if ($offer) {
            $offer->update([
                'offer_status' => 'OFFER_WITHDRAWN'
            ]);
            return response()->json([
                "success" => 1,
                'message' => 'Offer has been successfully withdrawn',

            ], 200);
        } else {
            return response()->json([
                "success" => 0,
                'message' => 'Offer has not been successfully withdrawn',

            ], 400);
        }
    }

}

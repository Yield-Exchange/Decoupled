<?php

namespace App\Services\CT;

use App\Constants;
use App\CustomEncoder;
use App\Events\CTTradeRequestOfferChatEvent;
use App\Events\CTTradeRequestOfferDepositChatEvent;
use App\Jobs\GenerateMTFiles;
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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;

use Illuminate\Support\Facades\Log;

class TradeCTService
{

    protected $tradehandlemarketoffer;

    public function __construct(CTHandleTradeMarketOfferService $tradehandlemarketoffer)
    {
        $this->tradehandlemarketoffer = $tradehandlemarketoffer;
    }


    public function getTradeRequests(Request $request)
    {
        $user = Auth::user();
        $reqs = CTTradeRequest::with(['inviter', 'invitedCGs' => function ($query) use ($request) {}])
            ->where('c_t_trade_requests.organization_id', $user->organization->id);
        //search
        if ($request->filled("search")) {
            $reqs->where(function ($query) use ($request) {
                $query->where("c_t_trade_requests.reference_no", "like", "%" . $request->search . "%");
            });
        }
        //search
        if ($request->type == "new") {
            $reqs->whereIn('c_t_trade_requests.request_status', ['ACTIVE', 'UNDER_REVIEW']);
        } elseif ($request->type == "inprogress") {
            $reqs->whereIn('c_t_trade_requests.request_status', ['ACTIVE']);
        } elseif ($request->type == "active") {
            $reqs->whereIn('c_t_trade_requests.request_status', ['COMPLETED']);
        } elseif ($request->type == "history") {
            $reqs->whereIn('c_t_trade_requests.request_status', ['COMPLETED', 'WITHDRAWN']);
        }
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
                $reqs->whereBetween(DB::raw('DATE(c_t_trade_requests.settlement_date)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
            } else {
                if (stripslashes($settlementobject[0]) != "") {
                    $dateString1 = $settlementobject[0];
                    $mindate = new DateTime($dateString1);
                    $reqs->where(DB::raw('DATE(c_t_trade_requests.settlement_date)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
                if (stripslashes($settlementobject[1]) != "") {
                    $dateString2 = $settlementobject[1];
                    $maxdate = new DateTime($dateString2);
                    $reqs->where(DB::raw('DATE(c_t_trade_requests.settlement_date)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
            }
        }

        $reqs->whereHas('invitedCGs', function ($query) {
            $query->whereIn('invitation_status', ['INVITED', 'PARTICIPATED']);
        })
            ->leftJoin("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
            ->leftJoin("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.invitation_id", "=", "c_t_trade_request_invited_c_g_s.id");
            if ($request->type == "new") {
                $reqs->select([
                    'c_t_trade_requests.*',
                    'c_t_trade_request_invited_c_g_s.c_t_trade_request_id',
                    'c_t_trade_request_c_g_offers.invitation_id',
                    DB::raw('MAX(CASE WHEN c_t_trade_request_c_g_offers.offer_status = "ACTIVE" THEN c_t_trade_request_c_g_offers.offer_interest_rate ELSE NULL END) as max_offer_interest_rate'),
                    DB::raw('MIN(CASE WHEN c_t_trade_request_c_g_offers.offer_status = "ACTIVE" THEN c_t_trade_request_c_g_offers.offer_interest_rate ELSE NULL END) as min_offer_interest_rate'),
                    DB::raw('COUNT(CASE WHEN c_t_trade_request_c_g_offers.offer_status = "ACTIVE" THEN 1 END) as total_offers')
          
                ]);
            }else{
                $reqs->select([
                    'c_t_trade_requests.*',
                    'c_t_trade_request_invited_c_g_s.c_t_trade_request_id',
                    'c_t_trade_request_c_g_offers.invitation_id',
                    DB::raw('MAX(c_t_trade_request_c_g_offers.offer_interest_rate) as max_offer_interest_rate'),
                    DB::raw('MIN(c_t_trade_request_c_g_offers.offer_interest_rate) as min_offer_interest_rate'),
                    DB::raw('COUNT(c_t_trade_request_c_g_offers.id) as total_offers')
          
                ]);
            }
        $reqs->groupBy("c_t_trade_requests.id")->orderBy("id", "DESC");        
        $reqs = $reqs->paginate($request->per_page ?? 10);
        return $reqs;
    }

    public function getTradeRequest(Request $request)
    {
        $user = Auth::user();
        $id = CustomEncoder::urlValueDecrypt($request->requestId);

        $req = CTTradeRequest::with(['inviter', 'invitedCGs' => function ($query) {
            $query->whereIn('invitation_status', ['INVITED', 'PARTICIPATED']);
        }])
            ->where('c_t_trade_requests.organization_id', $user->organization->id)
            // ->whereIn('c_t_trade_requests.request_status', ['ACTIVE', 'UNDER_REVIEW'])
            ->whereHas('invitedCGs', function ($query) {
                // $query->whereIn('invitation_status', ['INVITED', 'PARTICIPATED']);
            })
            ->leftJoin("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
            ->leftJoin("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.invitation_id", "=", "c_t_trade_request_invited_c_g_s.id")
            ->select([
                'c_t_trade_requests.*',
                DB::raw('MAX(c_t_trade_request_c_g_offers.offer_interest_rate) as max_offer_interest_rate'),
                DB::raw('MIN(c_t_trade_request_c_g_offers.offer_interest_rate) as min_offer_interest_rate'),
                DB::raw('COUNT(c_t_trade_request_c_g_offers.id) as total_offers')
            ])
            ->groupBy("c_t_trade_requests.id")
            ->where('c_t_trade_requests.id', $id)
            ->first();
        if ($req) {
            return $req;
        } else {
            return response()->json([], 404);
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
    public function saveRequest(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $tradeRequests = json_decode($request->tradeRequests, true);
            $invited = json_decode($request->invited, true);
            $tobesaved = [];
            $requestids = [];
            $therequests = [];
            $theinvites = [];
            $orgs = null;
            foreach ($tradeRequests as $tradeRequest) {
                $ctreq = null;
                $tradereqsave['reference_no'] = generateTradeRequestReference();
                $tradereqsave['investment_amount'] = $tradeRequest['investment_amount'];
                $tradereqsave['term_length_type'] = $tradeRequest['term_length_type'];
                $tradereqsave['term_length'] = $tradeRequest['term_length'];
                $tradereqsave['trade_time'] = $tradeRequest['trade_date'];
                $tradereqsave['currency'] = $tradeRequest['currency'];
                $tradereqsave['request_status'] = "ACTIVE";
                $tradereqsave['organization_id'] = $user->organization->id;
                $tradereqsave['user_id'] = $user->id;
                $tradereqsave['settlement_date'] = $tradeRequest['settlementDate'];
                $tradereqsave['interest_calculation_options_id'] = $tradeRequest['convention_id'];
                // $tradereqsave['trade_allowed_settlement_period_id'] = $tradeRequest['settlementDate'];
                $ctreq = CTTradeRequest::create($tradereqsave);
                array_push($therequests, $ctreq);
                array_push($requestids, $ctreq->id);
                //preferred
                $preferredcols = $tradeRequest['preferred_collateral'];
                foreach ($preferredcols as $preferredcol) {
                    $preferedob['c_t_trade_request_id'] = $ctreq->id;
                    $preferedob['preferred_collateral_id'] = $preferredcol;
                    CTTradeRequestPreferredCollateral::create($preferedob);
                }
                //close preffered
                //invited
                $orgs = Organization::whereIn('status', ['ACTIVE', 'REVIEWING'])
                    ->where('organizations.type', 'BANK')
                    ->select([
                        'organizations.*'
                    ])->find($invited);
                foreach ($orgs as $invitedinstitution) {
                    $invitedob['invitation_date'] = getUTCDateNow(true);
                    $invitedob['organization_id'] = $invitedinstitution->id;
                    $invitedob['c_t_trade_request_id'] = $ctreq->id;
                    CTTradeRequestInvitedCG::create($invitedob);
                }
                //close invited
            }
            //get the request object
            //send mails
            $requeastsmailobject['requests'] = $therequests;
            $requeastsmailobject['sender'] = Auth::user()->organization;

            foreach ($orgs as $invite) {
                $this->sendCTRequestNotificationTo($requeastsmailobject, $invite, "cg");
            }

            $this->sendCTRequestNotificationTo($requeastsmailobject, $user->organization, "ct");

            $requeastsmailobject['orgs'] = $orgs;
            $this->sendCTRequestNotificationTo($requeastsmailobject, $invite, "admin");
            ///send mails
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Created successfully', 'data' => $therequests]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }

    public function updateRequest(Request $request)
    {

        try {
            DB::beginTransaction();
            $invited = json_decode($request->invited, true);
            $unencodedId = CustomEncoder::urlValueDecrypt($request->ctrequest);
            $thisrequest = CTTradeRequest::find($unencodedId);
            //
            if ($thisrequest) {
                $actioning_user = Auth::user()->id;
                archiveRepoTable($unencodedId, "trade_requests", $thisrequest, $actioning_user, "Update.");
            }
            //
            $tradereqsave['investment_amount'] = $request->investment_amount;
            $tradereqsave['term_length_type'] = $request->term_length_type;
            $tradereqsave['term_length'] = $request->term_length;
            $tradereqsave['trade_time'] = $request->trade_date;
            $tradereqsave['currency'] = $request->currency;
            // $tradereqsave['trade_allowed_settlement_period_id'] = $request->settlementDate;

            $tradereqsave['settlement_date'] = $request->settlementDate;
            $tradereqsave['interest_calculation_options_id'] = $request->convention_id;

            CTTradeRequest::where("id", $unencodedId)->update($tradereqsave);
            $orgs = Organization::whereIn('status', ['ACTIVE', 'REVIEWING'])
                ->where('organizations.type', 'BANK')
                ->select([
                    'organizations.*'
                ])->find($invited);

            CTTradeRequestInvitedCG::where('c_t_trade_request_id', $unencodedId)
                ->whereNotIn('organization_id', $invited)
                ->update([
                    'invitation_status' => 'UNINVITED'
                ]);
            //preferred
            $preferredcols = $request->preferred_collateral;
            $foundRecords = CTTradeRequestPreferredCollateral::where("c_t_trade_request_id", $unencodedId)->get();
            //   return $foundRecords->toArray();
            archiveRepoTable($unencodedId, "request_pref_cols", $foundRecords, $actioning_user, "Editing preferred collaterals");

            $preferredcols = json_decode($preferredcols, true);

            CTTradeRequestPreferredCollateral::where("c_t_trade_request_id", $unencodedId)->delete();
            $preferredInserts = [];
            foreach ($preferredcols as $preferredcol) {
                $preferredInserts[] = [
                    'c_t_trade_request_id' => $unencodedId,
                    'preferred_collateral_id' => $preferredcol,
                ];
            }
            if (!empty($preferredInserts)) {
                CTTradeRequestPreferredCollateral::insert($preferredInserts);
            }
            //close preffered
            foreach ($orgs as $org) {

                $invitedob['invitation_date'] = getUTCDateNow(true);
                $invitedob['organization_id'] = $org->id;
                $invitedob['c_t_trade_request_id'] = $unencodedId;
                if (!CTTradeRequestInvitedCG::where('c_t_trade_request_id', $unencodedId)->where('organization_id', $org->id)->exists()) {
                    CTTradeRequestInvitedCG::create($invitedob);
                } else {
                    $invited = CTTradeRequestInvitedCG::where('c_t_trade_request_id', $unencodedId)->where('organization_id', $org->id)->first();
                    $invited->update($invitedob);
                }

                $this->sendCTRequestNotificationTo($thisrequest, $org, "cg");
                $this->sendCTRequestNotificationTo($thisrequest, $org, "admin");
            }






            DB::commit();
            $thisupdatedrequest = CTTradeRequest::with(['invitedCGs'])->where("id", $unencodedId)->first();
            return response()->json(['success' => true, 'message' => 'Updated successfully', 'data' => $thisupdatedrequest]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function withdrawRequest(Request $request)
    {
        try {
            DB::beginTransaction();
            $unencodedId = CustomEncoder::urlValueDecrypt($request->ctrequest);
            $thisrequest = CTTradeRequest::where("id", $unencodedId)->where("request_status", "ACTIVE")->first();
            $actioning_user = Auth::user()->id;
            //
            if ($thisrequest) {
                $actioning_user = Auth::user()->id;
                archiveRepoTable($unencodedId, "trade_requests", $thisrequest, $actioning_user, "Withdrawal of Request.");
            }
            //
            $responsemessage = "";
            $responsestatus = true;
            if ($thisrequest) {
                //update request status
                $thisrequest->update(['request_status' => 'WITHDRAWN', 'request_withdrawal_reason' => $request->reason]);
                //update withdrawal was successful via email
                $orgg = Organization::where("id", $thisrequest->organization_id)->first();
                $this->sendCTRequestwithdrawNotificationToCG($thisrequest, $orgg);
                //update withdrawal was successful via email
                //update request status
                //update present offers
                $invitedcgs = CTTradeRequestInvitedCG::with(['offers'])->where('c_t_trade_request_id', $unencodedId)->get();
                foreach ($invitedcgs as $invitedcg) {
                    //alert cg of the withdraw via mail
                    $org = Organization::where("id", $invitedcg->organization_id)->first();
                    $this->sendCTRequestwithdrawNotificationToCT($thisrequest, $org);
                    //alert cg of the withdraw via mail
                    //update offers
                    if ($invitedcg->offers) {
                        foreach ($invitedcg->offers as $offer) {
                            CTTradeRequestCGOffer::where("id", $offer)->update(['offer_status' => "REQUEST_WITHDRAWN"]);
                        }
                    }
                    ///update offers
                }
                //update present offers
                $responsemessage = "Request withdrawn successfully";
            } else {
                $responsestatus = false;
                $responsemessage = "Request can not be withdrawn, its no longer active";
            }
            DB::commit();
            return response()->json(['success' => $responsestatus, 'message' => $responsemessage, 'data' => $thisrequest]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    ///start of emails
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
    public function sendCancelletionEmails($deposit, $org, $type)
    {
        if ($type == "admin") {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "Trade Cancelled",
                'ct_Request' => $deposit,
                'user_type' => "Admin"
            ], 'tradeCancelled'));
        } else if ($type == "cg") {
            Mail::to($org->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "Trade Cancelled",
                'ct_Request' => $deposit,
                'user_type' => "CG"
            ], 'tradeCancelled'));
        } else if ($type == "ct") {
            Mail::to($org->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "Trade Cancelled",
                'ct_Request' => $deposit,
                'user_type' => "CT"
            ], 'tradeCancelled'));
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

            Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
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
    public function sendCTRequestOfferCounterNotificationToCG($requestt, $cg)
    {

        Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
            'subject' => "New Trade Counter Offer Received",
            'offerDetails' => $requestt,
            'user_type' => "CG"
        ], 'counterOfferRecieved'));
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




    public function sendCTRequestNotificationTo($requestt, $cg, $type)
    {
        if ($type == "admin") {

            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => "New Trade Request.",
                'ct_Request' => $requestt,
                'user_type' => "Admin"
            ], 'newRequest'));
        } else if ($type == "cg") {
            Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
                'subject' => "New Trade Request Received.",
                'ct_Request' => $requestt,
                'user_type' => "CG"
            ], 'newRequest'));
        } else if ($type == "ct") {

            Mail::to($cg->notifiableUsersEmails())->queue(new CTSMails([
                'subject' => "New Trade Request Sent.",
                'ct_Request' => $requestt,
                'user_type' => "CT"
            ], 'newRequest'));
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
    public function sendCTRequestwithdrawNotificationToCT($requestt, $cg)
    {
        Mail::to($cg->notifiableUsersEmails())->queue(new CTSMails([
            'subject' => "Request Withdrawn",
            'ct_Request' => $requestt,
            'user_type' => "CG"
        ], 'withdrawRequest'));
    }
    public function sendCTRequestwithdrawNotificationToCG($requestt, $cg)
    {
        Mail::to($cg->notifiableUsersEmails())->queue(new CGSMails([
            'subject' => "Request Withdrawn",
            'ct_Request' => $requestt,
            'user_type' => "CG"
        ], 'withdrawRequest'));
    }
    public function getTradeRequestOffer($request)
    {
        $user = Auth::user();
        $id = CustomEncoder::urlValueDecrypt($request->offerId);
        $offer = CTTradeRequestCGOffer::with(['product', 'basket', 'invitee', 'counterOffers', 'biColleteral'])
            ->where("id", $id)
            ->first();
        if ($offer) {
            return $offer;
        } else {
            return response()->json([], 404);
        }
    }
    public function getTradeRequestOffers(Request $request)
    {
        $offers = CTTradeRequestCGOffer::with(['product', 'basket', 'invitee', 'counterOffers', 'biColleteral']);
        if ($request->from == "review") {
            $offers->whereIn("offer_status", ['ACTIVE']);
        } else if ($request->from == "inprogress") {
            $offers->whereIn("offer_status", ['ACTIVE']);
        } else if ($request->from == "history") {
            $offers->whereIn("offer_status", ['REQUEST_WITHDRAWN', 'EXPIRED', 'OFFER_WITHDRAWN']);
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
        $offers->whereHas('invitee', function ($query) use ($request) {
            $query->where('c_t_trade_request_invited_c_g_s.c_t_trade_request_id', CustomEncoder::urlValueDecrypt($request->requestId));
        });
        $offers = $offers->orderBy("id", "DESC")->get();
        return $offers;
    }
    public function getTradeRequestInvitedCGS(Request $request)
    {
        $offers = CTTradeRequestInvitedCG::with(['offers', 'organization'])
            ->whereIn("invitation_status", ['INVITED', 'PARTICIPATED'])
            ->where('c_t_trade_request_invited_c_g_s.c_t_trade_request_id', CustomEncoder::urlValueDecrypt($request->requestId))
            ->get();
        return $offers;
    }
    public function giveCounterOffer(Request $request)
    {
        $user = Auth()->user();
        $offer = CTTradeRequestCGOffer::with('invitee')->where("id", CustomEncoder::urlValueDecrypt($request->offerId))->first();
        if ($offer) {
            $foundCounteroffer = CTTradeRequestOfferCounterOffer::where("offer_id", $offer->id)->first();

            $netInterestRate = 0;

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
            $offerobtosave['status'] = "PENDING";
            $offerobtosave['rate_type'] = $request->rate_type;

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
                    $netInterestRate = floatval($ratedetails->value) + floatval($request->entered_rate);
                } else if ($request->operator == "-") {
                    $netInterestRate = floatval($ratedetails->value) - floatval($request->entered_rate);
                }
            }
            $offerobtosave['offer_interest_rate'] = $netInterestRate;
            $offerobtosave['created_at'] = getUTCDateNow();
            CTTradeRequestOfferCounterOffer::where("offer_id", $offer->id)->where("status", "PENDING")->update(['status' => 'EDITED']);
            $counteroffer = CTTradeRequestOfferCounterOffer::create($offerobtosave);
            //send alert mail to CG
            $originaloffer = CTTradeRequestCGOffer::where("id", CustomEncoder::urlValueDecrypt($request->offerId))->first();

            $requestDetails = CTTradeRequest::join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id", "=", "c_t_trade_requests.id")
                ->join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.invitation_id", "=", "c_t_trade_request_invited_c_g_s.id")
                ->where("c_t_trade_request_c_g_offers.id", CustomEncoder::urlValueDecrypt($request->offerId))
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

            //send alert mail to CG
            return response()->json(['success' => true, 'message' => 'Counter offer posted successfully', 'data' => $theredetails], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'offer not found'], 400);
        }
    }
    public function selectOffers(Request $request)
    {
        $user = Auth::user();
        $offers = json_decode($request->offers, true);
        $tradeRequest = CTTradeRequest::where("id", CustomEncoder::urlValueDecrypt($request->requestId))
            ->where("request_status", "ACTIVE")
            ->first();
        if (!$tradeRequest) {
            systemActivities(Auth::id(), json_encode($request->query()), "Post trade select offers failed.. deposit request not found");
            $response = array("success" => false, "message" => "Request not found, reload the page and try again" . CustomEncoder::urlValueDecrypt($request->requestId), "data" => []);
            return response()->json($response, 400);
        }
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['success' => false, 'error' => 'Invalid JSON format.'], 400);
        }
        try {
            DB::beginTransaction();
            $responsestatus = true;
            $responsemessage = "Offers selected";
            $totalamountawardedsucceessfully = 0;
            $numberawardedsucceessfully = 0;
            $failed_404 = 0;
            $deposit_existed = 0;
            $success = 0;
            $offerIds = [];
            foreach ($offers as $offer) {
                $offerId = CustomEncoder::urlValueDecrypt($offer['offerId']);
                array_push($offerIds, $offerId);
                //get the offer object
                $offerObject = CTTradeRequestCGOffer::with(['CTdeposit', 'biColleteral', 'basket'])->whereHas("invitee", function ($query) {
                    $query->where('c_t_trade_request_invited_c_g_s.invitation_status', 'PARTICIPATED');
                })
                    ->where("c_t_trade_request_c_g_offers.id", $offerId)->first();
                if (!$offerObject) {
                    $failed_404++;
                    systemActivities(Auth::id(), json_encode($request->query()), "Offer id: " . $offerId . " does not exist in the database, hence unable to create a deposit");
                    continue;
                }
                //get the offer object
                //close unaccepted counters
                CTTradeRequestOfferCounterOffer::where("offer_id", $offerId)->update(['status' => 'CLOSED_ON_OFFER_SELECTION']);
                //close unaccepted counters
                //check if there is attached
                $offerContract = $offerObject->CTdeposit;
                if (!empty($offerContract)) {
                    $deposit_existed++;
                    continue;
                }
                //close check if there is attached
                //create deposit
                $tradedate = $offerObject->settlement_date;
                $maturityDate = Carbon::parse($tradedate);
                $newMaturityDate = null;

                if ($offerObject->offer_term_length_type == "MONTHS") {
                    $newMaturityDate = $maturityDate->addMonths($offerObject->offer_term_length);
                } else if ($offerObject->offer_term_length_type == "DAYS") {
                    $newMaturityDate = $maturityDate->addDays($offerObject->offer_term_length);
                }

                $depositob['c_t_trade_request_c_g_offer_id'] = $offerId;
                $depositob['deposit_reference_no'] = generateRepoOfferContractID($tradeRequest->reference_no);
                $depositob['offered_amount'] = $offer['awarded_amount'];
                $depositob['trade_date'] = $tradedate;
                $depositob['c_g_trade_request_id'] = $offerObject->c_g_trade_request_id;
                $depositob['c_g_trade_request_invited_c_t_offer_id'] = $offerObject->c_g_trade_request_invited_c_t_offer_id;

                if($offerObject->c_g_trade_request_invited_c_t_offer_id!=""){
                    $this->tradehandlemarketoffer->closeCGOfferOnClose($offerObject->c_g_trade_request_invited_c_t_offer_id);
                }

                if ($offerObject->biColleteral != null) {
                    if ($offerObject->biColleteral->is_dummy) {
                        $depositob['deposit_status'] = 'PENDING_DEPOSIT';
                    } else {
                        $depositob['deposit_status'] = 'ACTIVE';
                        $depositob['maturity_date'] = $newMaturityDate;
                    }
                }
                if ($offerObject->basket != null) {
                    if ($offerObject->basket->is_dummy) {
                        $depositob['deposit_status'] = 'PENDING_DEPOSIT';
                    } else {
                        $depositob['deposit_status'] = 'ACTIVE';
                        $depositob['maturity_date'] = $newMaturityDate;
                    }
                }




                $depositob['created_by'] = $user->id;
                $offer_created = CTTradeRequestOfferDeposit::create($depositob);
                // dispatch(new GenerateMTFiles($offer_created->id,'cds'))->afterCommit(); # add the bank to send to (triparty agent)
                Artisan::call('generate:mt-files', [
                    'deposit_id' => $offer_created->id
                ]);
                $success++;
                //close create deposit
                //update offer
                $offerObject->offer_status = 'SELECTED';
                $offerObject->save();
                //close update offer

            }
            //send mails
            $requestId = CustomEncoder::urlValueDecrypt($request->requestId);
            $getOrgs = CTTradeRequestInvitedCG::whereHas("offers", function ($query) {
                $query->where("c_t_trade_request_c_g_offers.offer_status", "SELECTED");
            })->where('c_t_trade_request_id', $requestId)
                ->select(DB::raw('distinct organization_id'), 'id as invitation_id')
                ->get();
            $selectedoffers = null;
            foreach ($getOrgs as $getOrg) {
                //get all the selected offers for this org
                $selectedoffers = CTTradeRequestCGOffer::with(['CTdeposit', 'product', 'basket', 'invitee', 'biColleteral'])->where("invitation_id", $getOrg->invitation_id)->where("offer_status", "SELECTED")->get();

                $cg = Organization::where("id", $getOrg->organization_id)->first();
                $this->sendCTRequestOfferSelectedNotificationToCG(['currency' => $tradeRequest->currency, 'selected_offers' => $selectedoffers, 'CTOrg' => $user->organization->name], $cg);



                //get all the selected offers for this org
            }
            //send mails
            $ct = Auth::user()->organization;
            $selectedoffers = CTTradeRequestCGOffer::with(['CTdeposit', 'product', 'basket', 'invitee', 'biColleteral'])->whereIn("id", $offerIds)->where("offer_status", "SELECTED")->get();
            $this->sendCGRequestOfferSelectedNotificationToCT(['currency' => $tradeRequest->currency, 'selected_offers' => $selectedoffers, 'CTOrg' => $user->organization->name], $ct);
            //send mails
            $message = "";
            $message_title = "";
            $message = "" . json_encode($getOrgs);
            $message_title = "";
            if ($success > 0) {
                $tradeRequest->request_status = 'COMPLETED';
                $tradeRequest->save();
                DB::commit();
                $message_title .= "You have selected " . ($success == 1 ? "an " : "") . Str::plural('offer', count($offers));
                $message .= "";
                $message .= "The selected Financial " . Str::plural('institution', count($offers)) . " " . ($success == 1 ? "is " : "are") . " being notified.";
            }
            if ($failed_404 > 0) {
                $message .= $failed_404 . " " . Str::plural('deposit', $failed_404) . " failed as the offer was not found. ";
            }

            if ($deposit_existed > 0) {
                $message .= $deposit_existed . " " . Str::plural('deposit', $deposit_existed) . " failed, as they already existed. ";
            }

            return response()->json([
                'success' => $responsestatus,
                'message' => $message,
                'data' => $offers,
                "selectedoffers" => $selectedoffers
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

        $offers = CTTradeRequestCGOffer::with(['product', 'basket', 'invitee', 'counterOffers'])
            ->whereIn("offer_status", ["ACTIVE", "SELECTED", "NOT_SELECTED"])
            ->whereHas("invitee", function ($query) use ($user) {
                $query->whereHas("ctTradeRequest", function ($query) use ($user) {
                    $query->where("organization_id", $user->organization->id);
                });
            });
        if ($request->type == "history") {
            $offers->whereIn('c_t_trade_request_c_g_offers.offer_status', ['REQUEST_WITHDRAWN', 'EXPIRED', 'OFFER_WITHDRAWN']);
        }
        //search
        if ($request->filled("search")) {
            $offers->where(function ($query) use ($request) {
                $query->where("c_t_trade_request_c_g_offers.reference_no", "like", "%" . $request->search . "%");
            });
        }
        //search
        //rate
        if ($request->filled("rate")) {

            $rate = explode(",", $request->rate);

            if ($rate[0] != '0' && $rate[1] === '0') {

                $offers->where('c_t_trade_request_offer_deposits.offer_interest_rate', ">=", $rate[0]);
            } else if ($rate[0] === '0' && $rate[1] != '0') {

                $offers->where('c_t_trade_request_offer_deposits.offer_interest_rate', "<=", $rate[1]);
            } else if ($rate[0] != '0' && $rate[1] != '0') {

                $offers->whereBetween('c_t_trade_request_offer_deposits.offer_interest_rate', [$rate[0], $rate[1]]);
            }
        }
        //rate
        if ($request->filled("trade_date")) {
            $trade_date_users = explode(",", $request->trade_date);
            if ($trade_date_users[0] != '0' && $trade_date_users[1] === '0') {
                $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                $offers->where('c_t_trade_request_c_g_offers.trade_time', ">=", $startdate);
            } else if ($trade_date_users[0] === '0' && $trade_date_users[1] != '0') {
                $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                $offers->where('c_t_trade_request_c_g_offers.trade_time', "<=", $enddate);
            } else if ($trade_date_users[0] != '0' && $trade_date_users[1] != '0') {
                $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                $offers->whereBetween('c_t_trade_request_c_g_offers.trade_time', [$startdate, $enddate]);
            }
        }
        if ($request->filled("min_investment_amount")) {

            $offer_amount = explode(",", $request->min_investment_amount);

            if ($offer_amount[0] != '0' && $offer_amount[1] === '0') {

                $offers->where('c_t_trade_request_offer_deposits.offer_minimum_amount', ">=", $offer_amount[0]);
            } else if ($offer_amount[0] === '0' && $offer_amount[1] != '0') {

                $offers->where('c_t_trade_request_offer_deposits.offer_minimum_amount', "<=", $offer_amount[1]);
            } else if ($offer_amount[0] != '0' && $offer_amount[1] != '0') {

                $offers->whereBetween('c_t_trade_request_offer_deposits.offer_minimum_amount', [$offer_amount[0], $offer_amount[1]]);
            }
        }
        if ($request->filled("max_investment_amount")) {

            $offer_amount = explode(",", $request->max_investment_amount);

            if ($offer_amount[0] != '0' && $offer_amount[1] === '0') {

                $offers->where('c_t_trade_request_offer_deposits.offer_maximum_amount', ">=", $offer_amount[0]);
            } else if ($offer_amount[0] === '0' && $offer_amount[1] != '0') {

                $offers->where('c_t_trade_request_offer_deposits.offer_maximum_amount', "<=", $offer_amount[1]);
            } else if ($offer_amount[0] != '0' && $offer_amount[1] != '0') {

                $offers->whereBetween('c_t_trade_request_offer_deposits.offer_maximum_amount', [$offer_amount[0], $offer_amount[1]]);
            }
        }
        $offers = $offers->paginate($request->per_page ? $request->per_page : 10);
        return $offers;
    }
    public function getDeposits(Request $request)
    {
        $user = Auth()->user();
        $deposits = CTTradeRequestOfferDeposit::join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.id", "=", "c_t_trade_request_offer_deposits.c_t_trade_request_c_g_offer_id")
            ->join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.id", "=", "c_t_trade_request_c_g_offers.invitation_id")
            ->join("c_t_trade_requests", "c_t_trade_requests.id", "=", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id")
            ->join("organizations", "organizations.id", "=", "c_t_trade_request_invited_c_g_s.organization_id")
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
                //rate
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
                $query->whereHas("invitee", function ($query) use ($user) {
                    $query->whereHas("ctTradeRequest", function ($query) use ($user) {
                        $query->where("organization_id", $user->organization->id);
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
                    $query->orWhere("trade_products.product_name", "like", "%" . $request->search . "%");
                    $query->orWhere("organizations.name", "like", "%" . $request->search . "%");
                });
            // $deposits->where(function ($query) use ($request) {
            //     $query->where("c_t_trade_request_offer_deposits.deposit_reference_no", "like", "%" . $request->search . "%");
            // });
        }
        //search
        if ($request->filled("trade_date")) {
            $trade_date_users = explode(",", $request->trade_date);
            if ($trade_date_users[0] != '0' && $trade_date_users[1] === '0') {
                $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                $deposits->where('c_t_trade_requests.trade_time', ">=", $startdate);
            } else if ($trade_date_users[0] === '0' && $trade_date_users[1] != '0') {
                $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                $deposits->where('c_t_trade_requests.trade_time', "<=", $enddate);
            } else if ($trade_date_users[0] != '0' && $trade_date_users[1] != '0') {
                $startdate = convertBackToUTC($trade_date_users[0] . " 23:59");
                $enddate = convertBackToUTC($trade_date_users[1] . " 23:59");
                $deposits->whereBetween('c_t_trade_requests.trade_time', [$startdate, $enddate]);
            }
        }
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
        $deposits->orderBy("c_t_trade_request_offer_deposits.id", "DESC");

        $deposits = $deposits->select("c_t_trade_request_offer_deposits.*")->paginate($request->per_page ? $request->per_page : 10);
        return $deposits;
    }
    public function getActiveDeposits(Request $request)
    {
        $user = Auth()->user();
        $deposits = CTTradeRequestOfferDeposit::with("CGOffer")
            ->whereHas("CGOffer", function ($query) use ($user) {
                $query->whereHas("invitee", function ($query) use ($user) {
                    $query->whereHas("ctTradeRequest", function ($query) use ($user) {
                        $query->where("organization_id", $user->organization->id);
                    });
                });
            })
            ->paginate($request->per_page ? $request->per_page : 10);
        return $deposits;
    }
    public function getPendingDeposits(Request $request)
    {
        $user = Auth()->user();
        $deposits = CTTradeRequestOfferDeposit::with("CGOffer")
            ->whereHas("CGOffer", function ($query) use ($user) {
                $query->whereHas("invitee", function ($query) use ($user) {
                    $query->whereHas("ctTradeRequest", function ($query) use ($user) {
                        $query->where("organization_id", $user->organization->id);
                    });
                });
            })
            ->select("c_t_trade_request_offer_deposits.*")->paginate($request->per_page ? $request->per_page : 10);
        return $deposits;
    }

    public function getPendingDeposit(Request $request)
    {
        $user = Auth()->user();
        $deposit = CTTradeRequestOfferDeposit::with("CGOffer")
            ->where("id", CustomEncoder::urlValueDecrypt($request->depositId))
            ->whereHas("CGOffer", function ($query) use ($user) {
                $query->whereHas("invitee", function ($query) use ($user) {
                    $query->whereHas("ctTradeRequest", function ($query) use ($user) {
                        $query->where("organization_id", $user->organization->id);
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
    public function postTradeEvent(Request $request)
    {
        $responseMessage = "";
        $responseStatus = true;

        try {
            DB::beginTransaction();

            $depositId = CustomEncoder::urlValueDecrypt($request->depositID);
            $foundRecord = CTTradeRequestOfferDeposit::with(['CGOffer'])->find($depositId);
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
                        CTRequestDepositTradeEvent::where("batch_no", "<>", $entry['batch_no'])
                            ->where("c_t_trade_request_offer_deposit_id", $depositId)
                            ->update(["event_status" => "CLOSED_ON_NEW_EVENT"]);
                        //update deposit trade active_batch_number
                        $foundRecord->update([
                            'active_trade_events_batch_number' => $entry['batch_no']
                        ]);
                        //update deposit active_trade_events_batch_number
                    }
                } else {

                    foreach ($combinedowstosave as $entry) {
                        $batchno = generateTradeEventBatchNumber($foundRecord->id);
                        $entry['batch_no'] = $batchno;
                        $created = CTRequestDepositTradeEvent::create($entry);
                        CTRequestDepositTradeEvent::where("id", "<>", $created->id)
                            ->where("c_t_trade_request_offer_deposit_id", $depositId)
                            ->update(["event_status" => "CLOSED_ON_NEW_EVENT"]);

                        $foundRecord->update([
                            'active_trade_events_batch_number' => $entry['batch_no']
                        ]);
                    }
                    if ($request->event_type == "cancel") {
                        $cgorg = $foundRecord->CGOffer->invitee->organization;
                        $this->sendCancelletionEmails($foundRecord, $cgorg, "cg");

                        $ctorg = $foundRecord->CGOffer->c_t_trade_request->inviter;

                        $this->sendCancelletionEmails($foundRecord, $ctorg, "ct");
                        $this->sendCancelletionEmails($foundRecord, $ctorg, "admin");
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
                        $depositFound->update(['deposit_status' => 'CANCELLED']);
                        $foundrecord->update(['event_status' => 'COMPLETED']);
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
            return response()->json(['success' => $responsestatus, 'message' => $responsemessage, 'data' => []]);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

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
                    'sent_to_organization_id' => $deposit->c_g_organization->id,
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
            return response()->json([
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
                    $destinationPath = public_path() . '/uploads/CTRequestsofferssmessages';
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
                    'sent_to_organization_id' => $deposit->invitee->organization_id,
                    'file' => $messageFile
                ];
                //message object
                $createdchat = CTTradeRequestOfferChat::create($messageobject);
                if ($createdchat != null) {
                    $data['deposit'] = $deposit;
                    $data['created_chat'] = $createdchat;
                    $data['get_all_deposit_chats'] = CTTradeRequestOfferChat::with(['by', 'to'])->where("id", $createdchat->id)->get();
                    // broadcast event

                    Log::info('CTTradeRequestOfferChatEvent Event broadcasted successfully. 11');
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
                $responsemessage = "Message failed.The offer was not found";
            }
            DB::commit();
            return response()->json([
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


    public function getPublishedRequests(Request $request)
    {
        $user = Auth::user();
        $requests = CGTradeRequest::with([
            'CGTradeRequestInvitedCT' => function ($query) use ($user) {
                $query->where('c_g_trade_request_invited_c_t_s.organization_id', $user->organization->id);
                $query->select('id', 'c_g_trade_request_id', 'invited_user_id')
                    ->with(['CGTradeRequestInvitedCTOffer' => function ($offerQuery) {
                        $offerQuery->select('id', 'c_g_trade_request_invited_c_t_id');
                    }]);
            }
        ])->paginate($request->per_page ?? 10);
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
                $query->select('id', 'name');
            },
            'purchaseHistory' => function ($query) {
                $query->select('*');
            },
            'counters.interestCalculationOption' => function ($query) {
                $query->select('*');
            }
        ])->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
            ->where("c_g_trade_request_invited_c_t_s.c_g_trade_request_id", CustomEncoder::urlValueDecrypt($request->req))
            ->where('c_g_trade_request_invited_c_t_s.organization_id', $user->organization->id)
            ->whereIn('c_g_trade_request_invited_c_t_offers.offer_status', ['ACTIVE']);
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
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length_type', $termLengthType);
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
        ])
            ->orderBy("id", "DESC")
            ->get();

        return $requests;
    }
    public function getPublishedRequestsOffers(Request $request)
    {
        $user = Auth::user();
        // return $user->organization->id;
        $requests = CGTradeRequestInvitedCTOffer::with([
            'product',
            'interestCalculationOption',
            'basket',
            'biColleteral',
            'CGTradeRequestInvitedCT' => function ($query) {
                $query->select('id', 'organization_id', 'c_g_trade_request_id');
            },
            'CGTradeRequestInvitedCT.ct' => function ($query) {
                $query->select('id', 'name');
            },
            'purchaseHistory' => function ($query) {
                $query->select('*');
            },
            'counters.interestCalculationOption' => function ($query) {
                $query->select('*');
            }
        ])->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
            ->join("c_g_trade_requests", "c_g_trade_requests.id", "=", "c_g_trade_request_invited_c_t_s.c_g_trade_request_id")
            ->join("organizations", "organizations.id", "=", "c_g_trade_requests.organization_id")
            ->where('c_g_trade_request_invited_c_t_s.organization_id', $user->organization->id)
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

        // filters here 
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
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length_type', $termLengthType);
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
                $query->select('id', 'name');
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
            ->whereIn('c_g_trade_request_invited_c_t_offers.offer_status', ['ACTIVE','CLOSED_ON_PURCHASE']);
      
        $requests=$requests->select([
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
            'rate_operator',
            'offer_status'
        ])->first();

        return  $requests;
    }

    public function getOfferMyRelatedProductsOffers(Request $request)
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
                $query->select('id', 'name');
            },
            'purchaseHistory' => function ($query) {
                $query->select('*');
            },
            'counters.interestCalculationOption' => function ($query) {
                $query->select('*');
            }
        ])->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
            ->join("c_g_trade_requests", "c_g_trade_requests.id", "=", "c_g_trade_request_invited_c_t_s.c_g_trade_request_id");
        if ($request->productType == "bi") {
            $requests->join("trade_organization_collateral_c_u_s_i_p_s", "trade_organization_collateral_c_u_s_i_p_s.id", "=", "c_g_trade_request_invited_c_t_offers.trade_organization_collateral_c_u_s_i_p_s_id")
                ->join("trade_organization_collaterals", "trade_organization_collaterals.id", "=", "trade_organization_collateral_c_u_s_i_p_s.trade_organization_collateral_id")
                ->join("trade_collaterals", "trade_collaterals.id", "=", "trade_organization_collateral_c_u_s_i_p_s.trade_collateral_id");
                // ->where("trade_collaterals.id", $request->productId);
        } else if ($request->productType == "tri") {

            $requests->join("trade_tri_basket_third_parties", "trade_tri_basket_third_parties.id", "=", "c_g_trade_request_invited_c_t_offers.trade_tri_basket_third_party_id")
                ->join("trade_collateral_baskets", "trade_collateral_baskets.id", "=", "trade_tri_basket_third_parties.trade_collateral_basket_id")
                ->join("trade_basket_types", "trade_basket_types.id", "=", "trade_collateral_baskets.trade_basket_type_id");
                // ->where("trade_basket_types.id", $request->productId);
        }
        $requests->where('c_g_trade_request_invited_c_t_s.organization_id', "=", $user->organization->id);
        $requests->where('c_g_trade_requests.organization_id', $request->cg);
        $requests->where('c_g_trade_request_invited_c_t_offers.id', "!=", $request->currentOfferId)
            ->whereIn('c_g_trade_request_invited_c_t_offers.offer_status', ['ACTIVE']);
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
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length_type', $termLengthType);
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
        $requests =  $requests->select([
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
        ])
            ->orderBy("id", "DESC")
            ->paginate($request->per_page ?? 10);

        return $requests;
    }
    public function getOfferOtherRelatedProductsOffers(Request $request)
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
                $query->select('id', 'name');
            },
            'purchaseHistory' => function ($query) {
                $query->select('*');
            },
            'counters.interestCalculationOption' => function ($query) {
                $query->select('*');
            }
        ])->join("c_g_trade_request_invited_c_t_s", "c_g_trade_request_invited_c_t_s.id", "=", "c_g_trade_request_invited_c_t_offers.c_g_trade_request_invited_c_t_id")
            ->join("c_g_trade_requests", "c_g_trade_requests.id", "=", "c_g_trade_request_invited_c_t_s.c_g_trade_request_id");
        if ($request->productType == "bi") {
            $requests->join("trade_organization_collateral_c_u_s_i_p_s", "trade_organization_collateral_c_u_s_i_p_s.id", "=", "c_g_trade_request_invited_c_t_offers.trade_organization_collateral_c_u_s_i_p_s_id")
                ->join("trade_organization_collaterals", "trade_organization_collaterals.id", "=", "trade_organization_collateral_c_u_s_i_p_s.trade_organization_collateral_id")
                ->join("trade_collaterals", "trade_collaterals.id", "=", "trade_organization_collateral_c_u_s_i_p_s.trade_collateral_id")
                ->where("trade_collaterals.id", $request->productId);
        } else if ($request->productType == "tri") {

            $requests->join("trade_tri_basket_third_parties", "trade_tri_basket_third_parties.id", "=", "c_g_trade_request_invited_c_t_offers.trade_tri_basket_third_party_id")
                ->join("trade_collateral_baskets", "trade_collateral_baskets.id", "=", "trade_tri_basket_third_parties.trade_collateral_basket_id")
                ->join("trade_basket_types", "trade_basket_types.id", "=", "trade_collateral_baskets.trade_basket_type_id")
                ->where("trade_basket_types.id", $request->productId);
        }
        $requests->where('c_g_trade_request_invited_c_t_s.organization_id', "=", $user->organization->id);
        $requests->where('c_g_trade_requests.organization_id', "!=", $request->cg);
        $requests->where('c_g_trade_request_invited_c_t_offers.id', "!=", $request->currentOfferId)
            ->whereIn('c_g_trade_request_invited_c_t_offers.offer_status', ['ACTIVE']);
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
                $requests->where('c_g_trade_request_invited_c_t_offers.offer_term_length_type', $termLengthType);
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
        ])
            ->orderBy("id", "DESC")
            ->paginate($request->per_page ?? 10);
        return $requests;
    }
    public function confirmMarketOffer(Request $request)
    {
        try {
            DB::beginTransaction();
            $cgRequestoffer = $this->getSingleMarketPlaceOffer($request);

            if($cgRequestoffer->offer_status=="CLOSED_ON_PURCHASE"){
                return  response()->json(['status' => 0, 'message' => "You can't buy this again."]);
            }

            $activeCounter = $this->tradehandlemarketoffer->CheckIfThereISExistingCounter($cgRequestoffer,true);
            $ctRequest=null;
            $ctofferinvited=null;
            $ctRequestOffer=null;
            $ctRequestOfferDeposit=null;

            if($activeCounter){        

                $ctRequestOffer=CTTradeRequestCGOffer::where("id",$activeCounter->offer_id)->orderBy("id","DESC")->first();
                $ctofferinvited=CTTradeRequestInvitedCG::where("id",$ctRequestOffer->invitation_id)->first(); 
                $ctRequest =CTTradeRequest::where("id",  $ctofferinvited->c_t_trade_request_id)->first();   

            }else{
            //make request
            $ctRequest = $this->tradehandlemarketoffer->makeRequestFromOffer($cgRequestoffer, $request, "confirm");
            //make request
            //make invited entry
            $ctofferinvited = $this->tradehandlemarketoffer->makeRequestInvitedFromOffer($cgRequestoffer, $ctRequest, $cgRequestoffer->c_g_trade_request, "confirm");
            //make invited entry
            //make offer entry
            $ctRequestOffer = $this->tradehandlemarketoffer->makeRequestInvitedOfferFromOffer($cgRequestoffer, $ctofferinvited, $ctRequest, $request, "confirm");
            //make offer entry
            //make the trade,                 
          
            }
            
            $ctRequestOfferDeposit = $this->tradehandlemarketoffer->makeRequestInvitedOfferTradeFromOffer($request, $ctRequestOffer, $ctRequest, $cgRequestoffer,"confirm");
            $res['offer_status'] = $cgRequestoffer->offer_status;
            $res['cgRequestOffer'] = $cgRequestoffer;
            $res['ctRequest'] = $ctRequest;
            $res['ctofferinvited'] = $ctofferinvited;
            $res['ctRequestOffer'] = $ctRequestOffer;

            $res['cgRectRequestOfferDepositquestOffer'] = $ctRequestOfferDeposit;
            Artisan::call('generate:mt-files', [
                'deposit_id' => $ctRequestOfferDeposit->id
            ]);
            //make the trade
            DB::commit();
            return  response()->json(['status' => 1, 'message' => 'Offer confirmed successfully', 'data' => $res]);
        } catch (\Exception $ex) {
            DB::rollback();
            return  response()->json(['status' => 0, 'message' => 'Offer confirmation failed successfully.', 'error' => $ex->getMessage()]);
        }
    }
    public function counterOfferMarketOffer(Request $request)
    {
        try {
            DB::beginTransaction();
            $cgRequestoffer = $this->getSingleMarketPlaceOffer($request);

            if( $cgRequestoffer->offer_status=="CLOSED_ON_PURCHASE"){
                return  response()->json(['status' => 0, 'message' => "You can't negotiate on this again."]);
            }
            //make request
           
            $activeCounter = $this->tradehandlemarketoffer->CheckIfThereISExistingCounter($cgRequestoffer);
            $ctofferinvited=null;
            $ctRequestOffer=null;
            if($activeCounter){
                

                $ctRequestOffer=CTTradeRequestCGOffer::where("id",$activeCounter->offer_id)->orderBy("id","DESC")->first();
                $ctofferinvited=CTTradeRequestInvitedCG::where("id",$ctRequestOffer->invitation_id)->first();   
                $ctRequest =CTTradeRequest::where("id",  $ctofferinvited->c_t_trade_request_id)->first();           


                $ctRequestOffer = CTTradeRequestCGOffer::where("id", $activeCounter->offer_id)->first();
                $ctofferinvited = CTTradeRequestInvitedCG::where("id", $ctRequestOffer->invitation_id)->first();
            } else {

                $ctRequest = $this->tradehandlemarketoffer->makeRequestFromOffer($cgRequestoffer, $request, "counter");
                $ctofferinvited = $this->tradehandlemarketoffer->makeRequestInvitedFromOffer($cgRequestoffer, $ctRequest, $cgRequestoffer->c_g_trade_request, "counter");
                //make invited entry
                //make offer entry
                $ctRequestOffer = $this->tradehandlemarketoffer->makeRequestInvitedOfferFromOffer($cgRequestoffer, $ctofferinvited, $ctRequest, $request, "counter");
                //make offer entry

            }

            //register counter
            $ctRequestOfferDeposit = $this->tradehandlemarketoffer->makeRequestInvitedOfferCounterOfferFromOffer($cgRequestoffer, $ctRequestOffer, $ctRequest, $request);
            //register counter
            //make request
            //make invited entry

            $res['cgRequestOffer'] = $cgRequestoffer;
            $res['ctRequest'] = $ctRequest;
            $res['ctofferinvited'] = $ctofferinvited;
            $res['ctRequestOffer'] = $ctRequestOffer;
            $res['counterOffer'] = $ctRequestOfferDeposit;

            //make the trade
            DB::commit();
            return  response()->json(['status' => 1, 'mesage' => 'Offer confirmed successfully', 'data' => $res]);
        } catch (\Exception $ex) {
            DB::rollback();
            return  response()->json(['status' => 0, 'mesage' => 'Offer confirmation failed successfully.', 'error' => $ex->getMessage()]);
        }
    }
}

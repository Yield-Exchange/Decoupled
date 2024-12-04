<?php

namespace App\Services;

use App\Constants;
use App\Data\BankData;
use App\Mail\AdminMail;
use App\Mail\Bank\MarketPlaceOfferCreated;
use App\Mail\Bank\MarketPlaceOfferSelected;
use App\Models\Deposit;
use App\Models\DepositRequest;
use App\Models\InvitedBank;
use App\Models\Campaign;
use App\Models\CampaignQuery;
use App\Models\MarketPlaceOfferView;
use App\Models\NAICS;
use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CampaignService extends BaseService{
    public function fetch(Request $request){
        $user = $request->user();
        $organization = $user->organization;

        if($organization->type == 'BANK') {
            return $this->bankMarketPlaceOffersFetch($request);
        }

        return $this->depositorMarketPlaceOffersFetch($request);
    }

    public function update(array $data, Model $model){
        archiveTable($model->id, $model->getTable(), auth()->id(), "EDITED");
        $utc_now = getUTCDateNow();
        $rate_held_until = changeDateFromLocalToUTC($data['rate_held_until'],Constants::DATE_FORMAT);
        $message="Market place offer updated successfully";
        if(isset($data['expireOffer']) && $data['expireOffer'] == "true" && Carbon::parse($rate_held_until)->lte($utc_now)){
            $message="Market place offer expired successfully";
            $data['status'] = 'EXPIRED';
            $data['is_featured'] = false;
        }else if(Carbon::parse($rate_held_until.' 23:59:59')->gte($utc_now)) {
            if($model->status=="EXPIRED"){
                $message="Market place offer activated successfully";
            }
            $data['status'] = 'ACTIVE';
        }
        $model->update($data);
        $response = array("success" => true, "message" => $message, "data" => []);
        return response()->json($response, 200);
    }

    public function save(array $data, $user){
        if ( isset($data['select_offer']) ){
            $market_place_offer = Campaign::find($data['market_place_offer_id']);
            if(!$market_place_offer){
                $response = array("success" => false, "message" => "Failed, selected market place offer not found.", "data" => []);
                return response()->json($response, 404);
            }

            if($market_place_offer->status != "ACTIVE"){
                $response = array("success" => false, "message" => "Failed, market place offer is not active.", "data" => []);
                return response()->json($response, 400);
            }
            $totalMarketplaceOfferAmount = DepositRequest::where('market_place_offer_id', $market_place_offer->id)->sum('amount');
            $checkCumulativeTotal = $market_place_offer->cumulative_total ? ($totalMarketplaceOfferAmount >= $market_place_offer->cumulative_total)  : false;

            if ($checkCumulativeTotal) {
                $response = array("success" => false, "message" => "Failed, market place offer limit exceeded.", "data" => []);
                return response()->json($response, 400);
            }
            
            try {
                DB::beginTransaction();

                // create depositor request
                $now = Carbon::now();
                $closing_date_time = $now;
                $date_of_deposit = Carbon::parse($data['date_of_deposit']);
                $deposit_request = DepositRequest::create([
                    'reference_no' => generateDepositRequestReference(),
                    'term_length_type' => $market_place_offer->term_length_type,
                    'term_length' => strtoupper(trim($market_place_offer->term_length)),
                    'lockout_period_days' => $market_place_offer->lockout_period,
                    'closing_date_time' => changeDateFromLocalToUTC($closing_date_time->format(Constants::DATE_TIME_FORMAT), Constants::DATE_TIME_FORMAT),
                    'amount' => str_replace(",","",trim($data['amount'])),
                    'currency' => $market_place_offer->currency,
                    'date_of_deposit' => changeDateFromLocalToUTC($date_of_deposit->format(Constants::DATE_TIME_FORMAT), Constants::DATE_TIME_FORMAT),
                    'compound_frequency' => $market_place_offer->compound_frequency,
                    'requested_rate' => $market_place_offer->interest_rate,
                    'requested_short_term_credit_rating' => '',
                    'requested_deposit_insurance' => '',
                    'special_instructions' => '',
                    'request_status' => 'COMPLETED',
                    'created_date' => getUTCDateNow(true),
                    'user_id' => $user->id,
                    'product_id' => $market_place_offer->product_id,
                    'organization_id'=>$user->organization->id,
                    'market_place_offer_id'=>$market_place_offer->id
                ]);

                // create invited
                $invited = InvitedBank::create([
                    'invitation_status'=>'PARTICIPATED',
                    'invitation_date'=>$now,
                    'depositor_request_id'=>$deposit_request->id,
                    'organization_id'=>$market_place_offer->organization_id,
                    'user_id'=>$market_place_offer->created_by
                ]);

                // create offer
                $offer = Offer::create([
                    'invitation_id'=>$invited->id,
                    'reference_no'=>generateOfferReference(),
                    'created_date'=>getUTCDateNow(),
                    'maximum_amount'=>$market_place_offer->maximum_amount,
                    'minimum_amount'=>$market_place_offer->minimum_amount,
                    'interest_rate_offer'=>$market_place_offer->interest_rate,
                    'rate_held_until'=>changeDateFromLocalToUTC($date_of_deposit->format(Constants::DATE_TIME_FORMAT), Constants::DATE_TIME_FORMAT),
                    'special_instructions'=>'',
                    'fixed_rate'=>$market_place_offer->interest_rate,
                    'user_id'=>$market_place_offer->created_by,
                    'offer_status'=>'SELECTED',
                    'market_place_offer_id'=>$market_place_offer->id
                ]);

                // create deposit
                $deposit_created = Deposit::create([
                    'reference_no' => generateOfferContractID($deposit_request->reference_no),
                    'offer_id' => $offer->id,
                    'offered_amount' => $data['amount'],
                    'status' => 'PENDING_DEPOSIT',
                    'created_at' => getUTCDateNow(),
                ]);

                // Expire offer when cumulative total is exceeded
                $totalMarketplaceOfferAmount = DepositRequest::where('market_place_offer_id', $market_place_offer->id)->sum('amount');
                $checkCumulativeTotal = $market_place_offer->cumulative_total ? ($totalMarketplaceOfferAmount >= $market_place_offer->cumulative_total)  : false;
                if ($checkCumulativeTotal) {
                    $data['expireOffer'] = true;
                    $data['rate_held_until'] = now();
                    $this->update($data, $market_place_offer);
                }

                DB::commit();

                $this->sendEmails('selected',$market_place_offer,$user);

                $response = array("success" => true, "message" => "Market place offer created successfully", "data" => $deposit_created, 'url'=>url('/pending-deposits'));
                return response()->json($response, 201);

            }catch (\Exception $exception){
                DB::rollBack();
                $response = array("success" => false, "message" => "Selecting market place offer failed. Please retry", "data" => [$exception->getMessage()]);
                return response()->json($response, 400);
            }
        }

        $data['reference_no'] = generateMarketPlaceOfferReference();
        $data['created_by'] = $user->id;
        $data['organization_id'] = $user->organization->id;
        $data['status'] = 'ACTIVE';
        return $this->store($data,$user);
    }

    public function store(array $data, $user=null){
        $market_place_offer = Campaign::create($data);
        $this->sendEmails('created',$market_place_offer, $user);
        $response = array("success" => true, "message" => "Market place offer created successfully", "data" => $market_place_offer);
        return response()->json($response, 201);
    }

    public function delete(Model $model){
        archiveTable($model->id, $model->getTable(), auth()->id(), "DELETED");
        $model->delete();
        $response = array("success" => true, "message" => "Market place offer deleted successfully", "data" => []);
        return response()->json($response, 200);
    }

    private function sendEmails($action, $market_place_offer, $user){
        if($action == "created"){
            $message="Market place offer ref no ".$market_place_offer->reference_no.' has been created.';
            Mail::to($user->organization->notifiableUsersEmails())->queue(new MarketPlaceOfferCreated([
                'message' => $message,
                'subject' =>"New market place offer.",
                'header' =>"Your market place offer has been created",
                'user_type' =>"Bank"
            ]));

            $notification = $message;
            Mail::to(getAdminEmails())->queue(new AdminMail([
                'subject' => "New market place offer.",
                'message' => $user->organization->name." has created a new market place offer, Ref: " . $market_place_offer->reference_no,
            ]));

            notify([
                'type' => 'MARKET PLACE OFFER CREATED',
                'details' => $notification,
                'date_sent' => getUTCDateNow(),
                'status' => 'ACTIVE',
                'organization_id'=>$user->organization->id
            ]);
            return;
        }

        // else it to be selected action

        // notify depositors
        $message="Your have selected a market place offer ref no ".$market_place_offer->reference_no;
        Mail::to($user->organization->notifiableUsersEmails())->queue(new MarketPlaceOfferSelected([
            'message' => $message,
            'subject' =>"Selected market place offer.",
            'header' =>"Your have selected a market place offer",
            'user_type' =>"Depositor"
        ]));

        // notify the bank
        $message="Your have selected a market place offer ref no ".$market_place_offer->reference_no;
        Mail::to($market_place_offer->organization->notifiableUsersEmails())->queue(new MarketPlaceOfferSelected([
            'message' => $message,
            'subject' =>"Selected market place offer.",
            'header' =>"Your have selected a market place offer",
            'user_type' =>"Bank"
        ]));

        $notification ="Market place offer ref no ".$market_place_offer->reference_no.' has been selected.';;
        Mail::to(getAdminEmails())->queue(new AdminMail([
            'subject' => "New market place offer.",
            'message' => $user->organization->name." has selected a new market place offer, Ref: " . $market_place_offer->reference_no,
        ]));

        notify([
            'type' => 'MARKET PLACE OFFER SELECTED',
            'details' => $notification,
            'date_sent' => getUTCDateNow(),
            'status' => 'ACTIVE',
            'organization_id'=>$user->organization->id
        ]);
        return;
    }

    private function bankMarketPlaceOffersFetch(Request $request){
        $user = $request->user();
        $organization = $user->organization;

        $response = array("success" => true, "message" => "Market place offer fetched successfully", "data" => BankData::marketPlaceOffersData(null,function ($data) use($request,$organization) {
            return $data->when($request->status,function ($query) use($organization,$request){
                $query->where('market_place_offers.status',$request->status);
            })->where('market_place_offers.organization_id',$organization->id)->orderBy('market_place_offers.is_featured','DESC')->orderBy('market_place_offers.interest_rate','DESC')
                ->paginate($request->per_page ? $request->per_page : 8)->setPath('/market-place-offer');

        }));
        return response()->json($response, 200);
    }

    private function depositorMarketPlaceOffersFetch(Request $request){
        $user = $request->user();
        $organization = $user->organization;

        if( $request->has('filter') ) {
            $request['last_market_place_filter'] = CampaignQuery::create([
                'organization_id'=>$organization->id,
                'user_id'=>$user->id,
                'product_id'=>$request->product_id ? $request->product_id : 0,
                'term_length_type'=>$request->term_length_type ? $request->term_length_type : '',
                'term_length'=>$request->term_length ? $request->term_length : 0,
                'amount'=>$request->amount ? $request->amount : 0,
                'currency'=>$request->filled('currency') ? $request->currency: '',
                'fi_organization_id'=>$request->filled('fi_organization_id') && $request->fi_organization_id != 'all' ?
                    $request->fi_organization_id : NULL
            ]);
        }

        $suggestions=[];
        $response = array("success" => true, "message" => "Market place offer fetched successfully", "data" => BankData::marketPlaceOffersData(null,function ($data) use($request,$organization,$user,&$suggestions) {
            $data = $data->where('market_place_offers.status','ACTIVE')
                ->where('market_place_offers.is_test',$user->is_test);
            $data_clone = clone $data;
            if(!$request->has('filter_all')){
                $data = $data->where('is_featured',true);
                $data = $data->where('market_place_offers.status','ACTIVE')
                    ->where('market_place_offers.is_test',$user->is_test)
                    ->orderBy('id','DESC')
                    ->get();
            }else {
                if ($request->filled('last_market_place_filter')) {
                    $last_market_place_filter = $request->last_market_place_filter;

                    if ($last_market_place_filter->term_length_type && strtolower($last_market_place_filter->term_length_type) != 'all') {
                        $data = $data->where('term_length_type', $last_market_place_filter->term_length_type);
                    }

                    if ($last_market_place_filter->product_id && strtolower($last_market_place_filter->product_id) != 'all') {
                        $data = $data->where('product_id', $last_market_place_filter->product_id);
                    }

                    if ($last_market_place_filter->currency && strtolower($last_market_place_filter->currency) !='all') {
                        $data = $data->where('currency', $last_market_place_filter->currency);
                    }

                    if ($last_market_place_filter->term_length) {
                        $data = $data->where('term_length', $last_market_place_filter->term_length);
                    }

                    if ($last_market_place_filter->amount > 0) {
                        $data = $data->where('minimum_amount', '<=', $last_market_place_filter->amount)
                            ->where('maximum_amount', '>=', $last_market_place_filter->amount);
                    }

                    if ($last_market_place_filter->fi_organization_id && strtolower($last_market_place_filter->fi_organization_id) != 'all') {
                        $data = $data->where('market_place_offers.organization_id', $last_market_place_filter->fi_organization_id);
                    }
                }
                $data_paginated = $data->where('market_place_offers.status','ACTIVE')
                    ->where('market_place_offers.is_test',$user->is_test)
                    ->orderBy('market_place_offers.is_featured','DESC')
                    ->orderBy('market_place_offers.interest_rate','DESC')
                    ->paginate($request->per_page ? $request->per_page : 5)->setPath('/depositor/market-place-offer');

                $filtered_data = $this->filterVisibilityWithPagination($data_paginated);

                $data = new LengthAwarePaginator($filtered_data,$data_paginated->total(),$data_paginated->perPage(),$data_paginated->currentPage(),[
                    'path'=>url('/depositor/market-place-offer'),
                    'query'=>$request->query()
                ]);


                if (count($data) ==0 ) {
                    $suggestions_paginated = $data_clone->where('market_place_offers.status','ACTIVE')
                        ->where('market_place_offers.is_test',$user->is_test)
                        ->orderBy('market_place_offers.is_featured','DESC')
                        ->orderBy('market_place_offers.interest_rate','DESC')
                        ->paginate($request->per_page ? $request->per_page : 5)->setPath('/depositor/market-place-offer');
                    $filtered_data = $this->filterVisibilityWithPagination($suggestions_paginated);

                    $data = new LengthAwarePaginator($filtered_data,$suggestions_paginated->total(),$suggestions_paginated->perPage(),$suggestions_paginated->currentPage(),[
                        'path'=>url('/depositor/market-place-offer'),
                        'query'=>$request->query()
                    ]);
                }
            }

            foreach ($data as $datum) {
                MarketPlaceOfferView::create([
                    'market_place_offer_id'=>$datum->id,
                    'fi_organization_id'=>$datum->organization_id,
                    'organization_id'=>$organization->id,
                    'user_id'=>$user->id,
                    'viewed_from_page'=>$request->filled('from_page') ? $request->from_page : 'Market Place Offer Page',
                    'query_string'=>json_encode($request->query())
                ]);
            }

            return $data;
        }),'url'=>$request->has('filter') ? route('market.get.offer',['has_filtered'=>1]) : route('market.get.offer'),
            'suggestion'=>$suggestions,'filters'=>$request->all());

        return response()->json($response, 200);
    }
    private function filterVisibilityWithPagination($data_paginated){
        $filtered_data=[];
        foreach ($data_paginated as $datum){
//            $fi_org = $datum->organization;
//            if($fi_org->visible_for_provinces){
//                $fi_org->visible_for_provinces = explode(',',$fi_org->visible_for_provinces);
//                if(!in_array($fi_org->demographicData->province,$fi_org->visible_for_provinces)){
//                    continue;
//                }
//            }
//
//            if($fi_org->visible_for_customers){
//                if($fi_org->visible_for_customers=="Only Financial Institutions"){
//                    continue;
//                }
//            }
//
//            if($fi_org->visible_for_naics_codes){
//                $fi_org->visible_for_naics_codes = explode(',',$fi_org->visible_for_naics_codes);
//                $naicscode = NAICS::where('id', $fi_org->naics_code_id)->first();
//                if(in_array($naicscode->code_description,$fi_org->visible_for_naics_codes)){
//                    continue;
//                }
//            }

            array_push($filtered_data,$datum);
        }
        return $filtered_data;
    }
}
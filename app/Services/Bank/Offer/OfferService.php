<?php

namespace App\Services\Bank\Offer;

use App\Constants;
use App\CustomEncoder;
use App\Data\BankData;
use App\Mail\AdminMail;
use App\Mail\NewCounterOfferMail;
use App\Mail\Bank\NewOfferMail;
use App\Models\CounterOffer;
use App\Models\DepositRequest;
use App\Models\Offer;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\CampaignFICampaignProduct;

use App\Mail\AdminMails;
use App\Mail\BankMails;
use App\Mail\DepositorMails;
use App\Models\FICampaignProduct;
use App\Models\InvitedBank;

class OfferService extends BaseService
{
    public function save(Request $request, $user)
    {
        //Log::alert(json_encode($request->all()));
        $is_counter_offer = $request->has('is_counter_offer');
        $is_counter_offer_from_depositor = $request->has('from_depositor');

        if ($request->has('expdate')) {
            $request->merge(['expdate' => removeAmPm($request->expdate)]);
        }

        $validation_rules = [
            // 'request_id' => 'required',
            'min_amount' => 'required|min:0',
            'max_amount' => 'required|gte:min_amount',
            //'expdate' => 'required|date_format:Y-m-d H:i',
        ];

        $counter_offer_expiry = null;

        if ($request->filled('from_campaign')) {

            $user = auth()->user();
            $timezone = formattedTimezone($user->timezone);
            $product = CampaignFICampaignProduct::query()
                ->with(['campaign.organization', 'campaign.organization.document'])
                ->select([
                    DB::raw('f_i_campaign_products.id as campaign_prod_id'),
                    'campaign_f_i_campaign_products.*',
                    'campaign_f_i_campaign_products.rate',
                    'campaign_f_i_campaign_products.minimum',
                    'campaign_f_i_campaign_products.maximum',
                    'campaign_f_i_campaign_products.order_limit',
                    'campaign_f_i_campaign_products.isFeatured',
                    DB::raw('LCASE(f_i_campaign_products.term_length_type) as term_length_type'),
                    'f_i_campaign_products.term_length',
                    'f_i_campaign_products.compound_frequency',
                    'f_i_campaign_products.lockout_period',
                    'f_i_campaign_products.interest_paid',
                    'f_i_campaign_products.default_product_name',
                    'f_i_campaign_products.pds',
                    'products.description',
                    'products.id as product_type_id',
                    'products.flexibility_rate',
                    'products.flexibility_text',
                    'products.earning_rate',
                    'products.earning_text',
                    'products.definition',
                    'ca.currency',
                    'ca.expiry_date as expiry_date_utc',
                    DB::raw("CONVERT_TZ(ca.expiry_date, '+00:00', '{$timezone}') as expiry_date"),
                    'ca.subscription_amount',
                ])
                ->join('campaigns as ca', 'ca.id', '=', 'campaign_f_i_campaign_products.campaign_id')
                ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
                ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
                ->join('campaign_target_groups', 'campaign_target_groups.campaign_id', '=', 'ca.id')
                ->join('f_i_campaign_groups', 'f_i_campaign_groups.id', '=', 'campaign_target_groups.fi_campaign_group_id')
                ->join('f_i_campaign_group_depositors', function ($join) use ($user) {
                    $join->on('f_i_campaign_group_depositors.fi_campaign_group_id', '=', 'f_i_campaign_groups.id');
                    $join->where('f_i_campaign_group_depositors.depositor_id', '=', $user->organization->id);
                })
                ->where('campaign_f_i_campaign_products.id', $request->campaign_prod_id)
                ->first();



            if ($request->filled('counter_offer_expiry')) {
                $request->merge(['counter_offer_expiry' => $product->expiry_date]);
            }




            // TODO: create a request 
            $deposit = DepositRequest::query()
                ->with('offers')
                ->select([
                    'depositor_requests.*'
                ])
                ->join('invited', 'invited.depositor_request_id', 'depositor_requests.id')
                ->join('offers', 'offers.invitation_id', "invited.id")
                ->where('depositor_requests.user_id', $user->id)
                ->where('depositor_requests.product_id', $product->product_type_id)
                ->where('depositor_requests.campaign_product_id', $product->id)
                ->where('depositor_requests.organization_id', $user->organization->id)
                ->where('depositor_requests.request_status', 'ACTIVE')
                ->where('offers.offer_status', 'ACTIVE')
                ->orderByDesc('depositor_requests.id')
                ->first();
            //Log::alert(); 
            if ($deposit) {
                $deposit_request = $deposit;
                $offer = $deposit->offers[0];   //// will always be one per organization per deposit request
            } else {
                $now = Carbon::now();
                $date_of_deposit = getUTCTimeNow();
                //Log::alert(json_encode($request->rate_held_until));
                $rate_held_until = $request->rate_held_until;
                $deposit_request = DepositRequest::create([
                    'reference_no' => generateDepositRequestReference(),
                    'term_length_type' => $product->term_length_type,
                    'term_length' => strtoupper(trim($product->term_length)),
                    'lockout_period_days' => $product->lockout_period,
                    'closing_date_time' => $rate_held_until,
                    'amount' => str_replace(",", "", trim($request['amount'])),
                    'currency' => $request->currency,
                    'date_of_deposit' => $date_of_deposit,
                    'compound_frequency' => $product->compound_frequency,
                    'requested_rate' => $product->rate,
                    'requested_short_term_credit_rating' => '',
                    'requested_deposit_insurance' => '',
                    'special_instructions' => '',
                    'request_status' => 'ACTIVE',
                    'created_date' => getUTCDateNow(true),
                    'user_id' => $user->id,
                    'product_id' => $product->product_type_id,
                    'organization_id' => $user->organization->id,
                    'campaign_product_id' => $product->id
                ]);
                // create invited
                $invited = InvitedBank::create([
                    'invitation_status' => 'PARTICIPATED',
                    'invitation_date' => $now,
                    'depositor_request_id' => $deposit_request->id,
                    'organization_id' => $product->campaign->fi_id,
                    'user_id' => $product->campaign->created_by
                ]);

                // create offer
                $offer = Offer::create([
                    'invitation_id' => $invited->id,
                    'reference_no' => generateOfferReference(),
                    'created_date' => getUTCDateNow(),
                    'maximum_amount' => $product->maximum,
                    'minimum_amount' => $product->minimum,
                    'interest_rate_offer' => $product->rate,
                    'rate_held_until' => $rate_held_until,
                    'special_instructions' => '',
                    'fixed_rate' => $product->rate,
                    'user_id' => $product->campaign->created_by,
                    'offer_status' => 'ACTIVE',
                    'campaign_product_id' => $product->id
                ]);
            }
        }

        if ($is_counter_offer) {
            $validation_rules['offer_id'] = 'required';
        }

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }
        if ($request->filled('from_campaign')) {
            $deposit_request = $deposit_request;
            $request->merge(['counter_offer_expiry' => $product->expiry_date]);
        } else {
            $deposit_request = DepositRequest::with(['invited'])->find(CustomEncoder::urlValueDecrypt($request->request_id));
        }

        if (!$deposit_request || $deposit_request->request_status != "ACTIVE") {
            systemActivities(Auth::id(), json_encode($request->query()), "Submit Place offer page, Unable to access the page.. deposit request not found");
            $response = array("success" => false, "message" => "Request not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => []);
            return response()->json($response, 400);
        }
        // if ($deposit_request->amount < $request->max_amount || $deposit_request->amount < $request->min_amount) {
        //     systemActivities(Auth::id(), json_encode($request->query()), "Deposit request amount should be greater than both min & max offer amount");
        //     $response = array("success" => false, "message" => "Failed, Min/Max amount exceeds the request amount, it's not allowed", "data" => []);
        //     return response()->json($response, 400);
        // }

        $rate_held_until = Carbon::parse(changeDateFromLocalToUTC($request->expdate, Constants::DATE_TIME_FORMAT, Constants::DATE_TIME_FORMAT_NO_SECONDS));
        // if (Carbon::parse($deposit_request->date_of_deposit)->greaterThan($rate_held_until)) {
        //     systemActivities(Auth::id(), json_encode($request->query()), "Rate held until must be greater than deposit date");
        //     $response = array("success" => false, "message" => "Rate held until must be greater than deposit date", "data" => []);
        //     return response()->json($response, 400);
        // }


        $url = "";
        if (!empty(trim($request->url))) {
            if (strpos($request->url, 'http') !== false) {
                $url = $request->url;
            } else {
                $url = $request->pre_url . $request->url;
            }
        }

        $product_disclosure_statement = "";
        if ($request->hasFile('file')) {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:jpeg,jpg,png,gif,pdf,docx,doc|max:25600'
            ]);

            if ($validator->fails()) {
                $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                return response()->json($response, 400);
            }

            $product_disclosure_statement = time() . '_' . str_replace(" ", "_", $request->file('file')->getClientOriginalName());
            $request->file('file')->move(public_path('/uploads'), $product_disclosure_statement);
        }

        if ($deposit_request->term_length_type == "HISA") {
            $validation_rules = [
                'rate_type' => "required|in:fixed,prime_rate,sofr,sonia,euribor,estr,tonar,saron,tibor,aonia",
            ];

            if ($request->rate_type == "fixed") {
                $validation_rules['nir'] = "required|min:0";
            }

            if ($request->rate_type == "variable") {
                $validation_rules['fixed_rate'] = "required|min:0";
                $validation_rules['rate_operator'] = "required|in:+,-";
            }

            $validator = Validator::make($request->all(), $validation_rules);

            if ($validator->fails()) {
                $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                return response()->json($response, 400);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'nir' => "required|min:0"
            ]);

            if ($validator->fails()) {
                $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                return response()->json($response, 400);
            }
        }

        $prime_rate = getSystemSettings($request->rate_type)->value;
        $rate_operator = null;
        $fixed_rate = null;
        $rate_type = "FIXED";
        $nir = $request->nir;
        if ($deposit_request->term_length_type == "HISA") {
            $rate_type = $request->rate_type;
            if (strtolower($rate_type) != "fixed") {
                $rate_operator = $request->rate_operator;
                $fixed_rate = $request->fixed_rate;
                if ($rate_operator == "+") {
                    $nir = $prime_rate + $fixed_rate;
                } else {
                    $nir = $prime_rate - $fixed_rate;
                }
            }
        }
        $rate_type = trim(strtoupper($rate_type));

        if ($is_counter_offer) {
            if ($request->filled('from_campaign')) {
                $offer = $offer;
            } else {
                $offer = Offer::find(CustomEncoder::urlValueDecrypt($request->offer_id));
            }

            if (!$offer) {
                systemActivities(Auth::id(), json_encode($request->query()), "Updating offer failed, offer not found");
                return response()->json(["message" => 'Failed, unable to update the offer, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 400);
            }

            // $counter_offer_expiry = NULL;
            // if ($request->filled('counter_offer_expiry')) {
            //     $counter_offer_expiry = Carbon::parse(changeDateFromLocalToUTC($request->counter_offer_expiry, Constants::DATE_TIME_FORMAT, Constants::DATE_TIME_FORMAT_NO_SECONDS));

            //     $utc_now = getUTCTimeNow();
            //     if ($utc_now->greaterThan($counter_offer_expiry) || $counter_offer_expiry->diffInMinutes($utc_now) < 30) {
            //         systemActivities(Auth::id(), json_encode($request->query()), "Counter offer expiry must be more than 30 minutes from now");
            //         $response = array("success" => false, "message" => "Counter offer expiry must be more than 30 minutes from now", "data" => []);
            //         return response()->json($response, 400);
            //     }

            //     if (Carbon::parse($deposit_request->date_of_deposit)->lessThan($counter_offer_expiry)) {
            //         systemActivities(Auth::id(), json_encode($request->query()), "Counter offer expiry is greater than date of deposit");
            //         $response = array("success" => false, "message" => "Counter offer expiry is greater than date of deposit. Please change the Rate held until or counter offer expiry", "data" => []);
            //         return response()->json($response, 400);
            //     }
            // }


            $create_array = [
                'offer_id' => $offer->id,
                'maximum_amount' => $request->max_amount,
                'minimum_amount' => $request->min_amount,

                'offered_interest_rate' => $nir,
                'offer_expiry' => $rate_held_until,
                'product_disclosure_url' => $url,
                'special_instructions' => $request->filled('special_ins') ? $request->special_ins : '',
                'requested_by_user_id' => $user->id,
                'requested_by_organization_id' => $user->organization->id,
                'counter_offer_expiry' => $counter_offer_expiry,
                'rate_type' => $rate_type,
                'prime_rate' => $prime_rate,
                'rate_operator' => $rate_operator,
                'fixed_rate' => $fixed_rate,
                'created_at' => getUTCDateNow(),
            ];
            //Log::alert(json_encode($create_array));

            if (!empty($product_disclosure_statement)) {
                $create_array['product_disclosure_statement'] = $product_disclosure_statement;
            }
            $existingCounters = CounterOffer::where('offer_id', $offer->id)
            ->whereIn('status', ['PENDING'])->update(['status' => 'EDITED']);

            $created = CounterOffer::create($create_array);

            systemActivities(Auth::id(), json_encode($request->query()), "Counter offer has been posted successfully");

            if (!$is_counter_offer_from_depositor) {



                $created->status = 'COUNTERED';
                $created->save();

                $archive_id = archiveTable($offer->id, 'offers', $user->id, 'UPDATED FROM COUNTER OFFER ID: ' . $created->id);

                CounterOffer::where(['offer_id' => $offer->id, 'status' => 'PENDING'])->update(['status' => 'CLOSED_ON_COUNTERED']); // Just in case there is a pending counter offer

                $update_array = [
                    'maximum_amount' => $created->maximum_amount,
                    'minimum_amount' => $created->minimum_amount,
                    'interest_rate_offer' => $created->offered_interest_rate,
                    'rate_held_until' => $created->offer_expiry,
                    'product_disclosure_url' => $created->product_disclosure_url,
                    'special_instructions' => $created->special_instructions,
                    'rate_type' => $created->rate_type,
                    'prime_rate' => $created->prime_rate,
                    'rate_operator' => $created->rate_operator,
                    'fixed_rate' => $created->fixed_rate,
                    'product_disclosure_statement' => $created->product_disclosure_statement,
                    'counter_offer_archive_id' => $archive_id ? $archive_id : NULL,
                    'admin_loggedin_as' => $user->admin_loggedin_as
                ];
                $offer->update($update_array);
            }

            $organization_receiving = !$is_counter_offer_from_depositor ? $offer->invited->depositRequest->organization : $offer->invited->organization;
            $this->counterOfferEmails($organization_receiving, $user->organization, $created);
            $response = array("success" => true, "existingCounters" => $existingCounters, "message" => "The Institution has been notified.", "message_title" => "Your counter offer has been posted successfully.", "data" => []);
            return response()->json($response, 201);
        }

        if ($request->filled('offer_id')) {
            if ($request->filled('from_campaign')) {
                $offer = $offer;
            } else {
                $offer = Offer::find(CustomEncoder::urlValueDecrypt($request->offer_id));
            }
            if (!$offer) {
                systemActivities(Auth::id(), json_encode($request->query()), "Updating offer failed, offer not found");
                return response()->json(["message" => 'Failed, unable to update the offer 5555, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 400);
            }

            $offer_Before = clone $offer;
            //            if (Carbon::parse($deposit_request->closing_date_time)->lessThan(getUTCTimeNow())){
            //                systemActivities(Auth::id(), json_encode($request->query()), "Update offer page, This offer can not updated");
            //                $response = array("success"=>false, "message"=>"This offer can not updated", "data"=>[]);
            //                return response()->json($response, 400);
            //            }

            archiveTable($offer->id, 'offers', $user->id, 'UPDATED');

            $update_array = [
                'maximum_amount' => $request->max_amount,
                'minimum_amount' => $request->min_amount,
                'interest_rate_offer' => $nir,
                'rate_held_until' => $rate_held_until,
                'product_disclosure_url' => $url,
                'special_instructions' => $request->filled('special_ins') ? $request->special_ins : '',
                'rate_type' => $rate_type,
                'prime_rate' => $prime_rate,
                'rate_operator' => $rate_operator,
                'fixed_rate' => $fixed_rate
            ];

            if (!empty($product_disclosure_statement)) {
                $update_array['product_disclosure_statement'] = $product_disclosure_statement;
            } else {
                if (empty($request->attached_file)) {
                    $update_array['product_disclosure_statement'] = '';
                }
            }

            $offer->update($update_array);

            //            if(true){
            $create_array = [
                'offer_id' => $offer->id,
                'maximum_amount' => $request->max_amount,
                'minimum_amount' => $request->min_amount,
                'offered_interest_rate' => $nir,
                'offer_expiry' => $rate_held_until,
                'product_disclosure_url' => $url,
                'special_instructions' => $request->filled('special_ins') ? $request->special_ins : '',
                'requested_by_user_id' => $user->id,
                'requested_by_organization_id' => $user->organization->id,
                'counter_offer_expiry' => NULL,
                'rate_type' => $rate_type,
                'prime_rate' => $prime_rate,
                'rate_operator' => $rate_operator,
                'fixed_rate' => $fixed_rate,
                'status' => 'EDITED',
                'created_at' => getUTCDateNow(),
            ];

            if (!empty($product_disclosure_statement)) {
                $create_array['product_disclosure_statement'] = $product_disclosure_statement;
            }

            CounterOffer::create($create_array);
            CounterOffer::where('offer_id', $offer->id)
                ->whereIn('status', ['PENDING'])
                ->update(['status' => 'EDITED']);
            //            }

            $offer_After = Offer::find($offer->id);
            $changes = $this->recursive_array_diff($offer_Before->toArray(), $offer_After->toArray());
            if (!empty($changes)) {
                $bank = $user->organization;
                $deposit_request_organization = $deposit_request->organization;
                $depositor_obj = $deposit_request_organization->notifiableUsersEmails($return_emails = false);
                for ($i = 0; $i < count($depositor_obj); $i++) {
                    $depositor_timezone = $deposit_request_organization->demographicData->timezone;
                    $datetime_from_utc = changeDateFromUTCtoLocal($rate_held_until, $format = 'Y-m-d h:i a', null, $depositor_timezone) . ' ' . $depositor_timezone;
                    Mail::to($depositor_obj[$i]->email)->queue(new NewOfferMail([
                        'message' => "<p><center>Your request " . $deposit_request->reference_no . " has an offer that has been updated by $bank->name. You can sign in and review the offer through the 'Review offers' page. You can select an offer after " . $datetime_from_utc . ".</center></p>",
                        'subject' => "Offer has been updated",
                        'header' => $bank->name . " has updated the offer",
                        'user_type' => "Depositor"
                    ]));
                }
            }

            systemActivities(Auth::id(), json_encode($request->query()), "Your offer has been updated successfully");

            $response = array("success" => true, "message" => "The Depositor has been notified.", "message_title" => "Your Offer has been updated.", "data" => []);
            return response()->json($response, 200);
        }

        $organization = $user->organization;

        $offer = Offer::whereHas('invited', function ($query) use ($deposit_request, $user, $organization) {
            $query->where('invited.depositor_request_id', $deposit_request->id)
                ->where('invited.organization_id', $organization->id);
        })->first();
        if ($offer) {
            systemActivities(Auth::id(), json_encode($request->query()), "You have already posted an offer for this request.");
            $response = array("success" => false, "message" => "You have already posted an offer for this request.", "data" => []);
            return response()->json($response, 400);
        }

        $invited = $deposit_request->invited->where('organization_id', $organization->id)->first();
        if (!$invited) {
            systemActivities(Auth::id(), json_encode($request->query()), "Your invitation to the request is not valid");
            $response = array("success" => false, "message" => "Your invitation to the request is not valid, " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => []);
            return response()->json($response, 400);
        }

        $create_array = [
            'invitation_id' => $invited->id,
            'reference_no' => generateOfferReference(),
            'offer_status' => 'ACTIVE',
            'created_date' => getUTCDateNow(),
            'maximum_amount' => $request->max_amount,
            'minimum_amount' => $request->min_amount,
            'interest_rate_offer' => $nir,
            'rate_held_until' => $rate_held_until,
            'product_disclosure_url' => $url,
            'special_instructions' => $request->filled('special_ins') ? $request->special_ins : '',
            'rate_type' => $rate_type,
            'prime_rate' => $prime_rate,
            'rate_operator' => $rate_operator,
            'fixed_rate' => $fixed_rate,
            'user_id' => \auth()->id(),
            'admin_loggedin_as' => $user->admin_loggedin_as
        ];

        if (!empty($product_disclosure_statement)) {
            $create_array['product_disclosure_statement'] = $product_disclosure_statement;
        }

        $created = Offer::create($create_array);
        $invited->invitation_status = 'PARTICIPATED';
        $invited->save();

        if (!$created) {
            systemActivities(Auth::id(), json_encode($request->query()), "Offer failed to create");
            $response = array("success" => false, "message" => "Unable to submit your offer, " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => []);
            return response()->json($response, 400);
        }

        $user = \auth()->user();
        $bank_organization = $user->organization;
        $deposit_request_organization = $deposit_request->organization;

        //Email to Depositor
        $depositor_obj = $deposit_request_organization->notifiableUsersEmails($return_emails = false);
        for ($i = 0; $i < count($depositor_obj); $i++) {
            $bank = $user->organization;
            $depositor_timezone = $depositor_obj[$i]->demographicData->timezone;
            $datetime_from_utc = changeDateFromUTCtoLocal($rate_held_until, $format = 'Y-m-d h:i a', null, $depositor_timezone) . ' ' . $depositor_timezone;

            Mail::to($depositor_obj[$i]->email)->queue(new DepositorMails([
                'subject' => "You have received an Offer",
                'new_offer_details' => ['deposit' => $deposit_request, 'bank' => $bank_organization, 'viable_select_time' => $datetime_from_utc],
                'user_type' => "Depositor"
            ], 'new_post_request_offer'));
        }

        //Email to the bank
        $banks = $bank_organization->notifiableUsersEmails($return_emails = false);
        foreach ($banks as $bank) {
            $bank_zone = $bank->demographicData->timezone;
            $closing_date_time = changeDateFromUTCtoLocal($deposit_request->closing_date_time, $format = 'Y-m-d h:i a', null, $bank_zone) . ' ' . $bank_zone;

            Mail::to($bank->email)->queue(new BankMails([
                'subject' => "Your offer has been placed",
                'new_offer_details' => ['deposit' => $deposit_request, 'depositor' => $deposit_request_organization, 'closing_date_time' => $closing_date_time],
                'user_type' => "Depositor"
            ], 'new_post_request_offer'));
        }

        notify([
            'type' => 'OFFER REQUEST',
            'details' => 'This is to notify you that a bank placed an offer',
            'date_sent' => getUTCDateNow(true),
            'status' => 'ACTIVE',
            'organization_id' => $deposit_request_organization->id
        ]);
        $data = [
            'reference_no' => $deposit_request->reference_no,
            'max_amount' => $request->max_amount,
            'min_amount' => $request->min_amount,
            'rate' => $nir,
            'rate_type' => $rate_type,
            'business_name' => $bank_organization->name,
            'currency' => $deposit_request->currency
        ];
        Mail::to(getAdminEmails())->queue(new AdminMails([
            'data' => $data,
            'subject' => 'Offer placed by ' . $bank_organization->name
        ], 'new_offer_deposit'));

        systemActivities(Auth::id(), json_encode($request->query()), "Your offer has been submitted successfully");
        $response = array("success" => true, "message" => "The Depositor has been notified.", "message_title" => "Your offer has been submitted.", "data" => []);
        return response()->json($response, 200);
    }

    public function getNewRequests(Request $request)
    {
        $utc_date_now = getUTCTimeNow()->format(Constants::DATE_TIME_FORMAT);
        $new_requests = DepositRequest::select([
            'depositor_requests.*',
            'demographic_organization_data.province',
            'organizations.name as depositor'
        ])->join('invited', function ($join) {
            $join->on('depositor_request_id', '=', 'depositor_requests.id');
            $join->where('invited.organization_id', \auth()->user()->organization->id);
            $join->where('invitation_status', ['INVITED']);
        })
            ->leftJoin('products', 'products.id', '=', 'depositor_requests.product_id')
            ->join('users', 'users.id', '=', 'depositor_requests.user_id')
            ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->whereIn('depositor_requests.request_status', ['ACTIVE'])
            ->where('date_of_deposit', '>=', getUTCTimeNow()->format(Constants::DATE_FORMAT))
            ->where('closing_date_time', '>=', $utc_date_now);
        //            ->doesntHave('invited.offer');
        $user = auth()->user();

        if ($user->approvalLimit) {
            if ($user->approvalLimit->isActive()) {
                $new_requests = $new_requests->whereBetween('amount', [intval($user->approvalLimit->minimumLimit), intval($user->approvalLimit->maximumLimit)]);
            }
        }
        if ($request->filled("offer")) {
            $deposit_amount = explode(",", $request->offer);
            if (($deposit_amount[0] > 0) && ($deposit_amount[1] > 0)) {
                $new_requests->whereBetween("depositor_requests.amount", $deposit_amount);
            } else {
                if ($deposit_amount[0] > 0) {
                    $new_requests->where("depositor_requests.amount", ">=", $deposit_amount[0]);
                }
                if ($deposit_amount[1] > 0) {
                    $new_requests->where("depositor_requests.amount", "<=", $deposit_amount[1]);
                }
            }
        }
        if ($request->filled("termLength")) {
            $termlenobject = explode(",", $request->termLength);
            $termtype = $request->termLengthType;

            if (($termlenobject[0] > 0) && ($termlenobject[1] > 0)) {
                $new_requests->where("depositor_requests.term_length_type", $termtype);
                $new_requests->whereBetween("depositor_requests.term_length", array_map('intval', $termlenobject));
            } else {
                if ($termlenobject[0] > 0) {
                    $new_requests->where("depositor_requests.term_length_type", $termtype);
                    $new_requests->where("depositor_requests.term_length", ">=", (int) $termlenobject[0]);
                }
                if ($termlenobject[1] > 0) {
                    $new_requests->where("depositor_requests.term_length_type", $termtype);
                    $new_requests->where("depositor_requests.term_length", "<=", (int) $termlenobject[1]);
                }
            }
        }

        if ($request->filled("products")) {
            $products = explode(",", $request->products);
            $new_requests->whereIn("products.description", $products);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $searchColumns = [
                'depositor_requests.reference_no',  'products.description', 'organizations.name'
            ];
            $new_requests->where(function ($query) use ($searchColumns, $searchTerm) {
                foreach ($searchColumns as $key => $column) {
                    if ($key == 0) {
                        $query->where($column, 'LIKE', '%' . $searchTerm . '%');
                    } else {
                        $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                    }
                }
            });
        }
        $new_requests = $new_requests->orderBy('id', 'DESC')->paginate(10);
        $new_requests->getCollection()->transform(function ($record) {
            $record->encoded_request_id = CustomEncoder::urlValueEncrypt($record->id);
            $record->closing_date_time = changeDateFromUTCtoLocal($record->closing_date_time);
            return $record;
        });
        return response($new_requests);
    }

    public function fetch(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/new-requests/page-access')) {
            $response = array(
                "draw" => 1,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            );
            return response()->json($response);
        }

        ## Read value
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length : 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        return BankData::newRequestData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                "reference_no",
                "depositor_name",
                "province",
                "amount",
                "product",
                "investment_period",
                //                "closing_date_time",
                "action"
            ];

            if (!empty($searchValue)) {

                $search_is_date = false;
                try {
                    try {
                        $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                        $closing_date_time_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT, Constants::DATE_FORMAT);
                    } catch (\Exception $exception) {
                        $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                        $closing_date_time_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT, Constants::DATE_FORMAT);
                    }
                    $data = $data->where('depositor_requests.closing_date_time', 'like', '%' . $closing_date_time_in_utc . '%');
                    $search_is_date = true;
                } catch (\Exception $exception) {
                }

                if (!$search_is_date) {
                    $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                        $query->where('organizations.name', 'like', '%' . $searchValue . '%');
                        foreach ($search_columns as $search_column) {
                            switch ($search_column) {
                                case 'amount':
                                    $searchValue = str_replace(",", "", $searchValue);
                                    $query->orWhere('depositor_requests.amount', 'like', '%' . $searchValue . '%')->orWhere('depositor_requests.currency', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(depositor_requests.currency, ' ', depositor_requests.amount)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'province':
                                    $query->orWhere('demographic_organization_data.province', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'product':
                                    $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'investment_period':
                                    $query->orWhere('depositor_requests.term_length', 'like', '%' . $searchValue . '%')
                                        ->orWhere('depositor_requests.term_length_type', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(term_length, ' ', depositor_requests.term_length_type)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case "reference_no":
                                    $query->orWhere('depositor_requests.reference_no', 'like', '%' . $searchValue . '%');
                                    break;
                            }
                        }
                    });
                }
            }

            if (!$columnIndex && !is_numeric($columnIndex)) {
                $data = $data->orderBy('depositor_requests.reference_no', 'DESC');
            } else {
                switch ($columnName) {
                    case 'closing_date_time':
                        $data = $data->orderBy('depositor_requests.closing_date_time', strtoupper($columnSortOrder));
                        break;
                    case 'amount':
                        $data = $data->orderBy('depositor_requests.amount', strtoupper($columnSortOrder));
                        break;
                    case 'product':
                        $data = $data->orderBy('products.description', strtoupper($columnSortOrder));
                        break;
                    case 'depositor_name':
                        $data = $data->orderBy('organizations.name', strtoupper($columnSortOrder));
                        break;
                    case 'province':
                        $data = $data->orderBy('demographic_organization_data.province', strtoupper($columnSortOrder));
                        break;
                    case 'investment_period':
                        $data = $data->orderBy('depositor_requests.term_length', strtoupper($columnSortOrder));
                        break;
                    case 'reference_no':
                    default:
                        $data = $data->orderBy('depositor_requests.reference_no', strtoupper($columnSortOrder));
                        break;
                }
            }

            $totalRecordswithFilter = with(clone $data)->get()->count();

            $data = $data->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();
            $user_data = \auth()->user();
            $show_view_btn =  ($user_data['is_non_partnered_fi'] == 1 && $user_data['account_status'] == 'ACTIVE' || $user_data['is_non_partnered_fi'] == 0);
            if (!$user_data->userCan('bank/new-requests/view-button')) {
                $show_view_btn = false;
            }

            foreach ($data as $record) {
                $request_id_encoded = CustomEncoder::urlValueEncrypt($record->id);

                $data_arr[] = array(
                    "reference_no" => $record->reference_no,
                    "depositor_name" => $record->depositor,
                    "province" => $record->province ? $record->province : '-',
                    "amount" => $record->currency . ' ' . number_format($record->amount),
                    "product" => $record->product->description,
                    "investment_period" => $record->term_length_type == "HISA" ? "-" : $record->term_length . ' ' . ucwords(strtolower($record->term_length_type)),
                    //"closing_date_time" => $record->closing_date_time ? changeDateFromUTCtoLocal($record->closing_date_time,Constants::DATE_TIME_FORMAT_NO_SECONDS) : '-',
                    "closing_date_time" => $record->closing_date_time ? timeIn_12_24_format($record->closing_date_time) : '-',
                    "action" => $show_view_btn ? '<a href="' . route('bank.place-offer', $request_id_encoded) . '?fromPage=new-requests" class="btn custom-primary round font-weight-bold  mmy_btn btn-block custom-primary round text-white">View</a>' : '-',
                );
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr
            );

            return response()->json($response);
        });
    }



    private function counterOfferEmails($organization_receiving_counter_offer, $organization_sending_counter_offer, $counter_offer)
    {
        $org_receiving_obj = $organization_receiving_counter_offer->notifiableUsersEmails($return_emails = false);

        for ($i = 0; $i < count($org_receiving_obj); $i++) {
            Mail::to($org_receiving_obj[$i]->email)->queue(new NewCounterOfferMail([
                'message' => "",
                'subject' => "You have received a counter offer",
                'header' => $organization_sending_counter_offer->name . " has sent you a counter offer",
                'user_type' => $organization_receiving_counter_offer->type,
                'counter_offer' => $counter_offer,
                'show_login' => false,
                'timezone' => changeDateFromUTCtoLocal($counter_offer->offer_expiry, 'M d Y', null, null, $org_receiving_obj[$i]) . " " . $org_receiving_obj[$i]->timezone
            ]));
        }

        $org_sending_obj = $organization_sending_counter_offer->notifiableUsersEmails($return_emails = false);

        foreach ($org_sending_obj as $obj) {
            Mail::to($obj->email)->queue(new NewCounterOfferMail([
                'message' => "",
                'subject' => "Your counter offer has been placed",
                'header' => 'Here is your counter to ' . $organization_receiving_counter_offer->name,
                'user_type' => $organization_sending_counter_offer->type,
                'counter_offer' => $counter_offer,
                'show_login' => true,
                'timezone' => changeDateFromUTCtoLocal($counter_offer->offer_expiry, 'M d Y', null, null, $obj) . " " . $obj->timezone
            ]));
        }

        notify([
            'type' => ' COUNTER OFFER REQUEST',
            'details' => 'This is to notify you of a new counter offer',
            'date_sent' => getUTCDateNow(true),
            'status' => 'ACTIVE',
            'organization_id' => $organization_receiving_counter_offer->id
        ]);

        Mail::to(getAdminEmails())->queue(new NewCounterOfferMail([
            'message' => '',
            'subject' => "Counter Offer Sent. Offer Ref: " . $counter_offer->offer->reference_no,
            'header' => "",
            'user_type' => 'Admin',
            'counter_offer' => $counter_offer,
            'show_login' => true,
            'timezone' => Carbon::parse($counter_offer->offer_expiry)->format('M d Y')
        ]));
    }

    private function recursive_array_diff($a1, $a2)
    {
        $r = array();
        foreach ($a1 as $k => $v) {
            if ($k == "modified_date" || $k == "invited") {
                continue;
            }

            if (array_key_exists($k, $a2)) {
                if (is_array($v)) {
                    $rad = $this->recursive_array_diff($v, $a2[$k]);
                    if (count($rad)) {
                        $r[$k] = $rad;
                    }
                } else {
                    if ($v != $a2[$k]) {
                        $r[$k] = $v;
                    }
                }
            } else {
                $r[$k] = $v;
            }
        }
        return $r;
    }
}

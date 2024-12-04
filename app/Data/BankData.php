<?php

namespace App\Data;

use App\Constants;
use App\Models\Deposit;
use App\Models\DepositRequest;
use App\Models\Campaign;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Log;

use App\Models\Product;

class BankData
{
    public static $pages_restricted_for_non_invited_fi = ['dashboard', 'account-setting', 'bank-active-deposits', 'bank-history', 'bank-reports', 'account-setting', 'notifications'];

    public static function newRequestData($limit = null, callable $callback = null)
    {
        $utc_date_now = getUTCTimeNow()->format(Constants::DATE_TIME_FORMAT);
        $requests = DepositRequest::select([
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
                $requests = $requests->whereBetween('amount', [intval($user->approvalLimit->minimumLimit), intval($user->approvalLimit->maximumLimit)]);
            }
        }

        $size = with(clone $requests)->get()->count();
        $total_amount['USD'] = with(clone $requests)->where('currency', 'USD')->sum('amount');
        $total_amount['CAD'] = with(clone $requests)->where('currency', 'CAD')->sum('amount');

        $data = $requests;

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data/*->orderBy('closing_date_time','DESC')*/->orderBy('id', 'DESC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }


    public static function inProgressData($limit=null, callable $callback=null){
        Log::alert(\auth()->user()->organization->id);
        $requests = Offer::with(['counterOffers'])->select([
            'offers.*',
            'depositor_requests.closing_date_time',
            'organizations.name as depositor'
        ])->join('invited', function ($join) {
            $join->on('invited.id', '=', 'offers.invitation_id');
            $join->where('invited.organization_id', \auth()->user()->organization->id);
            $join->whereIn('invitation_status', ['PARTICIPATED']);
        })
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            ->join('users', 'users.id', '=', 'depositor_requests.user_id')
            ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->leftJoin('products', 'products.id', '=', 'depositor_requests.product_id')
            ->whereIn('depositor_requests.request_status', ['ACTIVE'])
            ->whereIn('offers.offer_status', ['ACTIVE']);
        $user = auth()->user();
        if ($user->approvalLimit) {
            if ($user->approvalLimit->isActive()) {
                $requests = $requests->whereBetween('amount', [intval($user->approvalLimit->minimumLimit), intval($user->approvalLimit->maximumLimit)]);
            }
        }
        $size = with(clone $requests)->count();
        $total_amount['USD'] = with(clone $requests)->where('currency', 'USD')->sum('amount');
        $total_amount['CAD'] = with(clone $requests)->where('currency', 'CAD')->sum('amount');

        $data = $requests;

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data->orderBy('offers.reference_no', 'DESC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function pendingDepositData($limit = null, callable $callback = null)
    {
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            ->join('users', 'users.id', '=', 'depositor_requests.user_id')
            ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->where('invited.organization_id', \auth()->user()->organization->id)
            ->whereHas('offer.invited.depositRequest.user')
            ->whereIn('deposits.status', ['PENDING_DEPOSIT']);
        $user = auth()->user();
        if ($user->approvalLimit) {
            if ($user->approvalLimit->isActive()) {
                $contracts = $contracts->whereBetween('amount', [intval($user->approvalLimit->minimumLimit), intval($user->approvalLimit->maximumLimit)]);
            }
        }
        $size = with(clone  $contracts)->count();
        $total_amount['USD'] = with(clone $contracts)->where('depositor_requests.currency', 'USD')->sum('deposits.offered_amount');
        $total_amount['CAD'] = with(clone $contracts)->where('depositor_requests.currency', 'CAD')->sum('deposits.offered_amount');

        $data = $contracts->select([
            'deposits.*',
            'invited.depositor_request_id',
            'invited.invited_user_id', 'depositor_requests.term_length',
            'depositor_requests.term_length_type',
            'depositor_requests.amount',
            'depositor_requests.currency',
            'depositor_requests.user_id',
            'depositor_requests.closing_date_time',
            'depositor_requests.date_of_deposit',
            'depositor_requests.product_id',
            'offers.interest_rate_offer',
            'offers.maximum_amount',
            'offers.minimum_amount',
            'offers.rate_held_until',
            'offers.rate_type',
            'offers.fixed_rate',
            'offers.prime_rate',
            'offers.rate_operator',
            DB::raw("organizations.name as depositor_name"),
            DB::raw("products.description as product_name")
        ]);

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data->orderBy('deposits.reference_no', 'DESC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function activeDepositData($limit = null, callable $callback = null)
    {
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            ->join('users', 'users.id', '=', 'depositor_requests.user_id')
            ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->where('invited.organization_id', \auth()->user()->organization->id)
            ->whereHas('offer.invited.depositRequest.user')
            ->whereIn('deposits.status', ['ACTIVE']);
        $user = auth()->user();
        if ($user->approvalLimit) {
            if ($user->approvalLimit->isActive()) {
                $contracts = $contracts->whereBetween('amount', [intval($user->approvalLimit->minimumLimit), intval($user->approvalLimit->maximumLimit)]);
            }
        }
        $size = with(clone  $contracts)->count();
        $total_amount['USD'] = with(clone $contracts)->where('depositor_requests.currency', 'USD')->sum('deposits.offered_amount');
        $total_amount['CAD'] = with(clone $contracts)->where('depositor_requests.currency', 'CAD')->sum('deposits.offered_amount');

        $data = $contracts->select([
            'deposits.*',
            'invited.depositor_request_id',
            'invited.invited_user_id', 'depositor_requests.term_length',
            'depositor_requests.term_length_type',
            'depositor_requests.amount',
            'depositor_requests.currency',
            'depositor_requests.user_id',
            'depositor_requests.closing_date_time',
            'depositor_requests.date_of_deposit',
            'depositor_requests.product_id',
            'offers.interest_rate_offer',
            'offers.maximum_amount',
            'offers.minimum_amount',
            'offers.rate_held_until',
            'offers.rate_type',
            'offers.prime_rate',
            'offers.rate_operator',
            DB::raw("organizations.name as depositor_name"),
            'offers.fixed_rate',
            DB::raw("products.description as product_name")
        ]);

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data->orderBy('deposits.maturity_date', 'ASC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function depositHistoryData($limit = null, callable $callback = null)
    {
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            //            ->join('users','users.id','=','invited.invited_user_id')
            ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->whereHas('offer.invited.organization')
            ->where('invited.organization_id', \auth()->user()->organization->id)
            ->whereIn('deposits.status', ['MATURED', 'WITHDRAWN', 'INCOMPLETE', 'EARLY_REDEMPTION']);
        $user = auth()->user();
        if ($user->approvalLimit) {
            if ($user->approvalLimit->isActive()) {
                $contracts = $contracts->whereBetween('amount', [intval($user->approvalLimit->minimumLimit), intval($user->approvalLimit->maximumLimit)]);
            }
        }
        $size = with(clone  $contracts)->get()->count();
        $total_amount['USD'] = with(clone $contracts)->where('depositor_requests.currency', 'USD')->sum('deposits.offered_amount');
        $total_amount['CAD'] = with(clone $contracts)->where('depositor_requests.currency', 'CAD')->sum('deposits.offered_amount');

        $data = $contracts->select([
            'deposits.*',
            'invited.depositor_request_id',
            'invited.invited_user_id',
            'depositor_requests.term_length',
            'depositor_requests.term_length_type',
            'depositor_requests.amount',
            'depositor_requests.currency',
            'depositor_requests.user_id',
            'depositor_requests.product_id',
            'offers.interest_rate_offer',
            'offers.rate_type',
            'offers.prime_rate',
            'offers.rate_operator',
            DB::raw("organizations.name as depositor_name"),
            'offers.fixed_rate',
            DB::raw("products.description as product_name")
        ]);

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data->orderBy('deposits.modified_at', 'ASC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function offerHistoryData($limit = null, callable $callback = null)
    {
        $requests = Offer::select([
            'offers.*',
            'organizations.name as depositor'
        ])->join('invited', function ($join) {
            $join->on('invited.id', '=', 'offers.invitation_id');
            $join->where('invited.organization_id', \auth()->user()->organization->id);
        })->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            ->join('users', 'users.id', '=', 'depositor_requests.user_id')
            ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->leftJoin('products', 'products.id', '=', 'depositor_requests.product_id')
            ->whereIn('offers.offer_status', ['NOT_SELECTED', 'EXPIRED', 'REQUEST_WITHDRAWN', 'OFFER_WITHDRAWN']);
        $user = auth()->user();
        if ($user->approvalLimit) {
            if ($user->approvalLimit->isActive()) {
                $requests = $requests->whereBetween('amount', [intval($user->approvalLimit->minimumLimit), intval($user->approvalLimit->maximumLimit)]);
            }
        }
        $size = with(clone $requests)->get()->count();
        $total_amount['USD'] = with(clone $requests)->where('currency', 'USD')->sum('amount');
        $total_amount['CAD'] = with(clone $requests)->where('currency', 'CAD')->sum('amount');

        $data = $requests;

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data->orderBy('offers.modified_date', 'DESC')
                ->orderBy('offers.id', 'DESC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function bankReportsData($limit = null, callable $callback = null)
    {
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            ->join('users', 'users.id', '=', 'depositor_requests.user_id')
            ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->where('invited.organization_id', \auth()->user()->organization->id)
            ->whereIn('deposits.status', ['ACTIVE']);
        $user = auth()->user();
        if ($user->approvalLimit) {
            if ($user->approvalLimit->isActive()) {
                $contracts = $contracts->whereBetween('amount', [intval($user->approvalLimit->minimumLimit), intval($user->approvalLimit->maximumLimit)]);
            }
        }
        $size = with(clone  $contracts)->get()->count();
        $total_amount['USD'] = with(clone $contracts)->where('depositor_requests.currency', 'USD')->sum('deposits.offered_amount');
        $total_amount['CAD'] = with(clone $contracts)->where('depositor_requests.currency', 'CAD')->sum('deposits.offered_amount');

        $data = $contracts->select([
            'deposits.*',
            'invited.depositor_request_id',
            'invited.invited_user_id',
            'depositor_requests.lockout_period_days',
            'depositor_requests.term_length',
            'depositor_requests.term_length_type',
            'depositor_requests.amount',
            'depositor_requests.currency',
            'depositor_requests.user_id',
            'depositor_requests.product_id',
            'offers.interest_rate_offer',
            'offers.rate_type',
            'offers.prime_rate',
            'offers.rate_operator',
            'demographic_organization_data.province as province',
            DB::raw("organizations.name as depositor_name"),
            'offers.fixed_rate',
            DB::raw("products.description as product_name")
        ]);

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data->orderBy('deposits.reference_no', 'DESC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function marketPlaceOffersData($limit = null, callable $callback = null)
    {
        $market_place_offers = Campaign::join('organizations', 'market_place_offers.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'market_place_offers.product_id');

        $size = with(clone  $market_place_offers)->count();

        $data = $market_place_offers->select([
            'market_place_offers.*',
            'demographic_organization_data.province as province',
            DB::raw("organizations.name as bank_name"),
            DB::raw("products.description as product_name")
        ]);

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data->orderBy('market_place_offers.reference_no', 'DESC')->get();
        }

        return ['total' => $size, 'data' => $data];
    }


    public static function campaignDepositData($campaign, $returntype = "list", $statuses, $period = null)
    {

        $pendingcampaignsbought = DB::table("campaign_f_i_campaign_products")->join("offers", "offers.campaign_product_id", "=", "campaign_f_i_campaign_products.id")
            ->join("deposits", "deposits.offer_id", "=", "offers.id")
            ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
            ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
            ->where('campaign_f_i_campaign_products.campaign_id', $campaign);
        if ($period != null) {
            $pendingcampaignsbought->whereBetween(DB::raw('DATE(deposits.created_at)'), $period);
        }
        $pendingcampaignsbought->whereIn('deposits.status', $statuses);
        if ($returntype === "list") {
            $result = $pendingcampaignsbought->select(
                'products.description AS product',
                DB::raw('SUM(offered_amount) as total_offers')
            )->groupBy("product")->get();

            return $result;
        } elseif ($returntype === "sum") {
            $result = $pendingcampaignsbought->select(
                'products.description AS product',
                'offered_amount'
            )->get();
            return $result;
        }
    }
    public static function ratesComparison($campaign, $returntype = "list", $statuses, $period = null, $org)
    {
        $products = Product::all()->toArray();
        $prodrates = [];
        $myrates = [];
        $marketrates = [];
        foreach ($products as $prod) {
            //market rates
            $productMonthTrend =
                Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
                ->join("campaign_f_i_campaign_products", "campaign_f_i_campaign_products.id", "=", "offers.campaign_product_id")
                ->join('invited', 'invited.id', '=', 'offers.invitation_id')
                ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
                ->join(
                    'users',
                    'users.id',
                    '=',
                    'depositor_requests.user_id'
                )
                ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
                ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
                ->join('products', 'products.id', '=', 'depositor_requests.product_id');
            if ($period != null) {
                $productMonthTrend->whereBetween(DB::raw('DATE(deposits.created_at)'), $period);
            }
            $productMonthTrend->where('products.id', $prod['id']);
            $productMonthTrend->whereNotIn('depositor_requests.organization_id', [$org]);
            $productMonthTrend->whereIn('deposits.status', $statuses);
            $result = $productMonthTrend->select(
                'products.description as product',
                DB::raw('AVG(campaign_f_i_campaign_products.rate) as market_rates')
            )->groupBy('products.description')->first();
            array_push($marketrates, $result);
            //market rates
            //my rates rates
            $myra = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
                ->join("campaign_f_i_campaign_products", "campaign_f_i_campaign_products.id", "=", "offers.campaign_product_id")
                ->join('invited', 'invited.id', '=', 'offers.invitation_id')
                ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
                ->join('users', 'users.id', '=', 'depositor_requests.user_id')
                ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
                ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
                ->join('products', 'products.id', '=', 'depositor_requests.product_id');
            if ($period != null) {
                $myra->whereBetween(DB::raw('DATE(deposits.created_at)'), $period);
            }
            $myra->where('products.id', $prod['id']);
            $myra->whereIn('depositor_requests.organization_id', [$org]);
            $myra->whereIn('deposits.status', $statuses);
            $myr = $myra->select(
                'products.description as product',
                DB::raw('AVG(campaign_f_i_campaign_products.rate) as my_rates')
            )->groupBy('products.description')->first();
            array_push($myrates, $myr);
            //my rates

        }
        return ['my_rates' => $myrates, 'market_rates' => $marketrates];
    }
}

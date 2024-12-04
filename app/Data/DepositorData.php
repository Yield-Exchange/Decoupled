<?php

namespace App\Data;

use App\Constants;
use App\CustomEncoder;
use App\Models\Deposit;
use App\Models\DepositRequest;
use DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Http\Resources\PostRequestResource;

class DepositorData
{
    public static function reviewOfferData($request)
    {
        $utc_date_now = getUTCTimeNow()->format(Constants::DATE_TIME_FORMAT);
        $requests = DepositRequest::select([
            'depositor_requests.*',
            'offers.rate_type',
            'offers.prime_rate',
            'offers.fixed_rate',
            'offers.rate_operator',
            DB::raw('MAX(offers.interest_rate_offer) as max_interest_rate_offer'),
            DB::raw('MIN(offers.interest_rate_offer) as min_interest_rate_offer'),
            DB::raw('COUNT(offers.id) as total_offers')
        ])->leftJoin('products', 'products.id', '=', 'depositor_requests.product_id')
            ->leftJoin('invited', function ($join) {
                $join->on('depositor_request_id', '=', 'depositor_requests.id');
                $join->whereIn('invitation_status', ['INVITED', 'PARTICIPATED']);
            })
            ->leftJoin('offers', function ($join) {
                $join->on('offers.invitation_id', '=', 'invited.id');
                $join->where('offers.offer_status', 'ACTIVE');
                $join->where('invited.invitation_status', 'PARTICIPATED');
            })
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id)
            ->whereIn('depositor_requests.request_status', ['ACTIVE', 'ON_REVIEW'])
            ->where('date_of_deposit', '>=', getUTCTimeNow()->format(Constants::DATE_FORMAT))
            ->where(function ($query) use ($utc_date_now) {
                $query->where('closing_date_time', '>=', $utc_date_now)->orWhere(function ($query) use ($utc_date_now) {
                    $query->where('closing_date_time', '<', $utc_date_now)->whereIn('invited.invitation_status', ['PARTICIPATED']);
                });
            })->groupBy('depositor_requests.id');

        $size = with(clone $requests)->get()->count();
        $total_amount['USD'] = with(clone $requests)->get()->where('currency', 'USD')->sum('amount');
        $total_amount['CAD'] = with(clone $requests)->get()->where('currency', 'CAD')->sum('amount');
        $data = $requests;
        //dd($data->toSql(), $data->getBindings());
        $data = $data->orderBy('closing_date_time', 'DESC')->orderBy('id', 'ASC')->paginate(isset($request->per_page) ? $request->per_page : 10);
        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function reviewOfferDataNew($request)
    {
        $utc_date_now = getUTCTimeNow()->format(Constants::DATE_TIME_FORMAT);
   
        $requests = DepositRequest::select([
            'depositor_requests.*',
            'offers.rate_type',
            'offers.prime_rate',
            'offers.fixed_rate',
            'offers.rate_operator',
            DB::raw('MAX(offers.interest_rate_offer) as max_interest_rate_offer'),
            DB::raw('MIN(offers.interest_rate_offer) as min_interest_rate_offer'),
            DB::raw('COUNT(offers.id) as total_offers')
        ])->leftJoin('products', 'products.id', '=', 'depositor_requests.product_id')
            ->leftJoin('invited', function ($join) {
                $join->on('depositor_request_id', '=', 'depositor_requests.id');
                $join->whereIn('invitation_status', ['INVITED', 'PARTICIPATED']);
            })
            ->leftJoin('offers', function ($join) {
                $join->on('offers.invitation_id', '=', 'invited.id');
                $join->where('offers.offer_status', 'ACTIVE');
                $join->where('invited.invitation_status', 'PARTICIPATED');
            })
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id);
        //search
        if ($request->filled("search")) {
            $requests->where(function ($query) use ($request) {
                $query->where("products.description", "like", "%" . $request->search . "%")
                    ->orwhere("depositor_requests.reference_no", "like", "%" . $request->search . "%")
                    ->whereIn('depositor_requests.request_status', ['ACTIVE', 'ON_REVIEW'])
                    ->where('depositor_requests.organization_id', \auth()->user()->organization->id);
            });
        }
        //search
        // filter closing
        if ($request->filled("closing_dates")) {
            $explodedInput = explode(",", $request->closing_dates);
            if ($explodedInput[0] === '0' && $explodedInput[1] === '0') {
                $closingdateFilter = [];
            } else {
                if ($explodedInput[0] != '0' && $explodedInput[1] === '0') {
                    $startOfDay = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $explodedInput[0] . " 00:00:00");
                    $requests->where('depositor_requests.closing_date_time', ">=", $startOfDay);
                } else if ($explodedInput[0] === '0' && $explodedInput[1] != '0') {
                    $endOfDay = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $explodedInput[1] . " 23:59:59");
                    $requests->where('depositor_requests.closing_date_time', "<=", $endOfDay);
                } else if ($explodedInput[0] != '0' && $explodedInput[1] != '0') {
                    $startOfDay = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $explodedInput[0] . " 00:00:00");
                    $endOfDay = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $explodedInput[1] . " 23:59:59");
                    $requests->whereBetween('depositor_requests.closing_date_time', [$startOfDay, $endOfDay]);
                }
            }
        }
        //filter closing
        // filter request amount
        if ($request->filled("request_amount")) {
            $explodedRequestInput = explode(",", $request->request_amount);
            if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] === '0') {
                $closingdateFilter = [];
            } else {
                if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] === '0') {
                    $requests->where('depositor_requests.amount', ">=", $explodedRequestInput[0]);
                } else if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] != '0') {
                    $requests->where('depositor_requests.amount', "<=", $explodedRequestInput[1]);
                } else if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] != '0') {
                    $requests->whereBetween('depositor_requests.amount', $explodedRequestInput);
                }
            }
        }
        //filter request amount

        // filter duration
        if ($request->filled("termLength") && $request->filled("termLengthType")) {
            $termLengthType = $request->termLengthType;
            $explodedRequestInput = explode(",", $request->termLength);
            if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] === '0') {
                $closingdateFilter = [];
            } else {
                $requests->where('depositor_requests.term_length_type', $termLengthType);
                if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] === '0') {
                    $requests->where('depositor_requests.term_length', ">=", $explodedRequestInput[0]);
                } else if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] != '0') {
                    $requests->where('depositor_requests.term_length', "<=", $explodedRequestInput[1]);
                } else if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] != '0') {
                    $requests->whereBetween('depositor_requests.term_length', $explodedRequestInput);
                }
            }
        }
        //filter duration
        //product
        if ($request->filled("product_type")) {
            $requests->whereIn("products.description", explode(",", $request->product_type));
        }
        //product
        $requests->whereIn('depositor_requests.request_status', ['ACTIVE', 'ON_REVIEW'])
            ->where('date_of_deposit', '>=', getUTCTimeNow()->format(Constants::DATE_FORMAT))
            ->where(function ($query) use ($utc_date_now) {
                $query->where('closing_date_time', '>=', $utc_date_now)->orWhere(function ($query) use ($utc_date_now) {
                    $query->where('closing_date_time', '<', $utc_date_now)->whereIn('invited.invitation_status', ['PARTICIPATED']);
                });
            });

        $requests->groupBy('depositor_requests.id');
        $size = with(clone $requests)->get()->count();
        $total_amount['USD'] = with(clone $requests)->get()->where('currency', 'USD')->sum('amount');
        $total_amount['CAD'] = with(clone $requests)->get()->where('currency', 'CAD')->sum('amount');

        $data = $requests->orderBy('closing_date_time', 'DESC')->orderBy('id', 'ASC')->paginate(isset($request->per_page) ? $request->per_page : 10);
        $dataToArray = $data->toArray();

        $re = PostRequestResource::collection($dataToArray['data']);
        $respose = [
            'first_page_url' => $dataToArray['first_page_url'],
            'from' => $dataToArray['from'],
            'last_page' => $dataToArray['last_page'],
            'last_page_url' => $dataToArray['last_page'],
            'links' => $dataToArray['links'],
            'next_page_url' => $dataToArray['next_page_url'],
            'path' => $dataToArray['path'],
            'per_page' => $dataToArray['per_page'],
            'prev_page_url' => $dataToArray['prev_page_url'],
            'to' => $dataToArray['per_page'],
            'total' => $dataToArray['total'],
            'data' => $re,
        ];
        return ['total' => $size, 'data' => $respose, 'total_deposit' => $total_amount];
    }

    public static function pendingDepositData($limit = null, callable $callback = null)
    {
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            //->join('users','users.id','=','invited.invited_user_id')
            //->join('users','users.id','=','depositor_requests.user_id')
            ->join('organizations', 'invited.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->whereHas('offer.invited.organization')
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id)
            ->whereIn('deposits.status', ['PENDING_DEPOSIT']);

        $size = with(clone  $contracts)->count();
        $total_amount['USD'] = with(clone $contracts)->where('depositor_requests.currency', 'USD')->sum('deposits.offered_amount');
        $total_amount['CAD'] = with(clone $contracts)->where('depositor_requests.currency', 'CAD')->sum('deposits.offered_amount');

        $data = $contracts->select([
            'deposits.*',
            'invited.depositor_request_id',
            'invited.organization_id',
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
            'offers.fixed_rate',
            'offers.rate_operator',
            'offers.id as offer_id',
            DB::raw("organizations.name as bank_name"),
            DB::raw("products.description as product_name")
        ]);

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data->orderBy('depositor_requests.date_of_deposit', 'ASC')->orderBy('depositor_requests.id', 'ASC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function activeDepositData($limit = null, callable $callback = null)
    {
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            //            ->join('users','users.id','=','invited.invited_user_id')
            ->join('organizations', 'invited.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id)
            ->whereHas('offer.invited.organization')
            ->whereIn('deposits.status', ['ACTIVE']);

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
            DB::raw("organizations.name as bank_name"),
            DB::raw("products.description as product_name")
        ]);

        if ($limit != null) {
            $data = $data->take($limit);
        }

        if ($callback) {
            return $callback($data);
        } else {
            $data = $data->orderBy('deposits.maturity_date', 'ASC')->orderBy('deposits.id', 'ASC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function requestHistoryData($limit = null, callable $callback = null)
    {
        $utc_date_now = getUTCTimeNow()->format(Constants::DATE_TIME_FORMAT);
        $requests = DepositRequest::select([
            'depositor_requests.*',
            'offers.rate_type',
            'offers.prime_rate',
            'offers.rate_operator',
            DB::raw('COUNT(offers.id) as total_offers'),
        ])->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->leftJoin('invited', 'invited.depositor_request_id', '=', 'depositor_requests.id')
            ->leftJoin('offers', 'offers.invitation_id', '=', 'invited.id')
            ->leftJoin('deposits', 'deposits.offer_id', '=', 'offers.id')
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id)
            ->where(function ($query) use ($utc_date_now) {
                $query->whereIn('depositor_requests.request_status', ['EXPIRED', 'NO_OFFERS_RECEIVED', 'WITHDRAWN'])
                    ->orWhere(function ($query) use ($utc_date_now) {
                        $query->where(function ($query) use ($utc_date_now) {
                            $query->where('date_of_deposit', '<', getUTCTimeNow()->format(Constants::DATE_FORMAT))
                                ->whereDoesntHave('invited.offer', function ($query) {
                                    $query->whereIn('offer_status', ['SELECTED']);
                                });
                        })->orWhere(function ($query) use ($utc_date_now) {
                            $query->where('depositor_requests.closing_date_time', '<', $utc_date_now)
                                ->whereDoesntHave('invited', function ($query) {
                                    $query->whereIn('invitation_status', ['PARTICIPATED']);
                                });
                        });
                    });
            })->whereHas('invited.organization')
            ->whereDoesntHave('invited.offer.deposit')
            ->groupBy('depositor_requests.id');

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
            $data = $data->orderBy('depositor_requests.modified_date', 'DESC')
                ->orderBy('depositor_requests.created_date', 'DESC')->get();
        }

        return ['total' => $size, 'data' => $data, 'total_deposit' => $total_amount];
    }

    public static function depositHistoryData($limit = null, callable $callback = null)
    {
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            //            ->join('users','users.id','=','invited.invited_user_id')
            ->join('organizations', 'invited.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->whereHas('offer.invited.organization')
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id)
            ->whereIn('deposits.status', ['MATURED', 'WITHDRAWN', 'INCOMPLETE', 'EARLY_REDEMPTION']);

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
            DB::raw("organizations.name as bank_name"),
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

    public static function depositReportsData($limit = null, callable $callback = null)
    {
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            //            ->join('users','users.id','=','invited.invited_user_id')
            ->join('organizations', 'invited.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id)
            ->whereIn('deposits.status', ['ACTIVE']);

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
            DB::raw("organizations.name as bank_name"),
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
    public static function marketSectorAnalysis($campaign, $returntype = "list", $statuses, $period = null)
    {
        $marketsegmentation = DB::table("campaign_f_i_campaign_products")
            ->join("offers", "offers.campaign_product_id", "=", "campaign_f_i_campaign_products.id")
            ->join("deposits", "deposits.offer_id", "=", "offers.id")
            ->join("invited", "invited.id", "=", "offers.invitation_id")
            ->join("depositor_requests", "depositor_requests.id", "=", "invited.depositor_request_id")
            ->join("organizations", "organizations.id", "=", "depositor_requests.organization_id")
            ->join('industries', 'industries.id', '=', 'organizations.industry_id')
            ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
            ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
            ->whereYear("deposits.created_at", Carbon::now("UTC")->year);
        // if ($period != null) {
        //     $marketsegmentation->whereBetween(DB::raw('DATE(deposits.created_at)'), $period);
        // }

        $marketsegmentation->whereIn('deposits.status', $statuses);

        if ($returntype === "list") {


            $result = $marketsegmentation->select(
                'products.description',
                DB::raw('COUNT(deposits.id) as total_bought')
            )->groupBy("products.description")->get();
            return $result;
        } elseif ($returntype === "sum") {
            $result = $marketsegmentation->select(
                'products.description AS product',
                'offered_amount'
            )->get();
            return $result;
        }
    }
    public static function termAnalysis($campaign, $returntype = "list", $statuses, $period = null)
    {
        $marketsegmentation = DB::table("campaign_f_i_campaign_products")
            ->join("offers", "offers.campaign_product_id", "=", "campaign_f_i_campaign_products.id")
            ->join("deposits", "deposits.offer_id", "=", "offers.id")
            ->join("invited", "invited.id", "=", "offers.invitation_id")
            ->join("depositor_requests", "depositor_requests.id", "=", "invited.depositor_request_id")
            ->join("organizations", "organizations.id", "=", "depositor_requests.organization_id")
            ->join('industries', 'industries.id', '=', 'organizations.industry_id')
            ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
            ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
            ->whereYear("deposits.created_at", Carbon::now("UTC")->year);
        // if ($period != null) {
        //     $marketsegmentation->whereBetween(DB::raw('DATE(deposits.created_at)'), $period);
        // }
        $marketsegmentation->whereIn('deposits.status', $statuses);

        if ($returntype === "list") {


            $result = $marketsegmentation->select(
                DB::raw('CONCAT(f_i_campaign_products.term_length," ",f_i_campaign_products.term_length_type) as term'),
                DB::raw('COUNT(deposits.id) as total_bought')
            )->groupBy("f_i_campaign_products.term_length", "f_i_campaign_products.term_length_type")->get();
            return $result;
        } elseif ($returntype === "sum") {
            $result = $marketsegmentation->select(
                'products.le AS product',
                'offered_amount'
            )->get();
            return $result;
        }
    }
    public static function productMonthTrend($campaign, $returntype = "list", $statuses, $period = null)
    {
        $products = Product::all()->toArray();
        $prodtrend = [];
        foreach ($products as $prod) {
            $productMonthTrend = DB::table("campaign_f_i_campaign_products")
                ->join("offers", "offers.campaign_product_id", "=", "campaign_f_i_campaign_products.id")
                ->join("deposits", "deposits.offer_id", "=", "offers.id")
                ->join("invited", "invited.id", "=", "offers.invitation_id")
                ->join("depositor_requests", "depositor_requests.id", "=", "invited.depositor_request_id")
                ->join("organizations", "organizations.id", "=", "depositor_requests.organization_id")
                ->join('industries', 'industries.id', '=', 'organizations.industry_id')
                ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
                ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
                ->whereYear("deposits.created_at", Carbon::now("UTC")->year);
            // if ($period != null) {
            //     $productMonthTrend->whereBetween(DB::raw('DATE(deposits.created_at)'), $period);
            // }
            $productMonthTrend->where('f_i_campaign_products.product_type_id', $prod['id']);
            // $productMonthTrend->whereIn('deposits.status', $statuses);
            $result = $productMonthTrend->select(
                DB::raw('YEAR(deposits.created_at) as Year'),
                DB::raw('MONTH(deposits.created_at) as Month'),
                DB::raw('SUM(deposits.offered_amount) as total_bought')
            )->groupBy(DB::raw('MONTH(deposits.created_at)'))->first();

            // array_push($prodtrend, [$prod['description'] => ($result != null) ? $result->total_bought : 0]);
            array_push($prodtrend, [str_replace([" ", "-"], "_", ucwords($prod['description']))  => ($result) ? [$result] : null]);
            // $temparr[$prod['description']] = $result;
            // if ($result->total_bought) {
            //     array_push($prodtrend, [$prod['description'] => $result->total_bought]);
            // } else {
            //     array_push($prodtrend, [$prod['description'] => 0]);
            // }
        }
        return $prodtrend;
    }
    public static function noOfInvestors($campaign, $returntype = "list", $statuses, $period = null)
    {
        $marketsegmentation = DB::table("campaign_f_i_campaign_products")
            ->join("offers", "offers.campaign_product_id", "=", "campaign_f_i_campaign_products.id")
            ->join("deposits", "deposits.offer_id", "=", "offers.id")
            ->join("invited", "invited.id", "=", "offers.invitation_id")
            ->join("depositor_requests", "depositor_requests.id", "=", "invited.depositor_request_id")
            ->join("organizations", "organizations.id", "=", "depositor_requests.organization_id")
            ->join('industries', 'industries.id', '=', 'organizations.industry_id')
            ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
            ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
            ->whereYear("deposits.created_at", Carbon::now("UTC")->year);
        // if ($period != null) {
        //     $marketsegmentation->whereBetween(DB::raw('DATE(deposits.created_at)'), $period);
        // }
        $marketsegmentation->whereIn('deposits.status', $statuses);
        $result = $marketsegmentation->select(
            DB::raw('COUNT(DISTINCT(depositor_requests.organization_id)) as org_count')
        )->get();
        return $result;
    }
}

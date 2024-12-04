<?php

namespace App\Services\Depositors;

use App\Constants;
use App\CustomEncoder;
use App\Http\Resources\CampaignFICampaignProductResource;
use App\Mail\AdminMail;
use App\Mail\Bank\MarketPlaceOfferSelected;
use App\Models\Campaign;
use App\Models\CampaignFICampaignProduct;
use App\Models\Deposit;
use App\Models\DepositRequest;
use App\Models\InvitedBank;
use App\Models\Offer;
use App\Models\Organization;
use App\Models\Product;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Traits\UserTrait;
use App\Models\CampaignProductView;
use App\Models\CampaignView;
use App\Mail\DepositorMails;
use App\Mail\BankMails;


class CampaignOffersService extends BaseService
{
    use UserTrait;
    public function fetch(Request $request)
    {
        try {
            $data = $this->getActiveOffers($request);

            return response()->jsonSuccess("Offers fetched successfully", $data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->jsonErrorFailure($exception->getMessage());
        }
    }

    public function store(array $data)
    {
        try {
            $data_ = $this->saveOffer($data);
            return response()->jsonSuccess("Offer purchased successfully", ['id' => CustomEncoder::urlValueEncrypt($data_->offer->id)]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->jsonErrorFailure("Failed to purchase product" . $exception->getMessage());
        }
    }

    private function saveOffer($data)
    {
        $user = auth()->user();
        try {
            DB::beginTransaction();

            $req = new Request($data);
            $req['find_data'] = $data['product_id'];
            $product = $this->getActiveOffers($req);
            if (!$product) {
                throw new \Exception("Product not found");
            }

            // create depositor request
            $now = Carbon::now();
            $date_of_deposit = getUTCTimeNow(); //Carbon::parse($data['date_of_deposit']);
            $rate_held_until = $product->expiry_date;
            $deposit_request = DepositRequest::create([
                'reference_no' => generateDepositRequestReference(),
                'term_length_type' => $product->term_length_type,
                'term_length' => strtoupper(trim($product->term_length)),
                'lockout_period_days' => $product->lockout_period,
                'closing_date_time' => $date_of_deposit,
                'amount' => str_replace(",", "", trim($data['amount'])),
                'currency' => $product->currency,
                'date_of_deposit' => $date_of_deposit,
                'compound_frequency' => $product->compound_frequency,
                'requested_rate' => $product->rate,
                'requested_short_term_credit_rating' => '',
                'requested_deposit_insurance' => '',
                'special_instructions' => '',
                'request_status' => 'COMPLETED',
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
                'offer_status' => 'SELECTED',
                'campaign_product_id' => $product->id
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
            $totalProductAmount = DepositRequest::where('campaign_product_id', $product->id)->sum('amount');
            $checkCumulativeTotal = $product->order_limit ? ($totalProductAmount >= $product->order_limit)  : false;
            if ($checkCumulativeTotal) {
                $data_['status'] = 'COMPLETED';
                CampaignFICampaignProduct::where('id', $product->id)->update($data_);
            }

            DB::commit();

            $this->sendEmails($product, $user, $data);

            return $deposit_created;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception);
        }
    }

    private function sendEmails($product, $user, $data = null)
    {
        $user = auth()->user();
        // notify depositors
        $message = "Your have purchased a product " . $product->description;
        Mail::to($user->organization->notifiableUsersEmails())->queue(new DepositorMails([
            'message' => $message,
            'purchasedetails' => ['product' => $product, 'purchased_amount' => $data['amount']],
            'subject' => "Product purchased.",
            'product' => $product,
            'user_type' => "Depositor"
        ], 'campaign_product_purchase'));

        // notify the bank
        $message = "Your product " . $product->default_product_name . " has been purchased! ";
        $banks = Organization::find($product->campaign->fi_id);
        Mail::to($banks->notifiableUsersEmails())->queue(new BankMails([
            'message' => $message,
            'purchasedetails' => ['product' => $product, 'purchased_amount' => $data['amount'], 'depositor' => $user->organization],
            'subject' => "Product purchased",
            'product' => $product,
            'user_type' => "Bank"
        ], 'campaign_product_purchase'));

        //update bank on product performance

        $result = DB::table("campaign_f_i_campaign_products")
            ->join("offers", "offers.campaign_product_id", "=", "campaign_f_i_campaign_products.id")
            ->join("deposits", "deposits.offer_id", "=", "offers.id")
            ->where("offers.campaign_product_id", $product->id)
            ->groupBy("offers.campaign_product_id", "campaign_f_i_campaign_products.id", "campaign_f_i_campaign_products.order_limit")
            ->select(
                DB::raw("(SUM(deposits.offered_amount)/campaign_f_i_campaign_products.order_limit)*100 as percentage"),
                "campaign_f_i_campaign_products.id as campaign_prod_id",
                DB::raw("SUM(deposits.offered_amount) as bought_amount"),
                "campaign_f_i_campaign_products.order_limit as order_limit"
            )
            ->get();


        Mail::to($banks->notifiableUsersEmails())->queue(new BankMails([
            'message' => $message,
            'purchasedetails' => ['product' => $result, 'purchased_amount' => $data['amount'], 'product_id' => $product->id, 'depositor' => $user->organization],
            'subject' => "Offers Off Charts",
            'user_type' => "Bank"
        ], 'campaign_product_purchase'));
        //update bank on product performance

        $notification = "Product {" . $product->default_product_name . "} purchased from campaign {" . $product->campaign->campaign_name . '}.';;
        // Mail::to(getAdminEmails())->send(new AdminMail([
        //     'subject' => "Product purchased.",
        //     'message' => $notification,
        // ]));

        notify([
            'type' => 'Campaign product selected',
            'details' => $notification,
            'date_sent' => getUTCDateNow(),
            'status' => 'ACTIVE',
            'organization_id' => $user->organization->id
        ]);
    }

    private function getActiveOffers(Request $request)
    {
        $user = auth()->user();
        $timezone = formattedTimezone($user->timezone);
        $products = CampaignFICampaignProduct::query()
            ->with([
                'campaign.organization' => function ($query) {
                    $query->select('id', 'name')->without("demographicData");
                },
                'campaign.organization.document' => function ($query) {
                    $query->select('id', 'organization_id', 'file_name');
                },
                'offers' => function ($query) {
                    $query->select('id', 'campaign_product_id')->without(['invited', 'counterOffers']);
                },
                'offers.invited' => function ($query) {
                    $query->select('id', 'amount')->without('invited');
                },
                'offers.counterOffers' => function ($query) {
                    $query->select('id', 'offer_id', 'maximum_amount', 'minimum_amount');
                }
            ])
            ->select([
                DB::raw('f_i_campaign_products.id as campaign_prod_id'),
                'campaign_f_i_campaign_products.id',
                'campaign_f_i_campaign_products.campaign_id',
                'campaign_f_i_campaign_products.fi_campaign_product_id',
                'campaign_f_i_campaign_products.rate_type',
                'campaign_f_i_campaign_products.index_rate',
                'campaign_f_i_campaign_products.status',
                'campaign_f_i_campaign_products.spread',
                DB::raw('MAX(campaign_f_i_campaign_products.rate) as rate'),
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
                'ca.fi_id',
                'ca.expiry_date as expiry_date_utc',
                // Consider converting timezone at application layer if possible for performance
                DB::raw("CONVERT_TZ(ca.expiry_date, '+00:00', '{$timezone}') as expiry_date"),
                'ca.subscription_amount',
            ])
            ->join('campaigns as ca', 'ca.id', '=', 'campaign_f_i_campaign_products.campaign_id')
            ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
            ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
            ->join('campaign_target_groups', 'campaign_target_groups.campaign_id', '=', 'ca.id')
            ->join('f_i_campaign_groups', 'f_i_campaign_groups.id', '=', 'campaign_target_groups.fi_campaign_group_id')
            // ->join('offers','offers.campaign_product_id','=','f_i_campaign_products.id')
            ->join('f_i_campaign_group_depositors', function ($join) use ($user) {
                $join->on('f_i_campaign_group_depositors.fi_campaign_group_id', '=', 'f_i_campaign_groups.id')
                    ->where('f_i_campaign_group_depositors.depositor_id', '=', $user->organization->id);
            });
        // $products = CampaignFICampaignProduct::query()
        //     // Eager loading with specific columns for related models to reduce memory usage
        //     ->with([
        //         'campaign.organization' => function ($query) {
        //             $query->select('id', 'name');
        //         },
        //         'campaign.organization.document' => function ($query) {
        //             $query->select('id', 'organization_id', 'file_name');
        //         },
        //         'offers' => function ($query) {
        //             $query->select('id', 'campaign_product_id');
        //         },
        //         'offers.counterOffers' => function ($query) {
        //             $query->select('id', 'offer_id', 'maximum_amount', 'minimum_amount');
        //         }
        //     ])
        //     ->select([
        //         DB::raw('f_i_campaign_products.id as campaign_prod_id'),
        //         'campaign_f_i_campaign_products.id',
        //         'campaign_f_i_campaign_products.campaign_id',
        //         'campaign_f_i_campaign_products.fi_campaign_product_id',
        //         'campaign_f_i_campaign_products.rate_type',
        //         'campaign_f_i_campaign_products.index_rate',
        //         'campaign_f_i_campaign_products.status',
        //         'campaign_f_i_campaign_products.spread',
        //         DB::raw('MAX(campaign_f_i_campaign_products.rate) as rate'),
        //         'campaign_f_i_campaign_products.minimum',
        //         'campaign_f_i_campaign_products.maximum',
        //         'campaign_f_i_campaign_products.order_limit',
        //         'campaign_f_i_campaign_products.isFeatured',
        //         DB::raw('LCASE(f_i_campaign_products.term_length_type) as term_length_type'),
        //         'f_i_campaign_products.term_length',
        //         'f_i_campaign_products.compound_frequency',
        //         'f_i_campaign_products.lockout_period',
        //         'f_i_campaign_products.interest_paid',
        //         'f_i_campaign_products.default_product_name',
        //         'f_i_campaign_products.pds',
        //         'products.description',
        //         'products.id as product_type_id',
        //         'products.flexibility_rate',
        //         'products.flexibility_text',
        //         'products.earning_rate',
        //         'products.earning_text',
        //         'products.definition',
        //         'ca.currency',
        //         'ca.fi_id',
        //         'ca.expiry_date as expiry_date_utc',
        //         // Removed timezone conversion here; apply it in the application layer if necessary
        //         'ca.subscription_amount',
        //     ])
        //     ->join('campaigns as ca', 'ca.id', '=', 'campaign_f_i_campaign_products.campaign_id')
        //     ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
        //     ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
        //     ->join('campaign_target_groups', 'campaign_target_groups.campaign_id', '=', 'ca.id')
        //     ->join('f_i_campaign_groups', 'f_i_campaign_groups.id', '=', 'campaign_target_groups.fi_campaign_group_id')
        //     ->leftJoin('f_i_campaign_group_depositors', function ($join) use ($user) {
        //         $join->on('f_i_campaign_group_depositors.fi_campaign_group_id', '=', 'f_i_campaign_groups.id')
        //             ->where('f_i_campaign_group_depositors.depositor_id', '=', $user->organization->id);
        //     })
        //     // Apply groupBy for aggregation
        //     ->groupBy(
        //         'f_i_campaign_products.id',
        //         'campaign_f_i_campaign_products.id',
        //         'campaign_f_i_campaign_products.campaign_id',
        //         'campaign_f_i_campaign_products.fi_campaign_product_id',
        //         'campaign_f_i_campaign_products.rate_type',
        //         'campaign_f_i_campaign_products.index_rate',
        //         'campaign_f_i_campaign_products.status',
        //         'campaign_f_i_campaign_products.spread',
        //         'campaign_f_i_campaign_products.minimum',
        //         'campaign_f_i_campaign_products.maximum',
        //         'campaign_f_i_campaign_products.order_limit',
        //         'campaign_f_i_campaign_products.isFeatured',
        //         'f_i_campaign_products.term_length_type',
        //         'f_i_campaign_products.term_length',
        //         'f_i_campaign_products.compound_frequency',
        //         'f_i_campaign_products.lockout_period',
        //         'f_i_campaign_products.interest_paid',
        //         'f_i_campaign_products.default_product_name',
        //         'f_i_campaign_products.pds',
        //         'products.description',
        //         'products.id',
        //         'products.flexibility_rate',
        //         'products.flexibility_text',
        //         'products.earning_rate',
        //         'products.earning_text',
        //         'products.definition',
        //         'ca.currency',
        //         'ca.fi_id',
        //         'ca.expiry_date',
        //         'ca.subscription_amount'
        //     )
        //     // Apply pagination to handle large datasets
        //     ->paginate(100); // Adjust the page size as needed for performance

        // // Example of how to convert the expiry date to the desired timezone at the application layer:
        // $products->getCollection()->transform(function ($product) use ($timezone) {
        //     $product->expiry_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $product->expiry_date_utc)
        //         ->setTimezone($timezone)
        //         ->toDateTimeString();
        //     return $product;
        // });


        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $searchColumns = [
                'ca.currency',
                'products.description',
                'f_i_campaign_products.term_length',
                'f_i_campaign_products.term_length_type',
                'campaign_f_i_campaign_products.order_limit',
                'campaign_f_i_campaign_products.minimum',
                'campaign_f_i_campaign_products.maximum',
                'campaign_f_i_campaign_products.rate'
            ];
            $products->where(function ($query) use ($searchColumns, $searchTerm) {
                foreach ($searchColumns as $key => $column) {
                    if ($key == 0) {
                        $query->where($column, 'LIKE', '%' . $searchTerm . '%');
                    } else {
                        $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                    }
                }
            });
        }
        //filter by product description
        if ($request->filled("products")) {
            $productsob = explode(",", $request->products);
            $products->whereIn("products.description", $productsob);
        }
        //filter by product description        
        //filter by term length
        if ($request->filled("termLength")) {
            $termlenobject = explode(",", $request->termLength);
            $termtype = $request->termLengthType;

            if (($termlenobject[0] > 0) && ($termlenobject[1] > 0)) {
                $products->where("term_length_type", $termtype);
                $products->whereBetween("f_i_campaign_products.term_length", $termlenobject);
            } else {
                if ($termlenobject[0] > 0) {
                    $products->where("term_length_type", $termtype);
                    $products->where("f_i_campaign_products.term_length", ">=", $termlenobject[0]);
                }
                if ($termlenobject[1] > 0) {
                    $products->where("term_length_type", $termtype);
                    $products->where("f_i_campaign_products.term_length", "<=", $termlenobject[1]);
                }
            }
        }
        //filter by term length
        //filter by rates
        if ($request->filled("rate")) {
            $rateobject = explode(",", $request->rate);
            if (($rateobject[0] > 0) && ($rateobject[1] > 0)) {
                $products->whereBetween("campaign_f_i_campaign_products.rate", $rateobject);
            } else {
                if ($rateobject[0] > 0) {
                    $products->where("campaign_f_i_campaign_products.rate", ">=", $rateobject[0]);
                }
                if ($rateobject[1] > 0) {
                    $products->where("campaign_f_i_campaign_products.rate", "<=", $rateobject[1]);
                }
            }
        }


        if ($request->filled("status")) {
            $products->whereIn('campaign_f_i_campaign_products.status', explode(",", $request->status));
            // return $request->statuses;
        } else {
            $products->where('campaign_f_i_campaign_products.status', 'ACTIVE');
        }

        ///filter by rates
        //filter by lockout period
        if ($request->filled("lockoutPeriod")) {
            $lockoutpobject = explode(",", $request->lockoutPeriod);
            if (($lockoutpobject[0] > 0) && ($lockoutpobject[1] > 0)) {
                $products->whereBetween("f_i_campaign_products.lockout_period", $lockoutpobject);
            } else {
                if ($lockoutpobject[0] > 0) {
                    $products->where("f_i_campaign_products.lockout_period", ">=", $lockoutpobject[0]);
                    $products->whereNotNull("f_i_campaign_products.lockout_period");
                }
                if ($lockoutpobject[1] > 0) {
                    $products->where("f_i_campaign_products.lockout_period", "<=", $lockoutpobject[1]);
                }
            }
        }
        //filter by lockout period
        //filter by organizations
        if ($request->filled("financialOrganizations")) {

            $orgs = explode(",", $request->financialOrganizations);
            $products->join("organizations", "organizations.id", "=", "ca.fi_id");
            $products->whereIn("organizations.name", $orgs);
        }
        //filter by organizations   

        $products->where('ca.status', 'ACTIVE');
        $products->groupBy("ca.fi_id", "campaign_f_i_campaign_products.fi_campaign_product_id");

        if ($request->filled('find_data')) {
            return $products->where('campaign_f_i_campaign_products.id', $request->find_data)->first();
        }

        if ($request->filled("campaign_product_id")) {
            $products->where('campaign_f_i_campaign_products.id', $request->campaign_product_id);
        } else if ($request->filled("related_products") || $request->filled("other_bank_products")) {

            if ($request->filled("other_bank_products")) {
                $other_bank_products = $request->other_bank_products;
                $products->where('ca.fi_id', '=', $request->fi_id);
                $products->where('campaign_f_i_campaign_products.id', '!=', $other_bank_products);
            }
            if ($request->filled("related_products")) {
                $request->request->add(['find_data' => $request->related_products]);
                $product_data = $this->getActiveOffers($request);
                if ($product_data) {
                    $products->where('products.description', 'LIKE', '%' . $product_data->description . '%');
                }
                $products->where('ca.fi_id', '!=', $request->fi_id);
            }
        } else {

            if ($request->filled("isFeatured")) {                // return "filtered";
                $products->where('isFeatured', 1);
                // ->whereNotExists(function ($query) {
                //     $query->select(DB::raw(1))
                //         ->from('campaign_f_i_campaign_products as fcficp')
                //         ->join('f_i_campaign_products as ficp', 'cficp.fi_campaign_product_id', '=', 'ficp.id')
                //         ->join('campaigns as c', 'c.id', '=', 'cficp.campaign_id')
                //         ->whereColumn('cficp.fi_campaign_product_id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
                //         ->whereColumn('cficp.rate', '>', 'campaign_f_i_campaign_products.rate')
                //         ->whereColumn('c.fi_id', '=', 'ca.fi_id')
                //         ;
                // });
                $productsss = $products->orderBy('id', 'desc')
                    ->paginate($request->per_page ? $request->per_page : 10);
                //format        
                $records = CampaignFICampaignProductResource::collection($productsss);
                $pcampaigntoarray = $productsss->toArray();
                $response = [
                    'first_page_url' => $pcampaigntoarray['first_page_url'],
                    'from' => $pcampaigntoarray['from'],
                    'last_page' => $pcampaigntoarray['last_page'],
                    'last_page_url' => $pcampaigntoarray['last_page'],
                    'links' => $pcampaigntoarray['links'],
                    'next_page_url' => $pcampaigntoarray['next_page_url'],
                    'path' => $pcampaigntoarray['path'],
                    'per_page' => $pcampaigntoarray['per_page'],
                    'prev_page_url' => $pcampaigntoarray['prev_page_url'],
                    'to' => $pcampaigntoarray['per_page'],
                    'total' => $pcampaigntoarray['total'],
                    'data' => $records,
                ];
                return $response;
                //format
            } else {
                // $products->where('isFeatured', 0);
                $products->where('isFeatured', 0);
                $products = $products->orderBy('id', 'desc')
                    ->paginate($request->per_page ? $request->per_page : 10);
                //format        
                $records = CampaignFICampaignProductResource::collection($products);
                $pcampaigntoarray = $products->toArray();
                $response = [
                    'first_page_url' => $pcampaigntoarray['first_page_url'],
                    'from' => $pcampaigntoarray['from'],
                    'last_page' => $pcampaigntoarray['last_page'],
                    'last_page_url' => $pcampaigntoarray['last_page'],
                    'links' => $pcampaigntoarray['links'],
                    'next_page_url' => $pcampaigntoarray['next_page_url'],
                    'path' => $pcampaigntoarray['path'],
                    'per_page' => $pcampaigntoarray['per_page'],
                    'prev_page_url' => $pcampaigntoarray['prev_page_url'],
                    'to' => $pcampaigntoarray['per_page'],
                    'total' => $pcampaigntoarray['total'],
                    'data' => $records,
                ];
                return $response;
                //format
            }
        }
        $products = $products->orderBy('id', 'desc')
            ->paginate($request->per_page ? $request->per_page : 10);

        //format        
        $records = CampaignFICampaignProductResource::collection($products);
        $pcampaigntoarray = $products->toArray();
        $response = [
            'first_page_url' => $pcampaigntoarray['first_page_url'],
            'from' => $pcampaigntoarray['from'],
            'last_page' => $pcampaigntoarray['last_page'],
            'last_page_url' => $pcampaigntoarray['last_page'],
            'links' => $pcampaigntoarray['links'],
            'next_page_url' => $pcampaigntoarray['next_page_url'],
            'path' => $pcampaigntoarray['path'],
            'per_page' => $pcampaigntoarray['per_page'],
            'prev_page_url' => $pcampaigntoarray['prev_page_url'],
            'to' => $pcampaigntoarray['per_page'],
            'total' => $pcampaigntoarray['total'],
            'data' => $records,
        ];
        return $response;
        //format
    }

    private function resolveOffersFilter($filters)
    {
        $filters = explode(",", $filters);

        $product_names = Product::all()->pluck('description')->toArray();
        foreach ($filters as $filter) {
            $is_products = in_array($filter, $product_names);
            $term_length_type = in_array($filter, ["MONTHS", "DAYS"]);
            if ($is_products) {
                $filters['product_type_id'] = $filter;
            }
            if ($term_length_type) {
                $filters['term_length_type'] = $filter;
            }
        }
        return $filters;
    }
    public function registerDepositorCampaignView(Request $request)
    {
        $logeduser = $this->getLoggedInUserDetails();
        $viecampaignobject['campaign_id'] = $request->campaign;
        $viecampaignobject['viewer_organization_id'] = $request->organization;
        $viecampaignobject['viewer_user_id'] = $logeduser['user']->id;
        $registeredview = CampaignView::create($viecampaignobject);
    }
    public function registerDepositorCampaignProductView(Request $request)
    {
        $logeduser = $this->getLoggedInUserDetails();
        $campaigproductdetails = CampaignFICampaignProduct::where("id", $request->campaign_product)->first();
        $campaign = Campaign::find($campaigproductdetails->campaign_id);
        // return $logeduser;
        $viecampaignobject['campaign_f_i_campaign_product_id'] = $request->campaign_product;
        $viecampaignobject['viewer_organization_id'] = $request->depositor_org;
        $viecampaignobject['viewer_user_id'] = $logeduser['user_details']->id;
        $registeredview = CampaignProductView::create($viecampaignobject);

        // update campaigns
        $campaign->increment('cumulative_products_no_of_clicks');
        // Increment a specific field    
        return response()->json(['success' => true, "message" => "view registered"]);
    }
}

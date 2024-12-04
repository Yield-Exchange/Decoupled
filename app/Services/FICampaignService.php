<?php

namespace App\Services\Bank;

use App\Constants;
use App\Http\Resources\FICampaignResource;
use App\Models\Campaign;
use App\Models\CampaignFICampaignProduct;
use App\Models\CampaignTargetGroup;
use App\Models\FICampaignGroup;
use App\Models\FICampaignGroupDepositor;
use App\Models\FICampaignProduct;
use App\Models\FICampaignTargetGroup;
use App\Models\Organization;
use Illuminate\Support\Facades\Mail;
use App\Mail\BankMails;
use App\Models\Product;
use App\User;
use DB;
use Illuminate\Http\Request;
use App\Traits\UserTrait;
use DateTime;

class FICampaignService
{
    use UserTrait;
    public function saveCampaign(Request $request, $update = false)
    {
        try {
            DB::beginTransaction();
            $userdetails = $this->getUserFIDetails($request);
            $objecttosave['campaign_name'] = $request->campaignName;
            $objecttosave['expiry_date'] = changeDateFromLocalToUTC($request->expiryDate, Constants::DATE_TIME_FORMAT);
            $objecttosave['start_date'] = changeDateFromLocalToUTC($request->startDate, Constants::DATE_TIME_FORMAT);
            $objecttosave['currency'] = $request->currency;
            $objecttosave['status'] = $request->status;
            $objecttosave['fi_id'] = $userdetails->organization->id;
            $objecttosave['created_by'] = $userdetails->id;
            $objecttosave['subscription_amount'] = $request->subscriptionAmount;
            $campaign = null;
            $response = null;
            if (!$update) {
                $campaign = Campaign::create($objecttosave);

                $groups = $request->groups;
                if ($groups) {
                    foreach ($groups as $group) {
                        FICampaignTargetGroup::insert([
                            'campaign_id' => $campaign->id,
                            'fi_campaign_group_id' => $group,
                        ]);
                    }
                }
                //save target groups
                //save products
                //                $products=json_decode($request->products,true);
                $products = $request->products;
                if ($products) {
                    foreach ($products as $product) {
                        $productobject['campaign_id'] = $campaign->id;
                        $productobject['fi_campaign_product_id'] = is_array($product['product_id']) ? $product['product_id'][0] : $product['product_id']; // must be set
                        $productobject['rate_type'] = isset($product['rate_type']) ? sanitize_amount($product['rate_type']) : 'FIXED';
                        $productobject['index_rate'] = isset($product['index_rate']) ? sanitize_amount($product['index_rate']) : null;
                        $productobject['spread'] = isset($product['spread']) ? sanitize_amount($product['spread']) : null;
                        $productobject['rate'] = isset($product['rate']) ? sanitize_amount($product['rate']) : 0;
                        $productobject['minimum'] = isset($product['minimum']) ? sanitize_amount($product['minimum']) : 0;
                        $productobject['maximum'] = isset($product['maximum']) ? sanitize_amount($product['maximum']) : 0;
                        $productobject['order_limit'] = isset($product['order_limit']) ? sanitize_amount($product['order_limit']) : 0;
                        $productobject['status'] =$request->status;
                        $product = CampaignFICampaignProduct::create($productobject);
                    }
                }
                $campaign = Campaign::where("id", $campaign->id)->with("campaignGroups")->with("campaignProducts")->with("campaignFI")->first();
                $record = new FICampaignResource($campaign);
                $response = ["success" => true, "message" => "Campaign Created successfully.", "data" => $record];
            } else {
                $campaign = Campaign::findOrFail($request->campaign);
                $campaign->update($objecttosave);
                $groups = $request->groups;
                if ($groups) {
                    FICampaignTargetGroup::where('campaign_id', $campaign->id)->whereNotIn('fi_campaign_group_id', $groups)->delete();
                    foreach ($groups as $group) {
                        if (!FICampaignTargetGroup::where([
                            'campaign_id' => $campaign->id,
                            'fi_campaign_group_id' => $group,
                        ])->exists()) {
                            FICampaignTargetGroup::insert([
                                'campaign_id' => $campaign->id,
                                'fi_campaign_group_id' => $group,
                            ]);
                        }
                    }
                }

                $products = $request->products;
                if ($products) {
                    $product_ids_present = [];
                    foreach ($products as $product) {
                        array_push($product_ids_present, $product['product_id']);
                        $productobject['campaign_id'] = $campaign->id;
                        $productobject['fi_campaign_product_id'] = is_array($product['product_id']) ? $product['product_id'][0] : $product['product_id']; // must be set
                        $productobject['rate_type'] = isset($product['rate_type']) ? sanitize_amount($product['rate_type']) : 'FIXED';
                        $productobject['index_rate'] = isset($product['index_rate']) ? sanitize_amount($product['index_rate']) : null;
                        $productobject['spread'] = isset($product['spread']) ? sanitize_amount($product['spread']) : null;
                        $productobject['rate'] = isset($product['rate']) ? sanitize_amount($product['rate']) : 0;
                        $productobject['minimum'] = isset($product['minimum']) ? sanitize_amount($product['minimum']) : 0;
                        $productobject['maximum'] = isset($product['maximum']) ? sanitize_amount($product['maximum']) : 0;
                        $productobject['order_limit'] = isset($product['order_limit']) ? sanitize_amount($product['order_limit']) : 0;

                        $product = CampaignFICampaignProduct::where('campaign_id', $campaign->id)->where('fi_campaign_product_id', $product['product_id'])->first();
                        if ($product) {
                            $product->update($productobject);
                        } else {
                            CampaignFICampaignProduct::create($productobject);
                        }
                    }

                    try {
                        CampaignFICampaignProduct::whereIn('campaign_id', [$campaign->id])->whereNotIn('fi_campaign_product_id', $product_ids_present)->forceDelete();
                    } catch (\Exception $e) {
                    }

                 
                }
                $campaignsdata = Campaign::where("id", $request->campaign)->with("campaignGroups")->with("campaignProducts")->with("campaignFI")->first();
                //send the campaign edit emai update                  
                   Mail::to($userdetails->organization->notifiableUsersEmails())->queue(new BankMails([
                       'campaign' => $campaignsdata,
                       'subject' => "Campaign Updated Successfully"
                   ],'campaign_edited'));
                //send the campaign edit emai update
                $record = new FICampaignResource($campaign);
                $response = ["success" => true, "message" => "Campaign Updated successfully.", "data" => $record];
            } //save target groups

            //save products
            //  return $campaign;
            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),
                'error9' => $exp->getTraceAsString(),
            ], 400);
        }
    }
    public function deactivateCampaign(Request $request)
    {
        try {
            DB::beginTransaction();
            $campaign_to_delete = Campaign::find($request->campaign);
            $action = "INACTIVE"; //($request->action=="ACTIVATE") ? "ACTIVE" :
            $campaign_to_delete->update(['status' => $action]);
            $response = ["success" => true, "message" => "Campaign deactivated successfully."]; //($request->action=="ACTIVATE") ? "Campaign activated successfully." :
            DB::commit();
            return response()->json($response, 200);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function featureFICampaignProduct(Request $request)
    {
        try {
            DB::beginTransaction();
            $requestq = $request;
            $requestq['isFeatured'] = 1;
            $has_featured = $this->getMyCampaignProducts($requestq, 1);

            if (!empty($has_featured)) {
                $ids = collect($has_featured)->pluck('campaignproductid')->toArray();
                CampaignFICampaignProduct::whereIn('id', $ids)->update(['isFeatured' => 0]);
            }

            $campaign_to_delete = CampaignFICampaignProduct::where("id", $request->product)->first();
            $campaign_id = null;
            if ($campaign_to_delete) {
                $campaign_id = $campaign_to_delete->campaign_id;
            }
            $action = ($request->action == "FEATURE") ? 1 : 0;
            $campaign_to_delete->update(['isFeatured' => $action]);
            $response = ["success" => true, "message" => ($request->action == "FEATURE") ? "Product featured successfully." : "Product un featured successfully.", 'campaign_id' => $campaign_id];

            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function getMyActiveCampaignProducts($camp)
    {
        return Campaign::join("campaign_f_i_campaign_products", "campaign_f_i_campaign_products.campaign_id", "=", "campaigns.id")
            ->join("f_i_campaign_products", "f_i_campaign_products.id", "=", "campaign_f_i_campaign_products.fi_campaign_product_id")
            ->where("campaigns.id", $camp)
            ->whereNULL("f_i_campaign_products.deleted_at")->get("campaign_f_i_campaign_products.*");
    }

    public function getMyCampaigns(Request $request)
    {
        $userdetails = $this->getUserFIDetails($request);
        $campaign = Campaign::where("fi_id", $userdetails->organization->id)
            ->with(["campaignGroups", "campaignProducts", "campaignFI"]);

        if ($request->filled("subscription")) {
            $subobject = explode(",", $request->subscription);
            if (($subobject[0] > 0) && ($subobject[1] > 0)) {
                $campaign->whereBetween("subscription_amount", $subobject);
            } else {
                if ($subobject[0] > 0) {
                    $campaign->where("subscription_amount", ">=", $subobject[0]);
                }
                if ($subobject[1] > 0) {
                    $campaign->where("subscription_amount", "<=", $subobject[1]);
                }
            }
        }

        if ($request->filled("expiry")) {

            $expiryobject = explode(",", $request->expiry);
            if ((stripslashes($expiryobject[0]) != "") && (stripslashes($expiryobject[1]) != "")) {
                
                $dateString1 = $expiryobject[0];
                $dateString2 = $expiryobject[1];
                $mindate = new DateTime($dateString1);
                $maxdate = new DateTime($dateString2);
                $campaign->whereBetween(DB::raw('DATE(expiry_date)'), [changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT), changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT)]);
            } else {
                if (stripslashes($expiryobject[0]) != "") {
                    $dateString1 = $expiryobject[0];
                    $mindate = new DateTime($dateString1);
                    $campaign->where(DB::raw('DATE(expiry_date)'), '>=', changeDateFromLocalToUTC($mindate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
                if (stripslashes($expiryobject[1]) != "") {
                    $dateString2 = $expiryobject[1];
                    $maxdate = new DateTime($dateString2);
                    $campaign->where(DB::raw('DATE(expiry_date)'), '<=', changeDateFromLocalToUTC($maxdate->format('Y-m-d'), Constants::DATE_TIME_FORMAT));
                }
            }
        }

        if ($request->filled("clicks")) {
            $clicksobject = explode(",", $request->clicks);

            if (($clicksobject[0] > 0) && ($clicksobject[1] > 0)) {

                $campaign->whereBetween("cumulative_products_no_of_clicks", $clicksobject);
            } else {
                if ($clicksobject[0] > 0) {
                    $campaign->where("cumulative_products_no_of_clicks", ">=", $clicksobject[0]);
                }
                if ($clicksobject[1] > 0) {
                    $campaign->where("cumulative_products_no_of_clicks", "<=", $clicksobject[1]);
                }
            }
        }




        if ($request->filled("termLength")) {
            $termlenobject = explode(",", $request->termLength);
            $termtype = $request->termLengthType;
            if (($termlenobject[0] > 0) && ($termlenobject[1] > 0)) {
                $campaign->whereHas('campaignProducts.product', function ($query) use ($termlenobject, $termtype) {

                    $query->where("term_length_type", $termtype);
                    $query->whereBetween("term_length", $termlenobject);
                });
            } else {

                if ($termlenobject[0] > 0) {

                    $campaign->whereHas('campaignProducts.product', function ($query) use ($termlenobject, $termtype) {
                        $query->where("term_length_type", $termtype);
                        $query->where("term_length", ">=", $termlenobject[0]);
                    });
                }

                if ($termlenobject[1] > 0) {

                    $campaign->whereHas('campaignProducts.product', function ($query) use ($termlenobject, $termtype) {
                        $query->where("term_length_type", $termtype);
                        $query->where("term_length", "<=", $termlenobject[0]);
                    });
                }
            }
        }




        if ($request->filled("product_types")) {
            $product_types = explode(",", $request->product_types);
            $campaign->whereHas('campaignProducts.product', function ($query) use ($product_types) {
                $query->whereHas('productType', function ($query2) use ($product_types) {
                    $query2->whereIn("product_type_id", $product_types);
                });
            });
        }



        // url += '&clicks=100,1000';
        // url += '&subscription=5000,100000';
        // url += '&product_types=1,3,5';
        // url += '&provinces=Alberta,Manitoba,british columbia';
        // url += '&termlength=12,15';
        // url += '&expiry=2023-11-06,2023-11-07';

        if ($request->filled('campaign_id')) {
            $thiscamp = Campaign::where("fi_id", $userdetails->organization->id)->where("id", $request->campaign_id)->with(["campaignFI", "campaignGroups"])->first();
            $thiscamp['campaign_products'] = $this->getMyActiveCampaignProducts($request->campaign_id);
            return response()->json([
                "success" => true,
                'message' => 'Campaign fetched successfully',
                'data' => $thiscamp,
            ], 200);
        }

        if ($request->filled('status')) {
            if ($request->status == "history") {
                $campaign->whereNotIn('status', ['ACTIVE', 'DRAFT', 'SCHEDULED']);
            } else {
                $campaign->whereIn('status', explode(",", $request->status));
            }
        }




        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $searchColumns = ['campaign_name', 'expiry_date', 'start_date', 'currency', 'subscription_amount'];
            foreach ($searchColumns as $key => $column) {
                if ($key == 0) {
                    $campaign->where($column, 'LIKE', '%' . $searchTerm . '%');
                } else {
                    $campaign->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                }
            }
        }

        //filter logic 

        //filter logic


        // $filters = $request->input("filters");
        // if ($filters) {
        //     $filters = $this->resolveCampaignFilter($filters);
        //     foreach ($filters as $key => $filter) {
        //         if ($key == "product_type_id" || $key == "term_length_type") {
        //             $campaign->whereHas('campaignProducts.product', function ($query) use ($filter, $key) {
        //                 if ($key == "product_type_id") {
        //                     $query->whereHas('productType', function ($query) use ($filter) {
        //                         $query->where('description', 'LIKE', '%' . $filter . '%');
        //                     });
        //                 }
        //                 if ($key == "term_length_type") {
        //                     $query->where('term_length_type', 'LIKE', '%' . $filter . '%');
        //                 }
        //             });
        //         }
        //     }
        // }

        $campaign = $campaign->paginate($request->per_page ? $request->per_page : 10);
        $records = FICampaignResource::collection($campaign);
        $campaigntoarray = $campaign->toArray();
        $response = [
            'first_page_url' => $campaigntoarray['first_page_url'],
            'from' => $campaigntoarray['from'],
            'last_page' => $campaigntoarray['last_page'],
            'last_page_url' => $campaigntoarray['last_page'],
            'links' => $campaigntoarray['links'],
            'next_page_url' => $campaigntoarray['next_page_url'],
            'path' => $campaigntoarray['path'],
            'per_page' => $campaigntoarray['per_page'],
            'prev_page_url' => $campaigntoarray['prev_page_url'],
            'to' => $campaigntoarray['per_page'],
            'total' => $campaigntoarray['total'],
            'data' => $records,
        ];
        return $response;
    }
    public function getMyCampaignProducts(Request $request, $hasFeatured = 0)
    {
        $user = auth()->user();
        $products = CampaignFICampaignProduct::query()
            ->select([
                'campaign_f_i_campaign_products.id as campaignproductid',
                'campaign_f_i_campaign_products.*',
                'campaigns.campaign_name',
                'campaign_f_i_campaign_products.rate',
                'campaign_f_i_campaign_products.minimum',
                'campaign_f_i_campaign_products.maximum',
                'campaign_f_i_campaign_products.order_limit',
                'campaign_f_i_campaign_products.isFeatured',
                'f_i_campaign_products.term_length_type',
                'f_i_campaign_products.term_length',
                'f_i_campaign_products.compound_frequency',
                'f_i_campaign_products.lockout_period',
                'f_i_campaign_products.interest_paid',
                'f_i_campaign_products.default_product_name',
                'f_i_campaign_products.custom_product_name',
                'f_i_campaign_products.pds',
                'products.description',
                'products.definition',
                'products.flexibility_rate',
                'products.flexibility_text',
                'products.earning_rate',
                'products.earning_text',
                'campaigns.currency',
                'campaigns.expiry_date',
                'campaigns.subscription_amount',
            ])
            ->join('campaigns', 'campaigns.id', '=', 'campaign_f_i_campaign_products.campaign_id')
            ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'campaign_f_i_campaign_products.fi_campaign_product_id')
            ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
            ->whereNull('f_i_campaign_products.deleted_at');
        if ($request->filled("isFeatured")) {
            $products->where('isFeatured', 1);
        }
        if ($hasFeatured == 1) {
            $products->where('campaigns.fi_id', $user->organization->id)->where('campaigns.status', 'ACTIVE');
            $products->where('isFeatured', 1);
            return $products->get();
        }

        if ($request->filled("campaign_product_id")) {
            $products->where('campaign_f_i_campaign_products.id', $request->campaign_product_id);
        }

        // $searchTerm = $request->input('search');
        // if ($request->filled("search")) {
        //     $searchColumns = ['campaigns.currency', 'products.description', 'f_i_campaign_products.term_length', 'f_i_campaign_products.term_length_type', 'campaign_f_i_campaign_products.order_limit', 'campaign_f_i_campaign_products.minimum', 'campaign_f_i_campaign_products.maximum', 'campaign_f_i_campaign_products.rate'];

        //     $products->where(function ($query) use ($searchColumns, $searchTerm) {
        //         foreach ($searchColumns as $key => $column) {
        //             if ($key == 0) {
        //                 $query->where($column, 'LIKE', '%' . $searchTerm . '%');
        //             } else {
        //                 $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
        //             }
        //         }
        //     });
        // }
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

        $products->where('campaigns.fi_id', $user->organization->id);

        if($request->filled("status")){
            $products->whereIn('campaign_f_i_campaign_products.status',explode(",",$request->status));            
            // return $request->statuses;
        }else{
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
        if ($request->filled("status")) {
            $explodedStatus = explode(",", $request->status);
            // return $explodedStatus;
            $products->where('campaigns.fi_id', $user->organization->id)->whereIn('f_i_campaign_products.status', $explodedStatus);
        } else {
            $products->where('campaigns.fi_id', $user->organization->id)->where('campaigns.status', 'ACTIVE');
        }
       
        $products = $products->with("campaign")->orderBy('id', 'desc')
            ->paginate($request->per_page ? $request->per_page : 10);
        return $products;
    }
    public function checkExistanceOfFIProduct($fi, $product, $request, $update)
    {
        if ($update) {
            if (FICampaignProduct::where("fi_id", $fi)->where("default_product_name", $product)->whereNotIn("id", [$request->product])->with("productType")->exists()) {
                return true;
            }
            return false;
        } else {
            if (FICampaignProduct::where("fi_id", $fi)->where("default_product_name", $product)->with("productType")->exists()) {
                return true;
            }
            return false;
        }
    }
    public function saveFICampaignProduct(Request $request, $pds_file, $update)
    {

        try {
            DB::beginTransaction();
            $userdetails = $this->getUserFIDetails($request);
            if ($this->checkExistanceOfFIProduct($userdetails->organization->id, $request->defaultProductName, $request, $update)) {

                $response = ["success" => false, "message" => "Product already Exists."];
                return response()->json($response);
            }
            $productobject['product_type_id'] = $request->productType;
            $productobject['term_length_type'] = $request->termLengthType;
            $productobject['term_length'] = $request->termLength;
            if (isset($request->lockoutPeriod)) {
                $productobject['lockout_period'] = $request->lockoutPeriod;
            }
            $productobject['fi_id'] = $userdetails->organization->id;
            $productobject['compound_frequency'] = $request->compoundFrequency;
            $productobject['interest_paid'] = $request->interestPaid;
            $productobject['created_by'] = $userdetails->id;
            $productobject['default_product_name'] = $request->defaultProductName;
            $productobject['custom_product_name'] = ($request->customProductName == "") ? $request->defaultProductName : $request->customProductName;

            if (!empty($pds_file)) {
                $productobject['pds'] = $pds_file;
            }

            $productrecord = null;
            $response = null;
            if ($update) {
                $producttoupdate = FICampaignProduct::findOrFail($request->product);
                $producttoupdate->update($productobject);
                $productrecord = FICampaignProduct::where("id", $request->product)->with("productType")->first();
                $response = ["success" => true, "message" => "Product updated successfully.", "data" => $productrecord];
            } else {
                $product = FICampaignProduct::create($productobject);
                $productrecord = FICampaignProduct::where("id", $product->id)->with("productType")->first();
                $response = ["success" => true, "message" => "Product Created successfully.", "data" => $productrecord];
            }

            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage() . 'req' . json_encode($request->all()),

            ], 400);
        }
    }
    public function deleteFIProduct(Request $request)
    {
        $loggeduser = 0;
        if (isset($request->user)) {
            $loggeduser = $request->user;
        }
        try {
            DB::beginTransaction();
            $userdetails = $this->getUserFIDetails($request);
            $product_to_delete = FICampaignProduct::findOrFail($request->product);
            $product_to_delete->delete();
            $response = ["success" => true, "message" => "Product deleted successfully."];
            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function activateDeactivateFIProduct(Request $request)
    {
        $userdetails = $this->getUserFIDetails($request);
        try {
            DB::beginTransaction();
            $product_to_delete = FICampaignProduct::find($request->product);
            $action = ($request->action == "ACTIVATE") ? "ACTIVE" : "INACTIVE";
            $product_to_delete->update(['status' => $action]);
            $response = ["success" => true, "message" => ($request->action == "ACTIVATE") ? "Product activated successfully." : "Product deactivated successfully."];
            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function getUserFIDetails(Request $request)
    {
        //        if(isset($request->user) && !app()->environment('production')){ // to be removed
        //            $user = User::find($request->user);
        //        }else{
        $user = \auth()->user();
        //        }

        return $user;
    }

    public function getDepositorDetails($user)
    {
    }
    public function saveGroup(Request $request, $update = false)
    {
        //assign group members

        try {
            DB::beginTransaction();
            $userdetails = $this->getUserFIDetails($request);
            $groupobject['group_name'] = $request->groupName;
            $response = null;
            if (!$update) {
                if (FICampaignGroup::where("group_name", $request->groupName)->where('fi_id', $userdetails->organization->id)->exists()) {
                    return response()->json(["success" => false, "message" => "Group Name already exists.", "data" => []], 200);
                }
                $groupobject['fi_id'] = $userdetails->organization->id;
                $groupobject['created_by'] = $userdetails->id;
                $group = FICampaignGroup::create($groupobject);
                //create group
                //assign group members
                $submittedmembers = $request->depositors;
                if ($submittedmembers) {
                    foreach ($submittedmembers as $submittedmember) {
                        $depositor = Organization::where("id", $submittedmember)->first();
                        $group->depositors()->attach($depositor);
                    }
                }
                $record = FICampaignGroup::where("id", $group->id)->with("depositors")->get();
                $response = ["success" => true, "message" => "Group Created successfully.", "data" => $record];
            } else {
                if (FICampaignGroup::where("group_name", $request->groupName)->where('fi_id', $userdetails->organization->id)->where("id", "<>", $request->group)->exists()) {
                    return response()->json(["success" => false, "message" => "Group Name already exists.", "data" => []], 200);
                }
                $grouprecord = FICampaignGroup::findOrFail($request->group);
                $grouprecord->update($groupobject);

                $record = FICampaignGroup::where("id", $request->group)->with("depositors")->get();
                $response = ["success" => true, "message" => "Group Updated successfully.", "data" => $record];
            }

            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function deleteGroup(Request $request)
    {
        try {
            DB::beginTransaction();
            //            $userdetails = $this->getUserFIDetails($request);
            $group_to_delete = FICampaignGroup::findOrFail($request->group);

            $group_has_campaign = CampaignTargetGroup::where('fi_campaign_group_id', $request->group)->first();
            if ($group_has_campaign) {
                $campaign = Campaign::find($group_has_campaign->campaign_id);
                if ($campaign && in_array($campaign->status, ['ACTIVE', 'EXPIRED', 'SCHEDULED', 'DRAFT'])) {
                    $group_to_delete->update(['deletion_status'=>1]); 
                    $response = [
                        "success" => true,
                        "message" => "You have deactivated a group which is linked to a campaign.."
                    ];
                
                    return response()->json($response, 200);
                }else{
                    $group_to_delete->delete();
                }
                
            }else{
                $group_to_delete->delete();
            }          
            $response = ["success" => true, "message" => "Group deleted successfully."];
            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function removeGroup(Request $request)
    {
        //create group
        //assign group members
        try {
            DB::beginTransaction();
            $loggeduser = 0;
            if (isset($request->user)) {
                $loggeduser = $request->user;
            }
            $userdetails = $this->getUserFIDetails($loggeduser);
            $groupobject['group_name'] = $request->groupName;
            $groupobject['fi_id'] = $userdetails->Organizations[0]["id"];
            $groupobject['created_by'] = $loggeduser;
            $group = FICampaignGroup::create($groupobject);
            //create group
            //assign group members
            $submittedmembers = $request->depositors;
            foreach ($submittedmembers as $submittedmember) {
                $depositor = Organization::where("id", $submittedmember)->first();
                $group->depositors()->attach($depositor);
            }
            $record = FICampaignGroup::where("id", $group->id)->with("depositors")->get();
            $response = ["success" => true, "message" => "Group Created successfully.", "data" => $record];
            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function addGroupDepositor(Request $request)
    {
        try {
            DB::beginTransaction();
            $group = FICampaignGroup::where("id", $request->group)->first();
            if($group->fi_id==0){
                $response = ["success" => true, "message" => "Access denied."];
            }else{
                           //create group
            //assign group members
            $depositors = $request->depositors;

            foreach ($depositors as $depositor) {
                $exist = FICampaignGroupDepositor::where("fi_campaign_group_id", $request->group)->where("depositor_id", $depositor)->exists();
                if (!$exist) {
                    $thisdepositor = Organization::where("id", $depositor)->first();
                    $group->depositors()->attach($thisdepositor);
                }
            }

            //assign member
            $recordupdated = FICampaignGroup::where("id", $request->group)->with("depositors")->get();
            $response = ["success" => true, "message" => "Group Created successfully.", "data" => $recordupdated];
            DB::commit();
            return response()->json($response, 201); 
            }

        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function removeGroupDepositor(Request $request)
    {
        try {
            DB::beginTransaction();

            $groupobject = FICampaignGroup::findOrFail($request->group);
            $depositors = $request->depositors;
            foreach ($depositors as $depositor) {
                $depositorr = Organization::where("id", $depositor)->first();
                $groupobject->depositors()->wherePivot('depositor_id', $depositorr->id)->detach();
            }
            //create group
            //assign member
            $recordupdated = FICampaignGroup::where("id", $request->group)->with("depositors")->get();
            $response = ["success" => true, "message" => "Depositor removed successfully.", "data" => $recordupdated];
            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }

    public function getMyGroups(Request $request)
    {
        $userdetails = $this->getUserFIDetails($request);
        $groups = FICampaignGroup::with("depositors");
        $groups->orderBy('created_at', 'desc')->where("fi_id", $userdetails->organization->id);
        if ($request->filled("search")) {
            $groups->where("group_name", 'LIKE', '%' . $request->search . '%');
        }
       
        return $groups->get();
    }
    public function getMyGroup($group)
    {
        
        $groups = FICampaignGroup::with("depositors")->whereIn('id',$group);
        $groups->orderBy('created_at', 'desc');       
        return $groups->get();
    }
    public function getMyProducts(Request $request)
    {
        $userdetails = $this->getUserFIDetails($request);
        $products = FICampaignProduct::where("fi_id", $userdetails->organization->id)->with("productType");

        if ($request->filled('product_ids')) {
            $products->whereIn('id', explode(",", $request->product_ids))->whereNull('deleted_at');
        }

        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $searchColumns = ['term_length_type', 'term_length', 'lockout_period', 'default_product_name', 'pds', 'compound_frequency', 'interest_paid', 'product_type_id'];
            foreach ($searchColumns as $key => $column) {
                if ($key == 0) {
                    $products->where($column, 'LIKE', '%' . $searchTerm . '%');
                } else {
                    $products->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                }

                if ($column == "product_type_id") {
                    $products->orWhereHas('productType', function ($query) use ($searchTerm) {
                        $query->where('description', 'LIKE', '%' . $searchTerm . '%');
                    });
                }
            }
        }

        //filter by product description
        if ($request->filled("products")) {
            $productsob = explode(",", $request->products);
            $products->join("products", "products.id", "=", "f_i_campaign_products.product_type_id");
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
                    $products->whereNotNull("f_i_campaign_products.lockout_period");
                }
            }
        }
        //filter by lockout period


        return $products->orderBy('f_i_campaign_products.created_at', 'desc')->paginate($request->per_page ? $request->per_page : 10);
    }

    private function resolveProductFilter($filters)
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

    private function resolveCampaignFilter($filters)
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

    public function deleteMyCampaign(Request $request)
    {
        $user = auth()->user();
        try {
            DB::beginTransaction();
            $campaign = Campaign::when($request->campaign != 'all', function ($query) use ($request) {
                $query->where('id', $request->campaign);
            })->where('status', 'DRAFT')->where('fi_id', $user->organization->id);

            if (!$campaign->clone()->exists()) {
                throw new \Exception("Campaign not found");
            }

            $campaign->delete();
            $response = ["success" => true, "message" => "Campaign deleted successfully."];
            DB::commit();
            return response()->json($response, 200);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }

    public function deactivateMyCampaign(Request $request)
    {
        $user = auth()->user();
        try {
            DB::beginTransaction();
            $campaign = Campaign::where('id', $request->campaign)->where('fi_id', $user->organization->id);

            if (!$campaign->clone()->exists()) {
                throw new \Exception("Campaign not found");
            }

            $campaign->update(['status' => 'INACTIVE']);
            $response = ["success" => true, "message" => "Campaign deactivated successfully."];
            DB::commit();
            return response()->json($response, 200);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }

    public function removeProductMyCampaign(Request $request)
    {
        try {
            DB::beginTransaction();
            $campaign = CampaignFICampaignProduct::where('id', $request->campaign_fi_campaign_product_id);

            if (!$campaign->clone()->exists()) {
                throw new \Exception("Campaign Product not found");
            }

            $campaign->delete();
            $response = ["success" => true, "message" => "Campaign product removed successfully."];
            DB::commit();
            return response()->json($response, 201);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Request has not been successfully processed.' . $exp->getMessage(),

            ], 400);
        }
    }
    public function getGroupUnlinkedDepositors(Request $request)
    {
        // get group already linked depositors IDs
        $unlinkeddepositors = Organization::query();
        if ($request->filled("industries")) {
            $industries = explode(",", $request->industries);
            $unlinkeddepositors->whereIn("industry_id", $industries);
        }
        if ($request->filled("search")) {

            $unlinkeddepositors->where("organizations.name", "like", "%" . $request->search . "%");
        }
        if ($request->filled("provinces")) {
            $provinces = explode(",", $request->provinces);
            $unlinkeddepositors->join("demographic_organization_data", "demographic_organization_data.organization_id", "=", "organizations.id");
            $unlinkeddepositors->whereIn("province", $provinces);
        }
        $depositors = [];
        $depositorIds = [];
        if ($request->filled("grouping")) {
            $groupings = explode(",", $request->grouping);
            if (in_array("All", $groupings) || (in_array("Grouped Organizations", $groupings) && in_array("Ungrouped Organizations", $groupings))) {
            } else if ((sizeof($groupings) == 1) && ($groupings[0] == "Grouped Organizations")) {
                $userdetails = $this->getLoggedInUserDetails();
                $mygroups = FICampaignGroup::where("fi_id", $userdetails['user_details']->organization->id)->pluck("f_i_campaign_groups.id");
                $depositorIds = FICampaignGroupDepositor::whereIn("fi_campaign_group_id", $mygroups)->pluck("depositor_id");
                if ($depositorIds != null) {
                    $unlinkeddepositors->whereIn("organizations.id", $depositorIds);
                }
            } else if ((sizeof($groupings) == 1) && ($groupings[0] == "Ungrouped Organizations")) {
                $userdetails = $this->getLoggedInUserDetails();
                $mygroups = FICampaignGroup::where("fi_id", $userdetails['user_details']->organization->id)->pluck("id");
                $depositorIds = FICampaignGroupDepositor::whereIn("fi_campaign_group_id", $mygroups)->pluck("depositor_id");
                if ($depositorIds != null) {
                    $unlinkeddepositors->whereNotIn("organizations.id", $depositorIds);
                }
                // return "Ungrouped Organizations";

            }
        } else {
            if ($request->filled("groups")) {
                $depositors = FICampaignGroupDepositor::whereIn("fi_campaign_group_id", $request->groups)->get();
                foreach ($depositors as $item) {
                    $depositorIds[] = $item['depositor_id'];
                }
                if ($depositorIds != null) {
                    $unlinkeddepositors->whereNotIn("organizations.id", $depositorIds);
                }
            }
        }



        $unlinkeddepositors->where(
            'organizations.type',
            'DEPOSITOR'
        )->where('is_test', \auth()->user()->is_test);


        return $unlinkeddepositors->get();
    }
    public function updateCampaignProductInfo($request)
    {
        try {
            if ($request->minimum > $request->maximum) {
                return response()->json([
                    "success" => false,
                    'message' => 'Failed.Minimum amount cannot be more than the maximum amount',

                ], 200);
            }
            DB::beginTransaction();
            $productselected = CampaignFICampaignProduct::find($request->product);
            if ($productselected) {

                $productselected->update([
                    'rate' => sanitize_amount($request->rate),
                    'minimum' => sanitize_amount($request->minimum),
                    'maximum' => sanitize_amount($request->maximum),
                ]);
                DB::commit();
                $response = ["success" => true, "message" => "Product information updated.", "Newdetails" => CampaignFICampaignProduct::where('id', $request->product)->first()];
                return $response;
            }
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => 'Failed.' . $exp->getMessage(),

            ], 400);
        }
    }
}

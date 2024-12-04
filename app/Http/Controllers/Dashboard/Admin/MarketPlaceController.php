<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MarketPlaceOfferImport;
use App\Mail\AdminMail;
use App\Mail\Bank\MarketPlaceOfferCreated;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Campaign;
use App\Models\Organization;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class MarketPlaceController extends Controller
{

    public $active_bank_ids;
    public $expired_bank_ids;
    public $organizationBank;

    public function __construct() {
        $this->active_bank_ids = Campaign::where('status','ACTIVE')->pluck('organization_id')->toArray();
        $this->expired_bank_ids = Campaign::where('status','EXPIRED')->pluck('organization_id')->toArray();
        $this->organizationBank = Organization::where('type','BANK')->orderBy('name', 'asc');
        $this->expired_bank_ids = Campaign::where('status','EXPIRED')->pluck('organization_id')->toArray();
        $this->organizationBank = Organization::where('type','BANK')->orderBy('name', 'asc');
    }

    public function index() {
        $products = json_encode(Product::select('id', 'description')->get()->toArray()); 
        $allBamksList = $this->organizationBank->get()->toArray();
        array_unshift($allBamksList,["id"=>"all_banks","name"=>"All Banks"]);
        $allBamks = json_encode($allBamksList);
        $banksWithOffers = $this->addmarketplace()->where('status','ACTIVE')->whereIn('id', $this->active_bank_ids)->get();
        return view('dashboard.admin.marketplace', compact('products', 'banksWithOffers', 'allBamks'));
    }

    public function filterByBank() {
        if(request('bank_id') == 'all_banks') {
            $banks = $this->addmarketplace()->get();
        } else {
            $banks = $this->addmarketplace()->where('id',request('bank_id'))->get();
        }
        return response()->json(["bank" => $banks ]);
    }

    public function filterByStatus() {
        $bank = $this->addmarketplace()->whereNotIn('id', $this->active_bank_ids)->get();
        $bank = $this->addmarketplace()->whereNotIn('id', $this->active_bank_ids)->get();
        return response()->json(["bank" => $bank ]);
    }
    public function filter() {
        
    }

    private function addmarketplace() {
        return $this->organizationBank->with('marketPlaceOffer')->withCount('marketPlaceOffer');
    }

    public function importOffers() {

        request()->validate([
            'file' => 'required|file|mimes:csv,xlsx',
            'organizationId' => 'required',
            'userId' => 'required',
        ]);


        $importer = new MarketPlaceOfferImport;
        Excel::import($importer, request()->file('file'));

        $offerList = $importer->getData();
        $headings = $importer->getHeader();
        $user = User::where('id', request('userId'))->first();

  

        try {
            DB::beginTransaction();

            $old_offers =  Campaign::where('organization_id', request('organizationId'))
            ->where("status", "ACTIVE")
            ->orderBy('id', 'DESC')
            ->where('is_featured', false)
            ->get();
            if(count($old_offers) > 0) {
                foreach($old_offers as $old_offer) {
                    $old_offer->status = "INACTIVE";
                    $old_offer->save();

                    systemActivities(Auth::id(), json_encode(request()->query()), "Deleted a market place offer");
                    archiveTable($old_offer->id, $old_offer->getTable(), auth()->id(), "DELETED");
                    $old_offer->delete();
                }
            }
            foreach ($offerList as $offer) {


                // Remove Featured Product 
                if($offer[7] == "Yes") {

                    $featuredOffer = Campaign::where('is_featured', true)
                            // ->where('created_by', request('userId'))
                            ->where('organization_id', request('organizationId'))
                            ->where("status", "ACTIVE")
                            ->orderBy('id', 'DESC')
                            ->first();

                        if ($featuredOffer) {
                            $featuredOffer->is_featured = false;
                            $featuredOffer->status = "INACTIVE";
                            $featuredOffer->save();
                            systemActivities(Auth::id(), json_encode(request()->query()), "Deleted a market place offer");
                            archiveTable($featuredOffer->id, $featuredOffer->getTable(), auth()->id(), "DELETED");
                            $featuredOffer->delete();
                        }

                }
                

                $data =   [
                    "rate_held_until" => $offer[6] ? $offer[6] : throw new Exception("Rate held Until is Required for all Offers"),
                    "term_length_type" => "MONTHS",
                    "term_length" => $offer[2] ? $offer[2] : throw new Exception("Term length is Required for all Offers"),
                    "product_id" => $offer[0] ? get_product_id_from_description($offer[0]) : throw new Exception("Maximun Amount is Required for all Offers"),
                    "lockout_period" => $offer[1],
                    "currency" => "CAD",
                    "minimum_amount" => $offer[3] ? $offer[3] : throw new Exception("Minimum Amount is Required for all Offers"),
                    "maximum_amount" => $offer[4] ? $offer[4] : throw new Exception("Maximun Amount is Required for all Offers"),
                    "interest_rate" => $offer[8]? $offer[8] : throw new Exception("Interest Rate is Required for all Offers"),
                    "is_featured" => $offer[7] == "Yes" ? true : false,
                    "compound_frequency" => "At maturity",
                    "interest_paid" => "At maturity",
                    "expireOffer" => "false",
                    "reference_no" => generateMarketPlaceOfferReference(),
                    "created_by" => request('userId'),
                    "organization_id" => request('organizationId'),
                    "status" => "ACTIVE",
                    "cumulative_total" => $offer[5],
                ];
                $marketOffer = Campaign::create($data);
                systemActivities(Auth::id(), json_encode(request()->query()), "Admin Create Market Place Offer For Bank");
                $message="Market place offer ref no ".$marketOffer->reference_no.' has been created.';
                Mail::to($user->organization->notifiableUsersEmails())->queue(new MarketPlaceOfferCreated([
                    'message' => $message,
                    'subject' =>"New market place offer.",
                    'header' =>"Your market place offer has been created",
                    'user_type' =>"Bank"
                ]));

                $notification = $message;
                Mail::to(getAdminEmails())->queue(new AdminMail([
                    'subject' => "New market place offer.",
                    'message' => $user->organization->name." has created a new market place offer, Ref: " . $marketOffer->reference_no,
                ]));

                notify([
                    'type' => 'MARKET PLACE OFFER CREATED',
                    'details' => $notification,
                    'date_sent' => getUTCDateNow(),
                    'status' => 'ACTIVE',
                    'organization_id'=>$user->organization->id
                ]);
            
            }
            
            DB::commit();
            return response()->json(['status' => true, 'message' => "Offer Updated Successfully"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Failed to update market offer rates',
                "data" => [$th->getMessage()]
            ], 400);

        }
    }
}

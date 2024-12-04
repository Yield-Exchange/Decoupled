<?php
namespace App\Http\Controllers\Dashboard;

use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Mail\CounterOfferApprovalMail;
use App\Models\CounterOffer;
use App\Models\Offer;
use App\Models\Organization;
use App\Services\Bank\Offer\OfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CounterOfferController extends Controller{

    private $offer_service;
    public function __construct(OfferService $offer_service)
    {
        $this->middleware('auth');
        $this->offer_service = $offer_service;
    }

    public function campaignOffers(Request $request, $campaign_product_id){
        $user = Auth::user();
        $offers = Offer::with(['counterOffers'])->where('campaign_product_id',$campaign_product_id)->where('user_id',$user->id)->get();
        return  response()->json($offers);
    }

    public function store(Request $request){
        $user=\auth()->user();

        $organization=$user->organization;
        if(!$organization){
            $response = ['data'=>[],'message'=>"Unauthorized",'success'=>false];
            return response()->json($response,401);
        }

        if($organization->type=="DEPOSITOR") {
            if (!$user->userCan('depositor/review-offers/counter-offer')) {
                $response = ['data' => [], 'message' => "Access denied", 'success' => false];
                return response()->json($response, 403);
            }
            $request->merge(['from_depositor' => 1]);
        }else{
            if (!$user->userCan('bank/in-progress/counter-offer')) {
                $response = ['data' => [], 'message' => "Access denied", 'success' => false];
                return response()->json($response, 403);
            }
        }

        if($request->filled('counter_offer_expiry')){
            $request->merge(['counter_offer_expiry' => removeAmPm($request->counter_offer_expiry)]);
        }
        $request->merge(['is_counter_offer' => 1]);
        // return json_decode( $request);
        return $this->offer_service->save($request,$user);
    }

    public function action(Request $request, $counter_offer_id, $action){
        $user=\auth()->user();

        $organization=$user->organization;
        if(!$organization){
            //alert()->error("Failed, you need to be logged in as the Financial Institution to perform that action.");
            return response()->json(['message'=>"Failed, you need to be logged in as the Financial Institution to perform that action.",'data'=>[]]);
        }

        if($organization->type=="DEPOSITOR") {
           // alert()->error("Failed, you need to be logged in as the Financial Institution to perform that action.");
            return response()->json(['message'=>"Failed, you need to be logged in as the Financial Institution to perform that action.",'data'=>[]]);
        }

        $counter_offer = CounterOffer::find(CustomEncoder::urlValueDecrypt($counter_offer_id));
        if(!$counter_offer){
            systemActivities(Auth::id(), json_encode($request->query()), "Failed, counter offer not found");
           // alert()->error("Failed, counter offer not found");
            return response()->json(['message'=>'Failed, counter offer not found','data'=>[]]);
        }

        if($counter_offer->status !='PENDING'){
            systemActivities(Auth::id(), json_encode($request->query()), "Failed, counter offer already ".$counter_offer->status);
           // alert()->error("Failed, counter offer already ".strtolower($counter_offer->status));
            return response()->json(['message'=>"Failed, Counter Offer Already ".ucfirst(strtolower($counter_offer->status)),'data'=>[]]);
        }

        $offer = $counter_offer->offer;
        if(!$offer || $offer->invited->organization_id != $organization->id){
            systemActivities(Auth::id(), json_encode($request->query()), "Failed, offer not found");
            //alert()->error("Failed, offer not found");
            return response()->json(['message'=>'Failed, offer not found','data'=>[]]);
        }

        switch ($action){
            case 'accept':
                if (!$user->userCan('bank/in-progress/accept-offer')) {
                   // alert()->error("Failed, Access denied");
                    return response()->json(['message'=>'Failed, Access denied','data'=>[]]);
                }

                $counter_offer->status = 'ACCEPTED';
                $counter_offer->save();

                $archive_id = archiveTable($offer->id,'offers',$user->id,'UPDATED FROM COUNTER OFFER ID: '.$counter_offer->id);

                $update_array = [
                    'maximum_amount'=>$counter_offer->maximum_amount,
                    'minimum_amount'=>$counter_offer->minimum_amount,
                    'interest_rate_offer'=>$counter_offer->offered_interest_rate,
                    // 'rate_held_until'=>$counter_offer->offer_expiry,
                    'product_disclosure_url'=>$counter_offer->product_disclosure_url,
                    'special_instructions'=>$counter_offer->requestedByOrganization->type == 'BANK' ? $counter_offer->special_instructions : $offer->special_instructions,
                    'rate_type'=>$counter_offer->rate_type,
                    'prime_rate'=>$counter_offer->prime_rate,
                    'rate_operator'=>$counter_offer->rate_operator,
                    'fixed_rate'=>$counter_offer->fixed_rate,
                    'product_disclosure_statement'=>$counter_offer->product_disclosure_statement,
                    'counter_offer_archive_id'=>$archive_id ? $archive_id : NULL
                ];

                $offer->update($update_array);
               // alert()->success("Counter offer accepted successfully");
                $this->approvalEmail($offer->invited->depositRequest->organization, $organization, $action='accepted');
                systemActivities(Auth::id(), json_encode($request->query()), "Counter offer accepted successfully");
                return response()->json(['message'=>'Counter offer accepted successfully','data'=>[]]);
                break;
            case 'decline':
                if (!$user->userCan('bank/in-progress/decline-offer')) {
                    //alert()->error("Failed, Access denied");
                    return response()->json(['message'=>'Failed, Access denied','data'=>[]]);
                }

                $counter_offer->status = 'DECLINED';
                $counter_offer->save();
                if ($offer->campaign_product_id) {
                    $offer->update(['offer_status'=>'DECLINED']);
                    $deposit_request = $offer->invited->depositRequest;
                    if ($deposit_request) {
                        $deposit_request->update(['request_status'=>'DECLINED']);
                    }
                    # code...
                }
               // alert()->success("Counter offer declined successfully");
                $this->approvalEmail($offer->invited->depositRequest->organization, $organization, $action='declined');
                systemActivities(Auth::id(), json_encode($request->query()), "Counter offer declined successfully");
                return response()->json(['message'=>'Counter offer declined successfully','data'=>[]]);
                break;
            default:
                alert()->error("Failed, action not found");
                return response()->json(['message'=>'Failed, action not found','data'=>[]]);
        }

    }

    private function approvalEmail(Organization $depositor, Organization $bank, string $action='accepted'){
        $depositor_users = $depositor->notifiableUsersEmails($return_emails = true);
        Mail::to($depositor_users)->queue(new CounterOfferApprovalMail([
            'message'=> "",
            'subject'=>"Your counter offer has been ".$action,
            'header'=>$bank->name." has ".$action." your counter offer",
            'action'=>$action,
            'user_type' =>$depositor->type,
        ]));

        $bank_users = $bank->notifiableUsersEmails($return_emails = true);
        Mail::to($bank_users)->queue(new CounterOfferApprovalMail([
            'message'=> "",
            'subject'=>"You have ".$action." a counter offer",
            'header'=>"You have ".$action." a counter offer from ".$depositor->name,
            'action'=>$action,
            'user_type' =>$bank->type,
        ]));
    }
}
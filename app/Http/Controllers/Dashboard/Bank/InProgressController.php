<?php
namespace App\Http\Controllers\Dashboard\Bank;

use App\Constants;
use App\CustomEncoder;
use App\Data\BankData;
use App\Models\CounterOffer;
use App\Models\Offer;
use App\User;
use App\Models\DepositRequest;
use App\Models\InvitedBank;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Bank\WithdrawOfferMail;
use DB;
use Illuminate\Support\Facades\Log;

class InProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.bank');
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if(!$user->userCan('bank/in-progress/page-access')) {
            return redirect()->to('access-denied');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Bank In Progress");
        return view('dashboard.bank.in-progress');
    }

    public function getInProgressData(Request $request){
        $user = \auth()->user();
        if (!$user->userCan('bank/in-progress/page-access')) {
            $response = ["message" => "you cannot access the in_progress deposits"];
            return response()->json($response);
        }
        $requests = Offer::with(['counterOffers','invited'])->join('invited',function ($join){
                $join->on('invited.id','=','offers.invitation_id');
                $join->where('invited.organization_id',\auth()->user()->organization->id);
                $join->whereIn('invitation_status',['PARTICIPATED']);
            })
            ->join('depositor_requests','depositor_requests.id','=','invited.depositor_request_id')
            ->join('users','users.id','=','depositor_requests.user_id')
            ->join('organizations','depositor_requests.organization_id','=','organizations.id')
            ->leftJoin('demographic_organization_data','demographic_organization_data.organization_id','=','organizations.id')
            ->leftJoin('products','products.id','=','depositor_requests.product_id')
            ->whereIn('depositor_requests.request_status',['ACTIVE'])
            ->whereIn('offers.offer_status',['ACTIVE']);

            if ($request->filled("rate")) {
                $rateobject = explode(",", $request->rate);
                if (($rateobject[0] > 0) && ($rateobject[1] > 0)) {
                    $requests->whereBetween("offers.interest_rate_offer", $rateobject);
                } else {
                    if ($rateobject[0] > 0) {
                        $requests->where("offers.interest_rate_offer", ">=", $rateobject[0]);
                    }
                    if ($rateobject[1] > 0) {
                        $requests->where("offers.interest_rate_offer", "<=", $rateobject[1]);
                    }
                }
            }
            if ($request->filled("offer")) {
                $deposit_amount = explode(",", $request->offer);
                if (($deposit_amount[0] > 0) && ($deposit_amount[1] > 0)) {
                    $requests->whereBetween("depositor_requests.amount", $deposit_amount);
                } else {
                    if ($deposit_amount[0] > 0) {
                        $requests->where("depositor_requests.amount", ">=", $deposit_amount[0]);
                    }
                    if ($deposit_amount[1] > 0) {
                        $requests->where("depositor_requests.amount", "<=", $deposit_amount[1]);
                    }
                }
            }
            if ($request->filled("termLength")) {
                $termlenobject = explode(",", $request->termLength);
                $termtype = $request->termLengthType;

    
                if (($termlenobject[0] > 0) && ($termlenobject[1] > 0)) {
                    $requests->where("depositor_requests.term_length_type", $termtype);
                    $requests->whereBetween("depositor_requests.term_length", array_map('intval',$termlenobject));
                } else {
                    if ($termlenobject[0] > 0) {
                        $requests->where("depositor_requests.term_length_type", $termtype);
                        $requests->where("depositor_requests.term_length", ">=", (int)$termlenobject[0]);
                    }
                    if ($termlenobject[1] > 0) {
                        $requests->where("depositor_requests.term_length_type", $termtype);
                        $requests->where("depositor_requests.term_length", "<=", (int)$termlenobject[1]);
                    }
                }
            }
    
            if ($request->filled("products")) {
                $products = explode(",", $request->products);
                $requests->whereIn("products.description", $products);
            }
           
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $searchColumns = [
                    'offers.offer_status', 'offers.reference_no', 'offers.interest_rate_offer', 'depositor_requests.amount', 'products.description', 'organizations.name'
                ];
                $requests->where(function ($query) use ($searchColumns, $searchTerm) {
                    foreach ($searchColumns as $key => $column) {
                        if ($key == 0) {
                            $query->where($column, 'LIKE', '%' . $searchTerm . '%');
                        } else {
                            $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                        }
                    }
                });
            }
    
    
            $requests = $requests->select([
                'offers.*',
                'depositor_requests.closing_date_time',
                'organizations.name as depositor',
                'demographic_organization_data.province as province'
            ]);
            $requests = $requests->paginate(10);
    

            $requests->getCollection()->transform(function ($record) {
                $record->offer_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
                return $record;
            });

            return response()->json($requests);
    }

    public function getData(Request $request)
    {
        $user=\auth()->user();
        if(!$user->userCan('bank/in-progress/page-access') ){
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
        $rowperpage = $request->filled('start') ? $request->length: 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        BankData::inProgressData(null,function ($data) use($start,$rowperpage,$draw,$columnIndex,$columnName,$columnSortOrder,$searchValue){
            $totalRecords = with(clone $data)->count();

            $search_columns = [
                "reference_no",
                "depositor_name",
                "province",
                "amount",
                "product",
                "investment_period",
                "interest_rate",
//                "rate_held_until",
                "action"
            ];

            if( !empty($searchValue) ) {

                $search_is_date=false;
                try {
                    try{
                        $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                        $rate_held_until_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d H:i"), Constants::DATE_FORMAT,Constants::DATE_TIME_FORMAT_NO_SECONDS);
                    }catch (\Exception $exception) {
                        $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                        $rate_held_until_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT,Constants::DATE_FORMAT);
                    }

                    $data = $data->where('offers.rate_held_until', 'like', '%' . $rate_held_until_in_utc . '%');
                    $search_is_date=true;
                }catch (\Exception $exception){}

                if (!$search_is_date) {
                    $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                        $query->where('depositor_requests.reference_no', 'like', '%' . $searchValue . '%');
                        foreach ($search_columns as $search_column) {
                            switch ($search_column) {
                                case'depositor_name':
                                    $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'amount':
                                    $searchValue = str_replace(",","",$searchValue);
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
                                case 'interest_rate':
                                    $system_setting = getSystemSettings('prime_rate');
                                    $prime_rate = $system_setting->value;
                                    $searchValue = remove00AndPercentInterestRate($searchValue);
                                    $query->orWhere(DB::raw("CASE WHEN depositor_requests.term_length_type='HISA' AND offers.rate_type='VARIABLE' THEN 
                                    (CASE WHEN offers.rate_operator='-' THEN $prime_rate - COALESCE(offers.fixed_rate,0) ELSE $prime_rate + COALESCE(offers.fixed_rate,0) END)
                                    ELSE offers.interest_rate_offer END"), "like", '%'.$searchValue.'%');
                                    break;
                            }
                        }
                    });
                }
            }

            if(!$columnIndex && !is_numeric($columnIndex)){
                $data = $data->orderBy('depositor_requests.reference_no','ASC');
            }else{
                switch ($columnName){
                    case 'rate_held_until':
                        $data = $data->orderBy('depositor_requests.closing_date_time',strtoupper($columnSortOrder));
                        break;
                    case 'amount':
                        $data = $data->orderBy('depositor_requests.amount',strtoupper($columnSortOrder));
                        break;
                    case 'product':
                        $data = $data->orderBy('products.description',strtoupper($columnSortOrder));
                        break;
                    case 'depositor_name':
                        $data = $data->orderBy('organizations.name',strtoupper($columnSortOrder));
                        break;
                    case 'province':
                        $data = $data->orderBy('demographic_organization_data.province',strtoupper($columnSortOrder));
                        break;
                    case 'investment_period':
                        $data = $data->orderBy('depositor_requests.term_length',strtoupper($columnSortOrder));
                        break;
                    case 'interest_rate':
                        $data = $data->orderBy('offers.interest_rate_offer',strtoupper($columnSortOrder));
                        break;
                    default:
                        $data = $data->orderBy('depositor_requests.reference_no',strtoupper($columnSortOrder));
                        break;

                }
            }

            $totalRecordswithFilter = with(clone $data)->count();

            $data = $data->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();

//            $tooltip='data-toggle="tooltip" data-placement="top" title="This offer can not updated"';
            $user =\auth()->user();
            foreach($data as $record) {
                $depositRequest = $record->invited->depositRequest;

                $offer_id_encoded = CustomEncoder::urlValueEncrypt($record->id);

               $withdraw_modal='';
                   
               $action3='<div class="list-icons">
                                <div class="list-icons-item dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-uu" style="position: absolute;z-index:999">';

               if($user->userCan('bank/in-progress/withdraw-offer-action') ) {
                   //$action .= ' <a href="javascript:void()" req-id="' . $encoded_request_id . '" onclick="return withdraw(this)" class="dropdown-item">Withdraw Request</a>';
                   $action3 .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal'.$offer_id_encoded.'">Withdraw Offer</a>';
               }
        
               
               $action3 .= ' </div>
                            </div>
                        </div>';

                $withdraw_modal=' <div id="myModal'.$offer_id_encoded.'" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form action="'.route('bank.offer-withdraw',['offer_id'=>$offer_id_encoded]).'" class="withdraw-offer-form" method="post">
                                     '.csrf_field().'
                                    <div class="modal-header">
                                        <h4 class="modal-title">Do you want to withdraw this offer?</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                        <input type="hidden" name="offer_id" value="'.$offer_id_encoded.'">
                                            <div class="form-group col-12">
                                                <label>Reason for withdrawing the offer</label>
                                                <select name="reason" class="form-control" required>
                                                        <option value="">Select the reason</option>';
                                $reasons = DB::table('offer_withdrawal_reasons')->get();
                                foreach ( $reasons as $reason) {
                                    $withdraw_modal .='<option value="'.$reason->reason.'">'.$reason->reason.'</option>';
                                }

                                $withdraw_modal .=' </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn custom-primary round">Withdraw Offer</button>
                                        <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';

                if ($record['rate_type'] != 'VARIABLE') {
                    $interest_rate_offer = formatInterest($record["interest_rate_offer"]);
                }else{
                    $interest_rate_offer = formatInterest($record['fixed_rate'], true, $record['rate_operator'],true);
                }

                $isEditable = true; //Carbon::parse($record->closing_date_time)->greaterThan(getUTCTimeNow());

                $has_counter_offer = $record->counterOffers->where('status','PENDING')->first();
                if($has_counter_offer && $user->userCan('bank/in-progress/counter-offer')) {
                    $action = '<a href="' . route('bank.offer-summary', $offer_id_encoded) . '?fromPage=in-progress" class="btn btn-success  mmy_btn btn-block" >View <br><small>Counter Offer</small></a>';
                }else {
                    $action = '<a href="' . route('bank.offer-summary', $offer_id_encoded) . '?fromPage=in-progress" class="btn custom-primary round mmy_btn btn-block">View</a>';
                }

                $organization = $depositRequest->organization;
                $data_arr[] = array(
                    "reference_no" => $depositRequest->reference_no,
                    "depositor_name" => $organization->name,
                    "province" => $organization->demographicData->province,
                    "amount" => $depositRequest->currency.' '.number_format($depositRequest->amount),
                    "product" => $depositRequest->product->description,
                    "investment_period" => $depositRequest->term_length_type == "HISA" ? "-" : $depositRequest->term_length. ' ' . ucwords(strtolower($depositRequest->term_length_type)),
                    "interest_rate" => $interest_rate_offer,
                    //"rate_held_until" => $record->rate_held_until ? changeDateFromUTCtoLocal($record->rate_held_until,Constants::DATE_TIME_FORMAT_NO_SECONDS) : '-',
                    "rate_held_until" => $record->rate_held_until ? timeIn_12_24_format($record->rate_held_until) : '-',
                    "action" => $user->userCan('bank/in-progress/view-button') ? $action : '',
                    "action2" => $user->userCan('bank/in-progress/edit-button') ? '<a href="'.($isEditable ? route('bank.place-offer',['offer_id'=>$offer_id_encoded,'request_id'=>'null']) : '').'?fromPage=in-progress" class="btn custom-primary round mmy_btn btn-block '.(!$isEditable?"un-editable":"").'">Edit</a>' : '',
                    "action3" =>$action3.$withdraw_modal,
                );
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr
            );

            echo json_encode($response);
            exit;
        });
    }

    public function offerWithdraw(Request $request,$offer_id){
        $user=\auth()->user();
        if( !$user->userCan('bank/in-progress/withdraw-offer-action') ){
            $response = ['data'=>[],'message'=>"Access denied",'success'=>false];
            return response()->json($response,403);
        }

        $validator = Validator::make($request->all(), [
            'offer_id' => 'required',
            'reason' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        $offer = Offer::find(CustomEncoder::urlValueDecrypt($offer_id));
        if(!$offer){
            systemActivities(Auth::id(), json_encode($request->query()),"Pending Deposits  -> Withdraw, Failed.. offer not found");
            $response = array("success"=>false, "message"=>"Offer not found, please retry or ".Constants::RESPONSE_MESSAGE_CONTACT_US, "data"=>[]);
            return response()->json($response, 400);
        }

        if( $offer->offer_status == "ACTIVE" ){
            archiveTable($offer->id,"offers",\auth()->id(),"OFFER_WITHDRAWN");
            $offer->offer_status = 'OFFER_WITHDRAWN';
            $offer->offer_withdrawal_reason = $request->reason;
            $offer->save();

            $invitation = InvitedBank::where('id',$offer->invitation_id)->first();
            if ($invitation->depositor_request_id) {
                $deposit_request = DepositRequest::find($invitation->depositor_request_id);
                if ($deposit_request->reference_no) {
                    $usr =User::find($deposit_request->user_id);
                    $emails = $invitation->bank->notifiableUsersEmails();

                   $message = $deposit_request->reference_no;
                    Mail::to($emails)->queue(new WithdrawOfferMail([
                        'message' => $message,
                        'subject' =>"Offer Withdrawn"
                    ]));
                    
                }
            }

            $response = array("success"=>true, "message"=>"Offer withdrawn successfully", "data"=>[]);
            return response()->json($response, 200);
        }

        $response = array("success"=>false, "message"=>"Offer can not be withdrawn, its no longer active", "data"=>[]);
        return response()->json($response, 400);
    }

}

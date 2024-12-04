<?php

namespace App\Http\Controllers\Dashboard\Depositor;

use App\Constants;
use App\CustomEncoder;
use App\Data\DepositorData;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminMail;
use App\Mail\AdminMails;
use App\Mail\BankMails;
use App\Models\Deposit;
use App\Mail\DepositorMails;

class ActiveDepositsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.depositor');
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/active-deposits/page-access')) {
            return view('dashboard.403');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Depositor Active Deposits");
        return view('dashboard.depositor.active-deposits');
    }
    public function getInacticationreaseons()
    {
        $reasons = DB::table('deposit_inactivate_reasons')->whereNotIn('type', ['BANK'])->get();
        return response($reasons);
    }
    public function getActiveDeposits(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/active-deposits/page-access')) {
            $response = ["message" => "you cannot access the pending deposits"];
            return response()->json($response);
        }
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            //            ->join('users','users.id','=','invited.invited_user_id')
            ->join('organizations', 'invited.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id)
            ->whereHas('offer.invited.organization')
            ->whereIn('deposits.status', ['ACTIVE']);

        // filters
        if ($request->filled("rate")) {
            $rateobject = explode(",", $request->rate);
            if (($rateobject[0] > 0) && ($rateobject[1] > 0)) {
                $contracts->whereBetween("offers.interest_rate_offer", $rateobject);
            } else {
                if ($rateobject[0] > 0) {
                    $contracts->where("offers.interest_rate_offer", ">=", $rateobject[0]);
                }
                if ($rateobject[1] > 0) {
                    $contracts->where("offers.interest_rate_offer", "<=", $rateobject[1]);
                }
            }
        }
        if ($request->filled("offer")) {
            $deposit_amount = explode(",", $request->offer);
            if (($deposit_amount[0] > 0) && ($deposit_amount[1] > 0)) {
                $contracts->whereBetween("deposits.offered_amount", $deposit_amount);
            } else {
                if ($deposit_amount[0] > 0) {
                    $contracts->where("deposits.offered_amount", ">=", $deposit_amount[0]);
                }
                if ($deposit_amount[1] > 0) {
                    $contracts->where("deposits.offered_amount", "<=", $deposit_amount[1]);
                }
            }
        }

        if ($request->filled("termLength")) {
            $termlenobject = explode(",", $request->termLength);
            $termtype = $request->termLengthType;

            if (($termlenobject[0] > 0) && ($termlenobject[1] > 0)) {
                $contracts->where("depositor_requests.term_length_type", $termtype);
                $contracts->whereBetween("depositor_requests.term_length", array_map('intval', $termlenobject));
            } else {
                if ($termlenobject[0] > 0) {
                    $contracts->where("depositor_requests.term_length_type", $termtype);
                    $contracts->where("depositor_requests.term_length", ">=", (int) $termlenobject[0]);
                }
                if ($termlenobject[1] > 0) {
                    $contracts->where("depositor_requests.term_length_type", $termtype);
                    $contracts->where("depositor_requests.term_length", "<=", (int)$termlenobject[1]);
                }
            }
        }
        if ($request->filled("financialOrganizations")) {
            $orgs = explode(",", $request->financialOrganizations);
            $contracts->whereIn("organizations.name", $orgs);
        }
        if ($request->filled("products")) {
            $products = explode(",", $request->products);
            $contracts->whereIn("products.description", $products);
        }
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $searchColumns = [
                'deposits.gic_number', 'offers.fixed_rate', 'deposits.offered_amount', 'products.description', 'organizations.name'
            ];
            $contracts->where(function ($query) use ($searchColumns, $searchTerm) {
                foreach ($searchColumns as $key => $column) {
                    if ($key == 0) {
                        $query->where($column, 'LIKE', '%' . $searchTerm . '%');
                    } else {
                        $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                    }
                }
            });
        }

        // after search =
        // $contracts = $contracts->select('deposits.*');
        $contracts = $contracts->select('deposits.*');
        $contracts = $contracts->paginate(10);
        $contracts->getCollection()->transform(function ($record) {
            $record->encoded_offer_id = CustomEncoder::urlValueEncrypt($record->offer_id);
            $record->encoded_deposit_id = CustomEncoder::urlValueEncrypt($record->id);
            $record->offer->rate_held_until = changeDateFromUTCtoLocal($record->offer->rate_held_until);
            $record->maturity_date = changeDateFromUTCtoLocal($record->maturity_date);

            return $record;
        });
        return $contracts;
    }
    public function getData(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/active-deposits/page-access')) {
            $response = array(
                "draw" => 0,
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

        DepositorData::activeDepositData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                "gic_number",
                //                "maturity_date",
                "bank_name",
                "offered_amount",
                "product_name",
                "interest_rate_offer",
                "term_length"
            ];

            if (!empty($searchValue)) {

                $search_is_date = false;
                try {
                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                    $timezone_offset = timezoneOffsetFromUTC(\auth()->user()->timezone);
                    $data = $data->whereRaw("DATE(CONVERT_TZ(deposits.maturity_date,'+00:00','" . $timezone_offset . "')) = '" . $date->format("Y-m-d") . "'");
                    $search_is_date = true;
                } catch (\Exception $exception) {
                }

                if (!$search_is_date) {
                    $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                        $query->where('organizations.name', 'like', '%' . $searchValue . '%');
                        foreach ($search_columns as $search_column) {
                            switch ($search_column) {
                                case 'offered_amount':
                                    $searchValue = str_replace(",", "", $searchValue);
                                    $query->orWhere('deposits.offered_amount', 'like', '%' . $searchValue . '%')->orWhere('depositor_requests.currency', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(depositor_requests.currency, ' ', deposits.offered_amount)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'bank_name':
                                    $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'product_name':
                                    $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'gic_number':
                                    $query->orWhere('deposits.gic_number', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'term_length':
                                    $query->orWhere($search_column, 'like', '%' . $searchValue . '%')->orWhere('term_length_type', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(term_length, ' ', term_length_type)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'interest_rate_offer':
                                    $system_setting = getSystemSettings('prime_rate');
                                    $prime_rate = $system_setting->value;
                                    $searchValue = remove00AndPercentInterestRate($searchValue);
                                    if ($searchValue > 0) {
                                        $query->orWhere(DB::raw("CASE WHEN depositor_requests.term_length_type='HISA' AND offers.rate_type='VARIABLE' THEN 
                                        (CASE WHEN offers.rate_operator='-' THEN $prime_rate - offers.fixed_rate ELSE $prime_rate + offers.fixed_rate END)
                                        ELSE offers.interest_rate_offer END"), "like", '%' . $searchValue . '%');
                                    }
                                    break;
                            }
                        }
                    });
                }
            }

            if (!$columnIndex && !is_numeric($columnIndex)) {
                $data = $data->orderBy('deposits.maturity_date', 'ASC')->orderBy('deposits.id', 'ASC');
            } else {
                switch ($columnName) {
                    case 'interest_rate_offer':
                        $data = $data->orderBy('offers.interest_rate_offer', strtoupper($columnSortOrder));
                        break;
                    case 'offered_amount':
                        $data = $data->orderBy('deposits.offered_amount', strtoupper($columnSortOrder));
                        break;
                    case 'product_name':
                        $data = $data->orderBy('products.description', strtoupper($columnSortOrder));
                        break;
                    case 'bank_name':
                        $data = $data->orderBy('organizations.name', strtoupper($columnSortOrder));
                        break;
                    case 'maturity_date':
                        $data = $data->orderByRaw('- deposits.maturity_date' . ' ' . strtoupper($columnSortOrder)); // this minus (-) ensure null are assumed to be greater than
                        break;
                    case 'gic_number':
                        $data = $data->orderBy('deposits.gic_number', strtoupper($columnSortOrder));
                        break;
                    case 'term_length':
                        $data = $data->orderBy('depositor_requests.term_length', strtoupper($columnSortOrder));
                        break;
                }
            }

            $totalRecordswithFilter = with(clone $data)->get()->count();

            $data = $data->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();

            $user = \auth()->user();
            foreach ($data as $record) {
                if ($record->rate_type != 'VARIABLE') {
                    $interest_rate_offer = formatInterest($record->interest_rate_offer);
                } else {
                    $interest_rate_offer = formatInterest($record->fixed_rate, true, $record->rate_operator, true);
                }

                $encoded_deposit_id = CustomEncoder::urlValueEncrypt($record->id);
                $encoded_offer_id = CustomEncoder::urlValueEncrypt($record->offer_id);

                $markinactive_modal = "";

                $action = '<div class="list-icons">
                                <div class="list-icons-item dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-uu" style="position: absolute;z-index:999">';

                if ($user->userCan('depositor/active-deposits/review-offers')) {
                    $action .= '<a href="' . route('depositor.summary-request-review_offers_summary', $encoded_deposit_id) . '?fromPage=active-deposits" class="dropdown-item">Review Offers</a>';
                }

                $action .= '<div class="dropdown-divider"></div>';

                if ($user->userCan('depositor/active-deposits/view-button')) {
                    $action .= '<a href="' . route('depositor.offer-summary', $encoded_offer_id) . '?fromPage=active-deposits" class="dropdown-item">View</a>';
                }

                $action .= '<div class="dropdown-divider"></div>';
                if ($user->userCan('depositor/active-deposits/mark-inactive')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal' . $encoded_deposit_id . '">Mark Inactive</a>';
                }

                $action .= ' </div>
                            </div>
                        </div>';

                $markinactive_modal .= ' <div id="myModal' . $encoded_deposit_id . '" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form action="' . route('depositor.mark-deposit-inactive', ['deposit_id' => $encoded_deposit_id]) . '" class="withdraw-request-form" method="post">
                                     ' . csrf_field() . '
                                    <div class="modal-header">
                                        <h4 class="modal-title">Do you want to mark this deposit inactive?</h4>
                                    </div>
                                        <div class="modal-body">
                                        <div class="row">
                                        <input type="hidden" name="dep_id" value="' . $encoded_deposit_id . '">
                                            <div class="form-group col-12">
                                                <label>Reason for Marking the deposit Inactive</label>
                                                <select name="reason" class="form-control" required>
                                                        <option value="">Select the reason</option>';
                $reasons = DB::table('deposit_inactivate_reasons')->whereNotIn('type', ['BANK'])->get();
                foreach ($reasons as $reason) {
                    $markinactive_modal .= '<option value="' . $reason->reason . '">' . $reason->reason . '</option>';
                }

                $markinactive_modal .= ' </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn custom-primary round">Mark Inactive </button>
                                        <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';

                $data_arr[] = array(
                    "gic_number" => $record->gic_number,
                    "bank_name" => $record->bank_name,
                    "offered_amount" => $record->currency . ' ' . number_format($record->offered_amount),
                    "product_name" => $record->product_name,
                    "term_length" => $record->term_length_type == "HISA" ? "-" : $record->term_length . ' ' . ucwords(strtolower($record->term_length_type)),
                    "interest_rate_offer" => $interest_rate_offer,
                    "maturity_date" => $record->maturity_date ? changeDateFromUTCtoLocal($record->maturity_date, Constants::DATE_FORMAT) : '-',
                    "action" => $action . $markinactive_modal,
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

    public function markDepositInactive(Request $request, $deposit_id)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/active-deposits/mark-inactive')) {
            $response = ['data' => [], 'message' => "Access denied", 'success' => false];
            return response()->json($response, 403);
        }

        $validator = Validator::make($request->all(), [
            'dep_id' => 'required',
            'reason' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $contract = Deposit::where('offer_id', CustomEncoder::urlValueDecrypt($request->dep_id))->first();
        if (!$contract) {
            systemActivities(Auth::id(), json_encode($request->query()), "Deposit-> inactivate, Failed.. deposit not found");
            $response = array("success" => false, "message" => "Deposit not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => []);
            return response()->json($response, 400);
        }

        if ($contract->status == "ACTIVE") {
            archiveTable($contract->id, "deposits", auth()->id(), "EARLY_REDEMPTION");
            $contract->status = 'EARLY_REDEMPTION';
            $contract->redemption_date = getUTCDateNow($time = true);
            $contract->deposit_inactivate_reason = $request->reason;
            $contract->save();

            $user = \auth()->user();
            $message = "<center>Please note redemption is only initiated in the Yield Exchange platform. If funds have not already been released you may wish to contact " . $user->organization->name . " for next steps.</center>";
            $subject = $user->organization->name . " has marked a deposit as inactive.";
            $header = $contract->reference_no;
            $organization_name = $user->organization->name;
            try {
                $bank = $contract->offer->invited->organization;
                if ($bank) {
                    //Email to Bank
                    Mail::to($bank->notifiableUsersEmails())->queue(new BankMails([
                        'message' => $message,
                        'subject' => $subject,
                        'header' => $header,
                        'organization_name' => $organization_name,
                        'user_type' => 'Bank'
                    ], 'inactivate_deposit'));
                }

                //Email to Depositor
                $depositor_organization = $user->organization;
                $subject = "Your deposit has been marked inactive.";
                $header = $contract->reference_no;
                $organization_name = $user->organization->name;
                $message = "<center>Please note redemption is only initiated in the Yield Exchange platform. To redeem or gain access to your funds you will need to contact " . $bank->name . " directly.</center>";
                Mail::to($depositor_organization->notifiableUsersEmails())->queue(new DepositorMails([
                    'message' => $message,
                    'subject' => $subject,
                    'header' => $header,
                    'organization_name' => $organization_name,
                    'user_type' => 'Depositor'
                ], 'inactivate_deposit'));


                Mail::to(getAdminEmails())->queue(new AdminMails([
                    'message' => $message,
                    'header' => $header,
                    'subject' => $subject,
                    'organization_name' => $organization_name,
                    'user_type' => 'Depositor'
                ], 'inactivate_deposit'));
                /*$bank_objs = $bank->notifiableUsersEmails($return_emails=false);
                for($i=0;$i<count($bank_objs);$i++){
                    $message = "<center>Please note redemption is only initiated in the Yield Exchange platform. To redeem or gain access to your funds you will need to contact ".$bank_objs[$i]->organization_name." directly.</center>";
                    Mail::to($user->email)->queue(new MarkDepositInactive([
                        'message' => $message,
                        'subject' => $subject,
                        'header' => $header,
                        'user_type'=>'Depositor'
                    ]));
                }*/

                notify([
                    'type' => 'DEPOSIT MARKED INACTIVE',
                    'details' => $message,
                    'date_sent' => getUTCDateNow(true),
                    'status' => 'ACTIVE',
                    'organization_id' => $bank->id
                ]);
            } catch (\Exception $exception) {
            }

            $response = array("success" => true, "message" => "Deposit marked inactive successfully", "data" => []);
            return response()->json($response, 200);
        }

        $response = array("success" => false, "message" => "Deposit can not be marked inactive", "data" => []);
        return response()->json($response, 400);
    }
}

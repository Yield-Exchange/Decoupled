<?php

namespace App\Http\Controllers\Dashboard\Bank;

use App\Constants;
use App\CustomEncoder;
use App\Data\BankData;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminMail;
use App\Mail\AdminMails;
use App\Mail\BankMails;
use App\Models\Deposit;
use App\Mail\DepositorMails;

class ActiveDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.bank');
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/active-deposits/page-access')) {
            return redirect()->to('access-denied');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Bank Pending Deposits");
        return view('dashboard.bank.active-deposits');
    }
    public function getActiveDeposits(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/active-deposits/page-access')) {
            $response = ["message" => "you cannot access the pending deposits"];
            return response()->json($response);
        }
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
        // ->select('deposits.*')
        // ->paginate(10);
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
        $contracts = $contracts->select('deposits.*');
        $contracts = $contracts->paginate(10);
        $contracts->getCollection()->transform(function ($record) {
            $record->encoded_offer_id = CustomEncoder::urlValueEncrypt($record->offer_id);
            $record->encoded_deposit_id = CustomEncoder::urlValueEncrypt($record->id);
            $record->offer->rate_held_until = changeDateFromUTCtoLocal($record->offer->rate_held_until);
            $record->maturity_date = changeDateFromUTCtoLocal($record->maturity_date);

            return $record;
        });

        return response()->json($contracts);
    }
    public function getInacticationreaseons()
    {
        $reasons = DB::table('deposit_inactivate_reasons')->whereNotIn('type', ['DEPOSITOR'])->get();
        return response($reasons);
    }

    public function getData(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/active-deposits/page-access')) {
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

        BankData::activeDepositData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                "gic_number",
                "depositor_name",
                "province",
                "amount",
                "product",
                "investment_period",
                "interest_rate",
                //                "maturity_date",
                "action"
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
                        $query->where('deposits.gic_number', 'like', '%' . $searchValue . '%');
                        foreach ($search_columns as $search_column) {
                            switch ($search_column) {
                                case 'depositor_name':
                                    $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'amount':
                                    $searchValue = str_replace(",", "", $searchValue);
                                    $query->orWhere('deposits.offered_amount', 'like', '%' . $searchValue . '%')->orWhere('depositor_requests.currency', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(depositor_requests.currency, ' ', deposits.offered_amount)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'product':
                                    $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'province':
                                    $query->orWhere('demographic_organization_data.province', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'interest_rate':
                                    $system_setting = getSystemSettings('prime_rate');
                                    $prime_rate = $system_setting->value;
                                    $searchValue = remove00AndPercentInterestRate($searchValue);
                                    $query->orWhere(DB::raw("CASE WHEN depositor_requests.term_length_type='HISA' AND offers.rate_type='VARIABLE' THEN 
                                    (CASE WHEN offers.rate_operator='-' THEN $prime_rate - COALESCE(offers.fixed_rate,0) ELSE $prime_rate + COALESCE(offers.fixed_rate,0) END)
                                    ELSE offers.interest_rate_offer END"), "like", '%' . $searchValue . '%');
                                    break;
                                case 'investment_period':
                                    $query->orWhere('depositor_requests.term_length', 'like', '%' . $searchValue . '%')
                                        ->orWhere('depositor_requests.term_length_type', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(term_length, ' ', depositor_requests.term_length_type)"), 'like', '%' . $searchValue . '%');
                                    break;
                            }
                        }
                    });
                }
            }

            if (!$columnIndex && !is_numeric($columnIndex)) {
                $data = $data->orderBy('deposits.gic_number', 'ASC');
            } else {
                switch ($columnName) {
                    case 'maturity_date':
                        $data = $data->orderByRaw('- deposits.maturity_date' . ' ' . strtoupper($columnSortOrder)); // this minus (-) ensure null are assumed to be greater than
                        break;
                    case 'amount':
                        $data = $data->orderBy('deposits.offered_amount', strtoupper($columnSortOrder));
                        break;
                    case 'product':
                        $data = $data->orderBy('products.description', strtoupper($columnSortOrder));
                        break;
                    case 'depositor_name':
                        $data = $data->orderBy('organizations.name', strtoupper($columnSortOrder));
                        break;
                    case 'investment_period':
                        $data = $data->orderBy('depositor_requests.term_length', strtoupper($columnSortOrder));
                        break;
                    case 'interest_rate':
                        $data = $data->orderBy('offers.interest_rate_offer', strtoupper($columnSortOrder));
                        break;
                    case 'gic_number':
                        $data = $data->orderBy('deposits.gic_number', strtoupper($columnSortOrder));
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
                $depositRequest = $record->offer->invited->depositRequest;

                if ($record['rate_type'] != 'VARIABLE') {
                    $interest_rate_offer = formatInterest($record["interest_rate_offer"]);
                } else {
                    $interest_rate_offer = formatInterest($record['fixed_rate'], true, $record['rate_operator'], true);
                }

                $organization = $depositRequest->organization;
                $offer_id_encoded = CustomEncoder::urlValueEncrypt($record->offer->id);
                $encoded_deposit_id = CustomEncoder::urlValueEncrypt($record->id);

                $markinactive_modal = "";

                $action = '<div class="list-icons">
                                <div class="list-icons-item dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-uu" style="position: absolute;z-index:999">';

                if ($user->userCan('bank/active-deposits/view-button')) {
                    $action .= '<a href="' . route('bank.deposit-summary', $offer_id_encoded) . '?fromPage=bank-active-deposits" class="dropdown-item">View </a>';
                }

                $action .= '<div class="dropdown-divider"></div>';
                if ($user->userCan('bank/active-deposits/mark-inactive')) {
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal' . $encoded_deposit_id . '">Mark Inactive</a>';
                }

                $action .= ' </div>
                            </div>
                        </div>';

                $markinactive_modal .= ' <div id="myModal' . $encoded_deposit_id . '" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                     <form action="' . route('bank.mark-deposit-inactive', ['deposit_id' => $encoded_deposit_id]) . '" class="withdraw-request-form" method="post">
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
                $reasons = DB::table('deposit_inactivate_reasons')->whereNotIn('type', ['DEPOSITOR'])->get();
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
                    "gic_number" => $record->gic_number ? $record->gic_number : '-',
                    "depositor_name" => $organization->name,
                    "province" => $organization->demographicData->province,
                    "amount" => $depositRequest->currency . ' ' . number_format($record->offered_amount),
                    "product" => $depositRequest->product->description,
                    "investment_period" => $depositRequest->term_length_type == "HISA" ? "-" : $depositRequest->term_length . ' ' . ucwords(strtolower($depositRequest->term_length_type)),
                    "interest_rate" => $interest_rate_offer,
                    "maturity_date" => $record->maturity_date ? changeDateFromUTCtoLocal($record->maturity_date, Constants::DATE_FORMAT) : '-',
                    "action" => $action . $markinactive_modal
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
        if (!$user->userCan('bank/active-deposits/mark-inactive')) {
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
            $contract->deposit_inactivate_reason = $request->reason;
            $contract->redemption_date = getUTCDateNow($time = true);
            $contract->save();

            $user = \auth()->user();
            $message = "<center>Please note redemption is only initiated in the Yield Exchange platform.</center>";
            $subject = "Your deposit has been marked inactive.";
            $header = " Your deposit " . $contract->reference_no . " has been redeemed early";
            try {
                $bank = $contract->offer->invited->organization;
                $depositor = $contract->offer->invited->depositRequest->organization;
                $header =  $contract->reference_no;
                $organization_name = $bank->name;
                if ($bank) {
                    //  Email to Bank
                    Mail::to($bank->notifiableUsersEmails())->queue(new BankMails([
                        'message' => $message,
                        'header' => $header,
                        'subject' => $subject,
                        'organization_name' => $organization_name,
                        'user_type' => 'Bank'
                    ], 'inactivate_deposit'));
                }

                $message = "<center>Please note redemption is only initiated in the Yield Exchange platform.";
                $message .= " If funds have not already been release you may wish to contact " . $bank->name . " for next steps.</center>";
                $subject = $bank->name . " has marked a deposit as inactive.";
                $header = $contract->reference_no;
                //  Email to Depositor
                Mail::to($depositor->notifiableUsersEmails())->queue(new DepositorMails([
                    'message' => $message,
                    'header' => $header,
                    'subject' => $subject,
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

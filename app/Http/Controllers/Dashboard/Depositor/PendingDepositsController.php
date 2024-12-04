<?php

namespace App\Http\Controllers\Dashboard\Depositor;

use App\Constants;
use App\CustomEncoder;
use App\Data\DepositorData;
use App\Http\Controllers\Controller;
use App\Mail\Depositor\WithdrawDeposit;
use App\Models\Chat;
use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\AdminMail;
use App\Mail\AdminMails;
use App\Mail\BankMails;
use App\Mail\DepositorMails;
use App\Models\DepositRequest;

class PendingDepositsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.depositor');
    }

    public function createGic(Request $request, $deposit_id)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/pending-deposits/activate')) {
            return redirect()->to('access-denied');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Depositor Create GIC");
        $deposit = Deposit::where('offer_id', CustomEncoder::urlValueDecrypt($deposit_id))->first();
        if (!$deposit) {
            systemActivities(Auth::id(), json_encode($request->query()), "Depositor Create GIC, Deposit not found");
            alert()->error("Deposit not found");
            return redirect()->back();
        }

        if ($deposit->status != 'PENDING_DEPOSIT') {
            alert()->error("Deposit not found");
            return redirect()->back();
        }
        $deposit_req = DepositRequest::find($deposit->offer->invited->depositor_request_id);


        $fromPage = $request->has('fromPage') ? url($request->fromPage) : '';
        $organization = $deposit->offer->invited->organization;
        $submitRoute = route('depositor.confirm-activate-deposit');
        $permittedSubmitButton = $user->userCan('depositor/pending-deposits/activate');
        return view('dashboard.depositor.create-gic', compact('deposit', 'deposit_req', 'organization', 'submitRoute', 'fromPage', 'permittedSubmitButton'));
    }

    public function postCreateGic(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/pending-deposits/activate')) {
            $response = array("success" => false, "message" => "Access Denied", "data" => []);
            return response()->json($response, 403);
        }

        $validator = Validator::make($request->all(), [
            'deposit_id' => 'required',
            'gic_start_date' => 'required', //|date_format:Y-m-d
            'gic_number' => 'required',
            // 'maturity_date'=>'date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $contract = Deposit::whereHas('offer.invited.depositRequest')->find($request->deposit_id);
        if (!$contract) {
            systemActivities(Auth::id(), json_encode($request->query()), "Deposit-> Create GIC, Failed.. deposit not found");
            $response = array("success" => false, "message" => "Deposit not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => []);
            return response()->json($response, 400);
        }

        if ($contract->status == "PENDING_DEPOSIT") {

            try {
                \Illuminate\Support\Facades\DB::beginTransaction();

                $gic_start_date = Carbon::parse($request->gic_start_date)->setTime(23, 59, 59);
                $maturity_date = NULL;
                if ($contract->offer->invited->depositRequest->term_length_type != "HISA") {
                    $validator = Validator::make($request->all(), [
                        'maturity_date' => 'required' //|date_format:Y-m-d
                    ]);

                    if ($validator->fails()) {
                        $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
                        return response()->json($response, 400);
                    }

                    $maturity_date = changeDateFromLocalToUTC(Carbon::parse($request->maturity_date)->setTime(23, 59, 59)->toDateTimeString(), Constants::DATE_TIME_FORMAT);
                }

                archiveTable($contract->id, "deposits", auth()->id(), "ACTIVE");
                $contract->status = 'ACTIVE';
                $contract->gic_start_date = changeDateFromLocalToUTC($gic_start_date->toDateTimeString(), Constants::DATE_TIME_FORMAT);
                $contract->gic_number = $request->gic_number;
                $contract->maturity_date = $maturity_date;
                $contract->save();

                DB::table('deposit_rate_requests')->insert([
                    'gic_start_date' => changeDateFromLocalToUTC($gic_start_date->toDateTimeString(), Constants::DATE_TIME_FORMAT),
                    'gic_number' => $request->gic_number,
                    'maturity_date' => $maturity_date,
                    'deposit_id' => $request->deposit_id,
                    'offer_id' => $contract->offer->id,
                    'created_at' => getUTCTimeNow()
                ]);

                \Illuminate\Support\Facades\DB::commit();
            } catch (\Exception $exception) {
                \Illuminate\Support\Facades\DB::rollBack();
                $timestamp = time();
                Log::error($timestamp . ': ' . $exception->getTraceAsString());
                loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'Unable to perform the operation, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 409);
            }

            $deposit_request_organization = $contract->offer->invited->depositRequest->organization;
            $deposit_type = $contract->offer->invited->depositRequest->term_length_type != "HISA" ? "GIC" : "HISA";
            try {
                $deposit_request = $contract->offer->invited->depositRequest;
                Mail::to($deposit_request_organization->notifiableUsersEmails())->send(new DepositorMails([ /// depositor
                    'subject' => 'GIC Activated',
                    'request_details' => ['deposit_request' => $deposit_request, 'deposit_details' => $contract]
                ], 'activate_gic'));


                Mail::to($contract->offer->invited->organization->notifiableUsersEmails())->send(new BankMails([ /// bank
                    'subject' => 'GIC Activated',
                    'header' => 'GIC has been created',
                    'message' => ' ',
                    'request_details' => ['deposit_request' => $deposit_request, 'deposit_details' => $contract]
                ], 'activate_gic'));

                Mail::to(getAdminEmails())->queue(new AdminMails([
                    'message' => 'The following GIC has been set up',
                    'header' => 'GIC is All Set',
                    'user_type' => 'Admin',
                    'request_details' => ['deposit_request' => $deposit_request, 'deposit_details' => $contract],
                    'subject' => 'GIC Activated'
                ], "activate_gic"));

                if ($request->interest_rate != $contract->offer->interest_rate_offer) {
                    $message = $deposit_request_organization->name . " has indicated that the deposit " . $contract->reference_no . " for " . $contract->offer->invited->depositRequest->currency . " " . number_format($contract->offered_amount) . " has an interest rate of " . $request->interest_rate . "%.";
                    $message .= " instead of " . $contract->offer->interest_rate_offer . "%.";
                    $message .= " Please reply to info@yieldexchange.ca with the correct interest rate so that we can update the system.";
                    $organization_admins = $contract->offer->invited->organization->notifiableUsersEmails();
                    $system_admin_emails = getAdminEmails();
                    Mail::to($organization_admins)
                        ->bcc($system_admin_emails)
                        ->queue(new AdminMail([
                            'message' => $message,
                            'subject' => 'Interest rate change'
                        ]));
                    $type = 'Interest rate change';
                } else {
                    $message = $deposit_type . " has been created.";
                    $type = "CONTRACT DEPOSIT DATE UPDATED";
                }

                notify([
                    'type' => $type,
                    'details' => $message,
                    'date_sent' => getUTCDateNow(true),
                    'status' => 'ACTIVE',
                    'organization_id' => $contract->offer->invited->organization->id
                ]);
            } catch (\Exception $exception) {
            }

            loginActivities("", $request->server('HTTP_USER_AGENT'), 0);
            $url = $user->organization->type && $user->organization->type == "BANK" ? route('bank.active-deposits') : route('active-deposits');
            if ($request->interest_rate != $contract->offer->interest_rate_offer) {
                $response = array("success" => true, "message" => "A Yield Exchange admin will contact you to update the interest rate.", "message_title" => "Your new rate has been submitted.", "data" => [], "url" => $url);
            } else {
                $response = array("success" => true, "message" => "Your $deposit_type is now showing in the Active Deposit page.", "message_title" => $deposit_type . " has been created.", "data" => [], "url" => $url);
            }
            return response()->json($response, 200);
        }

        $response = array("success" => false, "message" => "Unable to perform the operation, " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => []);
        return response()->json($response, 400);
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/pending-deposits/page-access')) {
            return view('dashboard.403');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Pending Deposits");
        return view('dashboard.depositor.pending-deposits');
    }

    public function getPendingDeposits(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/pending-deposits/page-access')) {
            $response = ["message" => "you cannot access the pending deposits"];
            return response()->json($response);
        }
        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            //->join('users','users.id','=','invited.invited_user_id')
            //->join('users','users.id','=','depositor_requests.user_id')
            ->join('organizations', 'invited.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->whereHas('offer.invited.organization')
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id)
            ->whereIn('deposits.status', ['PENDING_DEPOSIT'])
            ->orderBy('depositor_requests.date_of_deposit', 'DESC')
            ->orderBy('depositor_requests.id', 'ASC');

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
                    $contracts->where("depositor_requests.term_length", ">=", (int)$termlenobject[0]);
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
                'deposits.reference_no', 'offers.fixed_rate', 'deposits.offered_amount', 'products.description', 'organizations.name'
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
        $contracts = $contracts->select('deposits.*')
            ->orderBy('depositor_requests.modified_date', 'DESC')
            ->orderBy('depositor_requests.created_date', 'DESC');
        $contracts = $contracts->paginate(10);

        $contracts->getCollection()->transform(function ($record) {
            $record->encoded_offer_id = CustomEncoder::urlValueEncrypt($record->offer_id);
            $record->encoded_deposit_id = CustomEncoder::urlValueEncrypt($record->id);
            $record->rate_held_until = changeDateFromUTCtoLocal($record->offer->rate_held_until);
            $record->date_of_deposit = changeDateFromUTCtoLocal($record->offer->invited->depositRequest->date_of_deposit);
            // $record->date_of_deposit = $record->offer->invited->depositRequest->date_of_deposit;

            return $record;
        });

        // return $request->filled('rate');
        return response()->json($contracts);
    }

    public function getData(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/pending-deposits/page-access')) {
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

        DepositorData::pendingDepositData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                "bank_name",
                "offered_amount",
                "product_name",
                "term_length",
                "interest_rate_offer"
            ];

            if (!empty($searchValue)) {
                $search_is_date = false;
                try {
                    try {
                        $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                        $date_time_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d H:i"), Constants::DATE_FORMAT, Constants::DATE_FORMAT);
                    } catch (\Exception $exception) {
                        $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                        $date_time_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d H:i"), Constants::DATE_FORMAT, Constants::DATE_FORMAT);
                    }
                    //                    $timezone_offset = timezoneOffsetFromUTC(\auth()->user()->timezone);
                    $data = $data->whereRaw("depositor_requests.date_of_deposit = '" . $date->format("Y-m-d") . "'") //$data->whereRaw("DATE(CONVERT_TZ(depositor_requests.date_of_deposit,'+00:00','".$timezone_offset."')) = '".$date->format("Y-m-d")."'")
                        ->orWhere('offers.rate_held_until', 'like', '%' . $date_time_in_utc . '%');
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
                                case 'product_name':
                                    $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'term_length':
                                    $query->orWhere('depositor_requests.term_length', 'like', '%' . $searchValue . '%')->orWhere('depositor_requests.term_length_type', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(depositor_requests.term_length, ' ', depositor_requests.term_length_type)"), 'like', '%' . $searchValue . '%');
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

            if (isset($columnSortOrder) && $columnIndex == 0) {
                if (strtoupper($columnSortOrder) === 'ASC') { /// make sure the default is desc ordered
                    $data = $data->orderByDesc('depositor_requests.date_of_deposit');
                } else {
                    $data = $data->orderBy('depositor_requests.date_of_deposit', 'ASC');
                }
            } elseif (!$columnIndex) {
                $data = $data->orderByDesc('depositor_requests.date_of_deposit');
            } else {
                switch ($columnName) {
                    case 'interest_rate_offer':
                        $data = $data->orderBy('offers.interest_rate_offer', strtoupper($columnSortOrder));
                        break;
                    case 'date_of_deposit':
                        $data = $data->orderBy('depositor_requests.date_of_deposit', strtoupper($columnSortOrder));
                        break;
                    case 'rate_held_until':
                        $data = $data->orderBy('offers.rate_held_until', strtoupper($columnSortOrder));
                        break;
                    case 'term_length':
                        $data = $data->orderBy('depositor_requests.term_length', strtoupper($columnSortOrder));
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
                }
            }

            $totalRecordswithFilter = with(clone $data)->get()->count();

            $data = $data->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();

            $user = \auth()->user();
            foreach ($data as $record) {
                $unread_messages = Chat::where('sent_to_organization_id', auth()->user()->organization->id)
                    ->where('deposit_id', $record->id)
                    ->where('status', 'NEW')
                    ->count();
                $badge_notify_chat1 = "";
                if ($unread_messages > 0) {
                    $badge_notify_chat1 = '<span class="badge badge-danger badge-notify-chat-1">' . $unread_messages . '</span>';
                }

                if ($record->rate_type != 'VARIABLE') {
                    $interest_rate_offer = formatInterest($record->interest_rate_offer);
                } else {
                    $interest_rate_offer = formatInterest($record->fixed_rate, true, $record->rate_operator, true);
                }

                $encoded_deposit_id = CustomEncoder::urlValueEncrypt($record->id);
                $encoded_offer_id = CustomEncoder::urlValueEncrypt($record->offer_id);

                $action = '<div class="list-icons" style="display:inline-block">
                                    <div class="list-icons-item dropdown">
                                        <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                        <div class="dropdown-menu">';

                if ($user->userCan('depositor/pending-deposits/view-button')) {
                    $action .= '<a href="' . route('depositor.offer-summary', $encoded_offer_id) . '?fromPage=pending-deposits" class="dropdown-item">View</a>';
                }
                $action .= ' <div class="dropdown-divider"></div>';
                if ($user->userCan('depositor/pending-deposits/review-offers')) {
                    $action .= '<a href="' . route('depositor.summary-request-review_offers_summary', $encoded_deposit_id) . '?fromPage=pending-deposits" class="dropdown-item">Review Offers</a>';
                }
                $action .= ' <div class="dropdown-divider"></div>';
                if ($user->userCan('depositor/pending-deposits/withdraw')) {
                    $action .= '<a href="javascript:void()" dep-id="' . $encoded_deposit_id . '" onclick="return withdraw(this);" class="dropdown-item">Withdraw</a>';
                }

                $action .= '</div>
                                    </div>
                                </div>';

                $create_gic = "<div style='white-space: nowrap;'>";
                if ($user->userCan('depositor/pending-deposits/activate')) {
                    $create_gic .= '<a style="display:inline-block" href="' . route('depositor.create-gic', $encoded_deposit_id) . '?fromPage=pending-deposits" class="btn custom-primary mmy_btn btn-sm round">Activate</a>';
                }

                $data_arr[] = array(
                    "date_of_deposit" => changeDateFromUTCtoLocal($record->date_of_deposit, \App\Constants::DATE_FORMAT), //changeDateFromUTCtoLocal($record->date_of_deposit,Constants::DATE_FORMAT),
                    //"rate_held_until" => changeDateFromUTCtoLocal($record->rate_held_until,Constants::DATE_TIME_FORMAT_NO_SECONDS),
                    "rate_held_until" => timeIn_12_24_format($record->rate_held_until),
                    "bank_name" => $record->bank_name,
                    "offered_amount" => $record->currency . ' ' . number_format($record->offered_amount),
                    "product_name" => $record->product_name,
                    "term_length" => $record['term_length_type'] == "HISA" ? "-" : $record['term_length'] . ' ' . ucwords(strtolower($record['term_length_type'])),
                    "interest_rate_offer" => $interest_rate_offer,
                    "chat" => ($user->userCan('universal/chats/page-access') && $user->userCan('universal/chats/send-messages')) ? '<a href="' . route('deposit.chat.room', $encoded_deposit_id) . '?fromPage=pending-deposits" class="btn custom-secondary mmy_btn btn-block round" style="display: inline-block;">Chat ' . $badge_notify_chat1 . '</a>' : '',
                    "action" => $create_gic . ' ' . $action . '</div>',
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

    public function depositWithdraw(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/pending-deposits/withdraw')) {
            $response = array("success" => false, "message" => "Access Denied", "data" => []);
            return response()->json($response, 403);
        }

        $validator = Validator::make($request->all(), [
            'deposit_id' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $contract = Deposit::where('offer_id', CustomEncoder::urlValueDecrypt($request->deposit_id))->first();
        if (!$contract) {
            systemActivities(Auth::id(), json_encode($request->query()), "Deposit-> Withdraw, Failed.. deposit not found");
            $response = array("success" => false, "message" => "Deposit not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => []);
            return response()->json($response, 400);
        }

        if ($contract->status == "PENDING_DEPOSIT") {
            archiveTable($contract->id, "deposits", auth()->id(), "WITHDRAWN");
            $contract->status = 'WITHDRAWN';
            $contract->save();


            $message =  $contract->reference_no;
            try {
                $bank = $contract->offer->invited->organization;
                if ($bank) {
                    Mail::to($bank->notifiableUsersEmails())->queue(new WithdrawDeposit([
                        'message' => $message,
                        'user_type' => 'Bank'
                    ]));
                    $depositor = $contract->offer->invited->depositRequest->user->organization;
                    Mail::to($depositor->notifiableUsersEmails())->queue(new WithdrawDeposit([
                        'message' => $message,
                        'user_type' => 'Depositor'
                    ]));
                }

                notify([
                    'type' => 'DEPOSIT WITHDRAWN',
                    'details' => $message,
                    'date_sent' => getUTCDateNow(true),
                    'status' => 'ACTIVE',
                    'organization_id' => $bank->id
                ]);
            } catch (\Exception $exception) {
            }

            $response = array("success" => true, "message" => "Deposit withdrawn successfully", "data" => []);
            return response()->json($response, 200);
        }

        $response = array("success" => false, "message" => "Deposit can not be withdrawn", "data" => []);
        return response()->json($response, 400);
    }
}

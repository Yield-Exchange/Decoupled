<?php

namespace App\Http\Controllers\Dashboard\Bank;

use App\Constants;
use App\CustomEncoder;
use App\Data\BankData;
use App\Http\Controllers\Controller;
use App\Mail\AdminMail;
use App\Mail\Bank\CreateGicMail;
use App\Models\Chat;
use App\Models\Deposit;
use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Mail\AdminMails;
use App\Mail\BankMails;
use App\Mail\DepositorMails;

class PendingDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.bank');
    }

    public function createGic(Request $request, $deposit_id)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/pending-deposits/create-gic-access')) {
            return redirect()->to('access-denied');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Bank Create GIC");
        $deposit = Deposit::find(CustomEncoder::urlValueDecrypt($deposit_id));
        if (!$deposit) {
            systemActivities(Auth::id(), json_encode($request->query()), "Bank Create GIC, Deposit not found");
            alert()->error("Deposit not found");
            return redirect()->back();
        }

        if ($deposit->status != 'PENDING_DEPOSIT') {
            alert()->error("Deposit not found");
            return redirect()->back();
        }

        $organization = $deposit->offer->invited->depositRequest->organization;

        $submitRoute = route('bank.confirm-activate-deposit');
        $fromPage = $request->has('fromPage') ? url($request->fromPage) : '';
        $permittedSubmitButton = $user->userCan('bank/pending-deposits/create-gic-submit-button');
        return view('dashboard.bank.create-gic', compact('deposit', 'organization', 'submitRoute', 'fromPage', 'permittedSubmitButton'));
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/pending-deposits/page-access')) {
            return redirect()->to('access-denied');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Bank Pending Deposits");
        return view('dashboard.bank.pending-deposits');
    }
    public function getPendingDeposits(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/pending-deposits/page-access')) {
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
            ->whereIn('deposits.status', ['PENDING_DEPOSIT']);
        // ->select('deposits.*');
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
                $contracts->whereBetween("depositor_requests.term_length",  array_map('intval', $termlenobject));
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
        $contracts = $contracts->select('deposits.*');
        $contracts = $contracts->paginate(10);


        $contracts->getCollection()->transform(function ($record) {
            $record->encoded_offer_id = CustomEncoder::urlValueEncrypt($record->offer_id);
            $record->encoded_deposit_id = CustomEncoder::urlValueEncrypt($record->id);
            $record->offer->rate_held_until = changeDateFromUTCtoLocal($record->offer->rate_held_until);
            $record->offer->invited->depositRequest->date_of_deposit = changeDateFromUTCtoLocal($record->offer->invited->depositRequest->date_of_deposit);
           
            return $record;
        });

        return response()->json($contracts);
    }

    public function getData(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/pending-deposits/page-access')) {
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

        BankData::pendingDepositData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                "reference_no",
                "depositor_name",
                "amount",
                "product",
                "investment_period",
                "interest_rate",
                //                "rate_held_until",
                "action"
            ];

            if (!empty($searchValue)) {
                $search_is_date = false;
                try {
                    try {
                        $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                        $rate_held_until_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d H:i"), Constants::DATE_FORMAT, Constants::DATE_TIME_FORMAT_NO_SECONDS);
                    } catch (\Exception $exception) {
                        $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                        $rate_held_until_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT, Constants::DATE_FORMAT);
                    }

                    $data = $data->where('offers.rate_held_until', 'like', '%' . $rate_held_until_in_utc . '%');
                    $search_is_date = true;
                } catch (\Exception $exception) {
                }

                if (!$search_is_date) {
                    $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                        $query->where('deposits.reference_no', 'like', '%' . $searchValue . '%');
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
                                case 'interest_rate':
                                    $system_setting = getSystemSettings('prime_rate');
                                    $prime_rate = $system_setting->value;
                                    $searchValue = remove00AndPercentInterestRate($searchValue);
                                    if ($searchValue > 0) {
                                        $query->orWhere(DB::raw("CASE WHEN depositor_requests.term_length_type='HISA' AND offers.rate_type='VARIABLE' THEN 
                                    (CASE WHEN offers.rate_operator='-' THEN $prime_rate - COALESCE(offers.fixed_rate,0) ELSE $prime_rate + COALESCE(offers.fixed_rate,0) END)
                                    ELSE offers.interest_rate_offer END"), "like", '%' . $searchValue . '%');
                                    }
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
                $data = $data->orderBy('deposits.reference_no', 'ASC');
            } else {
                switch ($columnName) {
                    case 'rate_held_until':
                        $data = $data->orderBy('offers.rate_held_until', strtoupper($columnSortOrder));
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
                    case 'reference_no':
                    default:
                        $data = $data->orderBy('deposits.reference_no', strtoupper($columnSortOrder));
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
                $depositRequest = $record->offer->invited->depositRequest;

                if ($record['rate_type'] != 'VARIABLE') {
                    $interest_rate_offer = formatInterest($record["interest_rate_offer"]);
                } else {
                    $interest_rate_offer = formatInterest($record['fixed_rate'], true, $record['rate_operator'], true);
                }

                //                $offer_id_encoded = CustomEncoder::urlValueEncrypt($record->offer->id);
                $deposit_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
                $data_arr[] = array(
                    "reference_no" => $record->reference_no,
                    "depositor_name" => $record->depositor_name,
                    "amount" => $depositRequest->currency . ' ' . number_format($record->offered_amount),
                    "product" => $depositRequest->product->description,
                    "investment_period" => $depositRequest->term_length_type == "HISA" ? "-" : $depositRequest->term_length . ' ' . ucwords(strtolower($depositRequest->term_length_type)),
                    "interest_rate" => $interest_rate_offer,
                    //"rate_held_until" => $record->rate_held_until ? changeDateFromUTCtoLocal($record->rate_held_until,Constants::DATE_TIME_FORMAT_NO_SECONDS) : '-',
                    "rate_held_until" => $record->rate_held_until ? timeIn_12_24_format($record->rate_held_until) : '-',
                    "action" => $user->userCan('universal/chats/page-access') ? '<a href="' . route('deposit.chat.room', $deposit_id_encoded) . '?fromPage=bank-pending-deposits" class="btn custom-secondary round mmy_btn btn-block" style="display: inline-block;">Chat ' . $badge_notify_chat1 . '</a>' : '',
                    "action2" => $user->userCan('bank/pending-deposits/create-gic-access') ? '<a href="' . route('bank.create-gic', $deposit_id_encoded) . '?fromPage=bank-pending-deposits" class="btn custom-primary round mmy_btn btn-block">Create ' . (strpos($depositRequest->product->description, 'High Interest Savings') !== false ? 'HISA' : 'GIC') . '</a>' : '',
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

    public function activateDeposit(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/pending-deposits/create-gic-submit-button')) {
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

                $offer = $contract->offer;
                if ($request->filled('interest_rate')) {
                    archiveTable($offer->id, "offers", auth()->id(), "interest_rate_offer updated");
                    // $offer->offer_status = "COMPLETED";
                    // $offer->save();

                    $offer->interest_rate_offer = $request->interest_rate;
                    $offer->save();
                }

                $deposit_request = $offer->invited->depositRequest;
                $deposit_offers = Offer::whereHas('invited', function ($query) use ($deposit_request) {
                    $query->where('depositor_request_id', $deposit_request->id);
                })->whereIn('offer_status', ['ACTIVE', 'SELECTED'])->get();

                if ($deposit_offers->count() == 0) {
                    $deposit_request->request_status = 'COMPLETED';
                    $deposit_request->save();
                }

                \Illuminate\Support\Facades\DB::commit();
            } catch (\Exception $exception) {
                \Illuminate\Support\Facades\DB::rollBack();
                $timestamp = time();
                Log::error($timestamp . ': ' . $exception->getTraceAsString());
                loginActivities("User sign up attempt failed, check with the developer. Error No: " . $timestamp, $request->server('HTTP_USER_AGENT'), 0);
                return response()->json(["message" => 'Unable to perform the operation, ' . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => [], "success" => false], 409);
            }

            $message  = "Deposit ID " . $contract->reference_no . " fund received date has been provided successfully and therefore marked as complete";
            $deposit_request_organization = $deposit_request->organization;
            try {

                Mail::to($deposit_request_organization->notifiableUsersEmails())->send(new DepositorMails([ /// depositor
                    'subject' => 'GIC Activated',
                    'request_details' => ['deposit_request' => $deposit_request, 'deposit_details' => $contract]
                ], 'activate_gic'));

                Mail::to($contract->offer->invited->organization->notifiableUsersEmails())->send(new BankMails([ /// bank
                    'subject' => 'GIC Activated',
                    'message' => ' ',
                    'header' => 'GIC has been created',
                    'request_details' => ['deposit_request' => $deposit_request, 'deposit_details' => $contract]
                ], 'activate_gic'));

                Mail::to(getAdminEmails())->queue(new AdminMails([
                    'message' => 'The following GIC has been set up',
                    'header' => 'GIC is All Set',
                    'request_details' => ['deposit_request' => $deposit_request, 'deposit_details' => $contract],
                    'subject' => 'GIC Activated'
                ], "activate_gic"));

                notify([
                    'type' => 'CONTRACT DEPOSIT DATE UPDATED',
                    'details' => $message,
                    'date_sent' => getUTCDateNow(true),
                    'status' => 'ACTIVE',
                    'organization_id' => $deposit_request_organization->id
                ]);
            } catch (\Exception $exception) {
            }

            $deposit_type = $contract->offer->invited->depositRequest->term_length_type != "HISA" ? "GIC" : "HISA";
            loginActivities("", $request->server('HTTP_USER_AGENT'), 0);
            $url = $user->organization->type && $user->organization->type == "BANK" ? route('bank.active-deposits') : route('active-deposits');
            $response = array("success" => true, "message" => "Your $deposit_type is now showing in the Active Deposit page.", "message_title" => $deposit_type . " has been created.", "data" => [], "url" => $url);
            return response()->json($response, 200);
        }

        $response = array("success" => false, "message" => "Unable to perform the operation, " . Constants::RESPONSE_MESSAGE_CONTACT_US, "data" => []);
        return response()->json($response, 400);
    }
}

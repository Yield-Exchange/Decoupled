<?php

namespace App\Http\Controllers\Dashboard\Depositor;

use App\Constants;
use App\CustomEncoder;
use App\Data\DepositorData;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\DepositRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.depositor');
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/history/page-access')) {
            return view('dashboard.403');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Depositor History");
        return view('dashboard.depositor.history.index');
    }

    function newDepositsHistoryDate(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/history/page-access')) {
            $response = array(
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            );
            return response()->json($response);
        }

        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            //            ->join('users','users.id','=','invited.invited_user_id')
            ->join('organizations', 'invited.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->whereHas('offer.invited.organization')
            ->where('depositor_requests.organization_id', \auth()->user()->organization->id)
            ->whereIn('deposits.status', ['MATURED', 'WITHDRAWN', 'INCOMPLETE', 'EARLY_REDEMPTION']);

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
                    $contracts->where("depositor_requests.term_length", "<=", (int) $termlenobject[1]);
                }
            }
        }

        if ($request->filled("products")) {
            $products = explode(",", $request->products);
            $contracts->whereIn("products.description", $products);
        }
        if ($request->filled("financialOrganizations")) {
            $orgs = explode(",", $request->financialOrganizations);
            $contracts->whereIn("organizations.name", $orgs);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $searchColumns = [
                'deposits.status', 'deposits.reference_no', 'offers.interest_rate_offer', 'deposits.offered_amount', 'products.description', 'organizations.name'
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


        $contracts = $contracts->select('deposits.*');
        $contracts = $contracts->paginate(10);


        $contracts->getCollection()->transform(function ($record) {
            $record->encoded_offer_id = CustomEncoder::urlValueEncrypt($record->offer_id);
            return $record;
        });

        return response()->json($contracts);
    }

    public function getDepositHistoryData(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/history/page-access')) {
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

        DepositorData::depositHistoryData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                //                "date",
                "reference_no",
                "gic_number",
                "bank_name",
                "offered_amount",
                "product_name",
                "term_length",
                "interest_rate_offer",
                "status"
            ];

            if (!empty($searchValue)) {

                $search_is_date = false;
                try {
                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                    $timezone_offset = timezoneOffsetFromUTC(\auth()->user()->timezone);
                    $data = $data->where(function ($query) use ($date, $timezone_offset) {
                        $query->where(function ($query) use ($date, $timezone_offset) {
                            $query->whereNull('deposits.modified_at');
                            $query->whereRaw("DATE(CONVERT_TZ(deposits.created_at,'+00:00','" . $timezone_offset . "')) = '" . $date->format("Y-m-d") . "'");
                        });
                        $query->orWhere(function ($query) use ($date, $timezone_offset) {
                            $query->whereNotNull('deposits.modified_at');
                            $query->whereRaw("DATE(CONVERT_TZ(deposits.modified_at,'+00:00','" . $timezone_offset . "')) = '" . $date->format("Y-m-d") . "'");
                        });
                    });
                    $search_is_date = true;
                } catch (\Exception $exception) {
                }

                if (!$search_is_date) {
                    $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                        $query->where('deposits.reference_no', 'like', '%' . $searchValue . '%');
                        foreach ($search_columns as $search_column) {
                            switch ($search_column) {
                                case 'offered_amount':
                                    $searchValue = str_replace(",", "", $searchValue);
                                    $query->orWhere('deposits.offered_amount', 'like', '%' . $searchValue . '%')->orWhere('depositor_requests.currency', 'like', '%' . $searchValue . '%')
                                        ->orWhere(\DB::raw("CONCAT(depositor_requests.currency, ' ', deposits.offered_amount)"), 'like', '%' . $searchValue . '%');
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
                                        (CASE WHEN offers.rate_operator='-' THEN $prime_rate - COALESCE(offers.fixed_rate,0) ELSE $prime_rate + COALESCE(offers.fixed_rate,0) END)
                                        ELSE offers.interest_rate_offer END"), "like", '%' . $searchValue . '%');
                                    }
                                    break;
                                case 'status':
                                    $searchValue = strtoupper(str_replace(" ", "_", $searchValue));
                                    $query->orWhere('deposits.status', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'bank_name':
                                    $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                                    break;
                            }
                        }
                    });
                }
            }

            if (!$columnIndex && !is_numeric($columnIndex)) {
                $data = $data->orderBy('deposits.modified_at', 'DESC')->orderBy('deposits.created_at', strtoupper($columnSortOrder));;
            } else {
                switch ($columnName) {
                    case 'interest_rate_offer':
                        $data = $data->orderBy('deposits.interest_rate_offer', strtoupper($columnSortOrder));
                        break;
                    case 'reference_no':
                        $data = $data->orderBy('deposits.reference_no', strtoupper($columnSortOrder));
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
                    case 'gic_number':
                        $data = $data->orderBy('deposits.gic_number', strtoupper($columnSortOrder));
                        break;
                    case 'date':
                        $data = $data->orderBy('deposits.modified_at', strtoupper($columnSortOrder))
                            ->orderBy('deposits.created_at', strtoupper($columnSortOrder));
                        break;
                    case 'term_length':
                        $data = $data->orderBy('depositor_requests.term_length', strtoupper($columnSortOrder));
                        break;
                    case 'status':
                        $data = $data->orderBy('deposits.status', strtoupper($columnSortOrder));
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
                $encoded_offer_id = CustomEncoder::urlValueEncrypt($record->offer_id);
                $action = '';
                if ($user->userCan('depositor/history/view-button')) {
                    $action = '<a href="' . route('depositor.offer-summary', $encoded_offer_id) . '?fromPage=depositor-history" class="btn custom-primary round">View</a>';
                }

                $data_arr[] = array(
                    "date" => $record->modified_at ? changeDateFromUTCtoLocal($record->modified_at, Constants::DATE_FORMAT) : changeDateFromUTCtoLocal($record->created_at, Constants::DATE_FORMAT),
                    "reference_no" => $record->reference_no,
                    "gic_number" => $record->gic_number ? $record->gic_number : '-',
                    "bank_name" => $record->bank_name,
                    "offered_amount" => $record->currency . ' ' . number_format($record->offered_amount),
                    "product_name" => $record->product_name,
                    "term_length" => $record->term_length_type == "HISA" ? "-" : $record->term_length . ' ' . ucwords(strtolower($record->term_length_type)),
                    "interest_rate_offer" => $interest_rate_offer,
                    "status" => ucwords(strtolower(str_replace("_", " ", $record->status))),
                    "action" => $action
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


    public function getRequestHistoryData2(Request $request)
    {

        $utc_date_now = getUTCTimeNow()->format(Constants::DATE_TIME_FORMAT);
        $contracts = DepositRequest::select([
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
                $query->whereIn('depositor_requests.request_status', ['EXPIRED', 'NO_OFFERS_RECEIVED', 'WITHDRAWN','DECLINED'])
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

        if ($request->filled("offer")) {
            $deposit_amount = explode(",", $request->offer);
            if (($deposit_amount[0] > 0) && ($deposit_amount[1] > 0)) {
                $contracts->whereBetween("depositor_requests.amount", $deposit_amount);
            } else {
                if ($deposit_amount[0] > 0) {
                    $contracts->where("depositor_requests.amount", ">=", $deposit_amount[0]);
                }
                if ($deposit_amount[1] > 0) {
                    $contracts->where("depositor_requests.amount", "<=", $deposit_amount[1]);
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
                    $contracts->where("depositor_requests.term_length", "<=", (int) $termlenobject[1]);
                }
            }
        }

        if ($request->filled("products")) {
            $products = explode(",", $request->products);
            $contracts->whereIn("products.description", $products);
        }
        if ($request->filled("status")) {
            $status = explode(",", $request->status);
            foreach ($status as $key => $value) {
                if ($value == "No Offers Received") {
                    $status[$key] = "NO_OFFERS_RECEIVED";
                }
            }
            $contracts->whereIn("request_status", $status);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $searchColumns = [
                'depositor_requests.request_status', 'depositor_requests.reference_no',  'depositor_requests.amount', 'products.description'
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


        // $size = with(clone$contracts)->get()->count();
        // $total_amount['USD'] = with(clone$contracts)->where('currency', 'USD')->sum('amount');
        // $total_amount['CAD'] = with(clone$contracts)->where('currency', 'CAD')->sum('amount');

        $data = $contracts;
        $data = $data->orderBy('depositor_requests.modified_date', 'DESC')
            ->orderBy('depositor_requests.created_date', 'DESC')->paginate(10);
        $data->getCollection()->transform(function ($record) {
            // $record->encoded_offer_id = CustomEncoder::urlValueEncrypt($record->offer_id);
            $record->encoded_deposit_id = CustomEncoder::urlValueEncrypt($record->id);

            return $record;
        });

        return response()->json($data);
    }

    public function getRequestHistoryData(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/history/page-access')) {
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

        DepositorData::requestHistoryData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue, $request) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                //                "date",
                "reference_no",
                "request_amount",
                "product_name",
                "term_length",
                "requested_rate",
                "status"
            ];

            if (!empty($searchValue)) {

                $search_is_date = false;
                try {
                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                    $timezone_offset = timezoneOffsetFromUTC(\auth()->user()->timezone);
                    $data = $data->where(function ($query) use ($date, $timezone_offset) {
                        $query->where(function ($query) use ($date, $timezone_offset) {
                            $query->whereNull('depositor_requests.modified_date');
                            $query->whereRaw("DATE(CONVERT_TZ(depositor_requests.created_date,'+00:00','" . $timezone_offset . "')) = '" . $date->format("Y-m-d") . "'");
                        });
                        $query->orWhere(function ($query) use ($date, $timezone_offset) {
                            $query->whereNotNull('depositor_requests.modified_date');
                            $query->whereRaw("DATE(CONVERT_TZ(depositor_requests.modified_date,'+00:00','" . $timezone_offset . "')) = '" . $date->format("Y-m-d") . "'");
                        });
                    });
                    $search_is_date = true;
                } catch (\Exception $exception) {
                }

                if (!$search_is_date) {
                    $data = $data->where(function ($query) use ($searchValue, $search_columns, $data, $request) {
                        $query->where('depositor_requests.requested_rate', 'like', '%' . $searchValue . '%');
                        foreach ($search_columns as $search_column) {
                            switch ($search_column) {
                                case 'request_amount':
                                    $searchValue = str_replace(",", "", $searchValue);
                                    $query->orWhere('depositor_requests.amount', 'like', '%' . $searchValue . '%')->orWhere('depositor_requests.currency', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(depositor_requests.currency, ' ', depositor_requests.amount)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'product_name':
                                    $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'term_length':
                                    $query->orWhere($search_column, 'like', '%' . $searchValue . '%')->orWhere('term_length_type', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(term_length, ' ', term_length_type)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'status':
                                    $searchValue = strtoupper(str_replace(" ", "_", $searchValue));
                                    $query->orWhere('depositor_requests.request_status', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'reference_no':
                                    $query->orWhere('depositor_requests.reference_no', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'requested_rate':
                                    $searchValue = remove00AndPercentInterestRate($searchValue);
                                    $query->orWhere('depositor_requests.requested_rate', 'like', '%' . $searchValue . '%');
                                    break;
                            }
                        }
                    });
                }
            }

            if (!$columnIndex && !is_numeric($columnIndex)) {
                $data = $data->orderBy('depositor_requests.modified_date', 'DESC')->orderBy('depositor_requests.created_date', strtoupper($columnSortOrder));;
            } else {
                switch ($columnName) {
                    case 'requested_rate':
                        $data = $data->orderBy('depositor_requests.requested_rate', strtoupper($columnSortOrder));
                        break;
                    case 'reference_no':
                        $data = $data->orderBy('depositor_requests.reference_no', strtoupper($columnSortOrder));
                        break;
                    case 'request_amount':
                        $data = $data->orderBy('depositor_requests.amount', strtoupper($columnSortOrder));
                        break;
                    case 'product_name':
                        $data = $data->orderBy('products.description', strtoupper($columnSortOrder));
                        break;
                    case 'date':
                        $data = $data->orderBy('depositor_requests.modified_date', strtoupper($columnSortOrder))
                            ->orderBy('depositor_requests.created_date', strtoupper($columnSortOrder));
                        break;
                    case 'term_length':
                        $data = $data->orderBy('depositor_requests.term_length', strtoupper($columnSortOrder));
                        break;
                    case 'status':
                        $data = $data->orderBy('depositor_requests.request_status', strtoupper($columnSortOrder));
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
                $request_id = CustomEncoder::urlValueEncrypt($record->id);
                $action = '';
                if ($user->userCan('depositor/history/review-offers')) {
                    $action = $record->total_offers > 0 ? '<a href="' . route('depositor.summary-request-review_offers_summary', ['deposit_id' => 'null', 'request_id' => $request_id]) . '?fromPage=depositor-history" class="btn custom-primary round">Review offers</a>' : '-';
                }
                $data_arr[] = array(
                    "date" => $record->modified_date ? changeDateFromUTCtoLocal($record->modified_date, Constants::DATE_FORMAT) : changeDateFromUTCtoLocal($record->created_date, Constants::DATE_FORMAT),
                    "reference_no" => $record->reference_no,
                    "request_amount" => $record->currency . ' ' . number_format($record->amount),
                    "product_name" => $record->product_name,
                    "term_length" => $record->term_length_type == "HISA" ? "-" : $record->term_length . ' ' . ucwords(strtolower($record->term_length_type)),
                    // "requested_rate" => formatInterest($record->requested_rate),
                    "total_offers" => $record->total_offers,
                    "status" => $record->request_status == "ACTIVE" ? (!empty($record->invited) ? 'Expired' : 'No Offers Received') : ucwords(strtolower(str_replace("_", " ", $record->request_status))),
                    "action" => $action,
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
}

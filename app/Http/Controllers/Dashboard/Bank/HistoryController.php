<?php

namespace App\Http\Controllers\Dashboard\Bank;

use App\Constants;
use App\CustomEncoder;
use App\Data\BankData;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Offer;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.bank');
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/history/page-access')) {
            return redirect()->to('access-denied');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Bank History");
        return view('dashboard.bank.history.index');
    }


    public function getOfferHistory(Request $request)
    {
        $user = \auth()->user();
        $user = \auth()->user();
        if (!$user->userCan('bank/history/page-access')) {
            $response = ["message" => "you cannot access the in_progress deposits"];
            return response()->json($response);
        }

        $requests = Offer::join('invited', function ($join) {
            $join->on('invited.id', '=', 'offers.invitation_id');
            $join->where('invited.organization_id', \auth()->user()->organization->id);
        })->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            ->join('users', 'users.id', '=', 'depositor_requests.user_id')
            ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->leftJoin('products', 'products.id', '=', 'depositor_requests.product_id')
            ->whereIn('offers.offer_status', ['NOT_SELECTED', 'EXPIRED', 'REQUEST_WITHDRAWN', 'OFFER_WITHDRAWN','DECLINED']);

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
        if ($request->filled('status')) {
            $status = explode(",",$request->status);
            $requests->where('offers.offer_status',$status);
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

    public function getDepositHistory(Request $request)
    {

        $contracts = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            //            ->join('users','users.id','=','invited.invited_user_id')
            ->join('organizations', 'depositor_requests.organization_id', '=', 'organizations.id')
            ->leftJoin('demographic_organization_data', 'demographic_organization_data.organization_id', '=', 'organizations.id')
            ->join('products', 'products.id', '=', 'depositor_requests.product_id')
            ->whereHas('offer.invited.organization')
            ->where('invited.organization_id', \auth()->user()->organization->id)
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
                    $contracts->whereBetween("depositor_requests.term_length", array_map('intval',$termlenobject));
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
    
    
            $contracts = $contracts->select([
                'deposits.*',
                'invited.depositor_request_id',
                'invited.invited_user_id',
                'depositor_requests.term_length as term_length',
                'depositor_requests.term_length_type as term_length_type',
                'depositor_requests.amount',
                'depositor_requests.currency as currency',
                'depositor_requests.user_id',
                'depositor_requests.product_id',
                'offers.interest_rate_offer',
                'offers.rate_type as rate_type',
                'offers.prime_rate',
                'offers.rate_operator',
                DB::raw("organizations.name as depositor_name"),
                'offers.fixed_rate',
                DB::raw("products.description as product_name")
            ]);
            $contracts = $contracts->paginate(10);

        $contracts->getCollection()->transform(function ($record) {
            $record->offer_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
            return $record;
        });
        return response()->json($contracts);
    }

    public function getOffersHistoryData(Request $request)
    {

        $user = \auth()->user();
        if (!$user->userCan('bank/history/page-access')) {
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

        BankData::offerHistoryData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                "reference_no",
                "date",
                "depositor_name",
                "province",
                "amount",
                "product",
                "investment_period",
                "interest_rate",
                "status",
                "action"
            ];

            if (!empty($searchValue)) {
                $search_is_date = false;
                try {
                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                    $timezone_offset = timezoneOffsetFromUTC(\auth()->user()->timezone);
                    $data = $data->where(function ($query) use ($date, $timezone_offset) {
                        $query->where(function ($query) use ($date, $timezone_offset) {
                            $query->whereNull('offers.modified_date');
                            $query->whereRaw("DATE(CONVERT_TZ(offers.created_date,'+00:00','" . $timezone_offset . "')) = '" . $date->format("Y-m-d") . "'");
                        });
                        $query->orWhere(function ($query) use ($date, $timezone_offset) {
                            $query->whereNotNull('offers.modified_date');
                            $query->whereRaw("DATE(CONVERT_TZ(offers.modified_date,'+00:00','" . $timezone_offset . "')) = '" . $date->format("Y-m-d") . "'");
                        });
                    });
                    $search_is_date = true;
                } catch (\Exception $exception) {
                }

                if (!$search_is_date) {
                    $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                        $query->where('depositor_requests.reference_no', 'like', '%' . $searchValue . '%');
                        foreach ($search_columns as $search_column) {
                            switch ($search_column) {
                                case 'depositor_name':
                                    $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'amount':
                                    $searchValue = str_replace(",", "", $searchValue);
                                    $query->orWhere('depositor_requests.amount', 'like', '%' . $searchValue . '%')->orWhere('depositor_requests.currency', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(depositor_requests.currency, ' ', depositor_requests.amount)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'province':
                                    $query->orWhere('demographic_organization_data.province', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'product':
                                    $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
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
                                case 'status':
                                    $searchValue = strtoupper(str_replace(" ", "_", $searchValue));
                                    $query->orWhere('offers.offer_status', 'like', '%' . $searchValue . '%');
                                    break;
                            }
                        }
                    });
                }
            }

            if (!$columnIndex && !is_numeric($columnIndex)) {
                $data = $data->orderBy('depositor_requests.reference_no', 'ASC');
            } else {
                switch ($columnName) {
                    case 'date':
                        $data = $data->orderBy('offers.modified_date', strtoupper($columnSortOrder))->orderBy('offers.created_date', strtoupper($columnSortOrder));
                        break;
                    case 'amount':
                        $data = $data->orderBy('depositor_requests.amount', strtoupper($columnSortOrder));
                        break;
                    case 'product':
                        $data = $data->orderBy('products.description', strtoupper($columnSortOrder));
                        break;
                    case 'depositor_name':
                        $data = $data->orderBy('organizations.name', strtoupper($columnSortOrder));
                        break;
                    case 'province':
                        $data = $data->orderBy('demographic_organization_data.province', strtoupper($columnSortOrder));
                        break;
                    case 'investment_period':
                        $data = $data->orderBy('depositor_requests.term_length', strtoupper($columnSortOrder));
                        break;
                    case 'interest_rate':
                        $data = $data->orderBy('offers.interest_rate_offer', strtoupper($columnSortOrder));
                        break;
                    case 'status':
                        $data = $data->orderBy('offers.offer_status', strtoupper($columnSortOrder));
                        break;
                    default:
                        $data = $data->orderBy('depositor_requests.reference_no', strtoupper($columnSortOrder));
                }
            }

            $totalRecordswithFilter = with(clone $data)->get()->count();

            $data = $data->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();

            $user = \auth()->user();
            foreach ($data as $record) {
                $depositRequest = $record->invited->depositRequest;

                if ($record['rate_type'] != 'VARIABLE') {
                    $interest_rate_offer = formatInterest($record["interest_rate_offer"]);
                } else {
                    $interest_rate_offer = formatInterest($record['fixed_rate'], true, $record['rate_operator'], true);
                }

                $offer_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
                $organization = $depositRequest->organization;
                $data_arr[] = array(
                    "reference_no" => $depositRequest->reference_no,
                    "date" => $record->modified_date ? changeDateFromUTCtoLocal($record->modified_date, Constants::DATE_FORMAT)
                        : changeDateFromUTCtoLocal($record->created_date, Constants::DATE_FORMAT),
                    "depositor_name" => $organization->name,
                    "province" => $organization->demographicData->province,
                    "amount" => $depositRequest->currency . ' ' . number_format($depositRequest->amount),
                    "product" => $depositRequest->product->description,
                    "investment_period" => $depositRequest->term_length_type == "HISA" ? "-" : $depositRequest->term_length . ' ' . ucwords(strtolower($depositRequest->term_length_type)),
                    "interest_rate" => $interest_rate_offer,
                    "status" => ucwords(str_replace("_", " ", strtolower($record->offer_status))),
                    "action" => $user->userCan('View-Offer-Summary') ? '<a href="' . route('bank.offer-summary', $offer_id_encoded) . '?fromPage=bank-history" class="btn custom-primary round mmy_btn btn-block">View</a>' : ''
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

    public function getDepositHistoryData(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/history/page-access')) {
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

        BankData::depositHistoryData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                "reference_no",
                "date",
                "depositor_name",
                "province",
                "amount",
                "product",
                "investment_period",
                "interest_rate",
                "status",
                "action"
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
                                case 'depositor_name':
                                    $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'amount':
                                    $searchValue = str_replace(",", "", $searchValue);
                                    $query->orWhere('deposits.offered_amount', 'like', '%' . $searchValue . '%')->orWhere('depositor_requests.currency', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(depositor_requests.currency, ' ', deposits.offered_amount)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'province':
                                    $query->orWhere('demographic_organization_data.province', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'product':
                                    $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'status':
                                    $searchValue = strtoupper(str_replace(" ", "_", $searchValue));
                                    $query->orWhere('deposits.status', 'like', '%' . $searchValue . '%');
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
                $data = $data->orderBy('deposits.reference_no', 'ASC');
            } else {
                switch ($columnName) {
                    case 'date':
                        $data = $data->orderBy('deposits.modified_at', strtoupper($columnSortOrder))->orderBy('id', strtoupper($columnSortOrder));
                        break;
                    case 'amount':
                        $data = $data->orderBy('depositor_requests.amount', strtoupper($columnSortOrder));
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
                        $data = $data->orderBy('deposits.reference_no', strtoupper($columnSortOrder));
                        break;
                    case 'status':
                        $data = $data->orderBy('deposits.status', strtoupper($columnSortOrder));
                        break;
                    case 'province':
                        $data = $data->orderBy('demographic_organization_data.province', strtoupper($columnSortOrder));
                        break;
                    default:
                        $data = $data->orderBy('deposits.reference_no', strtoupper($columnSortOrder));
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

                $offer_id_encoded = CustomEncoder::urlValueEncrypt($record->offer->id);
                $organization = $depositRequest->organization;
                $data_arr[] = array(
                    "reference_no" => $record->reference_no,
                    "date" => $record->modified_at ? changeDateFromUTCtoLocal($record->modified_at, Constants::DATE_FORMAT) :
                        changeDateFromUTCtoLocal($record->created_at, Constants::DATE_FORMAT),
                    "depositor_name" => $organization->name,
                    "province" => $organization->demographicData->province,
                    "amount" => $depositRequest->currency . ' ' . number_format($record->offered_amount),
                    "product" => $depositRequest->product->description,
                    "investment_period" => $depositRequest->term_length_type == "HISA" ? "-" : $depositRequest->term_length . ' ' . ucwords(strtolower($depositRequest->term_length_type)),
                    "interest_rate" => $interest_rate_offer,
                    "status" => ucwords(str_replace("_", " ", strtolower($record->status))),
                    "action" => $user->userCan('bank/history/view-button') ? '<a href="' . route('bank.deposit-summary', $offer_id_encoded) . '?fromPage=bank-history" class="btn custom-primary round btn-block mmy_btn">View</a>' : ''
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

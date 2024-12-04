<?php

namespace App\Http\Controllers\Dashboard\Depositor;

use App\Constants;
use App\CustomEncoder;
use App\Data\DepositorData;
use App\Http\Controllers\Controller;
use App\Mail\AdminMail;
use App\Mail\AdminMails;
use App\Mail\Bank\AwardedDeposit;
use App\Mail\Bank\OfferAccepted;
use App\Mail\Depositor\AwardDeposit;
use App\Mail\Depositor\WithdrawDeposit;
use App\Models\CounterOffer;
use App\Models\Deposit;
use App\Models\DepositRequest;
use App\Models\InvitedBank;
use App\Models\Campaign;
use App\Models\Organization;
use App\Models\Offer;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\BankMails;
use App\Mail\DepositorMails;
use App\Models\Product;
use App\Http\Resources\PostRequestOffersResource;
use App\Models\SystemInterestRate;
use App\Models\SystemSetting;

class ReviewOffersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.depositor');
    }
    public function getRateTypes()
    {
        $interestrates = SystemSetting::where("status", "ACTIVE")->where("setting_type", "rate")->select("system_settings.*", "system_settings.value as rate_value")->get();
        return $interestrates;
    }
    public function getOfferCounters(Request $request)
    {
        $allcounters = CounterOffer::where("offer_id", CustomEncoder::urlValueDecrypt($request->offer_id))->orderBy("id", "DESC")->limit(2)->get();
        $prime = SystemSetting::where("key", "prime_rate")->first();
        return ['offers' => $allcounters, 'prime' => $prime->value];
    }
    public function getReasonsForWithdraw()
    {
        $reasons = DB::table('request_withdrawal_reasons')->pluck("reason");
        return  $reasons;
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/page-access')) {
            return view('dashboard.403');
        }
        systemActivities(Auth::id(), json_encode($request->query()), "Depositor Review Offers");
        $product_types = Product::select(['description', 'id'])->get();
        return view('dashboard.depositor.new-post-request.review-offers-new', compact('product_types'));
    }

    public function exportReviewOffers(Request $request, $deposit_id)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/page-access')) {
            return redirect('access-denied');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Review Offer Export");

        $deposit_request = DepositRequest::find(CustomEncoder::urlValueDecrypt($deposit_id));
        if (!$deposit_request) {
            alert()->error("Deposit request not found");
            return redirect()->back();
        }

        $offers = Offer::whereHas('invited.depositRequest', function ($query) use ($deposit_request) {
            $query->where('id', $deposit_request->id);
        })->get();

        $pdf = PDF::loadView('dashboard.depositor.exports.review_offers', compact('deposit_request', 'user', 'offers'));
        //        $pdf->setPaper('a4', 'landscape');
        return $pdf->download(" Review_Offer_" . date(Constants::DATE_TIME_FORMAT_FOR_URL_NAMES) . ".pdf");
    }

    public function getOffers(Request $request)
    {
        $offers = DepositorData::reviewOfferData($request);
        return $offers;
    }
    public function getOffersNew(Request $request)
    {
        $offers = DepositorData::reviewOfferDataNew($request);
        return $offers;
    }
    public function getData(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/page-access')) {
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

        DepositorData::reviewOfferData(null, function ($data) use ($start, $rowperpage, $draw, $columnIndex, $columnName, $columnSortOrder, $searchValue) {
            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                //               "closing_date_time",
                "product_name",
                "amount",
                "term_length",
                "total_bids",
                "highest_rate",
                "lowest_rate"
            ];

            if (!empty($searchValue)) {
                $search_is_date = false;
                try {
                    try {
                        $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                        $rate_held_until_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT, Constants::DATE_FORMAT);
                    } catch (\Exception $exception) {
                        $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                        $rate_held_until_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT, Constants::DATE_FORMAT);
                    }
                    $data = $data->where('closing_date_time', 'like', '%' . $rate_held_until_in_utc . '%');
                    $search_is_date = true;
                } catch (\Exception $exception) {
                }

                if (!$search_is_date) {
                    $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                        $query->where('depositor_requests.reference_no', 'like', '%' . $searchValue . '%');
                        foreach ($search_columns as $search_column) {
                            switch ($search_column) {
                                case 'amount':
                                    $searchValue = str_replace(",", "", $searchValue);
                                    $query->orWhere($search_column, 'like', '%' . $searchValue . '%')->orWhere('currency', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(currency, ' ', amount)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'product_name':
                                    $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
                                    break;
                                case 'term_length':
                                    $query->orWhere($search_column, 'like', '%' . $searchValue . '%')->orWhere('term_length_type', 'like', '%' . $searchValue . '%')
                                        ->orWhere(DB::raw("CONCAT(term_length, ' ', term_length_type)"), 'like', '%' . $searchValue . '%');
                                    break;
                                case 'highest_rate':
                                case 'lowest_rate':
                                    $searchValue = remove00AndPercentInterestRate($searchValue);
                                    $query->orWhere('offers.interest_rate_offer', 'like', '%' . $searchValue . '%');
                                    break;
                            }
                        }
                    });
                }
            }

            if (!$columnIndex && !is_numeric($columnIndex)) {
                $data = $data->orderBy('closing_date_time', 'DESC')->orderBy('depositor_requests.id', 'DESC');
            } else {
                switch ($columnName) {
                    case 'reference_no':
                        $data = $data->orderBy('depositor_requests.reference_no', strtoupper($columnSortOrder));
                        break;
                    case 'closing_date_time':
                    case 'term_length':
                    case 'amount':
                        $data = $data->orderBy($columnName, strtoupper($columnSortOrder));
                        break;
                    case 'product_name':
                        $data = $data->orderBy('products.description', strtoupper($columnSortOrder));
                        break;
                    case 'total_bids':
                        $data = $data->orderBy('total_offers', strtoupper($columnSortOrder));
                        break;
                    case 'highest_rate':
                        $data = $data->orderBy('max_interest_rate_offer', strtoupper($columnSortOrder));
                        break;
                    case 'lowest_rate':
                        $data = $data->orderBy('min_interest_rate_offer', strtoupper($columnSortOrder));
                        break;
                    default:
                        $data = $data->orderBy('depositor_requests.id', 'DESC');
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

                $highest_rate = '-';
                $lowest_rate = '-';
                if ($record->rate_type != 'VARIABLE') {
                    $highest_rate = formatInterest($record->max_interest_rate_offer);
                    $lowest_rate = formatInterest($record->min_interest_rate_offer);
                } else {
                    if (!empty($record->fixed_rate)) {
                        $highest_rate = formatInterest($record->fixed_rate, true, $record->rate_operator, true);
                        $lowest_rate = formatInterest($record->fixed_rate, true, $record->rate_operator, true);
                    }
                }

                $encoded_request_id = CustomEncoder::urlValueEncrypt($record->id);
                $withdraw_modal = '';

                $action = '<div class="list-icons">
                                <div class="list-icons-item dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-uu" style="position: absolute;z-index:999">';

                if ($user->userCan('depositor/review-offers/view-request')) {
                    $action .= '<a href="' . route('depositor.summary-request', $encoded_request_id) . '" class="dropdown-item">View Request</a>';
                }

                $action .= '<div class="dropdown-divider"></div>';

                if ($user->userCan('depositor/review-offers/edit-request')) {
                    if ($record->total_offers > 0) {
                        $action .= '<a class="dropdown-item" onclick="active_requests()">Edit Request</a>';
                    } else {
                        $action .= '<a href="' . url('edit-post-request/' . $encoded_request_id) . '" onclick="return editIt(this)" class="dropdown-item">Edit Request</a>';
                    }
                }

                $action .= '<div class="dropdown-divider"></div>';
                if ($user->userCan('depositor/review-offers/withdraw-request')) {
                    //$action .= ' <a href="javascript:void()" req-id="' . $encoded_request_id . '" onclick="return withdraw(this)" class="dropdown-item">Withdraw Request</a>';
                    $action .= '<a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal' . $encoded_request_id . '">Withdraw Request</a>';
                }
                $action .= '  <div class="dropdown-divider"></div>';
                if ($user->userCan('depositor/review-offers/invited-institutions')) {
                    $action .= '  <a href="' . route('depositor.summary-request-invited_institutions', $encoded_request_id) . '" class="dropdown-item">Invited institutions</a>';
                }
                $action .= ' </div>
                            </div>
                        </div>';

                if ($user->userCan('depositor/review-offers/withdraw-request')) {
                    $withdraw_modal = ' <div id="myModal' . $encoded_request_id . '" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form action="' . route('depositor.post-request-withdraw', ['request_id' => $encoded_request_id]) . '" class="withdraw-request-form" method="post">
                                     ' . csrf_field() . '
                                    <div class="modal-header">
                                        <h4 class="modal-title">Do you want to withdraw this request?</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                        <input type="hidden" name="req_id" value="' . $encoded_request_id . '">
                                            <div class="form-group col-12">
                                                <label>Reason for withdrawing the request</label>
                                                <select name="reason" class="form-control" required>
                                                        <option value="">Select the reason</option>';
                    $reasons = DB::table('request_withdrawal_reasons')->get();
                    foreach ($reasons as $reason) {
                        $withdraw_modal .= '<option value="' . $reason->reason . '">' . $reason->reason . '</option>';
                    }

                    $withdraw_modal .= ' </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn custom-primary round">Withdraw Request</button>
                                        <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
                }

                $data_arr[] = array(
                    //"closing_date_time" => changeDateFromUTCtoLocal($record->closing_date_time,Constants::DATE_TIME_FORMAT_NO_SECONDS),
                    "closing_date_time" => timeIn_12_24_format($record->closing_date_time),
                    "reference_no" => $record->reference_no,
                    "product_name" => $record->product_name,
                    "amount" => $record->currency . ' ' . number_format($record->amount),
                    "term_length" => $record['term_length_type'] == "HISA" ? "-" : $record['term_length'] . ' ' . ucwords(strtolower($record['term_length_type'])),
                    "total_bids" => $record->total_offers,
                    "highest_rate" => $highest_rate,
                    "lowest_rate" => $lowest_rate,
                    "view_offers" => $user->userCan('depositor/review-offers/view-offers-button')  ? '<a href="' . route('pick.offers', $encoded_request_id) . '" class="btn  mmy_btn custom-primary round ">View&nbsp;Offers</a>' : '',
                    "action" => $action . $withdraw_modal,
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
    public function getRequestOffers()
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/page-access')) {
            return redirect('access-denied');
        }
        return view('dashboard.depositor.new-post-request.pick-offers-new');
    }
    public function getRequestFIS()
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/page-access')) {
            return redirect('access-denied');
        }
        return view('dashboard.depositor.new-post-request.request-fis-new');
    }
    public function getRequestSummary(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/page-access')) {
            return redirect('access-denied');
        }
        $requestId = CustomEncoder::urlValueDecrypt($request->request_id);
        if ($requestId === null) {
            return redirect()->route('access-denied')->with('error', 'Invalid request ID');
        }
        $results = DepositRequest::with(['offers', 'invited'])->whereIn("request_status", ['ACTIVE', 'ON-REVIEW'])->find($requestId);

        if (!$results) {
            return redirect()->route('access-denied')->with('error', 'Deposit request not found');
        }

        $fiorganizations = Organization::with('industry')->where('type', 'BANK')
            ->where('is_test', \auth()->user()->is_test)
            ->whereIn('status', ['ACTIVE'])
            ->get()->toArray();
        $encoded_deposit_request_id = $request->request_id;
        $deposit_request = $results;
        return view('dashboard.depositor.new-post-request.request-summary-new', compact('deposit_request', 'encoded_deposit_request_id', 'fiorganizations'));
    }
    public function getRequestOfferSummary(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/page-access')) {
            return redirect('access-denied');
        }
        $offer = Offer::with(['counterOffers', 'invited'])->where("id", CustomEncoder::urlValueDecrypt($request->offer_id))->get();
        $offers = PostRequestOffersResource::collection($offer);
        $formatted = [];
        if (sizeof($offers) > 0) {
            $offer = $offers[0];
        }

        return view('dashboard.depositor.new-post-request.request-offer-summary-new', compact('offer'));
    }
    public function pickOffers(Request $request, $deposit)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/page-access')) {
            return redirect('access-denied');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Depositor Review Offers -> Action View Offers");
        $deposit_request = DepositRequest::find(CustomEncoder::urlValueDecrypt($deposit));
        if (!$deposit_request || !in_array($deposit_request->request_status, ['ACTIVE', 'ON_REVIEW'])) {
            systemActivities(Auth::id(), json_encode($request->query()), "Depositor Review Offers -> Action View Offers, Unable to access the page.. deposit not found");
            alert()->warning("Deposit not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US);
            return redirect()->back();
        }

        return view('dashboard.depositor.pick-offers', compact('deposit_request'));
    }

    function getOffersFromRequests(Request $request, $request_id)
    {
        $user = \auth()->user();
        // $deposit_request = DepositRequest::with(['invited'])->find(CustomEncoder::urlValueDecrypt($request_id));
        // if (!$deposit_request) {
        //     systemActivities(Auth::id(), json_encode($request->query()), "pickOffersData failed.. deposit request not found");
        //     $output = array(
        //         "draw" => 1,
        //         "recordsTotal" => 0,
        //         "recordsFiltered" => 0,
        //         "data" => [],
        //         "message" => "Deposit request not found"
        //     );
        //     return response()->json($output, 404);
        // }
        if (!$user->userCan('depositor/review-offers/page-access')) {
            $response = array(
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            );
            return response()->json($response);
        }

        $offers = Offer::whereHas('invited', function ($query) use ($request, $request_id) {
            // rate offers
            if ($request->filled("rate")) {
                $rateobject = explode(",", $request->rate);
                if (($rateobject[0] > 0) && ($rateobject[1] > 0)) {
                    $query->whereBetween("interest_rate_offer", $rateobject);
                } else {
                    if ($rateobject[0] > 0) {
                        $query->where("interest_rate_offer", ">=", $rateobject[0]);
                    }
                    if ($rateobject[1] > 0) {
                        $query->where("interest_rate_offer", "<=", $rateobject[1]);
                    }
                }
            }

            // join organization
            $query->whereHas('bank', function ($query4) use ($request, $request_id) {
                if ($request->filled("financialOrganizations")) {
                    $orgs = explode(",", $request->financialOrganizations);
                    $query4->whereIn("organizations.name", $orgs);
                }
                // if ($request->filled('search')) {
                //     $searchTerm = $request->search;
                //     $query4->where('organizations.name', 'LIKE', '%' . $searchTerm . '%');
                // }
            });

            // join deposit request
            $query->whereHas('depositRequest', function ($query2) use ($request, $request_id) {
                $query2->where('id', CustomEncoder::urlValueDecrypt($request_id));

                // filter awardee amount
                if ($request->filled("offer")) {
                    $deposit_amount = explode(",", $request->offer);
                    if (($deposit_amount[0] > 0) && ($deposit_amount[1] > 0)) {
                        $query2->whereBetween("amount", $deposit_amount);
                    } else {
                        if ($deposit_amount[0] > 0) {
                            $query2->where("amount", ">=", $deposit_amount[0]);
                        }
                        if ($deposit_amount[1] > 0) {
                            $query2->where("amount", "<=", $deposit_amount[1]);
                        }
                    }
                }
                // if ($request->filled('search')) {
                //     $searchTerm = $request->search;
                //     $query2->where('depositor_requests.amount', 'LIKE', '%' . $searchTerm . '%');
                // }

                // filter by term length
                if ($request->filled("termLength")) {
                    $termlenobject = explode(",", $request->termLength);
                    $termtype = $request->termLengthType;

                    if (($termlenobject[0] > 0) && ($termlenobject[1] > 0)) {
                        $query2->where("term_length_type", $termtype)
                            ->whereBetween("term_length", array_map('intval', $termlenobject));
                    } else {
                        if ($termlenobject[0] > 0) {
                            $query2->where("term_length_type", $termtype)
                                ->where("term_length", ">=", (int) $termlenobject[0]);
                        }
                        if ($termlenobject[1] > 0) {
                            $query2->where("term_length_type", $termtype)
                                ->where("term_length", "<=", (int) $termlenobject[1]);
                        }
                    }
                }

                // filter by product description
                $query2->whereHas('product', function ($query3) use ($request) {
                    if ($request->filled("products")) {
                        $products = explode(",", $request->products);
                        $query3->whereIn("products.description", $products);
                    }
                });
            });

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $searchColumns = [
                    'offers.reference_no',
                    'offers.interest_rate_offer',
                ];
                $query->where(function ($query) use ($searchColumns, $searchTerm) {
                    foreach ($searchColumns as $key => $column) {
                        if ($key == 0) {
                            $query->where($column, 'LIKE', '%' . $searchTerm . '%');
                        } else {
                            $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                        }
                    }
                });
            }
        })->paginate(10);

        // $contracts = $contracts->paginate(10);
        $offers->getCollection()->transform(function ($record) {
            $record->encoded_offer_id = CustomEncoder::urlValueEncrypt($record->id);
            // $record->offered_amount = $record->deposit ? number_format($record->deposit->offered_amount) : '-';
            return $record;
        });
        return response()->json($offers);
    }
    public function getRequestInstitutions(Request $request)
    {

        // $deposit_request = DepositRequest::with(['invited','offers'])->find($request->request_id);
        $deposit_request = InvitedBank::with(['bank'])
            ->leftJoin("offers", "offers.invitation_id", "=", "invited.id")
            ->leftJoin("deposits", "deposits.offer_id", "=", "offers.id")
            ->leftJoin("depositor_requests", "depositor_requests.id", "=", "invited.depositor_request_id")
            ->select([
                "invited.*",
                "depositor_requests.currency",
                "offers.id as offer_id",
                "offers.rate_held_until",
                "offers.maximum_amount",
                "offers.minimum_amount",
                "deposits.offered_amount as awarded_amount",
                "offers.interest_rate_offer"
            ])
            ->where("invited.depositor_request_id", CustomEncoder::urlValueDecrypt($request->request_id))
            ->get();

        // return "well well";
        return $deposit_request;
    }
    public function getPRequestOffers(Request $request)
    {
        // return  CustomEncoder::urlValueDecrypt($request->request_id);
        $offers = Offer::with(['counterOffers', 'invited'])
            ->whereIn("offer_status",['ACTIVE'])
            ->whereHas('invited', function ($query) use ($request) {
                $query->where('depositor_request_id', CustomEncoder::urlValueDecrypt($request->request_id));
                $query->whereHas('bank', function ($query2) use ($request) {
                    if ($request->filled("financialOrganizations")) {
                        $query2->whereIn("name", explode(",", $request->financialOrganizations));
                    }
                    if ($request->filled('search')) {
                        $query2->where(function ($query) use ($request) {
                            $query->where("name", "like", "%" . $request->search . "%");
                        });
                    }
                });
            });
        if ($request->filled("offer_rate_limit")) {
            $explodedRequestInput = explode(",", $request->offer_rate_limit);
            if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] === '0') {
                // No filter applied if both min and max are 0.
            } else {
                if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] === '0') {
                    $offers->where('interest_rate_offer', '>=', $explodedRequestInput[0]);
                } else if ($explodedRequestInput[0] === '0' && $explodedRequestInput[1] != '0') {
                    $offers->where('interest_rate_offer', '<=', $explodedRequestInput[1]);
                } else if ($explodedRequestInput[0] != '0' && $explodedRequestInput[1] != '0') {
                    $offers->whereBetween('interest_rate_offer', [$explodedRequestInput[0], $explodedRequestInput[1]]);
                }
            }
        }

        $offers = $offers->get();
        $offersdata = PostRequestOffersResource::collection($offers);
        return $offersdata;
    }
    public function pickOffersData(Request $request)
    {
        $user = \auth()->user();
        $validator = Validator::make($request->all(), [
            'action' => 'required',
            'req_id' => 'required'
        ]);

        if ($validator->fails()) {
            systemActivities(Auth::id(), json_encode($request->query()), "pickOffersData failed");
            $output = array(
                "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "message" => Arr::flatten($validator->messages()->get('*'))
            );
            return response()->json($output, 400);
        }

        $deposit_request = DepositRequest::with(['invited'])->find(CustomEncoder::urlValueDecrypt($request->req_id));
        if (!$deposit_request) {
            systemActivities(Auth::id(), json_encode($request->query()), "pickOffersData failed.. deposit request not found");
            $output = array(
                "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "message" => "Deposit request not found"
            );
            return response()->json($output, 404);
        }

        $action = $request->action;
        switch ($action) {
            case 'FETCH_REVIEW_OFFERS_SUMMARY':
                if (!$user->userCan('depositor/review-offers/page-access')) {
                    $response = array(
                        "draw" => 0,
                        "iTotalRecords" => 0,
                        "iTotalDisplayRecords" => 0,
                        "aaData" => []
                    );
                    return response()->json($response);
                }

                $response = array();
                $offers = Offer::whereIn('invitation_id', $deposit_request->invited->pluck('id')->toArray())->get();
                if (!empty($offers)) {
                    foreach ($offers as $offer) {
                        $contract = $offer->deposit;
                        $request_deposit = $offer->invited->depositRequest;
                        $response[] = array(
                            "bank_name" => $offer->bank_name,
                            "interest_rate" => formatInterest($offer->interest_rate_offer),
                            "min_amount" => $request_deposit->currency . ' ' . number_format($offer->minimum_amount),
                            "max_amount" => $request_deposit->currency . ' ' . number_format($offer->maximum_amount),
                            "award_amount" => $contract ? $request_deposit->currency . ' ' . number_format($contract->offered_amount) : '-',
                            "action" => $user->userCan('depositor/review-offers/view-offers-button') ? ' <a href="' . route('depositor.offer-summary', CustomEncoder::urlValueEncrypt($offer->id)) . '?fromPage=' . ($request->filled("fromPage") ? $request->fromPage : "active-deposits") . '" class="btn custom-primary round btn-block" style="color:white">View</a>' : '',
                        );
                    }
                }

                $output = array(
                    "draw" => intval($request->draw),
                    "recordsTotal" => !empty($offers) ? count($offers) : 0,
                    "recordsFiltered" => !empty($offers) ? count($offers) : 0,
                    "data" => $response,
                );

                // Output to JSON format
                return response()->json($output, 200);
                break;
            case 'FETCH_INVITED_INSTITUTIONS':
                if (!$user->userCan('depositor/review-offers/invited-institutions')) {
                    $response = array(
                        "draw" => 0,
                        "iTotalRecords" => 0,
                        "iTotalDisplayRecords" => 0,
                        "aaData" => []
                    );
                    return response()->json($response);
                }

                $banks = InvitedBank::select([
                    'invited.*',
                ])->whereRaw('invited.id IN (SELECT MAX(i.id) FROM invited as i WHERE i.depositor_request_id=' . $deposit_request->id . ' GROUP BY i.organization_id)')
                    ->orderBy('invited.invitation_status', 'ASC')
                    ->get();
                $response = array();
                foreach ($banks as $bank) {
                    $record = $bank->bank;
                    $badge_class = "info";
                    switch ($bank->invitation_status) {
                        case "UNINVITED":
                            $badge_class = "danger";
                            break;
                        case "PARTICIPATED":
                            $badge_class = "success";
                            break;
                        case "DID_NOT_PARTICIPATE":
                            $badge_class = "warning";
                            break;
                    }
                    $response[] = array(
                        $record->name,
                        $record->demographicData ? $record->demographicData->province : '',
                        $record->depositCreditRating && $record->depositCreditRating->creditRating ? $record->depositCreditRating->creditRating->description : '',
                        $record->depositCreditRating && $record->depositCreditRating->insuranceRating ? $record->depositCreditRating->insuranceRating->description : '',
                        '<span class="badge badge-' . $badge_class . '">' . ucfirst(strtolower(str_replace("_", " ", $bank->invitation_status))) . '</span>'
                    );
                }

                $output = array(
                    "draw" => intval($request->draw),
                    "recordsTotal" => !empty($banks) ? count($banks) : 0,
                    "recordsFiltered" => !empty($banks) ? count($banks) : 0,
                    "data" => $response,
                );

                // Output to JSON format
                return response()->json($output, 200);
            case 'FETCH_REVIEW_OFFERS':
                if (!$user->userCan('depositor/review-offers/page-access')) {
                    $response = array(
                        "draw" => 0,
                        "iTotalRecords" => 0,
                        "iTotalDisplayRecords" => 0,
                        "aaData" => []
                    );
                    return response()->json($response);
                }

                $response = array();
                $invited = $deposit_request->invited->where('invitation_status', '=', 'PARTICIPATED');
                $offers = Offer::with('invited')->whereIn('invitation_id', $invited->pluck('id')->toArray())->where('offer_status', 'ACTIVE')->get();
                if (!empty($offers)) {
                    $prime_rate = getSystemSettings('prime_rate')->value;
                    foreach ($offers as $offer) {
                        $counterOffer = $offer->counterOffers->where('status', '!=', 'COUNTERED')->first();

                        $offerBeforeCounter = $offer->offerBeforeCounter();
                        if ($counterOffer && in_array($counterOffer->status, ['CLOSED_ON_COUNTERED'])) {
                            $offerBeforeCounter = $counterOffer;
                        }

                        if ($counterOffer && !in_array($counterOffer->status, ['PENDING', 'DECLINED'])) {
                            $counterOffer = null;
                        }

                        if ($offer->rate_type != 'VARIABLE') :
                            $offer_rate = formatInterest($offer->interest_rate_offer);
                        else :
                            $offer_rate = formatInterest($offer->fixed_rate, true, $offer->rate_operator, false);
                        endif;

                        $encoded_offer_id = CustomEncoder::urlValueEncrypt($offer->id);
                        $institution_column = $offer->bank_name . '<input type="hidden" value="' . $offer->id . '" class="form-control" name="id"/>';
                        $interest_column = "";
                        $minimum_amount = "";
                        $maximum_amount = "";
                        $counter_status = "";

                        $organization = $user->organization;
                        $_counterOffers = $offer->counterOffers
                            ->first();

                        if ($_counterOffers) {
                            if ($_counterOffers->status == "EDITED") {
                                $status = "Offer Edited";
                            } else {
                                $status = "Counter " . counter_offer_status_formatter($_counterOffers->status, $organization);
                            }
                            $counter_status = "<label style=\"margin-top: 3px\" class=\"badge badge-success\" disabled> " . $status . "</label>";
                        }

                        $strike_interest_column = false;
                        $strike_minimum_amount_column = false;
                        $strike_maximum_amount_column = false;

                        if ($counterOffer) {
                            if ($counterOffer->rate_type != 'VARIABLE') :
                                $counter_offer_rate = formatInterest($counterOffer->offered_interest_rate);
                            else :
                                $counter_offer_rate = formatInterest($counterOffer->fixed_rate, true, $counterOffer->rate_operator, false);
                            endif;

                            if ($offer_rate != $counter_offer_rate) {
                                if ($counterOffer->status == 'DECLINED') {
                                    $strike_interest_column = false;
                                    $interest_column .= '<span class="cancel-text">' . $counter_offer_rate . '</span>';
                                } else {
                                    $strike_interest_column = true;
                                    $interest_column .= '<span>' . $counter_offer_rate . '</span>';
                                }
                            }
                            if ($counterOffer->minimum_amount != $offer->minimum_amount) {
                                if ($counterOffer->status == 'DECLINED') {
                                    $strike_minimum_amount_column = false;
                                    $minimum_amount .= '<span class="cancel-text">' . $deposit_request->currency . ' ' . number_format($counterOffer->minimum_amount) . '</span>';
                                } else {
                                    $strike_minimum_amount_column = true;
                                    $minimum_amount .= '<span>' . $deposit_request->currency . ' ' . number_format($counterOffer->minimum_amount) . '</span>';
                                }
                            }
                            if ($counterOffer->maximum_amount != $offer->maximum_amount) {
                                if ($counterOffer->status == 'DECLINED') {
                                    $strike_maximum_amount_column = false;
                                    $maximum_amount .= '<span class="cancel-text">' . $deposit_request->currency . ' ' . number_format($counterOffer->maximum_amount) . '</span>';
                                } else {
                                    $strike_maximum_amount_column = true;
                                    $maximum_amount .= '<span>' . $deposit_request->currency . ' ' . number_format($counterOffer->maximum_amount) . '</span>';
                                }
                            }
                        } else if ($offerBeforeCounter) {
                            if ($offerBeforeCounter->rate_type != 'VARIABLE') :
                                $offer_before_counter_rate = formatInterest($offerBeforeCounter->interest_rate_offer ? $offerBeforeCounter->interest_rate_offer : $offerBeforeCounter->offered_interest_rate);
                            else :
                                $offer_before_counter_rate = formatInterest($offerBeforeCounter->fixed_rate, true, $offerBeforeCounter->rate_operator, false);
                            endif;
                            if ($offer_before_counter_rate != $offer_rate) {
                                $interest_column .= '<span class="cancel-text">' . $offer_before_counter_rate . '</span>';
                                $strike_interest_column = false;
                            }
                            if ($offerBeforeCounter->minimum_amount != $offer->minimum_amount) {
                                $minimum_amount .= '<span class="cancel-text">' . $deposit_request->currency . ' ' . number_format($offerBeforeCounter->minimum_amount) . '</span>';
                                $strike_minimum_amount_column = false;
                            }
                            if ($offerBeforeCounter->maximum_amount != $offer->maximum_amount) {
                                $maximum_amount .= '<span class="cancel-text">' . $deposit_request->currency . ' ' . number_format($offerBeforeCounter->maximum_amount) . '</span>';
                                $strike_maximum_amount_column = false;
                            }
                        }

                        $rateHistory = DB::table('offers_archives')->where('invitation_id', $offer->invitation_id)->get();
                        // dd($rateHistory);
                        $interest_column .= '<span class="' . ($strike_interest_column ? 'cancel-text' : '') . '">' . $offer_rate . '<input type="hidden" class="form-control" name="rate" value="' . $offer_rate . '"/></span><input type="hidden" class="form-control" name="term_length_type" value="' . $deposit_request->term_length_type . '"/></span><input type="hidden" class="form-control" name="term_length" value="' . $deposit_request->term_length . '"/></span>';
                        if (count($rateHistory) > 0) {
                            $interest_column .= '<i class="fa fa-exclamation-circle text-primary mt-2 pe-auto" title="Rate History" aria-hidden="true"  data-toggle="modal" data-target="#rateHistory' . $offer->id . '"  id="rateHistory' . $offer->id . 'Trigger"></i>';
                            $interest_column .= '
                                <div class="modal fade bd-example-modal-lg" id="rateHistory' . $offer->id . '" tabindex="-1" role="dialog" aria-labelledby="rateHistory' . $offer->id . 'Title" aria-hidden="true">
                                    <div class="modal-dialog  modal-lg" >
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                    <div>
                                                        <table class="table table-bordered table-hover text-center">
                                                            <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Description</th>
                                                                    <th>Rate</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>';
                            foreach ($rateHistory as $Hrate) {
                                $interest_column .= '<tr>
                                                                    <td>' . Carbon::create($Hrate->created_date)->isoFormat("MMMM Do YYYY, h:mm:ss a") . '</td>
                                                                    <td>Updated</td>
                                                                    <td>' . $Hrate->interest_rate_offer . '%</td>
                                                                </tr>';
                            }

                            $interest_column .= '</tbody>
                                                        </table>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        }

                        $minimum_amount .= '<span class="' . ($strike_minimum_amount_column ? 'cancel-text' : '') . '">' . $deposit_request->currency . ' ' . number_format($offer->minimum_amount) . '<input type="hidden" class="form-control" name="min_amount" value="' . $offer->minimum_amount . '"/></span>';
                        $maximum_amount .= '<span class="' . ($strike_maximum_amount_column ? 'cancel-text' : '') . '">' . $deposit_request->currency . ' ' . number_format($offer->maximum_amount) . '<input type="hidden" class="form-control" name="max_amount" value="' . $offer->maximum_amount . '"/></span>';

                        $action_column = $user->userCan('depositor/review-offers/select-offer-screen-view-button') ? '<a href="' . route('depositor.offer-summary', $encoded_offer_id) . '?fromPage=pick-offers/' . $request->req_id . '" class="btn custom-primary round" style="color:white">View</a>' : '';

                        if ($user->userCan('depositor/review-offers/counter-offer')) {
                            $counter_offer = CounterOffer::where('offer_id', $offer->id)->orderBy('id', 'DESC')->first();
                            $counter_button = view('dashboard.depositor.counter-offer', compact('deposit_request', 'offer', 'counter_offer', 'prime_rate'))->render();
                        } else {
                            $counter_button = '';
                        }

                        $utc_time_now = getUTCTimeNow();
                        $action_column .= $counter_button;
                        $select_action_column = "";
                        $disabled = true;
                        if ($user->userCan('depositor/review-offers/select-offer-button')) {
                            if (Carbon::parse($deposit_request->closing_date_time, "UTC")->greaterThan($utc_time_now)) {
                                //                                $select_action_column .= ' <button class="btn btn-no-action-custom round" disabled><i class="fa fa-edit"></i></button>';
                            } else {
                                $disabled = false;
                                //                                $select_action_column .= ' <button class="btn btn-outline-secondary-custom round" data-selected="0" onclick="selectOffer(this);"><i class="fa fa-edit"></i></button>';
                            }
                        }

                        $offered_amount_column = '<div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span style="padding-right: 5px;padding-top: 10px;">
                                                        ' . $deposit_request->currency . '
                                                    </span>
                                                </div>
                                                <input data-rec-id="' . $offer->id . '" type="text" onkeyup="addThousands(this);selectOffer(this);" class="form-control offered_amount" name="offered_amount" value="" ' . ($disabled ? 'disabled' : '') . ' />
                                                <div class="input-group-append" style="margin-top: 4px;">
                                                </div>
                                            </div>
                                            <div class="_error"></div>';


                        if (Carbon::parse($deposit_request->date_of_deposit, "UTC")->lessThan($utc_time_now)) {
                            $action_column = "";
                            if ($user->userCan('depositor/review-offers/select-offer-screen-view-button')) {
                                $action_column .= '<a href="' . route('depositor.offer-summary', $encoded_offer_id) . '?fromPage=pick-offers/' . $request->req_id . '" class="btn custom-primary round" style="color:white">View</a>';
                            }

                            $action_column .= $counter_button;
                            $select_action_column = "";
                            if ($user->userCan('depositor/review-offers/select-offer-button')) {
                                $select_action_column .= ' <button class="btn btn-no-action-custom round" disabled><i class="fa fa-edit"></i></button>';
                            }
                            $offered_amount_column = '<div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span style="padding-right: 5px;padding-top: 10px;">
                                                        ' . $deposit_request->currency . '
                                                    </span>
                                                </div>
                                                <input type="text" onchange="addThousands(this);selectOffer(this);" class="form-control offered_amount" ' . ($disabled ? 'disabled' : '') . ' name="offered_amount" value="" />
                                                <div class="input-group-append">
                                                    
                                                </div>
                                            </div>
                                            <div class="_error"></div>';
                        }

                        $response[] = array($institution_column . "<br/>" . $counter_status, /*$offer->invited->bank->digital_account_opening,*/  $interest_column, $minimum_amount, $maximum_amount, $action_column, $offered_amount_column);
                    }
                }

                $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => !empty($offers) ? count($offers) : 0,
                    "recordsFiltered" => !empty($offers) ? count($offers) : 0,
                    "data" => $response,
                );

                // Output to JSON format
                return response()->json($output, 200);
            case 'SHOW_CONFIRM_BUTTON':
                if (!$user->userCan('depositor/review-offers/page-access')) {
                    return response()->json(0, 200);
                }

                $utc_time_now = getUTCTimeNow();

                $show = 1;
                if (Carbon::parse($deposit_request->closing_date_time, "UTC")->greaterThan($utc_time_now)) {
                    $show = 0;
                }

                if (Carbon::parse($deposit_request->date_of_deposit, "UTC")->lessThan($utc_time_now)) {
                    $show = 0;
                }

                return response()->json($show, 200);
        }
    }

    public function postPickedOffersData(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/confirm-button')) {
            $response = array("success" => false, "message" => "Access denied", "data" => []);
            return response()->json($response, 400);
        }

        $validator = Validator::make($request->all(), [
            'offers' => 'required',
            'req_id' => 'required'
        ]);

        if ($validator->fails()) {
            systemActivities(Auth::id(), json_encode($request->query()), "postPickedOffers failed, validations");
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $request->offers = json_decode($request->offers);
        $deposit_request = DepositRequest::with(['invited'])->find(CustomEncoder::urlValueDecrypt($request->req_id));
        if (!$deposit_request) {
            systemActivities(Auth::id(), json_encode($request->query()), "postPickedOffers failed.. deposit request not found");
            $response = array("success" => false, "message" => "Request not found, reload the page and try again", "data" => []);
            return response()->json($response, 400);
        }

        $deposit_existed = 0;
        try {
            DB::beginTransaction();
            // if ($deposit_request->request_status == "ACTIVE" && (getUTCTimeNow()->greaterThanOrEqualTo(Carbon::parse($deposit_request->closing_date_time, "UTC")))) {
            if ($deposit_request->request_status == "ACTIVE") {
                $total_offered = 0;
                $offer_ids = array();

                foreach ($request->offers as $offer) {
                    $offered_amount = str_replace(",", "", trim($offer->offered_amount));
                    $total_offered += (float)$offered_amount;
                    array_push($offer_ids, CustomEncoder::urlValueDecrypt($offer->id));
                }
                // for calculations so that we only save valid data;// performance to be looked into later

                if ($total_offered > $deposit_request->amount) {
                    systemActivities(Auth::id(), json_encode($request->query()), "postPickedOffers failed.. Total requested amount must not exceed original requested amount.");
                    $response = array("success" => false, "message" => "Total requested amount must not exceed original requested amount.", "data" => []);
                    return response()->json($response, 400);
                }

                // $request_id = null;
                $depositor_organization = $deposit_request->organization;

                $success = 0;
                $failed_404 = 0;
                $failed_amount = 0;
                $message_to_depositor = "<p>You have selected the following offers:</p><ol>";

                $depositor_deposits_list = [];
                $banks_nofications_data = [];
                $banks_o = [];

                foreach ($request->offers as $offer) {

                    $offer_object = Offer::with(['deposit'])->where('offer_status', 'ACTIVE')->whereHas("invited.organization", function ($query) {
                        $query->where('invited.invitation_status', 'PARTICIPATED');
                    })->find(CustomEncoder::urlValueDecrypt($offer->id));


                    if (!$offer_object) {
                        $failed_404++;
                        systemActivities(Auth::id(), json_encode($request->query()), "Offer id: " . $offer->id . " does not exist in the database, hence unable to create a deposit");
                        continue;
                    }

                    CounterOffer::where(['offer_id' => $offer_object->id, 'status' => 'PENDING'])->update(['status' => 'CLOSED_ON_OFFER_SELECTION']);

                    $offer->offered_amount = str_replace(",", "", trim($offer->offered_amount));
                    $offered_amount = (float)$offer->offered_amount;

                    if ($offered_amount == 0 || !($offered_amount <= $offer_object->maximum_amount && $offered_amount >= $offer_object->minimum_amount)) {
                        $failed_amount++;
                        systemActivities(Auth::id(), json_encode($request->query()), "Offered Amount is:" . $offered_amount . " Offer id: " . $offer->id . " maximum_amount or minimum_amount is not met , hence unable to create a deposit");
                        continue;
                    }

                    $offer_contract = $offer_object->deposit;
                    if (!empty($offer_contract)) {
                        $deposit_existed++;
                        continue;
                    }

                    $deposit_created = Deposit::create([
                        'reference_no' => generateOfferContractID($deposit_request->reference_no),
                        'offer_id' => $offer_object->id,
                        'offered_amount' => $offered_amount,
                        'status' => 'PENDING_DEPOSIT',
                        'created_at' => getUTCDateNow(),
                    ]);

                    $success++;
                    archiveTable($deposit_request->id, "offers", auth()->id(), "SELECTED");
                    $offer_object->offer_status = 'SELECTED';
                    $offer_object->save();

                    $bank = $offer_object->invited->bank;

                    $gic_or_hisa = ($deposit_request->term_length_type != 'HISA') ? 'GIC' : 'HISA';

                    $message_to_depositor .= "<li><b>" . $bank->name . "</b>, " . $deposit_request->product->description .
                        ($deposit_request->term_length_type != 'HISA' ? ', ' . $deposit_request->term_length . ' ' . strtolower($deposit_request->term_length_type) : "")
                        . ", " . $deposit_request->currency . " " . number_format($offered_amount) . ", " . $offer_object->interest_rate_offer . "%</li>";

                    array_push(
                        $depositor_deposits_list,
                        [
                            'term_lentgh' => $deposit_request->term_length_type,
                            'lockout_period' => $deposit_request->lockout_period_days,
                            'product_description' => $deposit_request->product->description,
                            'term_lentgh' => $deposit_request->term_length_type,
                            'deposit_id' => $deposit_created->reference_no,
                            'offered_amount' => number_format($offered_amount),
                            'date_of_deposit' => changeDateFromUTCtoLocal($deposit_request->date_of_deposit, 'M d Y', Constants::DATE_TIME_FORMAT, $user->timezone),
                            'interest_rate' => $offer_object->interest_rate_offer,
                            'fi_name' => $bank->name,
                        ]
                    );

                    //                        $bank_timezone = \auth()->user()->timezone;

                    $notify_users = $bank->notifiableUsersEmails($return_emails = false);




                    if (!array_key_exists($bank->id, $banks_o)) {
                        $bankdtemp = ['id' => $bank->id, 'bankd' => $bank];
                        $banks_o[] = $bankdtemp;
                    }



                    foreach ($notify_users as $user) {
                        $datetime_from_utc = changeDateFromUTCtoLocal($deposit_request->date_of_deposit, 'M d Y', Constants::DATE_TIME_FORMAT, $user->timezone); //.' '.$user->timezone;
                        $message = "<p>" . $depositor_organization->name . " has chosen to deposit " . $deposit_request->currency . " " . number_format($offered_amount) . " with you in a " . $deposit_request->product->description . " " . $gic_or_hisa . " at " . $offer_object->interest_rate_offer . "%. " . $depositor_organization->name . " has indicated that the funds will be deposited approximately by " . $datetime_from_utc . ".</p>";
                        $message .= "<p>Don't forget to complete your Deposit after receiving the funds by pressing the 'Create " . $gic_or_hisa . "' button in the 'Pending Deposits' page.</p>";
                        $message .= "<p>If this is a new customer please reach out to " . $depositor_organization->name . " through our chat function on the 'Pending Deposits'.</p>";

                        //build fi object
                        $thisproduct_offer_update_to_bank = [
                            'term_lentgh' => $deposit_request->term_length_type,
                            'lockout_period' => $deposit_request->lockout_period_days,
                            'product_description' => $deposit_request->product->description,
                            'depositor_organization' => $depositor_organization->name,
                            'currency' => $deposit_request->currency,
                            'offered_amount' => number_format($offered_amount),
                            'deposit_id' => $deposit_created->reference_no,
                            'deposit_date' => $datetime_from_utc,
                            'interest_rate' => $offer_object->interest_rate_offer,
                            'depositor_name' => $depositor_organization->name,

                        ];
                        if (!array_key_exists($bank->name, $banks_nofications_data)) {
                            // Create 'user 3' if it doesn't exist
                            $banks_nofications_data[$bank->name] = [];
                        }

                        $banks_nofications_data[$bank->name] = $thisproduct_offer_update_to_bank;
                        //build fi object

                        // Mail::to($user->email)->queue(new AwardedDeposit([
                        //     'message' => $message,
                        //     'subject' => "Your offer has been selected",
                        //     'header' => $depositor_organization->name . " has selected your offer",
                        //     'user_type' => "Bank"
                        // ]));
                    }



                    $offbanks = collect($deposit_request->invited)->map(function ($query) {

                        return [
                            'invitation_status' => $query->invitation_status,
                            'user_id' => $query->bank->demographicData->user_id
                        ];
                    });

                    // foreach ($offbanks as $offbank) {
                    //     if ($offbank['invitation_status'] == "PARTICIPATED" && $offbank['user_id'] != $bank->demographicData->user_id) {
                    //         Log::error('<><> '.json_encode($offbank));
                    //         $offBankUser = User::where('id', $offbank['user_id'])->first();
                    //         if (isNotSupposedToReceiveMail($offBankUser->email, ['CLOSED'])) {
                    //             // Mail::to($offBankUser->email)->queue(new OfferAccepted([
                    //             //     "subject" => "Offer Declined",
                    //             //     "message" => "<p>" . $depositor_organization->name . " Request ID " . $deposit_request->reference_no . " Status Changed.</p><p>" . $depositor_organization->name . " has accepted a rate from another financial institute",
                    //             // ]));
                    //         }
                    //     }
                    // }

                    $notification = "This Request Id " . $deposit_request->reference_no . " has been awarded to you. Kindly Check your Deposit Section. ";


                    notify([
                        'type' => 'CONTRACT CREATED',
                        'details' => $notification,
                        'date_sent' => getUTCDateNow(),
                        'status' => 'ACTIVE',
                        'organization_id' => $bank->id
                    ]);
                }
                //send Mails to banks users whose offers were selected   
                if ($banks_nofications_data) {
                    foreach ($banks_o as  $bank_o) {

                        $orgnizationob = Organization::where("id", $bank_o['id'])->first();
                        $notify_users = $orgnizationob->notifiableUsersEmails(true);
                        Mail::to($notify_users)->queue(new BankMails([
                            'depositor_organization_name' => $depositor_organization->name,
                            'offers_selected' => $banks_nofications_data[$bank_o['bankd']->name],
                            'subject' => "Your offer " . $deposit_request->reference_no . " has been selected",
                        ], 'offers_selected'));
                    }
                }
                //send Mails to banks users whose offers were selected

                Mail::to(getAdminEmails())->queue(new AdminMails([
                    'subject' => "Offer Ref No " . $deposit_request->reference_no . " has been selected",
                    'depositor_organization_name' => $depositor_organization->name,
                    'offers_selected' => $depositor_deposits_list,
                ], 'admin_offers_selected'));

                //send mail to depositor
                Mail::to($depositor_organization->notifiableUsersEmails())->send(new DepositorMails([
                    'offers_selected' => $depositor_deposits_list,
                    'subject' => "Deposits Approved"
                ], 'offers_selected'));

                //send mail to depositor



                if ($success > 0 /*&& $request_id*/ && count($offer_ids) > 0) {
                    $all_offers = Offer::whereIn('invitation_id', $deposit_request->invited->pluck('id')->toArray())->get();
                    if (!empty($all_offers)) {
                        foreach ($all_offers as $all_offer) {
                            $all_offer = (object)$all_offer;
                            $offer_id = (int)$all_offer->id;
                            if (!in_array($offer_id, $offer_ids)) {
                                $offer_contract = $all_offer->deposit;
                                if (!empty($offer_contract)) {
                                    archiveTable($offer_contract->id, "deposits", auth()->id(), "WITHDRAWN");
                                    $offer_contract->status = 'WITHDRAWN';
                                    $offer_contract->save();

                                    $bank = $all_offer->invited->bank;
                                    $message = "Deposit ID " . $offer_contract->reference_no . " has been withdrawn.";
                                    $message .= "<br />";
                                    // Mail::to($bank->notifiableUsersEmails())->queue(new WithdrawDeposit([
                                    //     'message' => $message,
                                    // ]));

                                    $notification = "Deposit ID " . $offer_contract->reference_no . " has been withdrawn";
                                    notify([
                                        'type' => 'CONTRACT UPDATED/CREATED',
                                        'details' => $notification,
                                        'date_sent' => getUTCDateNow(),
                                        'status' => 'ACTIVE',
                                        'organization_id' => $bank->id
                                    ]);
                                }
                                archiveTable($offer_id, "offers", auth()->id(), "NOT_SELECTED");
                                $all_offer->offer_status = 'NOT_SELECTED';
                                $all_offer->save();
                            }
                        }
                    }
                }

                /*
                 *
                 * END OF THE CONDITION
                 */

                $message = "";
                $message_title = "";
                if ($success > 0) {
                    archiveTable($deposit_request->id, "depositor_requests", auth()->id(), "COMPLETED");
                    $deposit_request->request_status = 'COMPLETED';
                    $deposit_request->save();

                    DB::commit();

                    $message_title .= "You have selected " . ($success == 1 ? "an " : "") . Str::plural('offer', count($request->offers));
                    $message .= "The selected Financial " . Str::plural('institution', count($request->offers)) . " " . ($success == 1 ? "is " : "are") . " being notified.";
                }

                if ($failed_amount > 0) {
                    $message .= $failed_amount . " " . Str::plural('deposit', $failed_amount) . " failed as the offer maximum amount or minimum amount condition is not met. ";
                }

                if ($failed_404 > 0) {
                    $message .= $failed_404 . " " . Str::plural('deposit', $failed_404) . " failed as the offer was not found. ";
                }

                if ($deposit_existed > 0) {
                    $message .= $deposit_existed . " " . Str::plural('deposit', $deposit_existed) . " failed, as they already existed. ";
                }

                systemActivities(Auth::id(), json_encode($request->query()), $message);
                $response = array("success" => $success > 0, "message" => $message, "message_title" => $message_title, "data" => []);
                return response()->json($response, $success > 0 ? 200 : 400);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $timestamp = time();
            Log::error($timestamp . ': ' . $exception->getLine() . ", " . $exception->getTraceAsString());
            $message = "Unable to submit the selected offer. Please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US;
            systemActivities(Auth::id(), json_encode($request->query()), "Unable to submit the selected offer., check with the developer. Error No: " . $timestamp);
            $response = array("success" => false, "message" => $message, "data" => [], 'error' => $exception->getLine() . ", " . $exception->getTraceAsString());
            return response()->json($response, 400);
        }

        $message = "You can only select offers after closing date & time, Please wait until all parties have had a chance to submit their offer";
        systemActivities(Auth::id(), json_encode($request->query()), "postPickedOffers failed, failed. " . $message);
        $response = array("success" => false, "message" => $message, "data" => []);
        return response()->json($response, 400);
    }


    public function calculate()
    {
        return view('dashboard.depositor.calculator');
    }


    public function fetchRate()
    {
        $amount = request('amount');
        $rates = Campaign::Join('organizations', 'market_place_offers.organization_id', '=', 'organizations.id')
            // ->where('minimum_amount', '<', $amount)->where('maximum_amount', '>', $amount)
            ->where('market_place_offers.status', "ACTIVE")
            ->where('market_place_offers.interest_rate', '>', request('interest'))
            ->orderBy('interest_rate', 'DESC')
            ->groupBy('organizations.name')
            ->get();

        if (count($rates) < 1) {
            $rates = Campaign::Join('organizations', 'market_place_offers.organization_id', '=', 'organizations.id')
                ->orderBy('interest_rate', 'DESC')
                ->orderBy('term_length_type', 'DESC')
                ->orderBy('term_length', 'DESC')
                ->where('market_place_offers.status', 'ACTIVE')
                ->groupBy('organizations.name')
                ->take(4)->get();
        }

        $response = ['status' => 200, 'message' => 'data fetch successfully', 'rates' => json_encode($rates)];
        return response()->json($response, 200);
    }
}

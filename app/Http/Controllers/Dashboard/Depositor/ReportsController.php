<?php
namespace App\Http\Controllers\Dashboard\Depositor;

use App\Constants;
use App\Data\DepositorData;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Barryvdh\DomPDF\Facade as PDF;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.depositor');
    }

    public function index(Request $request)
    {  exit("Unavailable");

        $user=\auth()->user();
        if(!$user->userCan('universal/reports/page-access') ){
            return view('dashboard.403');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Depositor Reports");
        return view('dashboard.depositor.reports');
    }

    public function getData(Request $request)
    {
        $user=\auth()->user();
        if(!$user->userCan('universal/reports/page-access') ){
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
        $rowperpage = $request->filled('start') ? $request->length: 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex=null;
        $columnName="";
        $columnSortOrder="";
        if ($columnIndex_arr) {
            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        }

        $searchValue="";
        if($search_arr) {
            $searchValue = $search_arr['value']; // Search value
        }

        return DepositorData::depositReportsData(null,function ($data) use($start,$rowperpage,$draw,$columnIndex,$columnName,$columnSortOrder,$searchValue,$request){
            $from = getUTCTimeNow()->subDays(90)->format('Y-m-d');
            if ( $request->filled('date_from') ) {
                try {
                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT,$request->date_from);
                    $from = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT);
                } catch (\Exception $exception) {}
            }

            $to = getUTCTimeNow()->format('Y-m-d');
            if ( $request->filled('date_to') ) {
                try {
                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT,$request->date_to);
                    $to = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT);
                } catch (\Exception $exception) {}
            }

            $data = $data->whereDate('deposits.gic_start_date','>=',$from)->whereDate('deposits.gic_start_date','<=',$to);

            $totalRecords = with(clone $data)->get()->count();

            $search_columns = [
                "reference_no",
                "gic_number",
                "bank_name",
                "product_name",
                "term_length",
                "lockout_period_days",
                "request_amount",
                "interest_rate_offer",
                "gic_start_date",
                "maturity_date",
                "status"
            ];

            if( !empty($searchValue) ) {
                $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                    $query->where('deposits.reference_no', 'like', '%' . $searchValue . '%');
                    foreach ($search_columns as $search_column) {
                        switch ($search_column) {
                            case 'request_amount':
                                $query->orWhere('deposits.offered_amount', 'like', '%' . $searchValue . '%')->orWhere('depositor_requests.currency', 'like', '%' . $searchValue . '%')
                                    ->orWhere(DB::raw("CONCAT(depositor_requests.currency, ' ', deposits.offered_amount)"), 'like', '%' . $searchValue . '%');
                                break;
                            case 'product_name':
                                $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
                                break;
                            case 'gic_number':
                                $query->orWhere('deposits.gic_number', 'like', '%' . $searchValue . '%');
                                break;
                            case 'gic_start_date':
                                try {
                                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT,$searchValue);
                                    $date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT);
                                    $query->orWhereDate('deposits.gic_start_date', 'like', '%' . $date_in_utc . '%');
                                } catch (\Exception $exception) {
                                    $query->orWhere('deposits.gic_start_date', 'like', '%' . $searchValue . '%');
                                }
                                break;
                            case 'maturity_date':
                                try {
                                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT,$searchValue);
                                    $date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT);
                                    $query->orWhereDate('deposits.maturity_date', 'like', '%' . $date_in_utc . '%');
                                } catch (\Exception $exception) {
                                    $query->orWhere('deposits.maturity_date', 'like', '%' . $searchValue . '%');
                                }
                                break;
                            case 'lockout_period_days':
                                $query->orWhere('depositor_requests.lockout_period_days', 'like', '%' . $searchValue . '%');
                                break;
                            case 'date':
                                try {
                                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT,$searchValue);
                                    $date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT);
                                    $query->orWhereDate('deposits.modified_date', 'like', '%' . $date_in_utc . '%')
                                        ->orWhereDate('deposits.created_date', 'like', '%' . $date_in_utc . '%');
                                } catch (\Exception $exception) {
                                    $query->orWhere('deposits.modified_date', 'like', '%' . $searchValue . '%')
                                        ->orWhere('deposits.created_date', 'like', '%' . $searchValue . '%');
                                }
                                break;
                            case 'term_length':
                                $query->orWhere($search_column, 'like', '%' . $searchValue . '%')->orWhere('term_length_type', 'like', '%' . $searchValue . '%')
                                    ->orWhere(DB::raw("CONCAT(term_length, ' ', term_length_type)"), 'like', '%' . $searchValue . '%');
                                break;
                            case 'interest_rate_offer':
                                $query->orWhere('offers.interest_rate_offer', 'like', '%' . $searchValue . '%');
                                break;
                            case 'status':
                                $query->orWhere('deposits.status', 'like', '%' . $searchValue . '%');
                                break;
                            case 'bank_name':
                                $query->orWhere('users.name', 'like', '%' . $searchValue . '%');
                                break;
                        }
                    }
                });
            }

            if(!$columnIndex && !is_numeric($columnIndex)){
                $data = $data->orderBy('deposits.reference_no','DESC');
            }else{
                switch ($columnName){
                    case 'interest_rate_offer':
                        $data = $data->orderBy('offers.interest_rate_offer',strtoupper($columnSortOrder));
                        break;
                    case 'reference_no':
                        $data = $data->orderBy('deposits.reference_no',strtoupper($columnSortOrder));
                        break;
                    case 'request_amount':
                        $data = $data->orderBy('depositor_requests.amount',strtoupper($columnSortOrder));
                        break;
                    case 'product_name':
                        $data = $data->orderBy('products.description',strtoupper($columnSortOrder));
                        break;
                    case 'bank_name':
                        $data = $data->orderBy('users.name',strtoupper($columnSortOrder));
                        break;
                    case 'lockout_period_days':
                        $data = $data->orderBy('depositor_requests.lockout_period_days',strtoupper($columnSortOrder));
                        break;
                    case 'gic_number':
                        $data = $data->orderBy('deposits.gic_number',strtoupper($columnSortOrder));
                        break;
                    case 'maturity_date':
                        $data = $data->orderBy('deposits.maturity_date',strtoupper($columnSortOrder));
                        break;
                    case 'gic_start_date':
                        $data = $data->orderBy('deposits.gic_start_date',strtoupper($columnSortOrder));
                        break;
                    case 'term_length':
                        $data = $data->orderBy('depositor_requests.term_length',strtoupper($columnSortOrder));
                        break;
                }
            }

            if( $request->filled("export") ){
                $data = $data->get();
                $pdf = PDF::loadView('dashboard.depositor.exports.reports', compact('data'));
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download("Reports_".date(Constants::DATE_TIME_FORMAT_FOR_URL_NAMES).".pdf");
            }

            $totalRecordswithFilter = with(clone $data)->get()->count();

            $data = $data->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();

            foreach($data as $record){
                $data_arr[] = array(
                    "reference_no" => $record->reference_no,
                    "gic_number" => $record->gic_number,
                    "bank_name" => $record->bank_name,
                    "product_name" => $record->product_name,
                    "term_length" => $record->term_length_type == "HISA" ? "-" : $record->term_length. ' ' . ucwords(strtolower($record->term_length_type)),
                    "lockout_period_days"=>$record->lockout_period_days ? $record->lockout_period_days : '-',
                    "request_amount" => $record->currency.' '.number_format($record->amount),
                    "interest_rate_offer" => formatInterest($record->interest_rate_offer),
                    "gic_start_date"=>$record->gic_start_date ? changeDateFromUTCtoLocal($record->gic_start_date,Constants::DATE_FORMAT): '-',
                    "maturity_date"=>$record->maturity_date ? changeDateFromUTCtoLocal($record->maturity_date,Constants::DATE_FORMAT) : '-',
                );
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr
            );

            return response()->json($response);
        });
    }
}
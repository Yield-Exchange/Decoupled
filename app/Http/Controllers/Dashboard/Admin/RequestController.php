<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Constants;
use App\Data\DepositorData;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\DepositRequest;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index() {
        return view('dashboard.admin.requests.index');
    }

    public function getRequests() {

        $status = request('status');

        switch ($status) {
            case 'REVIEW OFFER':
                $requests = $this->reviewOffer();
                break;
            case 'PENDING OFFER':
                $requests = $this->pendingDeposit();
                break;
            case 'ACTIVE DEPOSIT':
                $requests = $this->activeDeposit();
                break;
            case 'EXPIRED':
                $requests = $this->listDeposits();
                break;
            case 'WITHDRAWN':
                $requests = $this->listDeposits();
                break;
            default:
                $requests = DepositRequest::join('products','products.id','=','depositor_requests.product_id')
                    ->join('organizations','depositor_requests.organization_id','=','organizations.id')
                    ->where('depositor_requests.request_status', request('status'));
                break;
        }
    
        $totalRecords = with(clone $requests)->get()->count();
        
        $data = $requests->select([
            'depositor_requests.*',
            DB::raw("products.description as product_name"),
            DB::raw('depositor_requests.id as depositor_request_id')
        ]);

        $searchValue = request('search');
        $getColumns = explode(',', request('columns'));
        
        if( !empty($searchValue) ) {
            $data = $requests->where(function ($query) use ($searchValue, $getColumns, $requests) {

                foreach ($getColumns as $search_column) {
                    switch ($search_column) {
                        case'request_no':
                            $query->orWhere('depositor_requests.reference_no', 'like', '%' . $searchValue . '%');
                            break;
                        case 'amount':
                            $query->orWhere('depositor_requests.amount', 'like', '%' . $searchValue . '%');
                            break;
                        case 'terms':
                            $query->orWhere('depositor_requests.term_length', 'like', '%' . $searchValue . '%');
                            $query->orWhere('depositor_requests.term_length_type', 'like', '%' . $searchValue . '%');
                            break;
                        case 'closure_date':
                            $query->orWhere('depositor_requests.closing_date_time', 'like', '%' . $searchValue . '%');
                            break;
                        case 'deposit_date':
                            $query->orWhere('depositor_requests.date_of_deposit', 'like', '%' . $searchValue . '%');
                            break;
                        case 'status':
                            $query->orWhere('depositor_requests.request_status', 'like', '%' . $searchValue . '%');
                            break;
                        // case 'rate':
                        //     $query->orWhere('offers.interest_rate_offer', 'like', '%' . $searchValue . '%');
                        //     break;
                        case 'invited_fi':
                            $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                            break;
                    }
                }
            });
        }

        $sortOrder = request('sortOrder');
        $sortColumn = request('sortColumn');

        if(!$sortColumn && !$sortOrder){
            $data = $data->orderBy('depositor_requests.reference_no','DESC');
        }else{
                switch ($sortColumn) {
                    case'request_no':
                        $data = $data->orderBy('depositor_requests.reference_no', strtoupper($sortOrder));
                        break;
                    case 'amount':
                        $data = $data->orderBy('depositor_requests.amount', strtoupper($sortOrder));
                        break;
                    case 'terms':
                        $data = $data->orderBy('depositor_requests.term_length', strtoupper($sortOrder));
                        break;
                    case 'closure_date':
                        $data = $data->orderBy('depositor_requests.closing_date_time', strtoupper($sortOrder));
                        break;
                    case 'deposit_date':
                        $data = $data->orderBy('depositor_requests.date_of_deposit', strtoupper($sortOrder));
                        break;
                    case 'status':
                        $data = $data->orderBy('depositor_requests.request_status', strtoupper($sortOrder));
                        break;
                    case 'invited_fi':
                        $data = $data->orderBy('organizations.name', strtoupper($sortOrder));
                        break;
                    }
        }

        $start =  request('startQueryFrom');
        $rowperpage = request('rowParPage');
        $totalRecords = with(clone  $requests)->get()->count();
        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();
        
        $data_arr = [];
        foreach ($data as $record) {

            $depositor = Organization::find($record->organization_id);
            $deposit_status = "";
            $status = $record->request_status;
            switch ($status){
                case "ACTIVE":
                    $status = "<span class='badge badge-success'>Active</span>";
                    break;
                case "WITHDRAWN":
                    $status = "<span class='badge badge-warning'>Withdrawn</span>";
                    break;
                case "EXPIRED":
                    $status = "<span class='badge badge-danger'>Expired</span>";
                    break;
                case "COMPLETED":
                    $status = "<span class='badge badge-info'>Completed</span>";
                    break;
                default:
                    $status= "<span class='badge badge-danger'>".$status."</span>";
                    break;
            }
            
            if ($record->status) {
                $deposit_status= "<span class='badge badge-default'>".$record->status."</span>";
            }
            
            $invitedBanks = '<table style="width: 100% !important;">';
            $request = DepositRequest::where('id', $record->depositor_request_id)->first();
            if($request->offers) {
                foreach($request->offers as $offer) {
                    $selected = (is_null($offer->deposit)) ? 0 : '$'.number_format($offer->deposit->offered_amount, 2) ;
                    $invitedBanks .=    '<tr>
                                            <td style="width:50%">'.$offer->invited->bank->name.'</td>
                                            <td>'.$offer->interest_rate_offer.'%</td>
                                            <td  style="width:40%">'.$selected.'</td>
                                        </tr>';
                }
            }                   
            $invitedBanks .= '</table>';

            $data_arr[] = array(
                "request_no" =>  $record->reference_no,
                "investors" => $depositor->name,
                "amount"=> '$'.number_format($record->amount, 2),
                "locked_in"=> ($record->lockout_period_days) ? $record->lockout_period_days." DAYS" : "",
                "terms"=> "" . intval($record->term_length)." ". $record->term_length_type,
                "closure_date"=> Carbon::create($record->closing_date_time)->setTimezone('PST')->toDateTimeString(). " PST",
                "deposit_date"=> Carbon::create($record->date_of_deposit)->toDateString(),
                "status"=> $status,
                "deposit_status"=> $deposit_status,
                "fis"=>  $invitedBanks,
            );

         }
    


        $response = array(
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function getRequestsOld() {

        $status = ['EXPIRED', 'ACTIVE', 'WITHDRAWN', 'COMPLETED'];

        $requests = DepositRequest::join('products','products.id','=','depositor_requests.product_id')
            ->join('organizations','depositor_requests.organization_id','=','organizations.id');
    
        $totalRecords = with(clone $requests)->get()->count();
        
        $data = $requests->select([
            'depositor_requests.*',
            DB::raw("products.description as product_name")
        ]);


        if(in_array(request('status'), $status)) {
            $requests->where('depositor_requests.request_status', request('status'));
        }

        $searchValue = request('search');
        $getColumns = explode(',', request('columns'));
        
        if( !empty($searchValue) ) {
            $data = $requests->where(function ($query) use ($searchValue, $getColumns, $requests) {

                foreach ($getColumns as $search_column) {
                    switch ($search_column) {
                        case'request_no':
                            $query->orWhere('depositor_requests.reference_no', 'like', '%' . $searchValue . '%');
                            break;
                        case 'amount':
                            $query->orWhere('depositor_requests.amount', 'like', '%' . $searchValue . '%');
                            break;
                        case 'terms':
                            $query->orWhere('depositor_requests.term_length', 'like', '%' . $searchValue . '%');
                            $query->orWhere('depositor_requests.term_length_type', 'like', '%' . $searchValue . '%');
                            break;
                        case 'closure_date':
                            $query->orWhere('depositor_requests.closing_date_time', 'like', '%' . $searchValue . '%');
                            break;
                        case 'deposit_date':
                            $query->orWhere('depositor_requests.date_of_deposit', 'like', '%' . $searchValue . '%');
                            break;
                        case 'status':
                            $query->orWhere('depositor_requests.request_status', 'like', '%' . $searchValue . '%');
                            break;
                        // case 'rate':
                        //     $query->orWhere('offers.interest_rate_offer', 'like', '%' . $searchValue . '%');
                        //     break;
                        case 'invited_fi':
                            $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                            break;
                    }
                }
            });
        }



        $start =  request('startQueryFrom');
        $rowperpage = request('rowParPage');
        $totalRecords = with(clone  $requests)->get()->count();
        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();
        
        $data_arr = [];
        foreach ($data as $record) {

            $depositor = Organization::find($record->organization_id);
            $deposit_status = "Active";
            $status = $record->request_status;
            switch ($status){
                case "ACTIVE":
                    $status = "<span class='badge badge-success'>Active</span>";
                    break;
                case "WITHDRAWN":
                    $status = "<span class='badge badge-warning'>Withdrawn</span>";
                    break;
                case "EXPIRED":
                    $status = "<span class='badge badge-danger'>Expired</span>";
                    break;
                case "COMPLETED":
                    $status = "<span class='badge badge-info'>Completed</span>";
                    break;
                default:
                    $status= "<span class='badge badge-danger'>".$status."</span>";
                    break;
            }
            
            $invitedBanks = '<table style="width: 100% !important;">';
            foreach($record->offers as $offer) {
                $selected = (is_null($offer->deposit)) ? 0 : '$'.number_format($offer->deposit->offered_amount, 2) ;
                $invitedBanks .=    '<tr>
                                        <td>'.$offer->invited->bank->name.'</td>
                                        <td>'.$offer->interest_rate_offer.'%</td>
                                        <td>'.$selected.'</td>
                                    </tr>';
            }
                                
            $invitedBanks .= '</table>';

            $data_arr[] = array(
                "request_no" =>  $record->reference_no,
                "investors" => $depositor->name,
                "amount"=> '$'.number_format($record->amount, 2),
                "locked_in"=> ($record->lockout_period_days) ? $record->lockout_period_days." DAYS" : "",
                "terms"=> "" . intval($record->term_length)." ". $record->term_length_type,
                "closure_date"=> Carbon::create($record->closing_date_time)->setTimezone('PST')->toDateTimeString(). " PST",
                "deposit_date"=> Carbon::create($record->date_of_deposit)->toDateString(),
                "status"=> $status,
                "deposit_status"=> $deposit_status,
                "invited_fis"=>  $record->invited->count(),
                "offers"=>  $record->offers->count(),
                "fis"=>  $invitedBanks,
            );

         }
    


        $response = array(
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    protected function reviewOffer () {
        $utc_date_now = getUTCTimeNow()->format(Constants::DATE_TIME_FORMAT);
        $requests = DepositRequest::leftJoin('products','products.id','=','depositor_requests.product_id')
                        ->whereIn('depositor_requests.request_status',['ACTIVE'])
                        ->select([
                            'depositor_requests.*',
                            'offers.*',
                            DB::raw('depositor_requests.id as depositor_request_id')
                        ])->leftJoin('invited',function ($join){
                                $join->on('depositor_request_id','=','depositor_requests.id');
                        })
            ->leftJoin('offers',function ($join){
                $join->on('offers.invitation_id','=','invited.id');
                $join->where('offers.offer_status','ACTIVE');
                $join->where('invited.invitation_status','PARTICIPATED');
            })
            
            ->where(function ($query) use($utc_date_now){
                $query->where('closing_date_time','>=',$utc_date_now)->orWhere(function ($query) use($utc_date_now){
                    $query->where('closing_date_time','<',$utc_date_now)->whereIn('invited.invitation_status',['PARTICIPATED']);
                });
            });
        return $requests;
    }

    protected function pendingDeposit () {
        
        $requests = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            ->join('organizations','invited.organization_id','=','organizations.id')
            ->join('products','products.id','=','depositor_requests.product_id')
            ->whereHas('offer.invited.organization')
            ->whereIn('deposits.status',['PENDING_DEPOSIT'])
            ->select([
                'deposits.*',
                'invited.*',
                'depositor_requests.*',
                'offers.*',
                DB::raw('depositor_requests.id as depositor_request_id'),
                DB::raw('deposits.status as status')
            ]);
        return $requests;
    }

    protected function activeDeposit() {
        $requests = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            ->join('organizations','invited.organization_id','=','organizations.id')
            ->join('products','products.id','=','depositor_requests.product_id')
            ->whereHas('offer.invited.organization')
            ->whereIn('deposits.status',['ACTIVE'])
            ->select([
                'deposits.*',
                'invited.*',
                'depositor_requests.*',
                'offers.*',
                DB::raw('depositor_requests.id as depositor_request_id'),
                DB::raw('deposits.status as status')
            ]);
        return $requests;
    }

    protected function listDeposits($status = ['MATURED','WITHDRAWN','INCOMPLETE','EARLY_REDEMPTION']) {
        
        $requests = Deposit::join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('invited', 'invited.id', '=', 'offers.invitation_id')
            ->join('depositor_requests', 'depositor_requests.id', '=', 'invited.depositor_request_id')
            ->join('organizations','invited.organization_id','=','organizations.id')
            ->join('products','products.id','=','depositor_requests.product_id')
            ->whereHas('offer.invited.organization')
            ->whereIn('deposits.status', $status);

            // DB::raw('depositor_requests.id as depositor_request_id')

        return $requests;
    }
}

<?php
namespace App\Http\Controllers\Dashboard\Admin;

use App\Constants;
use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LoginActivity;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > System Activity Logs");
        return view('dashboard.admin.logs.activity-logs');
    }

    public function getData(Request $request){
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

        $data = ActivityLog::join('users','users.id','=','activity_logs.user_id')
            ->join('role_user','role_user.user_id','=','users.id')
            ->join('roles','role_user.role_id','=','roles.id')
            ->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
                ->select([
            'activity_logs.*',
            'users.name',
            'users.email',
            'roles.display_name as role_name',
            'organizations.name as organizations_name'
        ]);

        $totalRecords = with(clone $data)->count();

        $search_columns = [
//                "sno",
            "name",
            "email",
            "role",
            "location",
            "from_location",
            "query_string",
            "organizations_name"
//            "event_date",
        ];

        if( !empty($searchValue) ) {
            $search_is_date=false;
            try {
                try{
                    $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                    $event_date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT,Constants::DATE_FORMAT);
                }catch (\Exception $exception) {
                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                    $event_date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT,Constants::DATE_FORMAT);
                }
                $data = $data->where('activity_logs.event_date', 'like', '%' . $event_date_in_utc . '%');
                $search_is_date=true;
            }catch (\Exception $exception){}

            if (!$search_is_date) {
                $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                    $query->where('users.name', 'like', '%' . $searchValue . '%');
                    foreach ($search_columns as $search_column) {
                        switch ($search_column) {
                            case'email':
                                $query->orWhere('users.email', 'like', '%' . $searchValue . '%');
                                break;
                            case'location':
                            case'from_location':
                            case'query_string':
                                $query->orWhere('activity_logs.' . $search_column, 'like', '%' . $searchValue . '%');
                                break;
                            case 'role':
                                $query->orWhere('roles.display_name', 'like', '%' . $searchValue . '%');
                                break;
                            case 'organizations_name':
                                $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                                break;
                        }
                    }
                });
            }
        }

        if(!$columnIndex && !is_numeric($columnIndex)){
            $data = $data->orderBy('activity_logs.id','DESC');
        }else{
            switch ($columnName){
                case 'name':
                    $data = $data->orderBy('users.name',strtoupper($columnSortOrder));
                    break;
                case 'email':
                    $data = $data->orderBy('users.email',strtoupper($columnSortOrder));
                    break;
                case'location':
                case'from_location':
                case'query_string':
                case'event_date':
                    $data = $data->orderBy('activity_logs.'.$columnName,strtoupper($columnSortOrder));
                    break;
                case 'role':
                    $data = $data->orderBy('roles.display_name',strtoupper($columnSortOrder));
                    break;
                case 'organizations_name':
                    $data = $data->orderBy('organizations.name',strtoupper($columnSortOrder));
                    break;
                default:
                    $data = $data->orderBy('activity_logs.id','DESC');
                    break;
            }
        }

        $totalRecordswithFilter = with(clone $data)->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        $i=1;
        foreach($data as $record) {

            $data_arr[] = array(
                "sno" => $i++,
                "name" => $record->name,
                "email" => $record->email,
                "role" => $record->role_name,
                "organizations_name" => $record->organizations_name,
                "location" => $record->location,
                "from_location" => $record->from_location,
                "query_string" => $record->query_string,
                "event_date" => $record->event_date ? changeDateFromUTCtoLocal($record->event_date,Constants::DATE_TIME_FORMAT_NO_SECONDS) : '-',
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response,200);
    }

    public function getLoginLogsData(Request $request){
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

        $data = LoginActivity::join('users','users.id','=','login_activities.user_id')
            ->join('role_user','role_user.user_id','=','users.id')
            ->join('roles','role_user.role_id','=','roles.id')
            ->leftJoin('users_organizations','users_organizations.user_id','=','users.id')
            ->leftJoin('organizations','users_organizations.organization_id','=','organizations.id')
            ->select([
                'login_activities.*',
                'users.name',
                'users.email',
                'roles.display_name as role_name',
                'organizations.name as organizations_name'
            ]);

        $totalRecords = with(clone $data)->count();

        $search_columns = [
//                "sno",
            "name",
            "email",
            "role",
            "activity_type",
            "user_agent",
            "organizations_name",
//            "event_date",
        ];

        if( !empty($searchValue) ) {
            $search_is_date=false;
            try {
                try{
                    $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                    $event_date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT,Constants::DATE_FORMAT);
                }catch (\Exception $exception) {
                    $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                    $event_date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT,Constants::DATE_FORMAT);
                }
                $data = $data->where('login_activities.event_time', 'like', '%' . $event_date_in_utc . '%');
                $search_is_date=true;
            }catch (\Exception $exception){}

            if (!$search_is_date) {
                $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                    $query->where('users.name', 'like', '%' . $searchValue . '%');
                    foreach ($search_columns as $search_column) {
                        switch ($search_column) {
                            case'email':
                                $query->orWhere('users.email', 'like', '%' . $searchValue . '%');
                                break;
                            case'activity_type':
                            case'user_agent':
                                $query->orWhere('login_activities.' . $search_column, 'like', '%' . $searchValue . '%');
                                break;
                            case 'role':
                                $query->orWhere('roles.display_name', 'like', '%' . $searchValue . '%');
                                break;
                            case 'organizations_name':
                                $query->orWhere('organizations.name', 'like', '%' . $searchValue . '%');
                                break;
                        }
                    }
                });
            }
        }

        if(!$columnIndex && !is_numeric($columnIndex)){
            $data = $data->orderBy('login_activities.id','DESC');
        }else{
            switch ($columnName){
                case 'name':
                    $data = $data->orderBy('users.name',strtoupper($columnSortOrder));
                    break;
                case 'email':
                    $data = $data->orderBy('users.email',strtoupper($columnSortOrder));
                    break;
                case'activity_type':
                case'user_agent':
                case'event_time':
                    $data = $data->orderBy('login_activities.'.$columnName,strtoupper($columnSortOrder));
                    break;
                case 'role':
                    $data = $data->orderBy('roles.display_name',strtoupper($columnSortOrder));
                    break;
                case 'organizations_name':
                    $data = $data->orderBy('organizations.name',strtoupper($columnSortOrder));
                    break;
                default:
                    $data = $data->orderBy('login_activities.id','DESC');
                    break;
            }
        }

        $totalRecordswithFilter = with(clone $data)->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        $i=1;
        foreach($data as $record) {

            $data_arr[] = array(
                "sno" => $i++,
                "name" => $record->name,
                "email" => $record->email,
                "role" => $record->role_name,
                "organizations_name" => $record->organizations_name,
                "activity_type" => $record->activity_type,
                "user_agent" => $record->user_agent,
                "event_date" => $record->event_time ? changeDateFromUTCtoLocal($record->event_time,Constants::DATE_TIME_FORMAT_NO_SECONDS) : '-',
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response,200);
    }
}
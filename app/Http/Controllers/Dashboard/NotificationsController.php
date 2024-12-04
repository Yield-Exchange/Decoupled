<?php
namespace App\Http\Controllers\Dashboard;

use App\Constants;
use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function countUnread(){
        $organization = \auth()->user()->organization;
        if(!$organization){
            return 0;
        }

        return UserNotification::where('sent_to_organization_id',$organization->id)
            ->whereHas('from')
            ->where('status','ACTIVE')
            ->count();
    }

    public function index(Request $request)
    {
        $user=\auth()->user();
        if(!$user->userCan('universal/notifications/page-access') || !$user->userCan('universal/notifications/delete-notifications')){
            return view('dashboard.403');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Notifications Page");
        return view('dashboard.notifications');
    }

    public function getData(Request $request)
    {
        $user=\auth()->user();

        $organization = $user->organization;
        if(!$user->userCan('universal/notifications/page-access') || !$user->userCan('universal/notifications/delete-notifications')){
            $response = array(
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            );
            return response()->json($response);
        }

        $notifications = UserNotification::select('notifications.*')->with(['me','from'])
            ->leftJoin('organizations','organizations.id','=','notifications.sent_to_organization_id')
            ->whereHas('from');
        $notifications = $notifications->where('sent_to_organization_id',$organization->id);

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
        $totalRecords = with(clone $notifications)->get()->count();

        $search_columns = [
            "institution_name",
            "details",
//            "send_date",
//            'status',
            "action"
        ];

        if( !empty($searchValue) ) {
            $search_is_date=false;
            try {
                $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                $timezone_offset = timezoneOffsetFromUTC(\auth()->user()->timezone);
                $notifications = $notifications->whereRaw("DATE(CONVERT_TZ(notifications.date_sent,'+00:00','".$timezone_offset."')) = '".$date->format("Y-m-d")."'");
                $search_is_date=true;
            }catch (\Exception $exception){}

            if (!$search_is_date) {
                $notifications = $notifications->where(function ($query) use ($searchValue, $search_columns) {
                    $query->where('organizations.name', 'like', '%' . $searchValue . '%');
                    foreach ($search_columns as $search_column) {
                        switch ($search_column) {
                            case 'details':
                                $query->orWhere('details', 'like', '%' . $searchValue . '%');
                                break;
                        }
                    }
                });
            }
        }

        if(!$columnIndex && !is_numeric($columnIndex)){
            $notifications = $notifications->orderBy('date_sent','DESC');
        }else{
            switch ($columnName){
                case 'send_date':
                    $notifications = $notifications->orderBy('date_sent',strtoupper($columnSortOrder));
                    break;
                case 'details':
                    $notifications = $notifications->orderBy('details',strtoupper($columnSortOrder));
                    break;
                case 'institution_name':
                    $notifications = $notifications->orderBy('organizations.name',strtoupper($columnSortOrder));
                    break;

            }
        }

        $data = $notifications;
        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        DB::table('notifications')/*->whereIn('id', $data->pluck('id')->toArray())*/
            ->where('status','!=','SEEN')
            ->where('sent_to_organization_id',\auth()->user()->organization->id)
            ->update(['status' => 'SEEN']);

            $user = auth()->user();
        foreach($data as $record){
            $action="";
            if($user->userCan('universal/notifications/delete-notifications')){
                $action='<a href="#" notification-id="'.CustomEncoder::urlValueEncrypt($record->id).'" onclick="return deleteIt(this);"><img style="height:10px;" src="'.asset('image/cross.png').'" class="img-responsive"></a>';
            }

            $data_arr[] = array(
                "send_date" => changeDateFromUTCtoLocal($record->date_sent,Constants::DATE_TIME_FORMAT_NO_SECONDS),
                "details" => ucfirst($record->details),
                "institution_name" => $record->from ? $record->from->name : '-',
                "action" => $action,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function deleteNotifications(Request $request)
    {
        $user = \auth()->user();
        if(!$user->userCan('universal/notifications/delete-notifications')){
            $response = array("success" => false, "message" => 'Access Denied', "data" => []);
            return response()->json($response, 403);
        }
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        $notification_id= CustomEncoder::urlValueDecrypt($request->notification_id);
        $notification = UserNotification::find($notification_id);
        if($notification) {
            $notification->delete();
        }

        $response = array("success"=>true, "message"=>"Notification deleted successfully", "data"=>[]);
        return response()->json($response, 200);
    }
}
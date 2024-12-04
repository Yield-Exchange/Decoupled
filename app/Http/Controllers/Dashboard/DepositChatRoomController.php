<?php
namespace App\Http\Controllers\Dashboard;

use App\Constants;
use App\CustomEncoder;
use App\Events\DepositChatEvent;
use App\Events\NotificationsCountEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Deposit;
use App\Models\UserNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DepositChatRoomController extends Controller
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
        return Chat::where('sent_to_organization_id',$organization->id)->whereHas('deposit',function ($query){
            $query->where('status','=','PENDING_DEPOSIT');
        })->where('status','NEW')->count();
    }

    public function index(Request $request,$deposit_id)
    {
        $user=\auth()->user();
        if(!$user->userCan('universal/chats/page-access') || !$user->userCan('universal/chats/send-messages')){
            return view('dashboard.403');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Chat Room");
        $deposit = Deposit::whereHas('offer.invited.depositRequest')->find(CustomEncoder::urlValueDecrypt($deposit_id));

        if (!$deposit){
            systemActivities(Auth::id(), json_encode($request->query()),"Chat Room, Unable to access the page.. deposit not found");
            alert()->error("Deposit not found, please retry or ".Constants::RESPONSE_MESSAGE_CONTACT_US);
            return redirect()->back();
        }

        $can_chat=$user->userCan('universal/chats/send-messages');
        return view('dashboard.deposit-chat-room',compact('deposit','deposit_id','can_chat'));
    }

    public function getMessages(Request $request,$deposit_id){
        $user=\auth()->user();
        if(!$user->userCan('universal/chats/read-messages')){
            $response = array("success" => false, "message" => "Unauthorized", "data" => []);
            return response()->json($response, 403);
        }
        $organization = $user->organization;

        $deposit = Deposit::whereHas('offer.invited.depositRequest')->find($deposit_id);
        if (!$organization || !$deposit || !in_array($organization->id,[$deposit->offer->invited->organization_id,$deposit->offer->invited->depositRequest->organization_id])){
            $response = array("success" => false, "message" => "Unauthorized", "data" => []);
            return response()->json($response, 403);
        }

        $chats = Chat::where('deposit_id','=',$deposit_id)->get();
        $data = [];
        if ($chats){
            foreach ($chats as $chat) {
                if($chat->sent_to_organization_id==$organization->id && $chat->status != 'SEEN') {
                    $chat->status = 'SEEN';
                    $chat->seen_at = now();
                    $chat->save();
                }
                $datum['id']=$chat->id;
                $datum['text']=$chat->message;
                $datum['created_at']=changeDateFromUTCtoLocal($chat->created_at,Constants::DATE_TIME_FORMAT_NO_SECONDS);
                $datum['sent_by']=$chat->by;
                $datum['sent_to']=$chat->to;
                $datum['is_mine']=$chat->byOrganization->id==$organization->id;
                $datum['file']=$chat->file ? url('uploads/'.$chat->file) : NULL;
                $datum['time_sent']=Carbon::parse($chat->created_at)->diffForHumans(null,false);
                array_push($data,$datum);
            }
        }

        $response = array("success" => true, "message" => "Messages fetched", "data" => $data);
        return response()->json($response, 200);
    }

    public function markAsRead(Request $request){
        $chat = Chat::find($request->id);
        $organization = \auth()->user()->organization;

        if ($organization && $chat && in_array($organization->id,[$chat->sent_to_organization_idd,$chat->sent_by_organization_id])){

            $chat->status='SEEN';
            $chat->seen_at=now();
            $chat->save();
            $response = array("success" => true, "message" => "Marked as read", "data" => []);
            return response()->json($response, 200);
        }
        $response = array("success" => false, "message" => "Unauthorized", "data" => []);
        return response()->json($response, 403);
    }

    public function store(Request $request)
    {
        $user=\auth()->user();
        if(!$user->userCan('universal/chats/send-messages')){
            return view('dashboard.403');
        }

        $validator = Validator::make($request->all(), [
//            'message' => 'nullable',
            'deposit_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array("success" => false, "message" => Arr::flatten($validator->messages()->get('*')), "data" => []);
            return response()->json($response, 400);
        }

        $deposit = Deposit::whereHas('offer.invited.depositRequest')->find($request->deposit_id);
        if ($deposit) {
            try {
                $user = \auth()->user();
                $organization = $user->organization;
                if(!$organization){
                    $response = array("success" => false, "message" => "Message not sent", "data" => []);
                    return response()->json($response, 409);
                }

                if (!in_array($organization->id,[$deposit->offer->invited->organization_id,$deposit->offer->invited->depositRequest->organization_id])){
                    $response = array("success" => false, "message" => "Unauthorized", "data" => []);
                    return response()->json($response, 403);
                }

                $file_path = null;
                if($request->hasFile('file')){
                    $validator = Validator::make($request->all(), [
                        'file' => 'required|mimes:jpeg,jpg,png,gif,pdf|max:25600'
                    ]);

                    if ($validator->fails()) {
                        $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
                        return response()->json($response, 400);
                    }

                    $file = $request->file('file');
                    $destinationPath=public_path() . '/uploads/';
                    $file_name = time().$file->getClientOriginalName().'.'.$file->extension();
                    if($file->move($destinationPath,$file_name)){
                        $file_path = $file_name;
                    }
                }

                $data = [
                    'sent_by_organization_id'=>$organization->id,
                    'sent_to_organization_id'=>$organization->type == "DEPOSITOR" ? $deposit->offer->invited->organization_id : $deposit->offer->invited->depositRequest->organization_id,
                    'sent_by' => $user->id,
                    'sent_to' => $organization->type == "DEPOSITOR" ? $deposit->offer->invited->invited_user_id : $deposit->offer->invited->depositRequest->user_id,
                    'message' => $request->filled('message') ? $request->message : '',
                    'deposit_id' => $deposit->id,
                    'status' => 'NEW',
                    'created_at' => getUTCDateNow(true),
                    'file' => $file_path,
                ];

                $data = Chat::create($data);
                
                broadcast(new DepositChatEvent($deposit,Chat::find($data->id)))->toOthers();

                $response = array("success" => true, "message" => "Message sent", "data" => [
                    'id'=>$data->id,
                    'text'=>$data->message,
                    'created_at'=>changeDateFromUTCtoLocal($data->created_at,Constants::DATE_TIME_FORMAT_NO_SECONDS),
                    'sent_by'=>$data->by,
                    'sent_to'=>$data->to,
                    'is_mine'=>$data->byOrganization->id==$organization->id,
                    'file'=>$data->file ? url('uploads/'.$data->file) : NULL,
                    'time_sent'=>Carbon::parse($data->created_at)->diffForHumans()
                ]);
                return response()->json($response, 200);
            }catch (\Exception $exception){
                Log::error($exception->getTraceAsString());
            }
        }

        $response = array("success" => false, "message" =>"Unable to send the message, please retry", "data" => []);
        return response()->json($response, 400);
    }

    public function sendNotifications($data){
        try {
            $sent_by=\auth()->user();
            $data['sent_by_organization_id']=$sent_by ? $sent_by->organization->id : 0;
            $data['sent_to_organization_id']=$data['organization_id'];
            UserNotification::create($data);
            broadcast(new NotificationsCountEvent([
                'organization_id'=>$data['organization_id']
            ]))->toOthers();
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
        }
    }

}
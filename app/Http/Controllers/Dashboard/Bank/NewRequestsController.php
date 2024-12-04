<?php

namespace App\Http\Controllers\Dashboard\Bank;

use App\Constants;
use App\CustomEncoder;
use App\Data\BankData;
use App\Http\Controllers\Controller;
use App\Mail\AdminMail;
use App\Mail\Bank\NewOfferMail;
use App\Mail\Bank\NonPartneredFiAcceptInvitationMail;
use App\Models\DepositRequest;
use App\Models\InvitedBank;
use App\Models\Offer;
use App\Models\SystemSetting;
use App\Models\UserPassword;
use App\Services\Bank\Offer\OfferService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class NewRequestsController extends Controller
{
    private $offer_service;
    public function __construct(OfferService $offer_service)
    {
        $this->middleware('auth');
        $this->middleware('auth.bank');
        $this->offer_service = $offer_service;
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/new-requests/page-access')) {
            return redirect()->to('access-denied');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Bank New Requests");
        return view('dashboard.bank.new-requests');
    }

    public function getNewRequests(Request $request)
    {
        return $this->offer_service->getNewRequests($request);
    }
    public function getData(Request $request)
    {
        return $this->offer_service->fetch($request);
    }

    public function placeOffer(Request $request, $request_id, $offer_id = null)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/new-requests/view-button') && !$user->userCan('bank/in-progress/edit-button')) {
            return redirect()->to('access-denied');
        }

        $offer = null;
        if ($request_id != 'null' && !$offer_id) {
            $deposit_request = DepositRequest::find(CustomEncoder::urlValueDecrypt($request_id));
            if (!$deposit_request) {
                systemActivities(Auth::id(), json_encode($request->query()), "Place offer page, Unable to access the page.. deposit request not found");
                alert()->error("Request not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US);
                return redirect()->back();
            }
            $invitation = InvitedBank::where('depositor_request_id', $deposit_request->id)->where('organization_id', $user->organization->id)->first();
            if (!$invitation->is_seen) {
                $invitation->markAsSeen();
            }
        } else {
            $offer = Offer::whereHas('invited.depositRequest')->find(CustomEncoder::urlValueDecrypt($offer_id));
            $deposit_request = $offer->invited->depositRequest;
            if (!$deposit_request) {
                systemActivities(Auth::id(), json_encode($request->query()), "Place offer page, Unable to access the page.. deposit request not found");
                alert()->error("Request not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US);
                return redirect()->back();
            }

            //            if (Carbon::parse($deposit_request->closing_date_time)->lessTh    an(getUTCTimeNow())){
            //                systemActivities(Auth::id(), json_encode($request->query()), "Update offer page, This offer can not updated");
            //                alert()->error("This offer can not updated");
            //                return redirect()->back();
            //            }
        }
        $unformattedusertimezone = Auth::user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);

        systemActivities(Auth::id(), json_encode($request->query()), "Bank Place Offer");
        $prime_rate = SystemSetting::where([['status', 'ACTIVE'], ['setting_type', 'rate']])->select(['key', 'rate_label', 'value'])->get();
        $deposit_request->closing_date_time = changeDateFromUTCtoLocal($deposit_request->closing_date_time);
        
        // $prime_rate = getSystemSettings('prime_rate')->value;
        return view('dashboard.bank.place-offer.index', compact('deposit_request', 'offer', 'formattedtimezone', 'prime_rate'));
    }

    public function submitPlaceOffer(Request $request)
    {
        $user = \auth()->user();
        if (!$user->userCan('bank/new-requests/submit-offer-button') && !$user->userCan('bank/in-progress/edit-button')) {
            $response = array("success" => false, "message" => 'Access Denied', "data" => []);
            return response()->json($response, 403);
        }

        return $this->offer_service->save($request, $user);
    }

    public function nonPartneredFiAcceptInvitation(Request $request)
    {
        $user = \auth()->user();
        if (!$user->isOrganizationAdmin()) {
            $response = array("success" => false, "message" => 'Access Denied', "data" => []);
            return response()->json($response, 403);
        }

        $validator = Validator::make($request->all(), [
            'action' => 'required'
        ]);

        if ($validator->fails()) {
            systemActivities(Auth::id(), json_encode($request->query()), "nonPartneredFiAcceptInvitation failed");
            return response()->json(['data' => [], 'message' => Arr::flatten($validator->messages()->get('*')), 'success' => true], 400);
        }

        $action = $request->action;
        switch ($action) {
            case 'DECLINE_TERMS_AND_CONDITIONS':
                $user_data = \auth()->user();
                $user_data->account_status = 'DECLINED_TERMS_AND_CONDITIONS';
                $user_data->save();
                InvitedBank::where('organization_id', $user_data->organization->id)->update([
                    'invitation_status' => 'DID_NOT_PARTICIPATE'
                ]);

                loginActivities("Decline T&C successfully", $request->server('HTTP_USER_AGENT'), $user_data->id);

                $request->session()->flush();
                Auth::logout();

                // Output to JSON format
                return response()->json(['data' => [], 'message' => "Declined T&C successfully", 'success' => true], 200);
            case 'ACCEPT_TERMS_AND_CONDITIONS':
                $user_data = \auth()->user();
                $organization = $user_data->organization;
                loginActivities("non partnered FI Accepted invitation by clicking YES to terms and conditions", $request->server('HTTP_USER_AGENT'), $user_data->id);
                $user_data->account_status = 'ACTIVE';
                $user_data->save();

                $pass = getRandomNumberBetween(11024, 103540);
                $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

                UserPassword::create([
                    'hash' => $hashed_password,
                    'user_id' => $user_data->id,
                    'created_at' => getUTCDateNow(true),
                    'updated_at' => getUTCDateNow(true)
                ]);

                Mail::to($organization->notifiableUsersEmails())->queue(new NonPartneredFiAcceptInvitationMail([
                    'message' => [
                        'name' => $organization->name,
                        'pass' => $pass
                    ],
                ]));

                $request->session()->flush();
                Auth::logout();

                return response()->json(['data' => [], 'message' => "You are required to re-login. An email has been sent to you with details for the next step.", 'success' => true], 200);
            case 'DECLINE_INVITATION_NON_PARTNERED_FI':
                $user_data = \auth()->user();
                $user_data->account_status = 'DECLINED_INVITATION';
                $user_data->save();

                InvitedBank::where('organization_id', $user_data->organization->id)->update([
                    'invitation_status' => 'DID_NOT_PARTICIPATE'
                ]);

                loginActivities("non partnered FI Declined invitation by clicking NO to terms and conditions", $request->server('HTTP_USER_AGENT'), $user_data->id);

                $request->session()->flush();
                Auth::logout();

                return response()->json(['data' => [], 'message' => "Decline invitation was successful", 'success' => true], 200);
                break;
            default:
                break;
        }
    }
}

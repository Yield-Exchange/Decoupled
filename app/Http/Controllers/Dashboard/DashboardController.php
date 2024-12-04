<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\BankData;
use App\Data\DepositorData;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\DepositRequest;
use App\Models\Offer;
use App\Models\Organization;
use App\Traits\BaseMiddlewareTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\u;

class DashboardController extends Controller
{
    use BaseMiddlewareTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = \auth()->user();
            $organization = $user->organization;
            $type = $organization ? $organization->type : "";
            switch ($type) {
                case 'DEPOSITOR':
                case 'BANK':
                    if ($this->shouldUpdatePassword($user)) {
                        alert()->warning("Please update your password");
                        return redirect()->route('force-update-password');
                    }

                    if ($type == "BANK") {
                        if ($organization->is_non_partnered_fi == 1 && $user->account_status == 'ACTIVE' && $user->password_changed == 0) {
                            alert()->warning("Please complete account settings in order to use the Yield Exchange Limited Version");
                            return redirect()->route('user.account-setting');
                        }
                    }
                    break;
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $user_type = Auth::user();
        switch (get_user_type($user_type)) {
            case 'BANK':
                return $this->bankDashboard($request);
                break;
            case 'DEPOSITOR':
                return $this->depositorDashboard($request);
                break;
            default:
                alert()->error("Permission denied");
                return redirect('/logout');
                break;
        }
    }

    private function bankDashboard(Request $request)
    {
        systemActivities(Auth::id(), json_encode($request->query()), "Bank Dashboard");

        $new_requests = BankData::newRequestData(3);

        $in_progress_data = BankData::inProgressData(3);

        $pending_deposit = BankData::pendingDepositData(3);
        $active_deposit = BankData::activeDepositData(3);

        return view('dashboard.bank.dashboard', compact('new_requests', 'in_progress_data', 'pending_deposit', 'active_deposit'));
    }

    private function depositorDashboard(Request $request)
    {
        systemActivities(Auth::id(), json_encode($request->query()), "Depositor Dashboard");

        $review_offers = DepositorData::reviewOfferData(3);

        $pending_contract = DepositorData::pendingDepositData(3);

        $active_contract = DepositorData::activeDepositData(3);

        return view('dashboard.depositor.dashboard', compact('review_offers', 'pending_contract', 'active_contract'));
    }

    public function adminDashboard(Request $request)
    {
        systemActivities(Auth::id(), json_encode($request->query()), "Admin Dashboard");
        $total_banks = Organization::where('type', 'BANK')->whereIn('status', systemActiveUsersStatuses())->count();

        $total_depositors = Organization::where('type', 'DEPOSITOR')->whereIn('status', systemActiveUsersStatuses())->count();

        $new_deposit_requests = DepositRequest::where('request_status', 'ACTIVE')->count();

        $bids_month = [];
        $banks_month = [];
        $dep_month = [];
        $request_month = [];
        $contract_month = [];
        $date_now = getUTCTimeNow();
        $date_now_minus_1_year = getUTCTimeNow()->subMonths(12);

        for ($i = 1; $i <= 12; $i++) {

            $bids_month[$i] = Offer::whereMonth('created_date', $i)
                ->where(function ($query) use ($date_now_minus_1_year, $date_now) {
                    $query->where(DB::raw('DATE(created_date)'), '>=', $date_now_minus_1_year->format("Y-m-d"))
                        ->orWhere(DB::raw('DATE(created_date)'), '<=', $date_now->format("Y-m-d"));
                })->count();

            $banks_month[$i] = User::whereHas('roleType', function ($query) {
                $query->where('description', 'Bank');
            })->whereIn('account_status', systemActiveUsersStatuses())->whereMonth('account_opening_date', $i)
                ->where(function ($query) use ($date_now_minus_1_year, $date_now) {
                    $query->where(DB::raw('DATE(account_opening_date)'), '>=', $date_now_minus_1_year->format("Y-m-d"))
                        ->orWhere(DB::raw('DATE(account_opening_date)'), '<=', $date_now->format("Y-m-d"));
                })->count();

            $dep_month[$i] = User::whereHas('roleType', function ($query) {
                $query->where('description', 'Depositor');
            })->whereIn('account_status', systemActiveUsersStatuses())->whereMonth('account_opening_date', $i)
                ->where(function ($query) use ($date_now_minus_1_year, $date_now) {
                    $query->where(DB::raw('DATE(account_opening_date)'), '>=', $date_now_minus_1_year->format("Y-m-d"))
                        ->orWhere(DB::raw('DATE(account_opening_date)'), '<=', $date_now->format("Y-m-d"));
                })->count();

            $request_month[$i] = DepositRequest::whereMonth('created_date', $i)
                ->where(function ($query) use ($date_now_minus_1_year, $date_now) {
                    $query->where(DB::raw('DATE(created_date)'), '>=', $date_now_minus_1_year->format("Y-m-d"))
                        ->orWhere(DB::raw('DATE(created_date)'), '<=', $date_now->format("Y-m-d"));
                })->count();

            $contract_month[$i] = Deposit::whereMonth('created_at', $i)
                ->where(function ($query) use ($date_now_minus_1_year, $date_now) {
                    $query->where(DB::raw('DATE(created_at)'), '>=', $date_now_minus_1_year->format("Y-m-d"))
                        ->orWhere(DB::raw('DATE(created_at)'), '<=', $date_now->format("Y-m-d"));
                })->count();
        }


        $data = [
            'total_banks' => $total_banks,
            'total_depositors' => $total_depositors,
            'new_requests' => $new_deposit_requests
        ];
        return view('dashboard.admin.dashboard', compact('data', 'bids_month', 'contract_month', 'request_month', 'contract_month', 'dep_month', 'banks_month'));
    }

    public function switchOrganization(Request $request)
    {
        $user = \auth()->user();
        if (!can_switch_to_organizations($user)) {
            systemActivities(Auth::id(), json_encode($request->query()), "Switching to an organization failed, not enabled to switch organizations");
            //            $response = array("success" => false, "message" => 'Switching to the organization failed, You are not allowed to switch organizations', "data" => []);
            //            return response()->json($response, 403);
            alert()->error('Switching to the organization failed, You are not allowed to switch organizations');
            // return redirect()->back();
            return response()->json([
                'success' => false,
                'message' => 'Switching to the organization failed. You are not allowed to switch organizations.'
            ], 403);
        }

        if ($user->organization->id == $request->organization_id) {
            systemActivities(Auth::id(), json_encode($request->query()), "Switching to an organization failed, already switched to the same organization");
            //            $response = array("success" => false, "message" => 'Switching to the organization failed, You are already switched to the same organization', "data" => []);
            //            return response()->json($response, 403);
            return response()->json([
                'success' => false,
                'message' => 'Switching to the organization failed, You are already switched to the same organization'
            ], 403);
            // alert()->error('Switching to the organization failed, You are already switched to the same organization');
            // return redirect()->back();
        }

        $allowed_organizations = $user->allowedOrganizations();
        $allowed_organization_ids = !empty($allowed_organizations) ? $allowed_organizations->pluck('id')->toArray() : [];
        if (!in_array($request->organization_id, $allowed_organization_ids)) {
            systemActivities(Auth::id(), json_encode($request->query()), "Switching to an organization failed, not a user of the organization");
            //            $response = array("success" => false, "message" => 'Switching to the organization failed, You are not a user in the organization', "data" => []);
            //            return response()->json($response, 403);
            return response()->json([
                'success' => false,
                'message' => 'Switching to the organization failed, You are not a user in the organization'
            ], 403);
            // alert()->error('Switching to the organization failed, You are not a user in the organization');
            // return redirect()->back();
        }

        $organization_to_switch = Organization::find($request->organization_id);
        if (!$organization_to_switch || !$organization_to_switch->allow_multi_organizations) {
            systemActivities(Auth::id(), json_encode($request->query()), "Switching to an organization failed, setting not enabled for the organization");
            //            $response = array("success" => false, "message" => 'Switching to the organization failed, Not allowed by system administrator', "data" => []);
            //            return response()->json($response, 403);
            return response()->json([
                'success' => false,
                'message' => 'Switching to the organization failed, Not allowed by system administrator'
            ], 403);
            // alert()->error('Switching to the organization failed, Not allowed by system administrator');
            // return redirect()->back();
        }

        // allow the user to switch
        archiveTable($user->id, "users", \auth()->id(), "Switched Organization");
        $user->switched_organization_id = $request->organization_id;
        $user->save();

        systemActivities(Auth::id(), json_encode($request->query()), "Switching to the organization successful");
        //        $response = array("success" => true, "message" => 'You have been switched successfully', "data" => []);
        //        return response()->json($response, 200);
        // alert()->success('You have been switched successfully');
        return response()->json([
            'success' => true,
            'message' => 'Switching to the organization failed, Not allowed by system administrator'
        ], 200);
        // return redirect()->to('/launchpad');
    }
}

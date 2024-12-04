<?php

namespace App\Http\Controllers\Dashboard\Depositor;

use App\Constants;
use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\DepositRequest;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SummaryScreensController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.depositor');
    }

    public function view_offers_summary_screen(Request $request, $offer_id)
    {
        $user = \auth()->user();
        if (
            !$user->userCan('depositor/active-deposits/review-offers')
            && !$user->userCan('depositor/pending-deposits/review-offers')
            && !$user->userCan('depositor/review-offers/view-offers-button')
        ) {
            return view('dashboard.403');
        }

        $offer = Offer::whereHas('invited.organization')->find(CustomEncoder::urlValueDecrypt($offer_id));
        if (!$offer) {
            systemActivities(Auth::id(), json_encode($request->query()), "Offer summary screen, Unable to access the page.. deposit not found");
            alert()->error("Offer not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US);
            return redirect()->back();
        }

        if (!$offer->is_seen) {
            $offer->markAsSeen();
        }

        $contract = $offer->deposit;
        $offer->rate_held_until = changeDateFromUTCtoLocal($offer->rate_held_until);
        $deposit_request = $offer->invited->depositRequest;
        systemActivities(Auth::id(), json_encode($request->query()), "Offer summary screen");
        $for = !empty($contract) ? "deposit" : "offer";

        switch ($for) {
            case 'deposit':
                $view = 'dashboard.depositor.summary-screens.deposit';
                break;
            case 'offer':
            default:
                $view = 'dashboard.depositor.summary-screens.offer';
                break;
        }
        return view($view, compact('offer', 'contract', 'deposit_request'));
    }

    public function view_request_summary(Request $request, $request_id)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/view-request')) {
            return view('dashboard.403');
        }

        $deposit_request = DepositRequest::find(CustomEncoder::urlValueDecrypt($request_id));
        if (!$deposit_request) {
            systemActivities(Auth::id(), json_encode($request->query()), "Depositor Request summary screen, Unable to access the page.. deposit not found");
            alert()->error("Deposit request not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US);
            return redirect()->back();
        }

        $contract = Deposit::whereHas('offer.invited', function ($query) use ($deposit_request) {
            $query->where('depositor_request_id', $deposit_request->id);
        })->first();
        systemActivities(Auth::id(), json_encode($request->query()), "Depositor Request summary screen");
        return view('dashboard.depositor.summary-screens.request', compact('deposit_request', 'contract'));
    }

    public function view_invited_institutions(Request $request, $request_id)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/review-offers/invited-institutions')) {
            return view('dashboard.403');
        }

        $deposit_request = DepositRequest::find(CustomEncoder::urlValueDecrypt($request_id));
        if (!$deposit_request) {
            systemActivities(Auth::id(), json_encode($request->query()), "Depositor View Invited Institutions, Unable to access the page.. deposit not found");
            alert()->error("Deposit request not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US);
            return redirect()->back();
        }
        systemActivities(Auth::id(), json_encode($request->query()), "Depositor View Invited Institutions");
        return view('dashboard.depositor.summary-screens.invited_institutions', compact('deposit_request', 'request_id'));
    }

    public function review_offers_summary(Request $request, $deposit_id, $request_id = null)
    {
        $user = \auth()->user();
        if (!$user->userCan('depositor/active-deposits/review-offers') || !$user->userCan('depositor/pending-deposits/review-offers')) {
            return view('dashboard.403');
        }

        if ($request_id == null) {
            $deposit = Deposit::find(CustomEncoder::urlValueDecrypt($deposit_id));
            if (!$deposit) {
                systemActivities(Auth::id(), json_encode($request->query()), "Deposit View Offers, Unable to access the page.. deposit not found");
                alert()->error("Deposit request not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US);
                return redirect()->back();
            }
            $depositor_request_id = $deposit->offer->invited->depositor_request_id;
        } else {
            $depositor_request_id = CustomEncoder::urlValueDecrypt($request_id);
        }

        $depositor_request_id = CustomEncoder::urlValueEncrypt($depositor_request_id);
        systemActivities(Auth::id(), json_encode($request->query()), "Depositor Review Offers Summary Screen");
        return view('dashboard.depositor.summary-screens.review_offers_summary', compact('depositor_request_id'));
    }
}

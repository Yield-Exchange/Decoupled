<?php

namespace App\Http\Controllers\Dashboard\Bank;

use App\Constants;
use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SummaryScreensController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.bank');
    }

    public function offer_summary_screen(Request $request, $offer_id)
    {
        // Log::alert('hhhhhhhhhh');
        // Log::alert(json_encode($request->all()));
        $offer = Offer::with('counterOffers')->find(CustomEncoder::urlValueDecrypt($offer_id));
        if (!$offer) {
            systemActivities(Auth::id(), json_encode($request->query()), "Offer summary screen, Unable to access the page.. offer not found");
            alert()->error("Offer not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US);
            return redirect()->back();
        }

        $offer->counterOffers->transform(function ($record) {
            $record->counter_offer_id_encoded = CustomEncoder::urlValueEncrypt($record->id);
            return $record;
        });

        $contract = $offer->deposit;
        $deposit_request = $offer->invited->depositRequest;
        $offer->rate_held_until = changeDateFromUTCtoLocal($offer->rate_held_until);

        systemActivities(Auth::id(), json_encode($request->query()), "Bank offer summary screen");
        $from_page = $request->fromPage;

        return view('dashboard.bank.summary-screens.offer', compact('offer', 'contract', 'deposit_request','from_page'));
    }

    public function deposit_summary_screen(Request $request, $offer_id)
    {
        $offer_id = CustomEncoder::urlValueDecrypt($offer_id);
        $contract = Deposit::whereHas('offer', function ($query) use ($offer_id) {
            $query->where('id', $offer_id);
        })->first();

        if (!$contract) {
            systemActivities(Auth::id(), json_encode($request->query()), "Deposit summary screen, Unable to access the page.. deposit not found");
            alert()->error("Deposit not found, please retry or " . Constants::RESPONSE_MESSAGE_CONTACT_US);
            return redirect()->back();
        }

        $deposit_request = $contract->offer->invited->depositRequest;
        $offer = $contract->offer;
        $depositor = $offer->invited->depositRequest->user;
        $organization = $offer->invited->depositRequest->organization;
        // $user = auth()->user();
        systemActivities(Auth::id(), json_encode($request->query()), "Bank summary screen");
        return view('dashboard.bank.summary-screens.deposit', compact('deposit_request', 'contract', 'offer','organization','depositor'));
    }
}

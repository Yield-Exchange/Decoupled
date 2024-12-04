<?php

use App\Models\Deposit;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('CT_Trade_Request_Deposit_chat.{deposit_reference_no}', function ($deposit_reference_no) {

    Log::info('Testing broadcast Testing broadcast.Refrence Number:');

    return true;
});
Broadcast::channel('CT_Trade_Request_offer_chat.{offer_reference_no}', function ($offer_reference_no) {

    Log::info('Testing broadcast Testing broadcast.Refrence Number:');

    return true;
});
Broadcast::channel('Deposit_chat.{deposit_reference_no}', function ($user,$deposit_reference_no) {
    $deposit = Deposit::whereHas('offer.invited.depositRequest')
        ->where('reference_no',$deposit_reference_no)
        ->first();

    if(auth()->check() && $deposit){
        $organization = auth()->user()->organization;
        if(!$organization){
            return false;
        }
       return in_array($organization->id,[$deposit->offer->invited->organization_id,$deposit->offer->invited->depositRequest->organization_id]);
   }
   return false;
});

Broadcast::channel('NotificationsCount.{organization_id}', function ($user,$organization_id) {
    $organization = auth()->user()->organization;
    if(!$organization){
        return false;
    }
    return auth()->check() && $organization->id==$organization_id;
});

Broadcast::channel('DepositChatCountMessages.{organization_id}', function ($user,$organization_id) {
    $organization = auth()->user()->organization;
    if(!$organization){
        return false;
    }
    return auth()->check() && $organization->id==$organization_id;
});

@extends('emails.new-master')
@section('page-content')
<div style="padding: 0 5%;margin-right:auto; margin-left:auto;" class="responsive">
    <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
        style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
        width="100%" align="center">
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="marign-top: 20px; margin-bottom:20px;color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Trade Cancelled
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/OBJECTS.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
            <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
                <p class="notify"
                    style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center; color:#252525"
                    align="center"> <span style="color: #5063F4;font-family: Montserrat;font-size: 20px;font-style: normal;
                    font-weight: 700;line-height: 26px;">
                        {{$CTRequest->CGOffer->c_t_trade_request->inviter->name}} </span> has cancelled their <span
                        style="color: #5063F4;font-family: Montserrat;font-size: 20px;font-style: normal;
                    font-weight: 700;line-height: 26px;">{{$CTRequest->CGOffer->product->name}}</span> Investment for
                    the trade ID <span style="color: #5063F4;font-family: Montserrat;font-size: 20px;font-style: normal;
                    font-weight: 700;line-height: 26px;">{{$CTRequest->deposit_reference_no}}</span>
                </p>
            </div>



        </div>
        @endsection
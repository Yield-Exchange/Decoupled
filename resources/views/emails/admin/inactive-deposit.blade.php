@extends('emails.new-master')
@section('page-content')
<div style="padding: 0 5%;margin-right:auto; margin-left:auto;" class="responsive">
       <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
        style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
        width="100%" align="center">
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="marign-top: 20px; margin-bottom:20px;color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Inactive Deposit
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/OBJECTS.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">

            <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
                <p class="notify"
                    style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center; color:#252525"
                    align="center">{{$organization_name}} has indicated an early redemption of the deposit <span style="color: #5063F4;font-family: Montserrat;font-size: 20px;font-style: normal;font-weight: 800;line-height: 125.4%;">{{$header}}</span></p>

            </div>
            @if ($user_type == 'Bank')
                <p style="color: #252525;text-align: center;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 300;line-height: normal;">Please note redemption is only initiated in the Yield Exchange platform. If funds have not already been release you may wish to contact {{$organization_name}} for next steps.</p>
            @endif
            <p></p>
        </div>
        @endsection
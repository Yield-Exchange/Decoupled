@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/game-icons_cash.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Deposit Posted
                </span>
            </div>
        </div>

        <div class="w-100 d-flex justify-content-center">
            <p class="action-message"
                style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Congratulations! Your GIC purchase of <span
                    style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal">
                    CAD <br>
                    {{ number_format($product['purchased_amount']) }} </span> has
                successfully been processed.</p>
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/gic-purchased.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>

        <div class="w-100 d-flex justify-content-center">
            <p class="suggest-action"
                style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal;text-align:center;">
                Here's what you can do now...</p>
        </div>


        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/game-icons_ringing-alarm.png') }}" style="padding: 0; margin: 0;"
                    height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Set Reminder:</span>
                <span style="margin-left: 10px;"> Start making a plan for your future funds.</span>
            </p>
        </div>
        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/mdi_folder-eye.png') }}" style="padding: 0; margin: 0;" height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> View Account: </span>
                <span style="margin-left: 10px;"> Keep up to date of your earnings.</span>
            </p>
        </div>
        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/material-symbols_explore.png') }}" style="padding: 0; margin: 0;"
                    height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Explore </span>
                <span style="margin-left: 10px;"> Check out other new options or to renew existing GICs.</span>
            </p>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('/login') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    Login In To Account
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="w-100 d-flex justify-content-center">
            <p class="discover-login"
                style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">Explore competitive rates and flexible terms to help you maximize your savings.
            </p>
        </div>
    </div>
@endsection

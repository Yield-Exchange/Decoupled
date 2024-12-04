@extends('emails.new-master')
@section('page-content')
    <div style="padding:0 5%; margin-left: auto; margin-right: auto;" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/game-icons_cash.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 11px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Campaign Status
                </span>
            </div>
        </div>
        <div style="margin-top:20px; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            No Campaigns
        </div>
        <div>

            <p style="color:#252525; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Create a 
                <span style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal; text-align:center">
                    new campaign today</span> 
                    to reach a range of depositors looking for competitive rates
                </p>
        </div>
        <div>
            <p
                style="color:#5063F4; font-family:Montserrat; font-size:32px; font-style:normal; font-weight:700; line-height:normal">
                Here's how you do it</p>
        </div>

        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/icon-park-solid-reload.png') }}" style="padding: 0; margin: 0;"
                    height="25">
                <span style="margin-left: 10px;">Click on build a campaign and give it a unique name</span>
            </p>
        </div>


        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/mdi_folder-add.png') }}" style="padding: 0; margin: 0;" height="25">
                <span style="margin-left: 10px;">Add or create new products that will be in your campaign</span>
            </p>
        </div>

        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/streamline_user-add-plus-solid.png') }}" style="padding: 0; margin: 0;" height="25">
                <span style="margin-left: 10px;">Add depositors and groups to your campaign</span>
            </p>
        </div>

        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/mdi_cart-favorite.png') }}" style="padding: 0; margin: 0;" height="25">
                <span style="margin-left: 10px;">Add competitive rates to your added campaign products</span>
            </p>
        </div>
        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/mdi_favorite-add.png') }}" style="padding: 0; margin: 0;" height="25">
                <span style="margin-left: 10px;">Feature products in your campaign to give them prominence</span>
            </p>
        </div>


        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('/campaigns/') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    Create New Campaign
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div>
            <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">Discover a world of exclusive investors waiting for you <a
                    href="{{ url('/request-an-account') }}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{ url('login') }}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
        <div>
            <p style="color: #000;text-align: center;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 300;line-height: normal;">
            Opt out of receiving marketing emails?
            <a href="{{ url('/unsubscribe/no-campaign/' . $user_id . '/' . $email) }}" style="color:#5063F4;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 700;line-height: normal;text-decoration-line: underline;">Unsubscribe</a></p>
        </div>
    </div>
@endsection

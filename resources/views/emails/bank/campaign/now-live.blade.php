@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/campaign-status-state.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Campaign Status
                </span>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            width="100%" align="center">
            Your campaign is now live
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/livecampaign.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>

        <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
            <p class="action-message"
                style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Exciting news! Your <span
                    style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal">
                    {{ json_encode($campaigndetails->campaign_name) }} </span>
                Campaign
                is now live and open to depositors</p>
        </div>
        <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
            <p class="suggest-action"
                style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal; text-align:center"
                align="center">
                Here's what you can do to next...</p>
        </div>


        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/icon-park-solid_reload.png') }}" style="padding: 0; margin: 0;"
                    height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Feature Products: </span>
                <span style="margin-left: 10px;">to make them more prominent to depositors</span>
            </p>
        </div>
        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/entypo_wallet.png') }}" style="padding: 0; margin: 0;" height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Set up a new campaign: </span>
                <span style="margin-left: 10px;"> to reach out to more depositors</span>
            </p>
        </div>

        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/entypo_wallet.png') }}" style="padding: 0; margin: 0;" height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Reach Out: </span>
                <span style="margin-left: 10px;"> should you have any doubts or need help</span>
            </p>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('/campaigns/summary/') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    View Product
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
                    href="{{ url('request-an-account')}}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{url('login')}}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
    </div>
@endsection

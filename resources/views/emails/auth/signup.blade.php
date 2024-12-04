@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%; margin-right:auto; margin-left:auto;" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 2rem; margin-bottom: 2rem;">
            <div
                style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/signup/book-v2.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 11px; font-style: normal; font-weight: 400; line-height: 14px;">
                    User Onboard
                </span>
            </div>
        </div>

        <div style="text-transform: capitalize; font-family:Montserrat; font-size:30px; font-style:normal; font-weight:700; line-height:33px; text-align:center"
            align="center">
            Welcome to Yield Exchange <br>
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/signup/partner-pana.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>

        <div
            style="margin-top:20px; color#252525; text-align:center; font-family:Montserrat; font-size:24px;font-style:normal;font-weight:300;
        line-height:normal;">
            The all-in-one treasury management platform that makes it easy to find the most competitive rates fast
        </div>
        <div>

            <p style="text-transform: capitalize; color:#5063F4; font-family:Montserrat; font-size:32px; font-style:normal; font-weight:700; line-height:normal; text-align:center"
                align="center">
                Here’s what you can do now..</p>
        </div>
        @if ($user_type == 'DEPOSITOR')
            <div
                style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
                <p
                    style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                    <img src="{{ asset('assets/signup/game-icons_ringing-alarm.png') }}" style="padding: 0; margin: 0;"
                        height="25">
                    <span style="color: #5063F4; font-weight: 700; margin-left: 10px;">Post a request: </span>
                    <span style="margin-left: 10px;">to over {{ $count }} financial institutions in Yield
                        Exchange.</span>
                </p>
            </div>
            <div
                style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
                <p
                    style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                    <img src="{{ asset('assets/signup/mdi_folder-eye.png') }}" style="padding: 0; margin: 0;"
                        height="25">
                    <span style="color: #5063F4; font-weight: 700; margin-left: 10px;">View Rates: </span>
                    <span style="margin-left: 10px;">from over {{ $count }} financial institutions in Yield
                        Exchange.</span>
                </p>
            </div>
            <div
                style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
                <p
                    style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                    <img src="{{ asset('assets/signup/material-symbols_explore.png') }}" style="padding: 0; margin: 0;"
                        height="25">
                    <span style="color: #5063F4; font-weight: 700; margin-left: 10px;">Add additional users? </span>
                    <span style="margin-left: 10px;">add more seats to your Yield Exchange Acount</span>
                </p>
            </div>
        @else
            <div
                style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
                <p
                    style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                    <img src="{{ asset('assets/signup/game-icons_ringing-alarm.png') }}" style="padding: 0; margin: 0;"
                        height="25">
                    <span style="color: #5063F4; font-weight: 700; margin-left: 10px;">Send us your all in rates: </span>
                    <span style="margin-left: 10px;">these rates are shared with over {{$count}} depositors</span>
                </p>
            </div>
            <div
                style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
                <p
                    style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                    <img src="{{ asset('assets/signup/mdi_folder-eye.png') }}" style="padding: 0; margin: 0;"
                        height="25">
                    <span style="color: #5063F4; font-weight: 700; margin-left: 10px;">Share your wire instructions: </span>
                    <span style="margin-left: 10px;">this will help settle your GICs in T+1 days</span>
                </p>
            </div>
            <div
                style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
                <p
                    style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                    <img src="{{ asset('assets/signup/material-symbols_explore.png') }}" style="padding: 0; margin: 0;"
                        height="25">
                    <span style="color: #5063F4; font-weight: 700; margin-left: 10px;">Add additional users? </span>
                    <span style="margin-left: 10px;">add more seats to your Yield Exchange Acount</span>
                </p>
            </div>
        @endif

    </div>
@endsection

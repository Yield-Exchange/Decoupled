@extends('emails.new-master')
    @section('page-content')
    <div>
            <div style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center" align="center">
                Welcome to Yield Exchange
            </div>
            <div style="width: 100%; text-align: center; margin-top: 20px;">
                <div
                    style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                    <img src="{{ asset('assets/emails/book-v2.png') }}" alt=""
                        style="vertical-align: middle; margin-right:8px">
                    <span
                        style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                        User Onboard
                    </span>
                </div>
            </div>
            <div>
                <img src="  {{asset('assets/emails/welcome.png') }}" alt="" style="max-height:400px">
            </div>
            <div>
                <p style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center" align="center">The all-in-one treasury management platform that makes it easy to find the most
                    competitive rates fast</p>
            </div>
            <div>
                <p style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal">Here’s what you can do now..</p>
            </div>
            <div style="background:#F4F5F6; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:12px; padding:10px">
                
                <p style="color:#252525; font-weight:300 display: flex; align-items: center; text-align: start;">
                    <img src="{{asset('assets/emails/game-icons_ringing-alarm.png')}}" height="25" alt="">
                    <span style="color:#5063F4; font-weight:700">   
                        Post a request:   
                    </span>
                    to over 20 financial institutions in Yield Exchange.
                </p>
            </div>
            <div style="background:#F4F5F6; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:12px; padding:10px">
                
                   
                <p style="color:#252525; font-weight:300 display: flex; align-items: center; text-align: start;">
                    <img src="{{assets('assets/emails/mdi_folder-eye.png')}}" height="25" alt="">
                     <span style="color:#5063F4; font-weight:700">   
                        View Rates:   
                    </span>
                   from over 20 financial institutions in Yield Exchange.
                </p>
            </div>
            <div style="background:#F4F5F6; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:12px; padding:10px">
                <p style="color:#252525; font-weight:300 display: flex; align-items: center; text-align: start;">
                    <img src="{{asset('assets/emails/material-symbols_explore.png')}}" height="25" alt="">
                    <span style="color:#5063F4; font-weight:700">  
                         Add additional users?   
                    </span>
                    add more seats to your Yield Exchange Acount
                </p>
            </div>
        </div>
    @endsection
@extends('emails.auth.newSignUp.master')
@section('page-content')
<div style="padding:0.5%; margin:0 auto; width:80%">
    <div style="width: 100%; text-align: center; margin-top: 2rem; margin-bottom: 2rem;">
        <div
            style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
            <img src="{{ asset('assets/signup/mdi_register.png') }}" alt=""
                style="vertical-align: middle; margin-right:8px">
            <span
                style="color: #FFF; font-family: Montserrat; font-size: 11px; font-style: normal; font-weight: 400; line-height: 14px;">
                Registration Status
            </span>
        </div>
    </div>
 
        <div style="text-transform: capitalize; font-family:Montserrat; font-size:30px; font-style:normal; font-weight:700; line-height:33px; text-align:center" align="center">
            Registration complete! <br>
            <span style="text-transform: capitalize; font-family:Montserrat; font-size:30px; font-style:normal; font-weight:400; line-height:33px; text-align:center">
                You are now on our waiting list.
            </span>
        </div>
        <div>
            <img src="{{ asset('assets/signup/Product-quality-pana.png') }}"  alt="" style="max-height:400px">
        </div>
        <div>
            
            <p style="text-transform: capitalize; color:#5063F4; font-family:Montserrat; font-size:32px; font-style:normal; font-weight:700; line-height:normal; text-align:center" align="center">
                Next Steps</p>
        </div>
        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/signup/mdi_email-fast.png') }}" style="padding: 0; margin: 0;"
                    height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;">Updates: </span>
                <span style="margin-left: 10px;">We will send you emails on new product features and updates</span>
            </p>
        </div>
        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/signup/mdi_talk.png') }}" style="padding: 0; margin: 0;"
                    height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;">Reach Out:  </span>
                <span style="margin-left: 10px;">If you need help getting your account set up and running</span>
            </p>
        </div>
    </div>
@endsection
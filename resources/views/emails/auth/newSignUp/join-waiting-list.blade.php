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
            <img src="{{ asset('assets/signup/Process-pana-2.png') }}"  alt="" style="max-height:400px">
        </div>
        <div>
            
            <p style="text-transform: capitalize; color:#5063F4; font-family:Montserrat; font-size:32px; font-style:normal; font-weight:700; line-height:normal; text-align:center" align="center">
               we will alert you once we wrap up the personal investor module!</p>
        </div>
    </div>
@endsection
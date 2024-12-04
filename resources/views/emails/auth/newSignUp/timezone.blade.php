@extends('emails.auth.newSignUp.master')
@section('page-content')
    <div style="padding:0.5%; margin:0 auto; width:80%">
        <div style="width: 100%; text-align: center; margin-top: 2rem;  margin-bottom: 2rem;">
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

        <div style="text-transform: capitalize; font-family:Montserrat; font-size:29px; font-style:normal; font-weight:700; line-height:33px; text-align:center"
            align="center">
            Registration details saved
        </div>
        <div>
            <img src="{{ asset('assets/signup/Time-management-pana.png') }}" alt="" style="max-height:400px">
        </div>
        <div>

            <p style="text-transform: capitalize; color:#5063F4; font-family:Montserrat; font-size:32px; font-style:normal; font-weight:700; line-height:normal; text-align:center"
                align="center">
                we will alert you once we are available in your time zone</p>
        </div>
    </div>
@endsection

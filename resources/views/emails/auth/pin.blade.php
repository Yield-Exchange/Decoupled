@extends('emails.new-master')

@section('page-content')
    <div style="padding: 0 5%; margin-right:auto; margin-left:auto" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #5063f4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/security-lock.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Account Security
                </span>
            </div>
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/otp-image.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>
        <div style="width: 100%; display: table; margin: 0 auto;">
            <p class="unique-key-text"
                style="color: var(--yield-exchange-pallette-yield-exchange-black, var(--yield-exchange-colors-darks, #252525)); text-align: center; font-family: Montserrat; font-size: 25px; font-style: normal; font-weight: 300; line-height: normal;">
                Use this unique one-time code:
            </p>
        </div>

        <div class="unique-key-container"
            style="width: 100%; background-color: #EFF2FE; display: table; margin: 0 auto; text-align: center;">
            <p class="unique-key p-0 m-0"
                style="color: #5063f4; font-family: Montserrat; font-size: 39px; font-style: normal; font-weight: 700; line-height: normal; display: inline-block;">
                {{ $pin }}
            </p>
        </div>
        <p
            style="color: #252525; text-align: center;font-family: Montserrat;font-size: 18px;font-style: normal; font-weight: 300;line-height: normal;">
            *This code is valid for 30 Minutes</p>


        <div style="width: 100%; display: table; margin: 0 auto;">
            <p class="discover-login"
                style="margin: 1rem; color: #252525; text-align: center; font-family: Montserrat; font-size: 16px; font-style: normal; font-weight: 300; line-height: normal;">
                Discover a world of exclusive investors waiting for you <a
                    href="{{ url('request-an-account')}}"><span
                        style="color: #5063F4; font-family: Montserrat; font-size: 16px; font-style: normal; font-weight: 300; line-height: normal; text-decoration-line: underline;">Sign
                        Up </span></a> Or
                <a href="{{ url('login')}}"> <span
                        style="color: #5063F4; font-family: Montserrat; font-size: 16px; font-style: normal; font-weight: 300; line-height: normal; text-decoration-line: underline;">
                        Log In</span></a>
            </p>
        </div>
    </div>
@endsection

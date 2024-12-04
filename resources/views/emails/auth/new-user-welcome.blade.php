@extends('emails.new-master')
@section('page-content')
    <div style="padding:0 5%; margin-right:auto;margin-left:auto" class="responsive">
        <div style="margin:0 auto; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center; margin-top:20px"
            align="center">
            Your account is under review
        </div>
        <div style="width: 100%; text-align: center; margin-top:20px;">
            <div
                style="display: inline-flex; align-items: center; background: #5063f4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/book-v2.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    User Onboard
                </span>
            </div>
        </div>

        <div style="width: 100%; text-align: center; margin-top:20px">
            <img src="{{ asset('assets/emails/accunder-review.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>
        <div style="width: 100%; display: table; margin: 0 auto;">
            <p class="discover-login"
                style="color: #252525; text-align: justify; font-family: Montserrat; font-size: 16px; font-style: normal; font-weight: 300; line-height: normal;">
                Thank you for your interest in Yield Exchange - Canada's premier platform for checking, comparing, and
                countering GIC rates. Your account has been successfully created. Our team is currently reviewing it to
                ensure the best experience for you.
                {{-- Here is your temporary password to log in --}}
            </p>
        </div>
        {{-- <div style="width: 100%; display: table; margin: 0 auto;">
            <p class="unique-key-text"
                style="color:#252525; text-align: justify; font-family: Montserrat; font-size: 15px; font-style: normal; font-weight: 700; line-height: normal;">
                In the meantime, here is your temporary password to log into our site:
            </p>
        </div> --}}

        {{-- <div class="unique-key-container"
            style="width: 100%; background-color: #EFF2FE; display: table; margin: 0 auto; text-align: center;">
            <p class="unique-key p-0 m-0"
                style="color: #5063f4; font-family: Montserrat; font-size: 39px; font-style: normal; font-weight: 700; line-height: normal; display: inline-block;margin:0px;padding:20px">
                {{ $password }}
            </p>
        </div> --}}


        <div style="width: 100%; display: table; margin: 0 auto;">
            <p class="discover-login"
                style="color: #252525; text-align: justify; font-family: Montserrat; font-size: 16px; font-style: normal; font-weight: 300; line-height: normal;">
                Should you have any questions or need assistance, please don't hesitate to reach out. We're here to help.

                {{-- You can use this password once your account is approved. Should you have any questions or need assistance, please don't hesitate to reach out. We're here to help. --}}
            </p>
        </div>
    </div>
@endsection

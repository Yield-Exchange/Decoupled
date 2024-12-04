@extends('home.master')
@section('page_title')
    Sign Up
@stop
@section('styles')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/onboarding.css?v=1.1') }}" rel="stylesheet" />
    <style>
     .header-bar-area{
        background: url('/assets/img/banner-bg.png') no-repeat;
        padding: 25px 0 10px;
         background-size: cover;
     }
     #banner,body{
         background: #FCFBFC!important;
     }
    </style>
@stop
@section('page_content')
    <div id="banner">
        <div class="container" id="VueApp">
            <div class="row" id="row">
                <sign-up
                    login-route="{{ is_admin_route($request) ? route('admin.login') : url('login') }}"
                    register-route="{{ url('sign-up') }}"
                    recaptcha-key="{{ config('app.RECAPTCHA_KEY') }}"
                    fis="{{ $fis }}"
                    provinces="{{ $provinces }}"
                    timezones="{{ $timezones }}"
                    naics-codes="{{ $naics_codes }}"
                    potential-deposits="{{ $potential_deposits }}"
                    deposit-portfolio="{{ $deposit_portfolio }}"
                    fi-types="{{ $fitypes }}"
                    terms-route="{{ url('sign-up') }}"
                    home-route="{{ url('/') }}"
                    skip_robot="{{ can_skip_robot_check_and_otp() ? 1 : 0 }}"
                    referral="{{ $referral }}"
                >
                </sign-up>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
    <script>

    </script>
@endsection

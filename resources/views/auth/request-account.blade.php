@extends('home.master')
@section('page_title')
    Request an Account
@stop
@section('styles')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/onboarding.css?v=1.1') }}" rel="stylesheet" />
    <style>
        .banner {
            background-color: white !important;
            padding-top: 70px !important;
        }

        #hs_show_banner_button {
            display: none !important;
        }
    </style>
@stop
@section('page_content')
    <div class="banner">
        <div class="" id="VueApp">
            <div class="">
                <request-account recaptcha-key="{{ config('app.RECAPTCHA_KEY') }}" is_conference="{{ false }}"
                    skip_robot="{{ can_skip_robot_check_and_otp() ? 1 : 0 }}"
                    verify-code-route="{{ request('code') ? route('depositor-sign-up-verification', request('code')) : null }}"
                    verify-code="{{ request('code') }}" register-route="{{ route('depositor-sign-up') }}"></request-account>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
    <script></script>
@endsection

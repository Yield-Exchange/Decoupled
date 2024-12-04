@extends('home.master')
@section('page_title')
    Depositor Sign Up
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
    <div id="banner" class="bg-white">
        <div class="container-fluid" id="VueApp">
            <div class="row" id="row">
                <depositor-sign-up
                    login-route="{{ is_admin_route(request()) ? route('admin.login') : url('login') }}"
                    register-route="{{ route('depositor-sign-up') }}"
                    recaptcha-key="{{ config('app.RECAPTCHA_KEY') }}"
                    skip_robot="{{ can_skip_robot_check_and_otp() ? 1 : 1 }}"
                    referral="{{ request('referral') ? request('referral') : null }}"
                >
                </depositor-sign-up>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
    <script>

    </script>
@endsection

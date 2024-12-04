@extends('home.master')
@section('page_title')
    Authentication
@stop
@section('styles')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/onboarding.css?v=1.1') }}" rel="stylesheet" />
    <style>
        .header-bar-area {
            background: url('/assets/img/banner-bg.png') no-repeat;
            /* padding: 25px 0 10px; */
            background-size: cover;
        }

        #banner,
        body {
            background: #FCFBFC !important;
        }
    </style>
@stop
@section('page_content')
    <!-- ====== Banner Part HTML Start ====== -->
    <div id="banner">
        <div class="" id="VueApp" style="margin-top: -70px">
            <div class="row">
                @if ($action == 'verifyOtp')
                    {{-- @include('auth.verify-otp') --}}
                    @include('auth.login', ['action' => 'verifyOtp'])
                @elseif($action == 'resetPassword')
                    {{-- <div class="alert"></div> --}}
                    @include('auth.login', ['action' => 'resetPassword'])
                    {{-- @include('auth.reset-password') --}}
                @elseif($action == 'changePassword')
                    {{-- <div class="alert"></div> --}}
                    @include('auth.login', ['action' => 'changePassword'])
                    {{-- @include('auth.change-password') --}}
                @else
                    {{-- <div class="alert"></div> --}}
                    @include('auth.login', ['action' => 'login'])
                    {{-- @include('auth.login') --}}
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endsection

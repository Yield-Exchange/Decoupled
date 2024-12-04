@extends('home.master')
@section('page_title')
    Authentication
@stop
@section('styles')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/onboarding.css?v=1.0') }}" rel="stylesheet" />
    <style>
        .header-bar-area{
            background: url('/assets/img/banner-bg.png') no-repeat;
            padding: 25px 0 10px;
            background-size: cover;
        }
    </style>
@stop
@section('page_content')
    <!-- ====== Banner Part HTML Start ====== -->
    <div id="banner">
        <div class="container" id="VueApp">
            <div id="row">
                <div class="row">
                    <div class="col-md-12 row">
                        <change-password
                                is-admin="{{is_admin_route($request)}}"
                                login-route="{{ is_admin_route($request) ? route('admin.login') : url('login') }}"
                                password-change-route="{{ is_admin_route($request) ? route('admin.reset-password-final-step') : url('reset-password-final-step') }}"
                                reset-code="{{ $code }}"
                                is-from-logged-in-user="{{ $isFromLoggedInUser  }}"
                        >
                        </change-password>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endsection
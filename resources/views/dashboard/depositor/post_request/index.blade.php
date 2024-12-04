@extends('dashboard.master')
@section('page_title')
    Post Request
@stop
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/datetimepicker/jquery.datetimepicker.css') }}" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tooltip-inner {
            background: transparent !important;
        }

        .tooltip-inner>img {
            width: 400px !important;
        }

        .tooltipimg {
            width: 150%;
        }

        .myinput {
            border-radius: 0;
        }

        label {
            color: grey;
        }

        #hover-content {
            display: none;
        }

        #parent:hover #hover-content {
            display: block;
        }

        .request_submit_btn_container {
            position: fixed;
            bottom: 15%;
            right: 2%;
            z-index: 9999;
            box-shadow: 3px 5px 8px 5px #ccc;
        }

        .toggle_advance_button_container {
            display: none;
        }

        .card-header:not([class*=bg-]):not([class*=alpha-]) {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .remove-product-btn {
            padding: 0;
            margin: 7px 0 0;
            color: red;
            cursor: pointer;
        }

        .remove-product-btn {
            display: none;
        }

        .request-summary-collapse {
            cursor: pointer;
        }

        .swal-modal .swal-text {
            text-align: left !important;
        }

        .swal-footer {
            text-align: center;
        }
    </style>
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <post-request-index deposit_insurances="{{ json_encode($deposit_insurances) }}"
            deposit_request="{{ json_encode($deposit_request) }}"
            organization="{{ json_encode($organization) }}"
            credit_rating_types="{{ json_encode($credit_rating_types) }}"
            formattedtimezone="{{ json_encode($formattedtimezone) }}" caninvitefis={{ json_encode($caninvitefis) }}
            products="{{ json_encode($products) }}"></post-request-index>

        {{-- <div class="col-xl-12">
            @include('dashboard.depositor.post_request.form')
            @include('dashboard.depositor.post_request.invite')
            @include('dashboard.depositor.post_request.confirm')
        </div> --}}
    </div>
@endsection
{{-- @include('dashboard.depositor.post_request.scripts') --}}

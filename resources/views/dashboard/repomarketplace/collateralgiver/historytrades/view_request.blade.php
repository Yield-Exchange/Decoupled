@extends('dashboard.master')
@section('page_title')
    Pending trades
@stop
@section('styles')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/datetimepicker/jquery.datetimepicker.css') }}" /> --}}
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: Montserrat !important;
        }
    </style>
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <repo-cg-history-trade-summary
            formattedtimezone="{{ json_encode($formattedtimezone) }}"></repo-cg-history-trade-summary>
    </div>
@endsection

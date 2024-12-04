@extends('dashboard.master')
@section('page_title')
    Repo Active Trades
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
        <repo-ct-active-trades formattedtimezone="{{ json_encode($formattedtimezone) }}"></repo-ct-active-trades>
    </div>
@endsection

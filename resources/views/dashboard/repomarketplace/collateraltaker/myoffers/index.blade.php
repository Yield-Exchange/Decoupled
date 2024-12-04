@extends('dashboard.master')
@section('page_title')
    Repo Pending Trades
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
        <repo-ct-my-offers formattedtimezone="{{ json_encode($formattedtimezone) }}" :user-logged-in='{{ json_encode($userLoggedIn) }}'></repo-ct-my-offers>
    </div>
@endsection

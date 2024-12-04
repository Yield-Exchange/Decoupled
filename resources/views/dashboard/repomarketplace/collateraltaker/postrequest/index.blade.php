@extends('dashboard.master')
@section('page_title')
    Repo Post Request
@stop
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/datetimepicker/jquery.datetimepicker.css') }}" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <repo-post-a-request :user-logged-in='{{ json_encode($userLoggedIn) }}'
            formattedtimezone="{{ json_encode($formattedtimezone) }}"></repo-post-a-request>
    </div>
@endsection

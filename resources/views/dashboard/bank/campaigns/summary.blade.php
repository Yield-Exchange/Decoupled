@extends('dashboard.master')
@section('page_title')
    Campaign Summary
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>
                <campaign-summary userloggedin="{{ $userLoggedIn }}" campaign="{{ $campaign }}" :formattedtimezone="{{json_encode($formattedtimezone)}}" />
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

@extends('dashboard.master')
@section('page_title')
    Place Offer
@stop
@section('page_content')

    <div class="row" id="VueApp">
        <div class="col-md-12">
            <view-trade-request-summary :user-logged-in='{{ json_encode($userLoggedIn) }}' prime_rate="{{ json_encode($prime_rate) }}"></view-trade-request-summary>
        </div>
    </div>

@endsection

@extends('dashboard.master')
@section('page_title')
    Place Offer
@stop
@section('page_content')

    <div class="row" id="VueApp">
        <div class="col-md-12">
            <new-request-summary deposit-request="{{ json_encode($deposit_request) }}" prime_rate="{{ $prime_rate }}"
                formattedtimezone="{{ json_encode($formattedtimezone) }}"
                offer="{{ json_encode($offer) }}"></new-request-summary>
        </div>
    </div>

@endsection

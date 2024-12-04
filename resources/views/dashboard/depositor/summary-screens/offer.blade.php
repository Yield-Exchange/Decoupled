@extends('dashboard.master')
@section('page_title')
    Request Summary
@stop
@section('styles')
    <style>
        p {
            color: grey;
            font-size: 13px;
        }
    </style>
@stop
@section('page_content')

    <div class="row">

        <div class="col-md-12" id="VueApp">
            <view-summary-for-history organization="{{ json_encode($offer->invited->organization) }}"
                offer="{{ json_encode($offer) }}" deposit-request="{{ json_encode($deposit_request) }}" />
        </div>

    </div>

    {{-- @include('dashboard.depositor.summary-screens.sections.offer_summary') --}}
    {{-- <div class='card p-3'>
        @include('dashboard.depositor.summary-screens.sections.request_summary_section')
    </div> --}}
    <div style="padding-bottom:30px;padding-top:25px;padding-left:10px;" class="row d-none">
        <div class="col-lg-2 col-md-2 col-sm-3">

            @if (request('fromPage'))
                <a href="{{ url(request('fromPage')) }}" class="btn btn-block custom-primary round"
                    style="border:1px solid gainsboro">Back</a>
            @endif

        </div>
        <div class="col-lg-2 col-md-2 col-sm-3">
            @php
                $counter_offer = App\Models\CounterOffer::where('offer_id', $offer->id)
                    ->orderBy('id', 'DESC')
                    ->get();
                $force_show = true;
                $user = auth()->user();
                $prime_rate = getSystemSettings('prime_rate')->value;
            @endphp
            @if ($user->userCan('depositor/review-offers/counter-offer'))
                {!! view(
                    'dashboard.depositor.counter-offer',
                    compact('deposit_request', 'offer', 'counter_offer', 'force_show', 'prime_rate'),
                )->render() !!}
            @endif
        </div>
        <div class="col-lg-2 col-md-2 col-sm-3"></div>
        <div class="col-lg-3 col-md-3 col-sm-3"></div>
    </div>

@endsection

@extends('dashboard.master')
@section('page_title')
    Offer Summary
@stop
@section('styles')
    <style>
        p {
            color: grey;
            /* font-size: 13px; */
        }

        .offerButtons {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .offerButtons>button {
            margin: 10px;
        }


        .timeline-steps {
            display: flex;
            justify-content: center;
            flex-wrap: wrap
        }

        .timeline-steps .timeline-step {
            align-items: center;
            display: flex;
            flex-direction: column;
            position: relative;
            margin: 1rem
        }

        .timeline-steps .timeline-step:not(:last-child):after {
            content: "";
            display: block;
            border-top: .15rem solid #3b82f6;
            width: 16rem;
            position: absolute;
            left: 8rem;
            top: .5rem
        }

        .timeline-steps .timeline-content {
            width: 15rem;
            text-align: center
        }

        .timeline-steps .timeline-content .inner-circle {
            border-radius: 1.5rem;
            height: 1rem;
            width: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #3b82f6;
        }

        .timeline-steps .timeline-content .inner-circle.active {
            background-color: #3b82f6;
        }

    </style>
@stop
@section('page_content')
@php
$counterOffer = $offer->counterOffers->where('status', '!=', 'COUNTERED')->first();
$offerBeforeCounter = $offer->offerBeforeCounter();
if ($counterOffer && in_array($counterOffer->status, ['CLOSED_ON_COUNTERED'])) {
    $offerBeforeCounter = $counterOffer;
}
if ($counterOffer && !in_array($counterOffer->status, ['PENDING', 'DECLINED'])) {
    $counterOffer = null;
}

@endphp
<div class="" id="VueApp">
    <offer-summary-card organization_data="{{$offer->invited->depositRequest->organization}}" offer="{{$offer}}" counterOffers="{{$counterOffer}}" from_page="{{$from_page}}" deposit_request="{{$deposit_request}}" ></offer-summary-card>

{{-- <div class="row">
    <div class="col-xl-12">
        <div class="card">

            <div class="row text-center justify-content-center mt-2 mb-2">
                <div class="col-xl-6 col-lg-8">
                    <h2 class="font-weight-bold">Offer In Progress</h2>
                </div>
            </div>
        
            <div class="row">
                <div class="col">
                    <div class="timeline-steps aos-init aos-animate mb-3" data-aos="fade-up">
                        <div class="timeline-step">
                            <div class="timeline-content">
                                <div class="inner-circle active"></div>
                                <p class="h6 text-dark mb-0 mb-lg-0">Offer Sent</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-content">
                                <div class="inner-circle {{ $offer->is_seen ? 'active' : '' }}"></div>
                                <p class="h6 text-dark mb-0 mb-lg-0"> Reviewing Offer</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-content">
                                <div class="inner-circle"></div>
                                <p class="h6 text-dark mb-0 mb-lg-0">Decision Made</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- /support tickets -->
    </div>
</div>
    @include('dashboard.bank.summary-screens.sections.offer_summary')
    <div class='card p-3'>
        @include('dashboard.bank.summary-screens.sections.request_summary')
    </div>
    <div style="padding-bottom:30px;padding-top:25px;padding-left:10px;" class="row">
        <div class="col-lg-3 col-md-3 col-sm-3">
            <a href="{{ url(Request::get('fromPage') ? Request::get('fromPage') : 'in-progress') }}"
                class="btn btn-lg custom-secondary round" style="border:1px solid gainsboro">Back</a>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-7">
            @if ($counterOffer && $counterOffer->status == 'PENDING')
                @php
                    $counter_offer_id = \App\CustomEncoder::urlValueEncrypt($counterOffer->id);
                    $counter_offer = $counterOffer;
                    $for_bank = 1;
                    $user = auth()->user();
                    $prime_rate = getSystemSettings('prime_rate')->value;
                @endphp
                @if ($user->userCan('bank/in-progress/counter-offer'))
                    <div class="offerButtons row">
                        <div style="margin-top: 5px">
                            {!! view(
                                'dashboard.depositor.counter-offer',
                                compact('deposit_request', 'offer', 'counter_offer', 'for_bank', 'prime_rate'),
                            )->render() !!}
                        </div>
                        <a class="btn btn-danger counter_offer_action_btn round"
                            data-action-text="This will not update the current offer." data-action="decline offer"
                            style="margin-left: 10px;margin-right: 15px;height: 35px;margin-top: 5px"
                            href="{{ url('counter-offer/' . $counter_offer_id . '/decline') }}">Decline Offer</a>
                        <a class="btn custom-primary round counter_offer_action_btn"
                            data-action-text="This will update the current offer." data-action="accept offer"
                            style="height: 35px;margin-top: 5px"
                            href="{{ url('counter-offer/' . $counter_offer_id . '/accept') }}">Accept Offer</a>
                    </div>
                @endif
            @endif
        </div>
    </div> --}}
</div>
@endsection
@section('scripts')
    {{-- @if ($counterOffer)
        <script>
            $(document).on('click', '.counter_offer_action_btn', function(e) {
                e.preventDefault();
                let action = $(this).data('action');
                let action_text = $(this).data('action-text');
                $this = $(this);
                swal({
                    title: "Are you sure to " + action + "?",
                    text: action_text,
                    buttons: ["No", "Yes"],
                }).then((response) => {
                    if (response) {
                        window.location.href = $this.attr('href');
                    }
                });
            });
        </script>
    @endif --}}
@endsection

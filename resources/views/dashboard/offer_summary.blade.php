<div class="col-md-7">
    <div class="card" style="height: 100%">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 row">
                    <div class="col-md-12">
                        <div style="display: inline-block;margin-right: 5%;vertical-align: top;">
                            <h5 style="color:#2CADF5;font-weight:bold;text-transform: uppercase">{{ $counterOffer ? 'Counter' : '' }} Offer
                                Summary</h5>
                        </div>
                        @if ($counterOffer && $counterOffer->status == 'PENDING' && $counterOffer->counter_offer_expiry)
                            <div style="display: inline-block">
                                <script type="text/template" id="countdown-timer-template">
                                    <div class="time <%= label %>">
                                        <span class="label"><%= label.length < 6 ? label : label.substr(0, 3)  %></span>
                                        <span class="count curr top"><%= curr %></span>
                                        <span class="count next top"><%= next %></span>
                                        <span class="count next bottom"><%= next %></span>
                                        <span class="count curr bottom"><%= curr %></span>
                                    </div>
                                </script>

                                <div>
                                    <div class="rate-held-container" data-toggle="tooltip" data-placement="top"
                                        title="Counter Expiry" style="height: 40px;margin-top: 10px;">
                                        {{--                                    <div class="col-sm-3"> --}}
                                        {{--                                        <p style="margin-top: 10px">Counter expiry:</p> --}}
                                        {{--                                    </div> --}}
                                        <div class="main-timer-container">
                                            <div class="countdown-container" id="counter-expiry"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row col-md-12">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6">
                                <p style="font-weight:bold;">Minimum amount</p>
                            </div>
                            <div class="col-md-6">
                                @if ($counterOffer)
                                    @if ($counterOffer->status == 'DECLINED')
                                        @if ($offer->minimum_amount != $counterOffer->minimum_amount)
                                            <span style="font-weight:bold" class="cancel-text">
                                                {{ $deposit_request->currency . ' ' . number_format($counterOffer->minimum_amount) }}
                                            </span>
                                        @endif
                                        <span style="font-weight:bold" class="pl-2">
                                            {{ $deposit_request->currency . ' ' . number_format($offer->minimum_amount) }}
                                        </span>
                                    @else
                                        @if ($offer->minimum_amount != $counterOffer->minimum_amount)
                                            <span style="font-weight:bold" class="cancel-text">
                                                {{ $deposit_request->currency . ' ' . number_format($offer->minimum_amount) }}
                                            </span>
                                        @endif
                                        <span style="font-weight:bold" class="pl-2">
                                            {{ $deposit_request->currency . ' ' . number_format($counterOffer->minimum_amount) }}
                                        </span>
                                    @endif
                                @elseif($offerBeforeCounter)
                                    @if ($offer->minimum_amount != $offerBeforeCounter->minimum_amount)
                                        <span style="font-weight:bold" class="cancel-text">
                                            {{ $deposit_request->currency . ' ' . number_format($offerBeforeCounter->minimum_amount) }}
                                        </span>
                                    @endif
                                    <span style="font-weight:bold" class="pl-2">
                                        {{ $deposit_request->currency . ' ' . number_format($offer->minimum_amount) }}
                                    </span>
                                @else
                                    <span style="font-weight:bold" class="pl-2">
                                        {{ $deposit_request->currency . ' ' . number_format($offer->minimum_amount) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p style="font-weight:bold;">Maximum amount</p>
                            </div>
                            <div class="col-md-6">
                                @if ($counterOffer)
                                    @if ($counterOffer->status == 'DECLINED')
                                        @if ($offer->maximum_amount != $counterOffer->maximum_amount)
                                            <span style="font-weight:bold" class="cancel-text">
                                                {{ $deposit_request->currency . ' ' . number_format($counterOffer->maximum_amount) }}
                                            </span>
                                        @endif
                                        <span style="font-weight:bold" class="pl-2">
                                            {{ $deposit_request->currency . ' ' . number_format($offer->maximum_amount) }}
                                        </span>
                                    @else
                                        @if ($offer->maximum_amount != $counterOffer->maximum_amount)
                                            <span style="font-weight:bold" class="cancel-text">
                                                {{ $deposit_request->currency . ' ' . number_format($offer->maximum_amount) }}
                                            </span>
                                        @endif
                                        <span style="font-weight:bold" class="pl-2">
                                            {{ $deposit_request->currency . ' ' . number_format($counterOffer->maximum_amount) }}
                                        </span>
                                    @endif
                                @elseif($offerBeforeCounter)
                                    @if ($offer->maximum_amount != $offerBeforeCounter->maximum_amount)
                                        <span style="font-weight:bold" class="cancel-text">
                                            {{ $deposit_request->currency . ' ' . number_format($offerBeforeCounter->maximum_amount) }}
                                        </span>
                                    @endif
                                    <span style="font-weight:bold" class="pl-2">
                                        {{ $deposit_request->currency . ' ' . number_format($offer->maximum_amount) }}
                                    </span>
                                @else
                                    <span style="font-weight:bold" class="pl-2">
                                        {{ $deposit_request->currency . ' ' . number_format($offer->maximum_amount) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if ($deposit_request->term_length_type == 'HISA')
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-weight:bold;">
                                        Interest Rate Type</p>
                                </div>
                                <div class="col-md-6">
                                    @if ($counterOffer)
                                        @if ($counterOffer->status == 'DECLINED')
                                            @if ($offer->rate_type != $counterOffer->rate_type)
                                                <span style="font-weight:bold" class="cancel-text">
                                                    {{ ucwords(strtolower($counterOffer->rate_type)) }}
                                                </span>
                                            @endif
                                            <span style="font-weight:bold" class="pl-2">
                                                {{ ucwords(strtolower($offer->rate_type)) }}
                                            </span>
                                        @else
                                            @if ($offer->rate_type != $counterOffer->rate_type)
                                                <span style="font-weight:bold" class="cancel-text">
                                                    {{ ucwords(strtolower($offer->rate_type)) }}
                                                </span>
                                            @endif
                                            <span style="font-weight:bold" class="pl-2">
                                                {{ ucwords(strtolower($counterOffer->rate_type)) }}
                                            </span>
                                        @endif
                                    @elseif($offerBeforeCounter)
                                        @if ($offer->rate_type != $offerBeforeCounter->rate_type)
                                            <span style="font-weight:bold" class="cancel-text">
                                                {{ ucwords(strtolower($offerBeforeCounter->rate_type)) }}
                                            </span>
                                        @endif
                                        <span style="font-weight:bold" class="pl-2">
                                            {{ ucwords(strtolower($offer->rate_type)) }}
                                        </span>
                                    @else
                                        <span style="font-weight:bold" class="pl-2">
                                            {{ ucwords(strtolower($offer->rate_type)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <p style="font-weight:bold;">Rate Held Until :</p>
                            </div>
                            <div class="col-md-6">
                                @if ($counterOffer)
                                    @if ($counterOffer->status == 'DECLINED')
                                        @if ($offer->rate_held_until != $counterOffer->offer_expiry)
                                            <span style="font-weight:bold" class="cancel-text">
                                                {{ timeIn_12_24_format($counterOffer->rate_held_until) }}
                                            </span>
                                        @endif
                                        <span style="font-weight:bold" class="pl-2">
                                            {{ timeIn_12_24_format($offer->offer_expiry) }}
                                        </span>
                                    @else
                                        @if ($offer->rate_held_until != $counterOffer->offer_expiry)
                                            <span style="font-weight:bold" class="cancel-text">
                                                {{ timeIn_12_24_format($offer->rate_held_until) }}
                                            </span>
                                        @endif
                                        <span style="font-weight:bold" class="pl-2">
                                            {{ timeIn_12_24_format($counterOffer->offer_expiry) }}
                                        </span>
                                    @endif
                                @elseif($offerBeforeCounter)
                                    @php
                                        $offerBeforeCounter_rate_held_until = $offerBeforeCounter->rate_held_until ? $offerBeforeCounter->rate_held_until : $offerBeforeCounter->offer_expiry;
                                    @endphp
                                    @if ($offer->rate_held_until != $offerBeforeCounter_rate_held_until)
                                        <span style="font-weight:bold" class="cancel-text">
                                            {{ timeIn_12_24_format($offerBeforeCounter_rate_held_until) }}
                                        </span>
                                    @endif
                                    <span style="font-weight:bold" class="pl-2">
                                        {{ timeIn_12_24_format($offer->rate_held_until) }}
                                    </span>
                                @else
                                    <span style="font-weight:bold" class="pl-2">
                                        {{ timeIn_12_24_format($offer->rate_held_until) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if (isset($contract->offered_amount))
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-weight:bold;">Awarded Amount</p>
                                </div>
                                <div class="col-md-6">
                                    <span style="font-weight:bold">
                                        {{ !in_array($contract->status, ['WITHDRAWN']) ? $deposit_request->currency . ' ' . number_format($deposit_request->offered_amount) : 0 }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <p style="font-weight:bold;">Product disclosure statement</p>
                            </div>
                            <div class="col-md-6">
                                <span style="font-weight:bold">
                                    {!! filter_var($offer->product_disclosure_url, FILTER_VALIDATE_URL)
                                        ? "<a target='_blank' href='" .
                                            $offer->product_disclosure_url .
                                            "' data-toggle='tooltip' title='" .
                                            $offer->product_disclosure_url .
                                            "'><i class='fa fa-eye'></i> Visit the link</a>"
                                        : $offer->product_disclosure_url !!}
                                    @if (!empty($offer->product_disclosure_statement))
                                        <a href="{{ url('uploads/' . str_replace('uploads/', '', $offer->product_disclosure_statement)) }}"
                                            target="_blank" class="btn btn-primary btn-sm" download><i
                                                class="fa fa-download"></i> Download</a>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        @php
                            $organization = auth()->user()->organization;
                            $_counterOffers = $offer->counterOffers->first();
                            
                            $status = null;
                            if ($_counterOffers) {
                                if ($_counterOffers->status == 'EDITED') {
                                    $status = 'Offer Edited';
                                } else {
                                    $status = 'Counter ' . counter_offer_status_formatter($_counterOffers->status, $organization);
                                }
                            }
                        @endphp
                        @if ($status)
                            <label style="margin-top: -3px;margin-bottom: 10px;margin-left: 10%"
                                class="badge badge-success" disabled>{{ $status }}</label>
                        @endif
                        <div class="offer-interest-rate-rounded-container">
                            @php
                                if ($offer->rate_type != 'VARIABLE'):
                                    $offer_rate = formatInterest($offer->interest_rate_offer);
                                else:
                                    $offer_rate = formatInterest($offer->fixed_rate, true, $offer->rate_operator, true);
                                endif;
                            @endphp
                            @if ($counterOffer)
                                @php
                                    if ($counterOffer->rate_type != 'VARIABLE'):
                                        $counter_offer_rate = formatInterest($counterOffer->offered_interest_rate);
                                    else:
                                        $counter_offer_rate = formatInterest($counterOffer->fixed_rate, true, $counterOffer->rate_operator, true);
                                    endif;
                                @endphp
                                @if ($counterOffer->status == 'DECLINED')
                                    @if ($counter_offer_rate != $offer_rate)
                                        <h2 style="font-weight: 300; font-size: 12px;" class="cancel-text">
                                            {!! $counter_offer_rate !!}
                                        </h2>
                                    @endif
                                    <h2 style="font-weight: bold; font-size: 20px; margin-top:-10px;">
                                        {!! $offer_rate !!}
                                    </h2>
                                @else
                                    @if ($counter_offer_rate != $offer_rate)
                                        <h2 style="font-weight: 300; font-size: 12px;" class="cancel-text">
                                            {!! $offer_rate !!}
                                        </h2>
                                    @endif
                                    <h2 style="font-weight: bold; font-size: 20px; margin-top:-10px;">
                                        {!! $counter_offer_rate !!}
                                    </h2>
                                @endif
                            @elseif($offerBeforeCounter)
                                @php
                                    if ($offerBeforeCounter->rate_type != 'VARIABLE'):
                                        $offer_before_counter_rate = formatInterest($offerBeforeCounter->interest_rate_offer ? $offerBeforeCounter->interest_rate_offer : $offerBeforeCounter->offered_interest_rate);
                                    else:
                                        $offer_before_counter_rate = formatInterest($offerBeforeCounter->fixed_rate, true, $offerBeforeCounter->rate_operator, true);
                                    endif;
                                @endphp
                                @if ($offer_before_counter_rate != $offer_rate)
                                    <h2 style="font-weight: 300; font-size: 12px;" class="cancel-text">
                                        {!! $offer_before_counter_rate !!}
                                    </h2>
                                @endif
                                <h2 style="font-weight: bold; font-size: 20px; margin-top:-10px;">
                                    {!! $offer_rate !!}
                                </h2>
                            @else
                                <h2 style="font-weight: bold">
                                    {!! $offer_rate !!}
                                </h2>
                            @endif
                            <p>Interest Rate</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row col-md-12">
                <div class="col-md-3" style="padding: 0;">
                    <p style="font-weight:bold;">Special Instructions</p>
                </div>
                <div class="col-md-6">
                    @if ($counterOffer)
                        <span style="font-weight:bold">
                            {{ $counterOffer->special_instructions }}
                        </span>
                    @elseif($offerBeforeCounter)
                        <span style="font-weight:bold">
                            {{ $offer->special_instructions }}
                        </span>
                    @else
                        <span style="font-weight:bold">
                            {{ $offer->special_instructions }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cancel-text {
        color: black;
        background-color: transparent;
        /*background-image: repeating-linear-gradient(166deg, transparent 0%, transparent 48%, black 50%, transparent 52%, transparent 100%);*/
        text-decoration: line-through;
        text-decoration-color: red;
        text-decoration-thickness: 1px;
    }
</style>
@if ($counterOffer && $counterOffer->counter_offer_expiry)
    <link rel="stylesheet" href="{{ asset('assets/css/jQuery.countdown.css') }}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oswald">
    <style>
        .main-timer-container .time {
            height: 30px !important;
            width: 30px !important;
            font-size: 10.4px !important;
        }

        .main-timer-container .label {
            top: -22px !important;
        }

        .main-timer-container .count.top {
            border-bottom: none !important;
        }

        .main-timer-container .count.bottom {
            border-top: none !important;
        }
    </style>
    <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.min.js"></script>
    <script src="{{ asset('assets/js/jquery.countdown.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous"></script>
    <script type="text/javascript">
        let counterExpiry = moment.utc("{{ $counterOffer->counter_offer_expiry }}").local().format("YYYY-MM-DD HH:mm:ss");
        $(window).on('load', function() {
            let labels = ['days', 'hours', 'minutes', 'seconds'],
                template = _.template($('#countdown-timer-template').html()),
                currDate = '00:00:00:00',
                nextDate = '00:00:00:00',
                parser = /([0-9]{2})/gi,
                $rateHeldTill = $('#counter-expiry');

            // Parse countdown string to an object
            function strfobj(str) {
                let parsed = str.match(parser),
                    obj = {};
                labels.forEach(function(label, i) {
                    obj[label] = parsed[i]
                });
                return obj;
            }
            // Return the time components that diffs
            function diff(obj1, obj2) {
                let diff = [];
                labels.forEach(function(key) {
                    if (obj1[key] !== obj2[key]) {
                        diff.push(key);
                    }
                });
                return diff;
            }
            // Build the layout
            let initData = strfobj(currDate);
            labels.forEach(function(label, i) {
                $rateHeldTill.append(template({
                    curr: initData[label],
                    next: initData[label],
                    label: label
                }));
            });

            // Starts the countdown for offer expiry date & time
            $rateHeldTill.countdown(counterExpiry, {
                    defer: false
                })
                .on('finish.countdown', function(event) {
                    $(".rate-held-container").find(".main-timer-container").find(".count").css('background',
                        '#ccc');
                }).on('update.countdown', function(event) {

                    if (parseInt(event.strftime("%D")) < 1 && parseInt(event.strftime("%H")) < 1) {
                        $(".rate-held-container").find(".main-timer-container").find(".count").css('background',
                            '#29AB87');
                    }

                    let newDate = event.strftime('%D:%H:%M:%S'),
                        data;
                    if (newDate !== nextDate) {
                        currDate = nextDate;
                        nextDate = newDate;
                        // Setup the data
                        data = {
                            'curr': strfobj(currDate),
                            'next': strfobj(nextDate)
                        };
                        // Apply the new values to each node that changed
                        diff(data.curr, data.next).forEach(function(label) {
                            let selector = '.%s'.replace(/%s/, label),
                                $node = $rateHeldTill.find(selector);
                            // Update the node
                            $node.removeClass('flip');
                            $node.find('.curr').text(data.curr[label]);
                            $node.find('.next').text(data.next[label]);
                            // Wait for a repaint to then flip
                            _.delay(function($node) {
                                $node.addClass('flip');
                            }, 50, $node);
                        });
                    }
                }).countdown('start');
        });
    </script>
@endif

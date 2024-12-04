@extends('dashboard.master')
@section('page_title')
    Pick offers
@stop
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/jQuery.countdown.css') }}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oswald">
    <link href="{{ asset('/assets/dashboard/datetimepicker/build/jquery.datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css" />
    <style>
        .debutton > button{
            padding: 0;
        }
        .debutton > button > i{
            font-size: 25px;
        }
        .btn-outline-secondary-custom {
            background: #80EC67ED !important;
        }

        .btn-no-action-custom {
            background: #ccc;
        }

        .custom-data-tables>tbody>tr>td {
            padding: 5px !important;
        }

        .custom-data-tables>tbody>tr>td:last-child {
            padding-top: 15px !important;
        }

        .custom-data-tables>tbody>tr>td:last-child>._error {
            width: 100% !important;
            text-align: center !important;
            padding-top: 5px !important;
            /*color: red !important;*/
            font-size: 13px !important;
        }

        #offer-table>thead>tr {
            height: 0 !important;
        }

        .cancel-text {
            display: block;
            color: black;
            background-color: transparent;
            /*background-image: repeating-linear-gradient(166deg, transparent 0%, transparent 48%, black 50%, transparent 52%, transparent 100%);*/
            text-decoration: line-through;
            text-decoration-color: red;
            text-decoration-thickness: 1px;
        }

        .xdsoft_datetimepicker.xdsoft_noselect.xdsoft_ {
            z-index: 100050 !important;
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

        /* .product-table  tbody tr  {
            margin: 20px !important;
        } */
        .product-table tbody tr td {
            font-weight: 900 !important;
            color: #000;
            padding: 10px 0px;
            border: none;
        }
        
    </style>
@stop
@section('page_content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card " >

                <div class="row text-center justify-content-center mt-2 mb-2">
                    <div class="col-xl-6 col-lg-8">
                        <h2 class="font-weight-bold">Depositor Request Progress</h2>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col">
                        <div class="timeline-steps aos-init aos-animate mb-3" data-aos="fade-up">
                            <div class="timeline-step">
                                <div class="timeline-content">
                                    <div class="inner-circle active"></div>
                                    <p class="h6 text-dark mb-0 mb-lg-0">Request Posted</p>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-content">
                                    <div class="inner-circle {{ ($deposit_request->count_viewed_invited() > 0) ? 'active' : '' }}"></div>
                                    <p class="h6 text-dark mb-0 mb-lg-0">{{$deposit_request->count_viewed_invited()}} Of {{ count($deposit_request->invited) }} FIs Reviewing</p>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-content">
                                    <div class="inner-circle"></div>
                                    <p class="h6 text-dark mb-0 mb-lg-0">{{ count($deposit_request->offers) }} Offers Reviewed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /support tickets -->
        </div>
    </div>
    @php
        $req_id = App\CustomEncoder::urlValueEncrypt($deposit_request->id);
        $user = auth()->user();
    @endphp
    <script type="text/template" id="countdown-timer-template">
        <div class="time <%= label %>">
            <span class="count curr top"><%= curr %></span>
            <span class="count next top"><%= next %></span>
            <span class="count next bottom"><%= next %></span>
            <span class="count curr bottom"><%= curr %></span>
            <span class="label"><%= label.length < 6 ? label : label.substr(0, 3)  %></span>
        </div>
    </script>


    <div class="row">
        <div class="col-xl-12">
            <div class="card" style="padding: 2%">
                <div class="row" style="padding: 1%">
                    <div class="col-sm-7">
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="b_b">ALL</span> OFFERS
                                <a href="{{ route('pick.offers.export', \App\CustomEncoder::urlValueEncrypt($deposit_request->id)) }}"
                                    class="btn custom-primary round">Print</a>
                                <br />
                            </div>
                            <div class="col-sm-6">
                                <table class="product-table table table-sm">
                                    <tbody>
                                        <tr>
                                            <td>Product</td>
                                            <td>:</td>
                                            <td class="pull-right">{{ $deposit_request->product_name }}</td>
                                        </tr>
                                        @if ($deposit_request->term_length_type != 'HISA')
                                            <tr>
                                                <td>Term</td>
                                                <td>:</td>
                                                <td class="pull-right">{{ $deposit_request->term_length . ' ' . ucfirst(strtolower($deposit_request->term_length_type)) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>Requested Amount</td>
                                            <td>:</td>
                                            <td class="pull-right">{{ $deposit_request->currency . ' ' . number_format($deposit_request->amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Awarded Amount</td>
                                            <td>:</td>
                                            <td><span id="total_offered" class="pull-right"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Wtd. Avg. Interest</td>
                                            <td>:</td>
                                            <td><span id="interest_rate" class="pull-right"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Interest</td>
                                            <td>:</td>
                                            <td><span id="total_interest" class="pull-right"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row select-offer-container" data-toggle="tooltip" data-placement="top"
                            title="Please wait until all parties have had a chance to submit their offer">
                            <div class="col-sm-4">
                                <p>Select Offers In:</p>
                            </div>
                            <div class="col-sm-8 main-timer-container">
                                <div class="countdown-container" id="select-offers-in"></div>
                            </div>
                        </div>
                        <div class="row request-expiry-container" style="margin-top:20px;" data-toggle="tooltip"
                            data-placement="top" title="If no offers are selected your request will expire">
                            <div class="col-sm-4">
                                <p>Request Expires In:</p>
                            </div>
                            <div class="col-sm-8 main-timer-container">
                                <div class="countdown-container" id="request-expires-in"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table custom-data-tables table-condensed" id="offer-table">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Institution</th>
{{--                                        <th style="width: 10%">Digital Onboarding</th>--}}
                                        <th style="width: 10%">Interest Rate %</th>
                                        <th style="width: 10%">Min Amount</th>
                                        <th style="width: 10%">Max Amount</th>
                                        <th style="width: 15%">Action</th>
                                        <th style="width: 20%">Awarded Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-right mr-5">
            <a href="{{ route('review-offers') }}" class="btn btn-lg custom-secondary round">Cancel</a>
            &nbsp;&nbsp;&nbsp;
            @if ($user->userCan('depositor/review-offers/confirm-button'))
                <button class="btn btn-lg custom-primary round" id="confirmButton" disabled
                    onclick="selectButton();">Confirm</button>
                <form id="form_offers" action="{{ route('submit.selected.offers') }}" method="post">
                    <input type="hidden" name="offers" />
                    <input type="hidden" name="req_id" value="{{ $req_id }}" />
                    @csrf
                </form>
            @endif
        </div>
    </div>

@endsection
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.min.js"></script>
    <script src="{{ asset('assets/js/jquery.countdown.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("body").tooltip({
                selector: '[data-toggle=tooltip]'
            });
        });

        $(document).ready(function() {
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous"></script>
    <script type="text/javascript">
        let is_already_open =
            "{{ \Carbon\Carbon::parse($deposit_request->closing_date_time)->lessThan(getUTCDateNow()) ? true : false }}";
        let is_already_expired =
            "{{ \Carbon\Carbon::parse($deposit_request->date_of_deposit)->lessThan(getUTCDateNow()) ? true : false }}";
        let req_id = "{{ $req_id }}";
        let closingDate = moment.utc("{{ $deposit_request->closing_date_time }}").local().format("YYYY/MM/DD HH:mm:ss");
        let requestExpiry = moment.utc("{{ $deposit_request->date_of_deposit }}").local().format("YYYY/MM/DD HH:mm:ss");
        let currency = "{{ $deposit_request->currency }}";
        let deposit_amount = "{{ $deposit_request->amount }}";
        let token = "{{ csrf_token() }}";
        let pick_offers_data_api_url = "{{ route('pick.offers-data') }}";
        let has_pending_deposit_permissions = "{{ $user->userCan('depositor/pending-deposits/page-access') }}";
    </script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/bid.js?v=1.1') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/moment-timezone.js') }}"></script>
    <script type="text/javascript">
        let format = 'YYYY/MM/DD HH:mm:ss ZZ';
        let timeZone = "{{ formattedTimezone(auth()->user()->timezone) }}";
        let amount = @php echo $deposit_request->amount; @endphp;
        let dateOfDepositWithUserTimezone = moment.utc("{{ $deposit_request->date_of_deposit }}").tz(timeZone);
        let minimum_counter_offer_expiry = moment.utc("{{ getUTCTimeNow()->addMinutes(30)->format('Y-m-d H:i:s') }}").tz(
            timeZone);
    </script>
    <script src="{{ asset('assets/dashboard/js/review_offers.js?v=1.1.7') }}"></script>
    <script>
        $(document).on('click', 'a', function(e) {
            if (!$(this).hasClass("paginate_button") && !$(this).hasClass("no-page-exit-alert")) {
                e.preventDefault();
                let $this = $(this);
                let offered_amounts = $('.offered_amount').map(function() {
                    return this.value; // $(this).val()
                }).get();


                let has_data = false;
                offered_amounts.forEach(function(item, index) {
                    if (item) {
                        has_data = true;
                        return true;
                    }
                });

                if (!has_data) {
                    window.onbeforeunload = null;
                    window.location.href = $this.attr('href');
                    return false;
                }

                swal({
                    title: "Do you want to leave this page?",
                    text: "Changes you made will not be saved.",
                    // icon: "warning",
                    buttons: ["No", "Yes"],
                }).then((response) => {
                    if (response) {
                        window.onbeforeunload = null;
                        window.location.href = $this.attr('href');
                    }
                });
            }
        });

        function openCounterOffer(counterId, offer, is_bank) {
            if (offer && offer.status == "PENDING" && !is_bank) {
                swal({
                    title: "A Counter Offer in progress",
                    text: "Do you wish to continue?",
                    buttons: ["No", "Yes"],
                }).then((response) => {
                    if (response) {
                        $('#' + counterId).modal('show');
                    } else {
                        return;
                    }
                });
            } else {
                $('#' + counterId).modal('show');
            }
        }
    </script>
@endsection

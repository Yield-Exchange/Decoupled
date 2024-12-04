@extends('dashboard.master')
@section('page_title')
    Deposit Summary
@stop
@section('styles')
    <style>
        p {
            color: grey;
            font-size: 13px;
        }
    </style>
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
@stop
@section('page_content')

    <div class="row">
        @php
            $bank = $offer->invited->bank;
            $organization = $bank;
            $auth_user = auth()->user();
        @endphp
        <div class="col-md-12" id="VueApp">
            <pr-deposit-summary organization="{{ json_encode($organization) }}"
              deposit-request="{{json_encode($deposit_request)}}"  offer="{{ json_encode($offer) }}" contract="{{ $contract}}"></pr-deposit-summary>
        </div>
        <div class="col-md-7 d-none">
            <div class="card" style="height: 100%">
                <div class="card-body">
                    @include('dashboard.depositor.summary-screens.sections.deposit_summary')
                </div>
            </div>
        </div>
    </div>

    <br /><br />
    <div class="col-12 d-none">
        <div class="row">
            <div class="col-12">
                <a href="{{ url(Request::get('fromPage') ? Request::get('fromPage') : 'pending-deposits') }}"
                    class="btn btn-lg custom-secondary round" style="border:1px solid gainsboro;margin-right: 3%">Back</a>

                @if ($contract->status == 'PENDING_DEPOSIT')
                    @php
                        $unread_messages = App\Models\Chat::where('sent_to', auth()->id())
                            ->where('deposit_id', $contract->id)
                            ->where('status', 'NEW')
                            ->count();
                        $badge_notify_chat1 = '';
                        if ($unread_messages > 0) {
                            $badge_notify_chat1 =
                                '<span class="badge badge-danger badge-notify-chat-1">' . $unread_messages . '</span>';
                        }
                        $deposit_id_encoded = App\CustomEncoder::urlValueEncrypt($contract->id);
                    @endphp
                    @if ($auth_user->userCan('depositor/pending-deposits/withdraw'))
                        <a href="#" onclick="return reject();"
                            class="btn custom-primary round mmy_btn btn-lg pull-right">Withdraw Award</a>
                    @endif
                    @if ($auth_user->userCan('universal/chats/page-access'))
                        <a href="{{ route('deposit.chat.room', $deposit_id_encoded) }}?fromPage=pending-deposits"
                            class="btn custom-secondary round mmy_btn btn-lg pull-right" style="margin-right: 3%">Chat
                            {!! $badge_notify_chat1 !!}</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.min.js"></script>
    <script src="{{ asset('assets/js/jquery.countdown.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous"></script>
    <script type="text/javascript">
        let offerExpiry = moment.utc("{{ $offer->rate_held_until }}").local().format("YYYY-MM-DD HH:mm:ss");
    </script>
    <script>
        function reject() {

            swal({
                title: "",
                text: "Do you want to retract the awarded deposit?",
                // icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {

                    let $loader = $("#cover-spin");
                    let dep_id = "{{ App\CustomEncoder::urlValueEncrypt($contract->id) }}";

                    makeApiCall("{{ url('withdraw-deposit') }}", {
                        "deposit_id": dep_id,
                        "_token": "{{ csrf_token() }}"
                    }, function(response) {
                        if (response.success) {
                            swal("", response.message, "success").then(function() {
                                $loader.show();
                                window.location.href = "/pending-deposits";
                            });
                        } else {
                            swal("", response.message, "info");
                        }
                    }, $loader, "POST", function(xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)) {
                            swal("An error occurred, the page will refresh.").then(() => {
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                            return;
                        }

                        swal("", apiCallServerErrorMessage(xhr,
                            "Unable to withdraw the deposit, try again later", "error"));
                    });

                }

            });
        }

        $(window).on('load', function() {
            let labels = ['days', 'hours', 'minutes', 'seconds'],
                template = _.template($('#countdown-timer-template').html()),
                currDate = '00:00:00:00',
                nextDate = '00:00:00:00',
                parser = /([0-9]{2})/gi,
                $rateHeldTill = $('#rate-held-till');

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
            $rateHeldTill.countdown(offerExpiry, {
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
@endsection

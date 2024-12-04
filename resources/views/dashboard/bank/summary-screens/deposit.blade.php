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
    @if ($contract->status == 'PENDING_DEPOSIT')
        <link rel="stylesheet" href="{{ asset('assets/css/jQuery.countdown.css') }}" />
        <link rel="stylesheet" type="text/css"
            href="{{ asset('assets/dashboard/datetimepicker/jquery.datetimepicker.css') }}" />
        <style>
            .xdsoft_datetimepicker {
                z-index: 9999999 !important;
            }
        </style>
    @endif
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

        .swal-modal .swal-text {
            text-align: center;
        }

        .swal-footer {
            text-align: center;
        }
    </style>
@stop
@section('page_content')


    <div class=" row " id="VueApp">

        <div class="col-md-12">
            <fi-deposit-summary organization="{{ json_encode($organization) }}" depositor="{{ json_encode($depositor) }}"
                offer="{{ json_encode($offer) }}" deposit-request="{{ json_encode($deposit_request) }}" />
        </div>
    </div>
@endsection
{{-- @section('scripts')
    @if ($contract->status == 'PENDING_DEPOSIT')
        <script src="{{ asset('assets/dashboard/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.min.js"></script>
        <script src="{{ asset('assets/js/jquery.countdown.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
            integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
            crossorigin="anonymous"></script>

        <script src="{{ asset('assets/js/moment-timezone.js') }}"></script>
        <script type="text/javascript">
            let format = 'YYYY/MM/DD HH:mm:ss ZZ';
            let timeZone = "{{ formattedTimezone($user->timezone) }}";
            let todayDateWithUserTimezone = moment(moment().toISOString(), format).tz(timeZone);
        </script>
        <script>
            const dateToday = new Date();
            $(document).ready(function() {
                let minDate = moment.utc("{{ $deposit_request->closing_date_time }}").local();
                let maxDate = todayDateWithUserTimezone;
                if (minDate > maxDate) {
                    maxDate = minDate;
                }
                $('.date_picker').datetimepicker({
                    minDate: minDate.format("YYYY/MM/DD"),
                    maxDate: maxDate.format("YYYY/MM/DD"),
                    timepicker: false,
                    format: 'Y-m-d',
                    validateOnBlur: true,
                    onChangeDateTime: function(dp, $input) {
                        var date = moment($input.val());
                        var date_;
                        var _date;

                        var termlength = "{{ $deposit_request->term_length }}";
                        var termlengthtype = "{{ $deposit_request->term_length_type }}";

                        if (termlengthtype === "MONTHS") {
                            date.add(parseInt(termlength), "months");
                            date_ = date.clone().subtract(7, "days");
                        } else if (termlengthtype === "HISA") {
                            $('.maturitydate1').hide();
                            return;
                        } else {
                            date.add(parseInt(termlength), "days");
                            if (termlength > 7) {
                                date_ = date.clone().subtract(7, "days");
                            } else {
                                date_ = moment($input.val());
                            }
                        }
                        $('.maturitydate1').show();
                        _date = date.clone().add(7, "days");

                        $('.date_picker2').val(date.format("YYYY-MM-DD"));
                        $('.date_picker2').datetimepicker({
                            maxDate: _date.format("YYYY/MM/DD"),
                            minDate: date_.format("YYYY/MM/DD"),
                            timepicker: false,
                            format: 'Y-m-d',
                            validateOnBlur: true,
                        });
                    }
                });
                $('.maturitydate1').hide();
            });
        </script>
        <script type="text/javascript">
            $(document).on("submit", "#create_gic_form", function() {
                $("#confirmButton").attr('disabled', true);
                let $this = $("#create_gic_form");
                let $loader = $("#cover-spin");

                swal({
                    title: "",
                    text: "Are you sure to proceed?",
                    // icon: "warning",
                    buttons: ["No", "Yes"],
                    className: "mod-width",
                }).then((response) => {
                    if (response) {
                        makeApiCall($this.attr('action'), $this.serialize(), function(response) {
                            if (response.success) {
                                $('#update').modal('hide');
                                swal(response.message_title, response.message).then(function() {
                                    $loader.show();
                                    window.location.href = "/bank-active-deposits";
                                });
                            } else {
                                swal("", response.message);
                            }
                            $("#confirmButton").attr('disabled', false).html('Please wait..');
                        }, $loader, "POST", function(xhr, textStatus, errorThrown) {
                            if ([419].includes(xhr.status)) {
                                swal("An error occurred, the page will refresh.").then(() => {
                                    window.onbeforeunload = null;
                                    window.location.reload();
                                });
                            }
                            return;

                            swal("", apiCallServerErrorMessage(xhr,
                                "Unable to submit, try again later"), "error");
                            $("#confirmButton").attr('disabled', false).html('Confirm');
                        });
                    }
                });

                return false;
            });
            let offerExpiry = moment.utc("{{ $offer->rate_held_until }}").local().format("YYYY-MM-DD HH:mm:ss");
        </script>
        <script>
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
    @endif
@endsection --}}

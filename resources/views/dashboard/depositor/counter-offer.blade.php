@php
    $current_counter_offer = $counter_offer = $counter_offer instanceof \Illuminate\Database\Eloquent\Collection ? with(clone $counter_offer)->first() : $counter_offer;
    if ($current_counter_offer && !in_array($current_counter_offer->status, ['PENDING', 'CLOSED_ON_COUNTERED'])) {
        $current_counter_offer = null;
    }
    $depositRequest = $offer->invited->depositRequest;
    $organization = $depositRequest->organization;
    $force_show = true;
    $bank = $offer->invited->bank;
    
    $current_offer = $current_counter_offer ? $current_counter_offer : $offer;
@endphp
<div style="display: inline-block; text-align: center;vertical-align: top">
    <button type="button" class="btn custom-secondary round ml-1 mr-1"
        onclick="openCounterOffer('counterOffer{{ $offer->id }}','{{ $current_counter_offer }}', '{{ isset($for_bank) }}')">Counter
        Offer</button>
    <div id="counterOffer{{ $offer->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered fix-width">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="#" id="bid_offer_form" method="post" autocomplete="off" enctype="multipart/form-data"
                    class="bid_offer form-horizontal">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h5 class="modal-title my_h"><span class="b_b">COUN</span>TER OFFER</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="request_id"
                            value="{{ \App\CustomEncoder::urlValueEncrypt($deposit_request->id) }}" />
                        <input type="hidden" name="offer_id"
                            value="{{ \App\CustomEncoder::urlValueEncrypt($offer->id) }}" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="box p-4 offerOne">
                                            <h3 class="text-uppercase">Current Offer</h3>
                                            <div>
                                                <h4>Institute Name</h4>
                                                <h6>{{ (auth()->user()->organization->is_depositor) ? $bank->name : $deposit_request->organization->name }}</h6>
                                            </div>
                                            <div>
                                                <h4>Minimum Amount</h4>
                                                <h6>{{ $deposit_request->currency . ' ' . number_format($current_offer->minimum_amount) }}
                                                </h6>
                                            </div>
                                            <div>
                                                <h4>Maximum Amount</h4>
                                                <h6>{{ $deposit_request->currency . ' ' . number_format($current_offer->maximum_amount) }}
                                                </h6>
                                            </div>
                                            <div>
                                                <h4>Rate</h4>
                                                <h6>{{ $current_offer->interest_rate_offer ? $current_offer->interest_rate_offer : $current_offer->offered_interest_rate }}%
                                                </h6>
                                            </div>
                                            <div>
                                                <h4>Rate Held Until</h4>
                                                <h6>{{ timeIn_12_24_format($current_offer->offer_expiry ? $current_offer->offer_expiry : $current_offer->rate_held_until) }}
                                                </h6>
                                            </div>
                                            <div>
                                                <h4>Special Instructions</h4>
                                                <h6>{{ $current_offer->special_instructions }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="box  p-4 offerTwo">
                                            <h3 class="text-uppercase">Counter Offer</h3>
                                            <div class="row">
                                                <div class="row">
                                                    <div class="col-md-6 mb-0 text-left">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Minimum Amount</label>
                                                            <span style="color:red">*</span>
                                                            <input type="text" onkeyup="addThousands(this);"
                                                                name="min_amount" id="min_amount"
                                                                value="{{ number_format($current_offer->minimum_amount) }}"
                                                                class="form-control col-lg-12"
                                                                placeholder="Minimum Amount" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-0 text-left">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Maximum Amount</label>
                                                            <span style="color:red">*</span>
                                                            <input type="text" onkeyup="addThousands(this);"
                                                                name="max_amount" id="max_amount"
                                                                value="{{ number_format($current_offer->maximum_amount) }}"
                                                                class="form-control col-lg-12"
                                                                placeholder="Maximum Amount" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div
                                                        class="col-md-{{ !isset($for_bank) ? '6' : '12' }} mb-0 text-left">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Rate held Until</label>
                                                            <span style="color:red">*</span>
                                                            <input type="text" id="offer_expiry"
                                                                value="{{ changeDateFromUTCtoLocal($current_offer->rate_held_until ? $current_offer->rate_held_until : $current_offer->offer_expiry, \App\Constants::DATE_TIME_FORMAT_NO_SECONDS) }}"
                                                                required name="expdate"
                                                                class="form-control col-md-12 datetimepicker"
                                                                placeholder="Rate held Until" />
                                                        </div>
                                                    </div>
                                                    @if (!isset($for_bank))
                                                        <div class="col-md-6 mb-0 text-left">
                                                            <div class="form-group">
                                                                <label style="font-weight:bold;">Counter Offer
                                                                    Expiry</label>
                                                                <span style="color:red" class="counterInfo"><i
                                                                        class="fa fa-exclamation-circle text-primary"
                                                                        aria-hidden="true"></i>* <span class="title">FI
                                                                        has to accept, decline, expire or counter on or
                                                                        before this time</span></span>
                                                                <input type="text" name="counter_offer_expiry"
                                                                    id="counter_offer_expiry"
                                                                    value="{{ changeDateFromUTCtoLocal(getUTCTimeNow()->addMinutes(30), \App\Constants::DATE_TIME_FORMAT_NO_SECONDS) }}"
                                                                    class="form-control col-lg-12 counter-offer-datetimepicker"
                                                                    placeholder="Counter Offer Expiry" required />
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-left">
                                                        <label style="font-weight:bold;">Interest Rate Offer
                                                            <small>(Simple Annual Interest Rate)</small></label> <span
                                                            style="color:red">*</span>
                                                        @if ($deposit_request->term_length_type != 'HISA')
                                                            <div class="form-group-feedback form-group-feedback-right">
                                                                <input type="number" name="nir" min="0.01"
                                                                    max="100" class="form-control col-lg-12"
                                                                    value="{{ $current_offer ? ($current_offer->interest_rate_offer ? $current_offer->interest_rate_offer : $current_offer->offered_interest_rate) : '' }}"
                                                                    placeholder="Interest Rate " step=".01"
                                                                    required />
                                                                <div class="form-control-feedback">
                                                                    <i style="margin-top: 12px;"
                                                                        class="fa fa-percent text-muted"></i>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="row">
                                                                <div class="col-md-2 text-left">
                                                                    <label class="radio-inline"><input type="radio"
                                                                            {{ $current_offer && $current_offer->rate_type == 'FIXED' ? 'checked' : '' }}
                                                                            class="rate_type" name="rate_type"
                                                                            value="fixed" checked />
                                                                        &nbsp;Fixed</label>
                                                                </div>
                                                                <div class="col-md-4 text-left">
                                                                    <label class="radio-inline"><input type="radio"
                                                                            {{ $current_offer && $current_offer->rate_type == 'VARIABLE' ? 'checked' : '' }}
                                                                            class="rate_type" name="rate_type"
                                                                            value="variable" /> &nbsp;Variable</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div
                                                                    class="form-group-feedback form-group-feedback-right col-md-12 fixed_interest_rate_container">
                                                                    <input type="number" name="nir"
                                                                        min="0.01" max="100"
                                                                        value="{{ $current_offer ? ($current_offer->interest_rate_offer ? $current_offer->interest_rate_offer : $current_offer->offered_interest_rate) : '' }}"
                                                                        class="form-control col-lg-12"
                                                                        placeholder="Fixed Interest Rate "
                                                                        step=".01" required />
                                                                    <div class="form-control-feedback">
                                                                        <i style="margin-top: 20px;"
                                                                            class="fa fa-percent text-muted"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div
                                                                    class="form-inline col-md-12 variable_rate_container">
                                                                    <div class="form-group-feedback form-group-feedback-right col-md-6"
                                                                        style="padding: 0">
                                                                        <input type="number" name="prime_rate"
                                                                            min="0.01" max="100"
                                                                            class="form-control col-md-12"
                                                                            placeholder="Prime Rate " step=".01"
                                                                            disabled value="{{ $prime_rate }}"
                                                                            style="width: 100%" />
                                                                        <div class="form-control-feedback">
                                                                            <i style="margin-top: 20px;"
                                                                                class="fa fa-percent text-muted"></i>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1">
                                                                        <select class="form-control"
                                                                            name="rate_operator">
                                                                            <option
                                                                                {{ $current_offer && $current_offer->rate_operator == '+' ? 'selected' : '' }}
                                                                                value="+">+</option>
                                                                            <option
                                                                                {{ $current_offer && $current_offer->rate_operator == '-' ? 'selected' : '' }}
                                                                                value="-">-</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group-feedback form-group-feedback-right col-md-5"
                                                                        style="padding: 0">
                                                                        <input type="number" name="fixed_rate"
                                                                            min="0.01" max="100"
                                                                            value="{{ $current_offer ? $current_offer->fixed_rate : '' }}"
                                                                            class="form-control col-md-12"
                                                                            placeholder="Spread Rate " step=".01"
                                                                            style="width: 100%" />
                                                                        <div class="form-control-feedback">
                                                                            <i style="margin-top: 20px;"
                                                                                class="fa fa-percent text-muted"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if (isset($for_bank))
                                                    <div class="row col-md-12 text-left">
                                                        <div class="form-group" id="fileinput"
                                                            style="padding-right: 0;width: 100%;">
                                                            <label style="font-weight:bold">Product Disclosure
                                                                Statement <span
                                                                    style="font-weight: normal;color: red;">Max. 25
                                                                    mb</span></label>
                                                            <div class="row ">
                                                                <div class="input-group col-md-7">
                                                                    <div class="input-group-prepend">
                                                                        <span>
                                                                            <select class="form-control pre_url "
                                                                                name="pre_url" required>
                                                                                <option value="https://">https://
                                                                                </option>
                                                                                <option value="http://">http://
                                                                                </option>
                                                                            </select>
                                                                        </span>
                                                                    </div>
                                                                    <input id="uurl" name="url"
                                                                        type="text" placeholder="Add Url Here"
                                                                        onblur="return checkURLHttps(this)"
                                                                        class="form-control"
                                                                        value="{{ $current_offer ? str_replace('http://', '', str_replace('https://', '', $current_offer->product_disclosure_url)) : '' }}" />
                                                                </div>
                                                                <div class="col-md-5 row">
                                                                    <input type="file" name="file"
                                                                        class="form-control col-md-9 file" />
                                                                    <button type="button" onclick="removeFile(this);"
                                                                        class="btn btn-danger btn-sm col-md-2"
                                                                        style="height: 30px;margin-top: 10px;margin-left: 5px;">
                                                                        X
                                                                    </button>
                                                                    @if (!empty($current_offer->product_disclosure_statement))
                                                                        <br />
                                                                        <a href="{{ url('uploads/' . str_replace('uploads/', '', $current_offer->product_disclosure_statement)) }}"
                                                                            target="_blank"
                                                                            class="btn btn-link disclosure_attachmentF">
                                                                            {{ str_replace('uploads/', '', $current_offer->product_disclosure_statement) }}</a>
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="attached_file"
                                                                    name="attached_file"
                                                                    value="{{ $current_offer && $current_offer->product_disclosure_statement ? $current_offer->product_disclosure_statement : '' }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-12 mb-0 text-left">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Special
                                                                Instructions</label>
                                                            <textarea id="my" maxlength="100" type="text" class="form-control textareaWithTextLimit"
                                                                placeholder="Special instructions" name="special_ins"></textarea>
                                                            <span style="font-weight:300" class="rchars">100</span>
                                                            <span style="font-weight:300">Character(s) Remaining</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12 md-0 text-center">
                            <div class="box-action">
                                <button class="btn custom-secondary round btn-md m-2" type="button"
                                    data-dismiss="modal">Cancel</button>
                                <button class="btn custom-primary round btn-md m-2" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .xdsoft_datetimepicker.xdsoft_noselect.xdsoft_ {
        z-index: 10050 !important;
    }

    @media (min-width: 576px) {
        .modal-dialog.fix-width {
            max-width: 60% !important;
        }
    }

    /*.datepicker {*/
    /*    z-index: 1600 !important; !* has to be larger than 1050 *!*/
    /*}*/
    .title {
        color: whitesmoke;
        background: #4e4545;
        position: absolute;
        display: none;
        width: 40%;
        margin-top: -18px;
        margin-left: 10px;
        white-space: initial;
        padding: 5px;
        border-radius: 10px;
        z-index: 10;
    }

    .counterInfo:hover .title {
        display: inline-block;
    }

    .btn-theme-secondary {
        color: #555;
        background-color: #ffffff;
        outline: 1px solid #000000;
    }

    .btn-theme-secondary:hover,
    .btn-theme-secondary:focus {
        background-color: #f9f9f9;
        outline: 1px solid #000000 !important;
    }

    .box {
        height: 100%;
    }

    .box.offerOne>div h6 {
        min-width: 0;
    }

    .box>div {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .box.offerOne>div:hover {
        cursor: pointer;
        background: lightgray;
    }

    .box.offerOne {
        cursor: pointer;
        border-radius: 10px;
        background: #ccc;
    }

    .box>div h4,
    .box>div h6 {
        margin-bottom: 5px;
    }

    .box>div h6 {
        font-weight: 500;
        word-break: break-all;
    }

    .box>div h4 {
        font-weight: 700;
    }

    .box>div.box-action {
        flex-direction: row;
        margin: 5px;
    }
</style>
@if (isset($for_bank) || isset($force_show))
    <link href="{{ asset('/assets/dashboard/datetimepicker/build/jquery.datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css" />
    <script src="{{ asset('assets/dashboard/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
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
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/bid.js?v=1.1') }}"></script>
    <script>
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
@endif

<script>
    $(document).ready(function() {
        //

        $('.bid_offer').data('serialize', $('.bid_offer').serialize());

        let close_btn_clicked_yes = false;
        $('#counterOffer{{ $offer->id }}').on('hide.bs.modal', function() {
            if ($(".bid_offer").serialize() == $(".bid_offer").data('serialize')) {
                close_btn_clicked_yes = true;
            }

            if (close_btn_clicked_yes) {
                close_btn_clicked_yes = false;
                return true;
            }
            swal({
                title: "Are you sure to close this page?",
                text: "The changes will be lost!",
                icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    close_btn_clicked_yes = true;
                    $('#counterOffer{{ $offer->id }}').modal('hide');
                    $('.bid_offer').trigger("reset");
                } else {
                    close_btn_clicked_yes = false;
                }
            });
            return false;
        });

        @if ($offer && $offer->rate_type == 'FIXED')
            $(".variable_rate_container").hide();
            $(".fixed_interest_rate_container").show();
        @elseif ($offer && $offer->rate_type == 'VARIABLE')
            $(".variable_rate_container").show();
            $(".fixed_interest_rate_container").hide();
        @else
            $(".fixed_interest_rate_container").show();
            $(".variable_rate_container").hide();
        @endif

        $(document).on("change", ".rate_type", function() {
            switch ($(this).val()) {
                case 'fixed':
                    $(".fixed_interest_rate_container").show();
                    $(".fixed_interest_rate_container").find("input").attr("required", "required");
                    $(".variable_rate_container").hide();
                    $(".variable_rate_container").find("input").removeAttr("required");
                    break;
                case 'variable':
                    $(".fixed_interest_rate_container").hide();
                    $(".fixed_interest_rate_container").find("input").removeAttr("required");
                    $(".variable_rate_container").show();
                    $(".variable_rate_container").find("input").attr("required", "required");
                    break;
            }
        });

        $(document).on("submit", ".bid_offer", function() {

            if ($(this).serialize() == $(this).data('serialize')) {
                swal("Nothing Updated", "Please make changes to submit.");
                return false;
            }

            if (parseInt($(this).find("#min_amount").val().replace(/,/g, "")) > amount) {
                swal("Minimum amount.", "The minimum amount cannot be greater than Requested Amount.");
                return false;
            }

            if (parseInt($(this).find("#max_amount").val().replace(/,/g, "")) > amount) {
                swal("Maximum amount.", "The maximum amount cannot be greater than Requested Amount.");
                return false;
            }

            if (parseInt($(this).find("#max_amount").val().replace(/,/g, "")) <= 0) {
                swal("Maximum amount.", "The maximum amount cannot be Zero.");
                return false;
            }

            if (parseFloat($(this).find("[name='min_amount']").val().replace(/,/g, "")) > parseFloat($(
                    this).find("[name='max_amount']").val().replace(/,/g, ""))) {
                swal("Minimum amount.", "The minimum cannot be greater than maximum amount.");
                $("[name='min_amount']").val(" ");
                return false;
            }

            if ((new Date($(this).find("#counter_offer_expiry").val())).getTime() < (new Date())
                .getTime()) {
                swal("Counter Offer Expiry.",
                    "Counter Offer Expiry should not be less than the present time");
                return false;
            }


            swal({
                title: "Do you want to submit this counter offer?",
                text: "Your  counter offer will be sent.",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {

                    let $this_ = $(".confirm_btn");
                    let $loader = $("#cover-spin");
                    let formData = new FormData(this);

                    let min_amount = $(this).find("input[name=min_amount]").val();
                    formData.append("min_amount", parseFloat(min_amount.replace(/,/g, '')));

                    let max_amount = $(this).find("input[name=max_amount]").val();
                    formData.append("max_amount", parseFloat(max_amount.replace(/,/g, '')));

                    $this_.attr('disabled', true).html('Please wait');
                    $('#exampleModalCenter').modal('hide');
                    makeApiCallWithFiles("{{ url('counter-offer') }}", formData, function(
                        response) {
                        if (response.success) {
                            swal(response.message_title, response.message).then(
                                function() {
                                    $loader.show();
                                    window.onbeforeunload = null;
                                    window.location.reload();
                                });
                        } else {
                            swal("", response.message);
                        }
                        $this_.attr('disabled', false).html('Submit');
                    }, $loader, "POST", function(xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)) {
                            swal("An error occurred, the page will refresh.").then(
                                () => {
                                    window.onbeforeunload = null;
                                    window.location.reload();
                                });
                            return;
                        }

                        swal("", apiCallServerErrorMessage(xhr,
                            "Unable to submit the counter offer, try again later",
                            "error"));
                        $this_.attr('disabled', false).html('Submit');
                    });

                }
            });
            //     }
            //});

            return false;
        });

    });
</script>

@php
    $system_setting = getSystemSettings('prime_rate');
    $prime_rate = $system_setting->value;
    $user = auth()->user();
@endphp
@section('styles')
    <!-- Select 2 -->
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <style>
        p {
            color: grey;
            font-size: 13px;
        }

        .select2-selection {
            padding-bottom: 30px !important;
        }

        p {
            color: grey;
            font-size: 13px;
        }

        .table-responsive {
            padding-left: 0px;
        }

        .termsx {
            overflow-y: scroll;
            /*height: 300px;*/
            width: 100%;
            border: 1px solid #DDD;
            padding: 10px;
        }

        #step1 a,
        #step1 h6,
        #step1 b button {
            font-weight: 500;
            font-size: 15px;
        }

        .swal-modal .swal-text {
            text-align: center;
        }

        .swal-footer {
            text-align: center;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/datetimepicker/jquery.datetimepicker.css') }}" />
@endsection
<div class='row'>
    <div class="col-md-12 ">
        <div class="card">
            <form action="#" id="bid_offer_form" method="post" autocomplete="off" enctype="multipart/form-data"
                class="bid_offer form-horizontal">
                <div class="card-body">
                    <h5 class="my_h" style="color:#2CADF5;font-weight:bold;text-transform:uppercase;"><span class="b_b-off">PLA</span>CE OFFER</h5>
                    <!-- /main charts -->
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label style="font-weight:bold;">Minimum Amount</label>
                                <span style="color:red">*</span>
                                <input type="text" onkeyup="addThousands(this);" name="min_amount" id="min_amount"
                                    value="{{ $offer ? $offer->minimum_amount : '' }}" class="form-control col-lg-11"
                                    placeholder="Minimum Amount" required />
                            </div>
                            <div class="form-group">
                                <label style="font-weight:bold;">Maximum Amount </label>
                                <span style="color:red">*</span>
                                <input type="text" onkeyup="addThousands(this);" name="max_amount" id="max_amount"
                                    value="{{ $offer ? $offer->maximum_amount : '' }}" class="form-control col-lg-11"
                                    placeholder="Maximum Amount" required />
                            </div>
                            <div class="form-group col-lg-11"
                                style="padding-right: 0!important;padding-left: 0!important;">
                                <label style="font-weight:bold;">Interest Rate Offer <small>(Simple Annual Interest
                                        Rate)</small></label> <span style="color:red">*</span>
                                @if ($deposit_request->term_length_type != 'HISA')
                                    <div class="form-group-feedback form-group-feedback-right">
                                        <input type="number" name="nir" min="0.01" max="100"
                                            class="form-control col-lg-12"
                                            value="{{ $offer ? $offer->interest_rate_offer : '' }}"
                                            placeholder="Interest Rate " step=".01" required />
                                        <div class="form-control-feedback">
                                            <i style="margin-top: 12px;" class="fa fa-percent text-muted"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-2 text-left">
                                            <label class="radio-inline"><input type="radio"
                                                    {{ $offer && $offer->rate_type == 'FIXED' ? 'checked' : '' }}
                                                    class="rate_type" name="rate_type" value="fixed" checked />
                                                &nbsp;Fixed</label>
                                        </div>
                                        <div class="col-md-4 text-left">
                                            <label class="radio-inline"><input type="radio"
                                                    {{ $offer && $offer->rate_type == 'VARIABLE' ? 'checked' : '' }}
                                                    class="rate_type" name="rate_type" value="variable" />
                                                &nbsp;Variable</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div
                                            class="form-group-feedback form-group-feedback-right col-md-12 fixed_interest_rate_container">
                                            <input type="number" name="nir" min="0.01" max="100"
                                                value="{{ $offer ? $offer->interest_rate_offer : '' }}"
                                                class="form-control col-lg-12" placeholder="Fixed Interest Rate "
                                                step=".01" required />
                                            <div class="form-control-feedback">
                                                <i style="margin-top: 20px;" class="fa fa-percent text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-inline col-md-12 variable_rate_container">
                                            <div class="form-group-feedback form-group-feedback-right col-md-5 row">
                                                <input type="number" name="prime_rate" min="0.01" max="100"
                                                    class="form-control col-md-12" placeholder="Prime Rate "
                                                    step=".01" disabled value="{{ $prime_rate }}"
                                                    style="width: 100%" />
                                                <div class="form-control-feedback">
                                                    <i style="margin-top: 20px;" class="fa fa-percent text-muted"></i>
                                                </div>
                                            </div>

                                            <select class="form-control" name="rate_operator">
                                                <option {{ $offer && $offer->rate_operator == '+' ? 'selected' : '' }}
                                                    value="+">+</option>
                                                <option {{ $offer && $offer->rate_operator == '-' ? 'selected' : '' }}
                                                    value="-">-</option>
                                            </select>

                                            <div class="form-group-feedback form-group-feedback-right col-md-5 row">
                                                <input type="number" name="fixed_rate" min="0.01"
                                                    max="100" value="{{ $offer ? $offer->fixed_rate : '' }}"
                                                    class="form-control col-md-12" placeholder="Spread Rate "
                                                    step=".01" style="width: 100%" />
                                                <div class="form-control-feedback">
                                                    <i style="margin-top: 20px;" class="fa fa-percent text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:bold;">Rate held Until </label>
                                <span style="color:red">*</span>
                                <!--<input type="text" id="offer_expiry" value="{{ $offer ? changeDateFromUTCtoLocal($offer->rate_held_until, \App\Constants::DATE_TIME_NEW_12_24_HRS_FORMAT) : '' }}" required name="expdate" class="form-control col-md-12 datetimepicker" placeholder="Rate held Until"/>-->
                                <input type="text" id="offer_expiry"
                                    value="{{ $offer ? timeIn_12_24_format($offer->rate_held_until) : '' }}" required
                                    name="expdate" class="form-control col-md-12 datetimepicker"
                                    placeholder="Rate held Until" />
                            </div>
                            <div class="row col-md-12">
                                <div class="form-group" id="fileinput" style="padding-right: 0;width: 100%;">
                                    <label style="font-weight:bold">Product Disclosure Statement <span
                                            style="font-weight: normal;color: red;">Max. 25 mb</span></label>
                                    <div class="row ">
                                        <div class="input-group col-md-7">
                                            <div class="input-group-prepend">
                                                <span>
                                                    <select class="form-control pre_url " name="pre_url" required style="height: 35px !important;">
                                                        <option
                                                            {{ $offer && strpos($offer->product_disclosure_url, 'https://') === 0 ? 'selected' : '' }}
                                                            value="https://">https://</option>
                                                        <option
                                                            {{ $offer && strpos($offer->product_disclosure_url, 'http://') === 0 ? 'selected' : '' }}
                                                            value="http://">http://</option>
                                                    </select>
                                                </span>
                                            </div>
                                            <input id="uurl" name="url" type="text"
                                                placeholder="Add Url Here" onblur="return checkURLHttps(this)"
                                                class="form-control"
                                                value="{{ $offer ? str_replace('http://', '', str_replace('https://', '', $offer->product_disclosure_url)) : '' }}" />
                                        </div>
                                        <div class="col-md-5 row">
                                            <input type="file" name="file"
                                                class="form-control col-md-9 file" />
                                            <button type="button" onclick="removeFile(this);"
                                                class="btn btn-danger btn-sm col-md-2"
                                                style="height: 30px;margin-top: 6px;margin-left: 5px;">
                                                X
                                            </button>
                                            @if (!empty($offer->product_disclosure_statement))
                                                <br />
                                                <a href="{{ url('uploads/' . str_replace('uploads/', '', $offer->product_disclosure_statement)) }}"
                                                    target="_blank" class="btn btn-link disclosure_attachmentF">
                                                    {{ str_replace('uploads/', '', $offer->product_disclosure_statement) }}</a>
                                            @endif
                                        </div>
                                        <input type="hidden" id="attached_file" name="attached_file"
                                            value="{{ $offer && $offer->product_disclosure_statement ? $offer->product_disclosure_statement : '' }}" />
                                        <input type="hidden" name="request_id"
                                            value="{{ \App\CustomEncoder::urlValueEncrypt($deposit_request->id) }}" />
                                        <input type="hidden" name="term_length_type"
                                            value="{{ $deposit_request->term_length_type }}" />
                                        <input type="hidden" name="offer_id"
                                            value="{{ $offer ? \App\CustomEncoder::urlValueEncrypt($offer->id) : '' }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label style="font-weight:bold;">Special Instructions</label>
                                    <textarea id="my" maxlength="100" type="text" class="form-control textareaWithTextLimit"
                                        placeholder="Special instructions" name="special_ins">{{ $offer ? $offer->special_instructions : '' }}</textarea>
                                    <span style="font-weight:300" class="rchars">100</span>
                                    <span style="font-weight:300">Character(s) Remaining</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ url('new-requests') }}" class="btn btn-default custom-secondary round ">Go
                                Back</a>
                            @if ($user->userCan('bank/new-requests/submit-offer-button') || $user->userCan('bank/in-progress/edit-button'))
                                <button type="submit" class="btn btn-primary confirm_btn custom-primary round">
                                    Submit Offer
                                </button>
                            @endif
                            <br />
                            <br />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@section('scripts')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
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
    </script>
    <script src="{{ asset('assets/dashboard/js/bid.js?v=1.2') }}"></script>
    <script>
        $(document).ready(function() {
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
        });

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

        /*$(document).on("click", ".pre-submit-offer", function () {
            if (!$('#bid_offer_form')[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $('.confirm_btn').click();
            } else {*/
        $(document).on("submit", ".bid_offer", function() {

            if (parseInt($("#min_amount").val().replace(/,/g, "")) > amount) {
                swal("Minimum amount.", "The minimum amount cannot be greater than Requested Amount.");
                return false;
            }

            if (parseInt($("#max_amount").val().replace(/,/g, "")) > amount) {
                swal("Maximum amount.", "The maximum amount cannot be greater than Requested Amount.");
                return false;
            }

            if (parseInt($("#max_amount").val().replace(/,/g, "")) <= 0) {
                swal("Maximum amount.", "The maximum amount cannot be Zero.");
                return false;
            }

            if (parseFloat($("[name='min_amount']").val().replace(/,/g, "")) > parseFloat($("[name='max_amount']")
                    .val().replace(/,/g, ""))) {
                swal("Minimum amount.", "The minimum cannot be greater than maximum amount.");
                $("[name='min_amount']").val(" ");
                return false;
            }

            //$('#exampleModalCenter').modal('show');
            swal({
                title: "Do you want to submit this offer?",
                text: "Your offer will be sent to the depositor.",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {

                    let $this_ = $(".confirm_btn");
                    let $loader = $("#cover-spin");
                    // let $this_form = $(".bid_offer");
                    let formData = new FormData(this); //$this_form.serializeArray();

                    let min_amount = $("input[name=min_amount]").val();
                    formData.append("min_amount", parseFloat(min_amount.replace(/,/g, '')));

                    let max_amount = $("input[name=max_amount]").val();
                    formData.append("max_amount", parseFloat(max_amount.replace(/,/g, '')));

                    $this_.attr('disabled', true).html('Please wait');
                    $('#exampleModalCenter').modal('hide');
                    makeApiCallWithFiles("{{ route('bank.submit.place-offer') }}", formData, function(
                        response) {
                        if (response.success) {
                            swal(response.message_title, response.message).then(function() {
                                $loader.show();
                                window.onbeforeunload = null;
                                window.location.href = "/in-progress";
                            });
                        } else {
                            swal("", response.message);
                        }
                        $this_.attr('disabled', false).html('Submit');
                    }, $loader, "POST", function(xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)) {
                            swal("An error occurred, the page will refresh.").then(() => {
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                            return;
                        }

                        swal("", apiCallServerErrorMessage(xhr,
                            "Unable to submit the offer, try again later", "error"));
                        $this_.attr('disabled', false).html('Submit');
                    });

                }
            });
            //     }
            //});

            return false;
        });
    </script>
@endsection

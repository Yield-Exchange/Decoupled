<style>
    .request_count {
        color: #2CADF5;
        font-weight: bold;
        font-size: 17px;
        padding-left: 2px;
    }
</style>
<form action="#" method="post" id="myform" autocomplete="off" class="validater_form post_request_form">
    <input type="hidden" name="deposit_request_id" value="{{ $deposit_request ? $deposit_request->id : '' }}" />
    @if ($market_place_offer)
        <input type="hidden" name="market_place_offer_id" value="{{ $market_place_offer->id }}" />
    @endif
    @csrf

    @php
        $user = auth()->user();
    @endphp
    <div class="card post_request_form_container post_request_container_default" @if(request()->filled('demo_setup')) style="background: pink" @endif>
        <div class="table-responsive" style="padding-bottom: 0!important;">
            <table class="table text-nowrap">
                <tbody>
                    <tr class="table-active table-border-double">
                        <td colspan="3" class="my_h"><span class="b_b">POST</span> REQUEST <span
                                class="request_count"></span></td>
                        <td class="text-right"><h4>{{ $user->is_super_admin ? 'Posting for: '.$organization->name : '' }}</h4></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 col-lg-5">
                        <div class="form-group row">
                            <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                <label style="font-weight:bold;">
                                    Product<span style="color:red">*</span> 
                                </label>
                                <x-defination  section='product' />
                                <div class="form-group">
                                    <select name="product_id[]" class="form-control myinput productos" required>
                                        @php
                                            $products = \App\Models\Product::where("is_disabled", 'no')->get();
                                        @endphp
                                        @foreach ($products as $product)
                                            <option
                                                {{ in_array($product->id, [$market_place_offer ? $market_place_offer->product_id : 0, $deposit_request ? $deposit_request->product->id : 0]) ? 'selected' : '' }}
                                                value="{{ $product->description }}">{{ $product->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row lockout-period-container">
                            <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                <label class="period-label" style="font-weight:bold;">Lockout period (days)</label>
                                <x-lockout-definition />
                                <div class="form-group">
                                    <input type="number" min="1"
                                        value="{{ $deposit_request && in_array($deposit_request->product->description, ['Cashable', 'Notice deposit']) && $deposit_request->lockout_period_days > 0 ? $deposit_request->lockout_period_days : ($market_place_offer && in_array($market_place_offer->product->description, ['Cashable', 'Notice deposit']) && $market_place_offer->lockout_period_days > 0 ?: '') }}"
                                        name="lockout_period[]" onchange="compareTermLength()" placeholder="-"
                                        class="form-control lockout_period"
                                        {{ ($deposit_request && in_array($deposit_request->product->description, ['Cashable', 'Notice deposit'])) || ($market_place_offer && in_array($market_place_offer->product->description, ['Cashable', 'Notice deposit'])) ? '' : 'readonly' }}
                                        oninput="this.value = Math.abs(this.value)" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                <label style="font-weight:bold;">Deposit Amount <span style="color:red">*</span>
                                </label>
                                <x-deposit-amount-definition />
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span>
                                            <select class="form-control deposit_currency" name="deposit_currency[]"
                                                required style="height: 33px !important;">
                                                <option
                                                    {{ in_array('CAD', [$deposit_request ? $deposit_request->currency : '', $market_place_offer ? $market_place_offer->currency : '']) ? 'selected' : '' }}
                                                    value="CAD">CAD</option>
                                                <option
                                                    {{ in_array('USD', [$deposit_request ? $deposit_request->currency : '', $market_place_offer ? $market_place_offer->currency : '']) ? 'selected' : '' }}
                                                    value="USD">USD</option>
                                            </select>
                                        </span>
                                    </div>
                                    <input type="text"
                                        value="{{ $deposit_request ? number_format($deposit_request->amount, 0) : '' }}"
                                        onkeyup="addThousands(this);" placeholder="Deposit Amount"
                                        name="deposit_amount[]" id="dep_amm" class="form-control dep_amm myinput"
                                        required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6">
                        <div class="form-group row term-length-container">
                            <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <label style="font-weight:bold;">Term length<span style="color:red">*</span>
                                    </label>
                                <x-term-definition />
                                <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span>
                                                <select class="form-control term_type" name="term_type[]"
                                                    onchange="compareTermLength()" required style="height: 33px !important;">
                                                    <option value="">Select term</option>
                                                    <option
                                                        {{ in_array('MONTHS', [$deposit_request ? $deposit_request->term_length_type : '', $market_place_offer ? $market_place_offer->term_length_type : '']) ? 'selected' : '' }}
                                                        value="months">Months</option>
                                                    <option
                                                        {{ in_array('DAYS', [$deposit_request ? $deposit_request->term_length_type : '', $market_place_offer ? $market_place_offer->term_length_type : '']) ? 'selected' : '' }}
                                                        value="days">Days</option>
                                                </select>
                                            </span>
                                        </div>
                                        <input type="number"
                                            value="{{ $deposit_request ? $deposit_request->term_length : ($market_place_offer && $market_place_offer->term_length ? number_format($market_place_offer->term_length, 0) : '') }}"
                                            placeholder="Term length" min="1" name="term_length[]"
                                            onchange="compareTermLength()" class="form-control myinput term_length"
                                            required />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <label style="font-weight:bold;">Closing Date &amp; Time <span
                                            style="color:red">*</span> </label>
                                    <x-closure-date-definition />
                                    <!--<input id="offer_closing_date" value="{{ $deposit_request ? changeDateFromUTCtoLocal($deposit_request->closing_date_time, \App\Constants::DATE_TIME_NEW_12_24_HRS_FORMAT) : '' }}" placeholder="Closing Date & Time" type="text" name="closing_date_time[]" class="form-control myinput datetimepicker" required />-->
                                    <input id="offer_closing_date"
                                        value="{{ $deposit_request ? timeIn_12_24_format($deposit_request->closing_date_time) : '' }}"
                                        placeholder="Closing Date & Time" type="text" name="closing_date_time[]"
                                        class="form-control myinput datetimepicker" required />
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <label style="font-weight:bold;">Date of deposit (approx) <span
                                            style="color:red">*</span></label>
                                    <x-date-of-deposit-definition />
                                    <input type="text"
                                        value="{{ $deposit_request ? changeDateFromUTCtoLocal($deposit_request->date_of_deposit, \App\Constants::DATE_FORMAT) : '' }}"
                                        id="offer_opening_date" placeholder="Date of deposit (approx)"
                                        name="date_of_deposit[]" class="form-control myinput date_picker" required />
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="table-responsive" style="padding-left:0">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr class="table-active table-border-double">
                                <td style="padding-left:0px;cursor:pointer;" class="toggle_advance_button">
                                    <span class="b_b">ADV</span>ANCED OPTIONS &nbsp;&nbsp;
                                    <img src="{{ asset('image/navigate-arrows-pointing-to-down.png') }}"
                                        class="img-advance-options" height="15px" />
                                </td>
                                <td class="text-left"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="toggle_advance_button_container row col-md-12">
                    <div class="row">
                        <div class="col-md-5 col-lg-5">

                            <div class="form-group row">
                                <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                    <label style="font-weight:bold;">Compounding frequency<span
                                            style="color:red">*</span> </label>
                                    <x-compound-frequency-definition />
                                    <div class="form-group">
                                        <select class="form-control myinput compound_frequency"
                                            name="compound_frequency[]">
                                            <option
                                                {{ in_array('At maturity', [$deposit_request ? $deposit_request->compound_frequency : '', $market_place_offer ? $market_place_offer->compound_frequency : '']) ? 'selected' : '' }}
                                                value="At maturity">At maturity</option>
                                            <option
                                                {{ in_array('Monthly', [$deposit_request ? $deposit_request->compound_frequency : '', $market_place_offer ? $market_place_offer->compound_frequency : '']) ? 'selected' : '' }}
                                                value="Monthly">Monthly</option>
                                            <option
                                                {{ in_array('Quarterly', [$deposit_request ? $deposit_request->compound_frequency : '', $market_place_offer ? $market_place_offer->compound_frequency : '']) ? 'selected' : '' }}
                                                value="Quarterly">Quarterly</option>
                                            <option
                                                {{ in_array('Semi annually', [$deposit_request ? $deposit_request->compound_frequency : '', $market_place_offer ? $market_place_offer->compound_frequency : '']) ? 'selected' : '' }}
                                                value="Semi annually">Semi annually</option>
                                            <option
                                                {{ in_array('Annually', [$deposit_request ? $deposit_request->compound_frequency : '', $market_place_offer ? $market_place_offer->compound_frequency : '']) ? 'selected' : '' }}
                                                value="Annually">Annually</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                    <label style="font-weight:bold;">Special Instructions </label>
                                    <input type="hidden" name="anonymous" value="off" />
                                    <textarea id="my" maxlength="100" type="text" class="form-control myinput textareaWithTextLimit"
                                        placeholder="Special instructions" name="special_instructions[]">{{ $deposit_request ? $deposit_request->special_instructions : '' }}</textarea>
                                    <div><span class="rchars">100</span> Character(s) Remaining</div>
                                    <br />
                                </div>
                            </div>
                            <div class="form-group row d-none">
                                <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                    <label style="font-weight:bold;">
                                        <div id="parent"> Ask Rate
                                            <div
                                                style="height: 20px;
                                                          width: 20px;
                                                          background-color: #274EB3;
                                                          color:white;
                                                          margin-left:8px;
                                                          border-radius: 50%;
                                                          display: inline-block;">
                                                <span style="margin-left:6.5px;"> ?</span>
                                            </div>
                                            <div id="hover-content" style="min-height:30px;">
                                                Offers are compared based on annual simple interest rate.
                                            </div>
                                        </div>
                                    </label>
                                    <x-ask-rate-definition />
                                    <div class="form-group-feedback form-group-feedback-right">
                                        <input
                                            value="{{ $deposit_request && $deposit_request->requested_rate > 0 ? $deposit_request->requested_rate : ($market_place_offer && $market_place_offer->interest_rate ? $market_place_offer->interest_rate : '') }}"
                                            type="number" name="requested_rate[]" min="0.00" max="100"
                                            step=".01" onkeypress="return isNumberKey(this)"
                                            class="form-control myinput" placeholder="Ask Rate %" />
                                        <div class="form-control-feedback">
                                            <i style="margin-top: 12px;" class="fa fa-percent text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="col-md-10" style="display: inline-block;">
                                        <label style="font-weight:bold;">Short Term Credit Rating</label>
                                        <x-short-rate-definition />
                                        <div class="form-group">
                                            <select type="text" class="form-control myinput credit_rating"
                                                name="credit_rating[]">
                                                @php
                                                    $credit_rating_types = \App\Models\CreditRatingType::where("status","ACTIVE")->orderBy('id', 'ASC')->get();
                                                @endphp
                                                @foreach ($credit_rating_types as $item)
                                                    <option
                                                        {{ $deposit_request && $deposit_request->requested_short_term_credit_rating == $item->description ? 'selected' : '' }}
                                                        value="{{ $item->description }}">{{ $item->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1" style="display: inline-block;">
                                        <a data-toggle="tooltip" class="no-page-exit-alert"
                                            title="<img src='{{ asset('assets/img/credit_rating.png') }}' style='width:100%' />">
                                            <i class="fa fa-info-circle" style="font-size: 20px"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-11 row">
                                <div class="col-xs-9 col-lg-9 col-md-9 col-sm-9">
                                    <label style="font-weight:bold;">Deposit Insurance</label>
                                    <x-deposit-insurance-definition />
                                    <div class="form-group">
                                        <select class="form-control myinput deposit_insurance"
                                            name="deposit_insurance[]">
                                            @php
                                                $deposit_insurances = \App\Models\DepositInsuranceType::all()
                                                    ->partition(function ($item) {
                                                        return strpos($item->description, 'Any') !== false; // This is move Any deposit insurance at the top
                                                    })
                                                    ->flatten();
                                            @endphp
                                            @foreach ($deposit_insurances as $item)
                                                <option
                                                    {{ $deposit_request && $deposit_request->requested_deposit_insurance == $item->description ? 'selected' : '' }}
                                                    value="{{ $item->description }}">{{ $item->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row col-md-12">
                    <div class="col-xs-11">
                        <button type="button" style="border:1.5px solid gainsboro"
                            class="btn btn-default button_clear custom-secondary round px-4 font-weight-bold">Clear</button>
                        <br />
                        <button type="button" class="btn btn-link remove-product-btn">Remove Product</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (empty($deposit_request))
        @include('dashboard.depositor.post_request.add-additional-request')
    @endif
    @php
        $user = auth()->user();
    @endphp
    @if ($user->userCan('depositor/post-request/post-request-button') ||
        $user->userCan('depositor/review-offers/edit-request'))
        <div class="request_submit_btn_container" style="box-shadow: none;">
            <button type="submit"
                class="btn btn-primary btn-lg mmy_btn submit_post_request custom-primary round px-4 font-weight-bold ">Next
                <i class="fa fa-angle-right"></i></button>
        </div>
    @endif
</form>

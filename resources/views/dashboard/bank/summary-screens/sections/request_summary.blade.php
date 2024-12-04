<div class="row">
    <div class="col-12">
        <h5 style="color:#2CADF5;font-weight:bold;">Request Summary</h5>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Request ID</p></div>
            <div class="col-md-7"><span style="font-weight:bold">{{ $deposit_request->reference_no }}</span></div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Product</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ $deposit_request->product->description }}
                </span>
            </div>
        </div>
        @if($deposit_request->term_length_type != "HISA")
            <div class="row">
                <div class="col-md-5"><p style="font-weight:bold;"> {{ $deposit_request->product && trim(strtolower($deposit_request->product->description)) =="notice deposit" ? 'Notice Period' : 'Lockout Period' }} </p></div>
                <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ !empty($deposit_request->lockout_period_days) && in_array(trim(strtolower($deposit_request->product->description)),['notice deposit','cashable']) ? $deposit_request->lockout_period_days.' Days' : '-' }}
                </span>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Requested Amount</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ $deposit_request->currency." ".number_format($deposit_request->amount) }}
                </span>
            </div>
        </div>
        @if($deposit_request->term_length_type != "HISA")
            <div class="row">
                <div class="col-md-5"><p style="font-weight:bold;">Term Length</p></div>
                <div class="col-md-7">
                <span style="font-weight:bold">
                    @if ($deposit_request->term_length_type == "HISA")
                        -
                    @else
                        {{ $deposit_request->term_length.' '.ucwords(strtolower($deposit_request->term_length_type)) }}
                    @endif
                </span>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Closing Date & Time</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <!--{{ changeDateFromUTCtoLocal($deposit_request->closing_date_time,\App\Constants::DATE_TIME_FORMAT_NO_SECONDS) }}-->
                    {{timeIn_12_24_format($deposit_request->closing_date_time)}}
                </span>
            </div>
        </div>
        <div class="row d-none">
            <div class="col-md-5"><p style="font-weight:bold;">Ask Rate</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                     {{ formatInterest($deposit_request->requested_rate) }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Date of deposit (approx)</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ changeDateFromUTCtoLocal($deposit_request->date_of_deposit,\App\Constants::DATE_FORMAT) }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Compounding frequency</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ $deposit_request->compound_frequency }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Short Term DBRS Rating</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ $deposit_request->requested_short_term_credit_rating }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Deposit Insurance</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ $deposit_request->requested_deposit_insurance }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Special Instructions</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ $deposit_request->special_instructions }}
                </span>
            </div>
        </div>
        @if (!empty($contract->gic_start_date))
            <div class="row">
                <div class="col-md-5"><p style="font-weight:bold;">Fund Received Date</p></div>
                <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ changeDateFromUTCtoLocal($contract->gic_start_date,\App\Constants::DATE_FORMAT) }}
                </span>
                </div>
            </div>
        @endif
    </div>
</div>
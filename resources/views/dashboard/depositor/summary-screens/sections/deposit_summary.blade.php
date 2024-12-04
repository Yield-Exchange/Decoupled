<div class="row">
    <div class="col-12">
        <h5 style="color:#2CADF5;font-weight:bold;text-transform:uppercase;">Deposit Summary</h5>
    </div>
    <div class="col-5">
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Deposit ID</p></div>
            <div class="col-md-7"><span style="font-weight:bold">{{ $contract->reference_no }}</span></div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Product</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ $deposit_request->product ? $deposit_request->product->description : '' }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">{{ trim(strtolower($deposit_request->product->description)) =="notice deposit" ? 'Notice Period' : 'Lockout Period' }} </p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ !empty($deposit_request->lockout_period_days) && in_array(trim(strtolower($deposit_request->product->description)),['notice deposit','cashable']) ? $deposit_request->lockout_period_days.' Days' : '-' }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">GIC Amount (Awarded Amount)</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ !in_array($contract->offered_amount,['WITHDRAWN']) ? $deposit_request->currency ." ".number_format($contract->offered_amount) : 0 }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        @if($contract->status=="PENDING_DEPOSIT")
        <div class="row">
            <script type="text/template" id="countdown-timer-template">
                <div class="time <%= label %>">
                    <span class="label"><%= label.length < 6 ? label : label.substr(0, 3)  %></span>
                    <span class="count curr top"><%= curr %></span>
                    <span class="count next top"><%= next %></span>
                    <span class="count next bottom"><%= next %></span>
                    <span class="count curr bottom"><%= curr %></span>
                </div>
            </script>

            <div class="col-md-12">
                <div class="row rate-held-container" data-toggle="tooltip" data-placement="top" title="Rate held till" style="height: 40px">
                    <div class="col-sm-5">
                        <p>Rate Held Until:</p>
                    </div>
                    <div class="col-sm-7 main-timer-container">
                        <div class="countdown-container" id="rate-held-till"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ( $deposit_request->term_length_type == "HISA" )
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">
                    Interest Rate Type</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ ucwords(strtolower($offer->rate_type)) }}
                </span>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Interest Rate</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                     @if ($offer->rate_type!='VARIABLE')
                        {!! formatInterest($offer->interest_rate_offer) !!}
                    @else
                        {!! formatInterest($offer->fixed_rate, true,$offer->rate_operator,true) !!}
                    @endif
                </span>
            </div>
        </div>
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
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Maturity Date</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ $contract->maturity_date ? changeDateFromUTCtoLocal($contract->maturity_date,\App\Constants::DATE_FORMAT) : '-' }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Compounding Frequency</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    {{ $deposit_request->compound_frequency }}
                </span>
            </div>
        </div>
    </div>
</div>
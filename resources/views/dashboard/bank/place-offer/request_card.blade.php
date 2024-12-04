<div class="row">
        @php
            $depositor = $deposit_request->customUser;
            $organization= $deposit_request->organization;
        @endphp
    <div class="col-md-5">
        <div class='card p-3 ml-1 ' id='summarycard' >
            <div class="row">
                <div class="col-12">
                    <h5 style="color:#2CADF5;font-weight:bold;text-transform:uppercase;margin-right: 50px;">Investor Information</h5>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;"  class="invisible">Name</p></div>
                        <div class="col-md-9">
                            <a {{$organization->demographicData ? 'href=https://'.$organization->demographicData->website.' target="_blank"' : '' }} ><span style="font-weight:bold;color:darkblue;text-transform:capitalize;">{{ $organization->name }}</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;" class="invisible">Location</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ $organization->demographicData ? $organization->demographicData->city. ', ' .$organization->demographicData->province  : ''  }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;" class="invisible">Industry</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ $organization->naics_code_id ? $organization->naics_code : ''  }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Name</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;"> {{ $depositor->name }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Job Title</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ optional($depositor->demographicData)->job_title  }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Email</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;">{{ $depositor->email  }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Telephone</p></div>
                        <div class="col-md-9"><span style="font-weight:bold">{{ formatPhone(optional($depositor->demographicData)->phone) }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Description</p></div>
                        <div class="col-md-9">
                            <span style="font-weight:bold">
                                {{ $organization->demographicData ? substr($organization->demographicData->description, 0, 40) : ''  }}
                            </span>
                            @if (strlen($organization->demographicData->description) > 40)
                                @include('dashboard.summary-model', ['organization' => $organization])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div style="position: absolute;top:20%;left:4%;">
                @if ( !empty($organization->logo) )
                    <img src="{{ url('image/'.$organization->logo) }}" width="80" height="80" alt="" style="border-radius: 50%;"/>
                @else
                    <div class="i-initial-inverse-big" style="width: 80px;height: 80px;color:#fff;line-height: 75px; font-size: 50px;text-transform:uppercase;">
                        {{ !empty($organization->name[0]) ? $organization->name[0] : 'Y' }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class='card p-3 ' id='requestcard' >
            <div class="row">
                <div class="col-12">
                    <h5 style="color:#2CADF5;font-weight:bold;text-transform:uppercase;">Request Summary</h5>
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
                        {{ !empty($deposit_request->lockout_period_days) && in_array(trim(strtolower($deposit_request->product->description)),['notice deposit','cashable']) ? $deposit_request->lockout_period_days.' days' : '-' }}
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
                    <div class="row d-none" >
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
                </div>
            </div>
        </div>
    </div>
</div>

<style>
        #summarycard {
            min-height: 370px;
        }
        #requestcard {
            min-height: 370px;
        }
    @media (max-width: 1300px) {
        #summarycard {
            height: 400px;
        }
        #requestcard {
            height: 400px;
        }
    }
    @media (max-width: 1200px) {
        #summarycard {
            height: 500px;
        }
        #requestcard {
            height: 500px;
        }
    }
</style>
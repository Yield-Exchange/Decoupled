@extends('dashboard.master')
@section('page_title')
    Dashboard
@stop
@section('page_content')
    @php
        $user = auth()->user();
    @endphp
    <div class="row c-dashboard" id="VueApp">
        <div class="col-xl-12">

            <div class="row">
                @if (!$user->userCan('bank/new-requests/page-access') &&
                    !$user->userCan('bank/in-progress/page-access') &&
                    !$user->userCan('bank/pending-deposits/page-access'))
                    <div class="col-sm-12">
                        <div class="alert alert-info">Welcome to Yield Exchange. It appears as if you do not have permissions
                            to operate the site. Please contact your organization administrator or info@yieldexchange.ca for
                            more information</div>
                    </div>
                @endif
                @if ($user->userCan('bank/new-requests/page-access'))
                    <div class="col-sm-4">
                        <div style="border-radius:20px;" class="card">
                            <h4 style="margin-left:15px;padding:15px;color:grey"><span
                                    style="border-bottom:3px solid #03a9f4">NEW</span> REQUESTS</h4>

                            <div class="row" style="padding:20px">
                                <div class="col-3">
                                    <img src="{{ asset('image/2.png') }}" class="img-responsive" style="max-height:60px" />
                                </div>
                                <div class="col-3">
                                    <h6 style="font-weight:bold;color:grey">No</h6>
                                    <p style="font-size:20px;font-weight:bold">{{ $new_requests['total'] }}</p>
                                </div>
                                <div class="col-{{ !empty($new_requests['total_deposit']['USD']) ? 3 : 6 }}">
                                    <h6 style="font-weight:bold;color:grey">CAD</h6>
                                    <p style="font-size:20px;font-weight:bold">
                                        {{ nice_number($new_requests['total_deposit']['CAD']) }}</p>
                                </div>
                                @if (!empty($new_requests['total_deposit']['USD']))
                                    <div class="col-3">
                                        <h6 style="font-weight:bold;color:grey">USD</h6>
                                        <p style="font-size:20px;font-weight:bold">
                                            {{ nice_number($new_requests['total_deposit']['USD']) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($user->userCan('bank/in-progress/page-access'))
                    <div class="col-sm-4">
                        <div style="border-radius:20px;" class="card">
                            <h4 style="margin-left:15px;padding:15px;color:grey"><span
                                    style="border-bottom:3px solid #03a9f4">IN</span> PROGRESS</h4>

                            <div class="row" style="padding:20px">

                                <div class="col-3">
                                    <img src="{{ asset('image/1.png') }}" class="img-responsive" style="max-height:60px">
                                </div>

                                <div class="col-3">
                                    <h6 style="font-weight:bold;color:grey">No</h6>
                                    <p style="font-size:20px;font-weight:bold">{{ $in_progress_data['total'] }}</p>
                                </div>
                                <div class="col-{{ !empty($in_progress_data['total_deposit']['USD']) ? 3 : 6 }}">
                                    <h6 style="font-weight:bold;color:grey">CAD</h6>
                                    <p style="font-size:20px;font-weight:bold">
                                        {{ nice_number($in_progress_data['total_deposit']['CAD']) }}
                                    </p>
                                </div>
                                @if (!empty($in_progress_data['total_deposit']['USD']))
                                    <div class="col-3">
                                        <h6 style="font-weight:bold;color:grey">USD</h6>
                                        <p style="font-size:20px;font-weight:bold">
                                            {{ nice_number($in_progress_data['total_deposit']['USD']) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($user->userCan('bank/pending-deposits/page-access'))
                    <div class="col-sm-4">
                        <div style="border-radius:20px;" class="card">
                            <h4 style="margin-left:15px;padding:15px;color:grey"><span
                                    style="border-bottom:3px solid #03a9f4">PEN</span>DING DEPOSITS</h4>
                            <div class="row" style="padding:20px">
                                <div class="col-3">
                                    <img src="{{ asset('image/3.png') }}" class="img-responsive" style="max-height:60px">
                                </div>
                                <div class="col-3" style="pedding:0px">
                                    <h6 style="font-weight:bold;color:grey">No</h6>
                                    <p style="font-size:20px;font-weight:bold">{{ $pending_deposit['total'] }}</p>
                                </div>
                                <div class="col-{{ !empty($pending_deposit['total_deposit']['USD']) ? 3 : 6 }}">
                                    <h6 style="font-weight:bold;color:grey">CAD</h6>
                                    <p style="font-size:20px;font-weight:bold">
                                        {{ nice_number($pending_deposit['total_deposit']['CAD']) }}</p>
                                </div>
                                @if (!empty($pending_deposit['total_deposit']['USD']))
                                    <div class="col-3">
                                        <h6 style="font-weight:bold;color:grey">USD</h6>
                                        <p style="font-size:20px;font-weight:bold">
                                            {{ nice_number($pending_deposit['total_deposit']['USD']) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if ($user->userCan('bank/new-requests/page-access'))
                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b">NEW</span> REQUESTS <span
                                            class="badge bg-blue badge-pill">{{ $new_requests['total'] }}</span></td>
                                    <td class="text-right"><a style="color:#03a9f4;text-decoration:underline;"
                                            href="{{ url('new-requests') }}">View all</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-condensed custom-data-tables">
                                <thead>
                                    <tr style="width:70%">
                                        <th>Request ID</th>
                                        <th>Depositor Name</th>
                                        <th>Province</th>
                                        <th>Request Amount</th>
                                        <th>Product</th>
                                        <th>Investment Period</th>
                                        <th>Closing Date & time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($new_requests['data'] as $record)
                                        @php
                                            $organization = $record->organization;
                                        @endphp
                                        <tr>
                                            <td class="text-left">
                                                <h6 class="mb-0">
                                                    {{ $record->reference_no }}
                                                </h6>
                                            </td>
                                            <td class="text-left">
                                                <h6 class="mb-0">
                                                    {{ $organization->name }}
                                                </h6>
                                            </td>
                                            <td class="text-left">
                                                <h6 class="mb-0">
                                                    {{ !empty($organization->demographicData) ? $organization->demographicData->province : '-' }}
                                                </h6>
                                            </td>
                                            <td data-order="{{ $record['amount'] }}">
                                                <h6 class="mb-0">
                                                    {!! $record['currency'] . '&nbsp;&nbsp;&nbsp;' . number_format($record['amount']) !!}
                                                </h6>
                                            </td>
                                            <td class="text-left">
                                                <h6 class="mb-0">
                                                    {{ $record->product->description }}
                                                </h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0">
                                                    @if ($record['term_length_type'] == 'HISA')
                                                        {{ '-' }}
                                                    @else
                                                        {{ $record['term_length'] . ' ' . ucwords(strtolower($record['term_length_type'])) }}
                                                    @endif
                                                </h6>
                                            </td>

                                            <td class="text-left">
                                                <h6 class="mb-0">
                                                    {{ changeDateFromUTCtoLocal($record->closing_date_time, \App\Constants::DATE_TIME_FORMAT_NO_SECONDS) }}
                                                </h6>
                                            </td>
                                            <td class="text-center">
                                                <m-button text="View"
                                                    link="{{ route('bank.place-offer', \App\CustomEncoder::urlValueEncrypt($record['id'])) . '?fromPage=new-requests' }}"
                                                    type="primary" xclass="float-start font-weight-bold my-0 font-s-12">
                                                </m-button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            @if ($user->userCan('bank/in-progress/page-access'))
                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">

                            <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b my_h">IN</span> PROGRESS <span
                                            class="badge bg-blue badge-pill">{{ $in_progress_data['total'] }}</span></td>
                                    <td class="text-right"><a style="color:#03a9f4;text-decoration:underline;"
                                            href="{{ url('in-progress') }}">View all</a></td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-condensed custom-data-tables">
                                <thead>
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Depositor Name</th>
                                        <th>Province</th>
                                        <th>Request Amount</th>
                                        <th>Product</th>
                                        <th>Investment Period</th>
                                        <th>Interest Rate %</th>
                                        <th>Rate Held Until</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($in_progress_data['data'] as $rec)
                                        @php
                                            $depositRequest = $rec->invited->depositRequest;
                                        @endphp
                                        <tr>

                                            <td>
                                                <h6 class="mb-0">
                                                    {{ $depositRequest->reference_no }}
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    {{ $rec->depositor }}
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    {{ !empty($depositRequest->organization->demographicData) ? $depositRequest->organization->demographicData->province : '-' }}
                                                </h6>
                                            </td>

                                            <td data-order="{{ $depositRequest->amount }}">
                                                <h6 class="mb-0">
                                                    {!! $depositRequest->currency . '&nbsp;&nbsp;&nbsp;' . number_format($depositRequest->amount) !!}
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    {{ $depositRequest->product->description }}
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    @if ($depositRequest['term_length_type'] == 'HISA')
                                                        {{ '-' }}
                                                    @else
                                                        {{ $depositRequest['term_length'] . ' ' . ucwords(strtolower($depositRequest['term_length_type'])) }}
                                                    @endif
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    @if ($rec['rate_type'] != 'VARIABLE')
                                                        {{ formatInterest($rec['interest_rate_offer']) }}
                                                    @else
                                                        {!! formatInterest($rec['fixed_rate'], true, $rec['rate_operator'], true) !!}
                                                    @endif
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    {{ changeDateFromUTCtoLocal($rec['rate_held_until'], \App\Constants::DATE_TIME_FORMAT_NO_SECONDS) }}
                                                </h6>
                                            </td>

                                            <td>
                                                @php
                                                    $has_counter_offer = $rec->counterOffers->where('status', 'PENDING')->first();
                                                @endphp
                                                @if ($has_counter_offer && $user->userCan('bank/in-progress/counter-offer'))
                                                    <a href="{{ route('bank.offer-summary', \App\CustomEncoder::urlValueEncrypt($rec['id'])) . '?fromPage=dashboard' }}"
                                                        class="btn btn-success mmy_btn btn-block">View <br><small>Counter
                                                            Offer</small></a>
                                                @else
                                                    <m-button text="View"
                                                        link="{{ route('bank.offer-summary', \App\CustomEncoder::urlValueEncrypt($rec['id'])) . '?fromPage=dashboard' }}"
                                                        type="primary" xclass="float-start font-weight-bold my-0 font-s-12">
                                                    </m-button>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- /main charts -->
@endsection
@section('scripts')
    {{--    <script> --}}
    {{--        $(document).ready(function() { --}}
    {{--            setInterval(function() { --}}
    {{--                $('#res').load('{{ action('DemoController@index') }}'); --}}
    {{--            }, 3000); --}}
    {{--        }); --}}
    {{--    </script> --}}
@endsection

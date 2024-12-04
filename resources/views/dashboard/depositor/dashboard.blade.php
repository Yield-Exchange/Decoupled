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
            @if (
                !$user->userCan('depositor/review-offers/page-access') &&
                    !$user->userCan('depositor/pending-deposits/page-access') &&
                    !$user->userCan('depositor/active-deposits/page-access'))
                <div class="col-sm-12">
                    <div class="alert alert-info">Welcome to Yield Exchange. It appears as if you do not have permissions to
                        operate the site. Please contact your organization administrator or info@yieldexchange.ca for more
                        information</div>
                </div>
            @endif

            <div class="row">
                @if ($user->userCan('depositor/review-offers/page-access'))
                    <div class="col-sm-4">
                        <div style="border-radius:20px;" class="card">
                            <h4 style="margin-left:15px;padding:15px;color:grey"><span
                                    style="border-bottom:3px solid #03a9f4">RE</span>VIEW OFFERS</h4>

                            <div class="row" style="padding:20px">
                                <div class="col-3">
                                    <img src="{{ asset('image/2.png') }}" class="img-responsive" style="max-height:60px" />
                                </div>
                                <div class="col-3">
                                    <h6 style="font-weight:bold;color:grey">No</h6>
                                    <p style="font-size:20px;font-weight:bold">{{ $review_offers['total'] }}</p>
                                </div>
                                <div class="col-{{ !empty($review_offers['total_deposit']['USD']) ? 3 : 6 }}">
                                    <h6 style="font-weight:bold;color:grey">CAD</h6>
                                    <p style="font-size:20px;font-weight:bold">
                                        {{ nice_number($review_offers['total_deposit']['CAD']) }}</p>
                                </div>
                                @if (!empty($review_offers['total_deposit']['USD']))
                                    <div class="col-3">
                                        <h6 style="font-weight:bold;color:grey">USD</h6>
                                        <p style="font-size:20px;font-weight:bold">
                                            {{ nice_number($review_offers['total_deposit']['USD']) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($user->userCan('depositor/pending-deposits/page-access'))
                    <div class="col-sm-4">
                        <div style="border-radius:20px;" class="card">
                            <h4 style="margin-left:15px;padding:15px;color:grey"><span
                                    style="border-bottom:3px solid #03a9f4">PEN</span>DING DEPOSITS</h4>

                            <div class="row" style="padding:20px">

                                <div class="col-3">
                                    <img src="{{ asset('image/1.png') }}" class="img-responsive" style="max-height:60px">
                                </div>

                                <div class="col-3">
                                    <h6 style="font-weight:bold;color:grey">No</h6>
                                    <p style="font-size:20px;font-weight:bold">{{ $pending_contract['total'] }}</p>
                                </div>
                                <div class="col-{{ !empty($pending_contract['total_deposit']['USD']) ? 3 : 6 }}">
                                    <h6 style="font-weight:bold;color:grey">CAD</h6>
                                    <p style="font-size:20px;font-weight:bold">
                                        {{ nice_number($pending_contract['total_deposit']['CAD']) }}
                                    </p>
                                </div>
                                @if (!empty($pending_contract['total_deposit']['USD']))
                                    <div class="col-3">
                                        <h6 style="font-weight:bold;color:grey">USD</h6>
                                        <p style="font-size:20px;font-weight:bold">
                                            {{ nice_number($pending_contract['total_deposit']['USD']) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($user->userCan('depositor/active-deposits/page-access'))
                    <div class="col-sm-4">
                        <div style="border-radius:20px;" class="card">
                            <h4 style="margin-left:15px;padding:15px;color:grey"><span
                                    style="border-bottom:3px solid #03a9f4">ACT</span>IVE DEPOSITS</h4>
                            <div class="row" style="padding:20px">
                                <div class="col-3">
                                    <img src="{{ asset('image/3.png') }}" class="img-responsive" style="max-height:60px">
                                </div>
                                <div class="col-3" style="pedding:0px">
                                    <h6 style="font-weight:bold;color:grey">No</h6>
                                    <p style="font-size:20px;font-weight:bold">{{ $active_contract['total'] }}</p>
                                </div>
                                <div class="col-{{ !empty($active_contract['total_deposit']['USD']) ? 3 : 6 }}">
                                    <h6 style="font-weight:bold;color:grey">CAD</h6>
                                    <p style="font-size:20px;font-weight:bold">
                                        {{ nice_number($active_contract['total_deposit']['CAD']) }}</p>
                                </div>
                                @if (!empty($active_contract['total_deposit']['USD']))
                                    <div class="col-3">
                                        <h6 style="font-weight:bold;color:grey">USD</h6>
                                        <p style="font-size:20px;font-weight:bold">
                                            {{ nice_number($active_contract['total_deposit']['USD']) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if ($user->userCan('depositor/review-offers/page-access'))
                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b">REV</span>IEW OFFERS <span
                                            class="badge bg-blue badge-pill">{{ $review_offers['total'] }}</span></td>
                                    <td class="text-right"><a style="color:#03a9f4;text-decoration:underline;"
                                            href="{{ url('/review-offers') }}">View all</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-condensed custom-data-tables">
                                <thead>
                                    <tr style="width:70%">
                                        <th>Closing date & time</th>
                                        <th>Request id</th>
                                        <th>Product</th>
                                        <th>Amount</th>
                                        <th>Investment period</th>
                                        <th>No of offers</th>
                                        <th>Highest</th>
                                        <th>Lowest</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($review_offers['data'] as $record)
                                        <tr>
                                            <td class="text-left">
                                                <h6 class="mb-0">
                                                    {{ changeDateFromUTCtoLocal($record['closing_date_time'], \App\Constants::DATE_TIME_FORMAT_NO_SECONDS) }}
                                                </h6>
                                            </td>
                                            <td class="text-left">
                                                <h6 class="mb-0">{{ $record['reference_no'] }}</h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0">
                                                    {{ $record['product_name'] }}
                                                </h6>
                                            </td>
                                            <td data-order="{{ $record['amount'] }}">
                                                <h6 class="mb-0">
                                                    {!! $record['currency'] . '&nbsp;&nbsp;&nbsp;' . number_format($record['amount']) !!}
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
                                                    {{ $record['total_offers'] }}
                                                </h6>
                                            </td>
                                            <td class="text-left">
                                                <h6 class="mb-0">

                                                    @if ($record['rate_type'] != 'VARIABLE')
                                                        {{ formatInterest($record['max_interest_rate_offer']) }}
                                                    @else
                                                        {{ formatInterest($record['fixed_rate'], true, $record['rate_operator']) }}
                                                    @endif
                                                </h6>
                                            </td>
                                            <td class="text-left">
                                                <h6 class="mb-0">

                                                    @if ($record['rate_type'] != 'VARIABLE')
                                                        {{ formatInterest($record['min_interest_rate_offer']) }}
                                                    @else
                                                        {{ formatInterest($record['fixed_rate'], true, $record['rate_operator']) }}
                                                    @endif
                                                </h6>
                                            </td>
                                            <td class="text-center">
                                                @if ($user->userCan('depositor/review-offers/view-offers-button'))
                                                    <m-button text="View"
                                                        link="{{ route('request-summary', ['request_id' => App\CustomEncoder::urlValueEncrypt($record['id'])]) }}"
                                                        type="primary"
                                                        xclass="float-start font-weight-bold my-0 font-s-12">
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
            @if ($user->userCan('depositor/pending-deposits/page-access'))
                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">

                            <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b my_h">PEN</span>DING DEPOSITS
                                        <span class="badge bg-blue badge-pill">{{ $pending_contract['total'] }}</span>
                                    </td>
                                    <td class="text-right"><a style="color:#03a9f4;text-decoration:underline;"
                                            href="{{ url('/pending-deposits') }}">View all</a></td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-condensed custom-data-tables">
                                <thead>
                                    <tr>
                                        <th>Date Of Deposit</th>
                                        <th>Rate Held Until</th>
                                        <th>Institution</th>
                                        <th>Deposit Amount</th>
                                        <th>Product</th>
                                        <th>Investment Period</th>
                                        <th>Interest Rate %</th>
                                        @if ($user->userCan('universal/chats/page-access'))
                                            <th>Chat</th>
                                        @endif
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pending_contract['data'] as $rec)
                                        @php
                                            $unread_messages = App\Models\Chat::where('sent_to', auth()->id())
                                                ->where('deposit_id', $rec['id'])
                                                ->where('status', 'NEW')
                                                ->count();
                                        @endphp
                                        <tr>

                                            <td>
                                                <h6 class="mb-0">
                                                    {{ changeDateFromUTCtoLocal($rec['date_of_deposit'], \App\Constants::DATE_FORMAT) }}
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    {{ changeDateFromUTCtoLocal($rec['rate_held_until'], \App\Constants::DATE_TIME_FORMAT_NO_SECONDS) }}
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    {{ $rec['bank_name'] }}
                                                </h6>
                                            </td>

                                            <td class="" data-order="{{ $rec['offered_amount'] }}">
                                                <h6 class="mb-0">
                                                    {{ $rec['currency'] . ' ' . number_format(str_replace(',', '', $rec['offered_amount'])) }}
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    @php
                                                        $product = \App\Models\Product::find($rec['product_id']);
                                                    @endphp
                                                    {{ $product ? $product->description : '' }}
                                                </h6>
                                            </td>

                                            <td>
                                                <h6 class="mb-0">
                                                    @if ($rec['term_length_type'] == 'HISA')
                                                        {{ '-' }}
                                                    @else
                                                        {{ $rec['term_length'] . ' ' . ucwords(strtolower($rec['term_length_type'])) }}
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
                                            @php
                                                $unread_messages = App\Models\Chat::where(
                                                    'sent_to_organization_id',
                                                    $user->organization->id,
                                                )
                                                    ->where('deposit_id', $rec['id'])
                                                    ->where('status', 'NEW')
                                                    ->count();
                                                $badge_notify_chat1 = '';
                                                if ($unread_messages > 0) {
                                                    $badge_notify_chat1 =
                                                        '<span class="badge badge-danger badge-notify-chat-1">' .
                                                        $unread_messages .
                                                        '</span>';
                                                }
                                                $deposit_id_encoded = App\CustomEncoder::urlValueEncrypt($rec['id']);
                                                $offer_id_encoded = App\CustomEncoder::urlValueEncrypt(
                                                    $rec['offer_id'],
                                                );
                                            @endphp
                                            <td style="width: 10px">
                                                @if ($user->userCan('universal/chats/page-access'))
                                                    <m-button text="Chat"
                                                        link="{{ route('deposit.chat.room', $deposit_id_encoded) }}?fromPage=bank-pending-deposits"
                                                        type="secondary"
                                                        xclass="float-start font-weight-bold my-0 font-s-12">
                                                    </m-button>
                                                @endif
                                            </td>

                                            <td style="width: 10px">
                                                @if ($user->userCan('depositor/pending-deposits/view-button'))
                                                    <m-button text="View"
                                                        link="{{ route('depositor.offer-summary', $offer_id_encoded) }}?fromPage=pending-deposits"
                                                        type="primary"
                                                        xclass="float-start font-weight-bold my-0 font-s-12">
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
    {{-- <script>
    --}}
    {{--        $(document).ready(function() { --}}
    {{--            setInterval(function() { --}}
    {{--                $('#res').load('{{ action('DemoController@index') }}'); --}}
    {{--            }, 3000); --}}
    {{--        }); --}}
    {{--    
</script> --}}
@endsection

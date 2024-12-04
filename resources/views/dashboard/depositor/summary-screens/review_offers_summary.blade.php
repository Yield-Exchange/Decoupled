@extends('dashboard.master')
@section('page_title')
    Request Review offers
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-xl-12">

            <review-offers requestid="{{ $depositor_request_id }}"/>
                <!-- /support tickets -->
            </div>
        </div>
@endsection
{{-- @section('scripts')
    <script>
        let request_id = "{{ $depositor_request_id }}";
        $(document).ready(function() {
            // DataTable
            let table = $('.custom-data-tables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    type: 'POST',
                    url: "{{ route('pick.offers-data') }}",
                    data: function(d) {
                        d.req_id = request_id;
                        d.action = "FETCH_REVIEW_OFFERS_SUMMARY";
                        d._token = "{{ csrf_token() }}",
                            d.fromPage =
                            "{{ Request::get('fromPage') ? Request::get('fromPage') : '' }}";
                    }
                },
                columns: [{
                        data: 'bank_name'
                    },
                    // {
                    //     data: 'digital_account_opening'
                    // },
                    {
                        data: 'interest_rate'
                    },
                    {
                        data: 'min_amount'
                    },
                    {
                        data: 'max_amount'
                    },
                    {
                        data: 'award_amount'
                    },
                    {
                        data: 'action'
                    }
                ],
                fnDrawCallback: function() {
                    $('.total_records_pill').html(this.api().page.info().recordsTotal);
                }
            });
        });
    </script>
@endsection --}}

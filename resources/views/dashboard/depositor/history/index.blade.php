@extends('dashboard.master')
@section('page_title')
    History
@stop
@section('page_content')
    <div class="row">

        <div class="col-md-12" id="VueApp">
            <depositor-history></depositor-history>
        </div>
    </div>
    {{-- @include('dashboard.depositor.history.deposits') --}}
    {{-- @include('dashboard.depositor.history.request') --}}

@endsection
{{-- @section('scripts')
    <script>
        $(document).ready(function() {
            // DataTable
            $('.custom-data-tables-1').DataTable({
                processing: true,
                serverSide: true,
                "order": [
                    [0, "desc"]
                ],
                ajax: "{{ route('depositor.deposits-history-data') }}",
                columns: [{
                        data: 'date'
                    },
                    {
                        data: 'reference_no'
                    },
                    {
                        data: 'gic_number'
                    },
                    {
                        data: 'bank_name'
                    },
                    {
                        data: 'offered_amount'
                    },
                    {
                        data: 'product_name'
                    },
                    {
                        data: 'term_length'
                    },
                    {
                        data: 'interest_rate_offer'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action'
                    }
                ],
                fnDrawCallback: function() {
                    $('.total_records_pill_1').html(this.api().page.info().recordsTotal);
                }
            });
            $('.custom-data-tables-2').DataTable({
                processing: true,
                "order": [
                    [0, "desc"]
                ],
                serverSide: true,
                ajax: {
                    url: "{{ route('depositor.request-history-data') }}",
                    data: function(d) {
                        d.fromPage = "{{ Request::get('fromPage') ? Request::get('fromPage') : '' }}";
                    }
                },
                columns: [{
                        data: 'date'
                    },
                    {
                        data: 'reference_no'
                    },
                    {
                        data: 'request_amount'
                    },
                    {
                        data: 'product_name'
                    },
                    {
                        data: 'term_length'
                    },
                    // { data: 'requested_rate' },
                    {
                        data: 'total_offers'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action'
                    }
                ],
                fnDrawCallback: function() {
                    $('.total_records_pill_2').html(this.api().page.info().recordsTotal);
                }
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
@endsection --}}

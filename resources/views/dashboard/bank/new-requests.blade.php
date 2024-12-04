@extends('dashboard.master')
@section('page_title')
    New Requests
@stop
@section('page_content')
    @php
        $user = auth()->user();
    @endphp
    @if ($user->is_non_partnered_fi && $user->account_status != 'ACTIVE')
        {{-- @include('dashboard.bank.non-fi-sections.accept-invitation') --}}
    @endif
    <div class="row" id="VueApp">
        <div class="col-xl-12">

            <new-requests></new-requests>
            <div class="card d-none">
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr class="table-active table-border-double">
                                <td colspan="3" class="my_h"><span class="b_b">New</span> Requests <span
                                        class="badge bg-blue badge-pill total_records_pill"></span></td>
                                <td class="text-right">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive">

                        <table class="table custom-data-tables table-condensed">
                            <thead>
                                <tr>
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

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
            <!-- /support tickets -->
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // $(document).ready(function () {

        // DataTable
        let table = $('.custom-data-tables').DataTable({
            processing: true,
            serverSide: true,
            "order": [
                [0, "DESC"]
            ],
            ajax: "{{ route('new-requests-data') }}",
            columns: [{
                    data: 'reference_no'
                },
                {
                    data: 'depositor_name'
                },
                {
                    data: 'province'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'product'
                },
                {
                    data: 'investment_period'
                },
                {
                    data: 'closing_date_time'
                },
                {
                    data: 'action'
                }
            ],
            fnDrawCallback: function() {
                $('.total_records_pill').html(this.api().page.info().recordsTotal);
            }
        });
        // $('.dataTables_length').addClass('bs-select');
        // });
    </script>
@endsection
@section('styles')
    <style>
        .custom-primary {
            color: #fff;
            background-color: #3656A6 !important;
            border-color: #3d66cd;
            /*font-size: .8em !important;*/
            padding: 5px 23px !important;
        }


        .custom-primary:hover {
            color: #fff;
            background-color: #456cce !important;
            border-color: #366aee;
        }

        .custom-primary.round {
            border-radius: 36px;
        }
    </style>
@endsection

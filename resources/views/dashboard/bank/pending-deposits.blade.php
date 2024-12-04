@extends('dashboard.master')
@section('page_title')
    Pending Deposits
@stop
@section('page_content')
    <div class="row">
        <div class="col-md-12" id="VueApp">
            <bank-pending-deposits></bank-pending-deposits>
        </div>
        {{-- <div class="col-xl-12">
            <div class="card" >
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        <tr class="table-active table-border-double">
                            <td colspan="3" class="my_h"><span class="b_b">Pen</span>ding Deposits <span class="badge bg-blue badge-pill total_records_pill"></span></td>
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
                                <th>Deposit ID</th>
                                <th>Depositor Name</th>
                                <th>Deposit Amount</th>
                                <th>Product</th>
                                <th>Investment Period</th>
                                <th>Interest Rate %</th>
                                <th>Rate Held Until</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
            <!-- /support tickets -->
        </div> --}}
    </div>
@endsection
{{-- @section('scripts')
    <script>
        // $(document).ready(function () {

        // DataTable
        let table = $('.custom-data-tables').DataTable({
            processing: true,
            serverSide: true,
            "order": [
                [0, "DESC"]
            ],
            ajax: "{{route('bank.pending-deposits-data')}}",
            columns: [
                { data: 'reference_no' },
                { data: 'depositor_name' },
                { data: 'amount' },
                { data: 'product' },
                { data: 'investment_period' },
                { data: 'interest_rate' },
                { data: 'rate_held_until' },
                { data: 'action' },
                { data: 'action2' }
            ],
            fnDrawCallback: function () {
                $('.total_records_pill').html(this.api().page.info().recordsTotal);
            }
        });
        // $('.dataTables_length').addClass('bs-select');
        // });
    </script>
@endsection --}}

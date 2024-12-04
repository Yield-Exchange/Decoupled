@extends('dashboard.master')
@section('page_title')
    Active Deposits
@stop
@section('page_content')
    <style>
        .swal-modal .swal-text {
            text-align: center;
        }

        .swal-footer {
            text-align: center;
        }

        .mod-width {
            width: 600px;
        }
    </style>
    <div class="row">

        <div class="col-md-12" id="VueApp">
            <pr-active-deposits></pr-active-deposits>
        </div>

        <div class="col-xl-12 d-none">
            <div class="card">
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr class="table-active table-border-double">
                                <td colspan="3" class="my_h"><span class="b_b">ACT</span>IVE DEPOSITS <span
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
                                <tr role="row">
                                    <th>GIC Number</th>
                                    <th>Institution</th>
                                    <th>Deposit Amount</th>
                                    <th>Product</th>
                                    <th>Investment Period</th>
                                    <th>Interest Rate %</th>
                                    <th>Maturity Date</th>
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
{{-- @section('scripts')
    <script>
        $(document).ready(function() {
            // DataTable
            $('.custom-data-tables').DataTable({
                processing: true,
                serverSide: true,
                "order": [
                    [6, "desc"]
                ],
                ajax: "{{ route('active-deposits-data') }}",
                columns: [{
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
                        data: 'maturity_date'
                    },
                    {
                        data: 'action'
                    }
                ],
                fnDrawCallback: function() {
                    $('.total_records_pill').html(this.api().page.info().recordsTotal);
                }
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
    <script>
        $(document).on("submit", ".withdraw-request-form", function() {

            swal({
                title: "Do you want to mark this deposit as inactive?",
                text: "Your deposit will no longer be available.",
                // icon: "warning",
                buttons: ["No", "Yes"],
                className: "withdraw-modl",
            }).then((response) => {
                if (response) {
                    let $loader1 = $("#cover-spin");
                    makeApiCall($(this).attr('action'), $(this).serializeArray(), function(response) {
                        swal("", response.message, "success").then(() => {
                            window.location.reload();
                        });
                    }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)) {
                            swal("An error occurred, the page will refresh.").then(() => {
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                            return;
                        }

                        swal("", apiCallServerErrorMessage(xhr,
                                "Unable to perform the action to the user, try again later"),
                            "error");
                    });
                }
            });

            return false;
        });
    </script>
@endsection --}}

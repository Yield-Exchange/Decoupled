@extends('dashboard.master')
@section('page_title')
    Pending Deposits
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

        :not(i) {
            font-family: Montserrat !important;
        }
    </style>
    <div class="row bg-white" id="VueApp">

        <pending-deposits></pending-deposits>


        {{-- <div class="col-xl-12 d-none">
            <div class="card">
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr class="table-active table-border-double">
                                <td colspan="3" class="my_h"><span class="b_b">PEN</span>DING DEPOSITS <span
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
                                    <th>Date Of Deposit</th>
                                    <th>Rate Held Until</th>
                                    <th>Institution</th>
                                    <th>Deposit Amount</th>
                                    <th>Product</th>
                                    <th>Investment Period</th>
                                    <th>Interest Rate %</th>
                                    <th></th>
                                    <th>Actions</th>
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
@section('scripts')
    <script>
        // $(document).ready(function () {

        // DataTable
        // let table = $('.custom-data-tables').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     "order": [
        //         [0, "ASC"]
        //     ],
        //     ajax: "{{ route('pending-deposits-datas') }}",
        //     columns: [{
        //             data: 'date_of_deposit'
        //         },
        //         {
        //             data: 'rate_held_until'
        //         },
        //         {
        //             data: 'bank_name'
        //         },
        //         {
        //             data: 'offered_amount'
        //         },
        //         {
        //             data: 'product_name'
        //         },
        //         {
        //             data: 'term_length'
        //         },
        //         {
        //             data: 'interest_rate_offer'
        //         },
        //         {
        //             data: 'chat'
        //         },
        //         {
        //             data: 'action'
        //         }
        //     ],
        //     fnDrawCallback: function() {
        //         $('.total_records_pill').html(this.api().page.info().recordsTotal);
        //     }
        // });
        // $('.dataTables_length').addClass('bs-select');
        // });
    </script>
    <script>
        // function withdraw(e) {
        //     let $this_ = $(e);
        //     let $loader = $("#cover-spin");
        //     let dep_id = $this_.attr('dep-id');

        //     swal({
        //         title: "Do you want to withdraw this Deposit?",
        //         text: "Your Deposit will no longer be available to all Finacial Institutions.",
        //         // icon: "warning",
        //         buttons: ["No", "Yes"],
        //         className: "mod-width",
        //     }).then((response) => {
        //         if (response) {
        //             makeApiCall("{{ url('withdraw-deposit') }}", {
        //                 "deposit_id": dep_id,
        //                 "_token": "{{ csrf_token() }}"
        //             }, function(response) {
        //                 if (response.success) {
        //                     swal("", response.message, "success").then(function() {
        //                         table.draw();
        //                     });
        //                 } else {
        //                     swal("", response.message, "info");
        //                 }
        //             }, $loader, "POST", function(xhr, textStatus, errorThrown) {
        //                 if ([419].includes(xhr.status)) {
        //                     swal("An error occurred, the page will refresh.").then(() => {
        //                         window.onbeforeunload = null;
        //                         window.location.reload();
        //                     });
        //                     return;
        //                 }

        //                 swal("", apiCallServerErrorMessage(xhr,
        //                     "Unable to withdraw the deposit, try again later", "error"));
        //             });
        //         }
        //     });
        // }
    </script>
@endsection

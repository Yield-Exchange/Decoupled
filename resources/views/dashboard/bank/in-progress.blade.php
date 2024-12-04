@extends('dashboard.master')
@section('page_title')
    In Progress
@stop
@section('page_content')
    <style>
        .un-editable{
            opacity: .4;
        }
        
        .withdraw-modl{ width:600px; }
        .swal-modal .swal-text {
            text-align: center;
        }
        .swal-footer { text-align: center; }
    </style>

<div  id="VueApp">
    <in-progress-deposits></in-progress-deposits>

    {{-- <div class="row">
        <div class="col-xl-12">
            <div class="card" >
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        <tr class="table-active table-border-double">
                            <td colspan="3" class="my_h"><span class="b_b">In</span> Progress <span class="badge bg-blue badge-pill total_records_pill"></span></td>
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
                                <th>Interest Rate</th>
                                <th>Rate Held Until</th>
                                <th>Action</th>
                                <th></th>
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
@section('scripts')
    {{-- <script>
        $(document).on("click",".un-editable",function (e) {
            e.preventDefault();
            swal({
                // html: true,
                title: "Your offer is now locked!",
                text: "The time period to edit your offer has passed. \nThe depositor is already reviewing their offers.",
                // icon: "warning",
                // buttons: ["No", "Yes"],
            });
        });
        // $(document).ready(function () {

        // DataTable
        let table = $('.custom-data-tables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('in-progress-data')}}",
            columns: [
                { data: 'reference_no' },
                { data: 'depositor_name' },
                { data: 'province' },
                { data: 'amount' },
                { data: 'product' },
                { data: 'investment_period' },
                { data: 'interest_rate' },
                { data: 'rate_held_until' },
                { data: 'action' },
                { data: 'action2' },
                { data: 'action3' }
            ],
            fnDrawCallback: function () {
                $('.total_records_pill').html(this.api().page.info().recordsTotal);
            }
        });
        // $('.dataTables_length').addClass('bs-select');
        // });
    </script> --}}
<script>
// $(document).on("submit",".withdraw-offer-form",function (){
// swal({
//     title: "Do you want to withdraw this offer?",
//     text: "Your offer will no longer be available to Investors.",
//     // icon: "warning",
//     buttons: ["No", "Yes"],
//     className: "withdraw-modl",
// }).then((response) => {
//     if(response) {
//         let $loader1 = $("#cover-spin");
//         makeApiCall($(this).attr('action'), $(this).serializeArray(), function (response) {
//             swal("", response.message, "success").then(() => {
//                 window.location.reload();
//             });
//         }, $loader1, "POST", function (xhr, textStatus, errorThrown) {
//             if ([419].includes(xhr.status)) {
//                 swal("An error occurred, the page will refresh.").then(() => {
//                     window.onbeforeunload = null;
//                     window.location.reload();
//                 });
//                 return;
//             }
//             swal("", apiCallServerErrorMessage(xhr, "Unable to perform the action to the user, try again later"), "error");
//         });
//     }
// });
// return false;
// });
</script>
@endsection
</div>
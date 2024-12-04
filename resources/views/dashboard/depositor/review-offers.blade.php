@extends('dashboard.master')
@section('page_title')
    Review offers
@stop
@section('page_content')
<style>
    .withdraw-modl{ width:600px; }

    .swal-modal .swal-text {
        text-align: center;
    }
    .swal-footer { text-align: center; }
</style>
    <div class="row">
        <div class="col-xl-12">
            <div class="card" >
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        <tr class="table-active table-border-double">
                            <td colspan="3" class="my_h"><span class="b_b">Re</span>view offers <span class="badge bg-blue badge-pill total_records_pill"></span></td>
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
                                <th>Closing date & time</th>
                                <th>Request ID</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Investment period</th>
                                <th>No of offers</th>
                                <th>Highest</th>
                                <th>Lowest</th>
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
        </div>
    </div>
    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="color:red" class="modal-title"><span class="b_b" >Act</span>ion can not be completed!</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body"><p style="text-align:center;">You have already received offers on this request</p></div>
                <br />
            </div>
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
            ajax: "{{route('review-offers-data')}}",
            "order": [[ 1, "desc" ]],
            columns: [
                { data: 'closing_date_time' },
                { data: 'reference_no' },
                { data: 'product_name' },
                { data: 'amount' },
                { data: 'term_length' },
                { data: 'total_bids' },
                { data: 'highest_rate' },
                { data: 'lowest_rate' },
                { data: 'view_offers' },
                { data: 'action' }
            ],
            fnDrawCallback: function () {
                $('.total_records_pill').html(this.api().page.info().recordsTotal);
            }
        });
        // $('.dataTables_length').addClass('bs-select');
    // });
</script>
<script>
   /* function withdraw(e){
        let $this_ = $(e);
        let $loader = $("#cover-spin");
        let req_id = $this_.attr('req-id');
        swal({
            title: "Do you want to withdraw this request?",
            text: "Your request will no longer be available to all invited Financial Institutions.",
            // icon: "warning",
            buttons: ["No", "Yes"],
            className: "withdraw-modl",

        }).then((response) => {
            if (response) {
                makeApiCall("{{ url('post-request-withdraw') }}", {
                    "req_id":req_id,
                    "_token":"{{ csrf_token() }}"
                }, function(response) {
                    if(response.success){
                        swal("",response.message,"success").then(function () {
                            table.draw();
                        });
                    }else{
                        swal("",response.message,"info");
                    }
                }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                    if ([419].includes(xhr.status)){
                        swal("An error occurred, the page will refresh.").then(()=>{
                            window.onbeforeunload = null;
                            window.location.reload();
                        });
                        return;
                    }

                    swal("",apiCallServerErrorMessage(xhr,"Unable to withdraw the request, try again later","error"));
                });
            }
        });
    }*/

    function editIt(e){
        let $this = $(e);

        swal({
            title: "Edit Request.",
            text: "Would you like to edit this request?",
            // icon: "warning",
            buttons: ["No", "Yes"],
        }).then((response) => {
            if (response) {
                window.location.href=$this.attr('href');
            }
        });

        return false
    }

    function active_requests(){
        // $('#myModal1').modal('show');
        swal({
            title: "You cannot edit your request.",
            text: "You have already received an offer on this request.",
            // icon: "warning",
            // buttons: ["No", "Yes"],
        });
    }

    function active_requests2(){
        // $('#myModal2').modal('show');
    }
</script>
<script>

$(document).on("submit",".withdraw-request-form",function (){

swal({
    title: "Do you want to withdraw this request?",
    text: "Your request will no longer be available to all invited Financial Institutions.",
    // icon: "warning",
    buttons: ["No", "Yes"],
    className: "withdraw-modl",
}).then((response) => {
    if(response) {
        let $loader1 = $("#cover-spin");
        makeApiCall($(this).attr('action'), $(this).serializeArray(), function (response) {
            swal("", response.message, "success").then(() => {
                window.location.reload();
            });
        }, $loader1, "POST", function (xhr, textStatus, errorThrown) {
            if ([419].includes(xhr.status)) {
                swal("An error occurred, the page will refresh.").then(() => {
                    window.onbeforeunload = null;
                    window.location.reload();
                });
                return;
            }

            swal("", apiCallServerErrorMessage(xhr, "Unable to perform the action to the user, try again later"), "error");
        });
    }
});

return false;
});
</script>
@endsection
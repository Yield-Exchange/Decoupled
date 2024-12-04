@extends('dashboard.master')
@section('page_title')
    Notifications
@stop
@section('page_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card" >
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        <tr class="table-active table-border-double">
                            <td colspan="3" class="my_h"><span class="b_b">Not</span>ifications <span class="badge bg-blue badge-pill total_records_pill"></span></td>
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
                                <th>Institution</th>
                                <th>Notification Details</th>
                                <th>Sent Date</th>
{{--                                <th>Status</th>--}}
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
        let table = $('.custom-data-tables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('user.notifications-data')}}",
            columns: [
                { data: 'institution_name' },
                { data: 'details' },
                { data: 'send_date' },
                // { data: 'status' },
                { data: 'action' }
            ],
            fnDrawCallback: function () {
                $('.total_records_pill').html(this.api().page.info().recordsTotal);
            }
        });

        function deleteIt(e){
            let $this_ = $(e);
            let $loader = $("#cover-spin");
            let notification_id = $this_.attr('notification-id');
          
                    makeApiCall("{{ route('user.notifications-delete') }}", {
                        "notification_id":notification_id,
                        "_token":"{{ csrf_token() }}"
                    }, function(response) {
                        if(response.success){
                            table.draw();
                        }else{
                            swal(response.message);
                        }
                    }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)){
                            swal("An error occurred, the page will refresh.").then(()=>{
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                            return;
                        }

                        swal("",apiCallServerErrorMessage(xhr,"Unable to delete notification, try again later","error"));
                    });
               
            return false;
        }

    </script>
@endsection
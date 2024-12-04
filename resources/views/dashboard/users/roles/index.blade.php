@extends('dashboard.master')
@section('page_title')
    User Roles
@stop
@section('page_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card" >
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        <tr class="table-active table-border-double">
                            <td colspan="3" class="my_h"><span class="b_b">R</span>oles <span class="badge bg-blue badge-pill total_records_pill"></span></td>
                            <td class="text-right">
                                <button type="button" class="btn custom-primary round mmy_btn pull-right" data-toggle="modal" data-target="#create"><i class="fa fa-plus"></i> Add Role</button>
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
                                <th>Role Name</th>
                                <th>Assigned Users</th>
                                <th>Assigned Permissions</th>
                                <th>Created By</th>
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
    <div id="create" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Add New Role</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" autocomplete="off" id="CreateRole">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 well">
                                <h5>Role Name</h5>
                                <div class="form-group">
                                    <input type="text" id="role_name" name="role_name" placeholder="Enter Role Name"  class="form-control col-md-12" />
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label style="font-weight: normal"> For System Admin ? <input type="checkbox" name="for_system_admin" /></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="button" class="btn custom-secondary round" data-dismiss="modal" value="Cancel">
                                    <input type="submit" class="btn custom-primary round mmy_btn CreateRoleSubmitBtn" value="Submit" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // DataTable
        let table = $('.custom-data-tables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.roles.data')}}",
            columns: [
                { data: 'role_name' },
                { data: 'assigned_users' },
                { data: 'role_permissions' },
                { data: 'creator' },
                { data: 'action' }
            ],
            fnDrawCallback: function () {
                $('.total_records_pill').html(this.api().page.info().recordsTotal);
            }
        });
    </script>
    <script>
        $(document).on("submit","#CreateRole",function () {
            let $this_=$(this);
            let $loader = $("#cover-spin");
            $this_.find(".CreateRoleSubmitBtn").attr('disabled', true).html('Please wait');
            makeApiCall("{{ route('admin.roles.create') }}", $this_.serialize(), function(response) {
                if(response.success){
                    $(".modal").modal('hide');
                    swal("",response.message,"success").then(function () {
                        // table.draw();
                        // $this_.trigger('reset');
                        $loader.show();
                        window.location.reload();
                    });
                }else{
                    swal("",response.message,"warning");
                }
                $this_.find(".CreateRoleSubmitBtn").attr('disabled', false).html('Submit');
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                swal("",apiCallServerErrorMessage(xhr,"Unable to create role, try again later","warning"));
                $this_.find(".CreateRoleSubmitBtn").attr('disabled', false).html('Submit');
            });

            return false;
        });

        $(document).on("click",".delete-role-confirm",function (){

            swal({
                title: "",
                text: "Are you sure to delete the role?",
                // icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    let $loader1 = $("#cover-spin");
                    makeApiCall($(this).attr('href'), {
                        '_token':"{{ csrf_token() }}"
                    }, function(response) {
                        swal("",response.message,"success").then(()=>{
                            window.location.reload();
                        });
                    }, $loader1,"POST", function (xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)){
                            swal("An error occurred, the page will refresh.").then(()=>{
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                            return;
                        }

                        swal("",apiCallServerErrorMessage(xhr,"Unable to perform the action to the role, try again later"),"error");
                    });
                }
            });

            return false;
        });

    </script>
@endsection
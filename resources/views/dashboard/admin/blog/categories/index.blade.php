@extends('dashboard.master')
@section('page_title')
    Blogs Categories
@stop
@section('page_content')
    @php
        $user = auth()->user();
    @endphp
    <div class="row">
        <div class="col-xl-12">
            <div class="card" >
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        <tr class="table-active table-border-double">
                            <td colspan="3">
                                <span class="b_b">BLOGS </span>CATEGORIES<span class="badge bg-blue badge-pill total_records_pill"></span>
                            </td>
                            <td class="text-right">
                                @if($user->userCan('admin/blogs-categories/create'))
                                <a data-toggle="modal" data-target="#create_blog_category" href="javascript:void()" class="btn btn-primary mmy_btn pull-right"><i class="fa fa-plus"></i> Create Category</a>
                                @endif
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
                                <th>#</th>
                                <th>Category</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Updated At</th>
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

            <div id="create_blog_category" class="modal fade" role="dialog">
                <div class="modal-dialog  modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><b>Add New Blog Category</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" autocomplete="off" id="CreateCategory">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 well">
                                        <h5>Category</h5>
                                        <div class="form-group">
                                            <input type="text" id="name" name="name" placeholder="Enter Category" maxlength="26" minlength="1"  class="form-control col-md-12" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 well">
                                    <div class="form-group">
                                        <br>
                                        <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancel">
                                        <input type="submit" class="btn btn-primary mmy_btn CreateCategorySubmitBtn" value="Submit" />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(document).on("click",".admin-delete-blog-category",function (){

            swal({
                title: "",
                text: "Are you sure to delete this blog category?",
                // icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if(response) {
                   window.location.href=$(this).attr('action');
                }
            });

            return false;
        });

        $(document).ready(function () {
            // DataTable
            $('.custom-data-tables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('admin.blogs-category.data')}}",
                    method:"POST",
                    data: function (d) {
                        d._token= "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: 'sno' },
                    { data: 'name' },
                    { data: 'created_by' },
                    { data: 'created_at' },
                    { data: 'updated_at' },
                    { data: 'action' }
                ],
                fnDrawCallback: function () {
                    $('.total_records_pill').html(this.api().page.info().recordsTotal);
                }
            });
        });
    </script>
       <script>
        $(document).on("submit","#CreateCategory",function () {
            let $this_=$(this);
            let $loader = $("#cover-spin");
            $this_.find(".CreateCategorySubmitBtn").attr('disabled', true).html('Please wait');
            makeApiCall("{{ route('blogs-category.store') }}", $this_.serialize(), function(response) {
                if(response.success){
                    $(".modal").modal('hide');
                    swal("",response.message,"success").then(function () {
                        // $this_.trigger('reset');
                        // table.draw();
                        $loader.show();
                        window.location.reload();
                    });
                }else{
                    swal("",response.message,"info");
                }
                $this_.find(".CreateCategorySubmitBtn").attr('disabled', false).html('Submit');
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                swal("",apiCallServerErrorMessage(xhr,"Unable to create/update blog category, try again later","error"));
                $this_.find(".CreateCategorySubmitBtn").attr('disabled', false).html('Submit');
            });

            return false;
        });

    </script>
@endsection
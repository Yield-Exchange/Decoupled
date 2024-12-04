@extends('dashboard.master')
@section('page_title')
    Industries
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
                                <span class="b_b">In</span>dustries<span class="badge bg-blue badge-pill total_records_pill"></span>
                            <td class="text-right">
                                @if($user->userCan('admin/industries/create'))
                                    <a data-toggle="modal" data-target="#create_industry" href="javascript:void()" class="btn btn-primary mmy_btn pull-right"><i class="fa fa-plus"></i> Create Industry</a>
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
                                <th>Name</th>
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

            <div id="create_industry" class="modal fade" role="dialog">
                <div class="modal-dialog  modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><b>Add New Industry</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" autocomplete="off" id="CreateIndustry">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 well">
                                        <h5>Industry</h5>
                                        <div class="form-group">
                                            <input type="text" id="industry" name="industry" placeholder="Enter Industry" maxlength="26" minlength="1"  class="form-control col-md-12" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 well">
                                        <div class="form-group">
                                            <br>
                                            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancel">
                                            <input type="submit" class="btn btn-primary mmy_btn CreateIndustrySubmitBtn" value="Submit" />
                                        </div>
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
@endsection
@section('scripts')
    <script>
        $(document).on("click",".admin-delete-industry",function (){

            swal({
                title: "",
                text: "Are you sure to delete this Industry?",
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
                    url: "{{route('admin.industries.data')}}",
                    method:"POST",
                    data: function (d) {
                        d._token= "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: 'sno' },
                    { data: 'name' },
                    { data: 'action' }
                ],
                fnDrawCallback: function () {
                    $('.total_records_pill').html(this.api().page.info().recordsTotal);
                }
            });
        });
    </script>
    <script>
        $(document).on("submit","#CreateIndustry",function () {
            let $this_=$(this);
            let $loader = $("#cover-spin");
            $this_.find(".CreateIndustrySubmitBtn").attr('disabled', true).html('Please wait');
            makeApiCall("{{ route('industries.store') }}", $this_.serialize(), function(response) {
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
                $this_.find(".CreateIndustrySubmitBtn").attr('disabled', false).html('Submit');
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                swal("",apiCallServerErrorMessage(xhr,"Unable to create industry, try again later","error"));
                $this_.find(".CreateCategorySubmitBtn").attr('disabled', false).html('Submit');
            });

            return false;
        });

    </script>
@endsection
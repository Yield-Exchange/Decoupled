@extends('dashboard.master')
@section('page_title')
    Blogs
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
                                <span class="b_b">B</span>LOGS<span class="badge bg-blue badge-pill total_records_pill"></span>
                                @if($user->userCan('admin/blogs-categories/page-access'))
                                <a href="{{ route('blogs-category.index') }}" class="btn btn-primary mmy_btn"><i class="fa fa-list"></i> Categories</a>
                                @endif
                                @if($user->userCan('admin/blogs-tags/page-access'))
                                <a href="{{ route('blogs-tag.index') }}" class="btn btn-primary mmy_btn"><i class="fa fa-list"></i> Tags</a>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($user->userCan('admin/blogs/create'))
                                <a href="{{route('blogs.create')}}" class="btn btn-primary mmy_btn pull-right"><i class="fa fa-plus"></i> Create Blog</a>
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
                                <th>Image</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Status</th>
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
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).on("click",".admin-delete-blog",function (){

            swal({
                title: "",
                text: "Are you sure to delete this blog?",
                // icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if(response) {
                   window.location.href=$(this).attr('action');
                }
            });

            return false;
        });

        $(document).on("click",".admin-approve-blog",function (){

            swal({
                title: "",
                text: "Are you sure to approve this blog?",
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
                    url: "{{route('admin.blogs.data')}}",
                    method:"POST",
                    data: function (d) {
                        d._token= "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: 'sno' },
                    { data: 'image' },
                    { data: 'title' },
                    { data: 'category'},
                    { data: 'author' },
                    { data: 'status' },
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
@endsection
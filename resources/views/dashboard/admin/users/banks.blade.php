@extends('dashboard.master')
@section('page_title')
    Banks
@stop
@section('page_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card" >
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        <tr class="table-active table-border-double">
                            <td colspan="3" class="my_h"><span class="b_b">B</span>anks<span class="badge bg-blue badge-pill total_records_pill"></span></td>
                            <td class="text-right">
                                <a href="{{ url('/sign-up') }}" class="btn btn-primary mmy_btn pull-right"><i class="fa fa-plus"></i> Create Bank</a>
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
                                <th>Institution</th>
                                <th>City</th>
                                <th>Admin</th>
                                <th>Users Limit</th>
                                <th>Telephone</th>
                                <th>Industry</th>
                                <th>Routing Agent</th>
                                <th>File Type</th>
                                <th>Delivery Method</th>
                                <th>Test Account</th>
                                <th>Status</th>
                               
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
    $(document).ready(function () {
        // DataTable
        let ipAddress = localStorage.getItem("ip_address");
        $('.custom-data-tables').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.users.data', 'banks') }}",
                method: "POST",
                data: function (d) {
                    d._token = "{{ csrf_token() }}";
                },
            },
            columns: [
                { data: 'sno' },
                { data: 'name' },
                { data: 'city' },
                { data: 'admin' },
                { data: 'users_limit' },
                { data: 'tel' },
                { data: 'industry' },
                {data: 'aws_routing_agent'},
                { data: 'aws_file_type' },
                { data: 'aws_delivery_method' },
                { data: 'is_test' },
                { data: 'status' },
                { data: 'action' }
            ],
            fnDrawCallback: function () {
                $('.total_records_pill').html(this.api().page.info().recordsTotal);
            }
        });
        $('.dataTables_length').addClass('bs-select');
    });
</script>

@endsection
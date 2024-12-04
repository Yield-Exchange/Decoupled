@extends('dashboard.master')
@section('page_title')
    System Activity Logs
@stop
@section('page_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card" >
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        <tr class="table-active table-border-double">
                            <td colspan="3" class="my_h"><span class="b_b">System</span> Activity Logs<span class="badge bg-blue badge-pill total_records_pill"></span></td>
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
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Organization</th>
                                <th>Location</th>
                                <th>From Location</th>
                                <th>Query String</th>
                                <th>Event Date</th>
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

    <div class="row">
        <div class="col-xl-12">
            <div class="card" >
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        <tr class="table-active table-border-double">
                            <td colspan="3" class="my_h"><span class="b_b">Login</span> Activity Logs<span class="badge bg-blue badge-pill total_records_pill1"></span></td>
                            <td class="text-right">

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-sm-12">
                    <div class="table-responsive">

                        <table class="table custom-data-tables1 table-condensed">
                            <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Organization</th>
                                <th>Activity Type</th>
                                <th>User Agent</th>
                                <th>Event Date</th>
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
            $('.custom-data-tables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('admin.system.activity.logs.data')}}",
                    method:"POST",
                    data: function (d) {
                        d._token= "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: 'sno' },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'role' },
                    { data: 'organizations_name' },
                    { data: 'location' },
                    { data: 'from_location' },
                    { data: 'query_string' },
                    { data: 'event_date' }
                ],
                fnDrawCallback: function () {
                    $('.total_records_pill').html(this.api().page.info().recordsTotal);
                }
            });
            // DataTable
            $('.custom-data-tables1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('admin.login.activity.logs.data')}}",
                    method:"POST",
                    data: function (d) {
                        d._token= "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: 'sno' },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'role' },
                    { data: 'organizations_name' },
                    { data: 'activity_type' },
                    { data: 'user_agent' },
                    { data: 'event_date' }
                ],
                fnDrawCallback: function () {
                    $('.total_records_pill1').html(this.api().page.info().recordsTotal);
                }
            });
        });
    </script>
@endsection
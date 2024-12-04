@extends('dashboard.master')
@section('page_title')
    Invited Institutions
@stop
@section('page_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr class="table-active table-border-double">
                                <td colspan="3" class="my_h"><span class="b_b">INVITED</span> INSTITUTION<span
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
                                    <th>Institution</th>
                                    <th>Province</th>
                                    <th>Short term DBRS rating</th>
                                    <th>Deposit Insurance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                    <div class="col-md-12 text-center">
                        <br />
                        <br />
                        <a href="{{ url(Request::get('fromPage') ? Request::get('fromPage') : 'review-offers') }}"
                            class="btn custom-secondary round btn-block col-md-3">Back</a>
                        <br />
                        <br />
                    </div>
                </div>

            </div>
            <!-- /support tickets -->
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let token = "{{ csrf_token() }}";
        let req_id = "{{ $request_id }}";
        $(document).ready(function() {
            $('.custom-data-tables').DataTable({
                "lengthMenu": [
                    ["All"],
                    ["All"]
                ],
                // Processing indicator
                "processing": true,
                // DataTables server-side processing mode
                "serverSide": true,
                // Initial no order.
                "order": [
                    [0, "desc"]
                ],
                "ordering": false,
                // Load data from an Ajax source
                "ajax": {
                    "url": "{{ route('pick.offers-data') }}",
                    "type": "POST",
                    "data": {
                        'action': 'FETCH_INVITED_INSTITUTIONS',
                        'req_id': req_id,
                        '_token': token
                    },
                    dataFilter: function(inData) {
                        // what is being sent back from the server (if no error)
                        // console.log(inData);
                        return inData;
                    },
                    error: function(err, status) {
                        // what error is seen(it could be either server side or client side.
                        // console.log(err);
                    },
                },
                //Set column definition initialisation properties
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],
                "scrollX": true,
                "pageLength": 50,
            });
        });
    </script>
@endsection

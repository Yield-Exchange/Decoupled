@extends('dashboard.master')
@section('page_title')
    Reports
@stop
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/datetimepicker/jquery.datetimepicker.css') }}" />
@stop
@section('page_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="col-sm-12">
                    <table class="table">
                        <tbody>
                            <tr class="table-active">
                                <td colspan="2" class="my_h"><span class="b_b">REP</span>ORT <span
                                        class="badge bg-blue badge-pill total_records_pill"></span></td>
                                <td>
                                    <form method="post" action="#" autocomplete="off" class="row">
                                        <label class="col-sm-2 text-right"><br />Date&nbsp;From:</label>
                                        <input type="text" class="form-control col-sm-3 date_picker" id="date_from"
                                            name="date_from" value="" /><br />
                                        <label class="col-sm-2 text-right"><br />Date&nbsp;To:</label>
                                        <input type="text" class="form-control col-sm-3 date_picker1" id="date_to"
                                            name="date_to" value="" />
                                        <button type="button"
                                            class="btn custom-primary round col-sm-1 inline-block btn-sm filterBtn"
                                            name="filter" style="margin-left:2%; font-size: 13px"> <i
                                                class="fa fa-filter"></i> Filter</button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <button type="submit" class="btn bg-grey-300 exportBtn round" name="filter"
                                        style="margin-left:2%;">
                                        <span class="badge bg-blue badge-pill"></span> <i class="fa fa-download"></i> Export
                                        Pdf</button>
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
                                    <th>Contract Number</th>
                                    <th>GIC Number</th>
                                    <th>Customer Name</th>
                                    <th>Province</th>
                                    <th>Product</th>
                                    <th>Term</th>
                                    <th>Lockout/Notice Period</th>
                                    <th>Amount</th>
                                    <th>Interest Rate</th>
                                    <th>GIC Start Date</th>
                                    <th>Maturity Date</th>
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
        $(document).ready(function() {
            // DataTable
            let table = $('.custom-data-tables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('bank.reports-data') }}",
                    data: function(d) {
                        d.date_from = $("#date_from").val();
                        d.date_to = $("#date_to").val();
                    }
                },
                columns: [{
                        data: 'reference_no'
                    },
                    {
                        data: 'gic_number'
                    },
                    {
                        data: 'depositor_name'
                    },
                    {
                        data: 'province'
                    },
                    {
                        data: 'product_name'
                    },
                    {
                        data: 'term_length'
                    },
                    {
                        data: 'lockout_period_days'
                    },
                    {
                        data: 'request_amount'
                    },
                    {
                        data: 'interest_rate_offer'
                    },
                    {
                        data: 'gic_start_date'
                    },
                    {
                        data: 'maturity_date'
                    }
                ],
                fnDrawCallback: function() {
                    let recordsTotal = this.api().page.info().recordsTotal;
                    $('.total_records_pill').html(recordsTotal);
                    if (recordsTotal > 0) {
                        $(".exportBtn").attr("no-data", 1);
                    } else {
                        $(".exportBtn").removeAttr("no-data");
                    }
                }
            });
            $('.dataTables_length').addClass('bs-select');

            $(document).on('click', '.filterBtn', function(e) {
                table.draw();
            });

            $(document).on('click', '.exportBtn', function(e) {
                e.preventDefault();
                if (!$(this).attr("no-data")) {
                    swal("", "No data is available for export!", "info");
                    return;
                }
                window.location.href = "{{ route('bank.reports-data') }}?date_from=" + $("#date_from")
                .val() + "&date_to=" + $("#date_to").val() + "&export=true";
            });
        });
    </script>
    <script src="{{ asset('assets/dashboard/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/moment-timezone.js') }}"></script>
    <script type="text/javascript">
        let format = 'YYYY/MM/DD HH:mm:ss ZZ';
        let timeZone = @php echo json_encode(formattedTimezone(auth()->user()->timezone)) @endphp;
        let todayDateWithUserTimezone = moment(moment().toISOString(), format).tz(timeZone);
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            let date_picker = $('.date_picker');
            date_picker.datetimepicker('destroy');
            date_picker.datetimepicker({
                maxDate: moment(),
                timepicker: false,
                format: 'Y-m-d',
                onChangeDateTime: function(dp, $input) {
                    var fromdate = $input.val();
                    $('.date_picker1').datetimepicker({
                        defaultDate: fromdate,
                        minDate: fromdate,
                        maxDate: moment(),
                        timepicker: false,
                        format: 'Y-m-d',
                    });
                    if (fromdate > $('.date_picker1').val()) {
                        $('.date_picker1').val(fromdate);
                    }
                }
            });

            $('.date_picker1').datetimepicker({
                defaultDate: moment().format("Y-MM-DD"),
                timepicker: false,
                format: 'Y-m-d',
            });

            $('.date_picker').val() != '' ? $('.date_picker').val() : $('.date_picker').val(moment().add(-90,
                "days").format("Y-MM-DD"));
            $('.date_picker1').val() != '' ? $('.date_picker1').val() : $('.date_picker1').val(moment().format(
                "Y-MM-DD"));
        });
    </script>
@endsection

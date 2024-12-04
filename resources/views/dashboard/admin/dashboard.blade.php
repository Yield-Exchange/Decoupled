@extends('dashboard.master')
@section('page_title')
    Dashboard
@stop
@section('styles')
    <style>
        .c-white{
            color:white!important;
        }
    </style>
@endsection
@section('page_content')
    @php
        $user = auth()->user();
    @endphp

    @if(!$user->userCan('admin/dashboard/page-access'))
        <div class="col-sm-12">
            <div class="alert alert-info">Welcome to Yield Exchange. It appears as if you do not have permissions to operate the site. Please contact your organization administrator or info@yieldexchange.ca for more information</div>
        </div>
    @else
    <!-- Traffic sources -->
    <div class="card">
        <div class="card-header header-elements-inline">

            <div class="col-lg-4">

                <!-- Members online -->
                <div class="card bg-teal-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0 c-white">{{ $data['total_banks'] }}</h3>
                        </div>

                        <div>
                            Total Banks
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div id="members-online"></div>
                    </div>
                </div>
                <!-- /members online -->

            </div>

            <div class="col-lg-4">

                <!-- Current server load -->
                <div class="card bg-pink-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0 c-white">{{ $data['total_depositors'] }}</h3>
                        </div>

                        <div>
                            Total Depositors
                        </div>
                    </div>
                </div>
                <!-- /current server load -->

            </div>

            <div class="col-lg-4">

                <!-- Today's revenue -->
                <div class="card bg-blue-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0 c-white">{{ $data['new_requests'] }}</h3>
                        </div>
                        <div>
                            New Requests
                        </div>
                    </div>
                </div>
                <!-- /today's revenue -->

            </div>

            <!-- /quick stats boxes -->

        </div>
    </div>
    <!-- /traffic sources -->

    <!-- Support tickets -->
    <div class="card">
        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
    </div>
    <!-- /support tickets -->
    <div class="card">
        <div id="chartContainer1" style="height: 300px; width: 100%;"></div>
    </div>

    <div class="card">
        <div id="chartContainer2" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="card">
        <div id="chartContainer3" style="height: 300px; width: 100%;"></div>
    </div>
    <div class="card">
        <div id="chartContainer4" style="height: 300px; width: 100%;"></div>
    </div>
    @endif
@endsection
@section('scripts')
    @if($user->userCan('admin/dashboard/page-access'))
    <script src="{{ asset('assets/js/jquery.canvasjs.min.js') }}"></script>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Month Wise Offers"
                },
                axisY: {
                    title: "",
                    suffix: "",
                    includeZero: false
                },
                axisX: {
                    title: "Months"
                },
                data: [{
                    type: "column",
                    dataPoints: [
                        { label: "Jan", y: {{ $bids_month[1] }} },
                        { label: "Feb", y: {{ $bids_month[2] }} },
                        { label: "Mar", y: {{ $bids_month[3] }} },
                        { label: "Apr", y: {{ $bids_month[4] }} },
                        { label: "May", y: {{ $bids_month[5] }} },
                        { label: "June", y: {{ $bids_month[6] }} },
                        { label: "July", y: {{ $bids_month[7] }} },
                        { label: "Aug", y: {{ $bids_month[8] }} },
                        { label: "Sep", y: {{ $bids_month[9] }} },
                        { label: "Oct", y: {{ $bids_month[10] }} },
                        { label: "Nov", y: {{ $bids_month[11] }} },
                        { label: "Dec", y: {{$bids_month[12]}} }

                    ]
                }]
            });
            chart.render();
            var chart = new CanvasJS.Chart("chartContainer1", {
                animationEnabled: true,
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Month Wise Banks"
                },
                axisY: {
                    title: "",
                    suffix: "",
                    includeZero: false
                },
                axisX: {
                    title: "Months"
                },
                data: [{
                    type: "column",
                    dataPoints: [
                        { label: "Jan", y: {{ $banks_month[1] }} },
                        { label: "Feb", y: {{ $banks_month[2] }} },
                        { label: "Mar", y: {{ $banks_month[3] }} },
                        { label: "Apr", y: {{ $banks_month[4] }} },
                        { label: "May", y: {{ $banks_month[5] }} },
                        { label: "June", y: {{ $banks_month[6] }} },
                        { label: "July", y: {{ $banks_month[7] }} },
                        { label: "Aug", y: {{ $banks_month[8] }} },
                        { label: "Sep", y: {{ $banks_month[9] }} },
                        { label: "Oct", y: {{ $banks_month[10] }} },
                        { label: "Nov", y: {{ $banks_month[11] }} },
                        { label: "Dec", y: {{$banks_month[12]}} }
                    ]
                }]
            });
            chart.render();
            var chart = new CanvasJS.Chart("chartContainer2", {
                animationEnabled: true,
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Month Wise Depositers"
                },
                axisY: {
                    title: "",
                    suffix: "",
                    includeZero: false
                },
                axisX: {
                    title: "Months"
                },
                data: [{
                    type: "column",
                    dataPoints: [
                        { label: "Jan", y: {{ $dep_month[1] }} },
                        { label: "Feb", y: {{ $dep_month[2] }} },
                        { label: "Mar", y: {{ $dep_month[3] }} },
                        { label: "Apr", y: {{ $dep_month[4] }} },
                        { label: "May", y: {{ $dep_month[5] }} },
                        { label: "June", y: {{ $dep_month[6] }} },
                        { label: "July", y: {{ $dep_month[7] }} },
                        { label: "Aug", y: {{ $dep_month[8] }} },
                        { label: "Sep", y: {{ $dep_month[9] }} },
                        { label: "Oct", y: {{ $dep_month[10] }} },
                        { label: "Nov", y: {{ $dep_month[11] }} },
                        { label: "Dec", y: {{$dep_month[12]}} }
                    ]
                }]
            });
            chart.render();
            var chart = new CanvasJS.Chart("chartContainer3", {
                animationEnabled: true,
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Month Wise Requests"
                },
                axisY: {
                    title: "",
                    suffix: "",
                    includeZero: false
                },
                axisX: {
                    title: "Months"
                },
                data: [{
                    type: "column",
                    dataPoints: [
                        { label: "Jan", y: {{ $request_month[1] }} },
                        { label: "Feb", y: {{ $request_month[2] }} },
                        { label: "Mar", y: {{ $request_month[3] }} },
                        { label: "Apr", y: {{ $request_month[4] }} },
                        { label: "May", y: {{ $request_month[5] }} },
                        { label: "June", y: {{ $request_month[6] }} },
                        { label: "July", y: {{ $request_month[7] }} },
                        { label: "Aug", y: {{ $request_month[8] }} },
                        { label: "Sep", y: {{ $request_month[9] }} },
                        { label: "Oct", y: {{ $request_month[10] }} },
                        { label: "Nov", y: {{ $request_month[11] }} },
                        { label: "Dec", y: {{$request_month[12]}} }

                    ]
                }]
            });
            chart.render();
            var chart = new CanvasJS.Chart("chartContainer4", {
                animationEnabled: true,
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Month Wise Deposits"
                },
                axisY: {
                    title: "",
                    suffix: "",
                    includeZero: false
                },
                axisX: {
                    title: "Months"
                },
                data: [{
                    type: "column",
                    dataPoints: [
                        { label: "Jan", y: {{ $contract_month[1] }} },
                        { label: "Feb", y: {{ $contract_month[2] }} },
                        { label: "Mar", y: {{ $contract_month[3] }} },
                        { label: "Apr", y: {{ $contract_month[4] }} },
                        { label: "May", y: {{ $contract_month[5] }} },
                        { label: "June", y: {{ $contract_month[6] }} },
                        { label: "July", y: {{ $contract_month[7] }} },
                        { label: "Aug", y: {{ $contract_month[8] }} },
                        { label: "Sep", y: {{ $contract_month[9] }} },
                        { label: "Oct", y: {{ $contract_month[10] }} },
                        { label: "Nov", y: {{ $contract_month[11] }} },
                        { label: "Dec", y: {{$contract_month[12]}} }

                    ]
                }]
            });
            chart.render();
        }
    </script>
    @endif
@endsection
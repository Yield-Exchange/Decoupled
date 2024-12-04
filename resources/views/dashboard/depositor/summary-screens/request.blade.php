@extends('dashboard.master')
@section('page_title')
    Offer Summary
@stop
@section('page_content')
    <!-- Main charts -->
    <div class="row">
        <div class="col-xl-12">

            <!-- Support tickets -->
            <div class="card" style="padding:20px;padding-top:10px">
                <br />
                @include('dashboard.depositor.summary-screens.sections.request_summary_section')
            </div>
            <div style="padding-bottom:30px;padding-top:25px;padding-left:10px;" class="row">
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <a href="{{ url(Request::get('fromPage') ? Request::get('fromPage') : 'review-offers') }}"
                        class="btn btn-block custom-secondary round" style="border:1px solid gainsboro">Back</a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3"></div>
                <div class="col-lg-2 col-md-2 col-sm-3"></div>
                <div class="col-lg-3 col-md-3 col-sm-3"></div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

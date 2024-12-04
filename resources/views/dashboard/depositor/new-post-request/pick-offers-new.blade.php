@extends('dashboard.master')
@section('page_title')
Review Offers
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>
                <request-offers></request-offers>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
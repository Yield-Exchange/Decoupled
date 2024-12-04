@extends('dashboard.master')
@section('page_title')
    My Offers
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <purchase-gic fi_admin="{{ $fi_admin }}" user="{{ json_encode(auth()->user()) }}"
                offer="{{ json_encode($offer) }}" organizationdetails="{{ json_encode($organizationdetails) }}" />
        </div>
    </div>
@endsection
@section('scripts')
@endsection

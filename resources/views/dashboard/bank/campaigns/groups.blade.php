@extends('dashboard.master')
@section('page_title')
    Groups
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>
               <groups-campaigns :depositors="{{ $depositors }}" />
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

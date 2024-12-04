@extends('dashboard.master')
@section('page_title')
    Campaign Edit
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>
                <campaign-edit products="{{ json_encode($products) }}" depositors="{{  json_encode($depositors) }}" timezone="{{ $timezone }}" camp_data="{{  json_encode($campaign) }}" />
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

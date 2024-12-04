@extends('dashboard.master')
@section('page_title')
Review Offers
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>                
               <request-summary :deposit_request="{{json_encode($deposit_request)}}" :fiorganizations="{{json_encode($fiorganizations)}}" :encoded_deposit_request_id="{{json_encode($encoded_deposit_request_id)}}"></reques-summary>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
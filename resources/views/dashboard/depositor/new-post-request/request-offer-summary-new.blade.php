@extends('dashboard.master')
@section('page_title')
Offer Summary
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>                       
               <request-offer-summary :deposit_request="{{json_encode($offer)}}"></request-offer-summary>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
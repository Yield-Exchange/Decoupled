@extends('dashboard.master')
@section('page_title')
    Campaign Product Summary
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>
                <campaign-product-summary product="{{json_encode($product)}}" log="{{$userLoggedIn}}" />
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

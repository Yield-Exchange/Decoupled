@extends('dashboard.master')
@section('page_title')
    Product Summary
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>
                <product-summary product="{{ $product }}" />
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

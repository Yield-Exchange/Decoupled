@extends('dashboard.master')
@section('page_title')
Review Offers
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            {{-- @php
            echo json_encode($product_types);
                @endphp
            @endphp --}}
            <div>
               <review-new-offers  :products="{{ json_encode($product_types) }}" ></review-new-offers>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
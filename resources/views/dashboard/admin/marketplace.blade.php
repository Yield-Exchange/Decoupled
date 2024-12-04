@extends('dashboard.master')
@section('page_title')
    Admin Marketplace
@stop
@section('styles')

@endsection
@section('page_content')
    <div id="VueApp">
        <admin-marketplace products="{{$products}}" banks_with_offer="{{$banksWithOffers}}" all_banks="{{$allBamks}}"></admin-marketplace>
    </div>
@endsection
@section('scripts')

@endsection
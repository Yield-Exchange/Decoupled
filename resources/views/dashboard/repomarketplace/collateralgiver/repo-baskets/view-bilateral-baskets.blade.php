@extends('dashboard.master')
@section('page_title')
    View Triparty Baskets
@stop
@section('page_content')

    <div class="row" id="VueApp">
        <div class="col-md-12">
            <view-bilateral-baskets :user-logged-in='{{ json_encode($userLoggedIn) }}' />
        </div>
    </div>

@endsection

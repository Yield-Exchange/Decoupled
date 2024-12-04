@extends('dashboard.master')
@section('page_title')
    Collaterals
@stop
@section('page_content')


    <div class="row" id="VueApp">
        <div class="col-md-12">
            <view-collaterals :user-logged-in='{{ json_encode($userLoggedIn) }}' />
        </div>
    </div>

@endsection

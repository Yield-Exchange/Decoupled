@extends('dashboard.master')
@section('page_title')
    Repo In Progress Requests
@stop
@section('page_content')


    <div class="row" id="VueApp">
        <div class="col-md-12">
            <view-in-progress-summary :user-logged-in='{{ json_encode($userLoggedIn) }}' />
        </div>
    </div>

@endsection

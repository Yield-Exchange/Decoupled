@extends('dashboard.master')

@section('page_title')
    Create Baskets
@stop

@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            {{-- Pass authenticated user data to Vue component --}}
            <create-baskets :user-logged-in='{{ json_encode($userLoggedIn) }}'></create-baskets>
        </div>
    </div>
@endsection

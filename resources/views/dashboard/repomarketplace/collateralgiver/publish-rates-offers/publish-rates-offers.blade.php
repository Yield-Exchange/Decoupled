@extends('dashboard.master')
@section('page_title')
    CG Post Request
@stop
@section('page_content')


    <div class="row" id="VueApp">
        <cg-post-request :user-logged-in='{{ json_encode($userLoggedIn) }}'
            formattedtimezone="{{ json_encode($formattedtimezone) }}"
            prime_rate="{{ json_encode($prime_rate) }}"></cg-post-request>
    </div>

@endsection

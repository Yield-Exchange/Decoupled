@extends('dashboard.master')
@section('page_title')
    CG Post Request with ai
@stop
@section('page_content')


    <div class="row" id="VueApp">
        <publish-rate-with-ai :user-logged-in='{{ json_encode($userLoggedIn) }}'
            formattedtimezone="{{ json_encode($formattedtimezone) }}"
            prime_rate="{{ json_encode($prime_rate) }}"></publish-rate-with-ai>
    </div>

@endsection

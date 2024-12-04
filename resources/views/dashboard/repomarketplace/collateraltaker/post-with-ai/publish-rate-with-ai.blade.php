@extends('dashboard.master')
@section('page_title')
    CG Post Request with ai
@stop
@section('page_content')


    <div class="row" id="VueApp">
        <publish-rate-with-ai :user-logged-in='{{ json_encode($userLoggedIn) }}'
            :organization='{{ auth()->user()->organization }}'
       
            formattedtimezone="{{ json_encode($formattedtimezone) }}"></publish-rate-with-ai>
    </div>
  

@endsection

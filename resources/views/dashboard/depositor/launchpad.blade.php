@extends('dashboard.master')
@section('page_title')
    Dashboard
@stop
@section('page_content')
    @php
        $user = auth()->user();
    @endphp
    <div class="row c-dashboard" id="VueApp">
        {{-- <div class="col-xl-12"> --}}
        <launch-pad  :user-logged-in='{{ json_encode($userLoggedIn) }}' enable_repos="{{ $enable_repos }}" enable_campaigns="{{ $enable_campaigns }}" />
        {{-- </div> --}}
    </div>
    <!-- /main charts -->
@endsection
@section('scripts')
    {{--    <script> --}}
    {{--        $(document).ready(function() { --}}
    {{--            setInterval(function() { --}}
    {{--                $('#res').load('{{ action('DemoController@index') }}'); --}}
    {{--            }, 3000); --}}
    {{--        }); --}}
    {{--    </script> --}}
@endsection

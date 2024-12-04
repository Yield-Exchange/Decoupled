@extends('dashboard.signup-master')
@section('page_title')
    Signup
@stop
@section('styles')
    <style>


    </style>
@endsection
@section('page_content')
    <!-- Main charts -->
    @php
        $user_data = auth()->user();
    @endphp
    {{-- {{ $user_data }} --}}
    <div class="row" id="VueApp">
        <new-sign-up ipinfokey="{{ config('app.IPINFO_KEY') }}" user="{{ json_encode($user_data) }}"
            fis="{{ json_encode(getFIs()) }}" fi_types="{{ json_encode($fi_types) }}"
            timezones="{{ json_encode(timezonesList()) }}"></new-sign-up>
    </div>

    <!-- /main charts -->
@endsection
@section('scripts')

@endsection

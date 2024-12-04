@extends('dashboard.master')
@section('page_title')
    Create GIC/HISA
@stop
@section('page_content')
    <div class="row" id="VueApp">
        {{-- <div class="col-xl-12"> --}}
        {{-- <div class="row"> --}}
        <div class="col-md-12">
            {{-- <div class="col-md-6"> --}}
            <bank-create-gic :organization="{{ json_encode($organization) }}" :deposit="{{ json_encode($deposit) }}"
                :submit-route="'{{ $submitRoute }}'" :from-page="'{{ $fromPage }}'"
                :user-object="{{ json_encode(auth()->user()) }}"
                :permitted-submit-button="{{ json_encode($permittedSubmitButton) }}">
            </bank-create-gic>
            {{-- </div> --}}
        </div>
        {{-- </div> --}}
    </div>
@endsection
@section('scripts')
@endsection

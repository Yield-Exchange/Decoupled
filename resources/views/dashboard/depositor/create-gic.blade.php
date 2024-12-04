@extends('dashboard.master')
@section('page_title')
    Create GIC/HISA
@stop
@section('page_content')
    <div class="row mb-5" id="VueApp">

        <div class="col-md-12">
            <depositor-create-gic :organization="{{ json_encode($organization) }}" :deposit="{{ json_encode($deposit) }}"
                :submit-route="'{{ $submitRoute }}'" :deposit-req="{{ json_encode($deposit_req) }}"
                :from-page="'{{ $fromPage }}'" :user-object="{{ json_encode(auth()->user()) }}"
                :permitted-submit-button="{{ json_encode($permittedSubmitButton) }}">
            </depositor-create-gic>
        </div>
    </div>

@endsection
@section('scripts')
@endsection

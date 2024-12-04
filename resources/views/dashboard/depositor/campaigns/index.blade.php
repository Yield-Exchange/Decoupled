@extends('dashboard.master')
@section('page_title')
    My Offers
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">          

            <depositor-campaign-offer :userloggedin="{{ $userLoggedIn }}" :products="{{ json_encode($products) }}" is_summary="{{ $is_summary }}"
                :fiorganizations="{{ json_encode($fiorganizations) }}" organization_id="{{ $organization_id }}"
                :industries="{{ json_encode($industries) }}" :formattedtimezone="{{ json_encode($formattedtimezone) }}"  ></depositor-campaign-offer>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

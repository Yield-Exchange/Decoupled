@extends('dashboard.master')
@section('page_title')
Campaigns
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>
                @includeWhen($organization->type == 'DEPOSITOR', 'dashboard.depositor.campaigns.index')
                @includeWhen($organization->type == 'BANK', 'dashboard.bank.campaigns.index')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

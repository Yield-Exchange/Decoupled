@extends('dashboard.master')
@section('page_title')
    Access Denied
@stop
@section('page_content')
    <div class="row">
        <div class="col-xl-12">
            <div class="alert alert-danger">Access denied, it appears as if you do not have permissions to operate the site. Please contact your organization administrator or info@yieldexchange.ca for more information.</div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
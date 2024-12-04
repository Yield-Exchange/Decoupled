@extends('dashboard.master')
@section('page_title')
    Drafts
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-md-12">
            <div>
               <drafts-campaigns products="{{ $products }}" />
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

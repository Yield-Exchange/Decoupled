@extends('home.master')
@section('page_title')
    Authentication
@stop
@section('styles')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/onboarding.css?v=1.0') }}" rel="stylesheet" />
    <style>
        .header-bar-area{
            background: url('/assets/img/banner-bg.png') no-repeat;
            padding: 25px 0 10px;
            background-size: cover;
        }
        .terms-and-condiotion-pdf-holder .pdf-holder{
            height: 80vh !important;
        }
    </style>
@stop
@section('page_content')
    <!-- ====== Banner Part HTML Start ====== -->
    <div id="banner" style="height: 100% !important;background:white">
        <div class="container" id="VueApp">
            <div id="row" style="height: 100% !important;background:white">
                <div class="row" style="height: 100% !important;background:white">
                    <div class="col-md-12 row mb-4" style="height: 100% !important;background:white">
                        <terms-and-conditions class="terms-and-condiotion-pdf-holder" oldonboarding="1"> </terms-and-conditions>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endsection
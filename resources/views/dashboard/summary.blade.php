@extends('dashboard.master')
@section('page_title')
    Summary
@stop
@section('styles')

@endsection
@section('page_content')
<style>
    .row-custom{
        margin: 30px 0 30px 0;
    }
    .card-buttons-container{
        justify-content: center; align-items: center;
    }
    .card-buttons:first-child{
        margin-right: 2%;
    }
    .card-buttons{
        cursor:pointer;
        min-height: 200px;
        font-size: 1rem;
        font-weight: normal;
        border-radius: 5px;
        width: 15rem;
    }
    .card-buttons:hover{
        -webkit-transform: scale(1.1);
        -moz-transform: scale(1.1);
        -ms-transform: scale(1.1);
        -o-transform: scale(1.1);
        transform: scale(1.1);
        box-shadow: 2px 2px #ccc;;
    }
    .choose-action-title{
        font-weight: 600;
        font-size: 1.3rem;
    }
    .card-text{
        font-weight: 300;
        font-size: 15px;
    }
    .card-img-top{
        height: 100px;
        width: 100px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
    }
</style>
<div class="row ">
    <div class="col-xl-12">
        <!-- Support tickets -->
        
        <div class="card" style="margin: 0;">
            <div class="jumbotron text-center">
                <h1 class="display-4">Company Summary</h1>
                <p class="lead">{{ $organization->demographicData->summary }}.</p>
                
                <p class="lead">
                  <a class="btn btn-primary btn-lg" href="{{ url()->current() }}" role="button">Next</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection
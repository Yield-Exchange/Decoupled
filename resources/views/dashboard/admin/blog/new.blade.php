@extends('dashboard.master')
@section('page_title')
    Create Blog
@stop
@section('page_content')
    <div class="row" id="VueApp">
        <div class="col-xl-12">
            <div class="row">
                <create-blog
                    blog="{{ $blog }}"
                    categories="{{ $categories }}"
                    tags="{{ $tags }}"
                    statuses="{{ json_encode($status) }}"
                >
                </create-blog>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
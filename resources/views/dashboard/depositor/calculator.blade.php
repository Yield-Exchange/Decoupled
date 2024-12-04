@extends('dashboard.master')
@section('page_title')
    Calculate Interest
@stop
@section('styles')
    
@stop
@section('page_content')

    <div id="VueApp">
        @if (request('rates'))
            <rates-calculator />
        @else
            <investor-calculator /> 
        @endif
        {{-- --}}
    </div>



@endsection
@section('scripts')
    
@endsection

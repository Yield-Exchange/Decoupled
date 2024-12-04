@extends('emails.master')
@section('page_content')
    <br/><center>Welcome to Yield Exchange. Please use this temporary password to login into the site: <div style='color:blue;width: 100%;text-align: center;padding-top:0;padding-bottom: 0;margin: 0'>{{ $password }}</div></center>
@endsection
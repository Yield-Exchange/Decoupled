@extends('emails.master')
@section('page_content')
    Hi {{ $message_['name'] }},<br/>
    <p>Welcome to Yield Exchange, your temporary password to login to Yield Exchange is
        <br/> <div style='color:blue;width: 100%;text-align: center;padding-top:0;padding-bottom: 0;margin: 0'>{{ $message_['pass'] }}</div><br/></p>
    <p>Please use this password to login and finish setting up your account. You will be requested to change the password when you login the first time.</p>
    <p>Thanks,</p>
@endsection
@extends('emails.master')
@section('page_content')
    Hi {{ $message_['account_manager'] }},<br/>
    <p>I have a deposit that I am looking for rates on and I'm inviting you to participate in this request through Yield Exchange.</p>
    <p>Yield Exchange is a digital platform that allows me to negotiate with Canadian Financial Institutions like you, easily and efficiently.</p>

    <p>Please contact me if you have any questions regarding this invitation, otherwise I look forward to your response on my Deposit.</p>

    <p>Thanks,</p>

    <p>{{ $message_['your_name'] }}</p>
    <p>{{ $message_['email'] }}</p>
    <p>{{ $message_['telephone'] }}</p>
@endsection
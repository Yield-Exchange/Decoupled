@extends('emails.new-master')
@section('page-content')
<div style="padding: 0.5%">
    <div style="width: 100%; text-align: center; margin-top: 20px;">
        <div
            style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
            <img src="{{ asset('assets/emails/game-icons_cash.png') }}" alt=""
                style="vertical-align: middle; margin-right:8px">
            <span
                style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                Repo Edit Status
            </span>
        </div>
    </div>

    <div style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
        align="center">
        Repo Edited Successfully
    </div>
    <div>
        <img src="{{ asset('assets/emails/campaign-edited.png') }}" alt="" style="max-height:400px">
    </div>
    <div>
        <p style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
            align="center">Your <span
                style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal">
                Edits were made to the following Repo
        </p>
    </div>
    <div style="width:100%; margin:0 auto;">
        <table
            style="border-collapse :collapse; width:65%; margin:0 auto; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; padding:10px">
            <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                <tr style="text-align: left;">
                    <th style="padding:0.6rem; border-bottom:3px solid white; border-top:none">Product</th>
                    <th style="padding:0.6rem; border-bottom:3px solid white; border-top:none">Original Rate</th>
                    <th style="padding:0.6rem; border-bottom:3px solid white; border-top:none">New Rate</th>
                </tr>
            </thead>
            <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">              
                <tr style="text-align: left;">
                    <td style="padding:0.6rem;border-bottom:3px solid white; border-top:none">
                        {{ $CTRequest['afterEdit']->product->name }}</td>
                        <td style="padding:0.6rem;border-bottom:3px solid white; border-top:none">
                            {{ $CTRequest['beforeEdit']->offer_interest_rate }}%
                        </td>
                    <td style="padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $CTRequest['afterEdit']->offer_interest_rate }}%</td>
                 
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
            align="center">Not sure why you received this email? <a href="{{ url('request-an-account')}}"><span
                    style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                    Chat </span></a> us Or
            <a href="mailto: info@yieldexchange.com"> <span
                    style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                    Email</span></a>
        </p>
    </div>
</div>
@endsection
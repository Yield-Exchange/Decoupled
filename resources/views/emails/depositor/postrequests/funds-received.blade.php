@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%; margin-right:auto; margin-left: auto;" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/book-v2.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    GIC Deposit
                </span>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            width="100%" align="center">
            Funds Received
        </div>

        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/funds-recieved.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>
        <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
            <p class="action-message"
                style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">The funds for the following deposit have been provided successfully and therefore
                marked as
                complete</p>
        </div>

        <div class="w-100  " style="width:100%" width="100%">
            <table class="custom-table w-100 table table-hover"
                style="width:100%; border-collapse:collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; padding:10px">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Deposit ID</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Depositor</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Deposited Amount</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Date of Deposit</th>
                    </tr>
                </thead>
                <tbody
                    style="background-color:#F4F5F6; color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin-top:30px"
                    bgcolor="#F4F5F6">
                    <tr>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">{{ $depositdetails['deposit_details']->reference_no }}</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">{{ $depositdetails['deposit_request']->user->name }}</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">CAD {{ number_format($depositdetails['deposit_details']->offered_amount) }}</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">{{ $depositdetails['deposit_request']->date_of_deposit }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('active-deposits') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    View All Deposits
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div>
            <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">Discover a world of exclusive investors waiting for you <a
                    href="{{ url('request-an-account')}}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{url('login')}}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
    </div>
@endsection

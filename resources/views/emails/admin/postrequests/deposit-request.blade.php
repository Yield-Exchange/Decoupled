@extends('emails.new-master')
@section('page-content')
<div style="padding: 0 5%;margin-right:auto; margin-left:auto;" class="responsive">
    <div style="width: 100%; text-align: center; margin-top: 20px;">
        <div
            style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
            <img src="{{ asset('assets/emails/people.png') }}" alt="" style="vertical-align: middle; margin-right:8px">
            <span
                style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                Deposit Status
            </span>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
        style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
        width="100%" align="center">
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="marign-top: 20px; margin-bottom:20px;color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            New Deposit Request
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/deposit-request.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">

            <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
                <p class="notify"
                    style="color:#252525; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                    align="center">Hello Admin, this is to notify you that a new deposit request has been posted, </p>

            </div>
            <div class="w-100  " style="width:100%" width="100%">
                <table class="width:100%;border-collapse :collapse;margin:0 auto !important; custom-table w-100 table table-hover"
                    style="width:100%; margin:0 auto !important; border-collapse :collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:12px; padding:10px">
                    <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE"
                        bgcolor="#EFF2FE">
                        <tr>
                            <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">Ref
                            </th>
                            <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">Product</th>
                            <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">Term Length</th>
                            <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">Amount</th>
                            <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">Closing Date</th>
                            <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">Deposit Date</th>

                        </tr>
                    </thead>
                    <tbody
                        style="background-color:#F4F5F6; color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin-top:30px"
                        bgcolor="#F4F5F6">
                         @for ($i=0; $i < count($newrequestDetails['products']); $i++)
                         <tr>
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $newrequestDetails['products'][$i]->reference_no }}</td>
                                @if (!is_null($newrequestDetails['products'][$i]->lockout_period_days) && $newrequestDetails['products'][$i]->lockout_period_days !=0 )
                                <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                    align="start">{{$newrequestDetails['products'][$i]->lockout_period_days}} days {{ $newrequestDetails['products'][$i]->product_name }}</td>  
                                @else
                                <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                    align="start">{{ $newrequestDetails['products'][$i]->product_name }}</td>  
                                @endif
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $newrequestDetails['products'][$i]->term_length }}
                                {{ ucfirst($newrequestDetails['products'][$i]->term_length_type) }}</td>
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $newrequestDetails['products'][$i]->currency }}
                                {{ number_format($newrequestDetails['products'][$i]->amount) }}</td>
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            
                                align="start">{{changeDateFromUTCtoLocal($newrequestDetails['products'][$i]->closing_date_time,'M d Y',null,null,$newrequestDetails['user'])}}
                            </td>
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{changeDateFromUTCtoLocal($newrequestDetails['products'][$i]->date_of_deposit,'M d Y',null,null,$newrequestDetails['user'])}}</td>
                        </tr>
                         @endfor
                        

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
                                    <a href="{{ url('/yie-admin') }}"
                                        style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                        View Deposit
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        @endsection
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
            style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            New Deposit Request From {{$newpostrequestdetails['depositor']->name}}
            
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/deposit-request.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">

            <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
                <p class="notify"
                    style="color:#252525; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                    align="center">If you are interested in putting in an offer, please login and submit a rate by {{$newpostrequestdetails['closing_date']}} </p>

            </div>
            <div class="w-100  " style="width:100%" width="100%">
                <table class="width:100%; border-collapse :collapse;margin:0 auto !important; custom-table w-100 table table-hover"
                    style="width:100%;margin:0 auto !important; border-collapse :collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:12px; padding:10px">
                    <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE"
                        bgcolor="#EFF2FE">
                        <tr>
                            <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">Depositor
                            </th>  
                            <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">Amount</th>
                            <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">Product</th>

                        </tr>
                    </thead>
                    <tbody
                        style="background-color:#F4F5F6; color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin-top:30px"
                        bgcolor="#F4F5F6">
                        {{-- //{{json_encode($newpostrequestdetails)}} --}}
                         @for ($i=0; $i < count($newpostrequestdetails['products']); $i++)
                         <tr>
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $newpostrequestdetails['depositor']->name }}</td>
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $newpostrequestdetails['products'][$i]->currency }}
                                {{ number_format($newpostrequestdetails['products'][$i]->amount) }}</td>
                            @if (!is_null($newpostrequestdetails['products'][$i]->lockout_period_days) && $newpostrequestdetails['products'][$i]->lockout_period_days !=0 )
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{$newpostrequestdetails['products'][$i]->lockout_period_days}} days {{ $newpostrequestdetails['products'][$i]->product_name }}</td>  
                            @else
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $newpostrequestdetails['products'][$i]->product_name }}</td>  
                            @endif
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
                                    <a href="{{ url('/login') }}"
                                        style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 40px; padding: 4px 30px; display: inline-block;">
                                        View Request
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        @endsection
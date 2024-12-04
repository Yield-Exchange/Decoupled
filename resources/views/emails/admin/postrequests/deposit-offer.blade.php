@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%; margin-right:auto; margin-left:auto" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/people.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Campaign Status
                </span>
            </div>
        </div>
        <div class="d-flex justify-content-center my-2 campaign-status-text "
            style="margin-bottom:20px; width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            width="100%" align="center">
            New Rate Offer
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/deposit-offer.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>

        <div class="d-flex justify-content-center" style="width:100%" width="100%">
            <p class="notify"
                style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">This is to notify you that <span class="" style="font-weight: 700">
                    {{ $new_offer['business_name'] }}</span>
                placed an offer </p>
        </div>

        <div style="width:100%" width="100%">
            <table class="custom-table table table-hover"
                style="width:100%; border-collapse:collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; padding:10px"
                width="80%">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th style="text-align:start; padding:0.6rem; border-bottom:3px solid white; border-top:none"
                            align="start">Deposit ID</th>
                        <th style="text-align:start; padding:0.6rem; border-bottom:3px solid white; border-top:none"
                            align="start">Max Amount</th>
                        <th style="text-align:start; padding:0.6rem; border-bottom:3px solid white; border-top:none"
                            align="start">Min Amount</th>
                        <th style="text-align:start; padding:0.6rem; border-bottom:3px solid white; border-top:none"
                            align="start">Rate</th>
                       
                    </tr>
                </thead>
                <tbody
                    style="background-color:#F4F5F6; color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin-top:30px"
                    bgcolor="#F4F5F6">
                     
                    <tr>
                        <td style="padding:0.6rem;text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ $new_offer['reference_no'] }}</td>
                        <td style="padding:0.6rem;text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                           {{$new_offer['currency']}} {{ number_format($new_offer['max_amount']) }}</td>
                        <td style="padding:0.6rem;text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{$new_offer['currency']}} {{ number_format($new_offer['min_amount']) }}</td>
                        <td style="padding:0.6rem;text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ number_format($new_offer['rate'],2) }} % </td>
                        {{-- <td style="padding:0.6rem;text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ $new_offer['rate_type'] }}</td> --}}
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
                                    Visit Deposit
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
@endsection

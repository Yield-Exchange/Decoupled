@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%; margin-right:auto; margin-left:auto;" class="responsive">
        <div style="width: 100%; text-align: center; margin-top:20px;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/game-icons_cash.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Counter Offer
                </span>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="marign-top: 20px; margin-bottom:20px;width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            width="100%" align="center">
            <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
                style="marign-top: 20px; margin-bottom:20px;color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
                align="center">
             
                Repo Counter Offer Sent

            </div>
            <div style="width: 100%; text-align: center;">
                <img src="{{ asset('assets/emails/Ecommerce-web-page-rafiki.png') }}" class="cover-image" alt=""
                    style="max-height: 400px; display: block; margin: 0 auto;">

                <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
                    <p class="notify"
                        style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center; color:#252525"
                        align="center">
                        Here is your counter to {{$offerDetails['to']}}   
                    </p>

                </div>
                <div class="w-100  " style="width:100%" width="100%">
                    <table class="border-collapse :collapse;margin:0 auto !important; custom-table w-100 table table-hover"
                        style="width:100%; margin:0 auto !important; border-collapse :collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:12px; padding:10px">
                        <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE"
                            bgcolor="#EFF2FE">
                            <tr>
                                <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                    align="start">
                                </th>
                                <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                    align="start">Original Offer
                                </th>
                                <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                    align="start">Counter Offer</th>

                            </tr>
                        </thead>
                        <tbody
                            style="background-color:#F4F5F6; color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin-top:30px"
                            bgcolor="#F4F5F6">
                            {{-- {{json_encode($offerDetails)}} --}}

                            {{-- rate --}}

                            @if ( $offerDetails['original_offer']->offer_interest_rate !=  $offerDetails['counter_offer']->offer_interest_rate)
                                <tr>
                                    <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; color:#5063F4; font-size:28px; font-weight:400"
                                        align="start">Rate</td>
                                    <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-size:28px"
                                        align="start">
                                        {{ number_format( $offerDetails['original_offer']->offer_interest_rate, 2) }}%</td>
                                    <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-size:28px; font-weight:700; color:#252525"
                                        align="start">
                                        {{ number_format( $offerDetails['counter_offer']->offer_interest_rate, 2) }}%</td>
                                </tr>
                            @endif

                            {{-- minimum amount   --}}
                            @if ( $offerDetails['original_offer']->offer_minimum_amount !=  $offerDetails['counter_offer']->offer_minimum_amount)
                                <tr>
                                    <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; color:#5063F4; font-size:28px; font-weight:400"
                                        align="start">Minimum Amount</td>
                                    <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-size:28px"
                                        align="start">
                                        {{ $offerDetails['currency'] }}

                                        {{ number_format(floatval( $offerDetails['original_offer']->offer_minimum_amount)) }}</td>
                                    <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-size:28px; font-weight:700; color:#252525"
                                        align="start">  {{ $offerDetails['currency'] }}
                                        {{ number_format(floatval( $offerDetails['counter_offer']->offer_minimum_amount)) }}</td>
                                </tr>
                            @endif
                            {{-- maximum amount --}}
                            @if ( $offerDetails['original_offer']->offer_maximum_amount !=  $offerDetails['counter_offer']->offer_maximum_amount)
                                <tr>
                                    <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; color:#5063F4; font-size:28px; font-weight:400"
                                        align="start">Maximum Amount</td>
                                    <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-size:28px"
                                        align="start">
                                        {{ $offerDetails['currency'] }}
                                        {{ number_format( $offerDetails['original_offer']->offer_maximum_amount) }}</td>
                                    <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-size:28px; font-weight:700; color:#252525"
                                        align="start">  {{ $offerDetails['currency'] }}
                                        {{ number_format( $offerDetails['counter_offer']->offer_maximum_amount) }}</td>
                                </tr>
                            @endif
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
                                       
                                                Login
                                        
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        @endsection

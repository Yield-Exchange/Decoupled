@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
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

        <div style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center ;width:100%;"
            align="center">
            New Counter Offer Request Posted
        </div>


        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/new-counter-offer.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>

        <div class="w-100  " style="text-align: center;">
            <table class="custom-table table table-hover"
                style="width: 70%; margin: 0 auto; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; border-collapse: collapse;">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th style="padding-left:10px; text-align:start; border-bottom:3px solid white; border-top:none; padding:0.6rem"
                            align="start"></th>
                        <th style="padding-left:10px; text-align:start; border-bottom:3px solid white; border-top:none; padding:0.6rem"
                            align="start">Original Offer</th>
                        <th style="padding-left:10px; text-align:start; border-bottom:3px solid white; border-top:none; padding:0.6rem"
                            align="start">Counter Offer</th>
                    </tr>
                </thead>
                <tbody
                    style="background-color:#F4F5F6; color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin-top:30px"
                    bgcolor="#F4F5F6">
                    <tr>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-weight:700 !important"
                            align="start">Minimum</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ number_format($offerdetails['original_offer']->minimum_amount) }}</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ number_format($offerdetails['counter_offer']['counter_offer_details']->minimum_amount) }}
                        </td>

                    </tr>
                    <tr>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-weight:700 !important"
                            align="start">Maximum</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ number_format($offerdetails['original_offer']->maximum_amount) }}</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ number_format($offerdetails['counter_offer']['counter_offer_details']->maximum_amount) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-weight:700 !important"
                            align="start">Rate</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ $offerdetails['original_offer']->interest_rate_offer }}%</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ $offerdetails['counter_offer']['counter_offer_details']->offered_interest_rate }}%</td>
                    </tr>
                    <tr>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-weight:700 !important"
                            align="start">Special
                            Instructions</td>
                        <td colspan="2"
                            style="padding-left:10px; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ $offerdetails['counter_offer']['counter_offer_details']->special_instructions == null ? 'None' : $offerdetails['counter_offer']['counter_offer_details']->special_instructions }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none; font-weight:700 !important"
                            align="start">Counter Offer
                            Expiry</td>
                        <td colspan="2"
                            style="padding-left:10px; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">
                            {{ changeDateFromUTCtoLocal($offerdetails['counter_offer']['counter_offer_details']->offer_expiry, 'Y-m-d H-i'), null, 'America/' . $offerdetails['counter_offer']['timezone'] }}
                            America/{{ $offerdetails['counter_offer']['timezone'] }}</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
            <p class="timezone"
                style="font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:10px; text-align:center"
                align="center">*Counter offer expiry timezone America/Winnipeg</p>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('/yie-admin/users/users_onboard') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    Review Offer
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
@endsection

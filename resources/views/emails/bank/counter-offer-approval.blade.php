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
                @if ($action == 'accepted')
                     Counter Offer Accepted
                @else
                    Counter Offer Declined
                @endif

            </div>
            <div style="width: 100%; text-align: center;">
                @if ($action == 'accepted')
                    <img src="{{ asset('assets/emails/High.png') }}" class="cover-image" alt=""
                        style="max-height: 400px; display: block; margin: 0 auto;">
                @else
                    <img src="{{ asset('assets/emails/decline.png') }}" class="cover-image" alt=""
                        style="max-height: 400px; display: block; margin: 0 auto;">
                @endif

                <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
                    <p class="notify"
                        style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center; color:#252525"
                        align="center"> {{ $header }}</p>

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
                                            View Offers
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        @endsection

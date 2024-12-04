@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%; margin-right:auto; margin-left:auto;" class="responsive">
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            width="100%" align="center">
            <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
                style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
                align="center">
                Request Closing soon
            </div>
            <div style="width: 100%; text-align: center;">
                <img src="{{ asset('assets/emails/Deadline-pana.png') }}" class="cover-image" alt=""
                    style="max-height: 400px; display: block; margin: 0 auto;">

                <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
                    <p class="notify"
                        style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center; color:#252525"
                        align="center"><span style="color: #5063F4; font-weight: 700">{{ $ref }}</span> for <span
                            style="color:#252525; font-weight:700">{{ $amount }}</span> accepts rates
                        till {{ $date }}</p>

                </div>

                <div
                    style="font-family:'Montserrat'; display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
                    <p
                        style="font-size:18px; margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                        <img src="{{ asset('assets/emails/ri_mail-add-fill.png') }}" style="padding: 0; margin: 0;"
                            height="25">
                        <span style="margin-left: 10px;">Please contact the depositor to initiate the next steps.</span>
                    </p>
                </div>


                <p
                    style="font-size:14px; font-family:'Montserrat'; margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                    If this is a new customer you can initiate the account onboarding process now.
                </p>

                {{-- <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="center" style="border-bottom:3px solid white; border-top:none">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" bgcolor="#ffffff"
                                        style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                        <a href="{{ url('/login') }}"
                                            style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                            Login
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table> --}}
            </div>
        @endsection

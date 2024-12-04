@extends('emails.new-master')
    @section('page-content')
    <div style="padding:0 5%; margin-right:auto; margin-left:auto;" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 4rem;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/fluent_people-add-16-filled.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Registration Status
                </span>
            </div>
        </div>
            <div style="marign-top: 20px; margin-bottom:20px;color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center" align="center">
                Abandoned Registration
            </div>
            <div>
                <img src="{{ asset('assets/emails/abandoned-account.png') }}"  alt="" style="max-height:400px">
            </div>
            <div>
                <p style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center" align="center">
                    <span style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal"> {{ $business_stage["business_name"]}} abandoned registration at {{ $business_stage["stage"]}}. </span> <br>Kindly review
                    the account within the next 24 hours to ensure a swift onboarding progression</p>
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
                                        Review Account
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endsection

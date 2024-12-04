@extends('emails.new-master')
    @section('page-content')
    <div style="padding: 0 5%; margin-right:auto; margin-left:auto" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/mdi_security-lock-outline.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Account Security
                </span>
            </div>
        </div>
        <div style="margin:0 auto; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Password Changed
        </div>

        <div>
            <img src="{{ asset('assets/emails/password-changed.png') }}" alt="" style="max-height:400px">
        </div>
        <div style="width: 100%">
            <p
                style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal ;margin:0 auto;text-align:center">
                Your password was changed on {{ getUTCEmailDateNow() }}.</p>
        </div>
        <div>
            <p style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">If this was you, then you can safely ignore this email.If this wasn't, your account
                has been compromised. Here is what you can do:</p>
        </div>

        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/mdi_folder-eye.png') }}" style="padding: 0; margin: 0;" height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Reset Your Password: </span>
                <span style="margin-left: 10px;">and take control of your account.</span>
            </p>
        </div>

        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/material-symbols_explore.png') }}" style="padding: 0; margin: 0;"
                    height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Email us of use our chat: </span>
                <span style="margin-left: 10px;">to help you log back into the system.</span>
            </p>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('login') }}?action=resetPassword"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    Reset Password
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>



        <div style="width:100%">
            <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin: 0 auto;text-decoration: none;text-align:center"
                align="center">Discover a world of exclusive GIC's waiting for you <a
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

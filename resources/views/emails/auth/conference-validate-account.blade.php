@extends('emails.new-master')
@section('page-content')
    <div style="padding:0 5%; margin-right:auto;margin-left:auto" class="responsive">
        <div style="margin:0 auto; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center; margin-top:20px"
            align="center">
            Account Approved
        </div>
        <div style="width: 100%; text-align: center; margin-top:20px;">
            <div
                style="display: inline-flex; align-items: center; background: #5063f4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/book-v2.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    User Onboard
                </span>
            </div>
        </div>

        <div style="width: 100%; text-align: center; margin-top:20px">
            <img src="{{ asset('assets/emails/accountapproved.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>
        <div style="width: 100%; display: table; margin: 0 auto;">
            <p class="discover-login"
                style="color: #252525; text-align: justify; font-family: Montserrat; font-size: 16px; font-style: normal; font-weight: 300; line-height: normal;">
                Thank you for joining Yield Exchange - Canada's premier platform for checking, comparing, and countering GIC
                rates. We are delighted to inform you that your account has been successfully approved. You can now access
                our platform and enjoy all its features.
                {{-- Please use the following temporary password to log in: --}}
            </p>
        </div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ $links }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 10px 30px; display: inline-block; width: 250px;">
                                    View Account
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>


        <div style="width: 100%; display: table; margin: 0 auto;">
            <p class="discover-login"
                style="color: #252525; text-align: justify; font-family: Montserrat; font-size: 16px; font-style: normal; font-weight: 300; line-height: normal;">
                Should you have any questions or need assistance, please don't hesitate to reach out. We're here to help.
            </p>
        </div>
    </div>
@endsection

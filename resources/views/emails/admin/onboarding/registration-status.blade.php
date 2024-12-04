@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-block; vertical-align: middle; background: #44E0AA; border-radius: 85.91px; padding: 3.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/people.png') }}" alt="" style="vertical-align: middle;">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Registration
                </span>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="marign-top: 20px; margin-bottom:20px;color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Terms and Conditions
        </div>

        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('assets/emails/regitsration-status.png') }}" class="cover-image" alt=""
                style="max-height:400px">
        </div>

        <div class="w-100 d-flex justify-content-center account gap-2 mt-3 flex-column"
            style="font-family:Montserrat; font-size:22px; font-style:normal; font-weight:300; line-height:normal">
            <p style="margin: 0; padding: 0; color: #252525; font-weight: 300;text-align: center;padding-left:20px">
                <span style="color: #5063F4; font-weight: 700;"> {{ $display_message }} </span>
            </p>
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
                                    View Account
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
@endsection

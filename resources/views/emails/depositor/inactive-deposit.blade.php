@extends('emails.new-master')
@section('page-content')
<div style="padding: 0 5%;margin-right:auto; margin-left:auto;" class="responsive">
       <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
        style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
        width="100%" align="center">
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="marign-top: 20px; margin-bottom:20px;color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Inactive Deposit
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/OBJECTS.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">

            <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
                <p class="notify"
                    style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center; color:#252525"
                    align="center">Your deposit <span style="color:  #5063F4; font-family: Montserrat;font-size: 20px; font-style: normal;font-weight: 400;line-height: 26px;">{{$header}}</span> has been redeemed early</p>

            </div>

            <p style="color: #252525;text-align: center;font-family: Montserrat;font-size: 16px;font-weight: 300;line-height: normal;">Please note redemption is only initiated in the Yield Exchange platform.</p>
         
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
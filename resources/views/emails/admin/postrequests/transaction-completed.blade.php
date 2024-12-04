@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%">
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
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="width:100%;marign-top: 20px; margin-bottom:20px; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            width="100%" align="center">
            Transaction Complete
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/complete-transaction.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>

        <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
            <p class="action-message"
                style="font-family:Montserrat; font-size:32px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">The fund received for <span
                    style="font-family:Montserrat; font-size:32px; font-style:normal; font-weight:700; line-height:normal; text-align:center"
                    align="center"> Deposit ID: {{ $reference_no }} </span> has been
                successfully provided,
                marking the transaction as complete.</p>
        </div>



        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('/login') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    View Transaction
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
@endsection

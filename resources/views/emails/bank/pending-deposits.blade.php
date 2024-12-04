@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%; margin-right:auto; margin-left:auto;" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/game-icons_cash.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Investment Guide
                </span>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            width="100%" align="center">
            <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
                style="marign-top: 20px; margin-bottom:20px;color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
                align="center">
                Pending Deposits
            </div>
            <div style="width: 100%; text-align: center;">
                <img src="{{ asset('assets/emails/pending_deposit.png') }}" class="cover-image" alt=""
                    style="max-height: 400px; display: block; margin: 0 auto;">

                <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
                    <p class="notify"
                        style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center; color:#252525"
                        align="center"><span style="font-weight: 700">You have <span
                                style="color:#5063F4; font-size:24px; fonr-weight:700;">{{ $count }}</span> GICs that
                            are pending your review. </p>

                </div>

                <div
                    style="font-family:'Montserrat'; display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
                    <p
                        style="font-size:18px; margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                        <img src="{{ asset('assets/emails/ri_mail-add-fill.png') }}" style="padding: 0; margin: 0;"
                            height="25">
                        <span style="margin-left: 10px; color:#252525; font-size:18px; font-weight:700">This helps the depositor monitor their GIC and is valuable during audits.</span>
                    </p>
                </div>


                <p
                    style="font-size:14px; font-family:'Montserrat'; margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                    If this is a new customer you can initiate the account onboarding process now.
                </p>

                <p
                    style="color: #252525;text-align: center;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 700;line-height: normal;">
                    Don't forget to complete your Deposit after receiving the funds by pressing the
                    <span
                        style="color: #5063F4;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 700;line-height: normal;">'Create
                        GIC'
                    </span> button in the
                    <span
                        style="color: #5063F4;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 700;line-height: normal;">'Pending
                        Deposits'</span> page.
                </p>

                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="center" style="border-bottom:3px solid white; border-top:none">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" bgcolor="#ffffff"
                                        style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                        <a href="{{ url('/login') }}"
                                            style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                            Log GIC
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>
        @endsection

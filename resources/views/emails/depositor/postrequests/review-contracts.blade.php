@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-block; vertical-align: middle; background: #44E0AA; border-radius: 85.91px; padding: 3.436px 10.309px; text-transform: capitalize;">
                <img src="https://i.postimg.cc/tCwF1QfH/people.png" alt="" style="vertical-align: middle;">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Campaign Status
                </span>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            width="100%" align="center">
            Pending Deposits
        </div>
        <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
            <img src="{{ asset('assets/emails/pending_deposit.png') }}" class="cover-image" alt=""
                style="max-height:400px">
        </div>
        <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
            <p class="action-message"
                style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Please review the incomplete contracts </p>
        </div>

        <div class="w-100  " style="width:100%" width="100%">
            <table class="custom-table w-100 table table-hover"
                style="width:100%; border-collapse:collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:12px; padding:10px"
                width="100%">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start"></th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Deposit ID</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Offered Amount</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Date of Deposit</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Depositor</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Institution</th>
                    </tr>
                </thead>
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                    <tr>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">1.</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">12343</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">CAD 2,000,000</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">2022-10-12</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Ferni Muni</td>
                        <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Synergy</td>
                    </tr>
                </tbody>
            </table>
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

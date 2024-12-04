<p>@extends('emails.new-master')
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
            style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Pending Deposits
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/pending_deposit.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>

        <div class="w-100 d-flex justify-content-center">
            <p class="action-message"
                style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Please review the following deposits for approval </p>
        </div>

        <div style="width:100%">
            <table class="custom-table w-100 table table-hover"
                style=" border-collapse: collapse;font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; padding:10px">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"></th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none">Deposit
                            ID</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none">Offered
                            Amount</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none">Date of
                            Deposit</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none">
                            Depositor</th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none">
                            Institution</th>
                    </tr>
                </thead>
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">

                    @if (count($pendingdeposits) > 0)
                        @foreach ($pendingdeposits as $key => $item)
                            <tr>
                                <td style="padding:0.6rem; text-align:start;border-bottom:3px solid white; border-top:none">
                                    {{ $key + 1 }}.</td>
                                <td style="padding:0.6rem; text-align:start;border-bottom:3px solid white; border-top:none">
                                    {{ $item['deposit_id'] }}</td>
                                <td style="padding:0.6rem; text-align:start;border-bottom:3px solid white; border-top:none">
                                    {{ current(explode(' ', $item['offered_amount'])) }}
                                    {{ number_format(explode(' ', $item['offered_amount'])[1]) }}</td>
                                <td style="padding:0.6rem; text-align:start;border-bottom:3px solid white; border-top:none">
                                    {{ current(explode(' ', $item['date_of_deposit'])) }} </td>
                                <td style="padding:0.6rem; text-align:start;border-bottom:3px solid white; border-top:none">
                                    {{ $item['depositor'] }}</td>
                                <td style="padding:0.6rem; text-align:start;border-bottom:3px solid white; border-top:none">
                                    {{ $item['fi'] }}</td>
                            </tr>
                        @endforeach
                    @endif

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
                                <a href="{{ url('/pending-deposits') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    Approve Contracts
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
@endsection

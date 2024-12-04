@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 auto; margin-left: auto; margin-right: auto;" class="responsive">

        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/game-icons_cash.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Campaign Status
                </span>
            </div>
        </div>
        <div style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Your Daily Campaigns summary
        </div>

        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/Online_world_amic.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>


        <div
            style="justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin-top: 20px; margin-bottom:20px; padding: 15px; ">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/streamline_user-add-plus-solid.png') }}" style="padding: 0; margin: 0;"
                    height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Campaigns available to depositors
                </span>
            </p>
        </div>


        <div class="w-100">
            <table class="custom-table w-100 table table-hover"
                style=" width:100%; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; border-collapse :collapse; padding:10px">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Campaign</th>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Target Depositors</th>
                    </tr>
                </thead>
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                    @php
                        $count = count($active->toArray()) <= 5 ? count($active->toArray()) : 5;
                    @endphp

                    @for ($i = 0; $i < $count; $i++)
                        <tr>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ $active[$i]->campaign_name }}</td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ $active[$i]->campaign_depositor_count['invitees'] }} target depositors</td>
                        </tr>
                    @endfor
                    @if (count($active->toArray()) > 5)
                        <td colspan="2"
                            style="background:#D9D9D9; text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none; color:#252525">
                           5 of {{ count($active->toArray()) }} </td>
                    @endif
                </tbody>
            </table>
        </div>
        @if (count($scheduled->toArray()) > 0)
            <div
                style="justify-content: flex-start;  background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 20px; margin-bottom:20px; padding: 15px; ">
                <p
                    style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                    <img src="{{ asset('assets/emails/mdi_talk.png') }}" style="padding: 0; margin: 0;" height="25">
                    <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Scheduled Campaigns </span>
                </p>
            </div>

            <div class="w-100">
                <table class="custom-table w-100 table table-hover"
                    style=" width:100%; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; border-collapse :collapse; padding:10px">
                    <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                        <tr>
                            <th
                                style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                                Campaign</th>
                            <th
                                style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                                Date Scheduled</th>
                        </tr>
                    </thead>
                    <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                        @php
                            $count = count($scheduled->toArray()) <= 5 ? count($scheduled->toArray()) : 5;
                        @endphp

                        @for ($i = 0; $i < $count; $i++)
                            <tr>
                                <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                    {{ $scheduled[$i]->campaign_name }}</td>
                                <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                    {{ date_format(new DateTime($scheduled[$i]->start_date), 'M d Y') }}</td>
                            </tr>
                        @endfor
                        @if (count($scheduled->toArray()) > 5)
                            <td colspan="2"
                                style="background:#D9D9D9; text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                5 of {{ count($scheduled->toArray()) }} 
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        @endif


        @if (count($expire->toArray()) > 0)
        <div
            style="justify-content: flex-start;  background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 20px; margin-bottom:20px; padding: 15px; ">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/tabler_clock-filled.png') }}" style="padding: 0; margin: 0;" height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Expiring Campaigns </span>
            </p>
        </div>

        <div class="w-100">
            <table class="custom-table w-100 table table-hover"
                style=" width:100%; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; border-collapse :collapse; padding:10px">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Campaign</th>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Date To Expire</th>
                    </tr>
                </thead>
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                    @php
                        $count = count($expire->toArray()) <= 5 ? count($expire->toArray()) : 5;
                    @endphp

                    @for ($i = 0; $i < $count; $i++)
                        <tr>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ $expire[$i]->campaign_name }}</td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ date_format(new DateTime($expire[$i]->expiry_date), 'M d Y') }}</td>
                        </tr>
                    @endfor
                    @if (count($expire->toArray()) > 5)
                        <td colspan="2"
                            style="background:#D9D9D9; text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none; color:#252525">
                             5 of {{ count($expire->toArray()) }} 
                        </td>
                    @endif
                </tbody>
            </table>
        </div>
    @endif


        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('login') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    Log in to see details
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div>
            <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">Discover a world of exclusive investors waiting for you <a
                    href="{{ url('/request-an-account') }}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{ url('/login') }}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
        @if ($from == 'fi')
        <div>
            <p style="color: #000;text-align: center;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 300;line-height: normal;">
            Opt out of receiving marketing emails?
            <a href="{{ url('/unsubscribe/daily-summary/' . $user_id . '/' . $email) }}" style="color:#5063F4;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 700;line-height: normal;text-decoration-line: underline;">Unsubscribe</a></p>
        </div>
        @endif
        
    </div>
@endsection

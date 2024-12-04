@extends('emails.new-master')
@section('page-content')
    <div style="padding:0 5%; margin-right:auto; margin-left:auto;" class="responsive">

        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/book-v2.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Offer Status
                </span>
            </div>
        </div>
        <div style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Your rates are being reviewed!
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/certification-pana.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>
        <div>

            <p style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Offers sent in the last <span
                    style="color:5063F4; font-weight: 700;">one hour</span>
            </p>
        </div>


        <div class="w-100">
            <table class="custom-table w-100 table table-hover"
                style=" width:100%; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; border-collapse :collapse; padding:10px">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                        </th>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Depositor </th>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Amount</th>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Product</th>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Rate Offer</th>
                    </tr>
                </thead>
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                    @php
                        $count = count($offers->toArray()) <= 5 ? count($offers->toArray()) : 5;
                    @endphp

                    @for ($i = 0; $i < $count; $i++)
                        <tr>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ $i + 1 }}</td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ $offers[$i]->invited->depositRequest->user->organization->name }}</td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ $offers[$i]->invited->depositRequest->currency }}
                                {{ number_format($offers[$i]->invited->depositRequest->amount) }}</td>
                            @if (
                                $offers[$i]->invited->depositRequest->lockout_period_days == null ||
                                    $offers[$i]->invited->depositRequest->lockout_period_days == 0)
                                <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                    {{ $offers[$i]->invited->depositRequest->product_name }}</td>
                            @else
                                <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                    {{ $offers[$i]->invited->depositRequest->lockout_period_days }} Days
                                    {{ $offers[$i]->invited->depositRequest->product_name }}</td>
                            @endif

                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ number_format($offers[$i]->interest_rate_offer, 2) }}%</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            @if ($count > 0)
            <p style="text-align:center; color:#5063F4;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: normal;">5 of {{count($offers->toArray())}} Offers</p>
    
            @endif
           
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('login') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    View offers
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
                    href="{{ url('request-an-account')}}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{url('login')}}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
        <div>
            <p style="color: #000;text-align: center;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 300;line-height: normal;">
            Opt out of receiving marketing emails?
            <a href="{{ url('/unsubscribe/offers-received/' . $user_id . '/' . $email) }}" style="color:#5063F4;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 700;line-height: normal;text-decoration-line: underline;">Unsubscribe</a></p>
        </div>
    </div>
@endsection

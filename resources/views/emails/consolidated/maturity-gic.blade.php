@extends('emails.new-master')
    @section('page-content')
    <div style="width: 70%; margin-left: auto; margin-right: auto;">

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
            GIC Maturity Approaching
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/deadlinegic.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>

        <div>

            <p style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal; text-align:center"
                align="center">The following products are approaching maturity
            </p>
        </div>


        <div class="w-100">
            <table class="custom-table w-100 table table-hover" style="font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; border-collapse :collapse; padding:10px">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none"></th>
                        <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">Product </th>
                        <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">Maturity Date</th>
                        <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">Deposits Placed</th>
                    </tr>
                </thead>
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                    @for ($i = 0; $i < count($expiring_gic->toArray()); $i++)
                    <tr>
                        <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $i + 1 }}</td>

                    @if ($expiring_gic[$i]->offer->invited->depositRequest->lockout_period_days == NULL || $expiring_gic[$i]->offer->invited->depositRequest->lockout_period_days == 0)
                        <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $expiring_gic[$i]->offer->invited->depositRequest->product_name }}</td>
                    @else
                        <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{$expiring_gic[$i]->offer->invited->depositRequest->lockout_period_days }} Days {{ $expiring_gic[$i]->offer->invited->depositRequest->product_name }}</td>
                    @endif
                       <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none"> {{ $expiring_gic[$i]->maturity_date }}</td>
                        <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">CAD {{ number_format($expiring_gic[$i]->offered_amount) }}</td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>

        <div>

            <p style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal; text-align:center"
                align="center">Here's what you can do next
            </p>
        </div>

        <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/icon-park-solid_reload.png') }}" style="padding: 0; margin: 0;"
                    height="25">
                <span style="color: #5063F4; font-weight: 700; margin-left: 10px;"> Renew </span>
                <span style="margin-left: 10px;">Looking to renew? Shop on Yield Exchange for the best rates</span>
            </p>
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
                                    View Products
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div>
            <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:0 auto; text-align:center"
                align="center">Discover a world of exclusive GIC’s waiting for you <a
                    href="{{ url('/request-an-account') }}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{ url('/login') }}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
    </div>
@endsection

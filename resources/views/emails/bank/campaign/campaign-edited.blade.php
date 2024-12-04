@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0.5%">
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
            Campaign Edited Successfully
        </div>
        <div>
            <img src="{{ asset('assets/emails/campaign-edited.png') }}" alt="" style="max-height:400px">
        </div>
        <div>
            <p style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Your <span
                    style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal">
                    {{ $campaign->campaign_name }} </span> has been edited
                successfully with <br> the following
                changes </p>
        </div>
        <div style="width:100%; margin:0 auto;">
            <table
                style="border-collapse :collapse; width:65%; margin:0 auto; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; padding:10px">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr style="text-align: left;">
                        <th style="padding:0.6rem; border-bottom:3px solid white; border-top:none"></th>
                        <th style="padding:0.6rem; border-bottom:3px solid white; border-top:none">Product</th>
                        <th style="padding:0.6rem; border-bottom:3px solid white; border-top:none">Rate</th>
                        <th style="padding:0.6rem; border-bottom:3px solid white; border-top:none">Term Length</th>
                        <th style="padding:0.6rem; border-bottom:3px solid white; border-top:none">Minimum</th>
                        <th style="padding:0.6rem; border-bottom:3px solid white; border-top:none">Maximum</th>
                    </tr>
                </thead>
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                    @foreach ($campaign->campaignProducts as $key => $item)
                        <tr style="text-align: left;">
                            <td style="border-bottom:3px solid white; border-top:none;">{{ $key + 1 }}</td>
                            @if (hasLockoutPeriod($item->product->productType->description))
                                <td style="border-bottom:3px solid white; border-top:none">
                                    {{ $item->product->lockout_period . ' Days ' . $item->product->productType->description }}
                                </td>
                            @else
                                <td style="border-bottom:3px solid white; border-top:none">
                                    {{ $item->product->productType->description }}</td>
                            @endif
                            <td style="border-bottom:3px solid white; border-top:none">{{ $item->rate }}%</td>
                            <td style="border-bottom:3px solid white; border-top:none">
                                {{ $item->product->term_length . ' ' . ucfirst(strtolower($item->product->term_length_type)) }}
                            </td>
                            <td style="border-bottom:3px solid white; border-top:none">CAD
                                {{ number_format($item->minimum) }} </td>
                            <td style="border-bottom:3px solid white; border-top:none">CAD
                                {{ number_format($item->maximum) }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:20px">
                                <a href="{{ url('/campaigns/summary/' . $campaign->id) }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    View All Products
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
       
        <div>
            <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">Discover a world of exclusive GIC’s waiting for you <a
                    href="{{ url('request-an-account')}}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{url('login')}}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
    </div>
@endsection

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
                Check out your rates below!
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/certification-pana.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>
        <div>

            <p style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Here are all the offers you have received in the last <span
                    style="color:5063F4; font-weight: 700;"> hour</span>
            </p>
        </div>


        <div class="w-100">
            <table class="custom-table w-100 table table-hover"
                style=" width:100%; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; border-collapse :collapse; padding:10px">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                 
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Products </th>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Offers Received</th>
                        <th
                            style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">
                            Highest Rate</th>
                       
                    </tr>
                </thead>
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                    @php
                        $count = count($products) <= 5 ? count($products) : 5;
                    @endphp
                    {{-- {{$products}} --}}

                    @for ($i = 0; $i < $count; $i++)
                        <tr>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ $products[$i]['product_name'] }}</td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ $products[$i]['count'] }}
                            </td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">
                                {{ number_format($products[$i]['highest_rate'], 2) }}%</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            @if ($count > 5)
            <p style="text-align:center; color:#5063F4;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: normal;">5 of {{count($products)}} Offers</p>
    
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
            <p style="color: #000;text-align: center;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 300;line-height: normal;">
            Opt out of receiving marketing emails?
            <a href="{{ url('/unsubscribe/depositor_offers_sent/' . $user_id . '/' . $email) }}" style="color:#5063F4;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 700;line-height: normal;text-decoration-line: underline;">Unsubscribe</a></p>
        </div>
    </div>
@endsection

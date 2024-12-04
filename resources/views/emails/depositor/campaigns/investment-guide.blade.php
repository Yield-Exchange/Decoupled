@extends('emails.new-master')
    @section('page-content')
        @php
            // $productName = null;
            // $myproduct = $campaign->offer->invited->depositRequest;
            // if (hasLockoutPeriod($myproduct->product_name)) {
            //     $productName = $myproduct->lockout_period_days . ' Day ' . $myproduct->product_name;
            // } else {
            //     $productName = $myproduct->product_name;
            // }
    
            // use Carbon\Carbon;
            // // Replace this with your date string
            // $dateTime = Carbon::parse($products['date_of_deposit']);
            // $hours = $dateTime->format('H');
            // $minutes = $dateTime->format('i');
            $bankname = $campaign->campaignFI->name;
        @endphp
    <div style="padding: 0.5%">
    
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/book-v2.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Investment Guide
                </span>
            </div>
        </div>
            <div class="w-100 d-flex justify-content-center">
                <img src="{{ asset('assets/emails/investment-guide.png') }}" class="cover-image" alt="" style="max-height:400px">
            </div>
            <div class="w-100 d-flex justify-content-center">
                <p class="action-message" style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center" align="center">Unlock your financial potential with exclusive access to <br> a new GIC investment
                    from <span class="text-capitalize" style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal"> {{ $bankname }}</span></p>
            </div>
    
    
            @foreach ($campaign->campaignProducts as $product)
                @if (hasLockoutPeriod($product->product->productType->description))
                    <div class="w-100 d-flex justify-content-center my-2 campaign-status-text " style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center" align="center">
                        {{ $product->product->lockout_period . ' Days ' . $product->product->productType->description }}
                        GIC
                    </div>
                @else
                    <div class="w-100 d-flex justify-content-center my-2 campaign-status-text " style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center" align="center">
                        {{ $product->product->productType->description }} GIC
                    </div>
                @endif
                <div class="w-100 " style="width: 65%; margin:0 auto;;">
                    <table class="custom-table w-100 table table-hover" style="width:100%;border-collapse :collapse;margin:0 auto !important; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:12px; padding:10px">
                        <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                            <tr>
                                <th style="text-align:start;padding:0.6rem; border-bottom:3px solid white; border-top:none">Term Length</th>
                                <th style="text-align:start;padding:0.6rem; border-bottom:3px solid white; border-top:none">Minimum Deposit</th>
                                <th style="text-align:start;padding:0.6rem; border-bottom:3px solid white; border-top:none">Offer Expiry</th>
                            </tr>
                        </thead>
                        <tbody style="background-color:#F4F5F6; color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:700; line-height:normal; margin-top:30px" bgcolor="#F4F5F6">
                            @if ($product->product != null)
                                <tr>
                                    <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none"> {{ $product->product->term_length . ' ' . ucfirst(strtolower($product->product->term_length_type)) }}
                                    </td>
                                    <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">CAD {{ number_format($product->minimum) }}</td>
                                    <td class="text-capitalize" style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $campaign->expiry_date }} </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endforeach
    
            
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="center" style="border-bottom:3px solid white; border-top:none">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center" bgcolor="#ffffff"
                                    style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                    <a href="{{ url('/inv-camp-offers') }}"
                                        style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                        Explore Investment
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="w-100 d-flex justify-content-center">
                <p class="discover-login" style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center" align="center">Curious about high potential GIC investments?<a href="{{ url('request-an-account')}}"><span style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign Up </span></a> Or
                    <a href="{{ url('login')}}"> <span style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline"> Log In</span></a> to explore <br> opportunities for
                    growth in a new world of financial possibilities!
                </p>
            </div>
        </div>
    @endsection
@extends('emails.new-master')
    @section('page-content')

    <div>
            
            <div style="width: 100%; text-align: center; margin-top: 20px;">
                <div
                    style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                    <img src="{{ asset('assets/emails/book-v2.png') }}" alt=""
                        style="vertical-align: middle; margin-right:8px">
                    <span
                        style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                        Deposit Status
                    </span>
                </div>
            </div>
            <div class="w-100 d-flex justify-content-center my-2 campaign-status-text " style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center" align="center">
                Deposits has been started
            </div>
            <div class="w-100 d-flex justify-content-center">
                <img src="{{asset('assets/emails/deposit-started.png')}}" class="cover-image" alt="" style="max-height:400px">
            </div>
            <div class="w-100 d-flex justify-content-center">
                <p class="action-message" style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center" align="center">You have selected the following offers for <br>
                    <span style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal">
                        @if (hasLockoutPeriod($offersselected[0]['product_description']))
                            {{ $offersselected[0]['lockout_period'] . ' Days ' . $offersselected[0]['product_description'] }}
                        @else
                            {{ $offersselected[0]['product_description'] }}
                        @endif
                    </span>
                </p>
            </div>
            {{-- {{ json_encode($offersselected) }} --}}
    
            {{-- <div class="w-100 d-flex justify-content-center">
                <p class="suggest-action" style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal">Here's what you can do to next...</p>
            </div> --}}
            <div class="w-100  "style="width:100%">
                <table class="custom-table w-100 table table-hover" style=" font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; border-collapse :collapse; padding:10px">
                    <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                        <tr>
                            <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none"></th>
                            <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">Institution </th>
                            <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">Offered Amount</th>
                            <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">Rate</th>
                            <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">Date Of Deposit</th>
                            {{-- <th style="text-align:start; padding:0.6rem;padding:0.6rem; border-bottom:3px solid white; border-top:none">Institution</th> --}}
                        </tr>
                    </thead>
                    <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
    
                        @for ($i = 0; $i < count($offersselected); $i++)
                        <tr>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $i + 1 }}</td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $offersselected[$i]['fi_name'] }}</td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">CAD {{ $offersselected[$i]['offered_amount'] }}</td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $offersselected[$i]['interest_rate'] }}%</td>
                            <td style="text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $offersselected[$i]['date_of_deposit'] }}</td>
                        </tr>
                    @endfor
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
                                    <a href="{{url('/login')}}"
                                        style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                        Explore Our GICs
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
            <div class="w-100 d-flex justify-content-center">
                <p class="discover-login" style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center" align="center">Discover a world of exclusive GIC’s waiting for you <a href="{{ url('request-an-account')}}"><span style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign Up </span></a> Or
                    <a href="{{ url('login')}}"> <span style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline"> Log In</span></a>
                </p>
            </div>
        </div>
    @endsection
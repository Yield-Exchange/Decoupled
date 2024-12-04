@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%;margin-right:auto;margin-left:auto" class="responsive">
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
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px;text-align:center"
            width="100%">
            {{ $depositororganizationname }} has selected a rate.
        </div>

        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/Site-Stats-cuate.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>
       


        <p style="color:  #252525;text-align: center;font-family: Montserrat;font-size: 20px;font-style: normal;font-weight: 400; line-height: 26px;">GIC Information</p>


        <div class="w-100  " style="width:100%" width="100%">
            <table class="custom-table w-100 table table-hover"
                style="width:100%; border-collapse:collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:5px auto; padding:10px"
                width="100%">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                    <tr>
                        <th class="campaign-status-text-2"
                            style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                            align="start">Institution</th>
                        <th class="campaign-status-text-2"
                            style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                            align="start">Product</th>
                        <th class="campaign-status-text-2"
                            style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                            align="start">Interest Rate</th>
                        <th class="campaign-status-text-2"
                            style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                            align="start">Deposit Amount</th>
                        <th class="campaign-status-text-2"
                            style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                            align="start">Date of Deposit</th>
                    </tr>
                </thead>
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                    {{-- {{json_encode($offersselected)}} --}}
                    @for ($i = 0; $i < count($offersselected); $i++ )
                        <tr>
                            <td class="bold-txt"
                                style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $offersselected[$i]['fi_name'] }}</td>
                            @if ($offersselected[$i]['lockout_period'] != 0)
                            <td class="bold-txt"
                                style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $offersselected[$i]['lockout_period'] . ' Days' . $offersselected[$i]['product_description'] }} </td>
                            @else
                            <td class="bold-txt"
                                style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $offersselected[$i]['product_description'] }} </td>
                            @endif
                            <td class="bold-txt"
                                style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $offersselected[$i]['interest_rate'] }} % </td>
                            <td class="bold-txt"
                                style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                                align="start">CAD {{ current(explode(' ', $offersselected[$i]['offered_amount'])) }} </td>
                            <td class="bold-txt"
                                style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $offersselected[$i]['date_of_deposit'] }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>



        {{-- <div
            style="display: inline-block; justify-content: flex-start; align-items: center; background: #F4F5F6; font-family: Montserrat; font-size: 16px; font-style: normal; line-height: normal; margin: 1px; margin-top: 10px; padding: 15px; text-align: center; width: 100%;">
            <p
                style="margin: 0; padding: 0; color: #252525; font-weight: 300; display: flex; align-items: center; text-align: start; padding-left: 20px;">
                <img src="{{ asset('assets/emails/ri_mail-add-fill.png') }}" style="padding: 0; margin: 0;"
                    height="25">   
                <span style="margin-left: 10px;">Please contact the depositor to initiate the next steps.</span>
            </p>
        </div>
        
        <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
            <p class="discover-login"
                style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">If this is a new customer you can initiate the account onboarding process now.
            </p>
        </div> --}}
       
        {{-- <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('active-deposits') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    View Pending Deposits
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
                    href="https://app.yieldexchange.ca/request-an-account"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="https://app.yieldexchange.ca/login"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div> --}}
    </div>
@endsection

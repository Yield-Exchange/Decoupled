@extends('emails.new-master')
@section('page-content')
    <div style="padding:0 5%; margin-right:auto; margin-left:auto;" class="responsive">
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
            style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Here are today’s top rates!
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/discover-gics.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>

        <div class="w-100 d-flex justify-content-center">
            <p class="action-message"
                style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">These are the rates currently available from our financial institution partners.</p>
        </div>
    

        <div class="w-100">
            <table class="custom-table w-100 table table-hover" style=" width:100%; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:0 auto; border-collapse :collapse; padding:10px">
                <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                    @foreach ($products as $gic_item)
                        <tr>
                            <td style="font-size:39px; font-style:normal; font-weight:700; line-height:normal ;color :#5063F4;text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $gic_item->rate }}%</td>
                            @if (hasLockoutPeriod($gic_item->description))
                            <td style="color:#252525; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal;text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none"> {{ $gic_item->lockout_period . ' Days ' . $gic_item->description }}</td>
                            @else
                            <td style="color:#252525; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal;text-align:start;text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none"> {{$gic_item->description}}</td>
                            @endif
                            <td style="color:#252525; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal;text-align:start;text-align:start; padding:0.6rem;border-bottom:3px solid white; border-top:none">{{ $gic_item->fi_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> 

        <div class="w-100 d-flex justify-content-center">
            <p class="action-message"
                style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Login today to get this rate, or submit a request based on your own investment needs!</p>
        </div>


        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('/inv-camp-offers') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    Login Now
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>


        <div class="w-100 d-flex justify-content-center">
            <p class="discover-login"
                style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">Discover a world of exclusive GIC’s waiting for you <a
                    href="{{ url('/request-an-account') }}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{ url('/login') }}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
        <div>
            <p style="color: #000;text-align: center;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 300;line-height: normal;">
            Opt out of receiving marketing emails?
            <a href="{{ url('/unsubscribe/discover-gic/' . $user_id . '/' . $email) }}" style="color:#5063F4;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 700;line-height: normal;text-decoration-line: underline;">Unsubscribe</a></p>
        </div>
    </div>
@endsection

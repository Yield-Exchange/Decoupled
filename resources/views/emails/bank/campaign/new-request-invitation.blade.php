@extends('emails.new-master')
@section('page-content')
    <div style="padding:0.5%;margin-right:auto; margin-left:auto;" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/book-v2.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    GIC Deposit
                </span>
            </div>
        </div>
        <div style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            New Deposit Request
        </div>
        <div>
            <img src="{{ asset('assets/emails/new-request-invitation.png') }}" alt="" style="max-height:400px">
        </div>
        <div>
            <p style="font-family:Montserrat; font-size:32px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center"><span
                    style="font-family:Montserrat; font-size:32px; font-style:normal; font-weight:700; line-height:normal; text-align:center"
                    align="center">{{ $newpostrequestdetails['depositor']->name }}</span> has invited you
                to a deposit of <span
                    style="font-family:Montserrat; font-size:32px; font-style:normal; font-weight:700; line-height:normal; text-align:center"
                    align="center"> {{ $newpostrequestdetails['deposit']->currency }}
                    {{ number_format($newpostrequestdetails['deposit']->amount) }}. </span>
            </p>
        </div>
        <div>
            <p style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">If you are interested in putting in an offer, please respond before
                {{ $newpostrequestdetails['closing_date'] }}
            </p>
        </div>



        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('/') }}/place-offer/{{ \App\CustomEncoder::urlValueEncrypt($newpostrequestdetails['deposit']->id) }}?fromPage=new-requests"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    View Request
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
                    href="h{{ url('request-an-account')}}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{url('login')}}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
    </div>
    </div>
@endsection

@extends('emails.new-master')
@section('page-content')
<div style="padding: 0 5%;margin-right:auto;margin-left:auto" class="responsive">
    <div style="width: 100%; text-align: center; margin-top: 20px;">
        <div
            style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
            <img src="{{ asset('assets/emails/book-v2.png') }}" alt="" style="vertical-align: middle; margin-right:8px">
            <span
                style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                Investment Guide
            </span>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
        style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px;text-align:center"
        width="100%">
        @if ($depositDetails['fourtyeighty'])
        Trades Expiring Soon.
        @else
        Update Dummy Basket.
        @endif
       
    </div>

    <div style="width: 100%; text-align: center;">
        <img src="{{ asset('assets/emails/Site-Stats-cuate.png') }}" class="cover-image" alt=""
            style="max-height: 400px; display: block; margin: 0 auto;">
    </div>


    @if ($depositDetails['fourtyeighty'])
    <div
    style="display:flex; flex-direction:row; gap:10px; justify-content:flex-start; align-items:center; color: #FF2E2E; margin-top:1rem; font-size: 14px; font-family: Montserrat; font-weight: 400; line-height: 16px; word-wrap: break-word">
    <img src="{{ asset('assets/emails/info2.png') }}" style="width: 30px; height:30px;" alt="">
    <p> Please update the dummy baskets for the following offers before they 
        <span style="color: #FF2E2E; font-size: 16px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
            expire in the next 48 hours.
        </span> 
    </p>
</div>

    @else
    <p
        style="color:  #252525;text-align: center;font-family: Montserrat;font-size: 20px;font-style: normal;font-weight: 400; line-height: 26px;">
        Please update the dummy baskets for the following offers to one of the provided Basket ID’s.</p>
    @endif







    <div class="w-100  " style="width:100%" width="100%">
        <table class="custom-table w-100 table table-hover"
            style="width:100%; border-collapse:collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:5px auto; padding:10px"
            width="100%">
            <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                <tr>

                    <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Offer ID</th>
                    <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Products</th>
                    <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Rate</th>
                    <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Settlement Date</th>

                </tr>
            </thead>
            <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                <?php 
                $thereIsdummy=false;    
                ?>

                @foreach ($depositDetails['depositDetails'] as $depositDetail)

                <tr>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        <a href="{{ url('/repos/cg-view-repo-pending-trade/' . $depositDetail->encoded_id) }}">
                            {{ $depositDetail->deposit_reference_no }}
                        </a>
                    </td>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{$depositDetail->CGOffer->offer_term_length}}
                        {{$depositDetail->CGOffer->offer_term_length_type}}
                        {{$depositDetail->CGOffer->product->product_name}}
                    </td>

                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{ number_format($depositDetail->CGOffer->offer_interest_rate, 2) }} %
                    </td>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{ changeDateFromUTCtoLocal($depositDetail->trade_date)}}
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
  

        <div>
            <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">
                <a href="{{ url('/login') }}"
                    style="color:white; text-decoration-line:none;width: 70px; padding:10px; height: 20px; padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 10px; background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; display: inline-flex">
                    Login
                </a>
            </p>
        </div>
    </div>

</div>
@endsection
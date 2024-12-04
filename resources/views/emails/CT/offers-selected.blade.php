@extends('emails.new-master')
@section('page-content')
<div style="padding: 0 5%;margin-right:auto;margin-left:auto" class="responsive">
    <div style="width: 100%; text-align: center; margin-top: 20px;">
        <div
            style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
            <img src="{{ asset('assets/emails/book-v2.png') }}" alt="" style="vertical-align: middle; margin-right:8px">
          
        </div>
    </div>
    <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
        style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px;text-align:center"
        width="100%">
       You have selected the following Trade Offers.
    </div>

    <div style="width: 100%; text-align: center;">
        <img src="{{ asset('assets/emails/Site-Stats-cuate.png') }}" class="cover-image" alt=""
            style="max-height: 400px; display: block; margin: 0 auto;">
    </div>



    <p
        style="color:  #252525;text-align: center;font-family: Montserrat;font-size: 20px;font-style: normal;font-weight: 400; line-height: 26px;">
        Trade Information</p>


    <div class="w-100  " style="width:100%" width="100%">
        <table class="custom-table w-100 table table-hover"
            style="width:100%; border-collapse:collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:5px auto; padding:10px"
            width="100%">
            <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE" bgcolor="#EFF2FE">
                <tr>
                    <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Collateral Giver</th>
                    <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Product</th>
                        <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Basket ID</th>
                    <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Rate</th>
                    <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Awarded Amount</th>
                    <th class="campaign-status-text-2"
                        style="padding:0.6rem; text-align:start; color:#5063F4; font-family:Montserrat; font-size:15px; font-style:normal; font-weight:700; line-height:42px; border-bottom:3px solid white; border-top:none"
                        align="start">Settlement Date</th>
                </tr>
            </thead>
            <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">

                @foreach ($selectedOffers['selected_offers'] as $offer)
                <tr>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                    align="start">
                    {{$offer->invitee->organization->name}}
                </td>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{$offer->product->product_name}}
                    </td>
                    @if($offer->basket==null && $offer->biColleteral!=null)
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
               
                        {{$offer->biColleteral->encoded_cusip_id }}
                    </td>
                    @endif
                    @if($offer->basket!=null && $offer->biColleteral==null)
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{  $offer->basket->encoded_basket_id }}
                    </td>
                    @endif    
                    @if($offer->basket==null && $offer->biColleteral==null)
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        N/A
                    </td>
                    @endif   
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{$offer->offer_interest_rate}} %
                    </td>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                       {{$selectedOffers['currency']}} {{ number_format($offer->CTdeposit->offered_amount) }}
                    </td>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{$offer->CTdeposit->trade_date}}
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <div>
            <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">
                <a href="{{ url('/login') }}" style="color:white; text-decoration-line:none;width: 70px; padding:10px; height: 20px; padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 10px; background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; display: inline-flex"> 
                     Login
                </a>
            </p>
        </div>
    </div>

</div>
@endsection
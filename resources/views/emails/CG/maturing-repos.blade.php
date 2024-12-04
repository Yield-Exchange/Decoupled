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
        width="100%">Repo Maturing</div>
    <div style="width: 100%; text-align: center;">
        <img src="{{ asset('assets/emails/deadlinegic.png') }}" class="cover-image" alt=""
            style="max-height: 400px; display: block; margin: 0 auto;">
    </div>  
    @if(sizeOf($depositDetails['depositDetails_fourty_eight'])>0)
 
    <p style="color:  #252525;text-align: center;font-family: Montserrat;font-size: 20px;font-style: normal;font-weight: 400; line-height: 26px;">
        The following Repos are going to be maturing in the next 24 Hrs.</p>
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
                        align="start">Maturity Date</th>

                </tr>
            </thead>
            <tbody style="background-color:#F4F5F6; margin-top:30px" bgcolor="#F4F5F6">
                <?php 
                $thereIsdummy=false;    
                ?>

                @foreach ($depositDetails['depositDetails_fourty_eight'] as $depositDetail)
             
                <tr>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        <a href="{{ url('/repos/cg-view-repo-pending-trade/' . $depositDetail->encoded_id) }}">
                            {{ $depositDetail->deposit_reference_no }}
                        </a>
                    </td>
                    <td style="padding:0.6rem; text-align:start;  font-family:Montserrat; font-size:16px;  word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{($depositDetail->CGOffer)?$depositDetail->CGOffer->offer_term_length:"-"}}
                        {{($depositDetail->CGOffer)?$depositDetail->CGOffer->offer_term_length_type:"-"}}
                        {{($depositDetail->CGOffer)?$depositDetail->CGOffer->product->product_name:"-"}}
                    </td>

                    <td style="padding:0.6rem; text-align:start;  font-family:Montserrat; font-size:16px; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{ ($depositDetail->CGOffer)? number_format($depositDetail->CGOffer->offer_interest_rate, 2):"-" }} %
                    </td>
                    <td style="padding:0.6rem; text-align:start; font-family:Montserrat; font-size:16px; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{ changeDateFromUTCtoLocal($depositDetail->maturity_date)}}
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>

    </div>
    @endif
    @if(sizeOf($depositDetails['depositDetails_seven'])>0)
    <p style="color:  #252525;text-align: center;font-family: Montserrat;font-size: 20px;font-style: normal;font-weight: 400; line-height: 26px;">
        The following Repos are going to be maturing in the next 7 Days.</p>
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

                @foreach ($depositDetails['depositDetails_seven'] as $depositDetail)

                <tr>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px; font-weight:700; word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        <a href="{{ url('/repos/cg-view-repo-pending-trade/' . $depositDetail->encoded_id) }}">
                            {{ $depositDetail->deposit_reference_no }}
                        </a>
                    </td>
                    <td style="padding:0.6rem; text-align:start; color:#252525; font-family:Montserrat; font-size:16px;  word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{($depositDetail->CGOffer)?$depositDetail->CGOffer->offer_term_length:"-"}}
                        {{($depositDetail->CGOffer)?$depositDetail->CGOffer->offer_term_length_type:"-"}}
                        {{($depositDetail->CGOffer)?$depositDetail->CGOffer->product->product_name:"-"}}
                    </td>

                    <td style="padding:0.6rem; text-align:start; font-family:Montserrat; font-size:16px;  word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{ ($depositDetail->CGOffer)? number_format($depositDetail->CGOffer->offer_interest_rate, 2):"-" }} %
                    </td>
                    <td style="padding:0.6rem; text-align:start; font-family:Montserrat; font-size:16px;  word-wrap:break-word; border-bottom:3px solid white; border-top:none"
                        align="start">
                        {{ changeDateFromUTCtoLocal($depositDetail->maturity_date)}}
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>


    </div>
    @endif

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
@endsection
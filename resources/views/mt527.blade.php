<!DOCTYPE html>
<html lang="en" style="box-sizing: border-box;font-family: sans-serif;line-height: 1.15;-webkit-text-size-adjust: 100%;-webkit-tap-highlight-color: transparent;">

<head style="box-sizing: border-box;">
    <meta charset="UTF-8" style="box-sizing: border-box;">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" style="box-sizing: border-box;">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" style="box-sizing: border-box;">


    <link rel="preconnect" href="https://fonts.googleapis.com" style="box-sizing: border-box;">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin style="box-sizing: border-box;">

    <title style="box-sizing: border-box;"></title>
</head>
<body>
<div style="display: flex; flex-direction:column">
   <div style="display: flex;  padding: 0px 10px; align-items: center;">
      <img src="{{public_path('images/logo_yield.png')}}" width="153px" height="23px; margin-top: -10px">
      <p style="color: #5063F4; text-align: right; font-feature-settings: 'liga' off, 'clig' off; font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 700;line-height: 12px; text-transform: capitalize;">MT527 - Triparty Collateral Instruction</p>
   </div>
      <div style="display: flex; justify-content:center; margin-top:15px">
        <table style="border-spacing: 0 10px">
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Start of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16R::GENL</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">GENERAL</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Page Number/Continuation Indicator</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">28E</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">00001/ONLY</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Sender’s Reference</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">20C::SEME</td>
                {{-- <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px"> -</td> --}}
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">{{$data->encoded_id}}</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Sender's Collateral Reference</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">20C::SCTR</td>
                {{-- <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px"> -</td> --}}
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">{{$data->c_g_organization->id}}</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Client’s Collateral Reference</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">20C::CLCI</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">-</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Function of the Message</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">23G</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">NEWM</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Execution Requested date</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">98A::EXRQ</td>
                {{-- <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px"> -</td> --}}
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">{{$data->CGOffer->trade_date}}</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Instruction Type Indicator</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">22H::CINT</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">INIT</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Exposure Type Indicator</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">22H::COLA</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">REPO</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Client Indicator</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">22H::REPR</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">PROV</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Start of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16R::COLLPRTY</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">COLLATERAL PARTY</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Party A</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">95P::PTYA</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">BICCODE1</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">End of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16S::COLLPRTY</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">COLLATERAL PARTY</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Start of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16R::COLLPRTY</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">COLLATERAL PARTY</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Party B</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">95P::PTYB</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">BICCODE2</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">End of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16S::COLLPRTY</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">COLLATERAL PARTY</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Start of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16R::COLLPRTY</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">COLLATERAL PARTY</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Triparty Agen</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">95P</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">TRAG/CEDE/ABCD/00GA012</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">End of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16S::COLLPRTY</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">COLLATERAL PARTY</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">End of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16S::GENL</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">GENERAL</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Start of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16R::DEALTRAN</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">TRANSACTIONAL DETAILS</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Closing Date</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">98A::TERM</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">20060217</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Transaction Amount</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">19A::TRAA</td>
                {{-- <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px"> -</td> --}}
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">{{$data->CGOffer->c_t_trade_request->currency}}{{$data->offered_amount}}</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Pricing Rate</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">92A::PRIC</td>
                {{-- <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px"> -</td> --}}
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">{{$data->CGOffer->offer_interest_rate}}</td>
            </tr>
            <tr style="background-color: #EFF2FE">
                <td class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">End of Block</td>
                <td class="header_data" style="color:#252525; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">16S::DEALTRAN</td>
                <td class="body_data" style="color:#5063F4; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">TRANSACTIONAL DETAILS</td>
            </tr>
        </table>
      </div>
</div>
<div style="display: flex; justify-content: space-between; margin:20px">
    <div class="footer-container" style="box-sizing:border-box; display:flex; justify-content:space-between; padding:10px; width:100%">
        <p class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">Page 1 of 1</p>
        <p class="description_data" style="color:#9CA1AA; font-family:Montserrat; font-size:11px; font-style:normal; font-weight:400; line-height:14px; padding:2px">{{ date('Y-m-d H:i:s T') }}</p>
    </div>
</div>

</body>
</html>
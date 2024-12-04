<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <title>Yield Exchange Inc | {{ $subject }}</title>
    <style type="text/css">
        @import url("https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200&display=swap");
        body{
            background: #FAFBFC;
        }
        #header-box{
            /*height:  70px;*/
             font-family: "Nunito Sans", sans-serif;
            background:  #1D212A;
            display: inline-block;
            text-align: center;
            /*padding-top: 20px;*/
            width: 100%;
            vertical-align: middle;
        }
        /*#header-box > img{*/
        /*    height: 35px;*/
        /*    margin-top: 10px;*/
        /*}*/
        #body-box{
            min-height: 600px;
            background: white;
        }
        #body-box > div > h4{
            margin-top: 30px;
            width: 100%;
            text-align: center;
             font-family: "Nunito Sans", sans-serif;
            font-size: 22px;
            font-style: bold;
            font-weight: 400;
            margin-bottom: 12px;
        }
        #content-div{
             font-family: "Nunito Sans", sans-serif;
            text-align: left;
            vertical-align: top;
            font-size: 16px;
            line-height: 24px;
            letter-spacing: 0em;
            padding-left: 24px;
            padding-right: 24px;
            font-weight: 300;
        }
        #buttons-div{
            width: 100%;
            margin-top: 20px;
            display: inline-block;
            text-align: center;
        }
        #buttons-div > a{
            border-radius: 36px;
            padding: 8px 32px 8px 32px;
            font-size: 16px;
            transition: transform .2s; /* Animation */
        }
        #button1{
            background: #3656A6;
            border-color: #3656A6;
            margin-left: 2%;
             font-family: "Nunito Sans", sans-serif;
            color: white;
            min-width: 178px;
        }
        #button2{
            border-color: #3656A6!important;
            color:#3656A6!important;
             font-family: "Nunito Sans", sans-serif;
            min-width: 178px;
        }
        #divider-line{
            margin-top: 50px;
            border-top: 1px solid #ccc;
            margin-left: 2%;
            margin-right: 2%;
            width: 96%;
        }
        #button2:hover{
            background: white;
            transform: scale(1.1);
        }
        #button1:hover{
            transform: scale(1.1);
        }
        #social-links-div, #footer-links-div{
            width: 100%;
            display: inline-block;
            text-align: center;
            padding-top: 10px;
        }
        #disclaimer-div{
             font-family: "Nunito Sans", sans-serif;
            text-align: center;
            vertical-align: top;
            font-size: 16px;
            line-height: 24px;
            letter-spacing: 0em;
            font-weight: 300;
            padding-left: 24px;
            padding-right: 24px;
        }
        #disclaimer-div > a, #disclaimer-div > span > a{
            color: #3656A6;
        }
        #disclaimer-div > span{
            font-size: 13px;
        }
        #social-links-div > a{
            color: #444;
        }
        #footer-links-div{
            display: inline-block;
            text-align: center;
        }
        #footer-links-div > a{
            font-size: 13px;
             font-family: "Nunito Sans", sans-serif;
            color: #3656A6;
            border-right: 1px solid #ccc;
            border-radius: 0;
            padding-top: 0;
            padding-bottom: 0;
            font-weight: bold;
        }
        #footer-links-div > a:last-child{
            border-right:0;
        }
        .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            width: 100%;
        }
        table{
            width: 100%;
        }
        #first-td{
            width: 20%;
        }
        #second-td{
            width: 60%;
        }
        #third-td{
            width: 20%;
        }
        @media (min-width: 992px){
            .col-lg-12 {
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        @media (min-width: 768px){
            .col-md-12 {
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        @media (min-width: 576px) {
            .col-sm-12 {
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        @media (max-width: 768px) {
            #body-box > div > h4{
                margin-top: 20px;
            }
            /*#header-box{*/
            /*    height:  50px;*/
            /*}*/
            /*#header-box > img{*/
            /*    height: 25px;*/
            /*    margin-top: 5px;*/
            /*}*/
            table{
                width: 100%;
            }
            #first-td{
                width: 10%;
            }
            #second-td{
                width: 80%;
            }
            #third-td{
                width: 10%;
            }
            #footer-links-div > a{
                font-size: 11px;
            }
            #body-box > div > #buttons-div > #button1{
                min-width: 150px;
                margin-bottom: 10px;
            }
            #body-box > div > #buttons-div > #button2{
                min-width: 150px;
                margin-bottom: 10px;
            }
        }
        .col-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .btn-group-lg>.btn, .btn-lg {
            padding: .5rem 1rem;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: .3rem;
        }
        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
        .btn-outline-info {
            color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        a{
            color: #007bff;
            text-decoration: none;
            background-color: transparent;
        }
        .btn-info {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
    </style>
</head>
<body>
<div class="row">
    <table>
        <tr>
            <td id="first-td"></td>
            <td id="second-td">
                <div class="col-sm-12 col-md-12 col-lg-12 col-12" id="body-wrapper">

                    @if ($logo_position=="top")
                    <div style="display:none;font-size:1px;color:#FAFBFC;line-height:1px;font-family:{font};max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;">
                       &nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;&nbsp;&#847;
                    </div>

                    <div class="row col-sm-12 col-md-12 col-lg-12 col-12" id="header-box">
                        <table style="width: 100%;border-collapse: collapse;">
                            <tbody>
                            <tr>
                                <td style="vertical-align: middle;height: 70px;">
                                    <img src="{{ url('/assets/images/logo_dark.png') }}" height="25" style="height: 25px"  alt="Yield Exchange Logo"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <div class="row col-sm-12 col-md-12 col-lg-12 col-12" id="body-box">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-12" style="width: 100%;background: white;">
                                <h4 style="width: 100%;text-align: center;">{{ $header }}</h4>

                                <div id="content-div">@yield('page_content')</div>

                                @php
                                $header = !$header ? $header : $subject;
                                @endphp

                                @if ( $show_login || $show_register || count($other_buttons) > 0 )
                                <div id="buttons-div">

                                    @if ($show_login && strtolower(trim($user_type))!='admin')
                                        <a href="{{url('/')}}/login?loginAs={{ strtolower(trim($user_type))=="bank" ? "fi" : "inv" }}" class="btn btn-outline-info btn-lg" id="button2">Login</a>
                                    @elseif ($show_login && strtolower(trim($user_type))=='admin')
                                    <a href="{{url('/yie-admin/login')}}" class="btn btn-outline-info btn-lg" id="button2">Login</a>
                                    @endif

                                    @if ($show_register)
                                        <a href="{{url('/')}}/signup" class="btn btn-info btn-lg" id="button1">Register</a>
                                    @endif

                                    @if ( count($other_buttons) > 0 )

                                    @php
                                    $count_n = count($other_buttons);
                                    $i=1;
                                    @endphp

                                    @foreach($other_buttons as $button)
                                        @if($count_n > 1 && $i==1 )
                                            <a href="{{ $button['link'] }}" style="background:white; color:blue;" class="btn btn-info btn-lg" id="button1">{{ $button['linkName'] }}</a>
                                        @else
                                            <a href="{{ $button['link'] }}" class="btn btn-info btn-lg" id="button1">{{ $button['linkName'] }}</a>
                                        @endif

                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach

                                    @endif
                                <div>
                                @endif

                                    <table style="width: 96%;border-collapse: collapse;">
                                        <tbody>
                                            <tr>
                                                <td style="border-bottom: 1px solid #ccc;height: 50px;">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>

    {{--                            <div id="divider-line"></div>--}}
                                @if ($logo_position=="bottom")
                                <div class="row col-sm-12 col-md-12 col-lg-12 col-12" id="header-box">
                                    <table style="width: 100%;border-collapse: collapse;">
                                        <tbody>
                                            <tr>
                                                <td style="vertical-align: middle;height: 70px;">
                                                    <img src="{{ url('/assets/images/logo_dark.png') }}" height="25" style="height: 25px"  alt="Yield Exchange Logo"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                @endif

                                <div id="social-links-div">
                                    <table style="width: 100%;border-collapse: collapse;">
                                        <tbody>
                                            <tr>
                                                <td style="vertical-align: middle;text-align: right;width: 45%">
                                                    {{-- <a class="btn btn-link" href="https://twitter.com/yieldexchange" style="color: #444"><img src="{{ url('/assets/images/icons/twitter-brands.png') }}" alt="Twitter" height="20" style="height: 20px"/> </a> --}}
                                                </td>
                                                <td style="vertical-align: middle;text-align: center;width: 10%">
                                                    <a class="btn btn-link" href="https://linkedin.com/company/ewob" style="color: #444"><img src="{{ url('/assets/images/icons/linkedin-brands.png') }}" alt="LinkedIn" height="20" style="height: 20px"/></a>
                                                </td>
                                                <td style="vertical-align: middle;text-align: left;width: 45%">
                                                    <a class="btn btn-link" href="mailto:info@yieldexchange.ca" style="color: #444"><img src=" {{ url('/assets/images/icons/envelope-solid.png') }}" alt="Email" height="20" style="height: 20px"/></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="disclaimer-div">
                                    Not sure why you received this email?<br> <a href="mailto:info@yieldexchange.ca"><b>Email</b></a> us or use our <a href="{{ url('/') }}"><b>Chat</b></a>.<br/><br/>
                                    <span>&copy; {{ date('Y') }} <a target="_blank" href="{{ url('/') }}"><b>Yield Exchange Inc</b></a></span>
                                </div>
                                <div id="footer-links-div">
                                    <a class="btn btn-link" target="_blank" href="{{ url('faq') }}"><b>FAQ</b></a>
                                </div>
                                <p style="color:white;opacity: 0">{{ time() }}</p> <!--Just to ensure the email is not trimmed as being repetitive-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td id="third-td"></td>
        </tr>
    </table>
</div>
</body>
</html>
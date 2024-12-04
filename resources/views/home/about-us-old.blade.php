@extends('home.master')
@section('page_title')
    About Us
@stop
@section('styles')
    <style>
        #banner{
            height: 742px;
            padding-top: 70px!important;
            padding-bottom: 20px!important;
            overflow: visible!important;
        }
        .card-shadow{
            border:none;
            border-radius: 5px;
            box-shadow: 0 3px 20px rgba(0, 0, 0,0.1);
            /*    box-shadow: [horizontal offset] [vertical offset] [blur radius] [optional spread radius] [color];*/
        }
        .card-center{
            margin-left: 5%;
            margin-right: 5%;
        }
        #banner .container{
            background: #fcfbfc;
            padding-right: 0;
            padding-left: 0;
        }
        #banner .container #row #card{
            position: relative;
            margin-top:20px;
            clear: both;
        }
        #banner .container #row{
            min-height: 800px!important;
            margin-bottom: 50px;
        }
        footer,#cpryt{
            display: none;
        }
        .card-body h3{
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 0;
            /* font-family: 'Montserrat'; */
        }
        .card-body p{
            font-size: 15px;
            /* font-family: 'Montserrat'; */
            font-weight: lighter;
            color: #777777;
        }
        .col-padding-remover{
            padding-left: 1.5%;
            padding-right: 1.5%;
        }
        .card-spacer{
            width: 100%;
            height: 20px;
        }
        .card-title{
            font-weight:bolder;
            text-align: center;
            font-size: 16px;
            /* font-family: 'Montserrat'; */
        }
        .card-title span{
            font-weight: normal;
            color: #777777;
        }
        .card-img-top{
            padding-left: 10%;
            padding-right: 10%;
            padding-top: 10%;
            width: 100%;
        }
        .card-people{
            /*width: 30%;*/
            display: inline-block;
            padding:0;
        }
        .card-people:first-child{
            margin-right: 4.5%;
        }
        .card-people:last-child {
            margin-left: 4.5%;
        }

        @media only screen and (max-width: 600px) {
            #row{
                width: 100%!important;
                /*margin-left: -3.8%!important;*/
            }
            .card,.col{
                width: 100%!important;
                flex-basis: unset;
            }
            .card-people{
                margin-bottom: 20px;
            }
            .card-people:last-child{
                margin-left: 0;
            }
            .card-people:first-child{
                margin-right: 0;
            }
        }
    </style>
@endsection
@section('page_content')
    <div id="banner">
        <span class="object object-1" style="left: 2%"></span>
        <span class="object object-2"></span>
        <span class="object object-3" style="left: 96%"></span>
        <span class="object object-4" style="left: 95%"></span>
        <span class="object object-5" style="left: 5%"></span>
        <span class="object object-9"></span>

        <div class="container">
            <div id="row">

                <div class="col-md-12 row">
                    <div class="card card-shadow card-center" id="card">
                        <div class="card-body" id="card-body">
                            <h3>About us</h3>
                            <p>Yield Exchange is transforming the traditional wholesale deposit negotiation process by directly
                                GIC wholesale depositors with Canadian Financial Institutions, via a digital platform solution.
                                Our platform is a secure, transparent, time and cost effective way to get the best GIC rates.
                            </p>
                            <p>
                                Yield Exchange is bringing the GIC deposit market into the digital era by enabling depositors and FIs
                                to negotiate investments openly and directly; eliminating the hidden and unnecessary the two sides are
                                currently incurring.
                            </p>
                            <p>
                                Yield Exchange is a way to feel confident that your rates are valued by Canadian financial institutions and that you have
                                done your best for your employer.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-spacer"></div>
                <div class="col-padding-remover row">
                    <div class="card card-shadow card-center" style="width: 88%;">
                        <div class="card-body">
                            <h3>Leadership Team</h3>
                        </div>
                    </div>
                </div>

                <div class="card-spacer"></div>
                <div class="col-md-12 col-padding-remover row">
                    <div class="card-center row">
                        <div class="card card-people card-shadow col">
                            <img class="card-img-top" src="{{ asset('images/coo.jpg') }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Yvette Wu<br> Chief Executive Officer</h5>
                                <p class="card-text">Being a leader; rolled out Vancity's enterprise risk department. Strategic management consultant
                                    for mid-large size brands including Best Buy, Aritzia, Sony Image Works, etc.</p>
                            </div>
                        </div>

                        <div class="card card-people card-shadow col">
                            <img class="card-img-top" src="{{ asset('images/coo.jpg') }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Ravi Sumal<br> Chief Operating Officer</h5>
                                <p class="card-text">Being a leader; rolled out Vancity's enterprise risk department. Strategic management consultant
                                    for mid-large size brands including Best Buy, Aritzia, Sony Image Works, etc.</p>
                            </div>
                        </div>

                        <div class="card card-people card-shadow col">
                            <img class="card-img-top" src="{{ asset('images/coo.jpg') }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Sampath Ekanayake<br> Chief Product Officer</h5>
                                <p class="card-text">Being a leader; rolled out Vancity's enterprise risk department. Strategic management consultant
                                    for mid-large size brands including Best Buy, Aritzia, Sony Image Works, etc.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-spacer"></div>
                <div class="col-md-12 col-padding-remover row">
                    <div class="card card-shadow card-center" style="width: 100%;">
                        <div class="card-body">
                            <h3>Advisors</h3>
                        </div>
                    </div>
                </div>

                <div class="card-spacer"></div>
                <div class="col-md-12 col-padding-remover row">
                    <div class="card-center row">
                        <div class="card card-people card-shadow col">
                            <img class="card-img-top" src="{{ asset('images/coo.jpg') }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Heather Kaart<br> <span>IT Banking Leader</span></h5>
                            </div>
                        </div>

                        <div class="card card-people card-shadow col">
                            <img class="card-img-top" src="{{ asset('images/coo.jpg') }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Glen Lougheed<br> <span>Tech entrepreneur</span></h5>
                            </div>
                        </div>

                        <div class="card card-people card-shadow col">
                            <img class="card-img-top" src="{{ asset('images/coo.jpg') }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Jameel Sayani<br> <span>Regional MNP Partner</span></h5>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-spacer"></div>
                <div class="col-md-12 col-padding-remover row">
                    <div class="card-center row" style="text-align: center">
                        <div class="col" style="margin-left:4.5%"></div>
                        <div class="card card-people card-shadow col">
                            <img class="card-img-top" src="{{ asset('images/coo.jpg') }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Monique Morden<br> <span>Founder JUDI.AI</span></h5>
                            </div>
                        </div>
                        <div class="col"></div>
                    </div>
                </div>

                <div class="card-spacer"></div>

            </div>
        </div>
    </div>
@endsection
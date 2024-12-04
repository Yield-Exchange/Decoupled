@extends('home.master')
@section('page_title')
    Blogs
@stop
@section('styles')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <style>
        .header-bar-area{
            background: url('/assets/img/banner-bg.png') no-repeat;
            padding: 25px 0 10px;
            background-size: cover;
        }
        .row{
            --bs-gutter-x:0!important;
        }
        .center-vertically {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #row{
            width: 100%!important;
        }
        body{
            background: #FCFBFC!important;
        }
        #banner{
            height: 742px;
            padding-top: 70px!important;
            padding-bottom: 20px!important;
            overflow: visible!important;
            background: #FCFBFC!important;
        }
        .card-shadow{
            border:none;
            border-radius: 5px;
            box-shadow: 0px 4px 32px #EAEAEA;
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
        /*.col-padding-remover{*/
        /*    padding-left: 1.5%;*/
        /*    padding-right: 1.5%;*/
        /*}*/
        .card-spacer{
            width: 100%;
            height: 20px;
        }
        .card-title{
            /*padding-top:5%;*/
            font-weight:600;
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
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
            background:white;
            /*width: 92%;*/
            /*display: block;*/
            padding:0;
            margin-bottom: 20px;
            /*padding-top: 20px;*/
            padding-bottom: 10px;
        }
        /*.card-people:first-child{*/
        /*    margin-right: 4.5%;*/
        /*}*/
        /*.card-people:last-child {*/
        /*    margin-left: 4.5%;*/
        /*}*/

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

        #row{
            /* margin-top: 10px; */
        }
    </style>
@stop
@section('page_content')

    <div id="banner">
{{--        <span class="object object-1" style="left: 2%"></span>--}}
{{--        <span class="object object-2"></span>--}}
{{--        <span class="object object-3" style="left: 96%"></span>--}}
{{--        <span class="object object-4" style="left: 95%"></span>--}}
{{--        <span class="object object-5" style="left: 5%"></span>--}}
{{--        <span class="object object-9"></span>--}}

        <div class="container" id="VueApp">
            <div id="row">
                <list-blogs
                    blogs="{{ json_encode($blogs) }}"
                    categories="{{ json_encode($categories) }}"
                    tags="{{ json_encode($tags) }}"
                    base_path="{{ url('/') }}"
                >
                </list-blogs>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endsection
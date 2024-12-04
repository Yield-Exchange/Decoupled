<!DOCTYPE html>
<html lang="en">

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--====== Title ======-->
    <title>Yield Exchange | @yield('page_title')</title>
    <meta name="description"
        content="{{ !empty($meta_description) ? $meta_description : 'Yield Exchange is disrupting the traditional wholesale deposit negotiation process with a digital solution that enables Wholesale Depositors to connect directly with Canadian Financial Institutions. Our platform offers a secure, transparent, efficient and low cost solution to negotiate GIC rates with multiple financial institutions - without the cost and time involved with using a middleman.We’re excited to offer a unique solution that allows the wholesale deposit market function as it should. Yield Exchange gives depositors the confidence to place funds at the going rate, while completing due diligence. For Financial Institutions, Yield Exchange enables you to be notified on all market activity so you can focus on requests that align with your liquidity needs.' }}" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('/assets/images/favicon.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700&display=swap" rel="stylesheet">

    <!--====== Bootstrap css ======-->
    <link href="{{ asset('/assets/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!--====== Animate css ======-->
    <link href="{{ asset('/assets/css/animate.min.css') }}" rel="stylesheet" />

    <!--====== Fontawesome css ======-->
    <!-- <link href="{{ asset('/assets/css/fontawesome.min.css') }}" rel="stylesheet" /> -->
    <link href="{{ asset('/assets/dashboard/css/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" />

    <!--====== Slick Slider css ======-->
    <link href="{{ asset('/assets/css/slick.css') }}" rel="stylesheet" />

    <!--====== Venobox popup css ======-->
    <link href="{{ asset('/assets/css/venobox.css') }}" rel="stylesheet" />

    <!--====== meanmenu css ======-->
    <link href="{{ asset('/assets/css/meanmenu.css') }}" rel="stylesheet" />

    <!--====== Style css ======-->
    <link href="{{ asset('/assets/css/style.css?v=1.0') }}" rel="stylesheet" />

    <!--====== Responsive css ======-->
    <link href="{{ asset('/assets/css/responsive.css') }}" rel="stylesheet" />

    <!--====== jquery js ======-->

    <style>
        .proloader {
            background: transparent !important;
        }

        .logo-area {
            max-width: 250px !important;
        }

        i :not(.fa, .icon*) {
            /*font-family: 'Montserrat', sans-serif !important;*/
        }

        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        a,
        button,
        input,
        textarea,
        label,
        span,
        .media-title {
            /*font-family: 'Montserrat', sans-serif !important;*/
        }

        .main-menu ul li a {
            font-size: 16px !important;
            margin: 0 10px !important;
        }

        .main-menu ul li:last-child a {
            padding: 10px 15px !important;
            margin-right: 0 !important;
        }

        #mobile-menu-active ul {
            padding-left: 0;
        }
    </style>
    @yield('styles')
</head>

<body>
    @include('sweet::alert')
    <!-- Preloader Start -->
    <div class="proloader" id="proloader">
        <div class="loader_34">
            <!-- Preloader Elements -->
            <div class="ytp-spinner">
                <div class="ytp-spinner-container">
                    <div class="ytp-spinner-rotator">
                        <!-- Preloader Container Left Begin -->
                        <div class="ytp-spinner-left">
                            <!-- Preloader Body Left -->
                            <div class="ytp-spinner-circle"></div>
                        </div>
                        <!-- Preloader Container Left End -->

                        <!-- Preloader Container Right Begin -->
                        <div class="ytp-spinner-right">
                            <!-- Preloader Body Right -->
                            <div class="ytp-spinner-circle"></div>
                        </div>
                        <!-- Preloader Container Right End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader End -->

    <!-- Header bar area Start -->
    @if (!Request::is('login') && !Request::is('yie-admin') && !Request::is('yie-admin/login'))
        <div class="header-bar-area" style="background: #FCFBFC">
            <div class="container" style="padding-left: 0;padding-right: 0">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12">
                        <div class="logo-area">
                            <a href="https://yieldexchange.ca"><img src="{{ asset('/assets/images/logo_light.png') }}"
                                    alt="Logo" style="width: 100%;" /></a>
                        </div>
                        <div class="menu-prepent"></div>
                    </div>
                    <div class="col-lg-8 text-right">
                        <div class="main-menu">
                            <nav id="mobile-menu-active">
                                <ul>
                                    <li class="{{ Request::is('/') ? 'active' : '' }}"><a
                                            @if (!app()->environment('production')) target="_blank" @endif
                                            href="https://yieldexchange.ca" style="color: #628BF2">HOME</a></li>
                                    {{--                            <li class="{{ Request::is('/#eowb') ? 'active': '' }}"><a href="{{ url('/') }}#eowb">Why us</a></li> --}}
                                    {{--                            <li class="{{ Request::is('/#how') ? 'active': '' }}"><a href="{{ url('/') }}#how">How it works</a></li> --}}
                                    {{--                            <li class="{{ Request::is('about-us') ? 'active': '' }}"><a href="{{ url('/about-us') }}">Our Team</a></li> --}}
                                    {{--                            <li class="{{ Request::is(['blogs','blog*']) ? 'active': '' }}"><a href="{{ url('/blogs') }}">Blogs</a></li> --}}
                                    {{--                            <li class="{{ Request::is('/#contact') ? 'active': '' }}"><a href="{{ url('/') }}#contact">Contact us</a></li> --}}
                                    @if (Auth::check())
                                        <li><a href="{{ url(auth()->user()->is_super_admin ? '/yie-admin/dashboard' : '/dashboard') }}"
                                                style="color: #628BF2">DASHBOARD</a></li>
                                    @else
                                        <li class="{{ Request::is('/login') ? 'active' : '' }}"><a
                                                href="{{ url('/login') }}" style="color: #628BF2">Sign In</a></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Header bar area End -->

    @yield('page_content')

    <!-- ====== Footer Part HTML Start ====== -->

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-4"></div>
                <div class="col-lg-3 col-sm-3">
                    <div class="footer-item first-footer">
                        <a href="{{ url('/') }}" class="footer-logo">
                            <img src="{{ asset('/assets/images/logo_light.png') }}" alt="Logo"
                                style="width: 100%" />
                        </a>
                        <ul>
                            <li><a href="mailTo:info@yieldexchange.ca">info@yieldexchange.ca</a></li>
                            <!-- <li><a href="tel:+1 778 918 7735">+1 778 918 7735</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <section id="cpryt">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>{{ date('Y') }} Yield Exchange Inc.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Start of HubSpot Embed Code -->
    <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/7987108.js"></script>
    <!-- End of HubSpot Embed Code -->

    <!-- ====== Footer Part HTML End ====== -->


    <button type="button" id="hs_show_banner_button"
        style="background-color: #425b76; border: 1px solid #425b76;border-radius: 3px;
 padding: 10px 16px; text-decoration: none; color: #fff; font-family: inherit;
  font-size: inherit; font-weight: normal; line-height: inherit;text-align: left;
   text-shadow: none;"
        onClick="(function(){var _hsp = window._hsp = window._hsp || []; _hsp.push(['showBanner']); })()">
        Cookie Settings
    </button>
    <script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GC56JV3V79"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-GC56JV3V79');
    </script>
    <!-- Start of HubSpot Embed Code -->
    <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/7987108.js"></script>
    <!-- End of HubSpot Embed Code -->

    <!--====== Popper js ======-->
    <script src="{{ asset('/assets/js/Popper.js') }}"></script>

    <!--====== Bootstrap js ======-->
    <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>

    <!--====== Jquery easing js ======-->
    <script src="{{ asset('/assets/js/jquery.easing.min.js') }}"></script>

    <!--====== Slick Slider js ======-->
    <script src="{{ asset('/assets/js/slick.min.js') }}"></script>

    <!--====== meanmenu js ======-->
    <script src="{{ asset('/assets/js/venobox.min.js') }}"></script>

    <!--====== Venobox popup js ======-->
    <script src="{{ asset('/assets/js/jquery.meanmenu.js') }}"></script>

    <!--====== Main js ======-->

    <script src="{{ asset('/assets/js/main.js?v=1.1') }}"></script>
    <script src="{{ asset('/assets/js/custom.js?v=1.1') }}"></script>
    <script>
        window.addEventListener("pageshow", function(event) {
            const urlParams = new URLSearchParams(location.search);

            if ((location.pathname.includes("login") || location.pathname.includes("yie-admin")) &&
                !urlParams.has("action") && urlParams.get("action") != "verifyOTP") {
                localStorage.removeItem('ip_address');
                // alert('executed');
            }
            let historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                // Handle page restore.
                window.location.reload();
            }
        });
    </script>
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && event.target.classList.contains('reagan--widget-loaded')) {
                event.preventDefault();
            }
        });
        // document.addEventListener('keydown', function(event) {
        //     // Check if the pressed key is Enter
        //     if (event.key === 'Enter') {
        //         // Check if the target element has the specified class
        //         if (event.target.classList.contains('chat-widget-launcher')) {
        //             // Prevent the default action
        //             event.preventDefault();
        //         }
        //     }
        // });
    </script>
    @yield('scripts')

</body>

</html>

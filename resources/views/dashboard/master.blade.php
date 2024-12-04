<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Yield Exchange | @yield('page_title')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/assets/images/favicon.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700&display=swap" rel="stylesheet" />
    <link href="{{ asset('/assets/global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/assets/dashboard/css/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/dashboard/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/dashboard/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/dashboard/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/dashboard/css/components.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/dashboard/css/colors.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/version-2.css') }}" rel="stylesheet" type="text/css" />

    <!-- /global stylesheets -->
    <link href="{{ asset('/assets/global_assets/css/icons/material/styles.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/assets/css/sweetalert.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/dashboard/css/custom.css?v=1.2') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/bootstrap-toggle.min.css') }}" rel="stylesheet" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    

    <!--End of Tawk.to Script-->

    <link href="{{ asset('/assets/css/style.css?v=1.8') }}" rel="stylesheet" />
    @yield('styles')
    <style>
        .alert-warning {
            color: #856404;
            /*color:#664d03;*/
            background-color: #fff3cd;
            border-color: #ffeeba;
            letter-spacing: -.015em;
            /*font-size: 1.0625rem;*/
            font-size: 1.0rem;
            margin-bottom: .625rem;
            font-weight: 400;
            line-height: 1.5385;
            margin-top: 0;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        #VueApp {
            display: flex;
            justify-content: center;
        }

        #VueApp div {
            max-width: 1920px !important;
        }
    </style>
</head>

<body class="dashboard-body ">
    @php
        $user_data = $user = auth()->user();
        $blurred = false;
        $organization = $user_data->organization;
    @endphp
    @if (
        $organization &&
            $organization->type == 'BANK' &&
            ($organization->admin && $organization->admin->password_changed == 1) &&
            $organization->is_non_partnered_fi == 1)
        @if (Request::is(\App\Data\BankData::$pages_restricted_for_non_invited_fi))
            @include('auth.blur-page')
            @php
                $blurred = true;
            @endphp
        @endif
    @endif
    <div id="cover-spin"></div>
    <!-- Page content -->
    <div class="page-content">
        @php
            function highlightNavigation($routes)
            {
                $fromPage = Request::get('fromPage') ? Request::get('fromPage') : null;
                if ($fromPage) {
                    if ((is_array($routes) && in_array($fromPage, $routes)) || $routes == $fromPage) {
                        return true;
                    }
                }
                return Request::is($routes);
            }
            $user_type = $user_data;
        @endphp

        <style>
            .check_chat_badge {
                display: none;
            }
        </style>

        <!-- Main sidebar -->
        <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
            <!-- Sidebar mobile toggler -->
            <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-main-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                Navigation
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <!-- /sidebar mobile toggler -->
            <!-- Sidebar content -->
            <div class="sidebar-content">
                @include('dashboard.side-bar-top')

                <!-- Main navigation -->
                <div class="card card-sidebar-mobile" style="background: url('/assets/dashboard/images/vector.png')">
                    @if ($organization && ($organization->type == 'BANK' || $organization->type == 'DEPOSITOR'))
                        <div class="mx-3" id="switch-organization-dropdown">
                            <select class="custom-select" name="" style="border-radius: 9px" id="switch-select">
                                <option value="">{{ $organization->name }}</option>
                            </select>
                        </div>
                    @endif


                    @if ($organization)
                        @switch ($organization->type)
                            @case ('BANK')
                                {{--                @includeWhen(
                                    $organization->requires_to_confirm_users_seats &&
                                        $user->role_name == 'Organization Administrator',
                                    'dashboard.confirm-organization-seats-sidebar') --}}
                                @include('dashboard.bank.sidebar')
                            @break

                            @case ('DEPOSITOR')
                                {{--                @includeWhen(
                                    $organization->requires_to_confirm_users_seats &&
                                        $user->role_name == 'Organization Administrator',
                                    'dashboard.confirm-organization-seats-sidebar') --}}
                                @include('dashboard.depositor.sidebar')
                            @break

                            @default
                                @includeWhen($user_type->is_super_admin, 'dashboard.admin.sidebar')
                            @break
                        @endswitch
                    @else
                        @includeWhen($user_type->is_super_admin, 'dashboard.admin.sidebar')
                    @endif

                </div>
                <!-- /main navigation -->

                <ul class="nav nav-sidebar nav-bottom">
                    <li class="nav-item">
                        <a href="https://yieldexchange.tawk.help/" target="_blank" class="nav-link">
                            <img src="{{ asset('assets/dashboard/icons/help.svg') }}"><span>FAQ</span>
                        </a>
                    </li>
                </ul>

            </div>
            <!-- /sidebar content -->
        </div>
        <!-- /main sidebar -->

        <!-- Main content -->
        <div class="content-wrapper">

            @include('dashboard.top-navbar')
            <!-- Content area -->
            <div class="content " style="background: var(--Page-Backgrounds, #F8FAFF);">
                @yield('page_content')
            </div>
            <!-- Footer -->
            <div class="navbar navbar-expand-lg navbar-light d-none">
                <div class="text-center d-lg-none w-100">
                    <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse"
                        data-target="#navbar-footer">
                        <i class="icon-unfold mr-2"></i>
                        Footer
                    </button>
                </div>

                <div class="navbar-collapse collapse" id="navbar-footer">
                    <span class="navbar-text">
                        All rights reserved by Yield Exchange
                    </span>
                </div>
            </div>
            <!-- /footer -->

        </div>
        <!-- /main content -->

    </div>
    <script src="{{ asset('/assets/js/jquery/3.4.1/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @include('dashboard.leave-page-modal')


    <!-- core JS files -->
    @includeWhen(can_switch_to_organizations($user_data), 'dashboard.new-switch-organization')
    @includeWhen(can_switch_to_organizations($user_data), 'dashboard.switch-organization')
    <script src="{{ asset('/assets/global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery/dataTables/jquery.dataTables.min.js') }}"></script>

    <!-- Theme JS files -->
    <script src="{{ asset('/assets/global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script src="{{ asset('/assets/dashboard/js/app.js') }}"></script>

    <!-- /theme JS files -->
    <script src="{{ asset('/assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dirty.js') }}"></script>
    <script src="{{ asset('/assets/js/custom.js?v=1.1') }}"></script>
    @include('sweet::alert')
   
    <script src="{{ asset('/assets/dashboard/js/custom.js?v=1.1') }}"></script>

    @if (!is_admin_route(request()) && app()->environment('production'))
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();
            (function() {
                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/5fd88696a8a254155ab37e0e/1epjlgc98';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
    @endif

    <script>
        /**
         * If browser back button was used, flush cache
         * This ensures that user will always see an accurate, up-to-date view based on their state
         */
        (function() {
            window.onpageshow = function(event) {
                if (event.persisted) {
                    window.location.reload();
                }
            };
        })();
    </script>

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap-toggle.min.js') }}"></script>
    <script>
        let user_object = @php
            $user = $user_data;
            echo json_encode([
                'id' => $user->id,
                'organization_id' => $organization ? $organization->id : 0,
                'name' => $user->name,
                'organization_name' => $organization ? $organization->name : 0,
        ]); @endphp
    </script>
    @yield('mix-scripts')
    <?php echo app('Tightenco\Ziggy\BladeRouteGenerator')->generate(); ?>
    @php
    // $apiKey = env('GOOGLE_MAPS_API_KEY');
    @endphp
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeo-2z3ogmd32YNCVLc_g51Sh9TQxf8Cg&libraries=places">
    </script>

   

    @yield('scripts')
    @include('dashboard.scripts')
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
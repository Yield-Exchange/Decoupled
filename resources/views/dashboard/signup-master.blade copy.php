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

    <!-- /global stylesheets -->
    <link href="{{ asset('/assets/global_assets/css/icons/material/styles.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/assets/css/sweetalert.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/dashboard/css/custom.css?v=1.2') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/bootstrap-toggle.min.css') }}" rel="stylesheet" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <script src="{{ asset('/assets/js/jquery/3.4.1/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/dashboard/js/custom.js?v=1.1') }}"></script>

    @if (!is_admin_route(request()))
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
    </style>
</head>

<body class="dashboard-body">
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
    <!-- Main navbar -->
    <div style="border:0" class="dashboard-navbar navbar navbar-expand-md navbar-dark">
        <div class="navbar-brand">
            <img src="{{ asset('assets/images/logo_light.png') }}" />
        </div>

        {{-- <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5" style="color:black"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3" style="color:black"></i>
        </button>
    </div> --}}

        <div class="collapse navbar-collapse" id="navbar-mobile"
            style="background-color:white;border:1px white solid;min-height:80px;">
            {{-- <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}"
                        class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                        <i class="icon-paragraph-justify3" style="color:black"></i>
                    </a>
                </li>
            </ul> --}}

            {{-- <span class="ml-md-3 mr-md-auto">
                <a style="float:right;background:white;" href="{{ url('/dashboard') }}" class="d-inline-block">
                    <img src="{{ asset('assets/images/logo_light.png') }}" style="height:40px;width:322px" />
                </a>
            </span> --}}

            <ul class="navbar-nav d-flex justify-content-between w-100 pl-2">
                @if ($user->userCan('universal/organization-setting/page-access'))
                    <li class="nav-item" style="margin-top: 10px">
                        <form method="post" class="timezone_switcher_form" action="#">
                            <select name="timezone" class="form-control switch_timezone" required>
                                <option value="">Select Timezone</option>
                                @php
                                    $timezones = timezonesList();
                                    $my_timezone = $user->timezone;
                                @endphp
                                @foreach ($timezones as $key => $timezone)
                                    <option {{ strcmp($my_timezone, $key) == 0 ? 'selected' : '' }}
                                        value="{{ $key }}">{{ $timezone }}</option>
                                @endforeach
                            </select>
                        </form>
                    </li>
                @endif
                <div class="d-flex">
                    @if (!is_admin_route(request()) && !$user_data->is_super_admin)
                        @if ($user->userCan('depositor/pending-deposits/page-access'))
                            <li class="nav-item dropdown" style="margin-top: 10px">
                                <a href="{{ $organization->type == 'DEPOSITOR' ? url('/pending-deposits') : url('/bank-pending-deposits') }}"
                                    class="navbar-nav-link nav-notifications" id="nav-chats">
                                    <i><img src="{{ asset('image/Group9.png') }}" style="height:15px;"></i>
                                    <span class="d-md-none ml-2">Messages</span>
                                    <chats-count :chats="chats">
                                    </chats-count>
                                </a>
                            </li>
                        @endif
                        @if ($user->userCan('universal/notifications/page-access'))
                            <li class="nav-item dropdown" style="margin-top: 10px">
                                <a href="{{ route('user.notifications') }}" class="navbar-nav-link nav-notifications"
                                    id="nav-notify">
                                    <i><img src="{{ asset('image/Group3.png') }}" style="height:18px;width:15px"></i>
                                    <span class="d-md-none ml-2">Notifications</span>
                                    <notify-count :notifications="notifications">
                                    </notify-count>
                                </a>
                            </li>
                        @endif
                    @endif
                    <li class="nav-item dropdown dropdown-user krishna">
                        <a href="#"
                            class="navbar-nav-link d-flex align-items-center dropdown-toggle no-page-exit-alert"
                            data-toggle="dropdown">
                            @if (!empty($organization->logo))
                                <img src="{{ url('image/' . $organization->logo) }}" class="rounded-circle mr-2"
                                    height="57" alt="" />
                            @else
                                <span
                                    class="i-initial-inverse"><span>{{ !empty($organization->name[0]) ? $organization->name[0] : 'Y' }}</span></span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-navbar">
                            @if (!is_admin_route(request()) && !$user_data->is_super_admin)
                                @if ($organization->is_non_partnered_fi != 1)
                                    <a href="javascript:void()" class="dropdown-item no-page-exit-alert">
                                        <i class="icon-volume-mute"></i> Email Notifications
                                        &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"
                                            {{ getUserPreference('mute_notification') == 0 ? 'checked' : '' }}
                                            data-toggle="toggle"
                                            class="pull-right update_notification_preference"></a>
                                @endif
                            @endif

                            @if (can_switch_to_organizations($user_data))
                                <a href="javascript:void()" data-toggle="modal"
                                    data-target="#switch-organization-modal"
                                    class="dropdown-item no-page-exit-alert"><i class="icon-reload-alt"></i>Switch
                                    Organization</a>
                            @endif
                            @if (!$user_data->is_super_admin && $user->userCan('universal/organization-setting/page-access'))
                                <a href="{{ url('account-setting') }}" class="dropdown-item"><i
                                        class="icon-users"></i>
                                    Organization Settings</a>
                            @endif
                            @if (!$user_data->is_super_admin && $user->userCan('universal/users/page-access'))
                                <a href="{{ route('users.index') }}" class="dropdown-item"><i
                                        class="icon-people"></i>
                                    Organization Users</a>
                            @endif
                            <a href="{{ !is_admin_route(request()) ? url('profile-setting') : url('yie-admin/profile-setting') }}"
                                class="dropdown-item"><i class="icon-user-plus"></i> Profile Settings</a>
                            @if (false /*$user->userCan('View-Reports') || $user->userCan('Export-Reports')*/)
                                @if (!is_admin_route(request()) && !$user_data->is_super_admin)
                                    <a href="{{ $organization->type == 'DEPOSITOR' ? url('depositor-reports') : url('bank-reports') }}"
                                        class="dropdown-item"><i class="icon-group"></i> Reports</a>
                                @endif
                            @endif
                            <div class="dropdown-divider"></div>
                            <a href="{{ url('logout') }}" class="dropdown-item no-page-exit-alert"><i
                                    class="icon-switch2"></i>Logout</a>
                        </div>
                    </li>
                </div>

            </ul>
        </div>
    </div>
    <!-- /main navbar -->

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
        @include('dashboard.signup-sidebar')

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">
                @yield('page_content')
            </div>
            <!-- Footer -->
            {{-- <div class="navbar navbar-expand-lg navbar-light">
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
            </div> --}}
            <!-- /footer -->

        </div>
        <!-- /main content -->

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- core JS files -->
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
        let user_object = @php$user = $user_data;
                                                            echo json_encode([
                                                                'id' => $user->id,
                                                                'organization_id' => $organization ? $organization->id : 0,
                                                                'name' => $user->name,
                                        ]); @endphp ?> ? > ? > ? > ;
    </script>
    @yield('mix-scripts')
    <?php echo app('Tightenco\Ziggy\BladeRouteGenerator')->generate(); ?>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')
    <script>
        //===== Prealoder
        // $(window).on('load', function (event) {
        //     $('#cover-spin').delay(100).fadeOut(100);
        // });

        $(document).on("change", ".switch_timezone", function() {
            makeApiCall("{{ url('update-timezone') }}", {
                'timezone': $(this).val(),
                '_token': "{{ csrf_token() }}"
            }, function(response) {
                @if (!$blurred)
                    if (response.success) {
                        try {
                            swal("Timezone update.", response.message).then(function() {
                                window.location.reload();
                            });
                        } catch (e) {
                            window.location.reload();
                        }
                    } else {
                        try {
                            swal("", response.message, "info");
                        } catch (e) {}
                    }
                @else
                    window.location.reload();
                @endif
            }, null, "POST", function(xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)) {
                    swal("An error occurred, the page will refresh.").then(() => {
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                    return;
                }

                @if (!$blurred)
                    swal("", apiCallServerErrorMessage(xhr, "Unable to update timezone, try again later"),
                        "error");
                @endif
            });
        });
    </script>
    <script>
        $(document).on("change", ".update_notification_preference", function() {
            makeApiCall("{{ url('update-preference') }}", {
                'preference': 'mute_notification',
                'preference_value': $(this).prop('checked') === true ? 1 : 0,
                '_token': "{{ csrf_token() }}"
            }, function(response) {
                swal("", response.message, "success");
            }, null, "POST", function(xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)) {
                    swal("An error occurred, the page will refresh.").then(() => {
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                    return;
                }

                swal("", apiCallServerErrorMessage(xhr,
                    "Unable to update the notifications setting, try again later"), "error");
            });
        });
    </script>
    <script>
        @if (is_admin_route(request()))
            $(document).on("click", ".admin-action-to-user-alert-update-credit-rating", function() {
                swal("", "Please update deposit and credit rating for the organization before approving", "");
                // swal({
                //     title: "",
                //     text: "Please update deposit and credit rating for the organization before approving",
                //     // icon: "warning",
                //     buttons: ["Ok"],
                // }).then((response) => {
                //     // DO  NOTHING
                // });
            });
            $(document).on("click", ".admin-action-to-user", function() {

                swal({
                    title: "",
                    text: "Are you sure to perform that action to the organization?",
                    // icon: "warning",
                    buttons: ["No", "Yes"],
                }).then((response) => {
                    if (response) {
                        let $loader1 = $("#cover-spin");
                        makeApiCall($(this).attr('href'), {
                            '_token': "{{ csrf_token() }}"
                        }, function(response) {
                            swal("", response.message, "success").then(() => {
                                window.location.reload();
                            });
                        }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                            if ([419].includes(xhr.status)) {
                                swal("An error occurred, the page will refresh.").then(() => {
                                    window.onbeforeunload = null;
                                    window.location.reload();
                                });
                                return;
                            }

                            swal("", apiCallServerErrorMessage(xhr,
                                "Unable to perform the action to the user, try again later"
                            ), "error");
                        });
                    }
                });

                return false;
            });
            $(document).on("submit", ".admin-close-user-form", function() {

                swal({
                    title: "",
                    text: "Are you sure to close this organization?",
                    // icon: "warning",
                    buttons: ["No", "Yes"],
                }).then((response) => {
                    if (response) {
                        let $loader1 = $("#cover-spin");
                        makeApiCall($(this).attr('action'), $(this).serializeArray(), function(response) {
                            swal("", response.message, "success").then(() => {
                                window.location.reload();
                            });
                        }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                            if ([419].includes(xhr.status)) {
                                swal("An error occurred, the page will refresh.").then(() => {
                                    window.onbeforeunload = null;
                                    window.location.reload();
                                });
                                return;
                            }

                            swal("", apiCallServerErrorMessage(xhr,
                                "Unable to perform the action to the user, try again later"
                            ), "error");
                        });
                    }
                });

                return false;
            });
            $(document).on("submit", ".admin-user-limit-form", function() {

                let $loader1 = $("#cover-spin");
                makeApiCall($(this).attr('action'), $(this).serializeArray(), function(response) {
                    swal("", response.message, "success").then(() => {
                        window.location.reload();
                    });
                }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                    if ([419].includes(xhr.status)) {
                        swal("An error occurred, the page will refresh.").then(() => {
                            window.onbeforeunload = null;
                            window.location.reload();
                        });
                        return;
                    }

                    swal("", apiCallServerErrorMessage(xhr,
                        "Unable to perform the action to the user, try again later"), "error");
                });

                return false;
            });
        @endif
        $(document).on("click", ".org-action-to-user", function() {

            swal({
                title: "",
                text: "Are you sure to perform that action to the user?",
                // icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    let $loader1 = $("#cover-spin");
                    makeApiCall($(this).attr('href'), {
                        '_token': "{{ csrf_token() }}"
                    }, function(response) {
                        swal("", response.message, "success").then(() => {
                            window.location.reload();
                        });
                    }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)) {
                            swal("An error occurred, the page will refresh.").then(() => {
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                            return;
                        }

                        swal("", apiCallServerErrorMessage(xhr,
                                "Unable to perform the action to the user, try again later"),
                            "error");
                    });
                }
            });

            return false;
        });
    </script>
</body>

</html>

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
            // var Tawk_API = Tawk_API || {},
            //     Tawk_LoadStart = new Date();
            // (function() {
            //     var s1 = document.createElement("script"),
            //         s0 = document.getElementsByTagName("script")[0];
            //     s1.async = true;
            //     s1.src = 'https://embed.tawk.to/5fd88696a8a254155ab37e0e/1epjlgc98';
            //     s1.charset = 'UTF-8';
            //     s1.setAttribute('crossorigin', '*');
            //     s0.parentNode.insertBefore(s1, s0);
            // })();
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

    <div id="cover-spin"></div>
    <!-- Main navbar -->
    {{-- <div style="border:0" class="dashboard-navbar navbar navbar-expand-md navbar-dark">
        <div class="navbar-brand">
            <img src="{{ asset('assets/images/logo_light.png') }}" />
        </div>


        <div class="collapse navbar-collapse" id="navbar-mobile"
            style="background-color:white;border:1px white solid;min-height:80px;">
        </div>
    </div> --}}
    <!-- /main navbar -->

    <!-- Page content -->
    <div class="page-content mt-0">
        {{-- @include('dashboard.signup-sidebar') --}}

        <!-- Main content -->
        <div class="content-wrapper mt-0">

            <!-- Content area -->
            <div class="content" style="padding: 0 !important;">
                @yield('page_content')
            </div>

        </div>
        <!-- /main content -->

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeo-2z3ogmd32YNCVLc_g51Sh9TQxf8Cg&libraries=places"></script>

    <!-- core JS files -->
    {{-- @includeWhen(can_switch_to_organizations($user_data), 'dashboard.switch-organization') --}}
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
             ]); @endphp;
    </script>
    @yield('mix-scripts')
    {{-- <?php echo app('Tightenco\Ziggy\BladeRouteGenerator')->generate(); ?> --}}
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')

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

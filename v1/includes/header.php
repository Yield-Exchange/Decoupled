<?php
    $current_file = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

    require_once "config/db.php";
    require_once "config/Model.php";
    require_once "config/AuthModel.php";
    require_once "config/AdminModel.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--====== Title ======-->
    <title>Yield Exchange <?php echo !empty($page_title) ? ' | '.$page_title : '';?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL.'/assets/images/favicon.png'?>"/>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700&display=swap" rel="stylesheet">

    <!--====== Bootstrap css ======-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!--====== Animate css ======-->
    <link href="assets/css/animate.min.css" rel="stylesheet">

    <!--====== Fontawesome css ======-->
    <link href="assets/css/fontawesome.min.css" rel="stylesheet">

    <!--====== Slick Slider css ======-->
    <link href="assets/css/slick.css" rel="stylesheet">

    <!--====== Venobox popup css ======-->
    <link href="assets/css/venobox.css" rel="stylesheet">

    <!--====== meanmenu css ======-->
    <link href="assets/css/meanmenu.css" rel="stylesheet">

    <!--====== Style css ======-->
    <link href="assets/css/style.css?v=1.0" rel="stylesheet">

    <!--====== Responsive css ======-->
    <link href="assets/css/responsive.css" rel="stylesheet">

    <!--====== jquery js ======-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?php echo BASE_URL.'/assets/';?>js/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
    <style>
        .proloader{
            background: transparent!important;
        }
        .logo-area{
            max-width: 250px!important;
        }
        i :not(.fa,.icon*){
            font-family: 'Montserrat', sans-serif !important;
        }
        p,h1,h2,h3,h4,h5,h6,a,button,input,textarea,label,span,.media-title{
            font-family: 'Montserrat', sans-serif !important;
        }
        .main-menu ul li a{
            font-size: 16px!important;
            margin: 0 10px!important;
        }
        .main-menu ul li:last-child a{
            padding: 10px 15px!important;
        }
    </style>
</head>

<body>

<!-- Preloader Start -->
<div class="proloader">
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
<div class="header-bar-area" style="<?php echo !empty($current_file) && !in_array($current_file,['index','index.php']) ? 'background:#517CE7' : '';?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-12">
                <div class="logo-area">
                    <a href="index"><img src="<?php echo BASE_URL.'/assets/images/logo_dark.png'?>" alt="Logo" style="width: 100%;" /></a>
                </div>
                <div class="menu-prepent"></div>
            </div>
            <div class="col-lg-8 text-right">
                <div class="main-menu">
                    <nav id="mobile-menu-active">
                        <ul>
                            <li class="<?php echo in_array($current_file,['index']) || empty($current_file) ? 'active' : '';?>"><a href="index">Home</a></li>
                            <li class="<?php echo in_array($current_file,['index#eowb']) ? 'active' : '';?>"><a href="index#eowb">Why us</a></li>
                            <li class="<?php echo in_array($current_file,['index#how']) ? 'active' : '';?>"><a href="index#how">How it works</a></li>
                            <li class="<?php echo in_array($current_file,['index#about']) ? 'active' : '';?>"><a href="index#about">About us</a></li>
                            <li class="<?php echo in_array($current_file,['index#contact']) ? 'active' : '';?>"><a href="index#contact">Contact us</a></li>
                            <?php
                            if ( AuthModel::isLoggedIn() || AdminModel::isLoggedIn() ){
                            ?>
                                <li><a href=" <?php echo AuthModel::isLoggedIn() ? 'login' : 'YEI/admin/index';?>">Dashboard</a></li>
                            <?php
                            }else{
                            ?>
                                <li class="<?php echo in_array($current_file,['login']) ? 'active' : '';?>"><a href="login">Sign In</a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Header bar area End -->
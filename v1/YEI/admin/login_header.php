<?php
include "../../config/db.php";
include BASE_DIR."/config/AuthModel.php";
include BASE_DIR."/config/Model.php";
include BASE_DIR."/config/AdminModel.php";

$case = Core::urlValueDecrypt($_GET['case']);
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login/Register</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/layout.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/colors.min.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <link rel="shortcut icon" type="image/png" href="../assets/images/favicon.png"/>

    <!-- Core JS files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="global_assets/js/main/bootstrap.bundle.min.js"></script>
    <script src="global_assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="global_assets/js/plugins/forms/styling/uniform.min.js"></script>

    <script src="assets/js/app.js"></script>
    <script src="global_assets/js/demo_pages/login.js"></script>

    <script src="<?php echo BASE_URL.'/assets/';?>js/sweetalert.min.js"></script>
    <!-- /theme JS files -->
    <style>
        .form-control-feedback{
            padding-top: .875rem;
        }
    </style>

</head>
<body style="background-image: url(../../assets/img/banner-bg.png); background-position: center;
            background-repeat: no-repeat;
            background-size: cover;">
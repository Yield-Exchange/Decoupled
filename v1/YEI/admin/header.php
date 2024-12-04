<?php
include "../../config/db.php";
include "../../config/Model.php";
require_once "../../config/AuthModel.php";
require_once "../../config/AdminModel.php";

$user_data = AdminModel::getUserdata();
switch ( $user_data['account_status'] ){
    case 'LOCKED':
    case 'PENDING':
    case 'CLOSED':
    case 'REJECTED':
    case 'SUSPENDED':
        AdminModel::logout();
        break;
}

$password_days = AuthModel::userPasswordHasExpired($user_data['id']);
if ( gettype($password_days) == "boolean" && $password_days==true ){
    echo "<script>location='" . BASE_URL . "/YEI/admin/password_expired'</script>"; exit;
}
?>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Home</title>
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700&display=swap" rel="stylesheet">
	<link href="global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/layout.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

    <link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL.'/assets/images/favicon.png'?>"/>
	<!-- Core JS files -->
<!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
    <script src="<?php echo BASE_URL.'/assets/';?>global_assets/js/main/bootstrap.bundle.min.js"></script>
	<script src="global_assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="global_assets/js/plugins/visualization/d3/d3.min.js"></script>
	<script src="global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
	<script src="global_assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script src="global_assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script src="global_assets/js/plugins/ui/moment/moment.min.js"></script>
	<script src="global_assets/js/plugins/pickers/daterangepicker.js"></script>

	<script src="assets/js/app.js"></script>
	<script src="global_assets/js/demo_pages/dashboard.js"></script>
	<!-- /theme JS files -->
    <!-- Theme JS files -->
	<script src="global_assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script src="global_assets/js/plugins/forms/inputs/touchspin.min.js"></script>
	<script src="global_assets/js/demo_pages/form_input_groups.js"></script>
	<!-- /theme JS files -->

    <!-- Theme JS files -->
	<script src="global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script src="global_assets/js/plugins/forms/selects/select2.min.js"></script>

    <script src="<?php echo BASE_URL.'/assets/';?>js/sweetalert.min.js"></script>

	<script src="global_assets/js/demo_pages/datatables_basic.js"></script>
    <script src="<?php echo BASE_URL.'/assets/dashboard/';?>js/custom.js"></script>
    <style>
        .myinput{
            border-radius: 20px;
        }
        th{
             font-size: 15px;
             text-transform: capitalize;
        }
        td{
            font-size: 16px;
            text-transform: capitalize;
        }
        .form-control{
            margin-top:8px;
        }
        .page-header-light{
            height: 40px;
        }

        element.style {
        }
        .sidebar-dark .nav-sidebar>.nav-item-open>.nav-link:not(.disabled), .sidebar-dark .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar>.nav-item-open>.nav-link:not(.disabled), .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar>.nav-item>.nav-link.active {
            background-color: linear-gradient(to right, rgb(0,51,51),rgb(73,117,117));
            color: #fff;
        }
        .sidebar-dark .nav-sidebar .nav-item>.nav-link.active, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-item>.nav-link.active {
            background-color: rgba(0,0,0,.15);
            color: #fff;
        }
        .sidebar-dark .nav-sidebar .nav-link, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-link {
            color: rgba(255,255,255,.9);
        }
        .nav-sidebar>.nav-item>.nav-link {
            font-weight: 500;
        }
        .nav-sidebar .nav-link {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: start;
            align-items: flex-start;
            padding: .75rem 1.25rem;
            transition: background-color ease-in-out .15s,color ease-in-out .15s;
        }
        .nav-item a {
            font-size: 14px!important;
        }
        .nav-item a {
            font-size: 15px;
        }
        .nav-link {
            position: relative;
            transition: all ease-in-out .15s;
        }
        .nav-link {
            display: block;
            padding: .625rem 1.25rem;
        }
        p,th,td, h1, h2, h3, h4, h5, h6, a, button, input, textarea, label, span, .media-title {
            font-family: 'Montserrat', sans-serif !important;
        }
        th{
            font-weight: 550!important;
        }
        .i-initial {
            color: #4975E3;
            background: #fff;
            padding: 5px 20px;
            border-radius: 50%;
            font-size: 30px;
        }
        .i-initial-inverse {
            background: #4975E3;
            color: #fff;
            padding: 5px 15px;
            border-radius: 50%;
            font-size: 30px;
        }
        .i-initial-inverse-big {
            background: #4975E3;
            color: #fff;
            height: 100px;
            width: 100px;
            text-align: center;
            line-height: 100px;
            border-radius: 50%;
            font-size: 40px;
        }
    </style>
    
    <script>
        $(document).ready(function() {
            let maxLength = 50;
            $('textarea').keyup(function() {
              let textlen = maxLength - $(this).val().length;
              $('#rchars').text(textlen);
            });

             $('#txtDate1').change(function() {
                if($('#txtDate').val()!=""&&$('#txtDate1').val()!=""){
                    if($('#txtDate').val()==$('#txtDate1').val()){
                            alert("Dates can't be same");
                    }
                }
            });
        });
    </script>
    <script>
      $(function(){
            var dtToday = new Date();

            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();

            var maxDate = year + '-' + month + '-' + day;

            $('#txtDate').attr('min', maxDate);
              $('#txtDate').attr('max', "2050-01-01");
        });
      
      $(function(){
        var dtToday = new Date();

        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;

        $('#txtDate1').attr('min', maxDate);
                   $('#txtDate1').attr('max', "2050-01-01");
    });

    function check_fields(){
        let empty = true;

        $('input[type="text"]').each(function(){
            if($(this).val()!=""){
                empty =false;
                return false;
            }
        });

        if(empty==true){
             location='index.php';
        }else if(empty==false){
            $('#myModal').modal('show');
        }
    }
      
      function reset(){
          $(':input','#myform')
          .not(':button, :submit, :reset, :hidden')
          .val('')
          .removeAttr('checked')
          .removeAttr('selected');
      }

      (function () {
          window.onpageshow = function(event) {
              if (event.persisted) {
                  window.location.reload();
              }
          };
      })();
    </script>

    <style>
         .mmy_btn:hover{
            background-color: #2664ae;
            color: white;
        }
    </style>

</head>

<body>
<?php
    $user_data = AdminModel::getUserdata();
?>
	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark" style="background-image: url(../../assets/img/banner-bg.png);">
		<div class="navbar-brand" style="padding:0px;">
			<a href="index.php" class="d-inline-block">
                <img src="image/logo1.png" style="height:30px;margin-top:5px;"  />
			</a>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			</ul>

			<span class="badge bg-success ml-md-3 mr-md-auto">Online</span>

			<ul class="navbar-nav">
 
				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
						<span><?php echo Core::render($user_data['name'])?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="account_settings" class="dropdown-item"><i class="icon-user-plus"></i> Account Settings</a>
						<div class="dropdown-divider"></div>
						<a href="logic?logout=1" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->

	<!-- Page content -->
	<div class="page-content">
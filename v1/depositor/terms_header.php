<?php
    require_once BASE_DIR."/includes/head.php";
?>

<body>

	<!-- Main navbar -->
	<div style="background-image: linear-gradient(to right,  rgba(59, 121, 219), rgba(0, 53, 199));border:0px;" class="navbar navbar-expand-md navbar-dark">
		<div class="navbar-brand" style="padding:0px;background-color:white;border:0px;"></div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3" style="color:black"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile" style="background-color:white;border:1px white solid">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3" style="color:black"></i>
					</a>
				</li>
			</ul>

			<span class="ml-md-3 mr-md-auto">
                <a style="float:right;" href="index.php" class="d-inline-block">
                    <img src="<?php echo BASE_URL.'/assets/images/logo_light.png'?>" style="height:40px;"/>
                </a>
			</span>

			<ul class="navbar-nav">
				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo BASE_URL.'/assets/images/avatar.png';?>" class="rounded-circle mr-2" height="34" alt="">
					</a>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->

	<!-- Page content -->
	<div class="page-content">

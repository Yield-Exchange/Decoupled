<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";
require_once "../config/AuthModel.php";

if ( AuthModel::isLoggedIn("depositor") ){
    require_once "header.php";
    require_once "sidebar.php";
?>

<?php
}else{
    require_once "terms_header.php";
?>
<!-- Main sidebar -->
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md" style="margin-top:-90px;min-height:900px">

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

            <!-- User menu -->
            <div class="sidebar-user">
                <div class="card-body">

                </div>
            </div>
            <!-- /user menu -->

            <!-- Main navigation -->
            <div class="card card-sidebar-mobile" style="font-size:15px;">
            </div>
            <!-- /main navigation -->

        </div>
        <!-- /sidebar content -->

    </div>
<!-- /main sidebar -->
<?php
}

?>

<!-- Main content -->
<div class="content-wrapper">
    <embed src="Terms_and_Conditions.pdf" width="100%" height="100%" />
<!-- /content area -->
<?php
require_once "footer.php";
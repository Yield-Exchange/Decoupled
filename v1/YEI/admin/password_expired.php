<?php
require_once "login_header.php";
if ( !AdminModel::isLoggedIn() ){
    echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate'</script>"; exit;
}

$user_data = AdminModel::getUserdata();
$password_days = AuthModel::userPasswordHasExpired($user_data['id']);
if ( gettype($password_days) == "boolean" && $password_days==false ){
    echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate'</script>"; exit;
}
?>
<!-- Page content -->
<div class="page-content login-cover">
    <!-- Main content -->
    <div class="content-wrapper">
        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center">
            <!-- Login form -->
            <div class="login-form wmin-sm-400">
                <div class="row" id="req12">
                    <div class="col-lg-12 col-md-12"></div>
                </div>
                <div class="card mb-0">
                    <div class="tab-content card-body">
                        <div class="tab-pane fade show active" id="login-tab1">
                            <?php
                            if ( gettype($password_days) == "integer" ){
                            ?>
                                <div class="alert alert-warning" style="margin-top: 20px"> You have <?php echo $password_days;?> days left for your password to expire.</div>
                            <?php
                            }else if ( gettype($password_days) == "boolean" && $password_days==true ){
                            ?>
                                <div class="alert alert-danger" style="margin-top: 20px"> Your password has expired.</div>
                            <?php
                            }
                            ?>
                            <br/>
                            <a href="<?php echo BASE_URL . '/YEI/admin/reset_password';?>" class="btn btn-primary">Click here to reset your password</a>
                            <?php
                            if ( gettype($password_days) == "boolean" && $password_days==false ){
                            ?>
                                <a href="<?php echo BASE_URL . '/YEI/admin/authenticate';?>" class="btn btn-link">Do it later</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /login form -->
        </div>
        <!-- /content area -->
    </div>
    <!-- /main content -->
</div>
<?php
require_once "login_footer.php";
?>

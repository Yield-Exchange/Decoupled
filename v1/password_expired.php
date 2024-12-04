<?php
session_start();
include "config/db.php";
include BASE_DIR . "/config/AuthModel.php";
include BASE_DIR . "/config/Model.php";

$page_title="Password Expiration";
require(BASE_DIR . "/includes/header.php");

if ( !AuthModel::isLoggedIn() ){
    echo "<script>location='" . BASE_URL . "/login'</script>"; exit;
}

$user_data = AuthModel::getUserdata();
$password_days = AuthModel::userPasswordHasExpired($user_data['id']);
if ( gettype($password_days) == "boolean" && $password_days==false ){
    echo "<script>location='" . BASE_URL . "/login'</script>"; exit;
}

$as = ($user_data['description']=='Bank' || $user_data['description']=='Broker') ? 'fi' : 'inv';
?>
<style>
    @media screen and (max-width: 578px) {
        #login_img{
            display: none;
        }
    }
    #banner{
        padding-top: 80px!important;
    }
</style>
<!-- ====== Banner Part HTML Start ====== -->
<div id="banner" style="background:white!important;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
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
                <a href="<?php echo BASE_URL . '/reset_password?as='.$as;?>" class="btn btn-primary">Click here to reset your password</a>

                <?php
                if ( gettype($password_days) == "boolean" && $password_days==false ){
                ?>
                    <a href="<?php echo BASE_URL . '/login';?>" class="btn btn-link">Do it later</a>
                <?php
                }
                ?>
            </div>
            <div class="col-md-8">
                <img style="height:100%;width: 105%" id="login_img" src="assets/images/back.jpg">
            </div>
        </div>
    </div>
</div>
<?php
require("includes/footer.php");
?>

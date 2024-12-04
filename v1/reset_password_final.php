<?php
session_start();
include "config/db.php";
include BASE_DIR."/config/Model.php";

$error_msg="";
if (!empty($_GET['code'])){
    $pass_reset = db::preparedQuery("SELECT * from password_resets where token=?", array("s", $_GET['code']));
    if (empty($pass_reset[0])){
        $error_msg="The link is invalid, please check your email and try again";
    }

    $dateTime = Model::utcDateTime();
    if ( date('Y-m-d H:i:s',strtotime ( '+5 minutes' , strtotime($pass_reset[0]['created_at']))) < $dateTime ){
        $error_msg =  "The link has expired, please try to reset the password again";
    }
}else{
    echo "<script>window.location='/login'</script>";die();
}

$as = !empty($_GET['a']) ? $_GET['a'] : "inv";
if ($as != "inv" && $as != "fi"){
    $as="inv";
}

include BASE_DIR."/config/AuthModel.php";
$page_title="Change Password";
require(BASE_DIR."/includes/header.php");

$error_msg1="";
if (isset($_POST['change_pwd']) && empty($error_msg) && !empty($_POST['pass'])) {
    if ($_POST['pass'] != $_POST['conf_pass']){
        $error_msg1="Passwords do not match";
    }else {
        if ($as == "fi") {
            $error_msg1 = AuthModel::recoverPasswordFinal("bank");
        } else {
            $error_msg1 = AuthModel::recoverPasswordFinal("depositor");
        }
        if ( $error_msg1=="success" ){
            echo "<script>swal({title:'', text:'Password updated successfully.', type:'success',timer: 5000}).then((value) => { window.location='".BASE_URL."/login' });</script>";exit();
        }
    }
}

$token = AuthModel::generateToken();

?>
<!-- ====== Banner Part HTML Start ====== -->
<style>
    .btn.btn-link{
        color: black;
    }
    .btn.btn-link.active{
        color: #007bff;
    }
    .form-check-label > .form-input-styled{
        margin-right: 5px;
    }
    #banner{
        padding-top: 80px!important;
    }
    @media screen and (max-width: 578px) {
        #login_img{
            display: none;
        }
    }
</style>
<link rel="stylesheet" href="assets/css/passwordmeter/custom.css?v=1.3" />
<div id="banner" style="background:white!important;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <form action="reset_password_final?a=<?php echo $as.'&&code='.$_GET['code'];?>" id="form-submit" method="post">
                    <br/>
                    <br/>
                    <?php
                        if (!empty($error_msg)){
                    ?>
                        <div class="alert alert-danger">
                            <?php echo $error_msg;?>
                        </div>
                    <?php
                        }
                    ?>

                    <?php
                    if (!empty($error_msg1)){
                    ?>
                        <div class="alert alert-danger">
                            <?php echo $error_msg1;?>
                        </div>
                    <?php
                    }

                    if (empty($error_msg)){
                    ?>
                    <span class="form-text text-muted">Enter the new password. </span>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="hidden" name="code" value="<?php echo $_GET['code'];?>" class="form-control"/>
                        <input type="hidden" name="env" value="<?php echo $_GET['env'];?>" class="form-control"/>
                        <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                    </div>

                    <div class="form-group" id="complexity">
                        <input id="lgd_out_pg_pass" name="pass" class="password form-control" type="password" onkeyup="checkThisPassword(this.value);" placeholder="New password" value="" />
                        <span id="complexity-span" class="pmshow">No Password</span>
                    </div>

                    <div class="generate_button chars_holder row col-md-12">
                        <div class="char_type col-md-3" id="special_count">
                            <span class="char_type_text" style="padding-left: 15px;">Symbols</span>
                        </div>

                        <div class="char_type col-md-3" id="digits_count">
                            <span class="char_type_text" style="padding-left: 15px;">Numbers</span>
                        </div>

                        <div class="char_type col-md-3" id="upper_count">
                            <span class="char_type_text" style="padding-left: 15px;">Upper case</span>
                        </div>
                        <div class="char_type col-md-3" id="lower_count">
                            <span class="char_type_text" style="padding-left: 15px;">Lower case</span>
                        </div>
                        <div class="breaker">&nbsp;</div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-right" style="margin-top: 15px">
                        <input type="password" name="conf_pass" class="form-control pass_confirm" id="conf_pass" placeholder="Confirm New Password" required/>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-lock2 text-muted"></i>
                        </div>
                        <span id="pass_error_confirm" style="color:red"></span>
                    </div>

                    <div class="row">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-input-styled" onclick="myFunction(this)"> Show Password
                            </label>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <a style="color: #4587FD;" href="<?php echo BASE_URL;?>/login" class="btn btn-link">Sign In Instead</a>
                        </div>
                        <?php
                        if (empty($error_msg)){
                        ?>
                        <div class="col-md-6">
                            <div class="form-group d-flex align-items-center">
                                <button type="submit" disabled name="change_pwd" class="btn btn-primary btn change_pwd">Submit</button>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <img style="height:100%;width: 105%" id="login_img" src="assets/images/back.jpg">
            </div>

        </div>
    </div>
</div>
<script src="assets/js/passwordmeter/zxcvbn.js" type="text/javascript"></script>
<script src="assets/js/passwordmeter/index.js?v=1.1" type="text/javascript"></script>
<script src="assets/js/reset-password.js?v=1.7" type="text/javascript"></script>
<?php
    require("includes/footer.php");
?>
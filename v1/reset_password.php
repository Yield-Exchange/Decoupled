<?php
session_start();
include "config/db.php";
include BASE_DIR."/config/Model.php";

$as = !empty($_GET['a']) ? $_GET['a'] : "inv";
if ($as != "inv" && $as != "fi"){
    $as="inv";
}

include BASE_DIR."/config/AuthModel.php";

$token = AuthModel::generateToken();
AuthModel::silentLogout();

$page_title="Recover Password";
require(BASE_DIR."/includes/header.php");
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- ====== Banner Part HTML Start ====== -->
<style>
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
    .nav-tabs > .nav-item > .nav-link.active{
        background: rgba(81, 124, 231, 0.2);
    }
</style>
<div id="banner" style="background:white!important;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="row" style="margin-bottom: 20px;margin-top: 20px">
                    <ul class="nav nav-tabs">
                        <li class="nav-item" style="display: <?php echo $as=='fi' ? 'none' : 'inline-block';?>">
                            <a class="nav-link" href="reset_password?a=inv">Wholesale Investor</a>
                        </li>
                        <li class="nav-item" style="display: <?php echo $as=='inv' ? 'none' : 'inline-block';?>">
                            <a class="nav-link" href="reset_password?a=fi">Financial Institution</a>
                        </li>
                    </ul>
                </div>
                <form action="<?php echo $as=='inv' ? 'depositor': 'bank';?>/logic" method="post" class="reset_password_form">
                    <?php
                        if ( !empty($_GET['case']) && Core::urlValueDecrypt($_GET['case']) == 'error' ) {
                            echo '<div class="alert alert-danger">If email exist in our system, you will get a reset link</div>';
                        }else if (!empty($_GET['case']) && Core::urlValueDecrypt($_GET['case']) == 'recaptcha' ){
                            echo '<div class="alert alert-danger">Could not verify recaptcha</div>';
                        }
                    ?>
                    <span class="form-text text-muted">Enter your registered email address and we'll send you the link to reset the password. </span>
                    <br>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required />
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                            <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                            <input type="hidden" name="_as" value="<?php echo $as; ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label id="recpatchareq" style="color:black;display:none">Please verify that you are not a robot</label>
                        <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_KEY;?>" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div>
                        <input class="form-control d-none recaptchar" name="recaptcha" data-recaptcha="true" data-error="Please complete the Captcha"/>
                        <br/>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <a style="color: #4587FD;" href="<?php echo BASE_URL;?>/login?a=<?php echo $as;?>" class="btn btn-link">Sign In Instead</a>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group d-flex align-items-center">
                                <button type="submit" name="forget_pwd" class="btn btn-primary">Reset password</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <img style="height:100%;width: 105%" id="login_img" src="assets/images/back.jpg">
            </div>

        </div>
    </div>
</div>
<script>
    function checkRecaptcha() {

        if (jQuery('.recaptchar').val() == "") {
            jQuery("#recpatchareq").show();
        } else {
            jQuery("#recpatchareq").hide();
        }

    }

    $(function () {

        checkRecaptcha();
        window.verifyRecaptchaCallback = function (response) {
            $('.recaptchar').val(response).trigger('change');
        };

        window.expiredRecaptchaCallback = function () {
            $('.recaptchar').val("").trigger('change');
        };

        $(document).on("submit",'.reset_password_form',function (e) {
            if (jQuery(".recaptchar").val() == "") {
                jQuery("#recpatchareq").show();
                swal("Please verify that you are not a robot");
                return false;
            } else {
                jQuery("#recpatchareq").hide();
            }

            if (!e.isDefaultPrevented()) {
                $('.reset_password_form').append("<input type='hidden' name='forget_pwd' value=''/>");
                $('.reset_password_form').submit();
                return false;
            }
        });

    });

    function myFunction(thi) {
        let x = document.getElementById("password");
        if (thi.checked) {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
<?php
require("includes/footer.php");
?>
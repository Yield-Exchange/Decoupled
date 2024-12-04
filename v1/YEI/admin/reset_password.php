<?php
    require_once "login_header.php";

    AdminModel::silentLogout();
    if (isset($_POST['forget_pwd'])) {
        AdminModel::recoverPassword();
    }

    $token = AuthModel::generateToken();
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
                            <form action="reset_password" method="post" class="reset_password_form">
                                <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                <?php
                                if ( !empty($_GET['case']) && Core::urlValueDecrypt($_GET['case']) == 'error' ) {
                                    echo '<div class="alert alert-danger">If email exist in our system, you will get a reset link</div>';
                                }elseif ( !empty($_GET['case']) && Core::urlValueDecrypt($_GET['case']) == 4){
                                    $status = isset($_GET['status']) ? Core::urlValueDecrypt($_GET['status']) : 'suspended';
                                    if (strtolower($status) == "pending"){
                                        echo '<div class="alert alert-warning"> Your account is still pending approval. Please contact info@yieldexchange.ca for more information</div>';
                                    }else {
                                        echo '<div class="alert alert-warning"> Sorry something appears to be wrong. Please contact info@yieldexchange.ca for more information</div>';
                                    }
                                }
                                ?>
                                <span class="form-text text-muted">Enter your registered email address and we'll send you the link to reset the password. </span>
                                <br>
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required />
                                    <div class="form-control-feedback">
                                        <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                                        <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
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
                                        <a style="color: #4587FD;" href="<?php echo BASE_URL;?>/YEI/admin/authenticate" class="btn btn-link">Sign In Instead</a>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group d-flex align-items-center">
                                            <button type="submit" name="forget_pwd" class="btn btn-primary">Reset password</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
</script>

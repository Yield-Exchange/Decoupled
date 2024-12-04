<?php
session_start();
include "config/db.php";
include BASE_DIR."/config/AuthModel.php";
include BASE_DIR."/config/Model.php";

$as = !empty($_GET['a']) ? $_GET['a'] : "inv";
if ($as != "inv" && $as != "fi"){
    $as="inv";
}

if (isset($_GET['tmpPass'])){
    AuthModel::silentLogout();
}

$token = AuthModel::generateToken();
$case = Core::urlValueDecrypt($_GET['case']);

if ($as == "inv") {
    if (AuthModel::isLoggedIn()){
        echo "<script>location='".BASE_URL."/depositor/index'</script>";
    }
}else{
    if (AuthModel::isLoggedIn()){
        $authenticate=AuthModel::getUserdata();
        if( $authenticate['is_non_partnered_fi']==1 ){
            if ( $authenticate['account_status'] == 'ACTIVE' && !$authenticate['password_changed'] ){
                echo "<script>location='" . BASE_URL . "/bank/non_fi_details'</script>"; exit();
            }
            echo "<script>location='" . BASE_URL . "/bank/requests'</script>"; exit();
        }

        echo "<script>location='".BASE_URL."/bank/index'</script>";
    }
}

$page_title="Login";
require(BASE_DIR."/includes/header.php");
?>
<style>

    .form-check-label{
        font-size: 14px;
    }

    .form-check-label > .form-input-styled{
        margin-right: 5px;
    }

    .form-text{
        font-size: 14px;
    }

    .forgot-pass{
        font-size: 14px;
    }
    @media screen and (max-width: 578px) {
        #login_img{
            display: none;
        }
    }
    #banner{
        padding-top: 80px!important;
    }
    .btn.btn-link{
        color: #007bff;
    }
    .btn.btn-link.active{
        color: black;
    }
    .nav-tabs > .nav-item > .nav-link.active{
        background: rgba(81, 124, 231, 0.2);
    }
</style>
<!-- ====== Banner Part HTML Start ====== -->
<div id="banner" style="background:white!important;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                    <?php
                    $pin_count = Core::passwordResetCount();
                    switch ( $case ):
                        case "expired":  // pin is old than 5 min
                        case "error":   // wrong OTP
                        case "resend": //resend OTP
                        case 1: // OTP
                        case "token_invalid": // OTP
                            $mail = @$_SESSION["pin_mail"];
                            echo '<div style="margin-bottom: 20px;margin-top: 20px">&nbsp;</div>';
                            if ( in_array($case,array(1,"resend")) ) {

                                echo '<div class="alert alert-info">Kindly check your Email. Pin code has been sent.</div>';
                                if ($case == "resend") {
                                    AuthModel::resendPin($mail, $as=="inv" ? 'depositer' : "bank");
                                }

                            }else if ( trim($case) == "error" ){
                                $attempts = AuthModel::getUserLoginAttempts( $mail, $as=="inv" ? 'depositor' : "bank");
                                if ($attempts > $pin_count){
                                    echo '<div class="alert alert-danger"> Your account has been locked. Please contact info@yieldexchange.ca to unlock your account.</div>';
                                }else if ($attempts == $pin_count) {
                                    echo '<div class="alert alert-danger"> Incorrect pin. This is your last attempt until your account is locked.</div>';
                                }else{
                                    echo '<div class="alert alert-warning"> Incorrect pin. You have ' . ($pin_count - $attempts) . ' more tries until your account is locked.</div>';
                                }
                            }elseif ( trim($case) == "expired" ){
                                echo '<div class="alert alert-info">The pin has expired, click resend to get a new one.</div>';
                            }elseif (trim($case) == "token_invalid"){
                                echo '<div class="alert alert-info">Token Invalid, please refresh the page and retry</div>';
                            }
                            ?>
                            <form action="<?php echo $as=='inv' ? 'depositor': 'bank';?>/logic" method="post">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" value="<?php echo $mail; ?>" readonly/>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="pinn" class="form-control" placeholder="Enter Login Pin">
                                </div>
                                <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                <div class="row">
                                    <div class="col-md-4">
                                        <a href="<?php echo BASE_URL;?>/login?a=<?php echo $as;?>" class="ml-auto">Back to Login</a>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group d-flex align-items-center">
                                            <a href="<?php echo BASE_URL;?>/login?a=<?php echo $as;?>&&case=<?php echo Core::urlValueEncrypt('resend');?>" class="btn btn-outline-primary">Resend&nbsp;PIN</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group d-flex align-items-center">
                                            <button type="submit" name="pin" class="btn btn-primary">Sign in</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php
                            break;
                        case 4: // non_approved || rejected || suspended
                            $status = isset($_GET['status']) ? Core::urlValueDecrypt($_GET['status']) : 'pending approval';
                            if (strtolower($status) == "pending"){
                                echo '<div class="alert alert-warning"> Your account is still pending approval. Please contact info@yieldexchange.ca for more information</div>';
                            }else {
                                echo '<div class="alert alert-warning"> Sorry something appears to be wrong. Please contact info@yieldexchange.ca for more information</div>';
                            }
                            break;
                        case 2: // wrong pass
                        case 3: // wrong mail
                        case 'password_empty':
                        case 'locked':
                        case 'required': // enter email & pass
                        case 'token_invalid_login':
                        default:
                            if ( in_array($case,array(2)) ){
                                $mail = @$_SESSION["pin_mail"];
                                $attempts = AuthModel::getUserLoginAttempts( $mail, $as=="inv" ? 'depositor' : "bank");
                                if ($attempts > $pin_count){
                                    echo '<div class="alert alert-danger"> Your account has been locked. Please contact info@yieldexchange.ca to unlock your account.</div>';
                                }else if ($attempts == $pin_count) {
                                    echo '<div class="alert alert-danger"> Incorrect Email or Password. This is your last attempt until your account is locked.</div>';
                                }else{
                                    echo '<div class="alert alert-warning"> Incorrect Email or Password. You have ' . ($pin_count - $attempts) . ' more tries until your account is locked.</div>';
                                }
                            }elseif ( trim($case) == 7 ){
                                echo '<div class="alert alert-success"> Password reset successful, check your email. The link will expire in the next 5 minutes</div>';
                            }elseif ( trim($case) == "success"){
                                echo '<div class="alert alert-success"> Registration Successful. Thank You for registering for an account with Yield Exchange. One of our representatives will get back to regarding the next steps.</div>';
                            }elseif (trim($case) == 3){
                                $limit = Core::invalidLoginResetCount();
                                $limit_remaining = $limit;
                                if( !empty($_COOKIE['limit_failed_login']) ) {
                                    $limit_remaining = $limit - $_COOKIE['limit_failed_login'];

                                    if ($limit_remaining <= 0){
                                        echo '<div class="alert alert-danger"> Your account has been locked. Please contact info@yieldexchange.ca to unlock your account.</div>';
                                    }else if ($limit_remaining == 1) {
                                        echo '<div class="alert alert-danger"> Incorrect Email or Password. This is your last attempt until your account is locked.</div>';
                                    }else{
                                        echo '<div class="alert alert-warning"> Incorrect Email or Password. You have ' . ($limit_remaining) . ' more tries until your account is locked.</div>';
                                    }

//                                    if ( $limit_remaining <= $_COOKIE['limit_failed_login'] ) {
//                                        echo '<div class="alert alert-warning"> Invalid Email or Password, '.$limit.' login attempts failed. Wait for 10 minutes then try again.</div>';
//                                    }else{
//                                        echo '<div class="alert alert-warning"> Invalid Email or Password, '.$limit_remaining.' login attempts remaining.</div>';
//                                    }
                                }else {
                                    echo '<div class="alert alert-warning"> Invalid Email or Password. You have '.$limit.' more tries until your account is locked.</div>';
                                }
                            }else if (trim($case) == "password_empty"){
                                echo '<div class="alert alert-warning"> Enter Password</div>';
                            }else if(trim($case) == "locked"){
                                echo '<br/><div class="alert alert-danger">Your account has been locked. Please contact info@yieldexchange.ca to unlock your account</div>';
                            }else if(trim($case) == "required"){
                                echo '<br/><div class="alert alert-info">Please provide valid email and password</div>';
                            }else if(trim($case) == "token_invalid_login"){
                                echo '<br/><div class="alert alert-info">Token Invalid, please refresh the page and retry</div>';
                            }
                            ?>
                            <div class="row m-3">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo $as=='inv' ? 'active' : '';?>" href="login?a=inv">Wholesale Investor</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo $as=='fi' ? 'active' : '';?>" href="login?a=fi">Financial Institution</a>
                                    </li>
                                </ul>
                            </div>
                            <form action="<?php echo $as=='inv' ? 'depositor': 'bank';?>/logic" method="post">
                                <div class="form-group form-group-feedback form-group-feedback-right">
                                    <input type="email" name="email" class="form-control" placeholder="Email" required/>
                                    <div class="form-control-feedback">
                                        <i style="margin-top:10px;" class="icon-user text-muted"></i>
                                    </div>
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-right">
                                    <input type="password" name="pass" class="form-control" id="password" placeholder="Password" required/>
                                    <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                    <?php
                                    if ( !empty(!empty($_GET['env'])) ){
                                    ?>
                                        <input type="hidden" name="_env" value="<?php echo $_GET['env']; ?>"/>
                                    <?php
                                    }
                                    ?>
                                    <div class="form-control-feedback">
                                        <i style="margin-top:10px;" class="icon-lock2 text-muted"></i>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-input-styled" onclick="myFunction(this)" /> Show Password
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-right" style="margin-bottom: 20px">
                                        <a href="reset_password?a=<?php echo $as;?>" class="ml-auto forgot-pass">Forgot password?</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <div class="form-group d-flex align-items-center">
                                            <a href="signup"  class="btn btn-outline-primary">Sign up</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group d-flex align-items-center">
                                            <button type="submit" name="login" class="btn btn-primary">Sign in</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php
                            break;
                    endswitch;
                    ?>
                </div>
                <div class="col-md-8">
                    <img style="height:100%;width: 105%" id="login_img" src="assets/images/back.jpg">
                </div>

        </div>
    </div>
</div>
<script>
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

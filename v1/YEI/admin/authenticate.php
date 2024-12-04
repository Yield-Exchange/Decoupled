<?php
    require_once "login_header.php";
    if (AdminModel::isLoggedIn()){
        echo "<script>location='" . BASE_URL . "/YEI/admin/index'</script>";
    }

    $token = AuthModel::generateToken();
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
								<div class="text-center mb-3">
									<img src="/assets/images/logo_light.png" style="max-height:50%;max-width:50%"/>
									<?php
                                       $pin_count = Core::passwordResetCount();
                                        switch ($case) {
                                            case 'pin_request':
                                            case 'wrong_pin':
                                            case 'expired':
                                            case 1: // OTP
                                            case "resend": //resend OTP
                                                $email = $mail = @$_SESSION["pin_mail"];
                                                if ( in_array($case,array(1,"resend")) ) {
                                                    echo '<div class="alert alert-info">Kindly check your Email. Pin code has been sent.</div>';
                                                    if ($case == "resend") {
                                                        AdminModel::resendPin($mail);
                                                    }
                                                }elseif ($case=="wrong_pin") {
                                                    $attempts = AdminModel::getUserLoginAttempts( $mail);
                                                    if ($attempts > $pin_count){
                                                        echo '<div class="alert alert-danger"> Your account has been locked. Please contact info@yieldexchange.ca to unlock your account.</div>';
                                                    }else if ($attempts == $pin_count) {
                                                        echo '<div class="alert alert-danger"> Incorrect pin. This is your last attempt until your account is locked.</div>';
                                                    }else{
                                                        echo '<div class="alert alert-warning"> Incorrect pin. You have ' . ($pin_count - $attempts) . ' more tries until your account is locked.</div>';
                                                    }
                                                }elseif ( trim($case) == "expired" ){
                                                    echo '<div class="alert alert-info">The pin has expired, click resend to get a new one.</div>';
                                                }
                                    ?>
                                    <form action="logic" method="post">
                                        <br/>
                                        <div class="form-group">
                                            <input type="email" readonly name="email" class="form-control" value="<?php echo $email;?>"/>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="pinn" class="form-control" placeholder="Enter Login Pin" />
                                        </div>
                                        <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-4">
                                                <a style="color:black" href="authenticate" class="ml-auto">Back to Login</a>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-4ol-md-4">
                                                <div class="form-group d-flex align-items-center">
                                                    <a href="<?php echo BASE_URL;?>/YEI/admin/authenticate?case=<?php echo Core::urlValueEncrypt('resend');?>" class="btn btn-outline-primary">Resend&nbsp;PIN</a>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-4">
                                                <div class="form-group d-flex align-items-center">
                                                    <button style="background-color:#4587FD;color:white;box-shadow: 3px 3px 3px 3px #4587FD;" type="submit" name="pin" class="btn btn-primary">Sign in</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                                break;
                                            default:
                                                if ($case == "wrong_credentials"){
                                                    $mail = @$_SESSION["pin_mail"];
                                                    $attempts = AdminModel::getUserLoginAttempts( $mail );
                                                    if ($attempts > $pin_count){
                                                        echo '<div class="alert alert-danger"> Your account has been locked. Please contact info@yieldexchange.ca to unlock your account.</div>';
                                                    }else if ($attempts == $pin_count) {
                                                        echo '<div class="alert alert-danger"> Incorrect Email or Password. This is your last attempt until your account is locked.</div>';
                                                    }else{
                                                        echo '<div class="alert alert-warning"> Incorrect Email or Password. You have ' . ($pin_count - $attempts) . ' more tries until your account is locked.</div>';
                                                    }
                                                }elseif (trim($case) == 7){
                                                    echo '<div class="alert alert-success"> Password reset successful, check your email. The link will expire in the next 5 minutes</div>';
                                                }elseif (trim($case) == 4){
                                                    $status = isset($_GET['status']) ? Core::urlValueDecrypt($_GET['status']) : 'suspended';
                                                    if (strtolower($status) == "pending"){
                                                        echo '<div class="alert alert-warning"> Your account is still pending approval. Please contact info@yieldexchange.ca for more information</div>';
                                                    }else {
                                                        echo '<div class="alert alert-warning"> Sorry something appears to be wrong. Please contact info@yieldexchange.ca for more information</div>';
                                                    }
                                                }elseif ($case == "locked"){
                                                    echo '<div class="alert alert-danger">Your account has been locked. Please contact info@yieldexchange.ca to unlock your account</div>';
                                                }
									?>
                                    <br />
									<h5 class="mb-0">Login to your account</h5>
									<span class="d-block text-muted">Your credentials</span>
								</div>
                                 <form action="logic" method="post">
                                    <div class="form-group form-group-feedback form-group-feedback-right">
                                        <input type="text" name="username" class="form-control" placeholder="Username" />
                                        <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                        <div class="form-control-feedback">
                                            <i class="icon-user text-muted"></i>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-feedback form-group-feedback-right">
                                        <input type="password" name="pass" id="password" class="form-control" placeholder="Password" />
                                        <div class="form-control-feedback">
                                            <i class="icon-lock2 text-muted"></i>
                                        </div>
                                    </div>

                                     <div class="row">
                                         <div class="form-check">
                                             <label class="form-check-label">
                                                 <input type="checkbox" class="form-input-styled" onclick="myFunction(this)" /> Show Password
                                             </label>
                                         </div>
                                         <br/>
                                     </div>

                                     <div class="row">
                                         <div class="col-md-12 text-right" style="margin-bottom: 20px">
                                             <a href="reset_password" class="ml-auto forgot-pass">Forgot password?</a>
                                         </div>
                                     </div>

                                    <div class="form-group">
                                        <button type="submit" name="login" class="btn btn-primary btn-block">Sign in</button>
                                    </div>
                                    <?php
                                        }
                                    ?>
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
require_once "login_footer.php";
?>

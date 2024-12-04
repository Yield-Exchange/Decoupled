<?php
session_start();
include "config/db.php";

require_once BASE_DIR."/config/AuthModel.php";
$token = AuthModel::generateToken();

$as = !empty($_GET['a']) ? $_GET['a'] : "inv";
if ($as != "inv" && $as != "fi"){
    $as="inv";
}

$page_title="Sign up";
require("includes/header.php");
?>
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
    .accountTypeBtn{
        font-size: 14px;
        padding: 1%;
        color:#4587FD;background-color:white;border-color:#4587FD;
    }
    .form-group > input,.form-group > select, .form-group > .form-check{
        font-size: 14px;
    }
    #deposit_insurance,#short_term_credit{
        display: none;
    }
    .tooltip-inner{
       background: transparent!important;
    }
    .tooltip-inner > img{
        width: 400px!important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="assets/css/passwordmeter/custom.css?v=1.3" />
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div id="banner" style="background:white!important;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <?php
                if (isset($_GET["case"])) {
                    $case = Core::urlValueDecrypt($_GET['case']);

                    if ($case == 2) {
                ?>
                        <div class="alert alert-warning">Password and Confirm password are not same , please try again .</div>
                <?php
                    } else if ($case == 3) {
                ?>
                        <div class="alert alert-warning">User already exist on this email .</div>
                <?php
                    } else if ($case == 'invalid_email'){
                ?>
                        <div class="alert alert-warning">Provided email is invalid .</div>
                <?php
                    } else if ($case == 'recaptcha'){
                ?>
                    <div class="alert alert-warning">Oops! Could not validate the recaptcha.</div>
                <?php
                    } else if ($case == "institution_exists" ){
                ?>
                        <div class="alert alert-warning">The institution already exists in the our system</div>
                <?php
                    }
                }
                ?>
                <form action="<?php echo $as=='inv' ? 'depositor': 'bank';?>/logic" method="post" id="signup-form">
                    <br />
                    <span>You are registering as</span>
                    <br>
                    <div class="form-group">
                        <label class="btn btn-primary accountTypeBtn">
                            <input type="radio" name="path" value="2" checked class="btn btn-primary register_as"> GIC Investor
                        </label>
                        <label class="btn btn-primary accountTypeBtn">
                            <input style="color:black;background-color:grey" type="radio" name="path" value="1" class="btn btn-primary register_as" required> Financial Institution
                        </label>
                        <!-- <label class="btn btn-primary accountTypeBtn">
                            <input type="radio" name="path" value="3" class="btn btn-primary register_as"> Deposit Broker
                        </label> -->
                    </div>

                    <div class="form-group select_institution">
                        <select name="institution_s" class="form-control select2 institution_s">
                            <option value="">Select Institution</option>
                            <?php
                            $fi = BankModel::getFI(true);
                            foreach ($fi as $f){
                                ?>
                                <option value="<?php echo $f['name'];?>"><?php echo $f['name'];?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control input_institution" placeholder="Institution" name="institution">
                        <div class="form-control-feedback">
                            <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Address Line 1" name="address" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Address Line 2" name="address2">
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="City" name="city" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="province" class="form-control select2" required>
                            <option value="">Select Province/Territory</option>
                            <option value="Alberta">Alberta</option>
                            <option value="British Columbia">British Columbia</option>
                            <option value="Manitoba">Manitoba</option>
                            <option value="New Brunswick">New Brunswick</option>
                            <option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
                            <option value="Nova Scotia">Nova Scotia</option>
                            <option value="Ontario">Ontario</option>
                            <option value="Prince Edward Island">Prince Edward Island</option>
                            <option value="Quebec">Quebec</option>
                            <option value="Saskatchewan">Saskatchewan</option>
                            <option value="Nunavut">Nunavut</option>
                            <option value="Quebec">Quebec</option>
                            <option value="NorthWest Territories">NorthWest Territories</option>
                            <option value="Yukon">Yukon</option>
                        </select>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Postal Code" name="postal" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="time_zone" class="form-control" required>
                            <option value="">Select Your Timezone</option>
                            <?php
                            $list_timezones = Model::timezonesList();
                            foreach ($list_timezones as $key => $list_timezone) {
                            ?>
                                <option value="<?php echo $key;?>"><?php echo $list_timezone;?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" name="telephone" class="form-control" placeholder="Your Telephone" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-mention text-muted"></i>
                        </div>
                    </div>

                    <!--<div class="form-group" id="deposit_insurance">
                        <select name="deposit_insurance" class="form-control deposit_insurance">
                            <option value="">Deposit insurance</option>
                            <?php
                            /*$deposit_insurance = db::getRecords("SELECT * FROM `deposit_insurance`");
                            if (!empty($deposit_insurance)){
                                foreach ( $deposit_insurance as $item) {
                            ?>
                                <option value="<?php echo $item['id'];?>"><?php echo $item['description'];?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group row" id="short_term_credit">
                        <div class="col-md-10" style="display: inline-block;">
                            <select name="short_term_credit" class="form-control" style="font-size: 13px;">
                                <option value="">Short Term credit Rating</option>
                                <?php
                                $credit_rating_type = db::getRecords("SELECT * FROM `credit_rating_type`");
                                if (!empty($credit_rating_type)){
                                    foreach ( $credit_rating_type as $item) {
                                ?>
                                    <option <?php echo $item['description'] =="Any/Not Rated" ? "selected" : "";?> value="<?php echo $item['id'];?>"><?php echo $item['description'];?></option>
                                <?php
                                    }
                                }*/
                                ?>
                            </select>
                        </div>
                        <div class="col-md-1" style="display: inline-block;">
                            <a data-toggle="tooltip" title="<img src='assets/img/credit_rating.png' style='width:100%' />">
                                <i class="fa fa-info-circle"></i>
                            </a>
                        </div>
                    </div>!-->

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="email" name="email" class="form-control" placeholder="Your email" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-mention text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group" id="complexity">
                        <input id="lgd_out_pg_pass" name="pass" class="password form-control" type="password" onkeyup="checkThisPassword(this.value);" placeholder="Your password" value="" />
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
                        <div class="char_type col-md-3 " id="lower_count">
                            <span class="char_type_text" style="padding-left: 15px;">Lower case</span>
                        </div>
                        <div class="breaker">&nbsp;</div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left" style="margin-top: 15px">
                        <input type="password" id="myInput1" name="cpass" class="form-control pass_confirm" placeholder="Confirm password" data-toggle-title="Show Password" required/>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-lock text-muted"></i>
                        </div>
                        <span id="pass_error_confirm" style="color:red"></span>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-input-styled" onclick="myFunction(this)"> Show Password
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label id="recpatchareq" style="color:black;display:none">Please verify that you are not a robot</label>
                        <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_KEY;?>" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div>
                        <input class="form-control d-none" name="recaptcha" data-recaptcha="true" required data-error="Please complete the Captcha"/>
                        <br/>
                    </div>

<!--                    <div class="form-group">-->
<!--                        <div class="form-check">-->
<!--                            <label class="form-check-label">-->
<!--                                <input type="checkbox" name="remember" class="form-input-styled" data-fouc required>-->
<!--                                Accept <a href="terms_condition" target="_blank">Website Terms and Conditions</a>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </div>-->
                    <button type="submit" name="signup" disabled class="btn btn-primary mmy_btn btn-block signup_btn">Register</button>
                </form>
                <!-- /page content -->
            </div>
            <div class="col-md-8">
                <img style="height:100%;width: 105%" id="login_img" src="assets/images/back.jpg">
            </div>
        </div>
    </div>
</div>

<script src="assets/js/passwordmeter/zxcvbn.js" type="text/javascript"></script>
<script src="assets/js/passwordmeter/index.js?v=1.1" type="text/javascript"></script>
<script src="assets/js/signup.js?v=1.4" type="text/javascript"></script>
<?php
    require("includes/footer.php");
?>
<script>
    $('a[data-toggle="tooltip"]').tooltip({
        sanitize: false,
        animated: 'fade',
        placement: 'bottom',
        html: true,
        viewport: '#signup-form'
    });
</script>
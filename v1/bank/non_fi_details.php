<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

//$token = AuthModel::generateToken();
if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";


Core::activityLog("Non Partnered FI Additional Details");
$user_data = AuthModel::getUserdata();

if ( $user_data['password_changed'] == 1 || $user_data['is_non_partnered_fi'] != 1 ){
    if ( $user_data['description'] == "Bank" ) {
        echo "<script>location='" . BASE_URL . "/bank/index'</script>";
        exit();
    }else{
        echo "<script>location='" . BASE_URL . "/depositor/index'</script>";
        exit();
    }
}

$demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
    if ( isset($_POST['non_fi_details']) ){

        if($_POST['conf_password'] != $_POST['pass']){
            $error="Password does not match";
        }else {
            // now update password & insurance

            $uploaded_profile_pic= $_POST['uploaded_profile_pic'];

            $user_id = $user_data['id'];
            $short_term_credit = $_POST['credit_rating'];
            $timezone = $_POST['timezone'];
            $deposit_insurance = $_POST['deposit_insurance'];
            $hashed_password =  password_hash($_POST['password'], PASSWORD_BCRYPT);
            $dateTime = Model::utcDateTime();

            db::insertRecord("INSERT INTO `passwords`(`hash`,`user_id`, `created_at`, `updated_at`) VALUES ('$hashed_password','$user_id','$dateTime','$dateTime')");
            db::query("DELETE FROM password_resets WHERE user_id='$user_id'");

            if (empty($short_term_credit)) {
                $short_term_credit = db::getCell(" SELECT id FROM credit_rating_type WHERE description='Any/Not Rated' LIMIT 1");
            }

            if (empty($deposit_insurance)) {
                $deposit_insurance = db::getCell(" SELECT id FROM deposit_insurance WHERE description='Any' LIMIT 1");
            }

            db::query("UPDATE `credit_rating` SET `credit_rating_type_id`='$short_term_credit', `deposit_insurance_id`='$deposit_insurance' WHERE `user_id`='$user_id'");

            Core::loginActivity("Non Partnered FI Additional Details updated ".$user_data['name'],$user_data['id']);
            RequestsModel::archiveTable($user_id,"users");
            db::query("UPDATE `users` SET password_changed=1 WHERE id='$user_id'");

            $address1 = $_POST['line1'];
            $address2 = $_POST['line2'];

            $city = $_POST['city'];
            $province = $_POST['province'];
            $postal = $_POST['postal'];
            $timezone = $_POST['timezone'];
            $tel = $_POST['tel'];
            $dateTime = Model::utcDateTime();

            db::preparedQuery("UPDATE `demographic_data` SET `address1`=?,`address2`=?,`city`=?,
                             `province`=?,`timezone`=?,`telephone`=?,`postal_code`=?,`created_at`=?,`updated_at`=? WHERE user_id=?", array("sssssssssi", $address1, $address2, $city, $province,$timezone,$tel,$postal,$dateTime,$dateTime,$user_id));

            RequestsModel::archiveTable($user_id,"users");
            db::preparedQuery("UPDATE `users` SET `profile_pic`=? WHERE `id`=?", array("si", (empty($uploaded_profile_pic) ? NULL : $uploaded_profile_pic), $user_id));

//            RequestsModel::archiveTable($user_id, "users", "status to ACTIVE");
            echo "<script>swal({title:'', text:'Account setting updated successfully.', type:'success',timer: 5000}).then((value) => { window.location='".BASE_URL."/bank/requests' });</script>";exit();
        }
    }

    $token = AuthModel::generateToken();
?>
        <link rel="stylesheet" href="<?php echo BASE_URL?>/assets/css/passwordmeter/custom.css?v=1.3" />
        <link rel="stylesheet" href="<?php echo BASE_URL;?>/assets/css/croppie.css" />
        <script src="<?php echo BASE_URL;?>/assets/js/croppie.js"></script>
<!--        <script src="--><?php //echo BASE_URL;?><!--/assets/js/jquery.dirty.js"></script>-->
        <style>
            .tooltip-inner{
                background: transparent!important;
            }
            .tooltip-inner > img{
                width: 400px!important;
            }
            #old_password_error{
                display: none;
            }
            #new_password_error{
                display: none;
            }
            .croppie-container{
                height: auto!important;
            }
            .char_type_text{
                padding-top: 20px;
            }
            .char_type{
                margin-top:10px!important;
                width: 16.66667% !important;
            }
        </style>
		<!-- Main content -->
		<div class="content-wrapper">

            <div class="content">
                <!-- Main charts -->
                <div class="row">
                    <div class="col-xl-12">

                        <!-- Traffic sources -->
                        <div class="card">
                            <div class="card-header header-elements-inline">

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">

                                            <fieldset class="col-md-10">
                                                <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i> Account Settings</legend>
                                                <p style="color: red">Please complete account settings in order to use the Yield Exchange Limited Version</p>
                                                <form action="#" class="form" method="post" id="myform1" enctype="multipart/form-data">
                                                    <input type="hidden" class="uploaded_profile_pic" name="uploaded_profile_pic" value="<?php echo !empty($user_data['profile_pic']) ? $user_data['profile_pic'] : ''; ?>"/>
                                                    <?php
                                                    if (isset($error)){
                                                    ?>
                                                        <div class="alert alert-info"><?php echo $error;?></div>
                                                        <br/>
                                                    <?php
                                                    }
                                                    ?>

                                                    <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                                    <div class="form-group row profile-image">
                                                        <?php
                                                        if ( !empty($user_data['profile_pic']) ){
                                                            ?>
                                                            <img style="height:100px;" src="image/<?php echo $user_data['profile_pic']; ?>" />
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <span class="i-initial-inverse-big"><span><?php echo !empty($user_data['name'][0]) ? Core::render($user_data['name'][0]) : 'Y'?></span></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                    if ( !empty($user_data['profile_pic']) ){
                                                        ?>
                                                        <div class="form-group row">
                                                            <div class="col-lg-5">
                                                                <a href="javascript:void()" class="btn btn-warning btn-sm no-page-exit-alert btn-remove-profile-image">Remove Profile Image</a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="form-group row">
                                                        <div class="col-lg-8">
                                                            <label style="font-weight:bold">Upload Image:</label>
                                                            <input type="file" name="file" class="form-control attach_image" />
                                                        </div>
                                                    </div>
                                                <div class="row" style="margin-top: 20px">
                                                    <div class="col-md-8" id="complexity">
                                                        <input id="lgd_out_pg_pass" name="pass" class="password form-control" type="password" onkeyup="checkThisPassword(this.value);" placeholder="Your password *" value=""  required/>
                                                        <span id="complexity-span" class="pmshow">No Password</span>
                                                    </div>
                                                    <div class="generate_button chars_holder row col-md-12">
                                                        <div class="char_type col-md-2" id="special_count">
                                                            <span class="char_type_text" style="padding-left: 15px;">Symbols</span>
                                                        </div>

                                                        <div class="char_type col-md-2" id="digits_count">
                                                            <span class="char_type_text" style="padding-left: 15px;">Numbers</span>
                                                        </div>

                                                        <div class="char_type col-md-2" id="upper_count">
                                                            <span class="char_type_text" style="padding-left: 15px;">Upper case</span>
                                                        </div>
                                                        <div class="char_type col-md-2" id="lower_count">
                                                            <span class="char_type_text" style="padding-left: 15px;">Lower case</span>
                                                        </div>
                                                        <div class="breaker">&nbsp;</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="margin-top: 20px">
                                                    <div class="col-lg-8">
                                                        <label style="font-weight:bold">Re-Enter Password <span style="color:red">*</span></label>
                                                        <input  type="password" id="myInput1"  name="conf_password" class="form-control pass_confirm" placeholder="Re-Enter Password" value="" required />
                                                    </div>
                                                    <div class="col-lg-8">
                                                    <span id="pass_error_confirm" style="color:red"></span>
                                                    </div>
                                                </div>

                                                    <div class="form-group">
                                                        <div class="form-check" style="padding-left: 0px">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-input-styled" onclick="myFunction(this)"> Show Password
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-lg-8">
                                                            <!-- <div class="col-lg-12"> -->
                                                            <label style="font-weight:bold">Address Line 1:</label>
                                                            <input type="text" name="line1" class="form-control myinput" placeholder="Address Line 1" value="<?php echo isset($_POST['line1']) ? $_POST['line1']: '' ?>" required />
                                                            <!-- </div> -->
                                                        </div>
                                                        <span style="color:red">*</span>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-lg-8">
                                                            <!-- <div class="col-lg-12"> -->
                                                            <label style="font-weight:bold">Address Line 2:</label>
                                                            <input type="text" name="line2" class="form-control myinput" placeholder="Address Line 2" value="<?php echo isset($_POST['line1']) ? $_POST['line1']: '' ?>" />
                                                            <!-- </div> -->
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-lg-8">
                                                            <!-- <div class="col-lg-12"> -->
                                                            <label style="font-weight:bold">City:</label>
                                                            <input type="text" name="city" class="form-control myinput" placeholder="City" value="<?php echo isset($_POST['line1']) ? $_POST['line1']: '' ?>" required />
                                                            <!-- </div> -->
                                                        </div>
                                                        <span style="color:red">*</span>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-lg-8">
                                                            <!-- <div class="col-lg-12"> -->
                                                            <label style="font-weight:bold">Province/Territory:</label>
                                                            <select  name="province" class="form-control" required>
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
                                                            <!-- </div> -->
                                                        </div>
                                                        <span style="color:red">*</span>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-lg-8">
                                                            <!-- <div class="col-lg-12"> -->
                                                            <label style="font-weight:bold">Postal Code:</label>
                                                            <input type="text" name="postal" class="form-control myinput" placeholder="Postal Code" value="<?php echo isset($_POST['line1']) ? $_POST['line1']: '' ?>" />
                                                            <!-- </div> -->
                                                        </div>
                                                        <span style="color:red">*</span>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-lg-8">
                                                            <!-- <div class="col-lg-12"> -->
                                                            <label style="font-weight:bold">TimeZone: <span style="color:red">*</span></label>
                                                            <select  name="timezone" class="form-control" required>
                                                                <option value="">Select</option>
                                                                <?php
                                                                $list_timezones = Model::timezonesList();
                                                                foreach ($list_timezones as $key => $list_timezone) {
                                                                    ?>
                                                                    <option <?php echo (isset($_POST['timezone']) ? $_POST['timezone'] : $demographic_user_data['timezone']) == $key ? "selected" : ""; ?> value="<?php echo $key;?>"><?php echo $list_timezone;?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <!-- </div> -->
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <br/><br/>
                                                            <span style="font-weight:bold;font-size:18px;border:1px solid #333; padding:6px;">
                                                        <?php
                                                        echo BankModel::getBankDateTime((isset($_POST['timezone']) ? $_POST['timezone'] : $demographic_user_data['timezone']));
                                                        ?>
                                                    <span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-lg-8">
                                                            <!-- <div class="col-lg-12"> -->
                                                            <label style="font-weight:bold">Telephone No:</label>
                                                            <input type="text" name="tel" class="form-control myinput" value="<?php echo (isset($_POST['tel']) ? $_POST['tel'] : $demographic_user_data['telephone']); ?>" placeholder="Telephone" required />
                                                            <!-- </div> -->
                                                        </div>
                                                        <span style="color:red">*</span>
                                                    </div>

                                                    <label style="color:skyblue;font-weight:bold">Credit Rating And Deposit Insurance </label>
                                                        <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                                        <div class="form-group row">
                                                            <div class="col-lg-8">
                                                                <div style="display: inline-block;width: 90%">
                                                                    <label style="font-weight:bold">Short Term Credit Rating</label>
                                                                    <select class="form-control" name="credit_rating" required>
                                                                        <option value="">--Select--</option>
                                                                        <?php
                                                                        $credit_rating_type = db::getRecords("SELECT * FROM `credit_rating_type`");
                                                                        if (!empty($credit_rating_type)){
                                                                            foreach ( $credit_rating_type as $item) {
                                                                                ?>
                                                                                <option <?php echo (isset($_POST['credit_rating']) ? $_POST['credit_rating'] : $item['description']) == "Any/Not Rated" ? "selected" : "";?> value="<?php echo $item['id'];?>"><?php echo $item['description'] == "Any/Not Rated" ? "Not Rated" : $item['description'];?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-1" style="display: inline-block;">
                                                                    <a data-toggle="tooltip" title="<img src='../assets/img/credit_rating.png' style='width:100%' />">
                                                                        <i class="fa fa-info-circle" style="font-size: 20px"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-lg-8">
                                                                <!-- <div class="col-lg-12"> -->
                                                                <label style="font-weight:bold"> Deposit insurance</label>
                                                                <select class="form-control" name="deposit_insurance" required>
                                                                    <option value="">--Select--</option>
                                                                    <?php
                                                                    $deposit_insurance = db::getRecords("SELECT * FROM `deposit_insurance`");
                                                                    if (!empty($deposit_insurance)){
                                                                        foreach ( $deposit_insurance as $item) {
                                                                    ?>
                                                                            <option <?php echo (!empty($_POST['deposit_insurance']) ? $_POST['deposit_insurance'] : $item['description']) == "Any" ? "selected" : "";?> value="<?php echo $item['id'];?>"><?php echo $item['description'];?></option>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <!-- </div> -->
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-8">
                                                                <button type="submit" name="non_fi_details" class="btn btn-primary mmy_btn">Save</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /support tickets -->
                </div>
            </div>
            <script src="<?php echo BASE_URL?>/assets/js/passwordmeter/zxcvbn.js" type="text/javascript"></script>
            <script src="<?php echo BASE_URL?>/assets/js/passwordmeter/index.js?v=1.1" type="text/javascript"></script>
            <script src="<?php echo BASE_URL?>/assets/js/signup.js?v=1.4" type="text/javascript"></script>
<?php
    require_once BASE_DIR."/includes/profile_uploader.php";
    require_once "footer.php";
}
?>
<script>

   // var formSubmitting = false;
// var setFormSubmitting = function() { formSubmitting = true; };

// window.onload = function() {
//     window.addEventListener("beforeunload", function (e) {
//         if (formSubmitting) {
//             return undefined;
//         }
//
//         var confirmationMessage = 'It looks like you have been editing something. '
//                                 + 'If you leave before saving, your changes will be lost.';
//
//         (e || window.event).returnValue = confirmationMessage; //Gecko + IE
//         return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
//     });
// };
    $(document).ready(function() {

        $(".pass_confirm").keyup(function () {
            let value = $(this).val();

            if (value !== "") {

                if (value !== $(".password").val()) {
                    $("#pass_error_confirm").show();
                    $("#pass_error_confirm").html("Password does not match");
                    // $(".mmy_btn").attr('disabled', true);
                } else {
                    $("#pass_error_confirm").hide();
                    $("#pass_error_confirm").html("");
                    // if (password_score >= 3){
                    //  $(".mmy_btn").removeAttr('disabled');
                    // }
                }

            }
        });

        $(".password").keyup(function () {
            let value = $(this).val();

            if (value !== "") {

                if (value !== $(".pass_confirm").val()) {
                    $("#pass_error_confirm").show();
                    $("#pass_error_confirm").html("Password does not match");
                    // $(".mmy_btn").attr('disabled', true);
                } else {
                    $("#pass_error_confirm").hide();
                    $("#pass_error_confirm").html("");
                    // if (password_score >= 3){
                    // $(".mmy_btn").removeAttr('disabled');
                    // }
                }

            }
        });

    });

    function myFunction(thi) {
        let x = document.getElementById("lgd_out_pg_pass");
        let x_ = document.getElementById("myInput1");
        if (thi.checked) {
            x.type = "text";
            x_.type = "text";
        } else {
            x.type = "password";
            x_.type = "password";
        }
    }
    $(document).on('click','a',function (e) {
        if ( !$(this).hasClass("no-page-exit-alert") ) {
            $this = $(this);
            swal("Please provide the following details before you proceed.");
            return false;
        }
    });
</script>
<script>
    let _inverse_big ='<span class="i-initial-inverse-big"><span><?php echo !empty($user_data['name'][0]) ? Core::render($user_data['name'][0]) : 'Y'?></span></span>';
    $(function() {
        $(document).on('submit','#myform1',function () {
            if($(".pass_confirm").val() != $(".password").val() ){
                swal("Password does not match");
                return false;
            }
            return confirm("Would like to save changes?");
        });
    });
    $('a[data-toggle="tooltip"]').tooltip({
        animated: 'fade',
        placement: 'bottom',
        html: true,
        viewport: '#myform3'
    });
    $(document).on("click",".btn-remove-profile-image",function (e) {
        e.preventDefault();
        if ( confirm("Are you sure to proceed and remove?") ){
            $(".uploaded_profile_pic").val("");
            $("div.profile-image").html(_inverse_big);
            $(this).hide();
        }
    });

    $(function() {
        $(".form").dirty({
            preventLeaving: true,
            leavingMessage: 'Are you sure you want to leave this page? Your request changes won\'t be saved!'
        });
    });
</script>

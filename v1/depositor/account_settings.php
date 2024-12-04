<?php
session_start();
require_once "header.php";

$token = AuthModel::generateToken();
if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";
    Core::activityLog("Depositor Account Settings");
    $user_data = AuthModel::getUserdata();
    $demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
    $ratings = Core::getRatings( $user_data['id'] );
    $account_doc = DepositorModel::getDepositorDoc($user_data['id']);
?>
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
            #hover-content {
            display:none;
            }
            #parent:hover #hover-content {
            display:block;
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
        </style>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
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
                                        <form action="logic" class="form" method="post" id="myform3" enctype="multipart/form-data">
<!--                                        <form action="logic" method="post" id="myform1" enctype="multipart/form-data">-->
                                            <div class="form-group row profile-image">
                                                <?php
                                                if (!empty($user_data['profile_pic'])){
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
                                                    <a href="javascript:void()" class="btn btn-warning btn-sm btn-remove-profile-image">Remove Profile Image</a>
                                                </div>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <label style="font-weight:bold"><div id="parent"> Upload Image:
                                                        <div style="height: 20px;
                                                          width: 20px; 
                                                          color:#1DA1F2;                                                         
                                                          margin-left:8px;
                                                          display: inline-block;">
                                                                    <span style="margin-left:6.5px;"> <i class="fa fa-info-circle" style="font-size: 20px"></i></span></div>
                                                                <div id="hover-content" style="min-height:30px;">
                                                                   Max. Image size: 500 x 500 ; Allowable image types: png, jpg
                                                                </div>
                                                            </div></label>
                                                    <input type="file" name="file" class="form-control attach_image" />
                                                </div><span style="color:red">*</span>

                                            </div>
<!--                                        </form>-->
<!--                                        <form action="logic" method="post" id="myform2" enctype="multipart/form-data">-->
<!--                                            <input type="hidden" name="_token" value="--><?php //echo $token; ?><!--"/>-->
                                            <a href="logic?update_password=1&&_token=<?php echo $token; ?>" class="btn btn-primary mmy_btn update_password" style="margin-bottom: 5px">
                                                Reset Password
                                            </a>
<!--                                        </form>-->

                                            <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                            <input type="hidden" class="uploaded_profile_pic" name="uploaded_profile_pic" value="<?php echo !empty($user_data['profile_pic']) ? $user_data['profile_pic'] : ''; ?>"/>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <!-- <div class="col-lg-12"> -->
                                                        <label style="font-weight:bold">Institution</label>
                                                        <input disabled type="text" name="institution" class="form-control" placeholder="Institution" value="<?php echo Core::render($user_data['name']); ?>" required />
                                                    <!-- </div> -->
                                                </div><span style="color:red"></span>
                                            </div>

                                            <div class="form-group row" style="display: none">
                                                <div class="col-lg-8">
                                                    <!-- <div class="col-lg-12"> -->
                                                        <label style="font-weight:bold">Account Opening Documents</label>
                                                        <input type="text" name="account_document" class="form-control" placeholder="Account Opening Documents" value="<?php echo !empty($account_doc['account_doc'])  ? Core::render($account_doc['account_doc']) : ''; ?>" />
                                                    <!-- </div> -->
                                                </div>
                                                <span style="color:red">*</span>
                                                <br/>
                                                <?php
                                                    if ( !empty($account_doc) ){
                                                ?>
                                                    <span><a target="_blank" href="<?php echo Core::render($account_doc['account_doc']); ?>">View Document(s)</a></span>
                                                <?php
                                                    }
                                                ?>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <!-- <div class="col-lg-12"> -->
                                                        <label style="font-weight:bold">Address Line 1:</label>
                                                        <input type="text" name="line1" class="form-control" placeholder="Address Line 1" value="<?php echo Core::render($demographic_user_data['address1']); ?>" required>
                                                    <!-- </div> -->
                                                </div><span style="color:red">*</span>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <!-- <div class="col-lg-12"> -->
                                                        <label style="font-weight:bold">Address Line 2:</label>
                                                        <input type="text" name="line2" class="form-control" placeholder="Address Line 2" value="<?php echo Core::render($demographic_user_data['address2']); ?>" />
                                                    <!-- </div> -->
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                    <label style="font-weight:bold">City:</label>
                                                    <input type="text" name="city" class="form-control" placeholder="City" value="<?php echo Core::render($demographic_user_data['city']); ?>" required />
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <!-- <div class="col-lg-12"> -->
                                                        <label style="font-weight:bold">Province/Territory:</label>
                                                        <select  name="province" class="form-control" required>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Alberta" ? "selected" : ""; ?> value="Alberta">Alberta</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "British Columbia" ? "selected" : ""; ?> value="British Columbia">British Columbia</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Manitoba" ? "selected" : ""; ?> value="Manitoba">Manitoba</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "New Brunswick" ? "selected" : ""; ?> value="New Brunswick">New Brunswick</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Newfoundland and Labrador" ? "selected" : ""; ?> value="Newfoundland and Labrador">Newfoundland and Labrador</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Nova Scotia" ? "selected" : ""; ?> value="Nova Scotia">Nova Scotia</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Ontario" ? "selected" : ""; ?> value="Ontario">Ontario</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Prince Edward Island" ? "selected" : ""; ?> value="Prince Edward Island">Prince Edward Island</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Quebec" ? "selected" : ""; ?> value="Quebec">Quebec</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Saskatchewan" ? "selected" : ""; ?> value="Saskatchewan">Saskatchewan</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Nunavut" ? "selected" : ""; ?> value="Nunavut">Nunavut</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Quebec" ? "selected" : ""; ?> value="Quebec">Quebec</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "NorthWest Territories" ? "selected" : ""; ?> value="NorthWest Territories">NorthWest Territories</option>
                                                            <option <?php echo Core::render($demographic_user_data['province']) == "Yukon" ? "selected" : ""; ?> value="Yukon">Yukon</option>
                                                        </select>
                                                    <!-- </div> -->
										        </div><span style="color:red">*</span>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <!-- <div class="col-lg-12"> -->
                                                        <label style="font-weight:bold">Postal Code:</label>
                                                        <input type="text" name="postal" class="form-control myinput" placeholder="Postal Code" value="<?php echo Core::render($demographic_user_data['postal_code']); ?>" />
                                                    <!-- </div> -->
                                                </div>
                                                <span style="color:red">*</span>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <!-- <div class="col-lg-12"> -->
                                                        <label style="font-weight:bold">Timezone:  <span style="color:red">*</span></label>
                                                        <select  name="timezone" class="form-control" required>
                                                            <option value="">Select</option>
                                                            <?php
                                                            $list_timezones = Model::timezonesList();
                                                            foreach ($list_timezones as $key => $list_timezone) {
                                                            ?>
                                                                <option <?php echo empty($demographic_user_data['timezone']) ? ($key=="Central" ? "selected" :"") : ($demographic_user_data['timezone']== $key ? "selected" : ""); ?> value="<?php echo $key;?>"><?php echo $list_timezone;?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    <!-- </div> -->
                                                </div>
                                                <div class="col-lg-4">
                                                <br/>
                                                <br/>
                                                <span style="font-weight:bold;font-size:18px;border:1px solid #333; padding:6px;">
                                                    <?php 
                                                        echo DepositorModel::getDepositorDateTime($demographic_user_data['timezone']);
                                                    ?>
                                                <span>
                                                </div>
                                            
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <!-- <div class="col-lg-12"> -->
                                                        <label style="font-weight:bold">Telephone No:</label>
                                                        <input type="text" name="tel" class="form-control" value="<?php echo Core::render($demographic_user_data['telephone']); ?>" placeholder="Telephone" required>
                                                    <!-- </div> -->
                                                </div><span style="color:red">*</span>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-8">
                                                    <!-- <div class="col-lg-12"> -->
                                                        <label style="font-weight:bold">Email:</label>
                                                        <input type="text" name="email" class="form-control myinput" value="<?php echo Core::render($user_data['email']); ?>" placeholder="Email" readonly>
                                                    <!-- </div> -->
                                                </div>
                                                <span style="color:red">*</span>
                                            </div>
                                            <button type="submit" name="profile_data" class="btn btn-primary mmy_btn">
                                                Save
                                            </button>
                                       </form>
                                                
                                              <label style="color:skyblue;font-weight:bold;display: none;">Credit rating and deposit insurance Preference</label>
                                              <form action="logic" class="form" method="post" id="myform3" enctype="multipart/form-data" style="display: none;">
                                                <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                                <div class="form-group row">
                                                    <div class="col-lg-8">
                                                        <div style="display: inline-block;">
                                                            <label style="font-weight:bold">Short term DBRS rating Preference</label>
                                                            <div class="form-group">
                                                                <select class="form-control" name="short">
                                                                    <option value="">--Select--</option>
                                                                    <?php
                                                                    $credit_rating_type = db::getRecords("SELECT * FROM `credit_rating_type`");
                                                                    if (!empty($credit_rating_type)){
                                                                        foreach ( $credit_rating_type as $item) {
                                                                            ?>
                                                                            <option <?php echo strtolower($item['description']) == strtolower(@$ratings['credit_rating']) ? 'selected' : '';?> value="<?php echo $item['id'];?>"><?php echo $item['description'];?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="display: inline-block;">
                                                            <a data-toggle="tooltip" title="<img src='../assets/img/credit_rating.png' style='width:100%' />">
                                                                <i class="fa fa-info-circle" style="font-size: 20px"></i>
                                                            </a>
                                                        </div>
                                                    </div><span style="color:red">*</span>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-8">
                                                        <!-- <div class="col-lg-12"> -->
                                                            <label style="font-weight:bold"> Deposit insurance Preference</label>
                                                            <select class="form-control" name="long">
                                                                <option value="">--Select--</option>
                                                                <?php
                                                                $deposit_insurance = db::getRecords("SELECT * FROM `deposit_insurance`");
                                                                if (!empty($deposit_insurance)){
                                                                    foreach ( $deposit_insurance as $item) {
                                                                        ?>
                                                                        <option <?php echo strtolower($item['description']) == strtolower(@$ratings['deposit_insurance']) ? 'selected' : '';?> value="<?php echo $item['id'];?>"><?php echo $item['description'];?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        <!-- </div> -->
                                                    </div><span style="color:red">*</span>
                                                </div>
                                                    <div class="col-lg-8">
                                                        <button type="submit" name="saveratingdeposit" class="btn btn-primary mmy_btn   ">
                                                            Save
                                                        </button>
                                                    </div>
                                               </form>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>
							</div>
						</div>
						<!-- /support tickets -->
					</div>
				</div>
            </div>
<?php
    require_once BASE_DIR."/includes/profile_uploader.php";
    require_once "footer.php";
}
?>
<script> let _inverse_big ='<span class="i-initial-inverse-big"><span><?php echo !empty($user_data['name'][0]) ? Core::render($user_data['name'][0]) : 'Y'?></span></span>';
    $(function() {
        $(".form").dirty({
            preventLeaving: true,
            leavingMessage: 'Are you sure you want to leave this page? Your request changes won\'t be saved!',
            // onDirty: function(){
            //     $(".message").html("The Form Is Dirty!")
            // }
        });

        // $('.form').areYouSure( {message:'Are you sure you want to leave this page? Your request changes won\'t be saved!'} );
        // $(document).on('submit','.form',function () {
        //     return confirm("Would like to save changes?");
        // });
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
</script>


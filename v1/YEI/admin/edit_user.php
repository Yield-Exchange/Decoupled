<?php
require_once("header.php");
if( AdminModel::isLoggedIn() ) {
    $token = AuthModel::generateToken();

    require_once("sidebar.php");
    if (isset($_GET['id'])){
        $user_data = AuthModel::getUserDataByID(Core::urlValueDecrypt($_GET['id']));
        if (!empty($user_data)){

            $demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
            $ratings = Core::getRatings( $user_data['id'] );

            Core::activityLog("admin edit user");
?>
            <style>
                .tooltip-inner{
                    background: transparent!important;
                }
                .tooltip-inner > img{
                    width: 400px!important;
                }
            </style>
            <link rel="stylesheet" href="<?php echo BASE_URL;?>/assets/css/croppie.css" />
            <script src="<?php echo BASE_URL;?>/assets/js/croppie.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<!--            <script src="--><?php //echo BASE_URL;?><!--/assets/js/jquery.dirty.js"></script>-->
            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-light">
                    <div class="page-header-content header-elements-md-inline">
                        <div class="page-title d-flex">
                            <a href="index" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                            <span class="breadcrumb-item active"><strong><?php echo Core::render($user_data['name']); ?></strong> Account Setting </span>
                        </div>

                        <div class="header-elements d-none">
                            <div class="d-flex justify-content-center">
                                <div class="header-elements d-none">
                                    <div class="d-flex justify-content-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /page header -->

                <!-- Content area -->
                <div class="content">
                    <!-- Main charts -->
                    <div class="row">
            <div class="col-xl-12">

            <!-- Traffic sources -->
            <div class="card">
                <div class="card-header header-elements-inline">

                    <div class="card-body">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <form action="logic" method="post" id="myform3" enctype="multipart/form-data">
<!--                            <form action="logic" method="post" id="myform1" enctype="multipart/form-data">-->
                                <div class="form-group row profile-image">
                                    <?php
                                    if (!empty($user_data['profile_pic'])){
                                    ?>
                                        <img style="height:100px;" src="<?php echo $user_data['description'] == 'Depositor' ? '../../depositor' : '../../bank';?>/image/<?php echo $user_data['profile_pic']; ?>" />
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
                                        <label style="font-weight:bold">Upload Image:</label>
                                        <input type="file" name="file" class="form-control attach_image" />
                                    </div><span style="color:red">*</span>
                                </div>
<!--                            </form>-->

                                <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                <input type="hidden" name="id" value="<?php echo Core::urlValueEncrypt($user_data['id']);?>" />
                                <input type="hidden" class="uploaded_profile_pic" name="uploaded_profile_pic" value="<?php echo !empty($user_data['profile_pic']) ? $user_data['profile_pic'] : ''; ?>"/>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold">Institution</label>
                                            <input type="text" name="institution" class="form-control myinput" placeholder="Institution" value="<?php echo Core::render($user_data['name']); ?>" required />
                                        </div>
                                    </div>
                                    <span style="color:red">*</span>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold">Address Line 1:</label>
                                            <input type="text" name="line1" class="form-control myinput" placeholder="Address Line 1" value="<?php echo Core::render($demographic_user_data['address1']); ?>" required />
                                        </div>
                                    </div>
                                    <span style="color:red">*</span>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold">Address Line 2:</label>
                                            <input type="text" name="line2" class="form-control myinput" placeholder="Address Line 2" value="<?php echo Core::render($demographic_user_data['address2']); ?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold">City:</label>
                                            <input type="text" name="city" class="form-control myinput" placeholder="City" value="<?php echo Core::render($demographic_user_data['city']); ?>" required />
                                        </div>
                                    </div>
                                    <span style="color:red">*</span>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold">Province/Territory:</label>
                                            <select  name="province" class="form-control" required>
                                                <option <?php echo $demographic_user_data['province'] == "Alberta" ? "selected" : ""; ?> value="Alberta">Alberta</option>
                                                <option <?php echo $demographic_user_data['province'] == "British Columbia" ? "selected" : ""; ?> value="British Columbia">British Columbia</option>
                                                <option <?php echo $demographic_user_data['province'] == "Manitoba" ? "selected" : ""; ?> value="Manitoba">Manitoba</option>
                                                <option <?php echo $demographic_user_data['province'] == "New Brunswick" ? "selected" : ""; ?> value="New Brunswick">New Brunswick</option>
                                                <option <?php echo $demographic_user_data['province'] == "Newfoundland and Labrador" ? "selected" : ""; ?> value="Newfoundland and Labrador">Newfoundland and Labrador</option>
                                                <option <?php echo $demographic_user_data['province'] == "Nova Scotia" ? "selected" : ""; ?> value="Nova Scotia">Nova Scotia</option>
                                                <option <?php echo $demographic_user_data['province'] == "Ontario" ? "selected" : ""; ?> value="Ontario">Ontario</option>
                                                <option <?php echo $demographic_user_data['province'] == "Prince Edward Island" ? "selected" : ""; ?> value="Prince Edward Island">Prince Edward Island</option>
                                                <option <?php echo $demographic_user_data['province'] == "Quebec" ? "selected" : ""; ?> value="Quebec">Quebec</option>
                                                <option <?php echo $demographic_user_data['province'] == "Saskatchewan" ? "selected" : ""; ?> value="Saskatchewan">Saskatchewan</option>
                                                <option <?php echo $demographic_user_data['province'] == "Nunavut" ? "selected" : ""; ?> value="Nunavut">Nunavut</option>
                                                <option <?php echo $demographic_user_data['province'] == "Quebec" ? "selected" : ""; ?> value="Quebec">Quebec</option>
                                                <option <?php echo $demographic_user_data['province'] == "NorthWest Territories" ? "selected" : ""; ?> value="NorthWest Territories">NorthWest Territories</option>
                                                <option <?php echo $demographic_user_data['province'] == "Yukon" ? "selected" : ""; ?> value="Yukon">Yukon</option>
                                            </select>
                                        </div>
                                    </div>
                                    <span style="color:red">*</span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold">Postal Code:</label>
                                            <input type="text" name="postal" class="form-control myinput" placeholder="Postal Code" value="<?php echo Core::render($demographic_user_data['postal_code']); ?>" />
                                        </div>
                                    </div>
                                    <span style="color:red">*</span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold">TimeZone: <span style="color:red">*</span></label>
                                            <select  name="timezone" class="form-control" required>
                                                <option value="">Select</option>
                                                <?php
                                                $list_timezones = Model::timezonesList();
                                                foreach ($list_timezones as $key => $list_timezone) {
                                                ?>
                                                    <option <?php echo Core::render($demographic_user_data['timezone']) == $key ? "selected" : ""; ?> value="<?php echo $key;?>"><?php echo $list_timezone;?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <br/><br/>
                                        <span style="font-weight:bold;font-size:18px;border:1px solid #333; padding:6px;">
                                            <?php
                                                echo BankModel::getBankDateTime($demographic_user_data['timezone']);
                                            ?>
                                        <span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold">Telephone No:</label>
                                            <input type="text" name="tel" class="form-control myinput" value="<?php echo Core::render($demographic_user_data['telephone']); ?>" placeholder="Telephone" required />
                                        </div>
                                    </div>
                                    <span style="color:red">*</span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold">Email:</label>
                                            <input type="text" name="email" class="form-control myinput" value="<?php echo Core::render($user_data['email']); ?>" placeholder="Email" readonly/>
                                        </div>
                                    </div><span style="color:red">*</span>
                                </div>

                            <?php
                            if ( in_array($user_data['description'],['Bank']) ){
                            ?>
                            <label style="color:skyblue;font-weight:bold">Credit Rating And Deposit Insurance </label>

<!--                            <form action="logic" method="post" id="myform3" enctype="multipart/form-data">-->
                                <input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
                                <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                <div class="form-group row">
                                    <div class="col-lg-9">
                                        <div class="col-md-10" style="display: inline-block;">
                                            <label style="font-weight:bold">Short Term Credit Rating</label>
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
                                        <div class="col-md-1" style="display: inline-block;">
                                            <a data-toggle="tooltip" title="<img src='../../assets/img/credit_rating.png' style='width:100%' />">
                                                <i class="fa fa-info-circle" style="font-size: 20px"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <span style="color:red">*</span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <div class="col-lg-12">
                                            <label style="font-weight:bold"> Deposit insurance</label>
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
                                        </div>
                                    </div>
                                    <span style="color:red">*</span>
                                </div>
                                <button type="submit" name="profile_data" class="btn btn-primary mmy_btn myinput">
                                    Save
                                </button>
                            </form>

<!--                                <div class="row">-->
<!--                                    <button type="submit" name="saverating" class="btn btn-primary mmy_btn">Save</button>-->
<!--                                </div>-->
<!--                            </form>-->
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            </div>
                    </div>
                </div>
            </div>
            <script>
                $('a[data-toggle="tooltip"]').tooltip({
                    animated: 'fade',
                    placement: 'bottom',
                    html: true,
                    viewport: '#myform3'
                });
            </script>

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
                    $(document).on('submit','.form',function () {
                        return confirm("Would like to save changes?");
                    });
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

<?php
            $_GET['id'] = Core::urlValueDecrypt($_GET['id']);
            $is_Admin=1;
            require_once BASE_DIR."/includes/profile_uploader.php";
        }
    }
}
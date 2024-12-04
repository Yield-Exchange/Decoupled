<?php
session_start();
require_once "header.php";
require_once "../config/RequestsModel.php";

$token = AuthModel::generateToken();
if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";

    if (isset($_GET['rqid'])) {
        $id = Core::urlValueDecrypt($_GET['rqid']);
        $offer_id = Core::urlValueDecrypt($_GET['id']);

        $request = RequestsModel::getRequestByID($id,false,true);
        if (!empty($request)){
            $user_data = AuthModel::getUserdata();

            $depositor_data = AuthModel::getUserDataByID($request['user_id']);
//            $depositor_demographic_data = AuthModel::getUserDemographicData($request['user_id']);
            $bank_demographic_data = AuthModel::getUserDemographicData($user_data['id']);

            $offer = BankModel::getOfferByID($offer_id,true);
            Core::activityLog("Bank In Progress -> Edit");
          ?>
            <style>
                .inline-div{
                    display: inline-block;
                }
                .select2-selection{
                    padding-bottom: 30px!important;
                }
                .myinput{
                    border-radius: 0px;
                }
                p{
                    color:grey;
                    font-size: 13px;
                }
                .table-responsive{
                    padding-left:0px;
                }
                .hidden{
                    display: none;
                }
            </style>
            <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
            <!-- Select 2 -->
            <link href="../assets/css/select2.min.css" rel="stylesheet" type="text/css">
            <script src="../assets/js/select2.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
            <script src="<?php echo BASE_URL.'/assets/';?>js/moment-timezone.js"></script>
            <script type="text/javascript">
                let format = 'YYYY/MM/DD HH:mm:ss ZZ';
                let timeZone = <?php echo json_encode(Model::formattedTimezone($bank_demographic_data['timezone']));?>;
                let dateOfDepositWithUserTimezone = moment.utc(<?php echo json_encode($request['date_of_deposit']);?>).tz(timeZone);
            </script>
<!--            <script src="--><?php //echo BASE_URL;?><!--/assets/js/jquery.dirty.js"></script>-->
            <script src="js/bid.js?v=2.6"></script>
            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Content area -->
                <div class="content">
                    <!-- Main charts -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Support tickets -->
                          
                            <div class="card transparent-card">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card" style="min-height: 330px;">
                                            <div class="card-body">
                                                <div class="row justify-content-center">
                                                    <?php
                                                    if ( !empty($depositor_data['profile_pic']) ) {
                                                    ?>
                                                        <img src="../depositor/image/<?php echo $depositor_data['profile_pic']; ?>" width="80" height="80" alt=""/>
                                                    <?php
                                                    }else {
                                                    ?>
                                                        <div class="i-initial-inverse-big"><span><?php echo !empty($depositor_data['name'][0]) ? $depositor_data['name'][0] : 'Y'?></span></div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/Depositor.png';?>" class="img-responsive mr-2" height="25"/> <?php echo $depositor_data['name']; ?></p>
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/City.png';?>" class="img-responsive mr-2" height="25"/><?php echo $bank_demographic_data['city']; ?></p>
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/Province.png';?>" class="img-responsive mr-2" height="25"/><?php echo $bank_demographic_data['province']; ?></p>
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/Email.png';?>" class="img-responsive mr-2" height="25"/><?php echo $depositor_data['email'];?></p>
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/Telephone.png';?>" class="img-responsive mr-2" height="25"/><?php echo $bank_demographic_data['telephone'];?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class='card p-3' style="min-height: 330px;">
                                            <?php
                                                require_once BASE_DIR.'/bank/inc/request_summary.php';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 card">
                                    <div class="card-body">
                                        <h5 style="color:#2CADF5;font-weight:bold;">Edit Offer</h5>
                                        <!-- /main charts -->
                                        <form action="logic" id="bid_offer_form" autocomplete="off" method="post" enctype="multipart/form-data" class="bid_offer form-horizontal">
                                            <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                            <div class="row">
                                                <?php
                                                if ( !empty($user_data['description']) && $user_data['description'] == "Broker" ) {
                                                    ?>
                                                    <div class="form-group col-md-5">
                                                        <label style="font-weight:bold;">On Behalf Of</label>
                                                        <span style="color:red">*</span>
                                                        <select class="form-control select2" name="obo">
                                                            <option>--Select--</option>
                                                            <?php
                                                            $fi = BankModel::getFI();
                                                            foreach ($fi as $f) {
                                                            ?>
                                                                <option value="<?php echo $f['name']; ?>"><?php echo $f['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label style="font-weight:bold;">Minimum Amount</label> <span style="color:red" >*</span>
                                                        <input type="text" <?php echo $request['closing_date_time'] < Model::utcDateTime() ? 'disabled' : ''?> onchange="addThousands(this);" name="min_amount" id="min_amount" class="form-control col-lg-11" value="<?php echo Core::render(number_format(str_replace(',','',$offer['minimum_amount']))); ?>" placeholder="Minimum Amount" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label style="font-weight:bold;">Maximum Amount </label> <span style="color:red" >*</span>
                                                        <input type="text" <?php echo $request['closing_date_time'] < Model::utcDateTime() ? 'disabled' : ''?> onchange="addThousands(this);" name="max_amount" id="max_amount" class="form-control col-lg-11" value="<?php echo Core::render(number_format(str_replace(',','',$offer['maximum_amount']))); ?>" placeholder="Maximum Amount" required/>
                                                    </div>
                                                    <div class="form-group col-lg-11" style="padding-right: 0!important;padding-left: 0!important;">
                                                        <label style="font-weight:bold;">Interest Rate Offer <small>(Simple Annual Interest Rate)</small></label> <span style="color:red" >*</span>
                                                        <?php
                                                            if ($request['term_length_type'] != "HISA") {
                                                        ?>
                                                                <div class="form-group-feedback form-group-feedback-right">
                                                                    <input <?php echo $request['closing_date_time'] < Model::utcDateTime() ? 'disabled' : '' ?>
                                                                            type="number" name="nir" min="0.01"
                                                                            max="100" class="form-control col-lg-12"
                                                                            placeholder="Interest Rate"
                                                                            value="<?php echo Core::render($offer['interest_rate_offer']); ?>"
                                                                            step=".01" required/>
                                                                    <div class="form-control-feedback">
                                                                        <i style="margin-top: 12px;"
                                                                           class="fa fa-percent text-muted"></i>
                                                                    </div>
                                                                </div>
                                                        <?php
                                                            }else{
                                                        ?>
                                                                <div class="row">
                                                                    <div class="col-md-2 text-left">
                                                                        <label class="radio-inline"><input type="radio" class="rate_type" name="rate_type" value="fixed" <?php echo $offer['rate_type'] == "FIXED" ? "checked" : ""; ?> /> &nbsp;Fixed</label>
                                                                    </div>
                                                                    <div class="col-md-4 text-left">
                                                                        <label class="radio-inline"><input type="radio" class="rate_type" name="rate_type" value="variable" <?php echo $offer['rate_type'] == "VARIABLE" ? "checked" : ""; ?> /> &nbsp;Variable</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group-feedback form-group-feedback-right col-md-12 fixed_interest_rate_container <?php echo $offer['rate_type'] != "FIXED" ? "hidden" : ""; ?>">
                                                                        <input type="number" name="nir" min="0.01" max="100"
                                                                               class="form-control col-lg-12"
                                                                               placeholder="Fixed Interest Rate" value="<?php echo Core::render($offer['interest_rate_offer']); ?>" step=".01"
                                                                               required/>
                                                                        <div class="form-control-feedback">
                                                                            <i style="margin-top: 20px;"
                                                                               class="fa fa-percent text-muted"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-inline col-md-12 variable_rate_container <?php echo $offer['rate_type'] != "VARIABLE" ? "hidden" : ""; ?>">
                                                                        <input type="number" name="prime_rate" min="0.01" max="100"
                                                                               class="form-control col-md-5"
                                                                               disabled
                                                                               placeholder="Prime Rate " step=".01" value="<?php echo Core::render( Core::getSystemSettingsValue('prime_rate') ); ?>"
                                                                               style="width: 45%"/>

                                                                        <select class="form-control" name="rate_operator">
                                                                            <option <?php echo $offer['rate_operator'] == "+" ? "selected" : ""; ?> value="+">+</option>
                                                                            <option <?php echo $offer['rate_operator'] == "-" ? "selected" : ""; ?> value="-">-</option>
                                                                        </select>

                                                                        <input type="number" name="fixed_rate" min="0.01" max="100"
                                                                               class="form-control col-md-5"
                                                                               placeholder="Spread Rate " step=".01" value="<?php echo Core::render($offer['fixed_rate']); ?>"
                                                                               style="width: 45%"/>
                                                                    </div>
                                                                </div>
                                                         <?php
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label style="font-weight:bold;">Rate held Until  </label> <span  style="color:red" >*</span>
                                                        <input type="text" id="offer_expiry" required name="expdate" class="form-control col-md-12 datetimepicker" placeholder="Rate held Until" value="<?php echo Model::dateTimeFromUTC('Y-m-d H:i', $offer['rate_held_until']); ?>"/>
                                                        <input type="hidden" class="form-control col-lg-10" id="txtDate129" value="<?php echo Model::dateTimeFromUTC('Y-m-d',$request["deposit_start"],$bank_demographic_data['timezone']) ?>" required/>

                                                        <input type="hidden" name="reqid" value="<?php echo $request["id"]; ?>" />
                                                        <input type="hidden" name="bidid" value="<?php echo $offer['id']; ?>" />

                                                    </div>
                                                    <div class="row col-md-12">
                                                        <div class="form-group" id="fileinput" style="padding-right: 0;width: 100%;">
                                                            <label style="font-weight:bold">Product Disclosure Statement <span style="font-weight: normal;color: red;">Max. 25 mb</span></label>
                                                            <div class="row">
                                                                <div class="input-group col-md-7">
                                                                    <div class="input-group-prepend">
                                                                        <span>
                                                                            <select class="form-control pre_url" name="pre_url" required>
                                                                                <option  value="https://">https://</option>
                                                                                <option  value="http://">http://</option>
                                                                            </select>
                                                                        </span>
                                                                    </div>

                                                                    <input id="uurl" name="url" type="text" placeholder="Add Url Here" class="form-control"
                                                                           onblur="return checkURLHttps(this)"
                                                                           value="<?php echo Core::render(str_replace("http://","",str_replace("https://","",$offer['product_disclosure_url']))); ?>" />
                                                            </div>
                                                                <div class="col-md-5 row">
                                                                    <input type="file" name="file" class="form-control col-md-9 file" />
                                                                    <button type="button" onclick="removeFile(this);" class="btn btn-danger btn-sm col-md-2" style="height: 30px;margin-top: 10px;margin-left: 5px;"> X </button>
                                                                    <?php
                                                                    if ( !empty($offer['product_disclosure_statement']) ) {
                                                                    ?>
                                                                        <br/>
                                                                        <a href="<?php echo $offer['product_disclosure_statement']; ?>" target="_blank" class="btn btn-link disclosure_attachmentF"><?php echo Core::render(str_replace("../uploads/","",$offer['product_disclosure_statement'])); ?></a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="reqid" value="<?php echo $request["id"]; ?>" >
                                                        <input type="hidden" id="attached_file" name="attached_file" value="<?php echo Core::render($offer['product_disclosure_statement']); ?>"/>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label style="font-weight:bold;">Special Instructions</label>
                                                            <textarea id="my" maxlength="100" type="text" class="form-control textareaWithTextLimit" placeholder="Special instructions" name="special_ins"><?php echo Core::render($offer['special_instructions']);?></textarea>
                                                            <span style="font-weight:300" id="rchars">100</span> <span style="font-weight:300">Character(s) Remaining</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="height:100%" class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <h2 style="text-align:center;"> Confirm Submit Offer</h2>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-1 " ></div>
                                                            <div style="padding:0px;" class="col-lg-3 col-md-3 col-sm-6 col-xs-4">
                                                                <button type="button"  style="border:1px solid grey;margin-right:4px" class="btn btn-block mmy_btn" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                            <div style="padding:0px; padding-bottom: 20px; margin-left: 5px;"class="col-lg-3 col-md-3 col-sm-6 col-xs-4">
                                                                <input type="hidden" name="bidding_update" value="1"/>
                                                                <input type="submit"  class="btn btn-block btn-primary mmy_btn confirm_btn" name="bidding_update" value="Confirm" >
                                                            </div>
                                                            <div class="col-lg-1 col-md-1 col-sm-1"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row" style="padding-bottom:20px;margin-top:20px">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <a style="background-color:white;color:grey;border:1px solid grey" href="my_bids" class="btn btn-default">Go Back</a>
                                            <button type="button" class="btn btn-primary mmy_btn pre-submit-offer" name="bidding_update">Submit Offer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /content area -->
                <script>
                    $(document).on("change", ".rate_type", function () {
                        switch($(this).val()) {
                            case 'fixed':
                                $(".fixed_interest_rate_container").show();
                                $(".fixed_interest_rate_container").find("input").attr("required","required");
                                $(".variable_rate_container").hide();
                                $(".variable_rate_container").find("input").removeAttr("required");
                                break;
                            case 'variable':
                                $(".fixed_interest_rate_container").hide();
                                $(".fixed_interest_rate_container").find("input").removeAttr("required");
                                $(".variable_rate_container").show();
                                $(".variable_rate_container").find("input").attr("required","required");
                                break;
                        }
                    });

                    $(document).on("click",".pre-submit-offer",function() {
                        if(! $('#bid_offer_form')[0].checkValidity()) {
                            // If the form is invalid, submit it. The form won't actually submit;
                            // this will just cause the browser to display the native HTML5 error messages.
                            $('.confirm_btn').click();
                        }else{
                            if(parseInt($("#min_amount").val().replace(/,/g, "")) > <?php echo $request['amount'];?>){
                                $('#exampleModalCenter').modal('hide');
                                swal("Minimum amount can't be More than Requested Amount");
                                return false;
                            }

                            if(parseInt($("#max_amount").val().replace(/,/g, "")) > <?php echo $request['amount'];?>){
                                $('#exampleModalCenter').modal('hide');
                                swal("Maximum amount can't be More than Requested Amount");
                                return false;
                            }

                            if(parseInt($("#max_amount").val().replace(/,/g, "")) <= 0){
                                $('#exampleModalCenter').modal('hide');
                                swal("Maximum amount can't be Zero");
                                return false;
                            }

                            if ( parseFloat($("[name='min_amount']").val().replace(/,/g, "")) > parseFloat($("[name='max_amount']").val().replace(/,/g, "")) ){
                                $('#exampleModalCenter').modal('hide');
                                swal("Max amount to be equal to or greater than Minimum Amount");
                                $("[name='min_amount']").val(" ");
                                return false;
                            }

                            $('#exampleModalCenter').modal('show');
                        }
                    });

                    $(document).on("submit",".bid_offer",function (e) {
                        e.preventDefault();

                        <?php
                        if ( !empty($user_data['description']) && $user_data['description'] == "Broker" ) {
                        ?>
                        const thi_s = $(this);
                        $('#exampleModalCenter').modal('hide');

                        swal({
                            title: "Broker agreement",
                            text: "Do you have broker agreement for the selected Financial Institution?",
                            icon: "warning",
                            buttons: [
                                'No',
                                'Yes'
                            ],
                            dangerMode: false,
                        }).then(function(isConfirm) {
                            if(isConfirm) {
                                thi_s.removeClass("bid_offer");
                                thi_s.submit();
                            }
                        });
                        <?php
                        }else{
                        ?>
                        this.submit();
                        <?php
                        }
                        ?>

                    });
                </script>
            <?php
            require_once "footer.php";
        }else{
            echo "<script>location='my_bids'</script>";
        }
    }else{
        echo "<script>location='my_bids'</script>";
    }
}
?>
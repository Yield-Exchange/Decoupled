<?php
session_start();
require_once "header.php";
require_once "../config/RequestsModel.php";

$token = AuthModel::generateToken();
if (AuthModel::isLoggedIn()) {
    require_once "sidebar.php";

    global $user_data;
    if (isset($_GET['id'])) {
        $r_id = Core::urlValueDecrypt($_GET['id']);
        $request = RequestsModel::getRequestByID($r_id, false, true);
        if (!empty($request)) {
            $user_data = AuthModel::getUserdata();
            $bank_demographic_data = AuthModel::getUserDemographicData($user_data['id']);

            $depositor = AuthModel::getUserDataByID($request['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($request['user_id']);

            Core::activityLog("Bank New Requests > View");
            ?>
            <!-- Select 2 -->
            <link href="../assets/css/select2.min.css" rel="stylesheet" type="text/css">
            <script src="../assets/js/select2.min.js"></script>
            <style>
                .select2-selection {
                    padding-bottom: 30px !important;
                }

                p {
                    color: grey;
                    font-size: 13px;
                }

                .table-responsive {
                    padding-left: 0px;
                }

                .termsx {
                    overflow-y: scroll;
                    /*height: 300px;*/
                    width: 100%;
                    border: 1px solid #DDD;
                    padding: 10px;
                }

                #step1 a, #step1 h6, #step1 b button {
                    font-weight: 500;
                    font-size: 15px;
                }

                .variable_rate_container{
                    display: none;
                }
            </style>
            <link rel="stylesheet" type="text/css"
                  href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
                    integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
                    crossorigin="anonymous"></script>
            <script src="<?php echo BASE_URL . '/assets/'; ?>js/moment-timezone.js"></script>
            <script type="text/javascript">
                let format = 'YYYY/MM/DD HH:mm:ss ZZ';
                let timeZone = <?php echo json_encode(Model::formattedTimezone($bank_demographic_data['timezone']));?>;
                let dateOfDepositWithUserTimezone = moment.utc(<?php echo json_encode($request['date_of_deposit']);?>).tz(timeZone);
            </script>
<!--            <script src="--><?php //echo BASE_URL; ?><!--/assets/js/jquery.dirty.js"></script>-->
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
                                                    if (!empty($depositor['profile_pic'])) {
                                                        ?>
                                                        <img src="../depositor/image/<?php echo $depositor['profile_pic']; ?>"
                                                             width="80" height="80" alt=""/>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="i-initial-inverse-big">
                                                            <span><?php echo !empty($depositor['name'][0]) ? $depositor['name'][0] : 'Y' ?></span>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>
                                                        <img src="<?php echo BASE_URL . '/assets/images/icons/Depositor.png'; ?>"
                                                             class="img-responsive mr-2"
                                                             height="25"/> <?php echo $depositor['name']; ?></p>
                                                    <p>
                                                        <img src="<?php echo BASE_URL . '/assets/images/icons/City.png'; ?>"
                                                             class="img-responsive mr-2"
                                                             height="25"/><?php echo $depositor_demographic_data['city']; ?>
                                                    </p>
                                                    <p>
                                                        <img src="<?php echo BASE_URL . '/assets/images/icons/Province.png'; ?>"
                                                             class="img-responsive mr-2"
                                                             height="25"/><?php echo $depositor_demographic_data['province']; ?>
                                                    </p>
                                                    <p>
                                                        <img src="<?php echo BASE_URL . '/assets/images/icons/Email.png'; ?>"
                                                             class="img-responsive mr-2"
                                                             height="25"/><?php echo $depositor['email']; ?></p>
                                                    <p>
                                                        <img src="<?php echo BASE_URL . '/assets/images/icons/Telephone.png'; ?>"
                                                             class="img-responsive mr-2"
                                                             height="25"/><?php echo $depositor_demographic_data['telephone']; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class='card p-3' style="min-height: 330px;">
                                            <?php
                                            require_once BASE_DIR . '/bank/inc/request_summary.php';
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-12 card">
                                        <form action="logic" id="bid_offer_form" method="post" autocomplete="off"
                                              enctype="multipart/form-data" class="bid_offer form-horizontal">
                                            <div class="card-body">
                                                <h5 class="my_h"><span class="b_b">PLA</span>CE OFFER</h5>
                                                <!-- /main charts -->

                                                <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                                <div class="row">
                                                    <?php
                                                    if (!empty($user_data['description']) && $user_data['description'] == "Broker") {
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
                                                            <label style="font-weight:bold;">Minimum Amount</label>
                                                            <span style="color:red">*</span>
                                                            <input type="text" onchange="addThousands(this);"
                                                                   name="min_amount" id="min_amount" value=""
                                                                   class="form-control col-lg-11"
                                                                   placeholder="Minimum Amount" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Maximum Amount </label>
                                                            <span style="color:red">*</span>
                                                            <input type="text" onchange="addThousands(this);"
                                                                   name="max_amount" id="max_amount"
                                                                   class="form-control col-lg-11"
                                                                   placeholder="Maximum Amount" required/>
                                                        </div>
                                                        
                                                        <div class="form-group col-lg-11"
                                                             style="padding-right: 0!important;padding-left: 0!important;">
                                                            <label style="font-weight:bold;">Interest Rate Offer <small>(Simple
                                                                    Annual Interest Rate)</small></label> <span
                                                                    style="color:red">*</span>

                                                            <?php
                                                            if ($request['term_length_type'] != "HISA") {
                                                            ?>
                                                                <div class="form-group-feedback form-group-feedback-right">
                                                                    <input type="number" name="nir" min="0.01" max="100"
                                                                           class="form-control col-lg-12"
                                                                           placeholder="Interest Rate " step=".01"
                                                                           required/>
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
                                                                        <label class="radio-inline"><input type="radio" class="rate_type" name="rate_type" value="fixed" checked /> &nbsp;Fixed</label>
                                                                    </div>
                                                                    <div class="col-md-4 text-left">
                                                                        <label class="radio-inline"><input type="radio" class="rate_type" name="rate_type" value="variable" /> &nbsp;Variable</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group-feedback form-group-feedback-right col-md-12 fixed_interest_rate_container">
                                                                        <input type="number" name="nir" min="0.01" max="100"
                                                                               class="form-control col-lg-12"
                                                                               placeholder="Fixed Interest Rate " step=".01"
                                                                               required/>
                                                                        <div class="form-control-feedback">
                                                                            <i style="margin-top: 20px;"
                                                                               class="fa fa-percent text-muted"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-inline col-md-12 variable_rate_container">
                                                                        <input type="number" name="prime_rate" min="0.01" max="100"
                                                                               class="form-control col-md-5"
                                                                               placeholder="Prime Rate " step=".01"
                                                                               disabled
                                                                               value="<?php echo Core::getSystemSettingsValue('prime_rate')?>"
                                                                               style="width: 45%"/>

                                                                            <select class="form-control" name="rate_operator">
                                                                                <option value="+">+</option>
                                                                                <option value="-">-</option>
                                                                            </select>

                                                                        <input type="number" name="fixed_rate" min="0.01" max="100"
                                                                               class="form-control col-md-5"
                                                                               placeholder="Spread Rate " step=".01"
                                                                               style="width: 45%"/>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Rate held Until </label>
                                                            <span style="color:red">*</span>
                                                            <input type="text" id="offer_expiry" required name="expdate"
                                                                   class="form-control col-md-12 datetimepicker"
                                                                   placeholder="Rate held Until"/>
                                                        </div>
                                                        <div class="row col-md-12">
                                                            <div class="form-group" id="fileinput"
                                                                 style="padding-right: 0;width: 100%;">
                                                                <label style="font-weight:bold">Product Disclosure
                                                                    Statement <span
                                                                            style="font-weight: normal;color: red;">Max. 25 mb</span></label>
                                                                <div class="row ">
                                                                    <div class="input-group col-md-7">
                                                                        <div class="input-group-prepend">
                                                                            <span>
                                                                                <select class="form-control pre_url " name="pre_url" required>
                                                                                    <option  value="https://">https://</option>
                                                                                    <option  value="http://">http://</option>
                                                                                </select>
                                                                            </span>
                                                                        </div>
                                                                        <input id="uurl" name="url" type="text"
                                                                           placeholder="Add Url Here" onblur="return checkURLHttps(this)"
                                                                           class="form-control " value=""/>
                                                                    </div>
                                                                   <div class="col-md-5 row">
                                                                        <input type="file" name="file"
                                                                               class="form-control col-md-9 file"/>
                                                                        <button type="button"
                                                                                onclick="removeFile(this);"
                                                                                class="btn btn-danger btn-sm col-md-2"
                                                                                style="height: 30px;margin-top: 10px;margin-left: 5px;">
                                                                            X
                                                                        </button>
                                                                    </div>
                                                                
                                                                    <input type="hidden" name="reqid"
                                                                           value="<?php echo $request["id"]; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--                                                        <div class="row">-->
                                                        <!--                                                            <div class="form-group col-md-5" id="fileinput" style="padding-right: 0;">-->
                                                        <!--                                                                <label style="font-weight:bold">Product Disclosure Statement</label>-->
                                                        <!--                                                                <input id="uurl" name="url" type="url" placeholder="Add Url Here" class="form-control" />-->
                                                        <!--                                                            </div>-->
                                                        <!--                                                            <div class="form-group col-md-7" style="padding-left: 0;">-->
                                                        <!--                                                                <label style="font-weight: normal;color: red;">Max. 25 mb</label>-->
                                                        <!--                                                                <div class="row">-->
                                                        <!--                                                                    <input type="file" name="file" class="file form-control col-md-9 file" />-->
                                                        <!--                                                                    <button type="button" onclick="removeFile(this);" class="btn btn-danger btn-sm col-md-2" style="height: 30px;margin-top: 10px;margin-left: 5px;"> X </button>-->
                                                        <!--                                                                </div>-->
                                                        <!--                                                            </div>-->
                                                        <!--                                                            <input type="hidden" name="reqid" value="-->
                                                        <?php //echo $request["id"];
                                                        ?><!--">-->
                                                        <!--                                                        </div>-->
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label style="font-weight:bold;">Special
                                                                    Instructions</label>
                                                                <textarea id="my" maxlength="100" type="text"
                                                                          class="form-control textareaWithTextLimit"
                                                                          placeholder="Special instructions"
                                                                          name="special_ins"></textarea>
                                                                <span style="font-weight:300" id="rchars">100</span>
                                                                <span style="font-weight:300">Character(s) Remaining</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a style="background-color:white;color:grey;border:1px solid grey"
                                                       href="requests" class="btn btn-default">Go Back</a>
                                                    <button type="button" class="btn btn-primary pre-submit-offer">
                                                        Submit Offer
                                                    </button>
                                                    <br/>
                                                    <br/>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <h2 style="text-align:center;"> Confirm Submit Offer</h2>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-1"></div>
                                                            <div style="padding:0px;"
                                                                 class="col-lg-3 col-md-3 col-sm-6 col-xs-4">
                                                                <button type="button"
                                                                        style="border:1px solid grey;margin-right:4px"
                                                                        class="btn btn-default btn-block mmy_btn"
                                                                        data-dismiss="modal">Cancel
                                                                </button>
                                                            </div>
                                                            <div style="padding:0px; padding-bottom: 20px; margin-left: 5px;"
                                                                 class="col-lg-3 col-md-3 col-sm-6 col-xs-4">
                                                                <input type="hidden" name="bidding" value="1"/>
                                                                <input type="submit"
                                                                       class="btn btn-block btn-primary mmy_btn confirm_btn"
                                                                       value="Confirm"/>
                                                            </div>
                                                            <div class="col-lg-1 col-md-1 col-sm-1"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- End of Modal  -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /content area -->
                    <!-- Modal -->
                </div>
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

                        $(document).on("click", ".pre-submit-offer", function () {
                            if (!$('#bid_offer_form')[0].checkValidity()) {
                                // If the form is invalid, submit it. The form won't actually submit;
                                // this will just cause the browser to display the native HTML5 error messages.
                                $('.confirm_btn').click();
                            } else {
                                if (parseInt($("#min_amount").val().replace(/,/g, "")) > <?php echo $request['amount'];?>) {
                                    swal("Minimum amount can't be More than Requested Amount");
                                    return false;
                                }

                                if (parseInt($("#max_amount").val().replace(/,/g, "")) > <?php echo $request['amount'];?>) {
                                    swal("Maximum amount can't be More than Requested Amount");
                                    return false;
                                }

                                if (parseInt($("#max_amount").val().replace(/,/g, "")) <= 0) {
                                    swal("Maximum amount can't be Zero");
                                    return false;
                                }

                                if (parseFloat($("[name='min_amount']").val().replace(/,/g, "")) > parseFloat($("[name='max_amount']").val().replace(/,/g, ""))) {
                                    swal("Max amount to be equal to or greater than Minimum Amount");
                                    $("[name='min_amount']").val(" ");
                                    return false;
                                }

                                $('#exampleModalCenter').modal('show');
                            }
                        });

                        $(document).on("submit", ".bid_offer", function (e) {
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
                            }).then(function (isConfirm) {
                                if (isConfirm) {
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
        } else {
            echo "<script>location='requests'</script>";
        }
    } else {
        echo "<script>location='requests'</script>";
    }
}
?>
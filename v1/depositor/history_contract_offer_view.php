<?php
session_start();
require_once "header.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";

    if (isset($_GET['id'])) {
        $bid = $_GET['id'];
        $offer = BankModel::getOfferByID($bid,true);

        if (!empty($offer)){
            Core::activityLog("Depositor History -> Review Offers -> Action View");
            $r_id = $offer['depositor_request_id'];
            $data = RequestsModel::getRequestByID($r_id);

            $bank = AuthModel::getUserDataByID($offer['invited_user_id']);
            $bank_demographic_data = AuthModel::getUserDemographicData($offer['invited_user_id']);
            $credit_ratings = Core::getRatings($offer['invited_user_id']);
            ?>
            <!-- Main content -->
            <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">

                <!-- Main charts -->
                <div class="row">
                    <div class="col-xl-12">

                        <!-- Support tickets -->
                        <div class="card" style="padding:20px;padding-top:10px">
                            <br/>
                            <?php
                            require_once "inc/summary_screen.php";
                            ?>
                        </div>
                    </div>

                </div>

            </div>
            <!-- /content area -->

            <!-- Modal -->

<!--            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">-->
<!--                <div class="modal-dialog modal-dialog-centered" role="document">-->
<!--                    <div class="modal-content">-->
<!--                        <div class="modal-body text-center">-->
<!--                            <h2>Awarded Amount</h2>-->
<!--                            <div class="row">-->
<!--                                <div class="col-sm-3"></div>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input type="text" onkeyup="addThousands(this);" value="--><?php //echo (isset($contract['offered_amount']) ? number_format(str_replace(',','',$contract['offered_amount'])) : "");?><!--" name="offered_amount" id="offered_amount" class="form-control"/><br/>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="row">-->
<!--                            <div class="col-lg-3 col-md-3 col-sm-1"></div>-->
<!--                            <div style="padding:0px;" class="col-lg-3 col-md-3 col-sm-6 col-xs-4">-->
<!--                                <button type="button" style="border:1px solid grey;margin-right:4px" class="btn btn-default btn-block mmy_btn" data-dismiss="modal">Cancel</button>-->
<!--                            </div>-->
<!--                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-4">-->
<!--                                <a href="confirm_offered?bid_id=--><?php //echo $offer['id']; ?><!--" class="btn btn-primary btn-block confirm_offered">Confirm</a>-->
<!--                            </div>-->
<!--                            <div class="col-lg-1 col-md-1 col-sm-1"></div>-->
<!--                        </div>-->
<!--                        <br>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

            <?php
            require_once "footer.php";
        }else{
            echo "<script>location='exp'</script>";
        }
    }else{
        echo "<script>location='exp'</script>";
    }
}
?>
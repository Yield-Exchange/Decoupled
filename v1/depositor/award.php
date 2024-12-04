<?php
require_once "header.php";
require_once "../config/RequestsModel.php";
if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";

    Core::activityLog("Depositor Award Contract");
    if (isset($_GET['id'])) {
        $bid = Core::urlValueDecrypt($_GET['id']);
        $offer = BankModel::getOfferByID($bid);

        if (!empty($offer)){
            $r_id = $offer['depositor_request_id'];
            $data = RequestsModel::getRequestByID($r_id,true);
            if (empty($data)){
                return;
            }

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
                    <div class="col-12">
                        <!-- Support tickets -->
                        <div class="card transparent-card">
                            <br/>
                            <?php
                            require_once "inc/summary_screen.php";
                            ?>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <a href="vrzlts?rqid=<?php echo Core::urlValueEncrypt($data['id']); ?>" class="btn btn-block" style="border:1px solid gainsboro" style="color:white">Back</a>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                    </div>
                </div>
            </div>
            <!-- /content area -->
            <!-- Modal -->
            <?php
            require_once "footer.php";
        }else{
//            echo "<script>location='bids.php'</script>";
        }
    }else{
//        echo "<script>location='bids.php'</script>";
    }
}
?>
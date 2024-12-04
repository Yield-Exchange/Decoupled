<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    global $user_data;
    require_once "sidebar.php";

    Core::activityLog("Depositor Review Offers -> View Request");
    if (isset($_GET['id'])) {
        $r_id = Core::urlValueDecrypt($_GET['id']);
        $data = RequestsModel::getRequestByID($r_id);
        if ( !empty($data) ){
            $depositor = AuthModel::getUserDataByID($data['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($data['user_id']);
            $account_doc = DepositorModel::getDepositorDoc($data['user_id']);
    ?>
    <style>
         p{
             color:grey;
             font-size: 13px;
         }
    </style>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content">

                <!-- Main charts -->
				<div class="row">
					<div class="col-xl-12">

                        <!-- Support tickets -->
						<div class="card"  style="padding:20px;padding-top:10px">
                            <br/>
                            <?php
                                require_once BASE_DIR.'/depositor/inc/request_summary.php';
                            ?>

                           <div style="padding-bottom:30px;padding-top:45px;padding-left:10px;" class="row">
                                <div class="col-lg-2 col-md-2 col-sm-3">
                                  <a href="bids" class="btn btn-block" style="border:1px solid gainsboro" style="color:white">Back</a>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3"></div>
                                <div class="col-lg-2 col-md-2 col-sm-3"></div>
                                <div class="col-lg-3 col-md-3 col-sm-3"></div>
                            </div>
						</div>
                    <br><br><br>
					</div>
				</div>
			</div>
			<!-- /content area -->
<?php
require_once "footer.php";
        }else{
            echo "<script>location='p_req'</script>";
        }
    }else{
        echo "<script>location='p_req'</script>";
    }
}
?>
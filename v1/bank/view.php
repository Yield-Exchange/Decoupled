<?php
session_start();
require_once "header.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";

    if ( isset($_GET['rqid']) ) {
        $id = Core::urlValueDecrypt($_GET['rqid']);
        $offer_id = Core::urlValueDecrypt($_GET['id']);

        $request = RequestsModel::getRequestByID($id,false,true);
        if ( !empty($request) ){
            $depositor_data = AuthModel::getUserDataByID($request['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($request['user_id']);

            $offer = BankModel::getOfferByID($offer_id);

            $bank_data = AuthModel::getUserDataByID($offer['invited_user_id']);
            $bank_demographic_data = AuthModel::getUserDemographicData($offer['invited_user_id']);

            Core::activityLog("Bank In Progress -> View");
    ?>
     <style>
         p{
             color:grey;
             font-size: 13px;
         }
         .table-responsive{
             padding-left:0;
         }
     </style>

		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			<div class="content">
                <!-- Main charts -->
				<div class="row">
					<div class="col-12">
                        <!-- Support tickets -->
						<div class="card transparent-card">
                            <?php
                                require_once "inc/summary_screen.php";
                            ?>
                            <div class="row">
                                <div class="col-12">
                                    <a style="background-color:white;color:grey;border:1px solid grey" href="my_bids" class="btn btn-md">Go Back</a>
                                </div>
                            </div>
					</div>
				</div>
			</div>
            </div>
			<!-- /content area -->
<?php
require_once "footer.php";
        }else{
            echo "<script>location='my_bids'</script>";die();
        }
    }else{
        echo "<script>location='my_bids'</script>";die();
    }
}
?>
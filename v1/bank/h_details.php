<?php
session_start();
require_once("header.php");
require_once BASE_DIR."/config/RequestsModel.php";

if( AuthModel::isLoggedIn() ){
    require_once("sidebar.php");

    if(isset($_GET['rqid'])){
        $id = Core::urlValueDecrypt($_GET['rqid']);
        $bid = Core::urlValueDecrypt($_GET['id']);
        $request = RequestsModel::getRequestByID($id,false,true);

        $depositor_data = AuthModel::getUserDataByID($request['user_id']);
        $depositor_demographic_data = AuthModel::getUserDemographicData($request['user_id']);

        $offer = BankModel::getOfferByID($bid,true);
        $bank_data = AuthModel::getUserDataByID($offer['invited_user_id']);
        $bank_demographic_data = AuthModel::getUserDemographicData($offer['invited_user_id']);

        Core::activityLog("Bank History -> View");
?>
     <style>
         p{
             color:grey;
             font-size: 13px;
         }
         .table-responsive{
             padding-left:0px;
         }
     </style>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- /page header -->
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
                            <br />
                            <div class="row">
                                <div class="col-12">
                                    <a style="background-color:white;color:grey;border:1px solid grey" href="history" class="btn btn-md">Go Back</a>
                                </div>
                            </div>
						</div>
                        <br /><br /><br />
					</div>

				</div>
 
			</div>
			<!-- /content area -->
            
            <!-- Modal -->
<?php
require_once("footer.php");
    }else{
        echo "<script>location='history'</script>";
    }
}
?>
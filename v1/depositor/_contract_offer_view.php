<?php
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {

    require_once "sidebar.php";
    if (isset($_GET['id'])) {
        $bid = Core::urlValueDecrypt($_GET['id']);

        $offer = BankModel::getOfferByID($bid);
        if(!empty($offer)){

            $data = RequestsModel::getRequestByID($offer['depositor_request_id'],true);
            if (empty($data)){
                return;
            }
            $bank_user_id=$offer['invited_user_id'];
            $bank = AuthModel::getUserDataByID($bank_user_id);
            $bank_demographic_data = AuthModel::getUserDemographicData($bank_user_id);
            $credit_ratings = Core::getRatings($bank_user_id);

            $depositor_data = AuthModel::getUserDataByID($data['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($data['user_id']);

            $offer_id = $offer['id'];
            $contract_data = db::getRecord("SELECT * FROM `deposits` WHERE offer_id='$offer_id'");
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
					<div class="col-12">
                        <!-- Support tickets -->
						<div class="card transparent-card">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr class="table-active table-border-double">
                                            <td style="padding-left:10px" colspan="3" class="my_h"><span class="b_b">REVIEW</span> OFFER</td>
                                            <td class="text-right"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                            require_once "inc/summary_screen.php";
                        ?>
                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <a href="<?php echo isset($_GET['page']) && $_GET['page']=='exp' ? 'exp' : (isset($_GET['page']) && $_GET['page']=='comp' ? 'comp' : 'contract');?>" class="btn btn-block" style="border:1px solid gainsboro" style="color:white" >Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<!-- /content area -->
<?php
require_once "footer.php";
        }
    }
}
?>
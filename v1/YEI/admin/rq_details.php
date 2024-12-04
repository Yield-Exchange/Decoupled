<?php
require_once("header.php");
require_once "../../config/RequestsModel.php";

if( AdminModel::isLoggedIn() ){
require_once("sidebar.php");

    if( isset($_GET['id']) ){

        $r_id = $_GET['id'];
        $data = RequestsModel::getRequestByID($r_id);
        if ( !empty($data) ){
            $depositor = AuthModel::getUserDataByID($data['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($data['user_id']);
            $account_doc = DepositorModel::getDepositorDoc($data['user_id']);

            $depositor_request_id = $data['id'];
            $interest_rate_offer = db::getCell("SELECT MAX(interest_rate_offer) FROM offers o,invited i WHERE i.id=o.invitation_id AND o.offer_status IN('ACTIVE','CONFIRMED') AND i.depositor_request_id = '$depositor_request_id'");
            $highest_offer = !empty($interest_rate_offer) ? $interest_rate_offer : 0;

            Core::activityLog("admin view request details");
?>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                        <span class="breadcrumb-item active">In Progress</span>
							<span class="breadcrumb-item active">Request details</span>
					</div>
					<div class="header-elements d-none"></div>
				</div>
			</div>
			<!-- page header -->

			<!-- Content area -->
			<div class="content">
                <!-- Main charts -->
				<div class="row">
					<div class="col-xl-12">
                        <!-- Support tickets -->
						<div class="card">
                            <div class="table-responsive">
								<table class="table text-nowrap">
                                    <tbody>
                                        <tr class="table-active table-border-double">
											<td colspan="3">Request details</td>
											<td class="text-right"></td>
										</tr>
                                    </tbody>
                                </table>
                            </div>
							<div class="table-responsive">
                                <table class="table text-nowrap">
                                  <tr>
                                    <th>Amount:</th>
                                    <td><?php  echo $data['currency']." ".$data['amount'];  ?></td>
                                  </tr>
                                  <tr>
                                    <th>Date of Deposit (Approximate):</th>
                                    <td><?php  echo $data['date_of_deposit'];  ?></td>
                                  </tr>
                                  <tr>
                                    <th>Product:</th>
                                    <td>
                                        <?php
                                            $product = RequestsModel::getProductByID($data["product_id"]);
                                            echo !empty($product) ? $product['description'] : '';
                                        ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Investment Period:</th>
                                    <td><?php echo $data['term_length'].' '.ucwords(strtolower($data['term_length_type'])); ?></td>
                                  </tr>
                                  <tr>
                                    <th>Closing date & Time:</th>
                                    <td>
                                        <?php
                                            $timezone=$depositor_demographic_data['timezone'];
                                            echo date("Y-m-d H:i", strtotime($data['closing_date_time'])) . " " . $timezone;
                                        ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Ask:</th>
                                    <td>
                                        <?php
                                            echo empty($data['requested_rate']) ? " - " : number_format($data['requested_rate'],2)."%";
                                        ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Compounding Frequency :</th>
                                    <td><?php echo $data['compound_frequency']; ?></td>
                                  </tr>
                                  <tr>
                                    <th>Short Term DBRS Rating :</th>
                                    <td><?php echo $data['requested_short_term_credit_rating']; ?></td>
                                  </tr>
                                  <tr>
                                    <th>Deposit Insurance:</th>
                                    <td><?php  echo $data['requested_deposit_insurance']; ?></td>
                                  </tr>
                                  <tr>
                                    <th>Special Instructions:</th>
                                    <td><?php echo $data['special_instructions'];  ?></td>
                                  </tr>
                                  <tr>
                                    <th>Current Highest Offer:</th>
                                    <td><?php echo BankModel::getInterest($highest_offer);?> </td>
                                  </tr>
                                </table>
                            </div>
                            <br>
						</div>
                         <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-4"></div>
                              <div class="col-lg-4 col-md-4 col-sm-4">
                                <a href="inpro" style="border:1.5px solid #2664ae" class="btn mmy_btn btn-block">Back</a>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-4"></div>
                        </div>
                        <br><br><br>
					</div>
				</div>
			</div>
<?php
require_once("footer.php");
        }
    }
}
?>
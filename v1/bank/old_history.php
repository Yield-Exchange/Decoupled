<?php
session_start();
require_once "header.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";
    global $user_data;
    
    $rzlt = BankModel::getBankHistoryBids($user_data['id']);
    $user_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);

    $data = $rzlt['data'];
    Core::activityLog("Bank History");
?>
<script>
    $(document).ready(function () {
        $('.custom-data-tables').DataTable({
            "order": [[ 0, "desc" ]],
            "scrollX": true,
        });
        $('.dataTables_length').addClass('bs-select');
    });
</script>

    <!-- Main content -->
		<div class="content-wrapper">
			<!-- /page header -->

			<!-- Content area -->
			<div class="content">

                	<div class="row">
					<div class="col-xl-12">

						<div class="card">

							    <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr class="table-active table-border-double">
                                                <td colspan="3"class="my_h"><span class="b_b">HIS</span>TORY <span class="badge bg-blue badge-pill data-size-count">0</span></td>
                                                <td class="text-right"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-sm-12">

                                <div class="table-responsive">

                                    <table class="table custom-data-tables table-condensed">

                                    	<thead>
                                            <tr role="row">
                                                <th style="display:none;">Id</th>
                                                <th>Province</th>
                                                <th>Institution</th>
                                                <th>Request ID</th>
                                                <th>Type</th>
                                                <th>Product</th>
                                                <th>Investment Period</th>
                                                <th>Our Offer</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
									<tbody>
                                            <?php
                                            $size=0;
                                            if ( !empty($data) ) {
                                                foreach ($data as $rec) {
                                                    $depositor_data = AuthModel::getUserDataByID($rec['user_id']);
                                                    $depositor_demographic_data = AuthModel::getUserDemographicData($rec['user_id']);
                                                    $offer = db::getRecord("SELECT o.reference_no, o.interest_rate_offer,o.maximum_amount,o.minimum_amount,o.id as offer_id,o.offer_status FROM offers o WHERE o.invitation_id='".$rec['invitation_id']."'");
                                                    if (!empty($offer)){
                                                        $offer_contract = db::getRecord("SELECT * FROM deposits WHERE offer_id='".$offer['id']."'");
                                                    }

                                                    $matured_contracts = db::getCell("SELECT COUNT('c.*') FROM deposits c, offers o, invited i WHERE c.offer_id = o.id AND i.id = o.invitation_id AND i.depositor_request_id = '".$rec['depositor_request_id']."' AND invitation_id='".$rec['invitation_id']."' AND c.status IN('MATURED')");
                                                    $withdrawn_contracts = db::getCell("SELECT COUNT('c.*') FROM deposits c, offers o, invited i WHERE c.offer_id = o.id AND i.id = o.invitation_id AND i.depositor_request_id = '".$rec['depositor_request_id']."' AND invitation_id='".$rec['invitation_id']."' AND c.status IN('WITHDRAWN')");
                                                    $incomplete_contracts = db::getCell("SELECT COUNT('c.*') FROM deposits c, offers o, invited i WHERE c.offer_id = o.id AND i.id = o.invitation_id AND i.depositor_request_id = '".$rec['depositor_request_id']."' AND invitation_id='".$rec['invitation_id']."' AND c.status IN('INCOMPLETE')");

                                                    if ( in_array($rec['invitation_status'], ['UNINVITED','DID_NOT_PARTICIPATE']) || ($offer && in_array($offer['offer_status'],['NOT_SELECTED','EXPIRED','REQUEST_WITHDRAWN'])) || (!empty($offer_contract) && in_array($offer_contract['status'],['WITHDRAWN'])) || (in_array($rec['request_status'], ["WITHDRAWN"])) || (in_array($rec['request_status'], ["ACTIVE","COMPLETED"]) && $matured_contracts > 0) || (in_array($rec['request_status'], ["COMPLETED"]) && $withdrawn_contracts > 0) || (in_array($rec['request_status'], ["COMPLETED"]) && $incomplete_contracts > 0) ){
                                                        $size++;

                                                        $status=$rec['request_status'];
                                                        if (in_array($rec['invitation_status'], ['UNINVITED','DID_NOT_PARTICIPATE'])){
                                                            $status=$rec['invitation_status'];
                                                        }else if ($offer && in_array($offer['offer_status'],['NOT_SELECTED','EXPIRED','WITHDRAWN'])){
                                                            $status='Offer '.$offer['offer_status'];
                                                        }else if (in_array($rec['request_status'], ["ACTIVE","COMPLETED"]) && $matured_contracts > 0){
                                                            $status="CONTRACT_MATURED";
                                                        }else if (in_array($rec['request_status'], ["COMPLETED"]) && $withdrawn_contracts > 0){
                                                            $status="CONTRACT_WITHDRAWN";
                                                        }else if (in_array($rec['request_status'], ["COMPLETED"]) && $incomplete_contracts > 0){
                                                            $status="CONTRACT_INCOMPLETE";
                                                        }else if (in_array($rec['request_status'], ["WITHDRAWN"])){
                                                            $status="REQUEST_WITHDRAWN";
                                                        }
                                            ?>
                                            <tr>
                                                <td class="hidden" style="display:none;">
                                                    <div>
                                                        <h6 class="mb-0"><?php echo $rec["depositor_request_id"]; ?></h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0" align="left">
                                                        <?php echo Core::render($depositor_demographic_data["province"]);?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0" align="left">
                                                        <?php echo Core::render($depositor_data["name"]);?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0" align="left">
                                                        <?php
                                                            $type="Request ID";
                                                            if (!empty($offer)) {
                                                                $contract_ = db::getRecord("SELECT * FROM `deposits` WHERE offer_id='" . $offer['id'] . "'");
                                                                if (!empty($contract_)){
                                                                    $type="Deposit ID";
                                                                    echo Core::render($contract_['reference_no']);
                                                                }else{
                                                                    $type="Offer ID";
                                                                    echo Core::render($offer['reference_no']);
                                                                }
                                                            }else{
                                                                echo Core::render($rec["request_reference_no"]);
                                                            }
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0" align="left">
                                                        <?php echo Core::render($type);?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="mb-0">
                                                            <?php
                                                                $product = RequestsModel::getProductByID($rec["product_id"]);
                                                                echo !empty($product) ? Core::render($product['description']) : '';
                                                            ?>
                                                        </h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0" style="text-transform:capitalize;">
                                                        <?php
                                                         if ($rec['term_length_type'] == "HISA"){
                                                             echo '-';
                                                         }else {
                                                             echo Core::render($rec["term_length"] . ' ' . ucwords(strtolower($rec['term_length_type'])));
                                                         }
                                                        ?>
                                                    </h6>
                                                </td>
                                            	<td>
											        <h6 class="mb-0">
                                                        <?php
                                                            echo !empty($offer['interest_rate_offer']) ? BankModel::getInterest($offer['interest_rate_offer']) : '';
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <?php
                                                        echo Core::render(ucwords(strtolower(str_replace("_"," ",$status))));
                                                    ?>
                                                </td>
                                            	<td class="text-center">
                                                    <?php
                                                        if ( !empty($offer['offer_id']) ){
                                                    ?>
                                                            <a href="h_details?id=<?php echo Core::urlValueEncrypt($offer['offer_id']); ?>&&rqid=<?php echo Core::urlValueEncrypt($rec['depositor_request_id']); ?>" class="btn btn-primary btn-block mmy_btn">View</a>
                                                    <?php
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- /support tickets -->
					</div>
				</div>
            </div>
         </div>

 <script>
        $(".data-size-count").html(<?php echo json_encode($size);?>);
		function deleteit(){
			return(confirm("The record will be deleted permanently. Do you really want to continue?"));
        }
		function editit(){
            return(confirm("Do you want to edit?"));
        }
    </script>
<?php
require_once "footer.php";
}
?>
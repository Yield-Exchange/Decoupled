<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

if (AuthModel::isLoggedIn()) {
    require_once "sidebar.php";
    global $user_data;
    
    $rzlt = DepositorModel::getDepositorHistoryRequests($user_data['id']);
    $size = $rzlt['total'];
    $data = $rzlt['data'];

    Core::activityLog("Depositor History");
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

			<!-- Content area -->
			<div class="content">

                	<div class="row">
					<div class="col-xl-12">

						<div class="card">

							   <div class="table-responsive" >
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr class="table-active table-border-double">
                                                <td colspan="3" class="my_h"><span class="b_b">HIS</span>TORY  <span class="badge bg-blue badge-pill history-count"></span></td>
                                                <td></td>
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
                                                <th>Request ID</th>
                                                <th>Product</th>
                                                <th>Requested Amount</th>
                                                <th>Closure Date & Time</th>
                                                <th>Term length</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
									<tbody>
                                    <?php
                                    $count=0;
                                    if ($data) {
                                         foreach ($data as $rec) {
                                            
                                              $matured_contracts = db::getCell("SELECT COUNT('c.*') FROM contracts c, offers o, invited i WHERE c.offer_id = o.id AND i.id = o.invitation_id AND i.depositor_request_id = '".$rec['id']."' AND c.status IN('MATURED')");
                                              $withdrawn_contracts = db::getCell("SELECT COUNT('c.*') FROM contracts c, offers o, invited i WHERE c.offer_id = o.id AND i.id = o.invitation_id AND i.depositor_request_id = '".$rec['id']."' AND c.status IN('WITHDRAWN')");
                                              $incomplete_contracts = db::getCell("SELECT COUNT('c.*') FROM contracts c, offers o, invited i WHERE c.offer_id = o.id AND i.id = o.invitation_id AND i.depositor_request_id = '".$rec['id']."' AND c.status IN('INCOMPLETE')");
 
                                             if ( in_array($rec['request_status'],['EXPIRED','NO_OFFERS_RECEIVED','WITHDRAWN']) || (in_array($rec['request_status'], ["ACTIVE","COMPLETED"]) && $matured_contracts > 0) || (in_array($rec['request_status'], ["COMPLETED"]) && $withdrawn_contracts > 0) || (in_array($rec['request_status'], ["COMPLETED"]) && $incomplete_contracts > 0) ){
                                                //TODO
                                             }else{
                                                 continue;
                                             }

                                             $user_demographic_data = AuthModel::getUserDemographicData($rec['user_id']);
                                             $status = $rec["request_status"];

                                             $count++;

                                             if (in_array($rec['request_status'], ["ACTIVE","COMPLETED"]) && $matured_contracts > 0){
                                                 $status="CONTRACT_MATURED";
                                             }
                                             if (in_array($rec['request_status'], ["COMPLETED"]) && $withdrawn_contracts > 0){
                                                $status="CONTRACT_WITHDRAWN";
                                            }
                                            if (in_array($rec['request_status'], ["COMPLETED"]) && $incomplete_contracts > 0){
                                                $status="CONTRACT_INCOMPLETE";
                                            }
                                    ?>
                                            <tr>
                                                <td class="hidden" style="display:none;">
                                                    <div>
                                                        <h6 class="mb-0"><?php echo $rec["id"]; ?></h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0"><?php echo Core::render($rec["reference_no"]); ?></h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0">
                                                        <?php
                                                            $product = RequestsModel::getProductByID($rec["product_id"]);
                                                            echo !empty($product) ? Core::render($product['description']) : '';
                                                        ?>
                                                    </h6>
											    </td>
                                                <td>
                                                    <h6 class="mb-0" align="left">
                                                        <?php
                                                            echo Core::render($rec["currency"]) . "&nbsp;&nbsp;&nbsp;" . number_format($rec["amount"]);
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0">
                                                        <?php
                                                            echo Model::dateTimeFromUTC('Y-m-d h:i a',$rec['closing_date_time']) . " " . Core::render($user_demographic_data['timezone']);
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0" align="left">
                                                        <?php
                                                         if ($rec['term_length_type'] == "HISA"){
                                                             echo '-';
                                                         }else {
                                                             echo Core::render(ucfirst($rec["term_length"]) . ' ' . ucwords(strtolower($rec['term_length_type'])));
                                                         }
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <?php
                                                        echo Core::render(ucwords(strtolower(str_replace("_"," ",$status))));
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="list-icons">
                                                        <div class="list-icons-item dropdown">
                                                            <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-uu" style="position: absolute;z-index:999">
                                                                <a href="view_invites?rid=<?php echo Core::urlValueEncrypt($rec['id']);?>" class="dropdown-item">Invited institutions</a>
                                                                <div class="dropdown-divider"></div>
                                                                <?php
                                                                $total_bids = db::getcell("select count(*) as total from offers o, invited i where i.id=o.invitation_id AND i.depositor_request_id='".$rec['id']."'");
                                                                if ($total_bids > 0) {
                                                                ?>
                                                                    <a href="history_contract_offers?rqid=<?php echo Core::urlValueEncrypt($rec['id']); ?>" class="dropdown-item">Review offers</a>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
										    </tr>
								 <?php
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
<?php
require_once "footer.php";
}
?>
<script>
    $(".history-count").html(<?php echo json_encode($count)?>);
</script>
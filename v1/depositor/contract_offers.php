<?php
require_once "header.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {

    require_once "sidebar.php";

    if (isset($_GET["rqid"])) {
        Core::activityLog("Depositor History -> Review Offers");

        $req_id = $_GET["rqid"];
        $post_request = DepositorModel::getPostRequestsByID($req_id);

        if(!empty($post_request)){
            $sql="SELECT o.*,i.invited_user_id FROM offers o,invited i WHERE i.id = o.invitation_id  AND i.depositor_request_id = '$req_id' AND offer_status IN('REJECTED','WITHDRAWN','ABANDONED','LOST','EXPIRED')";
            $offers = db::getRecords($sql);
?>
<script>
    $(document).ready(function() {
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    });

    $(document).ready(function () {
        $('.custom-data-tables').DataTable({
            "order": [[ 0, "desc" ]],
            "scrollX": true,
            "pageLength": 50
        });
        $('.dataTables_length').addClass('bs-select');
    });
</script>
		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			<div class="content">
                <br><!-- Main charts -->
				<div class="row">
					<div class="col-xl-12">
						<div class="card">
                           <div class="table-responsive" >
								<table class="table text-nowrap">
                                    <tbody>
                                        <tr class="table-active table-border-double">
											<td colspan="3"  class="my_h"><span class="b_b" >ALL</span> OFFERS  </td>
                                            <td class="text-right">
                                                <button onclick="window.open('review_offers_print?history=1&&rqid=<?php echo $req_id; ?>', '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');" class="btn btn-primary">Print</button>
                                            </td>
										</tr>
                                    </tbody>
                                </table>
                            </div>

					         <div class="table-responsive">
								<table class="table text-nowrap tbl_index custom-data-tables">
									 <thead>
										<tr>
                                            <th>Institution</th>
                                            <th>Interest Rate %</th>
                                            <th>Max Amount</th>
                                            <th>Min Amount</th>
                                            <th>Awarded Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
										</tr>
									</thead>
									<tbody>
                                    <?php
                                    if ( !empty($offers) ) {
                                        foreach ( $offers as $rec ) {

                                            $bank_user_id = $rec['invited_user_id'];
                                            $bank = AuthModel::getUserDataByID($bank_user_id);
                                            $contract = BankModel::getBankBidContract($rec["id"]);
                                    ?>
                                            <tr>
                                                <td><?php echo $bank["name"]; ?></td>
                                                <td><?php echo BankModel::getInterest($rec["interest_rate_offer"]);?></td>
                                                <td>
                                                    <h6 class="mb-0"><?php echo $post_request['currency'].' '.number_format($rec["maximum_amount"]); ?></h6>
                                                    <input type="hidden" class="form-control" name="max_amount" value="<?php echo str_replace(",","",$rec['max_amount']);?>"/>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0"><?php echo $post_request['currency'].' '.number_format($rec["minimum_amount"]); ?></h6></td>
                                                <td>
                                                    <h6 class="mb-0"><?php echo !empty($contract['offered_amount']) ? $post_request['currency'].' '.number_format(str_replace(",","",$contract['offered_amount'])) : '-';?></h6>
                                                </td>
                                                <td>
                                                    <?php echo ucwords(strtolower($rec['offer_status']));?>
                                                </td>
                                                <td>
                                                    <a href="history_contract_offer_view?id=<?php echo $rec['id'];?>" class="btn btn-primary btn-block" style="color:white">View</a>
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
                    </div>
                </div>
            </div>

                                            
<?php
require_once "footer.php";
        }
    }
}
?>
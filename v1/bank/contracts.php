<?php
session_start();
require_once("header.php");
require_once "../config/RequestsModel.php";

if( AuthModel::isLoggedIn() ){
    require_once("sidebar.php");
    global $user_data;
    $data = BankModel::getBankPendingContractsBids($user_data['id']);
    $bank_demographic_data = AuthModel::getUserDemographicData($user_data['id']);
    $size = $data['total'];
    $contracts = $data['data'];

    Core::activityLog("Bank Pending Deposits");
?>
<script>
    $(document).ready(function () {
        $('.custom-data-tables').DataTable({
            "order": [[ 0, "desc" ]],
            "scrollX": true,
        });
    });
</script>
<style>
    .badge-notify-chat-1{
        position: relative;
        top: -18px;
        left: -10px;
        border-radius: 50%;
        display: inline-block;
    }
</style>
    		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			<div class="content">
                <div class="row">
					<div class="col-xl-12">
						<div class="card">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr class="table-active table-border-double">
                                            <td colspan="3" class="my_h"><span class="b_b">PEN</span>DING DEPOSITS <span class="badge bg-blue badge-pill"><?php echo $size; ?></span></td>
                                            <td class="text-right"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table custom-data-tables no-footer table-condensed">
                                    	<thead>
                                            <tr role="row">
                                                <th>Deposit ID</th>
                                                <th>Depositor Name</th>
                                                <th>Deposit Amount</th>
                                                <th>Product</th>
                                                <th>Investment Period</th>
                                                <th>Interest Rate %</th>
                                                <th>Rate Held Until</th>
                                                <th>Action</th>
                                                <th></th>
                                            </tr>
                                        </thead>
									    <tbody>
                                        <?php
                                        if($contracts) {
                                            foreach($contracts as $rec)
                                            {
                                                $timezone = BankModel::getBankTimeZone($rec['bank_id']);
                                                $depositor_request = RequestsModel::getRequestByID($rec['depositor_request_id']);
                                                $depositor = AuthModel::getUserDataByID($depositor_request['user_id']);
                                                $depositor_data = AuthModel::getUserDemographicData($depositor_request['user_id']);
                                        ?>
                                                <tr>
                                                    <td style="padding:8px">
                                                        <?php echo Core::render($rec["reference_no"]); ?></h6>
                                                    </td>
                                                    <td style="padding:8px">
                                                        <div class="d-flex align-items-center">
                                                            <h6 class="mb-0"><?php echo Core::render($depositor["name"]); ?> </h6>
                                                        </div>
                                                    </td>
                                                    <td data-order="<?php echo $rec["offered_amount"];?>">
                                                        <h6 class="mb-0" align="left">
                                                            <?php
                                                                echo (isset($rec['offered_amount']) ? Core::render($depositor_request['currency']).' '.number_format($rec['offered_amount']) : "-");
                                                            ?>
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <h6 class="mb-0">
                                                                <?php
                                                                $product = RequestsModel::getProductByID($depositor_request["product_id"]);
                                                                echo !empty($product) ? Core::render($product['description']) : '';
                                                                ?>
                                                            </h6>
                                                        </div>
                                                    </td>
                                                    <td style="padding:8px">
                                                        <h6 class="mb-0">
                                                            <?php
                                                            if ($depositor_request['term_length_type'] == "HISA"){
                                                                echo '-';
                                                            }else {
                                                                echo Core::render($depositor_request['term_length'] . ' ' . ucwords(strtolower($depositor_request['term_length_type'])));
                                                            }
                                                            ?>

                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0">
                                                            <?php
                                                            if ($rec['rate_type']!='VARIABLE'){
                                                                echo BankModel::getInterest($rec['interest_rate_offer']);
                                                            }else {
                                                                echo BankModel::getInterest($rec['prime_rate'], true,$rec['rate_operator']);
                                                            }
                                                            ?>
                                                        </h6>
                                                    </td>
                                                    <td style="padding:8px">
                                                        <h6 class="mb-0">
                                                            <?php
                                                            $timezone = Core::render($bank_demographic_data['timezone']);
                                                            echo Model::dateTimeFromUTC('Y-m-d h:i a',$rec['rate_held_until'],$timezone);
                                                            ?>
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $chat_count = db::getCell("SELECT count(*) from chat where deposit_id='".$rec['id']."' AND status='NEW' AND sent_to='".$user_data['id']."'");
                                                        ?>
                                                         <a href="msgs?id=<?php echo Core::urlValueEncrypt($depositor_request['user_id']);?>&&c_id=<?php echo Core::urlValueEncrypt($rec['id']);?>" class="btn btn-primary mmy_btn btn-block" style="display: inline-block;">Chat</a> <?php echo $chat_count > 0 ? '<span class="badge badge-danger badge-notify-chat-1">'.$chat_count.'</span>' : '';?>
                                                    </td>
                                                    <td>
                                                        <a href="contract_details?cnid=<?php echo Core::urlValueEncrypt($rec["id"]);?>" class="btn btn-primary mmy_btn btn-block">Create <?php echo ( !empty($product) && strpos($product['description'], 'High Interest Savings') !== false ) ? 'HISA' : 'GIC' ?></a>
                                                    </td>
										        </tr>
                                <?php
                                            }
                                        }
                                ?>
									</tbody>
								</table>
                                <br/>
                                <br/>
							</div>
						</div>
				</div>
            </div>
        </div>
    </div>
 <script>
		function deleteit(){
			return(confirm("The record will be deleted permanently. Do you really want to continue?"));
        }
		function editit(){
			return(confirm("Do you want to edit?"));
        }
</script>
<?php
require_once("footer.php"); 
}
?>
            
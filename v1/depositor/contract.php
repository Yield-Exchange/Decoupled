<?php
session_start();
require_once "header.php";
require_once __DIR__."/../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";
    global $user_data;

    $usr_demographic_data = AuthModel::getUserDemographicData($user_data['id']);

    Core::activityLog("Depositor Pending Deposits");
    $data = DepositorModel::getDepositorsPendingContracts($user_data['id']);
?>

<script>
    $(document).ready(function () {
        $('.custom-data-tables').DataTable({
            "order": [[ 0, "asc" ]],
            "scrollX": true,
        });
        $('.dataTables_length').addClass('bs-select');
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

                       	    <div class="table-responsive" >
								<table class="table text-nowrap">

                                    <tbody>
                                        <tr class="table-active table-border-double">
											<td colspan="3" class="my_h"><span class="b_b" >PEN</span>DING DEPOSITS <span class="badge bg-blue badge-pill"><?php echo $data['total']; ?></span></td>
											<td class=""></td>
										</tr>
                                    </tbody>

                                </table>
                           </div>

                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table custom-data-tables table-condensed">
                                    <thead>
                                        <tr role="row">
                                            <th>Date Of Deposit</th>
                                            <th>Rate Held Until</th>
                                            <th>Institution</th>
                                            <th>Deposit Amount</th>
                                            <th>Product</th>
                                            <th>Investment Period</th>
                                            <th>Interest Rate %</th>
                                            <th>Action</th>
                                            <th></th>
                                        </tr>
                                    </thead>
									<tbody>
                                        <?php
                                        if ($data['data']) {
                                                foreach ($data['data'] as $rec) {
//                                                    $user_demographic_data = AuthModel::getUserDemographicData($rec['user_id']);
                                                    $timezone = $usr_demographic_data['timezone'];
                                        ?>
                                        <tr>
											<td>
                                                <h6 class="mb-0">
                                                    <?php echo Model::dateTimeFromUTC('Y-m-d',$rec["date_of_deposit"],$timezone); ?>
                                                </h6>
											</td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <?php echo Model::dateTimeFromUTC('Y-m-d h:i a',$rec["rate_held_until"],$timezone); ?>
                                                </h6>
                                            </td>
										    <td>
                                                <h6 class="mb-0">
                                                    <?php
                                                        $bank = AuthModel::getUserDataByID($rec['invited_user_id']);
                                                        echo $bank['name'];
                                                    ?>
                                                </h6>
											</td>

                                            <td align="left" data-order="<?php echo $rec["offered_amount"];?>">
                                                <h6 class="mb-0">
                                                    <?php
                                                        $amount = number_format((float)str_replace(",","",$rec["offered_amount"]));
                                                        echo !empty($amount) ? $rec["currency"] . "&nbsp;&nbsp;&nbsp;" . $amount : '-';
                                                    ?>
                                                </h6>
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
                                                <h6 class="mb-0">
                                                    <?php
                                                    if ($rec['term_length_type'] == "HISA"){
                                                        echo '-';
                                                    }else {
                                                        echo Core::render($rec['term_length']) . ' ' . Core::render(ucwords(strtolower($rec['term_length_type'])));
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

                                            <td>
                                                <?php
                                                    $chat_count = db::getCell("SELECT count(*) from chat where deposit_id='".$rec['id']."' AND status='NEW' AND sent_to='".$user_data['id']."'");
                                                ?>
                                                 <a href="msgs?id=<?php echo Core::urlValueEncrypt($rec['invited_user_id']); ?>&c_id=<?php echo Core::urlValueEncrypt($rec['id']); ?>" class="btn btn-primary mmy_btn btn-block" style="display: inline-block;">Chat</a> <?php echo $chat_count > 0 ? '<span class="badge badge-danger badge-notify-chat-1">'.$chat_count.'</span>' : '';?>
                                            </td>
                                            <td>
                                                <div class="list-icons">
                                                    <div class="list-icons-item dropdown">
                                                        <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                                        <div class="dropdown-menu">
                                                            <a href="c_details?cnid=<?php echo Core::urlValueEncrypt($rec["id"]); ?>" class="dropdown-item">View</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="pending_contract_offers?rqid=<?php echo Core::urlValueEncrypt($rec["depositor_request_id"]); ?>" class="dropdown-item">Review Offers</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="logic?reject=<?php echo Core::urlValueEncrypt($rec["id"]); ?>" onclick="return reject();" class="dropdown-item">Withdraw</a>
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
						</div>
						<!-- /support tickets -->

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

        function reject(){
            return(confirm("Do you want to withdraw the awarded contract?"));
        }

        function abandon(){
            return(confirm("Do you want to abandon the awarded contract?"));
        }
    </script>
<?php
require_once "footer.php";
}
?>

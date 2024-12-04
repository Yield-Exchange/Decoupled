<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";

require_once "header.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {

    require_once "sidebar.php";
    global $user_data;
    $user_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
    $data = BankModel::getMyBids($user_data['id']);

    Core::activityLog("Bank In Progress");
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

							    <div class="table-responsive">
                                    <table class="table text-nowrap">
                                            <tbody>
                                                <tr class="table-active table-border-double">
                                                    <td colspan="3"class="my_h"><span class="b_b">IN</span> PROGRESS <span class="badge bg-blue badge-pill"><?php echo Model::countArray($data); ?></span></td>
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
                                                    <th>Request ID</th>
                                                    <th>Depositor Name</th>
                                                    <th>Province</th>
                                                    <th>Request Amount</th>
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
                                            if (!empty($data)) {
                                                    foreach ($data as $rec) {
                                                        $deposit_request = RequestsModel::getRequestByID($rec['depositor_request_id']);
                                                        $post_id = $rec['id'];

                                                        $depositor = AuthModel::getUserDataByID($deposit_request['user_id']);
                                                        $depositor_demographic_data = AuthModel::getUserDemographicData($deposit_request['user_id']);
                                            ?>
                                            <tr>
                                                <td style="padding:8px">
                                                    <?php echo Core::render($deposit_request["reference_no"]); ?>
                                                </td>

                                                 <td style="padding:8px">
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="mb-0"><?php echo Core::render($depositor["name"]); ?> </h6>
											        </div>
                                                 </td>

                                                <td style="padding:8px">
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="mb-0"><?php echo Core::render($depositor_demographic_data['province']); ?> </h6>
                                                    </div>
                                                </td>
                                                <td style="padding:8px" data-order="<?php echo $deposit_request["amount"];?>">
                                                    <h6 class="mb-0" align="left">
                                                        <?php echo Core::render($deposit_request["currency"]) . "&nbsp;&nbsp;&nbsp;&nbsp;" . number_format($deposit_request["amount"]) . "&nbsp;&nbsp;&nbsp;&nbsp;"; ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">
                                                            <?php
                                                                $product = RequestsModel::getProductByID($deposit_request["product_id"]);
                                                                echo !empty($product) ? Core::render($product['description']) : '';
                                                            ?>
                                                        </h6>
                                                    </div>
                                                </td>

                                                <td style="padding:8px">
                                                    <h6 class="mb-0">
                                                        <?php
                                                         if ($deposit_request['term_length_type'] == "HISA"){
                                                             echo '-';
                                                         }else {
                                                             echo Core::render($deposit_request['term_length'] . ' ' . ucwords(strtolower($deposit_request['term_length_type'])));
                                                         }?>
                                                    </h6>
                                                </td>
                                                <td style="padding:8px">
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
                                                            $timezone = Core::render($user_demographic_user_data['timezone']);
                                                            echo Model::dateTimeFromUTC('Y-m-d h:i a',$rec['rate_held_until'],$timezone);
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td class="text-center">
                                                    <a href="view?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>&&rqid=<?php echo Core::urlValueEncrypt($deposit_request['id']); ?>" class="btn btn-primary mmy_btn btn-block">View</a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="edit_bid?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>&&rqid=<?php echo Core::urlValueEncrypt($deposit_request['id']); ?>" class="btn btn-primary mmy_btn btn-block">Edit</a>
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

 <script>
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
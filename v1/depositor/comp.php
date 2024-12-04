<?php
    session_start();
    require_once "header.php";
    require_once __DIR__."/../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {

    require_once "sidebar.php";
    global $user_data;
    $data = DepositorModel::getDepositorsActiveContracts($user_data['id']);
    $bank_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
    Core::activityLog("Depositor Active Deposits");
?>
        <script>
            $(document).ready(function () {
                $('.custom-data-tables').DataTable({
                    "order": [[ 6, "DESC" ]],
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
                                                <td colspan="3" class="my_h"><span class="b_b" >ACT</span>IVE DEPOSITS <span class="badge bg-blue badge-pill"><?php echo $data['total']; ?></span></td>
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
                                                <th>GIC Number</th>
                                                <th>Institution</th>
                                                <th>Deposit Amount</th>
                                                <th>Product</th>
                                                <th>Investment Period</th>
                                                <th>Interest Rate %</th>
                                                <th>Maturity Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
									<tbody>
                                        <?php
                                        if ( !empty($data['data']) ) {
                                            foreach ($data['data'] as $rec) {
                                                $depositor_data = AuthModel::getUserDataByID($rec['user_id']);
                                                $bank_data = AuthModel::getUserDataByID($rec['invited_user_id']);
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6 class="mb-0"><?php echo Core::render($rec["gic_number"]); ?></h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0">
                                                            <?php
                                                            echo Core::render($bank_data['name']);
                                                            ?>
                                                        </h6>
                                                    </td>

                                                    <td data-order="<?php echo $rec["offered_amount"];?>">
                                                        <h6 class="mb-0" align="left">
                                                            <?php
                                                            $amount = number_format((float)str_replace(",", "", $rec["offered_amount"]));
                                                            echo Core::render($rec["currency"]) . "&nbsp;&nbsp;&nbsp;" . $amount;
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
                                                            if ($rec['term_length_type'] == "HISA") {
                                                                echo '-';
                                                            } else {
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
                                                        if (!empty($rec["maturity_date"])) {
                                                            $date = new DateTime($rec["maturity_date"], new DateTimeZone("UTC"));
                                                            $date->setTimezone(new DateTimeZone(Model::formattedTimezone($bank_demographic_user_data['timezone'])));
                                                            echo $date->format("Y-m-d");
                                                        } else {
                                                            echo '-';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="col-12">
                                                            <div style="display: inline-block;">
                                                                <a href="active_contract_offers?rqid=<?php echo Core::urlValueEncrypt($rec["depositor_request_id"]); ?>"
                                                                   class="btn btn-primary mmy_btn btn-block">Review
                                                                    Offers</a>
                                                            </div>
                                                            <div style="display: inline-block;">
                                                                <a href="comp_details?cnid=<?php echo Core::urlValueEncrypt($rec["id"]); ?>"
                                                                   class="btn btn-primary  mmy_btn btn-block">View</a>
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

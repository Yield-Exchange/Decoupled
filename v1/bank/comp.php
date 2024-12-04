<?php
session_start();
require_once("header.php");
require_once "../config/RequestsModel.php";

if( AuthModel::isLoggedIn() ){

    global $user_data;

    require_once("sidebar.php");

    $contracts = BankModel::getBankCompletedContracts($user_data['id']);
    $bank_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
    $size = 0;

    if (!empty($contracts)){
        $size = sizeof($contracts);
    }

    Core::activityLog("Bank Active Deposits");
?>
        <style>
            .dtHorizontalExampleWrapper {
                max-width: 600px;
                margin: 0 auto;
            }
            #dtHorizontalExample th, td {
                white-space: nowrap;
            }
        </style>
        <script>
            $(document).ready(function () {
                $('.custom-data-tables').DataTable({
                    "order": [[ 7, "DESC" ]],
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
                                                <td colspan="3" class="my_h" ><span class="b_b">ACT</span>IVE DEPOSITS <span class="badge bg-blue badge-pill"><?php echo $size; ?></span></td>
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
                                                <th>Gic Number</th>
                                                <th>Depositor Name</th>
                                                <th>Province</th>
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
                                    if( !empty($contracts) ){
                                        foreach($contracts as $rec){
                                            $depositor_data = AuthModel::getUserDataByID($rec['user_id']);
                                            $depositor_demographic_data = AuthModel::getUserDemographicData($rec['user_id']);
                                    ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0" ><?php echo $rec["gic_number"]; ?></h6>
                                                </div>
                                            </td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <?php
                                                    echo Core::render($depositor_data['name']);
                                                    ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <?php
                                                        echo Core::render($depositor_demographic_data['province']);
                                                    ?>
                                                </h6>
                                            </td>
											<td data-order="<?php echo $rec["offered_amount"];?>">
												<h6 class="mb-0" align="left">
                                                    <?php
                                                        echo Core::render($rec["currency"]). "&nbsp;&nbsp;&nbsp;&nbsp;" .number_format($rec["offered_amount"]). "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">
                                                        <?php
                                                        $product = RequestsModel::getProductByID($rec["product_id"]);
                                                        echo !empty($product) ? Core::render($product['description']) : '';
                                                        ?>
                                                    </h6>
                                                </div>
                                            </td>
                                            <td style="padding:8px">
                                                <h6 class="mb-0">
                                                    <?php
                                                    if ($rec['term_length_type'] == "HISA"){
                                                        echo '-';
                                                    }else {
                                                        echo Core::render($rec['term_length'] . ' ' . ucwords(strtolower($rec['term_length_type'])));
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
                                                <h6 class="mb-0">
                                                    <?php
                                                        if( !empty($rec["maturity_date"]) ) {
                                                            $date = new DateTime($rec["maturity_date"], new DateTimeZone("UTC"));
                                                            $date->setTimezone(new DateTimeZone(Model::formattedTimezone($bank_demographic_user_data['timezone'])));
//                                                            if ($rec['term_length_type'] == "MONTHS") {
//                                                                $date->modify(" +" . $rec['term_length'] . ' month');
//                                                            } else {
//                                                                $date->modify(" +" . $rec['term_length'] . ' day');
//                                                            }
                                                            echo $date->format("Y-m-d");
                                                        }else{
                                                            echo '-';
                                                        }
                                                    ?>
                                                </h6>
                                            </td>

                                            <td class="text-center">
												<a href="c_details?id=<?php echo Core::urlValueEncrypt($rec['offer_id']);?>&&rqid=<?php echo Core::urlValueEncrypt($rec['depositor_request_id']);?>" class="btn btn-primary btn-block mmy_btn">View</a>
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
    require_once("footer.php");
}
?>
            
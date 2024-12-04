<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";
require_once "../config/AuthModel.php";
require_once "../config/RequestsModel.php";
require_once "timezone.php";

if ( AuthModel::isLoggedIn() ) {
    $user_data = AuthModel::getUserdata();
    $bank_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);

    $rzlt = BankModel::getPostRequestsForBank($user_data['id']);
    $size = $rzlt['total'];
    $data = $rzlt['data'];
    $total_amount = $rzlt['total_deposit'];

    $rzlt1 = BankModel::getBankInProgressBidsSummary($user_data['id']);
    $size1 = $rzlt1['total'];
    $data1 = $rzlt1['data'];

    $pending_total = $rzlt1['total_deposit'];

    $rzlt3 = BankModel::getBankPendingContractsBids($user_data['id']);
    $size3 = $rzlt3['total'];
    $data3 = $rzlt3['data'];
    $total_amount3 = $rzlt3['total_deposit'];
    ?>
                <!-- Main charts -->
				<div class="row">
					<div class="col-xl-12">

                        <div class="row">
                            <div class="col-sm-4">
                                <div style="border-radius:20px;" class="card">
                                   <h4 style="margin-left:15px;padding:15px;color:grey"><span style="border-bottom:3px solid #03a9f4">NEW</span> REQUESTS</h4>

                                    <div class="row" style="padding:20px">
                                        <div class="col-3">
                                            <img src="image/2.png" class="img-responsive" style="max-height:60px">
                                        </div>
                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">No</h6>
                                            <p style="font-size:20px;font-weight:bold"><?php echo $size; ?></p>
                                        </div>
                                        <div class="col-<?php echo !empty($total_amount['USD']) ? 3 : 6;?>">
                                            <h6 style="font-weight:bold;color:grey">CAD</h6>
                                            <p style="font-size:20px;font-weight:bold" ><?php echo Model::nice_number($total_amount['CAD']); ?> </p>
                                        </div>
                                        <?php
                                        if (!empty($total_amount['USD'])){
                                        ?>
                                            <div class="col-3">
                                                <h6 style="font-weight:bold;color:grey">USD</h6>
                                                <p style="font-size:20px;font-weight:bold" ><?php echo Model::nice_number($total_amount['USD']); ?> </p>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div style="border-radius:20px;" class="card">
                                  <h4 style="margin-left:15px;padding:15px;color:grey"><span style="border-bottom:3px solid #03a9f4">IN</span> PROGRESS</h4>

                                    <div class="row" style="padding:20px">

                                        <div class="col-3">
                                            <img src="image/1.png" class="img-responsive" style="max-height:60px">
                                        </div>
                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">No</h6>
                                            <p style="font-size:20px;font-weight:bold"><?php echo $size1; ?></p>
                                        </div>
                                        <div class="col-<?php echo !empty($pending_total['USD']) ? 3 : 6;?>">
                                            <h6 style="font-weight:bold;color:grey">CAD</h6>
                                            <p style="font-size:20px;font-weight:bold"><?php echo Model::nice_number($pending_total['CAD']); ?></p>
                                        </div>
                                        <?php
                                        if (!empty($pending_total['USD'])){
                                        ?>
                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">USD</h6>
                                            <p style="font-size:20px;font-weight:bold"><?php echo Model::nice_number($pending_total['USD']); ?></p>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div style="border-radius:20px;" class="card">
                                     <h4 style="margin-left:15px;padding:15px;color:grey"><span style="border-bottom:3px solid #03a9f4">PEN</span>DING DEPOSITS</h4>
                                    <div class="row" style="padding:20px">
                                        <div class="col-3">
                                            <img src="image/3.png" class="img-responsive" style="max-height:60px">
                                        </div>
                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">No</h6>
                                            <p style="font-size:20px;font-weight:bold"><?php echo $size3; ?></p>
                                        </div>
                                        <div class="col-<?php echo !empty($total_amount3['USD']) ? 3 : 6;?>">
                                            <h6 style="font-weight:bold;color:grey">CAD</h6>
                                            <p style="font-size:20px;font-weight:bold" ><?php echo Model::nice_number($total_amount3['CAD']); ?> </p>
                                        </div>
                                        <?php
                                        if (!empty($total_amount3['USD'])){
                                        ?>
                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">USD</h6>
                                            <p style="font-size:20px;font-weight:bold" ><?php echo Model::nice_number($total_amount3['USD']); ?> </p>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

						<div class="card">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr class="table-active table-border-double">
                                            <td colspan="3"class="my_h"><span class="b_b">NEW</span> REQUESTS <span class="badge bg-blue badge-pill"><?php echo $size; ?></span></td>
                                            <td class="text-right">
                                                <a style="color:#2CADF5;text-decoration:underline;" href="requests">View all</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
							    <div class="table-responsive">
								<table class="table custom-data-tables table-condensed">
									 <thead>
										<tr>
                                            <th>Depositor Name</th>
                                            <th>Province</th>
                                            <th>Request Amount</th>
                                            <th>Product</th>
                                            <th>Investment Period</th>
                                            <th>Closing Date & time</th>
                                            <th>Action</th>
										</tr>
									</thead>
									<tbody>

                                    <?php
                                    if ($data) {
                                        $s = 0;
                                        foreach ($data as $rec) {
                                            $s++;
                                            if($s > 3){ break;}
                                            $depositor_data = AuthModel::getUserDemographicData($rec['user_id']);
                                            $depositor = AuthModel::getUserDataByID($rec['user_id']);
                                    ?>
										<tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0"><?php echo Core::render($depositor['name']); ?></h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0"><?php echo Core::render($depositor_data['province']); ?></h6>
                                                </div>
                                            </td>
                                            <td data-order="<?php echo $rec["amount"];?>">
                                                <h6 class="mb-0" align="left">
                                                    <?php
                                                    echo Core::render($rec["currency"]) . "&nbsp;&nbsp;&nbsp;" . number_format($rec["amount"]);
                                                    ?>
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
												<h6 class="mb-0">
                                                    <?php
                                                    if ($rec['term_length_type'] == "HISA"){
                                                        echo '-';
                                                    }else {
                                                        echo Core::render($rec['term_length']) . ' ' . ucwords(strtolower(Core::render($rec['term_length_type'])));
                                                    }
                                                    ?>
                                                </h6>
											</td>
											<td>
												<h6 class="mb-0">
                                                    <?php
                                                        echo Model::dateTimeFromUTC('Y-m-d h:i a',$rec['closing_date_time'],$bank_demographic_user_data['timezone']);
                                                    ?>
                                                </h6>
                                            </td>
                                            <td align="right">
                                                <a href="bid_data?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>" class="btn btn-primary mmy_btn btn-block">View</a>
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

                            <div class="card">
							  <div class="table-responsive">
								<table class="table text-nowrap">

                                    <tbody>
                                        <tr class="table-active table-border-double">
											<td colspan="3" class="my_h">
                                                <span class="b_b">IN</span> PROGRESS <span class="badge bg-blue badge-pill"><?php echo Model::countArray($rzlt1['data']); ?></span>
                                            </td>
                                            <td class="text-right">
                                                <a style="color:#2CADF5;text-decoration:underline;" href="my_bids">View all</a>
											</td>
										</tr>
                                    </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
								<table class="table custom-data-tables table-condensed">
									 <thead>
										<tr>
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
                                            if ($data1) {
                                                $r = 0;
                                                foreach ($data1 as $rec) {
                                                    $r++;
                                                    if ($r > 3) {break;}

                                                    $deposit_request = RequestsModel::getRequestByID($rec['depositor_request_id']);
                                                    $depositor = AuthModel::getUserDataByID($deposit_request['user_id']);
                                                    $depositor_demographic_data = AuthModel::getUserDemographicData($deposit_request['user_id']);
                                            ?>
                                            <tr>
                                                <td style="padding:8px">
                                                    <div>
                                                        <h6 class="mb-0" ><?php echo Core::render($deposit_request["reference_no"]); ?></h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo Core::render($depositor['name']); ?></h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo Core::render($depositor_demographic_data['province']); ?></h6>
                                                    </div>
                                                </td>

                                                <td data-order="<?php echo $deposit_request["amount"];?>">
                                                    <h6 class="mb-0" align="left">
                                                        <?php
                                                        echo Core::render($deposit_request["currency"]) . "&nbsp;&nbsp;&nbsp;" . number_format($deposit_request["amount"]);
                                                        ?>
                                                    </h6>
                                                </td>

                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="mb-0">
                                                            <?php
                                                                $product = RequestsModel::getProductByID($deposit_request["product_id"]);
                                                                echo !empty($product) ? Core::render($product['description']) : '';
                                                            ?>
                                                        </h6>
                                                    </div>
											    </td>

                                                <td>
                                                    <h6 class="mb-0">
                                                        <?php
                                                        if ($deposit_request['term_length_type'] == "HISA"){
                                                            echo '-';
                                                        }else {
                                                            echo Core::render($deposit_request['term_length']) . ' ' . ucwords(strtolower(Core::render($deposit_request['term_length_type'])));
                                                        }
                                                        ?>
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
                                                        $timezone = Core::render($bank_demographic_user_data['timezone']);
                                                        echo Model::dateTimeFromUTC('Y-m-d h:i a',$rec['rate_held_until'],$timezone);
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <a href="view?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>&&rqid=<?php echo Core::urlValueEncrypt($deposit_request['id']); ?>" class="btn btn-primary mmy_btn btn-block">View</a>
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
				<!-- /main charts -->
 <?php
}
?>
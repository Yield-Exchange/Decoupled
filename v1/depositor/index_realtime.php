<?php
    session_start();
    require_once "../config/db.php";
    require_once "../config/Model.php";
    require_once "../config/AuthModel.php";
    require_once "../config/RequestsModel.php";
    require_once "timezone.php";

    $user_data = AuthModel::getUserdata();
    $usr_demographic_data = AuthModel::getUserDemographicData($user_data['id']);

    $review_offers = DepositorModel::getDepositorRequestsByUserID($user_data["id"]);
    $contract_data = DepositorModel::getDepositorsPendingContracts($user_data["id"]);
    $active_contract_data = DepositorModel::getDepositorsActiveContracts($user_data["id"]);
?>
				<div class="row c-dashboard">
					<div class="col-xl-12">

					 <div class="row">
                            <div class="col-sm-4">
                                <div style="border-radius:20px;" class="card">
                                   <h4 style="margin-left:15px;padding:15px;color:grey"><span style="border-bottom:3px solid #03a9f4">RE</span>VIEW OFFERS</h4>
                    
                                    <div class="row" style="padding:20px">

                                        <div class="col-3">
                                            <img src="image/2.png" class="img-responsive" style="max-height:60px">
                                        </div>
                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">No</h6>
                                            <p style="font-size:20px;font-weight:bold"><?php echo $review_offers['total']; ?></p>
                                        </div>
                                        <div class="col-<?php echo !empty($review_offers['total_deposit']['USD']) ? 3 : 6;?>">
                                            <h6 style="font-weight:bold;color:grey">CAD</h6>
                                            <p style="font-size:20px;font-weight:bold" ><?php echo Model::nice_number($review_offers['total_deposit']['CAD']); ?> </p>
                                        </div>
                                        <?php
                                        if (!empty($review_offers['total_deposit']['USD'])){
                                        ?>
                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">USD</h6>
                                            <p style="font-size:20px;font-weight:bold" ><?php echo Model::nice_number($review_offers['total_deposit']['USD']); ?> </p>
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
                                            <img src="image/1.png" class="img-responsive" style="max-height:60px">
                                        </div>

                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">No</h6>
                                            <p style="font-size:20px;font-weight:bold"><?php echo $contract_data['total']; ?></p>
                                        </div>
                                        <div class="col-<?php echo !empty($contract_data['total_deposit']['USD']) ? 3 : 6;?>">
                                            <h6 style="font-weight:bold;color:grey">CAD</h6>
                                            <p style="font-size:20px;font-weight:bold">
                                                <?php
                                                    echo Model::nice_number($contract_data['total_deposit']['CAD']);
                                                ?>
                                            </p>
                                        </div>
                                        <?php
                                        if (!empty($contract_data['total_deposit']['USD'])){
                                        ?>
                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">USD</h6>
                                            <p style="font-size:20px;font-weight:bold">
                                                <?php
                                                    echo Model::nice_number($contract_data['total_deposit']['USD']);
                                                ?>
                                            </p>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div style="border-radius:20px;" class="card">
                                  <h4 style="margin-left:15px;padding:15px;color:grey"><span style="border-bottom:3px solid #03a9f4">ACT</span>IVE DEPOSITS</h4>
                                    <div class="row" style="padding:20px">
                                        <div class="col-3">
                                            <img src="image/3.png" class="img-responsive" style="max-height:60px">
                                        </div>
                                        <div class="col-3" style="pedding:0px">
                                            <h6 style="font-weight:bold;color:grey">No</h6>
                                            <p style="font-size:20px;font-weight:bold"><?php echo $active_contract_data['total']; ?></p>
                                        </div>
                                        <div class="col-<?php echo !empty($active_contract_data['total_deposit']['USD']) ? 3 : 6;?>">
                                            <h6 style="font-weight:bold;color:grey">CAD</h6>
                                            <p style="font-size:20px;font-weight:bold"  ><?php echo Model::nice_number($active_contract_data['total_deposit']['CAD']); ?></p>
                                        </div>
                                        <?php
                                        if (!empty($active_contract_data['total_deposit']['USD'])){
                                        ?>
                                        <div class="col-3">
                                            <h6 style="font-weight:bold;color:grey">USD</h6>
                                            <p style="font-size:20px;font-weight:bold"  ><?php echo Model::nice_number($active_contract_data['total_deposit']['USD']); ?></p>
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
                                            <td colspan="3" class="my_h"><span class="b_b">REV</span>IEW OFFERS <span class="badge bg-blue badge-pill"><?php echo $review_offers['total']; ?></span></td>
                                            <td class="text-right"><a style="color:#03a9f4;text-decoration:underline;" href="bids" >View all</a></td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                        <div class="col-sm-12">
							<div class="table-responsive">
								<table class="table table-condensed custom-data-tables">
									 <thead>
										<tr style="width:70%">
                                            <th>Closing date & time</th>
                                            <th>Request id</th>
                                            <th>Product</th>
                                            <th>Amount</th>
                                            <th>Investment period</th>
                                            <th>No of offers</th>
                                            <th>Highest</th>
                                            <th>Lowest</th>
                                            <th>Action</th>
										</tr>
									</thead>
									<tbody>

<?php
    if ($review_offers['data']) {
        $rty = 0;
        foreach ($review_offers['data'] as $record) {
                    $rty++;
                    if ($rty > 3) {break;}

//                        $user_demographic_data = AuthModel::getUserDemographicData($record['user_id']);
                        $timezone = $usr_demographic_data['timezone'];
?>
                        <tr>
                            <td class="text-left">
                                <h6 class="mb-0">
                                    <?php
                                        echo Model::dateTimeFromUTC('Y-m-d h:i a',$record['closing_date_time']);
                                    ?>
                                </h6>
                            </td>
                            <td class="text-left">
                                <h6 class="mb-0"><?php echo Core::render($record["reference_no"]); ?></h6>
                            </td>
                            <td>
                                <h6 class="mb-0">
                                    <?php
                                        $product = RequestsModel::getProductByID($record["product_id"]);
                                        echo !empty($product) ? Core::render($product['description']) : '';
                                    ?>
                                </h6>
                            </td>
                            <td data-order="<?php echo $record["amount"];?>">
                                <h6 class="mb-0">
                                    <?php
                                        echo Core::render($record["currency"]) . "&nbsp;&nbsp;&nbsp;" . number_format($record["amount"]);
                                    ?>
                                </h6>
                            </td>
                            <td>
                                <h6 class="mb-0">
                                    <?php
                                    if ($record['term_length_type'] == "HISA"){
                                        echo '-';
                                    }else {
                                        echo Core::render($record['term_length']) . ' ' . Core::render(ucwords(strtolower($record['term_length_type'])));
                                    }
                                    ?>
                                </h6>
                            </td>

                            <td class="text-left">
                                <h6 class="mb-0">
                                    <?php
                                        $total_bids = db::getcell("select count(*) as total from offers o, invited i where i.id=o.invitation_id AND i.depositor_request_id='".$record['id']."' AND o.offer_status IN('ACTIVE')");
                                        echo $total_bids;
                                    ?>
                                </h6>
                            </td>
                            <td class="text-left">
                                <h6 class="mb-0">
                                    <?php
                                        $get_highest = db::getRecord("select o.id,o.rate_type,o.prime_rate,o.rate_operator,Max(interest_rate_offer) as interest_rate_offer from offers o, invited i where i.depositor_request_id='".$record['id']."' AND i.id=o.invitation_id AND o.offer_status IN('ACTIVE') GROUP BY o.id");
                                        if ($get_highest['rate_type']!='VARIABLE'){
                                            echo BankModel::getInterest($get_highest['interest_rate_offer']);
                                        }else {
                                            echo BankModel::getInterest($get_highest['prime_rate'], true,$get_highest['rate_operator']);
                                        }
                                    ?>
                                </h6>
                            </td>
                            <td class="text-left">
                                <h6 class="mb-0">
                                    <?php
                                        $get_lowest = db::getRecord("Select o.id,o.rate_type,o.prime_rate,o.rate_operator,Min(interest_rate_offer) as interest_rate_offer from offers o, invited i where i.depositor_request_id='".$record['id']."' AND i.id=o.invitation_id AND o.offer_status IN('ACTIVE') GROUP BY o.id");
                                        if ($get_lowest['rate_type']!='VARIABLE'){
                                            echo BankModel::getInterest($get_lowest['interest_rate_offer']);
                                        }else {
                                            echo BankModel::getInterest($get_lowest['prime_rate'], true,$get_lowest['rate_operator']);
                                        }
                                    ?>
                                </h6>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-primary btn-block mmy_btn" href="vrzlts?rqid=<?php echo Core::urlValueEncrypt($record["id"]); ?>">View</a>
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
                                                <td colspan="3" class="my_h" ><span class="b_b my_h">PEN</span>DING DEPOSITS <span class="badge bg-blue badge-pill"><?php echo $contract_data['total']; ?></span></td>
                                                <td class="text-right"><a style="color:#03a9f4;text-decoration:underline;" href="contract" >View all</a></td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed custom-data-tables">
                                            <thead>
                                                <tr>
                                                    <th>Date Of Deposit</th>
                                                    <th>Rate Held Until</th>
                                                    <th>Institution</th>
                                                    <th>Deposit Amount</th>
                                                    <th>Product</th>
                                                    <th>Investment Period</th>
                                                    <th>Interest Rate %</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if ($contract_data['data']) {
                                                $se_ = 0;
                                                foreach ($contract_data['data'] as $rec) {
                                                    $se_++;
                                                    if ($se_ > 3) {break;}

                                                    $bank_data = AuthModel::getUserDataByID($rec['invited_user_id']);
//                                                    $user_demographic_data = AuthModel::getUserDemographicData($rec['user_id']);
                                                    $timezone = $usr_demographic_data['timezone'];
                                            ?>
                                                    <tr>

                                                        <td>
                                                            <h6 class="mb-0"><?php echo Model::dateTimeFromUTC('Y-m-d',$rec["date_of_deposit"],$timezone); ?></h6>
                                                        </td>

                                                        <td>
                                                            <h6 class="mb-0"><?php echo Model::dateTimeFromUTC('Y-m-d h:i a',$rec["rate_held_until"],$timezone); ?></h6>
                                                        </td>

                                                        <td>
                                                            <h6 class="mb-0">
                                                                <?php
                                                                    echo Core::render($bank_data['name']);
                                                                ?>
                                                            </h6>
                                                        </td>

                                                        <td class="" data-order="<?php echo $rec["offered_amount"];?>">
                                                            <h6 class="mb-0">

                                                                <?php
                                                                    $amount = number_format(str_replace(",","",$rec["offered_amount"]));
                                                                    echo Core::render($rec["currency"]) . " " . $amount;
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
                                                    }?>

                                                            </h6>
                                                        </td>

                                                        <td>
                                                            <h6 class="mb-0">
                                                                <?php
                                                                    echo BankModel::getInterest($rec["interest_rate_offer"]);
                                                                ?>
                                                            </h6>
                                                        </td>

                                                        <td>
                                                            <a href="msgs?id=<?php echo Core::urlValueEncrypt($rec['invited_user_id']); ?>&c_id=<?php echo Core::urlValueEncrypt($rec['id']); ?>" class="btn btn-primary mmy_btn btn-block">Chat</a>
                                                        </td>

                                                        <td>
                                                            <a href="c_details?cnid=<?php echo Core::urlValueEncrypt($rec["id"]); ?>" class="btn btn-primary mmy_btn btn-block">View</a>
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
            <!-- /main charts -->
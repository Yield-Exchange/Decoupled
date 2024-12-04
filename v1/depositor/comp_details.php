<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

if (  AuthModel::isLoggedIn()  ) {
    require_once "sidebar.php";

    $data = "";
    if (isset($_GET['cnid'])) {
        Core::activityLog("Depositor Active Deposits Details");

        $id = Core::urlValueDecrypt($_GET['cnid']);
        $contract_data = BankModel::getContractByID($id);
        if ( !empty($contract_data) ){
            $data = RequestsModel::getRequestByID($contract_data['depositor_request_id'],true);
            if (empty($data)){
                return;
            }

            $depositor_data = AuthModel::getUserDataByID($data['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($data['user_id']);
            $account_doc = DepositorModel::getDepositorDoc($data['user_id']);

            $bank = AuthModel::getUserDataByID($contract_data['invited_user_id']);
            $bank_demographic_data = AuthModel::getUserDemographicData($contract_data['invited_user_id']);
            $credit_ratings = Core::getRatings($contract_data['invited_user_id']);

            $offer = BankModel::getOfferByID($contract_data['offer_id']);
    ?>

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content">
                <!-- Main charts -->
				<div class="row">
					<div class="col-12">
                        <!-- Support tickets -->
						<div class="card transparent-card">

				            <div class="table-responsive" style="padding-left:0px">
								<table class="table text-nowrap" >

                                    <tbody>
                                        <tr class="table-active table-border-double">
											<td style="padding-left:10px" colspan="3" class="my_h"><span class="b_b">REVIEW</span> OFFER</td>
											<td class="text-right"></td>
										</tr>
                                    </tbody>

                                </table>
                            </div>
                            <?php
                                $user_data = AuthModel::getUserdata();
                                $user_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
                                $contract = BankModel::getBankBidContract($offer['id']);
                            ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card" style="min-height: 300px;">
                                        <div class="card-body" style="padding: 1rem;">
                                            <div class="row justify-content-center">
                                                <?php
                                                if ( !empty($bank['profile_pic']) ) {
                                                    ?>
                                                    <img src="../bank/image/<?php echo $bank['profile_pic']; ?>" width="80" height="80" alt=""/>
                                                    <?php
                                                }else {
                                                    ?>
                                                    <div class="i-initial-inverse-big"><span><?php echo !empty($bank['name'][0]) ? Core::render($bank['name'][0]) : 'Y'?></span></div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="col-md-12">
                                                <p><img src="<?php echo BASE_URL.'/assets/images/icons/bank.png';?>" class="img-responsive mr-2" height="25"/> <?php echo Core::render(BankModel::isBankOrBrokerName($bank, $offer)); ?></p>
                                                <p><img src="<?php echo BASE_URL.'/assets/images/icons/Credit Rating.png';?>" class="img-responsive mr-2" height="25"/><?php echo !empty($credit_ratings) ? ($credit_ratings["credit_rating"] == "Any/Not Rated" ? "Not Rated" : $credit_ratings["credit_rating"]) : ''; ?></p>
                                                <p><img src="<?php echo BASE_URL.'/assets/images/icons/Insurance.png';?>" class="img-responsive mr-2" height="25"/><?php echo !empty($credit_ratings) ? $credit_ratings["deposit_insurance"] : ''; ?></p>
                                                <p><img src="<?php echo BASE_URL.'/assets/images/icons/Email.png';?>" class="img-responsive mr-2" height="25"/><?php echo Core::render($bank['email']); ?></p>
                                                <p><img src="<?php echo BASE_URL.'/assets/images/icons/Telephone.png';?>" class="img-responsive mr-2" height="25"/><?php echo Core::render($bank_demographic_data['telephone']);?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card" style="min-height: 300px;">
                                        <div class="card-body">
                                            <?php
                                            $depositor_demographic_data = AuthModel::getUserDemographicData($data['user_id']);
                                            $invited_user_id = db::getCell("SELECT invited_user_id FROM invited WHERE id='".$offer['invitation_id']."' ");
                                            $bank_demographic_data = AuthModel::getUserDemographicData($invited_user_id);
                                            ?>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 style="color:#2CADF5;font-weight:bold;">Deposit Summary</h5>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;">Deposit Id</p></div>
                                                        <div class="col-md-7"><span style="font-weight:bold"><?php echo Core::render($contract["reference_no"]); ?></span></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;">Product</p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                $product = RequestsModel::getProductByID($data["product_id"]);
                                                                echo !empty($product) ? Core::render($product['description']) : '';
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if ($data['term_length_type'] != "HISA"){
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;"><?php echo !empty($product) && trim(strtolower($product['description'])) =="notice deposit" ? 'Notice Period' : 'Lockout Period';?> </p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                echo !empty($data['lockout_period_days']) && in_array(trim(strtolower($product['description'])),['notice deposit','cashable']) ? Core::render($data['lockout_period_days']).' days' : '-';
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;">Amount</p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                echo Core::render($data["currency"]) ." ".number_format($contract['offered_amount']);
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;">Compounding Frequency</p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php echo Core::render($data['compound_frequency']); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;">Special Instructions (Request)</p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                echo Core::render($data["special_instructions"]);
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;"> <?php echo $data['term_length_type'] != "HISA" ? "GIC" :"HISA"?> number</p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php echo empty($contract['gic_number']) ? '-': Core::render($contract['gic_number']); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if ( $data['term_length_type'] == "HISA" ) {
                                                    ?>
                                                        <div class="row">
                                                            <div class="col-md-5"><p style="font-weight:bold;">
                                                                    Interest Rate Type</p></div>
                                                            <div class="col-md-7">
                                                                <span style="font-weight:bold">
                                                                    <?php echo ucwords(strtolower($offer['rate_type'])); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;">Interest Rate</p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                if ($offer['rate_type']!='VARIABLE'){
                                                                    echo BankModel::getInterest($offer['interest_rate_offer']);
                                                                }else {
                                                                    echo BankModel::getInterest($offer['prime_rate'], true,$offer['rate_operator'],true);
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if ($data['term_length_type'] != "HISA"){
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;">Term Length</p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                              <?php echo Core::render($data['term_length'].' '.strtolower($data['term_length_type'])); ?>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;">Maturity Date</p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                if( !empty($contract["maturity_date"]) ) {
                                                                    $date = new DateTime($contract["maturity_date"], new DateTimeZone("UTC"));
                                                                    $date->setTimezone(new DateTimeZone(Model::formattedTimezone($bank_demographic_data['timezone'])));
                                                                    echo $date->format("Y-m-d");
                                                                }else{
                                                                    echo '-';
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-5"><p style="font-weight:bold;">Special Instructions (Offer)</p></div>
                                                        <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                echo Core::render($offer["special_instructions"]);
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                              <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <a href="<?php echo isset($_GET['history']) ? 'exp' : 'comp'?>" class="btn btn-block" style="border:1px solid gainsboro" style="color:white" >Back</a>
                                    </div>
					            </div>
                            </div>
                            </div>
						</div>

				 <br>
				</div>

			</div>
			<!-- /content area -->
           <br>
            </div>
            <!-- Modal -->
            <script>
                function approve(){
                    return(confirm("Do you want to Approve?"));
                }
                function reject(){
                    return(confirm("Do you want to retract the awarded contract?"));
                }
            </script>
<?php
require_once "footer.php";
        }else{
            echo "<script>location='comp.php'</script>";
        }
    }else{
        echo "<script>location='comp.php'</script>";
    }
}
?>
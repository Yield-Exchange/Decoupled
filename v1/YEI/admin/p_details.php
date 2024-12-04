<?php
require_once("header.php");
require_once BASE_DIR."/config/RequestsModel.php";

if( AdminModel::isLoggedIn()){
    require_once("sidebar.php");

    if(isset($_GET['cnid'])){
        $id=$_GET['cnid'];
        $contract_data = DepositorModel::getContractByID($id);
        if (!empty($contract_data)){

            $depositor_request = RequestsModel::getRequestByID($contract_data['depositor_request_id']);
            $depositor_data = AuthModel::getUserDataByID($contract_data['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($contract_data['user_id']);
            $account_doc = DepositorModel::getDepositorDoc($contract_data['user_id']);
            $bank_data = AuthModel::getUserDataByID($contract_data['invited_user_id']);
            $bank_demographic_data = AuthModel::getUserDemographicData($contract_data['invited_user_id']);
            $offer = BankModel::getOfferByID($contract_data['offer_id']);
            $credit_ratings = Core::getRatings($contract_data['invited_user_id']);

            Core::activityLog("admin view pending Deposit Details");
?>

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                        <span class="breadcrumb-item active">Pending Deposits</span>
							<span class="breadcrumb-item active">Details</span>
					</div>
					<div class="header-elements d-none"></div>
				</div>
			</div>
			<!-- /page header -->

			<!-- Content area -->
			<div class="content">
              		<div class="content">
                <!-- Main charts -->
				<div class="row">
					<div class="col-xl-12">
                        <!-- Support tickets -->
						<div class="card" style="padding:20px;padding-top:10px">
                            <div class="table-responsive" style="padding-left:0px">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr class="table-active table-border-double">
                                                <td style="padding-left:10px" colspan="3" class="my_h"><span class="b_b">REVIEW</span> OFFER</td>
                                                <td class="text-right"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
                            <br>
                            <div class="col-12">
                             <h5 style="color:#2CADF5;font-weight:bold;">Depositor information </h5>
                               <div class="row">
                                   <?php
                                        if(true){
                                   ?>
                                    <div class="col-sm-1 col-4">
                                        <?php
                                        if ( !empty($depositor_data['profile_pic']) ) {
                                        ?>
                                            <img src="../depositor/image/<?php echo $depositor_data['profile_pic']; ?>" style="max-height:100px;max-width: 100%;" alt=""/>
                                        <?php
                                        }else {
                                        ?>
                                            <div class="i-initial-inverse-big"><span><?php echo !empty($depositor_data['name'][0]) ? $depositor_data['name'][0] : 'Y'?></span></div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-sm-10 col-8">
                                        <span style="font-weight:bold"><?php echo $depositor_data['name'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; ?></span>
                                        <br>
                                        <span style="margin-top:15px"><?php echo $depositor_demographic_data['city']; ?></span>
                                        <br>
                                        <span style="margin-top:15px" ><?php echo $depositor_demographic_data['telephone']; ?></span>
                                        <br/>
                                        <span><?php echo $depositor_data['email']; ?></span>
                                        <br/>
<!--                                        <span><a target="_blank" href="--><?php //echo !empty($account_doc) ? $account_doc['account_doc'] : ''; ?><!--">View Document(s)</a></span>-->
                                    </div>
                                    <?php
                                    }else{
                                    ?>
                                        <p style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;Anonymous Deposit Request</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                            <br>
                   
                            </div>

                            <div class="col-12">
                                <br>
                                <h5 style="color:#2CADF5;font-weight:bold;">Request Summary</h5>
                                <div class="row">
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Request id</p>
                                        <?php echo $depositor_request["reference_no"]; ?>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Product</p>
                                        <?php
                                        $product = RequestsModel::getProductByID($depositor_request["product_id"]);
                                        echo !empty($product) ? $product['description'] : '';
                                        ?>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Awarded Amount</p>
                                        <?php $amount = $depositor_request["amount"]; echo $depositor_request["currency"] . " " . $amount;?>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Term Length</p>
                                        <?php
                                         if ($depositor_request['term_length_type'] == "HISA"){
                                             echo '-';
                                         }else {
                                             echo $depositor_request['term_length'] . ' ' . ucwords(strtolower($depositor_request['term_length_type']));
                                         }?>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Closing Date & Time</p>
                                        <?php
                                        $timezone=$depositor_demographic_data['timezone'];
                                        echo date("Y-m-d H:i", strtotime($depositor_request['closing_date_time'])) . " " . $timezone;
                                        ?>
                                    </div>
                                </div>
                                <br><br>
                                <div class="row">
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Date of deposit (approx)</p>
                                        <?php echo $depositor_request['date_of_deposit']; ?>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Compounding frequency</p>
                                        <?php echo $depositor_request['compound_frequency']; ?>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Short Term DBRS Rating</p>
                                        <?php echo $depositor_request['requested_short_term_credit_rating']; ?>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Deposit Insurance</p>
                                        <?php echo $depositor_request['requested_deposit_insurance']; ?>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Ask Rate</p>
                                        <?php
                                        echo empty($depositor_request['requested_rate']) ? " - " : number_format($depositor_request['requested_rate'],2)."%";
                                        ?>
                                    </div>

                                </div>
                                <br>
                                <div class="row">

                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Special Instructions</p>
                                        <?php echo $depositor_request['special_instructions']; ?>
                                    </div>

                                </div>
                                <br><br>

                            </div>
                            
                           <br><br>

                            <div class="col-12">
                                <h5 style="color:#2CADF5;font-weight:bold;">Offer Details</h5>
                                <div class="row" style="font-weight:normal;color:#777;">
                                    <div class="col-sm-2 col-3">
                                        Institution name:<br/> <b style="color:#333;"><?php echo BankModel::isBankOrBrokerName($bank_data, $offer); ?></b>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        Short term rating:<br/> <b style="color:#333;"><?php echo !empty($credit_ratings) ? $credit_ratings["credit_rating"] : ''; ?></b>
                                    </div>
                                    <div class="col-sm-2 col-2">
                                        Deposit Insurance:<br/> <b style="color:#333;"><?php echo !empty($credit_ratings) ? $credit_ratings["deposit_insurance"] : ''; ?></b>
                                    </div>
                                    <div class="col-sm-2 col-2">
                                        Email:<br/> <b style="color:#333;"><?php echo $bank_data['email']; ?></b>
                                    </div>
                                    <div class="col-sm-2 col-2">
                                        Phone Number:<br/>
                                        <b style="color:#333;">
                                            <?php echo $bank_demographic_data['telephone']; ?>
                                        </b>
                                    </div>
                                </div>
                                <br/>
                                <br/>
                                <div class="row">
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Offer Expiry Date :</p>
                                        <?php
                                        $timezone = $bank_demographic_data['timezone'];
                                        echo date("Y-m-d H:i", strtotime($offer['rate_held_until'])) . " " . $timezone;?>
                                    </div>
                                    <div class="col-sm-2 col-3">
                                        <p style="font-weight:bold;">Interest Rate %</p>
                                        <?php
                                        echo BankModel::getInterest($offer['interest_rate_offer']);
                                        ?>
                                    </div>

                                    <div class="col-sm-2">
                                        <p style="font-weight:bold;">Maximum amount</p>
                                        <?php echo $depositor_request["currency"] . " " .number_format($offer['maximum_amount']); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <p style="font-weight:bold;">Minimum amount</p>
                                        <?php echo $depositor_request["currency"] . " " .number_format($offer['minimum_amount']); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <p style="font-weight:bold;">Awarded Amount</p>
                                        <?php
                                        echo (isset($contract_data['offered_amount']) ? $depositor_request["currency"] ." ".number_format( $contract_data['offered_amount']) : "-");
                                        ?>
                                    </div>

                                </div>
                                <br>
                                <div class="row">

                                    <div class="col-sm-2">
                                        <br/>
                                        <p style="font-weight:bold;">Special Instructions</p>
                                        <?php echo $offer['special_instructions']; ?>
                                    </div>

                                    <div class="col-sm-4">
                                        <br/>
                                        <p style="font-weight:bold;">Product disclosure statement</p>
                                        <?php echo $offer['product_disclosure_url']; ?>
                                        <?php if (!empty($offer['product_disclosure_statement'])) { ?>
                                            <a href="<?php echo $offer['product_disclosure_statement']; ?>" target="_blank" class="btn btn-link"><?php echo str_replace("../uploads/","",$offer['product_disclosure_statement']); ?></a>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                            
                            <br><br>

                            <div style="padding-bottom:20px;margin-top:20px" class="row">
                                <div class="col-lg-11 col-md-11 col-sm-11">
                                    <div class="row" style="margin-left:10px">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <a href="contract.php" class="btn btn-block" style="border:1px solid gainsboro" style="color:white" >Back</a>
                                        </div>
                                        <?php
                                        if ($contract_data != "COMPLETED")
                                        {
                                            ?>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <a href="logic?app=<?php echo $contract_data["id"]; ?>" onclick="approve();" class="btn btn-primary mmy_btn btn-block">Complete</a>
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <a href="logic?cancel=<?php echo $contract_data["id"]; ?>" class="btn btn-block" style="border:1px solid gainsboro;">Retract</a>
                                            </div>
                                            <?php
                                        }else{

                                            $timezone = $depositor_demographic_data['timezone'];
                                            $bid_open = $depositor_request['closing_date_time'];

                                            $date_plus_7 = new DateTime( $bid_open, new DateTimeZone(Model::formattedTimezone($timezone)) );
                                            $date_plus_7->modify('+7 day');

                                            $date_now = new DateTime("now", new DateTimeZone(Model::formattedTimezone($timezone)) );
                                            ?>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <?php
                                                if($date_now <= $date_plus_7) {
                                                    ?>
                                                    <a href="logic?reject=<?php echo $contract_data["id"]; ?>" onclick="return reject();"
                                                       class="btn btn-primary mmy_btn btn-block">Retract award</a>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <a href="logic?abandon_contract=<?php echo $contract_data["id"]; ?>" class="btn btn-primary mmy_btn btn-block"
                                                       onclick="return abandon();">Abandon</a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <br /><br /><br />
                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3"></div>
                            </div>
						</div>
                       
				 <br>
				</div>
 
			</div>
			<!-- /content area -->
           <br> 
            </div>
 
			</div>
			<!-- /content area -->
            
            <!-- Modal -->


<?php
    require_once("footer.php");
        }else{
            echo "<script>location='p_cont.php'</script>";
        }
    }else {
        echo "<script>location='p_cont.php'</script>";
    }
}
?>
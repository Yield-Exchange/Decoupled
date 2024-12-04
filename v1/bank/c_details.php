<?php
session_start();
require_once "header.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";

    if (isset($_GET['rqid'])) {
        $id = Core::urlValueDecrypt($_GET['rqid']);
        $bid = Core::urlValueDecrypt($_GET['id']);

        $request = RequestsModel::getRequestByID($id,false,true);
        $offer = BankModel::getOfferByID($bid,true);

        if ( !empty($offer) && !empty($request) ){

            $depositor_data  = AuthModel::getUserDataByID($request['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($request['user_id']);
            $account_doc = DepositorModel::getDepositorDoc($request['user_id']);

            $bank_data = AuthModel::getUserDataByID($offer['invited_user_id']);
            $bank_demographic_data = AuthModel::getUserDemographicData($offer['invited_user_id']);

            Core::activityLog("Bank Active Deposits -> View");
            ?>
            <!-- Main content -->
            <div class="content-wrapper">
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
                <!-- Main charts -->
                <div class="row">
                    <div class="col-12">

                        <!-- Support tickets -->
                        <div class="card transparent-card">
                            <div class="col-12">
                                <?php
                                $user_data = AuthModel::getUserdata();
                                $user_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
                                $contract = BankModel::getBankBidContract($offer['id']);
                                ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body" style="padding: 1rem;min-height: 290px;">
                                                <div class="row justify-content-center">
                                                    <?php
                                                    if ( !empty($depositor_data['profile_pic']) ) {
                                                        ?>
                                                        <img src="../depositor/image/<?php echo $depositor_data['profile_pic']; ?>" width="80" height="80" alt=""/>
                                                        <?php
                                                    }else {
                                                        ?>
                                                        <div class="i-initial-inverse-big"><span><?php echo !empty($depositor_data['name'][0]) ? Core::render($depositor_data['name'][0]) : 'Y'?></span></div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/Depositor.png';?>" class="img-responsive mr-2" height="25"/> <?php echo Core::render($depositor_data['name']); ?></p>
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/City.png';?>" class="img-responsive mr-2" height="25"/><?php echo Core::render($depositor_demographic_data['city']); ?></p>
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/Province.png';?>" class="img-responsive mr-2" height="25"/><?php echo Core::render($depositor_demographic_data['province']); ?></p>
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/Email.png';?>" class="img-responsive mr-2" height="25"/><?php echo Core::render($depositor_data['email']); ?></p>
                                                    <p><img src="<?php echo BASE_URL.'/assets/images/icons/Telephone.png';?>" class="img-responsive mr-2" height="25"/><?php echo Core::render($depositor_demographic_data['telephone']);?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="card" style="min-height: 290px;">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 style="color:#2CADF5;font-weight:bold;">Deposit Summary</h5>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="row">
                                                            <div class="col-md-5"><p style="font-weight:bold;">Deposit ID</p></div>
                                                            <div class="col-md-7"><span style="font-weight:bold"><?php echo Core::render($contract["reference_no"]); ?></span></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-5"><p style="font-weight:bold;">Product</p></div>
                                                            <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                $product = RequestsModel::getProductByID($request["product_id"]);
                                                                echo !empty($product) ? Core::render($product['description']) : '';
                                                                ?>
                                                            </span>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ($request['term_length_type'] != "HISA"){
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-5"><p style="font-weight:bold;"><?php echo !empty($product) && trim(strtolower($product['description'])) =="notice deposit" ? 'Notice Period' : 'Lockout Period';?> </p></div>
                                                            <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                echo !empty($request['lockout_period_days']) && in_array(trim(strtolower($product['description'])),['notice deposit','cashable']) ? Core::render($request['lockout_period_days']).' days' : '-';
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
                                                                echo Core::render($request["currency"]) ." ".number_format($contract['offered_amount']);
                                                                ?>
                                                            </span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-5"><p style="font-weight:bold;">Compounding Frequency</p></div>
                                                            <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php echo Core::render($request['compound_frequency']); ?>
                                                            </span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-5"><p style="font-weight:bold;">Special Instructions (Request)</p></div>
                                                            <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                echo Core::render($request["special_instructions"]);
                                                                ?>
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-5"><p style="font-weight:bold;">GIC Number</p></div>
                                                            <div class="col-md-7">
                                                                <span style="font-weight:bold">
                                                                    <?php echo empty($contract['gic_number']) ? '-': Core::render($contract['gic_number']); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ( $request['term_length_type'] == "HISA" ) {
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
                                                        if ($request['term_length_type'] != "HISA"){
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-5"><p style="font-weight:bold;">Term Length</p></div>
                                                            <div class="col-md-7">
                                                            <span style="font-weight:bold">
                                                                <?php
                                                                     echo Core::render($request['term_length'] . ' ' . strtolower($request['term_length_type']));
                                                                 ?>
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

                                <br/>
                                <div class="row">
                                    <div class="col-12">
                                        <a style="background-color:white;color:grey;border:1px solid grey" href="<?php echo isset($_GET['history']) ? 'history' : 'comp'?>" class="btn btn-md">Go Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                    </div>
                </div>
            </div>
            <!-- /content area -->

            <!-- Modal -->
            <?php
            require_once "footer.php";
        }else{
            echo "<script>location='comp'</script>";
        }
    }else {
        echo "<script>location='comp'</script>";
    }
}
?>
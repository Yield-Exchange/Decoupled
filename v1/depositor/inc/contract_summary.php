<?php
    $depositor_demographic_data = AuthModel::getUserDemographicData($data['user_id']);
    $invited_user_id = db::getCell("SELECT invited_user_id FROM invited WHERE id='".$offer['invitation_id']."' ");
    $bank_demographic_data = AuthModel::getUserDemographicData($invited_user_id);
    $contract = BankModel::getBankBidContract($offer['id']);
?>

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
                        $product = RequestsModel::getProductByID($data["product_id"]);
                        echo !empty($product) ? Core::render($product['description']) : '';
                    ?>
                </span>
            </div>
        </div>
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
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">GIC Amount (Awarded Amount)</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php
                        echo !in_array($contract['offered_amount'],['WITHDRAWN']) ? Core::render($data["currency"]) ." ".number_format($contract['offered_amount']) : 0;
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Interest Rate</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo BankModel::getInterest($offer['interest_rate_offer']); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Term Length</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php
                    if ($data['term_length_type'] == "HISA"){
                        echo '-';
                    }else {
                        echo Core::render($data['term_length'] . ' ' . strtolower($data['term_length_type']));
                    }
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
//                            if ($data['term_length_type'] == "MONTHS") {
//                                $date->modify(" +" . $data['term_length'] . ' month');
//                            } else {
//                                $date->modify(" +" . $data['term_length'] . ' day');
//                            }
                            echo $date->format("Y-m-d");
                        }else{
                            echo '-';
                        }
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
    </div>
</div>
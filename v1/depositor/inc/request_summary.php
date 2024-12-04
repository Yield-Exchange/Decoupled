<?php
    $depositor_demographic_data = AuthModel::getUserDemographicData($data['user_id']);
?>

<div class="row">
    <div class="col-12">
        <h5 style="color:#2CADF5;font-weight:bold;">Request Summary</h5>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Request Id</p></div>
            <div class="col-md-7"><span style="font-weight:bold"><?php echo Core::render($data["reference_no"]); ?></span></div>
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
            <div class="col-md-5"><p style="font-weight:bold;">Requested Amount</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php
                        $amount = $data["amount"];
                        echo Core::render($data["currency"]) . " " . number_format($amount);
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
                    <?php
                    if ($data['term_length_type'] == "HISA"){
                        echo '-';
                    }else {
                        echo Core::render($data['term_length']) . ' ' . Core::render(ucwords(strtolower($data['term_length_type'])));
                    }
                    ?>
                </span>
            </div>
        </div>
        <?php
        }
        ?>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Closing Date & Time</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php
                        echo Model::dateTimeFromUTC('Y-m-d h:i a', $data['closing_date_time'],$depositor_demographic_data['timezone']);
                    ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Ask Rate</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php
                        echo BankModel::getInterest($data['requested_rate']);
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Date of deposit (approx)</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo Model::dateTimeFromUTC('Y-m-d',$data['date_of_deposit'],$depositor_demographic_data['timezone']); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Compounding frequency</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo Core::render($data['compound_frequency']); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Short Term DBRS Rating</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo Core::render($data['requested_short_term_credit_rating']); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Deposit Insurance</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo Core::render($data['requested_deposit_insurance']); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Special Instructions</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo Core::render($data['special_instructions']); ?>
                </span>
            </div>
        </div>
        <?php
        if (!empty($contract['gic_start_date'])){
        ?>
            <div class="row">
                <div class="col-md-5"><p style="font-weight:bold;">Fund Received Date</p></div>
                <div class="col-md-7">
                    <span style="font-weight:bold">
                        <?php
                            echo Model::dateTimeFromUTC('Y-m-d h:i a',$contract['deposit_date'],$depositor_demographic_data['timezone']);
                        ?>
                    </span>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
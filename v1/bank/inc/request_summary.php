<div class="row">
    <div class="col-md-12">
        <h5 style="color:#2CADF5;font-weight:bold;">Request Summary</h5>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Request Id</p></div>
            <div class="col-md-7"><span style="font-weight:bold"> <?php echo Core::render($request["reference_no"]); ?></span></div>
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
            <div class="col-md-5"><p style="font-weight:bold;"><?php echo !empty($product) && trim(strtolower($product['description'])) =="notice deposit" ? 'Notice Period' : 'Lockout Period';?></p></div>
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
            <div class="col-md-5"><p style="font-weight:bold;">Requested Amount</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                   <?php $amount = $request["amount"];
                        echo Core::render($request["currency"]) . " " . number_format($amount);?>
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
                    if ($request['term_length_type'] == "HISA"){
                        echo '-';
                    }else {
                        echo Core::render($request['term_length'] . ' ' . ucwords(strtolower($request['term_length_type'])));
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
                        $timezone = $bank_demographic_data['timezone'];
                        echo Model::dateTimeFromUTC('Y-m-d h:i a',$request['closing_date_time'],$timezone);
                    ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Ask Rate</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php
                        echo BankModel::getInterest($request['requested_rate']);
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Date of deposit (approx)</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo Model::dateTimeFromUTC('Y-m-d',$request['date_of_deposit'],$timezone); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Compounding frequency</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo Core::render($request['compound_frequency']); ?>
                </span>
            </div>
        </div>
         <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Short Term DBRS Rating</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                   <?php echo Core::render($request['requested_short_term_credit_rating']); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Deposit Insurance</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo Core::render($request['requested_deposit_insurance']); ?>
                </span>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-5"><p style="font-weight:bold;">Special Instructions</p></div>
            <div class="col-md-7">
                <span style="font-weight:bold">
                    <?php echo Core::render($request['special_instructions']); ?>
                </span>
            </div>
        </div>
    </div>
</div>
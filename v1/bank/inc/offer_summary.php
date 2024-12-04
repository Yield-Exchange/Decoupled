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
                    <div class="col-md-12">
                        <h5 style="color:#2CADF5;font-weight:bold;">Offer Summary</h5>
                    </div>
                    <div class="row col-md-12">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-6"><p style="font-weight:bold;">Minimum amount</p></div>
                                <div class="col-md-6">
                                    <span style="font-weight:bold">
                                       <?php echo Core::render($request["currency"]) . " " .number_format($offer['minimum_amount']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><p style="font-weight:bold;">Maximum amount</p></div>
                                <div class="col-md-6">
                                    <span style="font-weight:bold">
                                        <?php echo Core::render($request["currency"]) . " " .number_format($offer['maximum_amount']); ?>
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
                                <div class="col-md-6"><p style="font-weight:bold;">Rate Held Until :</p></div>
                                <div class="col-md-6">
                                    <span style="font-weight:bold">
                                       <?php
                                           $timezone = $user_demographic_user_data['timezone'];
                                           echo Model::dateTimeFromUTC('Y-m-d h:i a', $offer['rate_held_until'],$timezone);
                                       ?>
                                    </span>
                                </div>
                            </div>
                            <?php
                            if (isset($contract['offered_amount'])){
                            ?>
                                <div class="row">
                                    <div class="col-md-6"><p style="font-weight:bold;">Awarded Amount</p></div>
                                    <div class="col-md-6">
                                        <span style="font-weight:bold">
                                           <?php
                                                echo !in_array($contract['status'],['WITHDRAWN']) ? Core::render($request["currency"]) ." ".number_format($contract['offered_amount']) : 0;
//                                                echo Core::render($request["currency"]) ." ".number_format( $contract['offered_amount']);
                                           ?>
                                        </span>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-6"><p style="font-weight:bold;">Product disclosure statement</p></div>
                                <div class="col-md-6">
                                    <span style="font-weight:bold">
                                      <?php echo filter_var($offer['product_disclosure_url'], FILTER_VALIDATE_URL) ? "<a target='_blank' href='".$offer['product_disclosure_url']."' data-toggle='tooltip' title='".$offer['product_disclosure_url']."'><i class='fa fa-eye'></i> Visit the link</a>" : $offer['product_disclosure_url']; ?>
                                        <?php if (!empty($offer['product_disclosure_statement'])) {?>
                                            <a href="<?php echo $offer['product_disclosure_statement']; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                                        <?php }?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="offer-interest-rate-rounded-container">
                                <h2 style="font-weight: bold">
                                    <?php
                                    if ($offer['rate_type']!='VARIABLE'){
                                        echo BankModel::getInterest($offer['interest_rate_offer']);
                                    }else {
                                        echo BankModel::getInterest($offer['prime_rate'], true,$offer['rate_operator'],true);
                                    }
                                    ?>
                                </h2>
                                <p>Interest Rate</p>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="col-md-3"><p style="font-weight:bold;">Special Instructions</p></div>
                        <div class="col-md-6">
                            <span style="font-weight:bold">
                              <?php echo Core::render($offer['special_instructions']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
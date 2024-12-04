<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";

    Core::activityLog("Depositor Deposit Details");
    if ( isset($_GET['cnid']) ) {
        $id = Core::urlValueDecrypt($_GET['cnid']);
        $contract_data = DepositorModel::getContractByID($id,true);

        if ( !empty($contract_data) ){
            $depositor_request = RequestsModel::getRequestByID($contract_data['depositor_request_id']);
            $depositor_data = AuthModel::getUserDataByID($contract_data['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($contract_data['user_id']);
            $account_doc = DepositorModel::getDepositorDoc($contract_data['user_id']);

            $data = $depositor_request;

            $bank = AuthModel::getUserDataByID($contract_data['invited_user_id']);
            $bank_demographic_data = AuthModel::getUserDemographicData($contract_data['invited_user_id']);
            $offer = BankModel::getOfferByID($contract_data['offer_id']);
            $credit_ratings = Core::getRatings($contract_data['invited_user_id']);
            $utc_time_now = Model::utcDateTime();
?>

            <link rel="stylesheet" href="<?php echo BASE_URL.'/assets/';?>css/jQuery.countdown.css">
            <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oswald">
            <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.min.js"></script>
            <script src="<?php echo BASE_URL.'/assets/';?>js/jquery.countdown.min.js"></script>
            <style>
                .main-timer-container .time{
                    height: 30px!important;
                    width: 30px!important;
                    font-size: 10.4px!important;
                }
                .main-timer-container .label{
                    top: -22px!important;
                }
                .main-timer-container .count.top{
                    border-bottom: none!important;
                }
                .main-timer-container .count.bottom{
                    border-top: none!important;
                }
            </style>

		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			<div class="content">

                <!-- Main charts -->
				<div class="row">
					<div class="col-12">
                        <!-- Support tickets -->
						<div class="card transparent-card">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr class="table-active table-border-double">
                                            <td colspan="3" class="my_h"><span class="b_b">REVIEW</span> OFFER</td>
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
                                                        <script type="text/template" id="countdown-timer-template">
                                                            <div class="time <%= label %>">
                                                                <span class="label"><%= label.length < 6 ? label : label.substr(0, 3)  %></span>
                                                                <span class="count curr top"><%= curr %></span>
                                                                <span class="count next top"><%= next %></span>
                                                                <span class="count next bottom"><%= next %></span>
                                                                <span class="count curr bottom"><%= curr %></span>
                                                            </div>
                                                        </script>

                                                        <div class="col-md-12">
                                                            <div class="row rate-held-container" data-toggle="tooltip" data-placement="top" title="Rate held till" style="height: 40px">
                                                                <div class="col-sm-5">
                                                                    <p style="margin-top: 10px">Rate Held Until:</p>
                                                                </div>
                                                                <div class="col-sm-7 main-timer-container">
                                                                    <div class="countdown-container" id="rate-held-till"></div>
                                                                </div>
                                                            </div>
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
                                                                <?php
                                                                    echo Core::render($data['term_length'] . ' ' . strtolower($data['term_length_type']));
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

                            <br /><br />
                          <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <a href="contract" class="btn btn-lg" style="border:1px solid gainsboro;margin-right: 3%">Back</a>
                                <?php
                                    $timezone = $depositor_demographic_data['timezone'];
                                    $bid_open = $depositor_request['closing_date_time'];
                                ?>
                                    <a href="logic?reject=<?php echo Core::urlValueEncrypt($contract_data["id"]); ?>" onclick="return reject();" class="btn btn-primary mmy_btn btn-lg pull-right">Withdraw Award</a>
                                    <a href="msgs?id=<?php echo Core::urlValueEncrypt($offer['invited_user_id']); ?>&c_id=<?php echo Core::urlValueEncrypt($contract_data['id']); ?>" class="btn btn-primary mmy_btn btn-lg pull-right" style="margin-right: 3%">Chat</a>
                                </div>
                            </div>
                          </div>
						</div>
				</div>
			</div>
			<!-- /content area -->
        </div>
        <!-- Modal -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
        <script type="text/javascript">
            let offerExpiry = moment.utc(<?php echo json_encode(Core::formattedDateTime($offer['rate_held_until'],true))?>).local().format("YYYY-MM-DD HH:mm:ss");
        </script>
<script>
    function approve(){
        return(confirm("Do you want to Approve?"));
    }

    function reject(){
        return(confirm("Do you want to retract the awarded contract?"));
    }

    $(window).on('load', function() {
        let labels = ['days', 'hours', 'minutes', 'seconds'],
            template = _.template($('#countdown-timer-template').html()),
            currDate = '00:00:00:00',
            nextDate = '00:00:00:00',
            parser = /([0-9]{2})/gi,
            $rateHeldTill = $('#rate-held-till');

        // Parse countdown string to an object
        function strfobj(str) {
            let parsed = str.match(parser),
                obj = {};
            labels.forEach(function(label, i) {
                obj[label] = parsed[i]
            });
            return obj;
        }
        // Return the time components that diffs
        function diff(obj1, obj2) {
            let diff = [];
            labels.forEach(function(key) {
                if (obj1[key] !== obj2[key]) {
                    diff.push(key);
                }
            });
            return diff;
        }
        // Build the layout
        let initData = strfobj(currDate);
        labels.forEach(function(label, i) {
            $rateHeldTill.append(template({
                curr: initData[label],
                next: initData[label],
                label: label
            }));
        });

        // Starts the countdown for offer expiry date & time
        console.log("offerExpiry",offerExpiry);
        $rateHeldTill.countdown(offerExpiry, {defer: false})
            .on('finish.countdown', function (event) {
                $(".rate-held-container").find(".main-timer-container").find(".count").css('background','#ccc');
            }).on('update.countdown', function(event) {

            if ( parseInt(event.strftime("%D")) < 1 && parseInt(event.strftime("%H")) < 1 ){
                $(".rate-held-container").find(".main-timer-container").find(".count").css('background','#29AB87');
            }

            let newDate = event.strftime('%D:%H:%M:%S'),
                data;
            if (newDate !== nextDate) {
                currDate = nextDate;
                nextDate = newDate;
                // Setup the data
                data = {
                    'curr': strfobj(currDate),
                    'next': strfobj(nextDate)
                };
                // Apply the new values to each node that changed
                diff(data.curr, data.next).forEach(function(label) {
                    let selector = '.%s'.replace(/%s/, label),
                        $node = $rateHeldTill.find(selector);
                    // Update the node
                    $node.removeClass('flip');
                    $node.find('.curr').text(data.curr[label]);
                    $node.find('.next').text(data.next[label]);
                    // Wait for a repaint to then flip
                    _.delay(function($node) {
                        $node.addClass('flip');
                    }, 50, $node);
                });
            }
        }).countdown('start');
    });
</script>

<?php
require_once "footer.php";
        }else{
            echo "<script>location='contract'</script>";
        }
    }else{
        echo "<script>location='contract'</script>";
    }
}
?>
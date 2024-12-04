<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

$token = AuthModel::generateToken();
if ( AuthModel::isLoggedIn() ) {

    require_once "sidebar.php";
    if (isset($_GET['cnid'])) {
        $id = Core::urlValueDecrypt($_GET['cnid']);

        $contract_data = BankModel::getContractByID($id,true);
        if ( !empty($contract_data) ){
            $request = RequestsModel::getRequestByID($contract_data['depositor_request_id'],false,true);
            $depositor_data = AuthModel::getUserDataByID($request['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($request['user_id']);
            $account_doc = DepositorModel::getDepositorDoc($request['user_id']);

            $bank_data = AuthModel::getUserDataByID($contract_data['invited_user_id']);
            $bank_demographic_data = AuthModel::getUserDemographicData($contract_data['invited_user_id']);

            $offer = BankModel::getOfferByID($contract_data['offer_id']);
            
            Core::activityLog("Bank Pending Deposits -> View");
    ?>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>

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
         p{
             color:grey;
             font-size: 13px;
         }
         .table-responsive{
             padding-left:0px;
         }
         .xdsoft_datetimepicker{
             z-index: 9999999!important;
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
                                            <?php
                                                $contract = $contract_data;
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
                                                                    <p style="margin-top: 10px">Rate held till:</p>
                                                                </div>
                                                                <div class="col-sm-7 main-timer-container">
                                                                    <div class="countdown-container" id="rate-held-till"></div>
                                                                </div>
                                                            </div>
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
                                                                    echo Core::render($request['term_length'].' '.strtolower($request['term_length_type']));
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
                                    <a style="background-color:white;color:grey;border:1px solid grey;margin-right: 3%" href="contracts" class="btn btn-md">Go Back</a>

                                    <?php
                                    if ( empty($contract_data['gic_start_date']) ) {
                                        ?> <button type="button" class="btn btn-primary mmy_btn pull-right" data-toggle="modal" data-target="#update">Create <?php echo ( !empty($product) && strpos($product['description'], 'High Interest Savings') !== false ) ? 'HISA' : 'GIC' ?></button>
                                        <?php
                                    }
                                    ?>
                                    <a href="msgs?id=<?php echo Core::urlValueEncrypt($offer['invited_user_id']); ?>&c_id=<?php echo Core::urlValueEncrypt($contract_data['id']); ?>" class="btn btn-primary mmy_btn btn-lg pull-right" style="margin-right: 3%">Chat</a>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>

      <!-- Modal -->
        <div id="update" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
              </div>
              <div class="modal-body">
                 <form action="logic" method="post" autocomplete="off">
                   <div class="row">
                     <div class="col-md-2"></div>
                          <div class="col-md-9">
                              <h3><?php echo ( !empty($product) && strpos($product['description'], 'High Interest Savings') !== false ) ? 'HISA' : 'GIC' ?> Start Date</h3>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                          <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                           <input style="width:160px" type="text" id="txtDate5" name="dop" placeholder="Date"  class="form-control date_picker" style="border-bottom-left-radius:20px;border-top-left-radius:20px;" required>
                                           <input type="hidden" id='demtimezone' value="<?php echo $bank_demographic_data['timezone']; ?>" >
                                            <input type="hidden" id='tlength' class='termlength' value="<?php echo $request['term_length']; ?>" >
                                            <input type="hidden" id='tlengthtype' class='termlengthtype' value="<?php echo $request['term_length_type']; ?>" >
                                            <input type="hidden" id='offerexpdate' class='offerexpirydate' value="<?php echo Model::dateTimeFromUTC('Y-m-d h:i a',$request['closing_date_time'],$depositor_demographic_data['timezone']); ?>" >
                                        </span>
                                    </div>
                                </div>
                                <h3><?php echo ( !empty($product) && strpos($product['description'], 'High Interest Savings') !== false )  ? 'HISA' : 'GIC' ?> Number</h3>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                           <input style="width:160px" type="text" id="gicno" maxlength="30" name="gicnumber" placeholder="<?php echo ( !empty($product) && strpos($product['description'], 'High Interest Savings') !== false ) ? 'HISA' : 'GIC' ?> number"  class="form-control" required>
                                        </span>
                                    </div>
                                </div>
                                <div id='mdate1' class="maturitydate1">
                                
                                    <h3>Maturity Date</h3>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <input style="width:160px" type="text" id="maturitydate" name="mdate" placeholder="Maturity Date"  class="form-control date_picker2">
                                                <input name="rqid" type="hidden" value="<?php echo $request["id"]; ?>" >
                                                <input name="cntid" type="hidden" value="<?php echo $id; ?>" >
                                            </span>
                                        </div>
                                        <br />
       
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="update_date" class="btn btn-primary mmy_btn" value="Confirm" style="margin-top:10px"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                </div>
                </form>
              </div>
              <div class="modal-footer"></div>
            </div>
          </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
        <script type="text/javascript">
            let offerExpiry = moment.utc(<?php echo json_encode(Core::formattedDateTime($offer['rate_held_until'],true))?>).local().format("YYYY-MM-DD HH:mm:ss");
        </script>
        <script src="<?php echo BASE_URL.'/assets/';?>js/moment-timezone.js"></script>
        <script type="text/javascript">
                let format = 'YYYY/MM/DD HH:mm:ss ZZ';
                let timeZone = <?php echo json_encode(Model::formattedTimezone($bank_demographic_data['timezone']));?>;
                let todayDateWithUserTimezone = moment(moment().toISOString(), format).tz(timeZone);
        </script>
    <script>
		function a(){
			return(confirm("Do you want to Confirm?"));
        }
		function b(){
			return(confirm("Do you want to Cancel?"));
        }
        const dateToday = new Date();
        $(document).ready(function(){
            let minDate=moment($(".offerexpirydate").val());
            // let minDate = ofrexpdate.add(24,"hours");
            let maxDate = todayDateWithUserTimezone;
            if(minDate > maxDate){
                maxDate=minDate;
            }
            $('.date_picker').datetimepicker({
                minDate:minDate.format("YYYY/MM/DD"),
                maxDate:maxDate.format("YYYY/MM/DD"),
                timepicker: false,
                format: 'Y-m-d',
                validateOnBlur : true,              
                onChangeDateTime: function(dp, $input) {
                    var date = moment($input.val());
                    var date_;
                    var _date;

                    var termlength = $('.termlength').val();
                    var termlengthtype = $('.termlengthtype').val();

                    // When in pending contracts for FI and you hit GIC the maturity date picker is greyed out until the full maturity. Can we make the days 7 days before the real maturity date available to select as well.
                    if(termlengthtype==="MONTHS"){
                        date.add( parseInt(termlength),"months" );
                        date_ = date.clone().subtract(7,"days");
                    }else if (termlengthtype==="HISA") {
                        $('.maturitydate1').hide();
                        return;
                    }else{
                        date.add( parseInt(termlength),"days");
                        if ( termlength > 7) {
                            date_ = date.clone().subtract(7, "days");
                        }else{
                            date_ = moment($input.val());
                        }
                    }
                    $('.maturitydate1').show();
                    _date = date.clone().add(7, "days");

                    $('.date_picker2').val(date.format("YYYY/MM/DD"));
                    $('.date_picker2').datetimepicker({
                        maxDate:_date.format("YYYY/MM/DD"),
                        minDate:date_.format("YYYY/MM/DD"),
                        timepicker: false,
                        format: 'Y-m-d',
                        validateOnBlur : true,
                    }); 
                    // $('.date_picker2').val(date.format("Y-MM-DD"));
                }
            });
             $('.maturitydate1').hide();
        });

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
            echo "<script>location='contracts'</script>";
      }
    }else{
        echo "<script>location='contracts'</script>";
    }
}
?>
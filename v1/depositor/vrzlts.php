<?php
session_start();
require_once "header.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";

    $token = AuthModel::generateToken();

    $utc_date_now = Model::utcDate();
    $utc_time_now = Model::utcDateTime();

    Core::activityLog("Depositor Review Offers -> Action View Offers");

    if ( isset($_GET["rqid"]) ) {
        $req_id = Core::urlValueDecrypt($_GET["rqid"]);

        $data = RequestsModel::getRequestByID($req_id,true);
        if ( !empty($data) ){
            $depositor = AuthModel::getUserDataByID($data['user_id']);
            $depositor_demographic_data = AuthModel::getUserDemographicData($data['user_id']);
            $bank_demographic_data = AuthModel::getUserDemographicData($data['user_id']);
            $offers = BankModel::getOffersByRequestID($req_id);
?>

<link rel="stylesheet" href="<?php echo BASE_URL.'/assets/';?>css/jQuery.countdown.css">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oswald">
<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.min.js"></script>
<script src="<?php echo BASE_URL.'/assets/';?>js/jquery.countdown.min.js"></script>
<script>
    $(document).ready(function() {
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    });

    $(document).ready(function () {
        $('.dataTables_length').addClass('bs-select');
    });
</script>

<style>
    .btn-outline-secondary-custom{
        background: #80EC67ED!important;
    }
    .btn-no-action-custom{
        background: #ccc;
    }
    .custom-data-tables > tbody > tr > td{
        padding: 5px!important;
    }
    .custom-data-tables > tbody > tr > td:last-child{
        padding-top: 15px!important;
    }
    .custom-data-tables > tbody > tr > td:last-child > ._error{
        width: 100%!important;text-align: center!important;padding-top: 5px!important;color: red!important;
        font-size: 13px!important;
    }
</style>

        <script type="text/template" id="countdown-timer-template">
            <div class="time <%= label %>">
                <span class="count curr top"><%= curr %></span>
                <span class="count next top"><%= next %></span>
                <span class="count next bottom"><%= next %></span>
                <span class="count curr bottom"><%= curr %></span>
                <span class="label"><%= label.length < 6 ? label : label.substr(0, 3)  %></span>
            </div>
        </script>
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Content area -->
            <div class="content">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card" style="padding: 2%">
                            <div class="row" style="padding: 1%">
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <span class="b_b">ALL</span> OFFERS
                                            <button onclick="window.open('review_offers_print?rqid=<?php echo $_GET["rqid"]; ?>', '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');" class="btn btn-primary">Print</button>
                                            <br/>
                                        </div>
                                        <div class="col-sm-6">
                                            <h6 style="width: 100%;"><strong>Product &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong>
                                                <span class="pull-right" style="padding-right: 5%;">
                                                <?php
                                                    $product = RequestsModel::getProductByID($data["product_id"]);
                                                    echo !empty($product) ? Core::render($product['description']) : '';
                                                ?>
                                                </span>
                                            </h6>
                                            <?php
                                            if ($data['term_length_type'] != "HISA"){
                                            ?>
                                            <h6 style="width: 100%;"><strong>Term &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong> <span class="pull-right" style="padding-right: 5%;">
                                                    <?php
                                                        echo Core::render($data['term_length'] . ' ' . ucwords(strtolower($data['term_length_type'])));
                                                    ?>
                                                </span></h6>
                                            <?php
                                            }
                                            ?>
                                            <h6 style="width: 100%;"> <strong>Requested Amount &nbsp; :</strong> <span class="pull-right" style="padding-right: 5%;"> <?php echo Core::render($data['currency'])." ".number_format(str_replace(",","",$data['amount']));?></span></h6>
                                            <h6 style="width: 100%;"><strong>Awarded Amount &nbsp;&nbsp;&nbsp;&nbsp; :</strong> <span id="total_offered" style="padding-right: 5%;" class="pull-right"></span></h6>
                                            <h6 style="width: 100%;"><strong>Wtd. Avg. Interest &nbsp;&nbsp;&nbsp;&nbsp; :</strong> <span id="interest_rate" style="padding-right: 5%;" class="pull-right"></span></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="row select-offer-container" data-toggle="tooltip" data-placement="top" title="Please wait until all parties have had a chance to submit their offer">
                                        <div class="col-sm-4">
                                            <p>Select Offers In:</p>
                                        </div>
                                        <div class="col-sm-8 main-timer-container">
                                            <div class="countdown-container" id="select-offers-in"></div>
                                        </div>
                                    </div>
                                    <div class="row request-expiry-container" style="margin-top:20px;" data-toggle="tooltip" data-placement="top" title="If no offers are selected your request will expire">
                                        <div class="col-sm-4">
                                            <p>Request Expires In:</p>
                                        </div>
                                        <div class="col-sm-8 main-timer-container">
                                            <div class="countdown-container" id="request-expires-in"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table custom-data-tables table-condensed" id="offer-table">
                                            <thead>
                                            <tr>
                                                <th>Institution</th>
                                                <th>Interest Rate %</th>
                                                <th>Min Amount</th>
                                                <th>Max Amount</th>
                                                <th>Action</th>
                                                <th>Awarded Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (in_array($data['request_status'],['ACTIVE','PENDING_DEPOSIT'])){
                ?>
                    <div class="row">
                        <div class="col-12 text-right mr-5">
                            <a href="bids" class="btn btn-lg btn-outline-secondary">Cancel</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-lg btn-primary" id="confirmButton" disabled onclick="selectButton();">Confirm</button>
                            <form id="form_offers" action="logic" method="post">
                                <input type="hidden" name="submitted_offers_data"/>
                                <input type="hidden" name="rqid" value="<?php echo $req_id; ?>"/>
                            </form>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
        <script type="text/javascript">
            let is_already_open = <?php echo $data['closing_date_time'] < $utc_time_now ? "true": "false";?>;
            let is_already_expired = <?php echo $utc_time_now > $data['date_of_deposit'] ? "true": "false";?>;
            let req_id= <?php echo $data['id'];?>;
            let closingDate = moment.utc(<?php echo json_encode($data['closing_date_time']);?>).local().format("YYYY/MM/DD HH:mm:ss");
            let requestExpiry = moment.utc(<?php echo json_encode($data['date_of_deposit']);?>).local().format("YYYY/MM/DD HH:mm:ss");
            let currency = <?php echo json_encode($data['currency']);?>;
            let deposit_amount = <?php echo $data['amount'];?>;
            let token = <?php echo json_encode($token);?>;
        </script>
        <script src="js/review_offers.js?v=2.3"></script>
<?php
        }else{
            echo "<script>location='bids'</script>";
        }
    }else{
        echo "<script>location='bids'</script>";
    }
}
?>
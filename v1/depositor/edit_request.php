<?php
session_start();
require_once "header.php";
require_once "sidebar.php";
require_once __DIR__."/../config/RequestsModel.php";

if (isset($_GET["id"])) {
    $id = Core::urlValueDecrypt($_GET["id"]);
    $data = RequestsModel::getRequestByID($id,true);
    $user_demographic_data = AuthModel::getUserDemographicData($data['user_id']);
    Core::activityLog("Depositor Review Offers -> Edit Request");
    $total_bids = db::getcell("select count(*) as total from offers o, invited i where i.id=o.invitation_id AND i.depositor_request_id='".$id."'");
    if ($total_bids > 0){
        Core::alert("An error occurred","An error occurred, you can not edit this request. Already have offers!","error","bids",true);
        return;
    }

    $token = AuthModel::generateToken();
    if( !empty($data) ){
        $this_product_name = RequestsModel::getProductByID(!empty($_SESSION["val2"]) ? $_SESSION["val2"] : $data['product_id']);
    ?>
        <link rel="stylesheet" href="<?php echo BASE_URL;?>/assets/css/croppie.css" />
        <script src="<?php echo BASE_URL;?>/assets/js/croppie.js"></script>

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
        <script src="<?php echo BASE_URL.'/assets/';?>js/moment-timezone.js"></script>
        <script type="text/javascript">
            let format = 'YYYY/MM/DD HH:mm:ss ZZ';
            let timeZone = <?php echo json_encode(Model::formattedTimezone($user_demographic_data['timezone']));?>;
            let is_product_high_interest=<?php echo ( !empty($this_product_name) && strpos($this_product_name['description'], 'High Interest Savings') !== false ) ? 1 : 0;?>;
            $(document).ready(function () {

                $lockout_period_container = $(".lockout-period-container");
                $term_length_container=$(".term-length-container");
                $lockout_period=$(".lockout_period");
                $term_type=$(".term_type");
                $term_length=$(".term_length");
                if ( is_product_high_interest ){
                    $term_length_container.hide();
                    $lockout_period_container.hide();
                    $lockout_period.removeAttr("required");
                    $term_type.removeAttr("required");
                    $term_length.removeAttr("required");
                }

            });
        </script>
<!--        <script src="--><?php //echo BASE_URL;?><!--/assets/js/jquery.dirty.js"></script>-->
        <script src="js/post_request.js?v=2.0"></script>
        <style>
            .tooltip-inner{
                background: transparent!important;
            }
            .tooltip-inner > img{
                width: 400px!important;
            }
            .myinput{
                border-radius: 0px;
            }
            label{
                color:grey;
            }
            #hover-content {
                display:none;
            }
            #parent:hover #hover-content {
                display:block;
                position:absolute;
            }
            .tooltip { pointer-events: none; }
        </style>
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Content area -->
            <div class="content">
                <!-- Main charts -->
                <div class="row">
                    <div class="col-xl-12">
                        <!-- Traffic sources -->
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr class="table-active table-border-double">
                                            <td colspan="3" style="padding-left:40px" class="my_h"><span  class="b_b">EDIT</span> REQUEST</td>
                                            <td class="text-right"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-header header-elements-inline">
                                <div class="card-body">
                                    <form action="logic" autocomplete="off" method="post" id="myform" class="validater_form post_request_form">
                                        <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                        <div class="row">
                                            <div class="col-md-5 col-lg-5">
                                                <div class="form-group row">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                        <label style="font-weight:bold;">Product <span style="color:red">*</span> </label>
                                                        <div class="form-group">
                                                            <select name="product" class="form-control myinput productos" required>
                                                                <?php
                                                                $products = RequestsModel::getProducts();
                                                                if (!empty($products)){
                                                                    foreach ($products as $product){
                                                                ?>
                                                                    <option <?php echo ($product['id'] == ( !empty($_SESSION["val2"]) ? $_SESSION["val2"] : $data['product_id']) ) ? "selected" : ""; ?> value="<?php echo $product['id'];?>"><?php echo $product['description'];?></option>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row lockout-period-container">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8" >
                                                        <label class="period-label" style="font-weight:bold;">Lockout period (days)</label>
                                                        <div class="form-group">
                                                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                                            <input type="hidden" name="deposit_refrence" value="<?php echo Core::render($data['reference_no']); ?>"/>
                                                            <input type="number" name="lockout_period" <?php echo ( !empty($this_product_name) && strpos($this_product_name['description'], 'Cashable') !== false) ? "" : "disabled"; ?> value="<?php echo (!empty($this_product_name) && strpos($this_product_name['description'], 'Cashable') !== false) ? $data["lockout_period_days"] : ''; ?>" class="form-control lockout_period" placeholder="Enter Lockout Period" oninput="this.value = Math.abs(this.value)"  min="1"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8" >
                                                        <label style="font-weight:bold;">Deposit Amount <span style="color:red">*</span> </label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span>
                                                                     <select class="form-control" name="deposit_currency" required>
                                                                      <option value="<?php echo !empty($_SESSION["val3"]) ? Core::render($_SESSION["val3"]) : Core::render($data["currency"]); ?>"><?php echo Core::render($data["currency"]); ?></option>
                                                                      <option value="CAD">CAD</option>
                                                                      <option value="USD">USD</option>
                                                                    </select>
                                                                </span>
                                                            </div>

                                                            <input type="text" placeholder="Deposit Amount" name="deposit_amount" id="dep_amm" class="form-control dep_amm myinput" onchange="addThousands(this);" value="<?php echo !empty($_SESSION["val4"]) ? $_SESSION["val4"] : number_format($data["amount"]); ?>" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group row term-length-container">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Term length<span style="color:red">*</span> </label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span>
                                                                        
                                                                        <select class="form-control term_type" name="term_type" required>
                                                                            <option <?php echo !empty($_SESSION["term_type"]) ? (strtoupper($_SESSION["term_type"])  == 'MONTHS') ? "selected" : "" : strtoupper($data["term_length_type"]) == 'MONTHS' ? "selected" : ""; ?> value="MONTHS">Months</option>
                                                                            <option <?php echo !empty($_SESSION["term_type"]) ? (strtoupper($_SESSION["term_type"])  == 'DAYS') ? "selected" : "" : strtoupper($data["term_length_type"]) == 'DAYS' ? "selected" : ""; ?> value="DAYS">Days</option>
                                                                        </select>
                                                                    </span>
                                                                </div>
                                                                <input type="number" min="0" placeholder="Term length" name="month" value="<?php echo !empty($_SESSION["val8"]) ? Core::render($_SESSION["val8"]) : Core::render($data["term_length"]); ?>" max="8000" class="form-control term_length myinput" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Closing Date &amp; Time <span style="color:red">*</span> </label>
                                                            <input id="offer_closing_date" type="text" name="closing" class="form-control myinput datetimepicker" value="<?php echo !empty($_SESSION["val11"]) ? $_SESSION["val11"] : Model::dateTimeFromUTC('Y-m-d H:i', $data["closing_date_time"]); ?>" required/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Date of deposit (approx) <span style="color:red">*</span></label>
                                                            <input type="text" id="offer_opening_date" name="deposit_start" class="form-control myinput date_picker" value="<?php echo !empty($_SESSION["val5"]) ? $_SESSION["val5"] : Model::dateTimeFromUTC('Y-m-d', $data["date_of_deposit"],$user_demographic_data['timezone']); ?>" required/>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <br />
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive" style="padding-left:0px" >
                                                    <table class="table text-nowrap">
                                                        <tbody>
                                                        <tr class="table-active table-border-double">
                                                            <td onclick="show();" colspan="3" style="padding-left:0px;cursor:pointer;" class="my_h">
                                                                <span class="b_b">ADV</span>ANCED OPTIONS &nbsp;&nbsp;
                                                                <img src="image/navigate-arrows-pointing-to-down.png" height="15px"/>
                                                            </td>
                                                            <td class="text-right"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div id="div1" class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-5 col-lg-5">
                                                            <div class="form-group row">
                                                                <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                                    <label style="font-weight:bold;">Compounding frequency<span style="color:red">*</span> </label>
                                                                    <div class="form-group">
                                                                        <select class="form-control myinput" name="invest_payment">
                                                                            <option value="<?php echo Core::render(!empty($_SESSION["val9"]) ? $_SESSION["val9"] : $data["compound_frequency"]); ?>"><?php echo Core::render(!empty($_SESSION["val9"]) ? $_SESSION["val9"] : $data["compound_frequency"]); ?></option>
                                                                            <option value="At maturity">At maturity</option>
                                                                            <option value="Monthly">Monthly</option>
                                                                            <option value="Quarterly">Quarterly</option>
                                                                            <option value="Semi annually">Semi annually</option>
                                                                            <option value="Annually">Annually</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="form-group row">
                                                                <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                                    <label style="font-weight:bold;">
                                                                        <div id="parent"> Ask Rate
                                                                            <div style="height: 20px; width: 20px;background-color: #274EB3;color:white;margin-left:8px;border-radius: 50%;
                                                                display: inline-block;">
                                                                                <span style="margin-left:6.5px;"> ?</span></div>
                                                                            <div id="hover-content" style="min-height:30px;">
                                                                                Offers are compared based on annual simple interest rate.
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                    <div class="form-group-feedback form-group-feedback-right" >
                                                                        <input type="number" name="ask" min="0.00"  max="100" step=".01"
                                                                               onkeypress="return isNumberKey(this)"  class="form-control myinput"
                                                                               value="<?php if ($data["requested_rate"] == 0) {
                                                                                   echo "";
                                                                               } else {
                                                                                   echo Core::render($data["requested_rate"]);
                                                                               }
                                                                               ?>" placeholder="Ask Rate %">
                                                                        <div class="form-control-feedback">
                                                                            <i style="margin-top: 12px;" class="fa fa-percent text-muted"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-8">
                                                                    <div class="col-md-10" style="display: inline-block;">
                                                                        <label style="font-weight:bold;">Short Term Credit Rating</label>
                                                                          <select class="form-control myinput" name="chk1">
                                                                            <?php
                                                                            $credit_rating_type = db::getRecords("SELECT * FROM `credit_rating_type`");
                                                                            if (!empty($credit_rating_type)){
                                                                                foreach ( $credit_rating_type as $item) {
                                                                                    ?>
                                                                                    <option <?php echo $item['description'] == $data["requested_short_term_credit_rating"] ? 'selected' : ''; ?> value="<?php echo $item['description'];?>"><?php echo $item['description'];?></option>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-1" style="display: inline-block;">
                                                                        <a data-toggle="tooltip" title="<img src='../assets/img/credit_rating.png' style='width:100%' />">
                                                                            <i class="fa fa-info-circle" style="font-size: 20px"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br/>
                                                            <div class="form-group row">
                                                                <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                                    <label style="font-weight:bold;">Deposit Insurance</label>
                                                                    <div class="form-group">
                                                                        <select class="form-control myinput" name="dep_ins">
                                                                            <option <?php echo @$_SESSION["val18"] == "Any deposit insurance" ? 'selected' : ''; ?> value="Any deposit insurance">Any deposit insurance</option>
                                                                            <option <?php echo @$_SESSION["val18"] == "Any provincial insurance" ? 'selected' : ''; ?> value="Any provincial insurance">Any provincial insurance</option>
                                                                            <?php
                                                                            $deposit_insurance = db::getRecords("SELECT * FROM `deposit_insurance`");
                                                                            if (!empty($deposit_insurance)){
                                                                                foreach ( $deposit_insurance as $item) {
                                                                            ?>
                                                                                    <option <?php echo strtolower($item['description']) == strtolower($data["requested_deposit_insurance"]) ? 'selected' : '';?> value="<?php echo $item['description'];?>"><?php echo $item['description'];?></option>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 col-lg-5">
                                                            <div class="form-group row">
                                                                <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                                    <label style="font-weight:bold;">Special Instructions </label>
                                                                    <textarea id="my" maxlength="100" type="text" class="form-control myinput textareaWithTextLimit" placeholder="Special instructions" name="special_institute"><?php echo Core::render($data["special_instructions"]); ?></textarea>
                                                                    <span id="rchars">100</span> Character(s) Remaining
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-11">
                                                        <button type="button" style="border:1.5px solid gainsboro" class="btn btn-default" onclick="return window.location.reload();">Clear</button>
                                                        <a href="bids" style="border:1.5px solid gainsboro" class="btn btn-default">Cancel</a>
                                                        <input type="submit" name="submit_post_request1" class="btn btn-primary mmy_btn" value="Next" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /support tickets -->
                </div>
            </div>
            <script>
            $('a[data-toggle="tooltip"]').tooltip({
                animated: 'fade',
                placement: 'bottom',
                html: true,
                viewport: '#myform3'
            });
        </script>


        <script type="text/javascript">
            
    $(document).ready(function(){

    $(document).on('click','a',function (e) {
        if ( !$(this).hasClass("paginate_button") && !$(this).hasClass("no-page-exit-alert")) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "Are you sure?",
                text: "Are you sure leave this page?, your request changes won't be saved!",
                icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    $.post("../includes/ajax/general_requests.php",{
                            'action':'CANCEL_NON_PARTNERED_INVITES'
                        },
                        function(data, status){
                            data=JSON.parse(data);
                            if (data.success){
                                window.location.href = "logic?cancel_request_values=" + $this.attr("href");
                                return;
                            }

                            if(data?.reload){
                                swal({title:'', text:data.message, type:data.success? 'success': 'error',timer: 5000}).then((value) => { window.location.reload() });
                                return;
                            }
                            swal(data.message);
                        });
                }
            });
        }
    });
});
        </script>
        <?php
        require_once "footer.php";
    }else{
        echo "<script>location='bids'</script>";
    }
}else{
    echo "<script>location='bids'</script>";
}
?>
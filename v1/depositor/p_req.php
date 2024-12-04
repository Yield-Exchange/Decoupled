<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";
if (AuthModel::isLoggedIn()) {
    require_once "sidebar.php";
    global $user_data;
    
    $user_demographic_data = AuthModel::getUserDemographicData($user_data['id']);
    Core::activityLog("Depositor Post Request");

    $token = AuthModel::generateToken();
    $this_product_name = RequestsModel::getProductByID(@$_SESSION["val2"]);
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
<!--    <script src="--><?php //echo BASE_URL;?><!--/assets/js/jquery.dirty.js"></script>-->
    <script src="js/post_request.js?v=2.0"></script>
    <style>
        .tooltip-inner{
            background: transparent!important;
        }
        .tooltip-inner > img{
            width: 400px!important;
        }
        .tooltipimg
        {
            width:150%;
        }
        .myinput{
            border-radius: 0;
        }
        label{
            color:grey;
        }
        #hover-content {
            display:none;
        }
        #parent:hover #hover-content {
            display:block;
        }
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
                                    <td colspan="3" style="padding-left:40px" class="my_h"><span  class="b_b">POST</span> REQUEST</td>
                                    <td class="text-right"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">

                            <div class="card-header header-elements-inline">
                                <div class="card-body">
                                    <form action="logic" method="post" id="myform" autocomplete="off" class="validater_form post_request_form">
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
                                                                        <option <?php echo @$_SESSION["val2"] == $product['id'] ? 'selected' : ''; ?> value="<?php echo $product['id'];?>"><?php echo $product['description'];?></option>
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
                                                            <input type="number" min="1" value="<?php echo @$_SESSION["lockout_period"];?>" <?php echo ( !isset($_SESSION["lockout_period"])) ? "disabled" : ''; ?> name="lockout_period" placeholder="-" class="form-control lockout_period" oninput="this.value = Math.abs(this.value)"/>
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
                                                          <option <?php echo @$_SESSION["val3"] == "CAD" ? 'selected' : ''; ?> value="CAD">CAD</option>
                                                          <option <?php echo @$_SESSION["val3"] == "USD" ? 'selected' : ''; ?> value="USD">USD</option>
                                                        </select>
                                                    </span>
                                                            </div>
                                                            <input type="text" value="<?php echo @$_SESSION["val4"];?>" onchange="addThousands(this);" placeholder="Deposit Amount" name="deposit_amount" id="dep_amm" class="form-control dep_amm myinput" required>
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
                                                                <option value="">Select term</option>
                                                                <option <?php echo @$_SESSION["term_type"] == "months" ? 'selected' : ''; ?> value="months">Months</option>
                                                                <option  <?php echo @$_SESSION["term_type"] == "days" ? 'selected' : ''; ?> value="days">Days</option>
                                                            </select>
                                                        </span>
                                                                </div>
                                                                <input type="number" value="<?php echo @$_SESSION["val8"];?>" placeholder="Term length" min="1" max="8000" name="month" class="form-control myinput term_length" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Closing Date &amp; Time <span style="color:red">*</span> </label>
                                                            <input id="offer_closing_date" value="<?php echo @$_SESSION["val11"];?>" placeholder="Closing Date & Time" type="text" name="closing" class="form-control myinput datetimepicker" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                        <div class="form-group">
                                                            <label style="font-weight:bold;">Date of deposit (approx) <span style="color:red">*</span></label>
                                                            <input type="text" value="<?php echo @$_SESSION["val5"];?>" id="offer_opening_date" placeholder="Date of deposit (approx)" name="deposit_start"  class="form-control myinput date_picker" required />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <br>

                                        <div class="table-responsive" style="padding-left:0px" >
                                            <table class="table text-nowrap">
                                                <tbody>
                                                <tr class="table-active table-border-double">
                                                    <td style="padding-left:0px;cursor:pointer;" class="my_h" onclick="show();">
                                                        <span class="b_b">ADV</span>ANCED OPTIONS &nbsp;&nbsp;
                                                        <img src="image/navigate-arrows-pointing-to-down.png" class="img-advance-options" height="15px"/>
                                                    </td>
                                                    <td class="text-left"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <br> <br>

                                        <div id="div1" class="row">
                                            <div class="col-md-5 col-lg-5">

                                                <div class="form-group row">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                        <label style="font-weight:bold;">Compounding frequency<span style="color:red">*</span> </label>
                                                        <div class="form-group">
                                                            <select class="form-control myinput" name="invest_payment">
                                                                <option <?php echo @$_SESSION["val9"] == "At maturity" ? 'selected' : ''; ?> value="At maturity">At maturity</option>
                                                                <option <?php echo @$_SESSION["val9"] == "Monthly" ? 'selected' : ''; ?> value="Monthly">Monthly</option>
                                                                <option <?php echo @$_SESSION["val9"] == "Quarterly" ? 'selected' : ''; ?> value="Quarterly">Quarterly</option>
                                                                <option <?php echo @$_SESSION["val9"] == "Semi annually" ? 'selected' : ''; ?> value="Semi annually">Semi annually</option>
                                                                <option <?php echo @$_SESSION["val9"] == "Annually" ? 'selected' : ''; ?> value="Annually">Annually</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8">
                                                        <label style="font-weight:bold;">
                                                            <div id="parent"> Ask Rate
                                                                <div style="height: 20px;
                                                          width: 20px;
                                                          background-color: #274EB3;
                                                          color:white;
                                                          margin-left:8px;
                                                          border-radius: 50%;
                                                          display: inline-block;">
                                                                    <span style="margin-left:6.5px;"> ?</span></div>
                                                                <div id="hover-content" style="min-height:30px;">
                                                                    Offers are compared based on annual simple interest rate.
                                                                </div>
                                                            </div>
                                                        </label>
                                                        <div class="form-group-feedback form-group-feedback-right" >
                                                            <input value="<?php echo @$_SESSION["val17"];?>" type="number" name="ask" min="0.00" max="100" step=".01" onkeypress="return isNumberKey(this)" class="form-control myinput" placeholder="Ask Rate %">
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
                                                            <div class="form-group">
                                                                <select type="text" class="form-control myinput" name="chk1">
                                                                    <?php
                                                                    $credit_rating_type = db::getRecords("SELECT * FROM `credit_rating_type`");
                                                                    if (!empty($credit_rating_type)){
                                                                        foreach ( $credit_rating_type as $item) {
                                                                            if(isset($_SESSION["val12"])){
                                                                            ?>
                                                                            <option <?php echo (@$_SESSION["val12"] == $item['description'] ) ? "selected" : "";?> value="<?php echo $item['description'];?>"><?php echo $item['description'];?></option>
                                                                            <?php
                                                                        } else{ ?>
                                                                                <option <?php echo ( $item['description'] == "Any/Not Rated") ? "selected" : "";?> value="<?php echo $item['description'];?>"><?php echo $item['description'];?></option>
                                                                   <?php     }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
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
                                                                    <option <?php echo strtolower($item['description']) == strtolower(@$_SESSION["val18"]) ? 'selected' : '';?> value="<?php echo $item['description'];?>"><?php echo $item['description'];?></option>
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
                                        <div id="div2" class="row">
                                            <div class="col-md-5 col-lg-5">
                                                <input type="hidden" name="anonymous" value="off"/>
                                                <div class="col-xs-8 col-lg-8 col-md-8 col-sm-8" style="padding-left: 0!important;">
                                                    <label style="font-weight:bold;">Special Instructions </label>
                                                    <textarea id="my" maxlength="100" type="text" class="form-control myinput textareaWithTextLimit" placeholder="Special instructions" name="special_institute"><?php echo @$_SESSION["val10"];?></textarea>
                                                    <br/>
                                                    <span id="rchars">100</span> Character(s) Remaining
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-11">
                                                <button onclick="window.location.href='<?php echo BASE_URL;?>/depositor/logic?reset_values=1'" style="border:1.5px solid gainsboro" class="btn btn-default">Clear</button>

                                                <input type="submit" name="submit_post_request" class="btn btn-primary mmy_btn submit_post_request" value="Next">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
}
?>
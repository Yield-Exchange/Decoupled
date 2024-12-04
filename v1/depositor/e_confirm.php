<?php
session_start();
require_once "header.php";
require_once "sidebar.php";
require_once BASE_DIR."/config/RequestsModel.php";

global $user_data;
$demographic_data = AuthModel::getUserDemographicData($user_data['id']);
$banks = BankModel::getBanksANDBrokersWithCreditDepositInsuranceFilters($_SESSION["val18"],$_SESSION["val12"],$user_data);
Core::activityLog("Depositor Confirm Update Post Request");
?>
    <style>
        p{
            color:grey;
            font-size: 13px;
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
                        <div class="card" style="padding:20px;padding-top:10px">
				            <div class="table-responsive" style="padding-left:0px">
								<table class="table text-nowrap" >
                                    <tbody>
                                        <tr class="table-active table-border-double">
											<td style="padding-left:10px" colspan="3" class="my_h"><span class="b_b">CON</span>FIRM DATA</td>
											<td class="text-right"></td>
										</tr>
                                    </tbody>
                                </table>
                            </div>
                        <br />
                        <div class="row">
                                <div class="col-md-12">
                                    <h5 style="color:#2CADF5;font-weight:bold;">Request Summary</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5"><p style="font-weight:bold;">Product</p></div>
                                        <div class="col-md-7">
                                            <span style="font-weight:bold">
                                                <?php
                                                    $product = RequestsModel::getProductByID($_SESSION["val2"]);
                                                    echo !empty($product) ? $product['description'] : '';
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php
                                    if ( empty($product) || strpos($product['description'], 'High Interest Savings') === false ){
                                    ?>
                                    <div class="row">
                                        <div class="col-md-5"><p style="font-weight:bold;"><?php echo !empty($product) && trim(strtolower($product['description'])) =="notice deposit" ? 'Notice Period' : 'Lockout Period';?></p></div>
                                        <div class="col-md-7">
                                            <span style="font-weight:bold">
                                                <?php
                                                    echo !empty($_SESSION['lockout_period']) && in_array(trim(strtolower($product['description'])),['notice deposit','cashable']) ? $_SESSION["lockout_period"].' days' : '-';
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
                                                <?php echo $_SESSION["val3"] . " " . $_SESSION["val4"]; ?>
                                            </span>
                                        </div>
                                    </div>
                                   <?php
                                   if ( empty($product) || strpos($product['description'], 'High Interest Savings') === false ){
                                    ?>
                                    <div class="row">
                                        <div class="col-md-5"><p style="font-weight:bold;">Term Length</p></div>
                                        <div class="col-md-7">
                                            <span style="font-weight:bold">
                                                <?php if ($_SESSION["val8"] > 1) { echo $_SESSION["val8"] . " " . $_SESSION["term_type"];} else {echo $_SESSION["val8"] . " " . $_SESSION["term_type"];}?>
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
                                                <?php echo $_SESSION["val11"]; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5"><p style="font-weight:bold;">Ask Rate</p></div>
                                        <div class="col-md-7">
                                            <span style="font-weight:bold">
                                                <?php
                                                echo BankModel::getInterest($_SESSION["val17"]);
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
                                                <?php echo $_SESSION["val5"]; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5"><p style="font-weight:bold;">Compounding frequency</p></div>
                                        <div class="col-md-7">
                                            <span style="font-weight:bold">
                                                <?php echo $_SESSION["val9"]; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5"><p style="font-weight:bold;">Short Term DBRS Rating</p></div>
                                        <div class="col-md-7">
                                            <span style="font-weight:bold">
                                                <?php echo $_SESSION["val12"]; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5"><p style="font-weight:bold;">Deposit Insurance</p></div>
                                        <div class="col-md-7">
                                            <span style="font-weight:bold">
                                                <?php echo $_SESSION["val18"]; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5"><p style="font-weight:bold;">Special Instructions</p></div>
                                        <div class="col-md-7">
                                            <span style="font-weight:bold">
                                                <?php echo $_SESSION["val10"]; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-md-12">
                                     <div class="table-responsive" style="padding-left:0px" >
                                            <table class="table text-nowrap">
                                                <tbody>
                                                <tr class="table-active table-border-double">
                                                    <td style="padding-left:0px;cursor:pointer;" class="my_h" onclick="show_more();">
                                                        <span style="color:#2CADF5;font-weight:bold;">INVITED FINANCIAL INSTITUTIONS</span> &nbsp;&nbsp;
                                                        <img src="image/navigate-arrows-pointing-to-down.png" class="img-advance-options" height="15px"/>
                                                    </td>
                                                    <td class="text-left"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                            </div>
                            <div class="row" id="div11">
                                    <?php
                                    $i=1;
                                    if (!empty($banks)) {
                                        foreach ($banks as $record) {
                                            $user_id = $record["id"];
                                            
                                            $credit_ratings = Core::getRatings($user_id);
                                           
                                            $invited_now = $_SESSION['too']; 
                    
                                            if(in_array($record["id"], $invited_now)){
                                    ?>
                                    
                                        <div class="col-md-3"><?= $i.'.  '; ?>&nbsp;
                                        
                                            <span style="font-weight:bold">
                                                <?php echo Core::render($record["name"]); ?>
                                            </span>
                                        </div>
                                    
                                <?php
                                    $i++;  }
                                        }
                                    }
                                ?>
                            </div>
                        <br /><br />
                         <div style="padding-bottom:30px;padding-top:45px;padding-left:10px;" class="row">
                            <div class="col-lg-2 col-md-2 col-sm-3">
                                <button style="border:1.5px solid gainsboro" class="btn btn-block cancel-request">Cancel</button>
                            </div>
                             <div class="col-lg-2 col-md-2 col-sm-3">
                                 <button onclick="window.location.href='edit_request?id=<?php echo Core::urlValueEncrypt($_SESSION["dep_id"]); ?>'" style="border:1.5px solid gainsboro" class="btn btn-block">Edit</button>
                             </div>
                            <div class="col-lg-2 col-md-2 col-sm-3">
                                <button onclick="window.location.href='logic?confirm_all2=all'"  class="btn mmy_btn btn-primary btn-block">Submit</button>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3"></div>
                         </div>
					 </div>
                    </div>
                </div>
            </div>
<script>
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

    $(document).on('click','.cancel-request',function (e) {
        e.preventDefault();
        $this = $(this);

        swal({
            title: "Are you sure?",
            text: "You are about to cancel editing of the request, would you wish to proceed?",
            icon: "warning",
            buttons: ["No", "Yes"],
        }).then((response) => {
            if (response) {
                window.location.href='logic?reset_values1=1';
                // window.location.href=$this.attr('href');
            }
        });
    });

     $(document).ready(function() {
         $('#div11').hide();
        $('#div22').hide();
    });

function show_more(){

    if(document.getElementById("div11").style.display=="none"){
        $('#div11').show();
        $('#div22').show();
        
    }else{
        $('#div11').hide();
        $('#div22').hide();
    }
}
</script>
<?php
require_once "footer.php";
?>
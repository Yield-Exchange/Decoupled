<?php
session_start();
require_once "header.php";
require_once "sidebar.php";
require_once "../config/AuthModel.php";
require_once "../config/RequestsModel.php";

if (AuthModel::isLoggedIn()) {
    global $user_data;

    $rzlt = BankModel::getPostRequestsForBank($user_data['id'],true);
    $size = $rzlt['total'];
    $data = $rzlt['data'];

    $user_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
    Core::activityLog("Bank New Requests");
    $link = BankModel::generateNonInvitedFITerms($user_data);
?>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content">
                 <?php
                    if ( $user_data['is_non_partnered_fi'] == 1 && $user_data['account_status'] != 'ACTIVE' ){
                ?>
                <style>
                    .row-custom{
                        margin: 30px 0 30px 0;
                    }
                    .card-buttons-container{
                        justify-content: center; align-items: center;
                    }
                    .card-buttons:first-child{
                        margin-right: 2%;
                    }
                    .card-buttons{
                        cursor:pointer;
                        min-height: 200px;
                        font-size: 1rem;
                        font-weight: normal;
                        border-radius: 5px;
                        width: 15rem;
                    }
                    .card-buttons:hover{
                        -webkit-transform: scale(1.1);
                        -moz-transform: scale(1.1);
                        -ms-transform: scale(1.1);
                        -o-transform: scale(1.1);
                        transform: scale(1.1);
                        box-shadow: 2px 2px #ccc;;
                    }
                    .choose-action-title{
                        font-weight: 600;
                        font-size: 1.3rem;
                    }
                    .card-text{
                        font-weight: 300;
                        font-size: 15px;
                    }
                    .card-img-top{
                        height: 100px;
                        width: 100px;
                        margin-left: auto;
                        margin-right: auto;
                        margin-top: 10px;
                    }
                </style>
                <div class="row">
                    <div class="col-xl-12">
                        <!-- Support tickets -->
                        <div class="card" style="width: 60%;margin: 0 auto 20px;">
                            <div class="row row-custom">
                                <div class="col-md-12 text-center">
                                    <h4 class="choose-action-title">Choose an action that will guide you through the best steps to take</h4>
                                    <br/>
                                    <div class="row col-md-12 card-buttons-container">
                                        <div class="card btn-decline-terms card-buttons col-md-3">
                                            <img class="card-img-top" src="<?php echo BASE_URL.'/assets/images/icons/';?>decline-terms.png" alt="Card image cap">
                                            <div class="card-body" style="padding-left: 0;padding-right: 0">
                                                <p class="card-text">I'm not interested</p>
                                            </div>
                                        </div>
                                        <div class="card card-buttons btn-show-terms col-md-3">
                                            <img class="card-img-top" src="<?php echo BASE_URL.'/assets/images/icons/';?>accept-terms.png" alt="Card image cap">
                                            <div class="card-body" style="padding-left: 0;padding-right: 0">
                                                <p class="card-text">Proceed to Terms <br/>and conditions.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="terms" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-xl">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><b>Terms and Conditions</b></h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="post" autocomplete="off" id="terms_and_conditions">
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <h5>Read carefully and click on accept at the end.</h5>
                                                        <div class="form-group">
                                                            <div class="termsx">
                                                                <div class="col-md-12">
                                                                    <iframe src="<?php echo $link; ?>" width="100%" height="500px"></iframe>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" style="justify-content:center">
                                            <button type="button" class="btn btn-secondary btn-lg" onclick="return terms(false,this);">I do not accept T&C</button>
                                            <button type="button" class="btn btn-success btn-lg" onclick="return terms(true,this);">I accept T&C</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        <script type="text/javascript">
                            $(document).on('click','.btn-decline-terms',function () {
                                $this = $(this);
                                swal({
                                    title: "",
                                    text: "You are about to decline the invitation, do you wish to proceed?",
                                    icon: "warning",
                                    buttons: ["No", "Yes"],
                                }).then((response) => {
                                    if (response) {
                                        $.post("../includes/ajax/general_requests.php", {
                                                'action': 'DECLINE_INVITATION_NON_PARTNERED_FI'
                                            },
                                            function (data, status) {
                                                data = JSON.parse(data);
                                                if (data.success) {
                                                    window.location.href = "<?php echo BASE_URL?>/index";
                                                    return;
                                                }
                                                swal(data.message);
                                            });
                                    }
                                });
                            });

                            $(document).on('click','a',function (e) {
                                if ( !$(this).hasClass("no-page-exit-alert") ) {
                                    $this = $(this);
                                    swal({
                                        title: "Are you sure?",
                                        text: "You are about to to leave this page.",
                                        icon: "warning",
                                        buttons: ["No", "Yes"],
                                    }).then((response) => {
                                        if (response) {
                                            window.location.href=$this.attr('href');
                                        }
                                    });

                                    return false;
                                }
                            });

                            $(document).on('click','.btn-show-terms',function () {
                                $('#terms').modal('show'); //{backdrop: 'static', keyboard: false}
                            });

                            function terms(accept=true,_this) {
                                if(accept){
                                    ajaxTerms(accept,_this);
                                }else {
                                    swal({
                                        title: "",
                                        text: "You no longer want to hear from Yield Exchange about deposit opportunities",
                                        icon: "warning",
                                        buttons: ["No", "Yes"],
                                    }).then((response) => {
                                        if (response) {
                                            ajaxTerms(accept,_this);
                                        } else {
                                            $('#terms').modal('hide');
                                        }
                                    });
                                }
                            }

                            function ajaxTerms(accept=true,_this) {
                                let text  = $(_this).html();
                                $(_this).html('Please wait');
                                $.post("../includes/ajax/general_requests.php", {
                                        'action': accept ? 'ACCEPT_TERMS_AND_CONDITIONS' : 'DECLINE_TERMS_AND_CONDITIONS',
                                    },
                                    function (data, status) {
                                        data = JSON.parse(data);
                                        if (data.success) {
                                            $('#step3').show();
                                            $('#step1').hide();
                                            $('#terms').modal('hide');
                                            swal({title:'', text:data.message, type:data.success? 'success': 'info',timer: 5000}).then((value) => {
                                                if ( data.hasOwnProperty('relogin') ){
                                                    window.location.href = "<?php echo BASE_URL?>/login?a=fi";
                                                }else {
                                                    window.location.href = "<?php echo BASE_URL?>/bank/requests";
                                                }
                                            });
                                        }else{
                                            swal({title:'', text:data.message, type:data.success? 'success': 'info',timer: 5000}).then((value) => { window.location.href = "<?php echo BASE_URL?>/index"; });
                                        }
                                        $(_this).html(text);
                                    });
                            }
                        </script>
                <?php
                    }
                ?>

            <!-- Main charts -->
				<div class="row">
					<div class="col-xl-12">

                        <!-- Support tickets -->
						<div class="card">

                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr class="table-active table-border-double">
                                            <td colspan="3">New Requests <span class="badge bg-blue badge-pill"><?php echo $size; ?></span></td>
                                            <td class="text-right">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table custom-data-tables no-footer table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Depositor Name</th>
                                            <th>Province</th>
                                            <th>Request Amount</th>
                                            <th>Product</th>
                                            <th>Investment Period</th>
                                            <th>Closing Date & time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
	                                <tbody>
                                    <?php
                                    if ($data) {
                                            foreach ($data as $rec) {
                                                $depositor = AuthModel::getUserDataByID($rec['user_id']);
                                                $depositor_data = AuthModel::getUserDemographicData($rec['user_id']);
                                    ?>
										<tr>
                                            <td>
                                                <?php echo Core::render($depositor['name']); ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0"><?php echo Core::render($depositor_data['province']); ?></h6>
                                                </div>
                                            </td>

                                            <td data-order="<?php echo $rec["amount"];?>">
                                                <h6 class="mb-0" align="left">
                                                    <?php
                                                    echo Core::render($rec["currency"]) . "&nbsp;&nbsp;&nbsp;" . number_format($rec["amount"]);
                                                    ?>
                                                </h6>
                                            </td>

											<td>
												<div>
                                                    <h6 class="mb-0">
                                                        <?php
                                                            $product = RequestsModel::getProductByID($rec["product_id"]);
                                                            echo !empty($product) ? Core::render($product['description']) : '';
                                                        ?>
                                                    </h6>
                                                </div>
											</td>

											<td>
												<h6 class="mb-0">
                                                    <?php
                                                     if ($rec['term_length_type'] == "HISA"){
                                                             echo '-';
                                                     }else {
                                                         echo Core::render($rec['term_length'] . ' ' . ucwords(strtolower($rec['term_length_type'])));
                                                     }
                                                    ?>
                                                </h6>
											</td>
                                            <td>
												<h6 class="mb-0">
                                                    <?php
                                                        echo Model::dateTimeFromUTC('Y-m-d h:i a',$rec['closing_date_time'],$user_demographic_user_data['timezone']);
                                                    ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <?php
                                                if ( $user_data['is_non_partnered_fi'] == 1 && $user_data['account_status'] == 'ACTIVE' || $user_data['is_non_partnered_fi'] == 0 ){
                                                ?>
												    <a href="bid_data?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>" class="btn btn-primary mmy_btn btn-block">View</a>
												<?php
                                                }
                                                ?>
                                            </td>
										</tr>
                                    <?php
                                         }
                                    }
                                    ?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- /support tickets -->
					</div>

				</div>
				<!-- /main charts -->
			</div>
			<!-- /content area -->
            </div>
<script type="text/javascript">

    $('.custom-data-tables').DataTable({
        "order": [[ 0, "desc" ]],
        "scrollX": true,
    });
</script>
<?php
require_once "footer.php";
}
?>
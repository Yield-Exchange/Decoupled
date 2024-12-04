<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

if (AuthModel::isLoggedIn()) {
    require_once "sidebar.php";
    global $user_data;

    $usr_demographic_data = AuthModel::getUserDemographicData($user_data['id']);

    Core::activityLog("Depositor Review Offers");

    $rzlt = DepositorModel::getDepositorRequestsByUserID($user_data['id']);
    $size = $rzlt['total'];
    $data = $rzlt['data'];
?>
<script>
    $(document).ready(function () {
        $('.custom-data-tables').DataTable({
            // columnDefs: [
            //     { type: 'natural', targets: 3 }
            // ],
            order: []
        });
        $('.dataTables_length').addClass('bs-select');
    });
</script>
        <!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			<div class="content">
                <div class="row">
					<div class="col-xl-12">
						<div class="card" >
                            <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr class="table-active table-border-double">
                                                <td colspan="3" class="my_h"><span class="b_b" >Re</span>view offers <span class="badge bg-blue badge-pill"><?php echo $size; ?></span></td>
                                                <td class="text-right">
                                                </td>
                                            </tr>
                                        </tbody>
                                </table>
                           </div>

                            <div class="col-sm-12">
                                <div class="table-responsive">

                                <table class="table custom-data-tables table-condensed">
                                    <thead>
                                        <tr role="row">
                                            <th>Closing date & time</th>
                                            <th>Request id</th>
                                            <th>Product</th>
                                            <th>Amount</th>
                                            <th>Investment period</th>
                                            <th>No of offers</th>
                                            <th>Highest</th>
                                            <th>Lowest</th>
                                            <th style="width: 15%;">Actions</th>
                                        </tr>
                                    </thead>
						            <tbody>
                                        <?php
                                            if ($data) {
                                                foreach ($data as $record) {
//                                                    $user_demographic_data = AuthModel::getUserDemographicData($record['user_id']);
                                                    $timezone = $usr_demographic_data['timezone'];
                                        ?>
										<tr>
                                            <td>
                                                <h6 class="mb-0">
                                                    <?php
                                                        echo Model::dateTimeFromUTC('Y-m-d h:i a',$record['closing_date_time'],$timezone);
                                                    ?>
                                                </h6>
                                            </td>
                                            <td>
												<h6 class="mb-0"><?php echo Core::render($record["reference_no"]); ?> </h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <?php
                                                    $product = RequestsModel::getProductByID($record["product_id"]);
                                                    echo !empty($product) ? Core::render($product['description']) : '';
                                                    ?>
                                                </h6>
                                            </td>
                                            <td data-order="<?php echo $record["amount"];?>">
												<h6 class="mb-0" align="left">
                                                    <?php
                                                        echo Core::render($record["currency"]) . "&nbsp;&nbsp;&nbsp;" . number_format($record["amount"]);
                                                    ?> 
                                                </h6>
											</td>
                                            <td>
                                                <h6 class="mb-0" align="left">
                                                    <?php
                                                    if ($record['term_length_type'] == "HISA"){
                                                        echo '-';
                                                    }else {
                                                        echo Core::render($record['term_length']) . ' ' . Core::render(ucwords(strtolower($record['term_length_type'])));
                                                    }
                                                    ?>
                                                </h6>
                                            </td>
                                            <td class="text-center">
												<h6 class="mb-0">
                                                    <?php
                                                        $total_bids = db::getcell("select count(*) as total from offers o, invited i where i.id=o.invitation_id AND i.depositor_request_id='".$record['id']."' AND o.offer_status IN('ACTIVE')");
                                                        echo $total_bids;
                                                    ?>
                                                </h6>
                                            </td>
                                            <td>
												<h6 class="mb-0">
                                                    <?php
                                                    $get_highest = db::getRecord("select o.id,o.rate_type,o.prime_rate,o.rate_operator,Max(interest_rate_offer) as interest_rate_offer from offers o, invited i where i.depositor_request_id='".$record['id']."' AND i.id=o.invitation_id AND o.offer_status IN('ACTIVE') GROUP BY o.id");
                                                    if ($get_highest['rate_type']!='VARIABLE'){
                                                        echo BankModel::getInterest($get_highest['interest_rate_offer']);
                                                    }else {
                                                        echo BankModel::getInterest($get_highest['prime_rate'], true,$get_highest['rate_operator']);
                                                    }
                                                    ?>
                                                </h6>
                                            </td>
                                            <td class="text-center">
												<h6 class="mb-0">
                                                    <?php
                                                    $get_lowest = db::getRecord("Select o.id,o.rate_type,o.prime_rate,o.rate_operator,Min(interest_rate_offer) as interest_rate_offer from offers o, invited i where i.depositor_request_id='".$record['id']."' AND i.id=o.invitation_id AND o.offer_status IN('ACTIVE') GROUP BY o.id");
                                                    if ($get_lowest['rate_type']!='VARIABLE'){
                                                        echo BankModel::getInterest($get_lowest['interest_rate_offer']);
                                                    }else {
                                                        echo BankModel::getInterest($get_lowest['prime_rate'], true,$get_lowest['rate_operator']);
                                                    }
                                                    ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <a href="vrzlts?rqid=<?php echo Core::urlValueEncrypt($record["id"]); ?>" class="btn btn-primary mmy_btn">View&nbsp;Offers</a>
                                                <div class="list-icons">
                                                    <div class="list-icons-item dropdown">
                                                        <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-uu" style="position: absolute;z-index:999">
                                                             <a href="rq_details?id=<?php echo Core::urlValueEncrypt($record["id"]); ?>" class="dropdown-item">View Request</a>
                                                            <div class="dropdown-divider"></div>
                                                            <?php
                                                                if ($total_bids > 0) {
                                                            ?>
                                                                <a class="dropdown-item" onclick="active_requests()">Edit Request</a>
                                                            <?php
                                                                } else {
                                                            ?>
                                                                <a href="edit_request?id=<?php echo Core::urlValueEncrypt($record["id"]); ?>" onclick="return editIt()" class="dropdown-item">Edit Request</a>
                                                            <?php
                                                                }
                                                            ?>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="logic?del_id=<?php echo Core::urlValueEncrypt($record["id"]); ?>" onclick="return withdraw()" class="dropdown-item">Withdraw Request</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="view_invites?rid=<?php echo Core::urlValueEncrypt($record['id']);?>" class="dropdown-item">Invited institutions</a>
                                                        </div>
                                                    </div>
                                                </div>
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

						</div>
						<!-- /support tickets -->
					</div>
				</div>
            </div>

             <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 style="color:red" class="modal-title"><span class="b_b" >Act</span>ion can not be completed!</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <div class="modal-body"><p style="text-align:center;">You have already received offers on this request</p></div>
                  <br />
                </div>
              </div>
            </div>

 <script>
    function withdraw(){
        return(confirm("Do you really want to continue and withdraw this request?"));
    }
    function editIt(){
        return(confirm("Do you really want to edit?"));
    }
    function active_requests(){
       $('#myModal1').modal('show');
    }
    function active_requests2(){
       $('#myModal2').modal('show');
    }
 </script>
<?php
require_once "footer.php";
}
?>
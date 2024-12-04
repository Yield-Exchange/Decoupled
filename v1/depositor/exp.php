<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

if (AuthModel::isLoggedIn()) {
require_once "sidebar.php";
global $user_data;
$usr_demographic_data = AuthModel::getUserDemographicData($user_data['id']);

Core::activityLog("Depositor History");

$request_history = DepositorModel::getDepositorRequestsHistory($user_data['id']);
$contract_history = DepositorModel::getDepositorContractHistory($user_data['id']);
?>
<script>
    $(document).ready(function () {
        $('.custom-data-tables').DataTable({
            "scrollX": true,
            "order": [ 0, "desc" ]
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
                <div class="card">
                    <div class="table-responsive" >
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3" class="my_h">Deposits History  <span class="badge bg-blue badge-pill contract-history-count">0</span></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table custom-data-tables table-condensed">
                                <thead>
                                    <tr role="row">
                                        <th>Date</th>
                                        <th>Deposit ID</th>
                                        <th>GIC Number</th>
                                        <th>Institution</th>
                                        <th>Deposit Amount</th>
                                        <th>Product</th>
                                        <th>Investment Period</th>
                                        <th>Interest Rate</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ( !empty($contract_history['data'])){
                                    foreach ($contract_history['data'] as $rec){
                                        $user_demographic_data = $usr_demographic_data; //AuthModel::getUserDemographicData($rec['user_id']);
                                        $bank_data = AuthModel::getUserDataByID($rec['invited_user_id']);
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                            echo !empty($rec['contract_modified_at']) ? Model::dateTimeFromUTC('Y-m-d',$rec['contract_modified_at']) : Model::dateTimeFromUTC('Y-m-d',$rec['created_at']);
                                        ?>
                                    </td>
                                    <td>
                                        <h6 class="mb-0"><?php echo Core::render($rec["contract_reference_no"]); ?></h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0"><?php echo Core::render($rec["gic_number"]); ?></h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0"><?php echo Core::render($bank_data["name"]); ?></h6>
                                    </td>
                                    <td data-order="<?php echo $rec["offered_amount"];?>">
                                        <h6 class="mb-0" align="left">
                                            <?php
                                            echo Core::render($rec["currency"]) . "&nbsp;&nbsp;&nbsp;" . number_format($rec["offered_amount"]);
                                            ?>
                                        </h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">
                                            <?php
                                                $product = RequestsModel::getProductByID($rec["product_id"]);
                                                echo !empty($product) ? Core::render($product['description']) : '';
                                            ?>
                                        </h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0" align="left">
                                            <?php
                                            if ($rec['term_length_type'] == "HISA"){
                                                echo '-';
                                            }else {
                                                echo Core::render(ucfirst($rec["term_length"]) . ' ' . ucwords(strtolower($rec['term_length_type'])));
                                            }
                                            ?>
                                        </h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">
                                            <?php
                                            if ($rec['rate_type']!='VARIABLE'){
                                                echo BankModel::getInterest($rec['interest_rate_offer']);
                                            }else {
                                                echo BankModel::getInterest($rec['prime_rate'], true,$rec['rate_operator']);
                                            }
                                            ?>
                                        </h6>
                                    </td>

                                    <td>
                                        <?php
                                            echo Core::render(ucwords(strtolower(str_replace("_"," ",$rec['contract_status']))));
                                        ?>
                                    </td>
                                    <td>
                                        <a href="comp_details?cnid=<?php echo Core::urlValueEncrypt($rec['contract_id']); ?>&&history" class="btn btn-primary">View</a>
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
        </div>

        <div class="row">
            <div class="col-xl-12">

                <div class="card">

                    <div class="table-responsive" >
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3" class="my_h">Request History  <span class="badge bg-blue badge-pill request-history-count">0</span></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table custom-data-tables table-condensed">
                                <thead>
                                    <tr role="row">
                                        <th>Date</th>
                                        <th>Request ID</th>
                                        <th>Request Amount</th>
                                        <th>Product</th>
                                        <th>Investment Period</th>
                                        <th>Asked interest rate</th>
                                        <th>No of offers</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($request_history['data'])){
                                  foreach ($request_history['data'] as $rec){
                                    $user_demographic_data = $usr_demographic_data; //AuthModel::getUserDemographicData($rec['user_id']);
                                    $total_bids = db::getcell("select count(*) as total from offers o, invited i where i.id=o.invitation_id AND i.depositor_request_id='" . $rec['id'] . "'");
                                ?>
                                    <tr>
                                        <td>
                                            <?php
                                                echo !empty($rec['modified_date']) ? Model::dateTimeFromUTC('Y-m-d',$rec['modified_date']) : Model::dateTimeFromUTC('Y-m-d',$rec['created_date']);
                                            ?>
                                        </td>
                                        <td>
                                            <h6 class="mb-0"><?php echo Core::render($rec["reference_no"]); ?></h6>
                                        </td>
                                        <td data-order="<?php echo $rec["amount"];?>">
                                            <h6 class="mb-0" align="left">
                                                <?php
                                                echo Core::render($rec["currency"]) . "&nbsp;&nbsp;&nbsp;" . number_format($rec["amount"]);
                                                ?>
                                            </h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">
                                                <?php
                                                    $product = RequestsModel::getProductByID($rec["product_id"]);
                                                    echo !empty($product) ? Core::render($product['description']) : '';
                                                ?>
                                            </h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0" align="left">
                                                <?php
                                                  if ($rec['term_length_type'] == "HISA"){
                                                      echo '-';
                                                  }else {
                                                      echo Core::render(ucfirst($rec["term_length"]) . ' ' . ucwords(strtolower($rec['term_length_type'])));
                                                  }
                                                ?>
                                            </h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0" align="left">
                                                <?php
                                                    echo BankModel::getInterest($rec["requested_rate"]);
                                                ?>
                                            </h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0" align="left">
                                                <?php
                                                    echo Core::render($total_bids);
                                                ?>
                                            </h6>
                                        </td>
                                        <td>
                                            <?php
                                            if ($rec['request_status']=="ACTIVE"){ /*IN CASE THE CRON JOB IS LATE*/
                                                    $invites =db::getRecord("SELECT i.* FROM invited i WHERE i.depositor_request_id = '".$rec['id']."' AND i.invitation_status IN('PARTICIPATED')");
                                                    if ( !empty($invites) ){
                                                        echo "Expired";
                                                    }else{
                                                        echo "No Offers Received ";
                                                    }
                                            }else {
                                                echo Core::render(ucwords(strtolower(str_replace("_", " ", $rec['request_status']))));
                                            }
                                            ?>
                                        </td>
                                        <td>
                                      <?php
                                      if ($total_bids > 0) {
                                      ?>
                                            <a href="history_contract_offers?rqid=<?php echo Core::urlValueEncrypt($rec['id']); ?>" class="btn btn-primary">Review offers</a>
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
        </div>

    </div>
    <?php
        require_once "footer.php";
    }
    ?>
    <script>
        $(".contract-history-count").html(<?php echo json_encode($contract_history['total'])?>);
        $(".request-history-count").html(<?php echo json_encode($request_history['total'])?>);
    </script>
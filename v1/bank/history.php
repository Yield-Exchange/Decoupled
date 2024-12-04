<?php
session_start();
require_once "header.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";
    global $user_data;

    $offers_history = BankModel::getBankHistoryOffers($user_data['id']);
    $contract_history = BankModel::getBankHistoryContracts($user_data['id']);
    $user_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);

    Core::activityLog("Bank History");
    ?>
    <script>
        $(document).ready(function () {
            $('.custom-data-tables').DataTable({
                "scrollX": true,
                "order": [ 1, "desc" ]
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

    <!-- Main content -->
    <div class="content-wrapper">
        <!-- /page header -->

        <!-- Content area -->
        <div class="content">

            <div class="row">
                <div class="col-xl-12">

                    <div class="card">

                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3" class="my_h">Deposits HISTORY <span class="badge bg-blue badge-pill contract-history-count">0</span></td>
                                    <td class="text-right"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-12">

                            <div class="table-responsive">

                                <table class="table custom-data-tables table-condensed">
                                    <thead>
                                        <tr role="row">
                                            <th>Deposit ID</th>
                                            <th>Date</th>
                                            <th>Depositor Name</th>
                                            <th>Province</th>
                                            <th>Deposit Amount</th>
                                            <th>Product</th>
                                            <th>Investment Period</th>
                                            <th>Interest Rate %</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ( !empty($contract_history['data'])){
                                        foreach ($contract_history['data'] as $rec){
                                            $depositor_data = AuthModel::getUserDataByID($rec['user_id']);
                                            $depositor_demographic_data = AuthModel::getUserDemographicData($rec['user_id']);
                                    ?>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0" align="left">
                                                        <?php echo Core::render($rec["contract_reference_no"]);?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <?php echo !empty($rec["contract_modified_at"]) ? Model::dateTimeFromUTC('Y-m-d',$rec["contract_modified_at"]) : Model::dateTimeFromUTC('Y-m-d',$rec["contract_modified_at"]);?>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0" align="left">
                                                        <?php echo Core::render($depositor_data["name"]);?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0" align="left">
                                                        <?php echo Core::render($depositor_demographic_data["province"]);?>
                                                    </h6>
                                                </td>
                                                <td data-order="<?php echo $rec["offered_amount"];?>">
                                                    <h6 class="mb-0" style="text-transform:capitalize;">
                                                        <?php
                                                        echo Core::render($rec["currency"] .' '.number_format($rec['offered_amount']));
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="mb-0">
                                                            <?php
                                                                $product = RequestsModel::getProductByID($rec["product_id"]);
                                                                echo !empty($product) ? Core::render($product['description']) : '';
                                                            ?>
                                                        </h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0" style="text-transform:capitalize;">
                                                        <?php
                                                         if ($rec['term_length_type'] == "HISA"){
                                                             echo '-';
                                                         }else {
                                                             echo Core::render($rec["term_length"] . ' ' . ucwords(strtolower($rec['term_length_type'])));
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
                                                <td class="text-center">
                                                    <a href="c_details?id=<?php echo Core::urlValueEncrypt($rec['offer_id']); ?>&&rqid=<?php echo Core::urlValueEncrypt($rec['depositor_request_id']); ?>&&history" class="btn btn-primary btn-block mmy_btn">View</a>
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

                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3"class="my_h">OFFERS HISTORY <span class="badge bg-blue badge-pill offers-history-count">0</span></td>
                                    <td class="text-right"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-12">

                            <div class="table-responsive">

                                <table class="table custom-data-tables table-condensed">
                                    <thead>
                                        <tr role="row">
                                            <th>Request ID</th>
                                            <th>Date</th>
                                            <th>Depositor Name</th>
                                            <th>Province</th>
                                            <th>Request amount</th>
                                            <th>Product</th>
                                            <th>Investment Period</th>
                                            <th>Interest Rate %</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ( !empty($offers_history['data'])){
                                        foreach ($offers_history['data'] as $rec){
                                            $depositor_data = AuthModel::getUserDataByID($rec['user_id']);
                                            $depositor_demographic_data = AuthModel::getUserDemographicData($rec['user_id']);
                                    ?>
                                        <tr>
                                            <td>
                                                <h6 class="mb-0" align="left">
                                                    <?php echo Core::render($rec["request_reference_no"]);?>
                                                </h6>
                                            </td>
                                            <td>
                                                <?php echo !empty($rec["offer_modified_date"]) ? Model::dateTimeFromUTC('Y-m-d',$rec["offer_modified_date"]) : Model::dateTimeFromUTC('Y-m-d',$rec["created_date"]);?>
                                            </td>
                                            <td>
                                                <h6 class="mb-0" align="left">
                                                    <?php echo Core::render($depositor_data["name"]);?>
                                                </h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0" align="left">
                                                    <?php echo Core::render($depositor_demographic_data["province"]);?>
                                                </h6>
                                            </td>
                                            <td data-order="<?php echo $rec["amount"];?>">
                                                <h6 class="mb-0" style="text-transform:capitalize;">
                                                    <?php
                                                    echo Core::render($rec["currency"] .' '.ucwords(strtolower($rec['amount'])));
                                                    ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0">
                                                        <?php
                                                        $product = RequestsModel::getProductByID($rec["product_id"]);
                                                        echo !empty($product) ? Core::render($product['description']) : '';
                                                        ?>
                                                    </h6>
                                                </div>
                                            </td>
                                            <td>
                                                <h6 class="mb-0" style="text-transform:capitalize;">
                                                    <?php
                                                     if ($rec['term_length_type'] == "HISA"){
                                                             echo '-';
                                                         }else {
                                                         echo Core::render($rec["term_length"] . ' ' . ucwords(strtolower($rec['term_length_type'])));
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
                                                    echo Core::render(ucwords(strtolower(str_replace("_"," ",$rec['offer_status']))));
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="h_details?id=<?php echo Core::urlValueEncrypt($rec['offer_id']); ?>&&rqid=<?php echo Core::urlValueEncrypt($rec['depositor_request_id']); ?>" class="btn btn-primary btn-block mmy_btn">View</a>
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

        <script>
            $(".contract-history-count").html(<?php echo json_encode(!empty($contract_history['data']) ? count($contract_history['data']) : 0);?>);
            $(".offers-history-count").html(<?php echo json_encode( !empty($offers_history['data']) ? count($offers_history['data']) : 0);?>);
        </script>
    <?php
    require_once "footer.php";
}
?>
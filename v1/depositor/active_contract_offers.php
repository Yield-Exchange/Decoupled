<?php
session_start();
require_once "header.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    require_once "sidebar.php";

    Core::activityLog("Depositor Active Contact Offers");

    if (isset($_GET["rqid"])) {
        $req_id = Core::urlValueDecrypt($_GET["rqid"]);
        $data=BankModel::getAllOffersByRequestID($req_id,true);
?>

<script>
    $(document).ready(function() {
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    });

    $(document).ready(function () {
        $('.custom-data-tables').DataTable({
            "order": [[ 0, "desc" ]],
            "scrollX": true,
            "pageLength": 50
        });
        $('.dataTables_length').addClass('bs-select');
    });
</script>

    <!-- Main content -->
    <div class="content-wrapper">
    <!-- Content area -->
    <div class="content">
        <br><!-- Main charts -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="table-responsive" >
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b" >ALL</span> OFFERS</td>
                                    <td class="text-right">
                                        <button onclick="window.open('review_offers_print?rqid=<?php echo $_GET["rqid"]; ?>', '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');" class="btn btn-primary">Print</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap tbl_index custom-data-tables">
                            <thead>
                            <tr>
                                <th>Institution</th>
                                <th>Interest Rate %</th>
                                <th>Min Amount</th>
                                <th>Max Amount</th>
                                <th>Awarded Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($data)){
                                foreach ( $data as $rec ) {
                                    $bid = $rec['id'];

                                    $bank_data = AuthModel::getUserDataByID($rec['invited_user_id']);
                                    $contract_data = BankModel::getBankBidContract($bid);
                                    $depositor_request = RequestsModel::getRequestByID($rec['depositor_request_id']);
                            ?>
                                    <tr style="<?php echo !empty($contract_data) && $contract_data['status']=='ACTIVE' ? 'background:#DEF0F5': '' ?>">
                                        <td><?php echo Core::render($bank_data["name"]); ?>
                                        </td>
                                        <td><?php echo BankModel::getInterest($rec["interest_rate_offer"]);?>
                                        </td>
                                        <td><h6 class="mb-0"><?php echo Core::render($depositor_request['currency']).' '.number_format($rec["minimum_amount"]); ?></h6>
                                        </td>
                                        <td><h6 class="mb-0"><?php echo Core::render($depositor_request['currency']).' '.number_format($rec["maximum_amount"]); ?></h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0"><?php echo !empty($contract_data['offered_amount']) && !in_array($contract_data['status'], ["WITHDRAWN"]) ? Core::render($depositor_request['currency']).' '.number_format($contract_data['offered_amount']) : '-';?></h6>
                                        </td>
                                        <td>
                                            <a href="contract_offer_view?id=<?php echo Core::urlValueEncrypt($rec['id']).'&&page='.($current_file=='active_contract_offers' ? 'comp' : ($current_file=='pending_contract_offers' ? 'contract' : 'exp'));?>" class="btn btn-primary btn-block" style="color:white">View</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <br /><br />
                    <div class="row mt-2 pb-2">
                        <div class="col-md-12">
                            <div class="row ml-3">
                                <div class="col-md-2">
                                    <a href="<?php echo $current_file=='active_contract_offers' ? 'comp' : ($current_file=='pending_contract_offers' ? 'contract' : 'exp');?>" class="btn btn-block" style="border:1px solid gainsboro">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
require_once "footer.php";

    }else {
        echo "<script>location='comp'</script>";
    }
}
?>
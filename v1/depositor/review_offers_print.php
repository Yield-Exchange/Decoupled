<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";
require_once "../config/AuthModel.php";
require_once "../config/RequestsModel.php";

if ( AuthModel::isLoggedIn() ) {
    if(!empty($_GET['rqid'])){
        Core::activityLog("Depositor History -> Review Offers -> Print");

        $r_qid=Core::urlValueDecrypt($_GET['rqid']);
        $f_history=$_GET['history'];
        $post_request = DepositorModel::getPostRequestsByID($r_qid);

        if(!empty($post_request)){
            if (!empty($f_history)) {
                $sql = "SELECT o.*,i.invited_user_id FROM offers o,invited i WHERE i.id = o.invitation_id  AND i.depositor_request_id = '$r_qid' AND offer_status IN('REJECTED','WITHDRAWN','ABANDONED','LOST','EXPIRED')";
            }else{
                $sql = "SELECT o.*,i.invited_user_id FROM offers o,invited i WHERE i.id = o.invitation_id  AND i.depositor_request_id = '$r_qid' AND offer_status NOT IN('REJECTED','WITHDRAWN','ABANDONED','LOST','EXPIRED')";
            }

            $data = db::getrecords($sql);
            $dep_rec = AuthModel::getUserDataByID($post_request['user_id']);
            $user_demographic_user_data = AuthModel::getUserDemographicData($post_request['user_id']);

    $total_offered_amount = 0;
    $sm_rate = 0;
    $av_rate=0;

    if ( !empty($data) ) {
        foreach ($data as $rec) {
            $bid = $rec["id"];
            $contract = BankModel::getBankBidContract($bid);
            $offered_amount = isset($contract['offered_amount']) ? $contract['offered_amount'] : 0;
            $rate = $rec["interest_rate_offer"];
            $sm_rate += ( $offered_amount * $rate / 100 );
            $total_offered_amount += $offered_amount;
        }
        $av_rate = $total_offered_amount > 0 ? ($sm_rate/$total_offered_amount) * 100 : 0;
    }
?>

<!doctype html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Review Offers</title>
        <meta name="description" content="Review Offers">
        <meta name="author" content="Review Offers">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <style>
            .table td, .table th{
                padding: .40rem 0.35rem!important;
            }
        </style>
    </head>

    <body onfocus="window.close()">
        <div id="printIt" class="container-fluid">
            <table class="table table-borderless table-condensed">
                <thead>
                    <tr>
                        <td><?php echo Core::render($dep_rec['name']); ?></td>
                        <td>Date &amp; Time Printed: <?php echo date('Y-m-d h:i:s');?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Request ID: <?php echo Core::render($post_request['reference_no']);?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Product: <?php
                                $product = RequestsModel::getProductByID($post_request['product_id']);
                                echo !empty($product) ? Core::render($product['description']) : '';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Term:
                            <?php
                                if ($post_request['term_length_type'] == "HISA"){
                                    echo '-';
                                }else {
                                    echo Core::render($post_request['term_length'] . ' ' . ucwords(strtolower($post_request['term_length_type'])));
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Requested amount: <?php echo Core::render($post_request['currency'])." ".$post_request['amount'];?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Wtd. Avg. Interest:: <?php echo number_format($av_rate,3).'%';?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Total offered: <?php echo Core::render($post_request['currency'])." ".number_format(str_replace(',','',$total_offered_amount));?></td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <h3>All offers</h3>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Institution</th>
                        <th>Contract Date</th>
                        <th>Interest Rate %</th>
                        <th>Max Amount</th>
                        <th>Min Amount</th>
                        <th>Awarded Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ( !empty($data) ){

                    foreach ($data as $rec) {

                        $req_id = $r_qid;
                        $bank_id = $rec["invited_user_id"];
                        $bid = $rec["id"];
                        $bank = AuthModel::getUserDataByID($bank_id);

                        $contract = $contract = BankModel::getBankBidContract($bid);
                        $offered_amount = isset($contract['offered_amount']) ? $contract['offered_amount'] : 0;

                        $rate = $rec["interest_rate_offer"];
                        $contract_ref = $contract['reference_no'];
                ?>
                    <tr>
                        <td><?php echo Core::render($bank["name"]); ?></td>
                        <td><?php echo !empty($contract) ? Model::dateTimeFromUTC('Y-m-d h:i a',$contract['deposit_date'],$user_demographic_user_data['timezone']).' '.Core::render($user_demographic_user_data['timezone']) : '-';?></td>
                        <td><?php echo BankModel::getInterest($rate);?></td>
                        <td><?php echo Core::render($post_request['currency']).' '.number_format($rec["maximum_amount"],2); ?></td>
                        <td><?php echo Core::render($post_request['currency']).' '.number_format($rec["minimum_amount"],2); ?></td>
                        <td><?php echo !empty($contract['offered_amount'] && $contract['status']!="WITHDRAWN") ? Core::render($post_request['currency']).' '.number_format($offered_amount) : '-'; ?></td>
                    </tr>
                <?php
                    }
                }
                ?>
                </tbody>
            </table>

        </div>
        <script>
            printDiv('printIt');
            function printDiv(divName) {
                let printContents = document.getElementById(divName).innerHTML;
                let originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();

                document.body.innerHTML = originalContents;
            }
        </script>
    </body>
</html>

<?php
        }
    }
}
?>
<?php
session_start();
include "../config/db.php";
include __DIR__."/../config/Model.php";
require_once __DIR__."/../config/BankModel.php";
require_once __DIR__."/../config/DepositorModel.php";
require_once __DIR__."/../config/AuthModel.php";
require_once __DIR__."/functions.php";
require_once "timezone.php";
require_once __DIR__."/../config/RequestsModel.php";
require_once BASE_DIR."/includes/shared_logic.php";

use Dompdf\Dompdf;

//if (isset($_GET['FITerms']) && AuthModel::isLoggedIn()){
//    BankModel::generateNonInvitedFITerms(AuthModel::getUserdata());
//}

//reset password
if ( isset($_POST['forget_pwd']) && isset($_POST['recaptcha']) ){
    $as = $_POST['_as'];
    if ($as == "fi") {
        AuthModel::recoverPassword("bank");
    } else {
        AuthModel::recoverPassword("depositor");
    }
}

if ( isset($_GET['resend_pwd']) && isset($_GET['email']) ){
    $as = $_GET['env'];
    if ($as == "fi") {
        AuthModel::resendRecoverPasswordLink("bank");
    } else {
        AuthModel::resendRecoverPasswordLink("depositor");
    }
}
//login
if (isset($_POST['login'])) {
    AuthModel::depositorOrBankLogin("bank");
}

//signup
if (isset($_POST['signup'])) {
    AuthModel::depositorAndBankRegistration();
}

//pin
if (isset($_POST['pin'])) {
    AuthModel::verifyPin("bank");
}

//reset
if (isset($_GET['reset'])) {
    if ($_GET['reset'] == 1) {
        echo "<script>location='login'</script>";
    }
}

if (isset($_POST['bidding'])) {
    Core::activityLog("Bank Submit offer for a request");
    makeBankOffer("create");
}

if (isset($_POST['bidding_update'])) {
    Core::activityLog("Bank Submit update offer for a request");
    makeBankOffer("edit");
}

if(isset($_POST['non_fi_details'])){
 Core::alert("Details Saved","Your details submitted successfully","success","my_bids");exit();
}

if (isset($_GET['reset_values1'])) {
    if ($_GET['reset_values1'] == 1) {
        unset($_SESSION["val1"]);
        unset($_SESSION["val2"]);
        unset($_SESSION["val3"]);
        unset($_SESSION["val4"]);
        unset($_SESSION["val5"]);
        unset($_SESSION["val8"]);
        unset($_SESSION["val9"]);
        unset($_SESSION["val10"]);
        unset($_SESSION["val11"]);
        unset($_SESSION["val12"]);
        unset($_SESSION["val17"]);
        unset($_SESSION["val18"]);
        unset($_SESSION["dep_id"]);

        echo "<script>location='bids'</script>";
    }
}

if (isset($_GET['logout'])) {
    Core::activityLog("Bank logged out");
    AuthModel::logout();
}

if (isset($_POST['update_date'])) {
    AuthModel::authCsrfToken();

    global $notification_email;

    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];
    $user_demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);

    $rqid = $_POST['rqid'];
    $cntid = $_POST['cntid'];
    $dop = Model::dateTimeToUTC("Y-m-d H:i:s",$_POST['dop']." 23:59:59",$user_demographic_user_data['timezone']);
    $time_now = Model::utcDateTime();

    $gicnumber=$_POST['gicnumber'];

    $contract_exists_for_bank = db::getRecord("select o.* from deposits c, offers o, invited i where i.id = o.invitation_id AND i.depositor_request_id='$rqid' AND c.id='$cntid' AND i.invited_user_id='$user_id'");
    $maturitydate=NULL;
    $product_term_type=db::getCell("SELECT term_length_type FROM depositor_requests WHERE id='$rqid'");
    if ($product_term_type != "NONE" ){
        $maturitydate=Model::dateTimeToUTC("Y-m-d H:i:s",$_POST['mdate']." 23:59:59",$user_demographic_user_data['timezone']);
    }

    if (count($contract_exists_for_bank) > 0) {

        RequestsModel::archiveTable($cntid, "deposits","COMPLETED");
        db::preparedQuery("update deposits SET gic_start_date=?, gic_number=?, maturity_date=?, status=? WHERE id=?", array("ssssi",$dop,$gicnumber,$maturitydate,'ACTIVE',$cntid));

        RequestsModel::archiveTable($contract_exists_for_bank['id'], "offers","COMPLETED");
        db::preparedQuery("update offers SET offer_status=? WHERE id=?",array('si','COMPLETED',$contract_exists_for_bank['id']));

        $depositor_request = RequestsModel::getRequestByID($rqid);
        if (!empty($depositor_request)) {
            $offers = db::getCell("SELECT COUNT(o.*) FROM offers o, invited i WHERE i.id = o.invitation_id AND i.depositor_request_id = '$rqid' AND o.offer_status IN('ACTIVE','SELECTED')");
            if ($offers == 0){
                RequestsModel::archiveTable($rqid, "depositor_requests", "COMPLETED");
                db::query("UPDATE depositor_requests SET request_status='COMPLETED' WHERE id='$rqid'");
            }

            Core::activityLog("Bank Pending Deposits -> View -> GIC start date");
            $notification = "Deposit ID " . $depositor_request['reference_no'] . " fund received date has been provided successfully and therefore marked as complete";
            $user_id = $depositor_request['user_id'];
            Core::sendAdminEmail("Contract marked as complete", $notification);

            $depositor_user_data = AuthModel::getUserDataByID($user_id);
            Core::sendMail("Contract marked as complete",$depositor_user_data['email'], $notification);

            $logged_in_user = $user_data;
            $logged_in_user_id = $logged_in_user['id'];

            $query = "INSERT into notifications (`type`, `details`, `date_sent`, `user_id`, `sent_by`,`status`) VALUES ('CONTRACT DEPOSIT DATE UPDATED','$notification','$time_now','$user_id','$logged_in_user_id','ACTIVE')";
            db::query($query);
        }
    }

    if ( $user_data['is_non_partnered_fi'] ) {
        Core::alert("", "Your deposit has been created, onboard with Yield Exchange to see your active deposits.", "success", "comp", false, false);
    }else{
        Core::alert("", "Your deposit has been created", "success", "comp", false, false);
    }
//    echo "<script>location='comp'</script>";
}

//if ( isset($_POST['saverating']) ) {
//
//    $short1 = $_POST['short'];
//    $long1 = $_POST['long'];
//
//    $user_data = AuthModel::getUserdata();
//    $user_id = $user_data['id'];
//
//    $credit_rating = db::getRecord("SELECT * FROM credit_rating WHERE user_id='$user_id'");
//    if (empty($credit_rating)){
//        db::query("INSERT INTO `credit_rating`(`user_id`, `credit_rating_type_id`, `deposit_insurance_id`) VALUES ('$user_id','$short1','$long1')");
//    }else{
//        db::query("UPDATE `credit_rating` SET `credit_rating_type_id`='$short1',`deposit_insurance_id`='$long1' WHERE user_id='$user_id'");
//    }
//    Core::alert("Account update","Updating account data was successful","success","account_settings");
//}

if ( isset($_GET['download_report_csv']) ){
    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];
    $demographic_user_data = AuthModel::getUserDemographicData($user_id);

    $from = $_REQUEST['date_from'];
    $to = $_REQUEST['date_to'];

    $sql = "SELECT c.reference_no as ReferenceNo, u.name as DepositName, dr.currency,dr.amount, c.gic_start_date, o.interest_rate_offer, c.maturity_date, dr.user_id, dr.product_id, dr.term_length, dr.term_length_type, dr.lockout_period_days FROM 
                    deposits c, offers o, invited i, depositor_requests dr, users u WHERE
                  c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id=u.id AND i.invited_user_id = '$user_id' AND c.status IN('ACTIVE')";
    if ( !empty($from) && !empty($to) ) {
        $utc_from = Model::dateTimeToUTC('Y-m-d',$from,$demographic_user_data['timezone']);
        $utc_to = Model::dateTimeToUTC('Y-m-d',$to,$demographic_user_data['timezone']);
        $sql .=  " AND DATE(c.gic_start_date) BETWEEN '" . $utc_from . "' AND '" . $utc_to . "'";
    }

    $sql.=" ORDER BY c.reference_no DESC";
    $results = db::getrecords($sql);

    $body="";
    if (!empty($results)) {
        foreach ($results as $row) {
            $depositor_data = AuthModel::getUserDemographicData($row['user_id']);
            $product = RequestsModel::getProductByID($row["product_id"]);

            $body.=' <tr>
                        <td>'.$row["ReferenceNo"].'</td>
                        <td>'.$row["gic_number"].'</td>
                        <td>'.$row["DepositName"].'</td>
                        <td>'.$depositor_data["province"].'</td>
                        <td>'.(!empty($product) ? Core::render($product['description']) : '').'</td>
                        <td>'.( $row['term_length_type']!="HISA" ? Core::render($row['term_length'].' '.strtolower($row['term_length_type'])) : '-').'</td>
                        <td>'.(!empty($row["lockout_period_days"]) ? Core::render($row["lockout_period_days"]).' days' : '').'</td>
                        <td>'.$row["currency"].' '.number_format($row["amount"]).'</td>
                        <td>'.BankModel::getInterest($row["interest_rate_offer"]).'</td>
                        <td>'.Model::dateTimeToUTC('Y-m-d',$row["gic_start_date"],$demographic_user_data['timezone']).'</td>
                        <td>'.(!empty($row["maturity_date"]) ? Model::dateTimeToUTC('Y-m-d',$row["maturity_date"],$demographic_user_data['timezone']) : "").'</td>
                    </tr>';
        }
    }

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml('<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reports</title>
    <meta name="description" content="Review Offers">
    <meta name="author" content="Review Offers">
    <style>
        table td,table th{
            width: 20%;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        div,table{
            width: 100%;
        }
        table{
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
<div>
    <h3>Reports</h3>
    <table style="100%">
        <thead>
        <tr>
            <th>Contract Number</th>
            <th>GIC Number</th>
            <th>Customer Name</th>
            <th>Province</th>
            <th>Product</th>
            <th>Term</th>
            <th>Lockout/Notice Period</th>
            <th>Amount</th>
            <th>Interest Rate</th>
            <th>Gic Start Date</th>
            <th>Maturity Date</th>
        </tr>
        </thead>
        <tbody>'.$body.'</tbody>
    </table>

</div>
</body>
</html>');

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream(" Reports".date("Ymdhi").".pdf");

    exit;
}

function cleanData(&$str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) {
        $str = '"' . str_replace('"', '""', $str) . '"';
    }
}

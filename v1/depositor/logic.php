<?php
session_start();
include "../config/db.php";
include __DIR__."/../config/Model.php";
require_once __DIR__."/../config/BankModel.php";
require_once __DIR__."/../config/DepositorModel.php";
require_once __DIR__."/../config/AuthModel.php";
require_once __DIR__."/../config/RequestsModel.php";
require_once __DIR__."/functions.php";
require_once __DIR__."/timezone.php";
require_once __DIR__."/../includes/shared_logic.php";

use Dompdf\Dompdf;

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
    AuthModel::depositorOrBankLogin();
}

//pin
if (isset($_POST['pin'])) {
    AuthModel::verifyPin();
}

//signup
if (isset($_POST['signup'])) {
    AuthModel::depositorAndBankRegistration();
}

//reset
if (isset($_GET['reset'])) {
    if ($_GET['reset'] == 1) {
        echo "<script>location='".BASE_URL."/login'</script>";
    }
}

//Post req
if (isset($_POST['submit_post_request'])) {
    RequestsModel::postRequest();
}

if (isset($_GET['reset_values'])) {
    if ($_GET['reset_values'] == 1) {
        resetRequestValues();
        echo "<script>location='p_req'</script>";
    }
}

function resetRequestValues(){
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
    unset($_SESSION["term_type"]);
    unset($_SESSION["lockout_period"]);
    unset($_SESSION["anonymous"]);
    unset($_SESSION['new_invited_fi']);
    unset($_SESSION['too']);
}

function sendInvites($pref)
{
    if (isset($_SESSION['too'])) {
        if (!empty($_SESSION['too'])) {

            $to = $_SESSION['too'];
            if (count($to) > 0) {


                $user_data = AuthModel::getUserdata();
                $user_id = $user_data['id'];

                $query = "SELECT * from depositor_requests where reference_no='$pref' AND user_id='$user_id'";
                $request = db::getRecord($query);

                if (!empty($request)) {
                        $depositor_request_id = $request['id'];
                        db::getCell( "UPDATE invited SET `invitation_status`='UNINVITED' WHERE depositor_request_id='$depositor_request_id' AND invited_user_id NOT IN('".implode("','",$to)."')");
                        foreach ($to as $id) {
                            $invited_exits = db::getCell( "SELECT COUNT(*) FROM invited WHERE depositor_request_id='$depositor_request_id' AND invited_user_id='$id'");
                            if ($invited_exits == 0) {

                                $user_bank = AuthModel::getUserDataByID($id);
                                $bank_preference = Core::getUserPreferences($id);

                                $usr_demographic_data = AuthModel::getUserDemographicData($user_id);
                                $timezone = $usr_demographic_data['timezone'];
                                //$date_now = Model::utcDateTime();
                                $datetime_from_utc = Model::dateTimeFromUTC('Y-m-d h:i a T',$request['closing_date_time'],$timezone);

                                $subject = "You have a deposit opportunity";
                                $header ="You have been invited to a request from ".$user_data['name'];
                                $toEmail = $user_bank['email'];
                                $message = "<p><center>".$user_data['name']." has invited you to a deposit of ".$request['currency']." ".$request['amount'].". If you are interested in putting in an offer, please respond before ".$datetime_from_utc."</center></p>";
                                $link = BASE_URL."/login?a=fi";

                                if (empty($bank_preference) || $bank_preference['mute_notification'] == 1) {
                                    if ( !$user_bank['is_non_partnered_fi'] ){
                                        Core::sendMail($subject, $toEmail, $message,'fi',false,false,[['linkName'=>'Sign in','link'=>$link]],true,$logo_position="top",$header);
                                    }
                                }

                                if ( !empty($_SESSION['new_invited_fi']) && is_array($_SESSION['new_invited_fi']) && $user_bank['is_non_partnered_fi'] && in_array($user_bank['id'] ,$_SESSION['new_invited_fi']) ){

                                    $account_manager = trim($user_bank['account_manager']); //$your_name
                                    $your_name = trim($user_bank['inviter_name']); //!empty($_SESSION['new_invited_fi_account_managers']) ? @$_SESSION['new_invited_fi_account_managers'][$user_bank['id']]['your_name'] : '';
                                    
                                    if($your_name != $user_data['name']){
                                        //$subject = "Invitation from ".$your_name.' '.'at '.$user_data['name'];
                                        $subject = $your_name.' at '.trim($user_data['name']).' has invited you to join Yield Exchange';
                                        $header =  $your_name.' has invited you to join Yield Exchange';
                                    } else{
                                        //$subject = "Invitation from ".$user_data['name'];
                                        $subject = trim($user_data['name']).' has invited you to join Yield Exchange.';
                                        $header =  trim($user_data['name']).' has invited you to join Yield Exchange.';
                                    }

                                    $depositor_demographic_data = AuthModel::getUserDemographicData($user_data['id']);
                                        $message = "Hi ".$account_manager.",<br/>
                                        <p>I have a deposit that I am looking for rates on and I'm inviting you to participate in this request through Yield Exchange.</p>
                                        <p>Yield Exchange is a digital platform that allows me to negotiate with Canadian Financial Institutions like you, easily and efficiently.</p>
                            
                                        <p>Please contact me if you have any questions regarding this invitation, otherwise I look forward to your response on my Deposit.</p>
                            
                                        <p>Thanks,</p>
                            
                                        <p>".$your_name."</p>
                                        <p>".$user_data['email']."</p>
                                        <p>".$depositor_demographic_data['telephone']."</p>";

                                        $link = BASE_URL.'/accept_invitation?token='.Core::urlValueEncrypt($user_bank['id']);
                                        Core::sendMail($subject, $toEmail, $message,'',false,false,[['linkName'=>'View Invitation','link'=>$link]],false,'bottom', $header);

                                        db::query("UPDATE users SET is_temporary=0 WHERE id='".$user_bank['id']."'");
                                }

                                $notification = $pref . " A Request has been Posted";
                                $date_now = Model::utcDateTime();

                                $logged_in_user = AuthModel::getUserdata();
                                $logged_in_user_id = $logged_in_user['id'];

                                $query = "INSERT into notifications (type,details,date_sent,user_id,sent_by,status)VALUES ('POST REQUEST','$notification','$date_now','$id','$logged_in_user_id','ACTIVE')";
                                db::query($query);

                                $query1 = "INSERT into invited (invitation_status,invitation_date,depositor_request_id,invited_user_id)VALUES ('INVITED','$date_now','$depositor_request_id','$id')";
                                db::query($query1);
                            }
                        }

                }

            }

        }

        unset($_SESSION['too']);
        unset($_SESSION['new_invited_fi_account_managers']);
        unset($_SESSION['new_invited_fi']); // unset them
        unset($_SESSION['CACHE_INVITES'] );
    }
}

if(isset($_GET['cancel_request_values'])){
    resetRequestValues();
    $redirect = $_GET['cancel_request_values'];
    echo "<script>location='$redirect'</script>";
}

if (isset($_GET['confirm_values'])) {
    if ($_GET['confirm_values'] == 1) {

        if (isset($_GET['send_invites'])) {
            if (!empty($_GET['to'])) {
                $_SESSION['too'] = json_decode(base64_decode($_GET['to']));
                echo "<script>location='confirm'</script>";
            //RequestsModel::saveRequest();
            }
        }

    }
}

if( isset($_GET['confirm_all']) && $_GET['confirm_all'] =="all" ){
    RequestsModel::saveRequest();
}


if (isset($_POST['submit_post_request1'])) {
    AuthModel::authCsrfToken();

    if (AuthModel::isLoggedIn()) {
        $lockout_period = $_POST['lockout_period'];
        $_SESSION["lockout_period"] = $lockout_period;

        $term_type = $_POST['term_type'];
        $_SESSION["term_type"] = $term_type;
        $id = $_POST['id'];

        $_SESSION["dep_id"] = $id;
        $deposit_refrence = $_POST['deposit_refrence'];
        $_SESSION["val1"] = $deposit_refrence;
        $product = $_POST['product'];

//        if (strpos($product, 'Cashable') !== false) {
//            $product = $lockout_period . " days " . $product;
//        }

        $_SESSION["val2"] = $product;
        $deposit_currency = $_POST['deposit_currency'];
        $_SESSION["val3"] = $deposit_currency;

        $deposit_amount = $_POST['deposit_amount'];
        $_SESSION["val4"] = $deposit_amount;

        $deposit_start = $_POST['deposit_start'];
        $_SESSION["val5"] = $deposit_start;

        $month = $_POST['month'];
        $_SESSION["val8"] = $month;

        $invest_payment = $_POST['invest_payment'];
        $_SESSION["val9"] = $invest_payment;

        $special_institute = $_POST['special_institute'];
        $_SESSION["val10"] = $special_institute;

        if (isset($_POST['closing'])) {
            $bid_opening = $_POST['closing'];
        } else {
            $bid_opening = "";
        }

        $_SESSION["val11"] = $bid_opening;
        if (isset($_POST['chk1'])) {
            $chk1 = $_POST['chk1'];
        } else {
            $chk1 = "";
        }

        $_SESSION["val12"] = $chk1;

        if (isset($_POST['ask'])) {
            $Ask_Rate = $_POST['ask'];
        } else {
            $Ask_Rate = "";
        }

        $_SESSION["val17"] = $Ask_Rate;

        if (isset($_POST['dep_ins'])) {
            $Dep_insu = $_POST['dep_ins'];
        } else {
            $Dep_insu = "";
        }

        $_SESSION["val18"] = $Dep_insu;

        if (isset($_POST['anonymous'])) {
            $_SESSION["anonymous"] = $_POST['anonymous'];
        }
    }
    echo "<script>location='invites?editing=1'</script>";
}

if (isset($_GET['reset_values1'])) {
    if ($_GET['reset_values1'] == 1) {
        $id = $_SESSION["dep_id"];

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
        unset($_SESSION["term_type"]);
        unset($_SESSION["lockout_period"]);
        unset($_SESSION["anonymous"]);
        unset($_SESSION['too']);

        $path = 'edit_request?id=' . $id;

        echo "<script>location='$path'</script>";
    }
}

if (isset($_GET['logout'])) {
    Core::activityLog("Depositor logged out");
    AuthModel::logout();
}

if (isset($_GET['confirm_values1'])) {
    if ($_GET['confirm_values1'] == 1) {
        if (isset($_GET['send_invites'])) {
            if (!empty($_GET['to'])) {

                $_SESSION['too'] = json_decode(base64_decode($_GET['to']));
                echo "<script>location='e_confirm'</script>";

            }
        }
    }
}

if( isset($_GET['confirm_all2']) && $_GET['confirm_all2'] =="all" ){
    RequestsModel::postEditRequest();
}

if ( isset($_GET['del_id']) ) {
    if (AuthModel::isLoggedIn()) {
        Core::activityLog("Depositor Review Offers -> Withdraw Request");
        $id = Core::urlValueDecrypt($_GET['del_id']);

        $user_data = AuthModel::getUserdata();
        $user_id = $user_data['id'];

        $deposit_request = RequestsModel::getRequestByID($id,true);
        if ( !empty($deposit_request) && $deposit_request['request_status'] == "ACTIVE"){
            RequestsModel::archiveTable($id, "depositor_requests","WITHDRAWN");
            db::query("UPDATE depositor_requests SET request_status='WITHDRAWN' where id='$id'");

            $invitations = db::getRecords("SELECT * FROM `invited` WHERE depositor_request_id = '$id'");
            foreach ($invitations as $invitation) {
                $invitation_id = $invitation['id'];

                $bank = db::getRecord(" SELECT u.* FROM invited i, users u WHERE u.id = i.invited_user_id AND i.id='$invitation_id'");
                $toEmail = $bank['email'];
                $subject = "Request Withdrawn";
                $message = "Request id " . $deposit_request['reference_no'] . " has been withdrawn.";
                Core::sendMail($subject, $toEmail, $message);

                $offer_id = db::getCell("SELECT id FROM offers WHERE invitation_id='$invitation_id' ");
                if ( !empty($offer_id) ){
                    RequestsModel::archiveTable($offer_id, "offers", "REQUEST_WITHDRAWN");
                    db::query("UPDATE offers SET offer_status='REQUEST_WITHDRAWN' WHERE invitation_id='$invitation_id' ");
                }
            }
        }
    }
    echo "<script>location='bids'</script>";
}

if ( isset($_GET['app']) ) {
    if (AuthModel::isLoggedIn()) {
        $id = $_GET['app'];
        RequestsModel::completeContract($id);
        echo "<script>location='comp'</script>";
    }
}

if (isset($_GET['abandon_contract'])){
    if (AuthModel::isLoggedIn()) {
//        abandonRequestContract();
        echo "<script>location='contract'</script>";
    }
}

if ( isset($_GET['reject']) || isset($_GET["cancel"]) ) {
    if (AuthModel::isLoggedIn()) {
        $id = isset($_GET['reject']) ? $_GET['reject'] : $_GET["cancel"];
        RequestsModel::rejectContract($id);
        echo "<script>location='contract'</script>";
    }
}

//update or insert deposit ratings start
if (isset($_POST['saveratingdeposit'])) {
    Core::activityLog("Depositor Saving Credit rating and deposit insurance Preference");
    $short1 = $_POST['short'];
    $long1 = $_POST['long'];

    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];

    $credit_rating = db::getRecord("SELECT * FROM credit_rating WHERE user_id='$user_id'");
    if (empty($credit_rating)){
        db::query("INSERT INTO `credit_rating`(`user_id`, `credit_rating_type_id`, `deposit_insurance_id`) VALUES ('$user_id','$short1','$long1')");
    }else{
        db::query("UPDATE `credit_rating` SET `credit_rating_type_id`='$short1',`deposit_insurance_id`='$long1' WHERE user_id='$user_id'");
    }

    Core::alert("Account update","Updating account data was successful","success","account_settings");
}

if ( isset($_POST['submitted_offers_data']) ){
    if (AuthModel::isLoggedIn()) {
        Core::activityLog("Depositor Review Offers -> View Offers -> Confirm");
        makeReviewOffers();
        echo "<script>window.location.href='contract'</script>";
    }
}

if ( isset($_POST['remove_submitted_offers_data']) ){
    if (AuthModel::isLoggedIn()) {
        Core::activityLog("Depositor Review Offers -> View Offers -> Abandon");
//        removeReviewOffer();
    }
    ob_clean();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

if ( isset($_GET['download_report_csv']) ){
    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];

    $from=$_REQUEST['date_from'];
    $to=$_REQUEST['date_to'];

    $sql = "SELECT c.gic_number,c.reference_no as ReferenceNo, u.name as BankName, dr.currency, dr.amount, c.gic_start_date, c.maturity_date, o.interest_rate_offer,dr.product_id,dr.term_length,dr.term_length_type,dr.lockout_period_days FROM 
                    deposits c, offers o, invited i, depositor_requests dr, users u WHERE
                  c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND i.invited_user_id=u.id AND dr.user_id='$user_id' AND c.status IN('ACTIVE')";

    $demographic_user_data = AuthModel::getUserDemographicData($user_id);
    if ( !empty($from) && !empty($to) ) {
        $utc_from = Model::dateTimeToUTC('Y-m-d',$from,$demographic_user_data['timezone']);
        $utc_to = Model::dateTimeToUTC('Y-m-d',$to,$demographic_user_data['timezone']);
        $s =  " AND DATE(c.gic_start_date) BETWEEN '" . $utc_from . "' AND '" . $utc_to . "'";
        $sql .= $s;
    }

    $sql.=" ORDER BY c.reference_no DESC";

    $results = db::getrecords($sql);

    $body="";
    if (!empty($results)){
        foreach ($results as $row) {
            $product = RequestsModel::getProductByID($row["product_id"]);

            $body.=' <tr>
                    <td>'.$row["ReferenceNo"].'</td>
                    <td>'.$row["gic_number"].'</td>
                    <td>'.$row["BankName"].'</td>
                    <td>'.(!empty($product) ? Core::render($product['description']) : '').'</td>
                    <td>'.( $row['term_length_type'] == "HISA" ? "-" : Core::render($row['term_length'].' '.strtolower($row['term_length_type']))).'</td>
                    <td>'.(!empty($row["lockout_period_days"]) ? Core::render($row["lockout_period_days"]).' days': '').'</td>
                    <td>'.$row['currency'].' '.number_format($row["amount"]).'</td>
                    <td>'.BankModel::getInterest($row["interest_rate_offer"]).'</td>
                    <td>'.Model::dateTimeToUTC('Y-m-d',$row["gic_start_date"],$demographic_user_data['timezone']).'</td>
                    <td>'.( !empty($row["maturity_date"]) ? Model::dateTimeToUTC('Y-m-d',$row["maturity_date"],$demographic_user_data['timezone']) : "").'</td>
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
            <th>Bank Name</th>
            <th>Product</th>
            <th>Term</th>
            <th>Lockout/Notice Period</th>
            <th>Amount</th>
            <th>Interest Rate</th>
            <th>Start Date</th>
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
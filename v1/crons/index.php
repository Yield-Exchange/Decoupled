<?php
include __DIR__."/../config/db.php";
include __DIR__."/../config/Model.php";
require_once __DIR__."/../config/AuthModel.php";
require_once __DIR__."/../config/RequestsModel.php";

function checkExpiry($request) // when reaching here.. the deposit request already has some offers
{
    
    $total_offers = db::getCell("SELECT COUNT('o.*') FROM offers o, invited i WHERE i.id = o.invitation_id AND i.depositor_request_id = '" . $request['id'] . "'");
    $active_offers = db::getRecords("SELECT o.* FROM offers o, invited i WHERE i.id = o.invitation_id AND i.depositor_request_id = '" . $request['id'] . "' AND o.offer_status IN('ACTIVE')");
    $selected_offers = db::getRecords("SELECT o.* FROM offers o, invited i WHERE i.id = o.invitation_id AND i.depositor_request_id = '" . $request['id'] . "' AND o.offer_status IN('SELECTED')");
    $utc_date_now = new DateTime("now", new DateTimeZone("UTC"));

    /*----------------------------------REQUEST EXPIRY-------------------------------------------------------------*/
    if ($utc_date_now->format('Y-m-d H:i:s') > $request['closing_date_time'] && ($utc_date_now->format('Y-m-d H:i:s') > $request['date_of_deposit'] && $selected_offers==0)){
        //Closing date and time of request has expired AND no offers were selected by the date of deposit

        if ( $request['request_status'] !='EXPIRED' ) {
            RequestsModel::archiveTable($request['id'], "depositor_requests", "EXPIRED");
            db::query("UPDATE depositor_requests SET request_status='EXPIRED' WHERE id='" . $request['id'] . "' AND request_status!='EXPIRED' ");
        }

        // if no offers has been selected at the time of request expiry -> mark all offers as not selected
        $all_offers = db::getRecords("SELECT o.* FROM offers o, invited i WHERE i.id = o.invitation_id AND i.depositor_request_id = '".$request['id']."'");
        foreach ($all_offers as $offer){
            if ($offer['offer_status']=='ACTIVE') {
                RequestsModel::archiveTable($offer['id'], "offers", "NOT_SELECTED");
                db::query("UPDATE offers SET offer_status='NOT_SELECTED' WHERE id='" . $offer['id'] . "'");
            }
        }
    }

    #THIS SCENARIO LOGICALLY IS NEVER MEANT TO HAPPEN // but lets  just leave it here for future reference
//    $expired_offers = db::getRecords("SELECT o.* FROM offers o, invited i WHERE i.id = o.invitation_id AND i.depositor_request_id = '" . $request['id'] . "' AND o.offer_status IN('EXPIRED')");
//    if ($total_offers == $expired_offers && $utc_date_now->format('Y-m-d H:i:s') > $request['closing_date_time'] && $utc_date_now->format('Y-m-d H:i:s') > $request['date_of_deposit']) {
//        //Closing date and time of request has expired AND date of deposit has passed and all offers have expired.
//
//        RequestsModel::archiveTable($request['id'], "depositor_requests", "EXPIRED");
//        db::query("UPDATE depositor_requests SET request_status='EXPIRED' WHERE id='" . $request['id'] . "' ");
//
//        // loop through all invites and check if there was an offer : if not update invited table to have a status DID_NOT_PARTICIPATE
//        $invites = db::getRecords("SELECT * FROM invited WHERE depositor_request_id='" . $request['id'] . "'");
//        foreach ($invites as $invite){
//            $invite_offer = db::exists(" SELECT * FROM offers WHERE invitation_id='".$invite['id']."'");
//            if ($invite['invitation_status']=='INVITED' && empty($invite_offer)){
//                db::query("UPDATE `invited` SET `invitation_status`='DID_NOT_PARTICIPATE' WHERE id='".$invite['id']."'");
//            }
//        }
//    }
    /*----------------------------------END REQUEST EXPIRY-------------------------------------------------------------*/

    if ($utc_date_now->format('Y-m-d H:i:s') > $request['closing_date_time']){
        // If no offer can be sent for a request due to offer closing date and time being PAST

        // loop through all invites and check if there was an offer : if not update invited table to have a status DID_NOT_PARTICIPATE
        $invites = db::getRecords("SELECT * FROM invited WHERE depositor_request_id='" . $request['id'] . "'");
        foreach ($invites as $invite){
            $invite_offer = db::exists(" SELECT * FROM offers WHERE invitation_id='".$invite['id']."'");
            if ($invite['invitation_status']=="INVITED" && empty($invite_offer)) {
                db::query("UPDATE `invited` SET `invitation_status`='DID_NOT_PARTICIPATE' WHERE id='" . $invite['id'] . "'");
            }
        }
    }

    if (!empty($active_offers)) {
        // Check for the actual offer expiry
        foreach ($active_offers as $offer) {
            $utc_date_now = new DateTime("now", new DateTimeZone("UTC"));
            if ($utc_date_now->format('Y-m-d H:i:s') > $offer['rate_held_until']) {
                if ( $offer['offer_status'] != 'EXPIRED' ) {
                    RequestsModel::archiveTable($offer['id'], "offers", "EXPIRED");
                    db::query("UPDATE offers SET offer_status='EXPIRED' WHERE id='" . $offer['id'] . "'");
                }
            }
        }
    }

    $selected_offers = db::getRecords("SELECT o.* FROM offers o, invited i WHERE i.id = o.invitation_id AND i.depositor_request_id = '" . $request['id'] . "' AND o.offer_status IN('SELECTED')");
    if ($selected_offers > 0) {
        //Depositor has selected an offer(s) hence mark the request as completed

        if ( $request['request_status'] != 'COMPLETED' ) {
            RequestsModel::archiveTable($request['id'], "depositor_requests", "COMPLETED");
            db::query("UPDATE depositor_requests SET request_status='COMPLETED' WHERE id='" . $request['id'] . "' ");
        }
    }
}

$requests = db::getRecords("SELECT * FROM depositor_requests WHERE request_status IN('ACTIVE') LIMIT 20");
if (!empty($requests)) {
    foreach ($requests as $request) {
        $r_id = $request['id'];

        $all_offers = db::getCell("SELECT COUNT('o.*') FROM offers o, invited i WHERE i.id = o.invitation_id AND i.depositor_request_id = '$r_id'");

        $utc_date_now = new DateTime("now", new DateTimeZone("UTC"));
        $closing_date_time = $request['closing_date_time'];

        if (($all_offers == 0 && $utc_date_now->format('Y-m-d H:i:s') > $closing_date_time)) {
            if ( $request['request_status'] != 'NO_OFFERS_RECEIVED' ) {
                RequestsModel::archiveTable($request['id'], "depositor_requests", "NO_OFFERS_RECEIVED");
                db::query("UPDATE depositor_requests SET request_status='NO_OFFERS_RECEIVED' WHERE id='" . $request['id'] . "' ");
            }
            db::query("UPDATE `invited` SET `invitation_status`='DID_NOT_PARTICIPATE' WHERE depositor_request_id='".$request['id']."' AND invitation_status != 'DID_NOT_PARTICIPATE'");
        }

        if ($all_offers > 0) {
            checkExpiry($request);
        }

    }
}

$contracts = db::getRecords("SELECT c.*,i.depositor_request_id,i.invited_user_id,dr.date_of_deposit,dr.amount,dr.currency,dr.user_id,dr.closing_date_time,dr.product_id,dr.term_length,dr.term_length_type,o.interest_rate_offer,o.maximum_amount,o.minimum_amount,o.rate_held_until
                                                FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND c.status IN('ACTIVE','PENDING_DEPOSIT') ORDER BY c.reference_no DESC");
if (!empty($contracts)) {
    foreach ($contracts as $contract) {
        $utc_date_now = new DateTime("now", new DateTimeZone("UTC"));
        if ( !empty($contract["maturity_date"]) ) {
            $date = new DateTime($contract["maturity_date"], new DateTimeZone("UTC"));
//            if ($contract['term_length_type'] == "MONTHS") {
//                $date->modify(" +" . $contract['term_length'] . ' month');
//            } else {
//                $date->modify(" +" . $contract['term_length'] . ' day');
//            }

            if ( $date->format('Y-m-d H:i:s') < $utc_date_now->format('Y-m-d H:i:s') ) {
                if ($contract['status'] != 'MATURED') {
                    RequestsModel::archiveTable($contract['id'], "deposits", "MATURED");
                    db::query("UPDATE deposits SET status='MATURED' WHERE id='" . $contract['id'] . "' ");
                }
            }
        } else if ($contract['status']=="PENDING_DEPOSIT"){
            $offer_expiry = new DateTime($contract['rate_held_until'], new DateTimeZone("UTC"));
            if ($offer_expiry->format('Y-m-d H:i:s') < $utc_date_now->format('Y-m-d H:i:s')) {
                // Instead please create an email that will be sent to Sam and Ravi that identifies the contracts that appear to be incomplete based on this logic.
                // Please send us the contract #, contract $, Depositor, FI, and date of deposit.
                // we will need to manually follow up on these for the time being.
//                RequestsModel::archiveTable($contract['id'], "contracts", "INCOMPLETE");
//                db::query("UPDATE contracts SET status='INCOMPLETE' WHERE id='" . $contract['id'] . "' ");

                if ( !$contract['admins_notified'] ) {
                    $bank = AuthModel::getUserDataByID($contract['invited_user_id']);
                    $depositor = AuthModel::getUserDataByID($contract['user_id']);

                    $subject = ENVIRONMENT.", INCOMPLETE DEPOSIT - ID #" . $contract['reference_no'];
                    $message = "The following contract is incomplete.";
                    $message .= "<br/><strong>Deposit ID: </strong>" . $contract['reference_no'];
                    $message .= "<br/><strong>Offered Amount: </strong>" . $contract['currency'] . " " . $contract['offered_amount'];
                    $message .= "<br/><strong>Date of Deposit: </strong>" . $contract['date_of_deposit'];
                    $message .= "<br/><strong>Depositor: </strong>" . $depositor['name'];
                    $message .= "<br/><strong>FI: </strong>" . $bank['name'];

                    Core::sendMail($subject, "ravi@yieldexchange.ca", $message);
                    Core::sendMail($subject, "Sampath@yieldexchange.ca", $message);

                    db::query("UPDATE deposits SET admins_notified=1 WHERE id='" . $contract['id'] . "' ");
                }
            }
        }
    }
}

// send emails
//$emails = db::getRecords("SELECT * FROM `queued_emails` WHERE status IN('PENDING') LIMIT 5");
//if ( !empty($emails) ){
//    foreach ($emails as $email) {
//        $email = (object) $email;
//        $date_time=gmdate('Y-m-d H:i:s');
//        db::query("UPDATE `queued_emails` SET status='SENDING', updated_at='$date_time' WHERE id='$email->id'");
//        if ( Core::sendMailSMTP($email->to,base64_decode($email->message),$email->subject, true) ){
//            db::query("UPDATE `queued_emails` SET status='SENT', updated_at='$date_time' WHERE id='$email->id'");
//        }else{
//            db::query("UPDATE `queued_emails` SET status='FAILED', updated_at='$date_time' WHERE id='$email->id'");
//        }
//    }
//}
//SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id
$utc_date_now = new DateTime("now", new DateTimeZone("America/Vancouver"));
$utc_date_now_f= $utc_date_now->format('H:i');
if ( $utc_date_now_f >= "09:00" && $utc_date_now_f < "09:01" ||  $utc_date_now_f >= "17:00" && $utc_date_now_f < "17:01") {
    $pending_users = db::getRecords("SELECT u.*,rt.description FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND /*(account_status IN('ON_REVIEW') AND is_non_partnered_fi=1) OR */ account_status IN('PENDING')");
    if (!empty($pending_users)) {
        $subject = ENVIRONMENT . ", " . (count($pending_users)) . " NEW USERS AWAITING YOUR REVIEW";
        $message = "Please review the following user's account for approval.<br/> <ol>";

//        db::query("UPDATE deposits SET admins_notified=1 WHERE id='" . $contract['id'] . "' ");
        foreach ($pending_users as $pending_user) {
            $message .= "<li><strong>" . $pending_user['description'] . " : </strong> Email: " . $pending_user['email'] . " Name: " . $pending_user['name'] . "</li>";
        }

        $message .= '</ol>';

        Core::sendMail($subject, "ravi@yieldexchange.ca", $message);
        Core::sendMail($subject, "Sampath@yieldexchange.ca", $message);
    }
}

$pacific_date_now = new DateTime("now", new DateTimeZone("America/Vancouver"));
$pacific_date_now_f = $pacific_date_now->format('H:i');
if ($pacific_date_now_f >= "08:00" && $pacific_date_now_f < "08:01"  ) {
    $pending_users = db::getRecords("SELECT u.*,rt.description FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND account_status IN('INVITED') AND is_non_partnered_fi=1");
    if (!empty($pending_users)) {
        foreach ($pending_users as $user_bank) {

            // check if the bank was created today and skip it
            if ( !empty($user_bank['account_opening_date']) ){
                $utc_date_now = new DateTime("now", new DateTimeZone("UTC"));
                $created_on =  new DateTime( $user_bank['account_opening_date'], new DateTimeZone("UTC"));
                if ( $utc_date_now->diff($created_on)->format('%a') < 1 ){ // 1 day 24 hours
                    continue;
                }
            }

            $account_manager = trim($user_bank['account_manager']);
            $your_name = trim($user_bank['inviter_name']);

            if($your_name != $user_bank['name'] ){
                $subject = $your_name.' at '.trim($user_bank['name']).' has invited you to join Yield Exchange';
                $header =  $your_name.' has invited you to join Yield Exchange';
                //$subject = "Pending Invitation from ".$your_name.' '.'at '.$user_bank['name'];
            } else{
                $subject = trim($user_bank['name']).' has invited you to join Yield Exchange.';
                $header = trim($user_bank['name']).' has invited you to join Yield Exchange.';
                //$subject = "Pending Invitation from ".$user_bank['name'];
            }

            $depositor_demographic_data = AuthModel::getUserDemographicData($user_bank['id']);
            $message = "Hi ".$account_manager.",<br/>
                                        <p>I have a deposit that I am looking for rates on and I'm inviting you to participate in this request through Yield Exchange.</p>
                                        <p>Yield Exchange is a digital platform that allows me to negotiate with Canadian Financial Institutions like you, easily and efficiently.</p>
                            
                                        <p>Please contact me if you have any questions regarding this invitation, otherwise I look forward to your response on my Deposit.</p>
                            
                                        <p>Thanks,</p>
                            
                                        <p>".$your_name."</p>
                                        <p>".$user_bank['email']."</p>
                                        <p>".$depositor_demographic_data['telephone']."</p>";

            $link = BASE_URL.'/accept_invitation?token='.Core::urlValueEncrypt($user_bank['id']);
            Core::sendMail($subject, $user_bank['email'], $message,'',false,false,[['linkName'=>'View Invitation','link'=>$link]],false,'bottom',$header);
        }
    }
}
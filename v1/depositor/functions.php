<?php

function makeReviewOffers()
{
    global $notification_email;
    $user_data = AuthModel::getUserdata();
    $user_data_id = $user_data['id'];

    $offers = json_decode($_POST['submitted_offers_data']);
    $rq_id = $_POST['rqid'];

    $utc_time_now = new DateTime("now",new DateTimeZone("UTC"));
    $depositor_request = db::getrecord("select * from depositor_requests where id='$rq_id' AND user_id='$user_data_id' AND request_status IN('ACTIVE')");
    if ( !empty($depositor_request) && ($utc_time_now->format('Y-m-d H:i:s') >= $depositor_request['closing_date_time']) ) {

        $total_offered=0;
        $offer_ids=array();
        foreach ($offers as $offer) {
            $offered_amount = str_replace(",", "", trim($offer->offered_amount));
            $total_offered+=(float)$offered_amount;
            array_push($offer_ids,$offer->id);
        } // for calculations so that we only save valid data;// performance to be looked into later

//        if ($total_offered != $depositor_request['amount']){
//            Core::alert("An error occurred","Total requested amount must equal original requested amount.","error","bids");
//            exit();
//        }

        if ($total_offered > $depositor_request['amount']){
            Core::alert("An error occurred","Total requested amount must not exceed original requested amount.","error","bids");
            exit();
        }

        $request_id=null;
        foreach ($offers as $offer) {
            $depositor_request_conf = db::getRecord("SELECT dr.*,o.maximum_amount,o.minimum_amount FROM offers o, invited i, depositor_requests dr WHERE dr.id = i.depositor_request_id AND o.invitation_id = i.id AND o.id='$offer->id' AND dr.user_id = '$user_data_id'");
            if ( !empty($depositor_request_conf) ) {
                $request_id = $depositor_request_conf['id'];

                $offer->offered_amount = str_replace(",", "", trim($offer->offered_amount));

                $offered_amount = (float)$offer->offered_amount;
                $offer_id = (int) $offer->id;

                if ( $offered_amount == 0 || !($offered_amount <= $depositor_request_conf['maximum_amount'] && $offered_amount >= $depositor_request_conf['minimum_amount'] ) ){
                    continue;
                }

                $time_now = Model::utcDateTime();
                $ref_ = DepositorModel::generateOfferContractID($depositor_request["reference_no"]);
                $offer_contract = db::getRecord("SELECT * FROM deposits WHERE offer_id='$offer->id'");
                $contract_existed=false;
                if (!empty($offer_contract)) {
                    $contract_existed=true;
                    RequestsModel::archiveTable($offer_contract['id'], "deposits","REVIEW OFFER UPDATE");
                    db::query("UPDATE `deposits` SET `offered_amount`='$offered_amount',`deposit_date`=NULL,`status`='PENDING_DEPOSIT' WHERE `offer_id`='$offer_id'");
                }else{
                    $query = "INSERT INTO `deposits`(`reference_no`, `offer_id`, `offered_amount`, `status`, `created_at`) 
                    VALUES ('$ref_','$offer_id','$offered_amount','PENDING_DEPOSIT', '$time_now')";
                    db::query($query);
                }

                RequestsModel::archiveTable($offer_id, "offers", "SELECTED");
                db::query("UPDATE `offers` SET `offer_status`='SELECTED' WHERE id ='$offer_id'");

                RequestsModel::archiveTable($request_id, "depositor_requests", "COMPLETED");
                db::query("UPDATE `depositor_requests` SET `request_status`='COMPLETED' WHERE id ='$request_id'");

                $bank = db::getRecord(" SELECT u.* FROM offers o, invited i, users u WHERE o.invitation_id = i.id AND u.id = i.invited_user_id AND o.id='$offer_id'");
                if (!$contract_existed) {
                    $product = RequestsModel::getProductByID($depositor_request["product_id"])['description'];
                    $interest_rate_offer = BankModel::getOfferByID($offer_id)['interest_rate_offer'];
                    $toEmail = AuthModel::getUserDataByID($depositor_request['user_id'])['email'];
                    $subject = "You have selected an offer";
                    $header = "A deposit has been started";

                    $message = "<p><center>You have selected the following offers:</center></p>";
                    $message .= "<ol><li> <strong>". (!empty($bank['name']) ? $bank['name'] : ' (Bank) ').":</strong> ".$product.", ".$depositor_request['term_length']." ".strtolower($depositor_request['term_length_type']).", ".$depositor_request["currency"]." ".$offered_amount.", ".$interest_rate_offer."% </li></ol>";
                    $message .= "<p>To complete your deposit you must transfer your funds to selected Financial Institutions. If you do not have an existing relationship with these Financial Institutions then you will also need to onboard with the financial institution.</p>";
                   

                    Core::sendMail($subject, $toEmail, $message,'inv',true,false,[],true,$logo_position="top",$header);

                    $usr_demographic_data1 = AuthModel::getUserDemographicData($bank['id']);
                    $timezone11 = $usr_demographic_data1['timezone'];
                    $datetime_from_utc = Model::dateTimeFromUTC('Y-m-d h:i a ',$depositor_request['date_of_deposit'],$timezone11);

                    $product_name=RequestsModel::getProductByID($depositor_request['product_id'])['description'];
                    $interest_rate =BankModel::getOfferByID($offer_id)['interest_rate_offer'];

                    $toEmail = $bank['email'];
                    $subject = "Your offer has been selected";
                    $header =$user_data['name']." has selected your offer";
                    $message = "<p><center>".$user_data['name']." has chosen to deposit ".$depositor_request['currency'] . " " . $offered_amount." with you in a ".$product_name." at ".$interest_rate."%. You can expect the funds to be deposited on or before ".$datetime_from_utc.".</center></p>";
                    $message .= "<p><center>Don't forget to create the <GIC or HISA> after receiving the funds. You should also complete hit 'create <gic/hisa>' in the 'Pending Deposits' page.</center></p>";
                    $message .= "<p><center>If this is a new customer please reach out to ".$user_data['name']." through our chat function on the 'Pending Deposits'.</center></p>";
                    $link = BASE_URL."/login?a=fi";
                   
                    Core::sendMail($subject, $toEmail, $message,'fi',false,false,[['linkName'=>'Sign in','link'=>$link]],true,$logo_position="top",$header);
                    $notification = "This Request Id " . $ref_ . " has been awarded to you. Kindly Check your Deposit Section. ";
                }else{
                    if ($offer_contract['offered_amount'] != $offered_amount) {
                        $toEmail = $bank['email'];
                        $subject = "Offer Updated";
                        $message = "<p>The Awarded amount has been updated to:  " . $depositor_request['currency'] . " " . $offered_amount . " (Request ID " . $ref_ . ")";
                        $message .= "</p>";
                        $message .= "<p>What's next: Login to the account and “Pending Deposits” to compare the received offers. Use the chat/email/phone to discuss the fund transfer process. <strong style='color:red;'>Do not</strong> request or share Bank account information through the chat function.";
                        $message .= "</p>";
                        $message .= "<p>Have questions?  Please contact info@yieldexchange.ca</p>";
                        Core::sendMail($subject, $toEmail, $message,'fi',true);
                        $notification = "The Awarded amount has been updated to:  " . $depositor_request['currency'] . " " . $offered_amount . " (Request ID " . $ref_ . ")";
                    }
                }

                if (!empty($notification)) {
                    Core::sendAdminEmail("Contract awarded!", $notification);
                    $bank_id = $bank['id'];
                    $query1 = "INSERT into notifications (`type`, `details`, `date_sent`, `user_id`,`sent_by`, `status`) VALUES('CONTRACT UPDATED/CREATED','$notification','$time_now','$bank_id','$user_data_id','ACTIVE')";
                    db::query($query1);
                }
            }
        }

        if ( $request_id && count($offer_ids) > 0 ) {
            $all_offers = BankModel::getOffersByRequestID($request_id, true);
            if(!empty($all_offers)) {
                foreach ($all_offers as $all_offer) {
                    $all_offer = (object)$all_offer;
                    $offer_id = (int)$all_offer->id;
                    if (!in_array($offer_id, $offer_ids)) {
                        $offer_contract = db::getRecord("SELECT * FROM deposits WHERE offer_id='$offer_id'");
                        if (!empty($offer_contract)) {
                            RequestsModel::archiveTable($offer_contract['id'], "deposits", "WITHDRAWN");
                            db::query("UPDATE `deposits` SET `status`='WITHDRAWN' WHERE offer_id='$offer_id'");
                            $bank = db::getRecord(" SELECT u.* FROM offers o, invited i, users u WHERE o.invitation_id = i.id AND u.id = i.invited_user_id AND o.id='$offer_id'");
                            $toEmail = $bank['email'];
                            $subject = "Contract Withdrawn";
                            $message = "Deposit ID " . $offer_contract['reference_no'] . " has been withdrawn.";
                            $message .= "<br />";
                            $message .= "What's next: Have questions?  Please contact info@yieldexchange.ca";
                            Core::sendMail($subject, $toEmail, $message, 'inv', true);

                            $notification = "Deposit ID " . $offer_contract['reference_no'] . " has been withdrawn";
                            $time_now = Model::utcDateTime();
                            $query = "INSERT into notifications (`type`, `details`, `date_sent`, `user_id`, `sent_by`, `status`) VALUES ('CONTRACT WITHDRAWN','$notification','$time_now','" . $bank['id'] . "','$user_data_id','ACTIVE')";
                            db::query($query);
                        }
                        RequestsModel::archiveTable($offer_id, "offers", "NOT_SELECTED");
                        db::query("UPDATE `offers` SET `offer_status`='NOT_SELECTED' WHERE id ='$offer_id'");
                    }
                }
            }
        }
    }else{
        if (empty($depositor_request)) {
            Core::alert("An error occurred", "The request could not be found.", "error", "bids");
        }else{
            Core::alert("You can only select offers after closing date & time", "Please wait until all parties have had a chance to submit their offer", "error", "vrzlts?rqid=".$depositor_request['id']);
        }
        exit();
    }
    Core::alert("Review Offers","Deposit created successfully","success","contract"); exit();
}
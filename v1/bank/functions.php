<?php
function makeBankOffer($action){
    AuthModel::authCsrfToken();

    $user_data = AuthModel::getUserdata();
    $demographic_user_data= AuthModel::getUserDemographicData($user_data['id']);
    $user_id = $user_data['id'];

    $reqid = $_POST['reqid'];
    $expdate = Model::dateTimeToUTC("Y-m-d H:i:s", $_POST['expdate'],$demographic_user_data['timezone']);

    $special_ins = "";
    if (isset($_POST['special_ins'])) {
        $special_ins = $_POST['special_ins'];
    }

    $url = "";
    if ( !empty( trim($_POST['url']) ) ){
        if(strpos($_POST['url'], 'http') !== false){
            $url = $_POST['url'];
        } else{
            $url = $_POST['pre_url'].$_POST['url'];
        }

        if (Core::validate_url($url) === false){
            Core::alert("Invalid link/url","Please provide a valid link/url to product disclosure statement","error",!empty(Core::previousUrl()) ? Core::previousUrl() :"requests");
            return;
        }
    }

    $attachment = "";
    if (!empty($_FILES["file"]["tmp_name"])) {
        $attachment = fileUploader($_FILES);
        if (!$attachment['status']){
            Core::alert("File upload failed",$attachment['message'],"error",!empty(Core::previousUrl()) ? Core::previousUrl() :"requests");
            return;
        }
        $attachment=$attachment['data'];
    }

    $post_request = db::getRecord("SELECT * from depositor_requests dr,invited i where i.depositor_request_id=dr.id AND dr.id='$reqid' AND i.invited_user_id='$user_id'");

    $prime_rate=Core::getSystemSettingsValue('prime_rate');
    $rate_operator=null;
    $fixed_rate=null;
    $rate_type="FIXED";
    if ( $post_request['term_length_type'] == "HISA" ) {
        $rate_type = $_POST['rate_type'];
        if ( strtolower($rate_type)=="fixed" ) {
            $nir = $_POST['nir'];
        }else{
            $rate_operator = $_POST['rate_operator'];
            $fixed_rate = $_POST['fixed_rate'];

            if ( $rate_operator=="+" ) {
                $nir = $prime_rate + $fixed_rate;
            }else{
                $nir = $prime_rate - $fixed_rate;
            }
        }
    }else{
        $nir = $_POST['nir'];
    }

    $rate_type = trim(strtoupper($rate_type));

    if ( !empty($post_request) ) {
        $depositor_data = AuthModel::getUserDataByID($post_request['user_id']);
        $depositor_id = $depositor_data['id'];

        $utc_time_now = new DateTime("now",new DateTimeZone("UTC"));
        $bid_open = $post_request['closing_date_time'];

        if ( $action == "create" && ($utc_time_now->format('Y-m-d H:i:s') > $bid_open) ) {
            Core::alert("Request already expired","could not send an offer","error","requests");
            return;
        }

        $depositor_preferences = Core::getUserPreferences($post_request['user_id']);
        $dateTime = Model::utcDateTime();
        $obo = trim($_POST['obo']);

        $min_amount = isset($_POST['min_amount']) ? str_replace(",", "", $_POST['min_amount']) : 0;
        $max_amount = isset($_POST['max_amount']) ? str_replace(",", "", $_POST['max_amount']) : 0;

        if ($action == "create" || ($action != "create" && $post_request['closing_date_time'] >= Model::utcDateTime()) ) {
            if ($max_amount == 0 || $max_amount < $min_amount || $max_amount > $post_request['amount'] || $min_amount > $post_request['amount']) {
                Core::alert("An error occurred", "An error occurred, please try again with correct data", "error", !empty(Core::previousUrl()) ? Core::previousUrl() : "requests");
                return;
            }
        }

        if ($expdate < $post_request['date_of_deposit']){
            Core::alert("An error occurred","Rate held until must be greater than deposit date","error",!empty(Core::previousUrl()) ? Core::previousUrl() :"requests");
            return;
        }

        $bid_exists_for_bank = db::getCell("select count(*) as total from offers o, invited i where i.id = o.invitation_id AND i.depositor_request_id='$reqid' AND i.invited_user_id='$user_id'");
        if ($action == "create") {
            $offerID = Model::generateUniqueOfferID();
            if ($bid_exists_for_bank > 0) {
                Core::alert("An error occurred","You have already posted an offer for this request.","error","my_bids");
                return;
            }

            $invitation = db::getRecord("SELECT * FROM invited WHERE invited_user_id='$user_id' AND depositor_request_id='$reqid'");
            $invitation_id = !empty($invitation) ? $invitation['id'] : 0;

            if (!empty($invitation_id)) {
                $query = "INSERT INTO `offers`(`reference_no`,`on_behalf_of`, `invitation_id`, `maximum_amount`, `minimum_amount`, `interest_rate_offer`, `rate_held_until`, `product_disclosure_statement`, `product_disclosure_url`, `special_instructions`, `offer_status`, `created_date`, `prime_rate`, `rate_operator`, `fixed_rate`, `rate_type`) 
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                db::preparedQuery($query,array('ssidddssssssdsds',$offerID,$obo,$invitation_id,$max_amount,$min_amount,$nir,$expdate,$attachment,$url,$special_ins,'ACTIVE',$dateTime,$prime_rate,$rate_operator,$fixed_rate,$rate_type));
                db::query("UPDATE `invited` SET `invitation_status`='PARTICIPATED' WHERE id='$invitation_id'");

                $depositor_demographic_user_data= AuthModel::getUserDemographicData($depositor_data['id']);
                $depositor_timezone = $depositor_demographic_user_data['timezone'];
                //$date_now = Model::utcDateTime();
                $datetime_from_utc = Model::dateTimeFromUTC('Y-m-d h:i a T',$expdate,$depositor_timezone);

                $toEmail = $depositor_data['email'];
                $subject = "You have received an Offer";
                $header = $user_data['name']." has placed an offer";

                $message = "<p><center>Your request ".$post_request['reference_no']." has received an offer.";
                $message .= " You can sign in and review the offer through the 'Review offers' page. You can select an offer after ".$datetime_from_utc.".</center></p>";
                $link = BASE_URL."/login?=inv";
                
                if (empty($depositor_preferences) || $depositor_preferences['mute_notification'] == 1) {
                    Core::sendMail($subject, $toEmail, $message,'inv',false,false,[['linkName'=>'Sign in','link'=>$link]],true,$logo_position="top",$header);
                }

                $timezone2 = $demographic_user_data['timezone'];
                //$date_now = Model::utcDateTime();
                $datetime_from_utc2 = Model::dateTimeFromUTC('Y-m-d h:i a ',$post_request['closing_date_time'],$timezone2);
                $datetime_from_utc21 = Model::dateTimeFromUTC('Y-m-d h:i a T',$post_request['closing_date_time'],$timezone2);
                $toEmail_fi = $user_data['email'];
                $subject_fi = "Your offer has been placed";
                $header_fi = $depositor_data['name']." has received your offer";

                $message_fi = "<p><center>Your offer has been sent. You can sign in ".$datetime_from_utc2." to see if your offer was selected by ".$depositor_data['name'].".</center></p>";
                $message_fi .= "<p><center>You can edit your request if required until ".$datetime_from_utc21.".</center></p>";
                $link2 = BASE_URL."/login?=fi";

                Core::sendMail($subject_fi, $toEmail_fi, $message_fi,'fi',false,false,[['linkName'=>'Sign in','link'=>$link2]],true,$logo_position="top",$header_fi);


                $deposit_reference = $post_request['reference_no'];
                $notification = "A bid has been placed against " . $deposit_reference . " reference ID";
                $subject="New offer";
                Core::sendAdminEmail($subject, $notification);

                $query = "INSERT INTO notifications (type,details,date_sent,user_id,sent_by,status) VALUES ('OFFER REQUEST','$notification','$dateTime','$depositor_id','$user_id','ACTIVE')";
                db::insertRecord($query);

                $is_first_time = db::getCell("SELECT COUNT(*) FROM `invited` WHERE `invitation_status`='PARTICIPATED' AND invited_user_id='$user_id'");
                $is_first_time = $is_first_time == 1;
                Core::alert("Offer created","Your offer has been submitted successfully ".(!$is_first_time ? "" : "Please check your email to finish setting up your account."),"success","my_bids");exit();
            }
        } else {
            if ($bid_exists_for_bank == 0) {
                Core::alert("An error occurred","You have not posted an offer for this request","error","my_bids");
                return;
            }

            $bid_id = $_POST["bidid"];
            RequestsModel::archiveTable($bid_id, "offers","edited");
          
            $query = "UPDATE `offers` SET `on_behalf_of`=?, `rate_held_until`=?, `product_disclosure_url`=?, `special_instructions`=?,`modified_date`=?,`modified_by`=?, `prime_rate`=?, `rate_operator`=?, `fixed_rate`=?, `rate_type`=? ";
            $bindings="ssssssdsds";
            $bindings_data=[$obo,$expdate,$url,$special_ins,$dateTime,$user_id,$prime_rate,$rate_operator,$fixed_rate,$rate_type];
            if ($post_request['closing_date_time'] >= Model::utcDateTime()){
                $query.=" ,`maximum_amount`=?, `minimum_amount`=?, `interest_rate_offer`=?";
                $bindings.="ddd";
                array_push($bindings_data,$max_amount,$min_amount,$nir);
            }

            if (!empty($attachment) || empty($_POST['attached_file'])) {
              // TODO
            }else{
                $attachment = db::getCell("SELECT product_disclosure_statement FROM `offers` WHERE id='$bid_id'");
            }

            $query .= ", `product_disclosure_statement`=? WHERE id=?";
            $bindings.="si";
            array_push($bindings_data,$attachment,$bid_id);

            array_unshift($bindings_data,$bindings);
            db::preparedQuery($query,$bindings_data);
            Core::alert("Offer updated","Your offer has been updated successfully","success","my_bids");exit();
        }

    }
}

function fileUploader($file){
    if ( $file['file']['size']/1000/1000 <= 25 ) {

        $target_dir = "../uploads/"; // change do your upload dir
        $target_file = $target_dir . '_' . time() . '_' . basename($file["file"]["name"]);
        $post_tmp = $file["file"]["tmp_name"];
        $file_type = $file["file"]["type"];

        switch (true) {
            case Core::startsWith($file_type, 'image/'):
            case Core::startsWith($file_type, 'application/vnd.openxmlformats-officedocument'):
            case Core::startsWith($file_type, 'application/pdf'):
            case Core::startsWith($file_type, 'application/msword'):
            case Core::startsWith($file_type, 'application/vnd.oasis.opendocument/'):
            /*case Core::startsWith($file_type, 'video/'):*/
                if (is_dir($target_dir) && is_writable($target_dir)) {
                    if (move_uploaded_file($post_tmp, $target_file)) {
                        return ['status'=>true,'message'=>'Upload successful','data'=>$target_file];
                    }else{
                        return ['status'=>false,'message'=>'Failed to upload the Product Disclosure statement, please contact us','data'=>''];
                    }
                }else{
                    return ['status'=>false,'message'=>'Failed to upload the Product Disclosure statement, please contact us','data'=>''];
                }
//                return ['status'=>false,'message'=>'Failed to upload the Product Disclosure statement','data'=>''];
            default:
                return ['status'=>false,'message'=>'Please upload a disclosure statement in a valid format: jpeg, gif, png, pdf and word documents','data'=>''];
        }

    }else{
        return ['status'=>false,'message'=>'The uploaded file size is greater than the minimum allowed 25mb','data'=>''];
    }
}
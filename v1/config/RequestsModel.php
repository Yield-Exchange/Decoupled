<?php
class RequestsModel{

    public static function getProducts(){
        return db::getRecords("SELECT * FROM products");
    }

    public static function getProductByID($id){
        return db::getRecord("SELECT * FROM products WHERE id='$id'");
    }

    public static function getRequestByRef($ref_no){
        return db::getRecord("SELECT * FROM depositor_requests WHERE reference_no='$ref_no'");
    }

    public static function getRequestByID($id,$requireAuth=false,$requiresInvitation=false){
        if ($requireAuth){
            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
            return db::getRecord("SELECT * FROM depositor_requests WHERE id='$id' AND user_id='$user_id'");
        }

        if ($requiresInvitation){
            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
            return db::getRecord("SELECT dr.* FROM depositor_requests dr,invited i WHERE dr.id = i.depositor_request_id AND i.invited_user_id = '$user_id' AND dr.id='$id'");
        }
        return db::getRecord("SELECT * FROM depositor_requests WHERE id='$id'");
    }

    public static function completeContract($id){
        $user_data = AuthModel::getUserdata();
        $user_data_id = $user_data['id'];

        $contract_data = BankModel::getContractByID($id);
        if ( !empty($contract_data) ){
            $depositor_request = self::getRequestByID($contract_data['depositor_request_id']);
            if ( ($contract_data['status'] == "IN_PROGRESS" || $contract_data['status'] == "ACTIVE") && !empty($contract_data['gic_start_date']) ){
                if ($depositor_request['user_id'] == $user_data['id']){ // make sure the user is a depositor/poster(logged in)
                    self::archiveTable($id,"deposits","COMPLETED");
                    db::query("UPDATE deposits SET status='COMPLETED' WHERE id='$id'");

                    self::archiveTable($contract_data['offer_id'],"offers","COMPLETED");
                    db::query("UPDATE offers SET offer_status='COMPLETED' WHERE id='".$contract_data['offer_id']."'");

                    if ($user_data['description'] == "Admin"){
                        Core::activityLog("Admin Marked Contract Complete");
                    }else {
                        Core::activityLog("Depositor Marked Contract Complete");
                    }

                    $notification = "Deposit ID " . $depositor_request['reference_no'] . " has been marked as completed";
                    Core::sendAdminEmail("Contract Marked As Complete!", $notification);

                    $user_id = $contract_data['invited_user_id'];
                    $bank_data = AuthModel::getUserDataByID($user_id);

                    $toEmail = $bank_data['email'];
                    $subject = "Contract Marked as Complete";

                    $message = "Contract marked as completed.";
                    $message .= "<br />";
                    $message .= $notification;
                    $message .= "<br />";
                    $message .= "Have questions?  Please contact info@yieldexchange.ca";

                    $bank_preferences = Core::getUserPreferences($user_id);
                    if ( empty($bank_preferences) || $bank_preferences['mute_notification'] == 1){
                        Core::sendMail($subject, $toEmail, $message,'fi',true);
                    }

                    $user_id = $contract_data['invited_user_id'];
                    $time_now = Model::utcDateTime();
                    $query = "INSERT into notifications (`type`, `details`, `date_sent`, `user_id`, `sent_by`, `status`) VALUES ('CONTRACT MARKED AS COMPLETE','$notification','$time_now','$user_id','$user_data_id','ACTIVE')";
                    db::query($query);
                }
            }
        }
    }

    public static function rejectContract($id){
        $id = Core::urlValueDecrypt($id);
        $contract_data = BankModel::getContractByID($id);
        if ( !empty($contract_data) ) {
            $rq_id = $contract_data['depositor_request_id'];
            $bid_id = $_POST['offer_id'];

            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
            $post_request = db::getrecord("select * from depositor_requests where id='$rq_id' AND user_id='$user_id'");
            if (!empty($post_request)) {
                    $contract = $contract_data;
                    $contract_id = $contract['id'];
                    RequestsModel::archiveTable($contract_id, "deposits", "WITHDRAWN");
                    db::query("UPDATE `deposits` SET `status`='WITHDRAWN' WHERE id='$contract_id'");
                    $bank = db::getRecord(" SELECT u.* FROM offers o, invited i, users u WHERE o.invitation_id = i.id AND u.id = i.invited_user_id AND o.id='$bid_id'");
                    $toEmail = $bank['email'];
                    $subject = "Deposit Withdrawn";
                    $message = "Deposit id " . $contract['reference_no'] . " has been withdrawn";
                    $message .= "<br />";
                    $message .= "What's next: Have questions?  Please contact info@yieldexchange.ca";
                    Core::sendMail($subject, $toEmail, $message,'inv',true);

                    $notification = "Deposit Id " . $post_request['reference_no'] . " has been withdrawn";
                    Core::sendAdminEmail("Deposit withdrawn", $notification);

//                    RequestsModel::archiveTable($contract['offer_id'], "offers", "CONTRACT_WITHDRAWN");
//                    db::query("UPDATE `offers` SET `offer_status`='CONTRACT_WITHDRAWN' WHERE id ='" . $contract['offer_id'] . "'");

                    $time_now = Model::utcDateTime();
                    $logged_in_user_id = $user_data['id'];
                    $query = "INSERT into notifications (`type`, `details`, `date_sent`, `user_id`, `sent_by`, `status`) VALUES ('DEPOSIT WITHDRAWN','$notification','$time_now','" . $bank . "','$logged_in_user_id','ACTIVE')";
                    db::query($query);
            }
        }
    }

    public static function postEditRequest(){
        $user_data = AuthModel::getUserdata();
        $user_id = $user_data['id'];

        if(empty($_SESSION["dep_id"])){
            $path = 'bids';
            echo "<script>location='$path'</script>";
        }else{
            $id=$_SESSION["dep_id"];
            $query = "SELECT id from depositor_requests where id='$id' AND user_id='$user_id'";
            $dep_id = db::getCell($query);
            if(empty($dep_id)){
                $path = 'bids';
                echo "<script>location='$path'</script>"; exit();
            }
        }

        $total_bids = db::getcell("select count(*) as total from offers o, invited i where i.id=o.invitation_id AND i.depositor_request_id='".$id."'");
        if ($total_bids > 0){
            Core::alert("An error occurred","An error occurred, you can not edit this request. Already have offers!","error","bids");
            return;
        }

        Core::activityLog("Depositor Saving Edited Post Request");

        $user_data = AuthModel::getUserdata();
        $demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
        $date_now = Model::utcDateTime();
        $user_id = $user_data['id'];

        $product_id = $_SESSION["val2"];
        $deposit_currency = $_SESSION["val3"];
        $deposit_amount = str_replace(",","",$_SESSION["val4"]);
        $deposit_start = Model::dateTimeToUTC("Y-m-d H:i:s",$_SESSION["val5"]." 23:59:59",$demographic_user_data['timezone']);

        $invest_payment = $_SESSION["val9"];
        $special_institute = $_SESSION["val10"];
        $bid_opening = Model::dateTimeToUTC("Y-m-d H:i:s",$_SESSION["val11"],$demographic_user_data['timezone']);

        if ($bid_opening < Model::utcDateTime() ){
            Core::alert("An error occurred","An error occurred, closing date & time should not be less than now","error","edit_request?id=".Core::urlValueEncrypt($_SESSION["dep_id"]));
            return;
        }

        if ($deposit_start < $bid_opening ){
            Core::alert("An error occurred","An error occurred, date of deposit should not be less than closing date & time","error","edit_request?id=".Core::urlValueEncrypt($_SESSION["dep_id"]));
            return;
        }

        $chk1 = $_SESSION["val12"];
        $Ask_Rate = $_SESSION["val17"];
        $Ask_Rate = !empty($Ask_Rate) ? $Ask_Rate : 0;
        $Dep_insu = $_SESSION["val18"];

        $product = RequestsModel::getProductByID($product_id);

        $r_id = $_SESSION["dep_id"];

        $lockout_period = $_SESSION["lockout_period"];
        $lockout_period = !empty($lockout_period) && in_array(trim(strtolower($product['description'])),['notice deposit','cashable','high interest savings']) ? $lockout_period : 0;

        $term_type = 'HISA';
        $term_length = 0;
        if (strpos($product['description'], 'High Interest Savings') === false) {
            $term_type = trim($_SESSION["term_type"]);
            $term_length = $_SESSION["val8"];
        }

        $depositor_preferences = Core::getUserPreferences($user_id);

        self::archiveTable($r_id,"depositor_requests");
        $sql = "UPDATE `depositor_requests` SET `term_length_type`=?, `term_length`=?, `lockout_period_days`=?, `closing_date_time`=?,`amount`=?, `currency`=?, `date_of_deposit`=?, `compound_frequency`=?, `requested_rate`=?, `requested_short_term_credit_rating`=?, `requested_deposit_insurance`=?, `special_instructions`=?,`modified_date`=?, `modified_by`=?, `product_id`=? WHERE id=?";
        db::preparedQuery($sql,array('siisdsssdssssiii',$term_type,$term_length,$lockout_period,$bid_opening,$deposit_amount,$deposit_currency,$deposit_start,$invest_payment,$Ask_Rate,$chk1,$Dep_insu,$special_institute,$date_now,$user_id,$product_id,$r_id));

        $toEmail = $user_data['email'];
        $subject = "New Request";

        $message = "Congratulations!!! your request is edited and live on Yield Exchange.";
        $message .= "<br />";
        $message .= "What's next: You are going to receive offers from our associated financial institutions. Login to the account and 'Review offers' to compare the received offers.";
        $message .= "<br />";
        $message .= "Have questions?  Please contact info@yieldexchange.ca";

        if ( empty($depositor_preferences) || $depositor_preferences['mute_notification'] == 1 ){
            Core::sendMail($subject, $toEmail, $message, 'inv',true);
        }

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
        unset($_SESSION["anonymous"]);
        unset($_SESSION["lockout_period"]);
        unset($_SESSION["term_type"]);

        $deposit_reference = db::getCell("SELECT reference_no from depositor_requests where id='$id' AND user_id='$user_id'");
        sendInvites($deposit_reference);
        Core::alert("Request posted","We are sending the invites","success","bids");
    }

    public static function postRequest(){
        AuthModel::authCsrfToken();

        Core::activityLog("Depositor Confirm New Post Request");

        if(isset($_POST['lockout_period'])){ $lockout_period = $_POST['lockout_period'];} else{ $lockout_period = ''; }
        $lockout_period = $_POST['lockout_period'];
        $_SESSION["lockout_period"] = $lockout_period;

        if(isset($_POST['term_type'])){ $term_type = $_POST['term_type'];} else{ $term_type = ''; }
        
        $_SESSION["term_type"] = $term_type;

        $_SESSION["val1"] = "";

        $product = $_POST['product'];

        $_SESSION["val2"] = $product;

        $deposit_currency = $_POST['deposit_currency'];
        $_SESSION["val3"] = $deposit_currency;

        $deposit_amount = $_POST['deposit_amount'];
        $_SESSION["val4"] = $deposit_amount;

        $deposit_start = $_POST['deposit_start'];
        $_SESSION["val5"] = $deposit_start;

        if(isset($_POST['month'])){  $month = $_POST['month']; } else{  $month = ''; }
       
        $_SESSION["val8"] = $month;

        if(isset($_POST['invest_payment'])){ $invest_payment = $_POST['invest_payment']; } else{ $invest_payment=''; }
        
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
        echo "<script>location='invites'</script>";
    }

    public static function saveRequest(){
        $user_data = AuthModel::getUserdata();

        Core::activityLog("Depositor Save New Post Request");

        $query_ = "SELECT COUNT(*) FROM `depositor_requests`";
        $data_ = db::getCell($query_);

        $count_ = 0;
        if (!empty($data_)) {
            $count_ = $data_;
        }

        $incr_ = 100 + $count_;
        $deposit_reference = date('ymd') . $incr_;

        $product_id = $_SESSION["val2"];
        $deposit_currency = $_SESSION["val3"];
        $deposit_amount = str_replace(",","",$_SESSION["val4"]);
        $deposit_start = Model::dateTimeToUTC("Y-m-d H:i:s",$_SESSION["val5"]." 23:59:59");

        $invest_payment = $_SESSION["val9"];
        $special_institute = $_SESSION["val10"];
        $bid_opening = Model::dateTimeToUTC("Y-m-d H:i:s",$_SESSION["val11"]);

        if ($bid_opening < Model::utcDateTime() ){
            Core::alert("An error occurred","An error occurred, closing date & time should not be less than now","error","p_req");
            return;
        }

        if ($deposit_start < $bid_opening ){
            Core::alert("An error occurred","An error occurred, date of deposit should not be less than closing date & time","error","p_req");
            return;
        }

        $chk1 = $_SESSION["val12"];
        $Ask_Rate = $_SESSION["val17"];
        $Ask_Rate = !empty($Ask_Rate) ? $Ask_Rate : 0;
        $Dep_insu = $_SESSION["val18"];

        $product = RequestsModel::getProductByID($product_id);

        $lockout_period = $_SESSION["lockout_period"];
        $lockout_period = !empty($lockout_period) && in_array(trim(strtolower($product['description'])),['notice deposit','cashable','high interest savings']) ? $lockout_period : 0;

        $term_type = 'HISA';
        $term_length = 0;
        if (strpos($product['description'], 'High Interest Savings') === false) {
            $term_type = trim($_SESSION["term_type"]);
            $term_length = $_SESSION["val8"];
        }

        if ( empty($deposit_amount) || empty($bid_opening) || empty($deposit_start) ){
            echo "<script>location='p_req'</script>"; exit();
        }

        $date_now = Model::utcDateTime();
        $user_id = $user_data['id'];

        $query="INSERT INTO `depositor_requests`(`reference_no`, `term_length_type`, `term_length`, `lockout_period_days`, `closing_date_time`, `amount`, `currency`, `date_of_deposit`, `compound_frequency`, `requested_rate`, `requested_short_term_credit_rating`, `requested_deposit_insurance`, `special_instructions`, `request_status`, `created_date`, `user_id`, `product_id`) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        db::preparedQuery($query, array("ssiisdsssdsssssii", $deposit_reference, $term_type, $term_length, $lockout_period, $bid_opening, $deposit_amount,
            $deposit_currency, $deposit_start, $invest_payment, $Ask_Rate, $chk1, $Dep_insu, $special_institute, 'ACTIVE', $date_now, $user_id, $product_id));

        $usr_demographic_data = AuthModel::getUserDemographicData($user_id);
        $timezone = $usr_demographic_data['timezone'];
        $datetime_from_utc = Model::dateTimeFromUTC('Y-m-d h:i a T',$bid_opening,$timezone);

        $toEmail = $user_data['email'];
        $depositor_preferences = Core::getUserPreferences($user_id);

        $subject = "Your request has been posted";
        $header ="Your request ".$deposit_reference." is live";
        $message = "<p><center>Your request for ".$deposit_currency." ".$deposit_amount." has been posted. You can sign in into the platform after ".$datetime_from_utc." to see if you have any offers.</center></p>";
        $link = BASE_URL."/login?=inv";
        
        if ( empty($depositor_preferences) || $depositor_preferences['mute_notification'] == 1 ){
            Core::sendMail($subject, $toEmail, $message,'inv',false,false,[['linkName'=>'Sign in','link'=>$link]],true,$logo_position="top",$header);
        }

        $notification = $deposit_reference . " A Request has been Posted";
        Core::sendAdminEmail("New Deposit Request!", $notification);

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

        sendInvites($deposit_reference);
        Core::alert("Request posted","We are sending the invites","success","bids");
    }

    public static function archiveTable($id,$table,$action=""){
        try{
            $user_data = !empty($_SESSION['USERID']) ? AuthModel::getUserdata() : (!empty($_SESSION['ADMIN_USERID']) ? AdminModel::getUserdata() : []);
        }catch (\Throwable $e){return;}

        $user_id = $user_data['id'];
        $timeNow = Model::utcDateTime();
        switch ( $table ){
            case "deposits";
                $sql = "INSERT INTO deposits_archive (SELECT 0, c.reference_no, c.offer_id, c.offered_amount, c.gic_start_date, c.gic_number,c.maturity_date, c.status, c.created_at, c.modified_at, c.modified_by FROM deposits c WHERE c.id='$id')";
                $insert_id = db::insertRecord($sql);
                db::query("UPDATE deposits_archive SET modified_by='$user_id', modified_at='$timeNow', modified_section='$action' WHERE id='$insert_id'");
                db::query("UPDATE deposits SET modified_by='$user_id', modified_at='$timeNow', modified_section='$action' WHERE id='$id'");
                break;
            case "depositor_requests";
                $sql = "INSERT INTO depositor_requests_archive (SELECT 0, reference_no, term_length_type, term_length, lockout_period_days, lockout_period_months, closing_date_time, amount, currency, date_of_deposit, compound_frequency, requested_rate, requested_short_term_credit_rating, requested_deposit_insurance,
                        special_instructions,request_status,created_date,closed_date, user_id, product_id, modified_date, modified_section, modified_by FROM depositor_requests WHERE id='$id')";
                $insert_id = db::insertRecord($sql);
                db::query("UPDATE depositor_requests_archive SET modified_by='$user_id', modified_date='$timeNow', modified_section='$action' WHERE id='$insert_id'");
                db::query("UPDATE depositor_requests SET modified_by='$user_id', modified_date='$timeNow', modified_section='$action' WHERE id='$id'");
                break;
            case "offers";
                $sql = "INSERT INTO offers_archives (SELECT 0, invitation_id,on_behalf_of,reference_no, maximum_amount, minimum_amount, interest_rate_offer, rate_held_until, product_disclosure_statement,product_disclosure_url, special_instructions, offer_status, created_date, modified_date, modified_section, modified_by FROM offers WHERE id='$id')";
                $insert_id = db::insertRecord($sql);
                db::query("UPDATE offers_archives SET modified_by='$user_id', modified_date='$timeNow', modified_section='$action' WHERE id='$insert_id'");
                db::query("UPDATE offers SET modified_by='$user_id', modified_date='$timeNow', modified_section='$action' WHERE id='$id'");
                break;
            case "users";
                $sql_ = "INSERT INTO users_archive (SELECT 0, `name`, `profile_pic`, `email`, `account_opening_date`, `account_status`, `modified_date`, `modified_section`, `modified_by`, `failed_login_attempts`, `account_closure_date`, `account_closure_reason` FROM users WHERE id='$id')";
                $return = db::insertRecord($sql_);
                db::query("UPDATE users_archive SET modified_by='$user_id', modified_date='$timeNow', modified_section='$action' WHERE id='$return'");
                db::query("UPDATE users SET modified_by='$user_id', modified_date='$timeNow', modified_section='$action' WHERE id='$id'");
                return $return;
        }
    }

}
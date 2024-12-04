<?php
use Dompdf\Dompdf;

class BankModel{

    public static function generateNonInvitedFITerms($user){
        $dompdf = new Dompdf();
        $fileContent = file_get_contents(BASE_DIR."/bank/inc/non-invited-fi-terms.php");
        $fileContent = str_replace("{INSTITUTION}", $user['name'],$fileContent);
        $fileContent = str_replace("{BASE_URL}", BASE_URL,$fileContent);
        $dompdf->loadHtml($fileContent);
        // Render the HTML as PDF
        $dompdf->render();

        $output = $dompdf->output();
        file_put_contents(BASE_DIR."/uploads/".$user['name']."-Terms&Conditions-".date("Y-m-d-h:i").".pdf", $output);
        return BASE_URL."/uploads/".$user['name']."-Terms&Conditions-".date("Y-m-d-h:i").".pdf";
    }

    public static function getBanks($is_non_partnered_fi=false){
        if ($is_non_partnered_fi){
            $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id";
            $sql_.=" AND rt.description='Bank' AND u.is_non_partnered_fi=1 ORDER BY account_opening_date DESC";
            return db::getRecords($sql_);
        }
        $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id";
        $sql_.=" AND rt.description='Bank' AND u.account_status IN('ACTIVE')";
        return db::getRecords($sql_);
    }

    public static function getBanksANDBrokers(){
        $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id";
        $sql_.=" AND (rt.description='Bank' || rt.description='Broker') AND u.account_status IN('ACTIVE')";
        return db::getRecords($sql_);
    }

    public static function getBanksANDBrokersWithCreditDepositInsuranceFilters($credit,$debit,$user_data,$is_for_invites=false){
        $my_user_id=$user_data['id'];
        $sql_ = "SELECT u.*,ur.role_type_id,di.description as deposit_insurance,crt.description as credit_rating 
                    FROM users u, user_role_types ur, role_types rt, credit_rating cr, deposit_insurance di, credit_rating_type crt 
                        WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND cr.credit_rating_type_id = crt.id AND di.id = cr.deposit_insurance_id AND cr.user_id=u.id";
        $sql_.=" AND (rt.description='Bank' || rt.description='Broker') AND (u.account_status IN('ACTIVE','INVITED','REVIEWING','LOCKED') || (u.is_non_partnered_fi = 1 AND u.is_temporary=1 AND u.created_by='$my_user_id'))";

        if ( $credit && strpos($credit, 'Any') === false ){
            $sql_.=" AND crt.description LIKE '%".$credit."%'";
        }

        if ( $debit && strpos($debit, 'Any') === false ){
            $sql_.=" AND di.description LIKE '%".$debit."%'";
        }

        $all_banks =  db::getRecords($sql_,NULL,NULL,false);
        return $all_banks;

        // Fetch the created by me and temporary
//        $sql_query = "SELECT u.*,ur.role_type_id,di.description as deposit_insurance,crt.description as credit_rating
//                    FROM users u, user_role_types ur, role_types rt, credit_rating cr, deposit_insurance di, credit_rating_type crt
//                        WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND cr.credit_rating_type_id = crt.id AND di.id = cr.deposit_insurance_id AND cr.user_id=u.id";
//        $sql_query.=" AND (rt.description='Bank' || rt.description='Broker') AND u.is_non_partnered_fi = 1 AND u.is_temporary=1 AND u.created_by='$my_user_id'";

//        if ($is_for_invites){
//            $sql_query.=" AND u.account_status IN('INVITED','REVIEWING','DECLINED_INVITATION')";
//        }else{
//            $sql_query.=" AND u.account_status IN('INVITED','REVIEWING')";
//        }
//        $my_non_invited_fis = db::getRecords($sql_query,NULL,NULL,false);

//        return array_merge($all_banks,$my_non_invited_fis);
    }

    public static function generateUniqueBankID( $start=1001 ){

        $bankID = db::getCell("SELECT bankID FROM bank ORDER BY bankID DESC LIMIT 1");
        if(empty($bankID)){

            $banks = db::getCell("SELECT COUNT(*) FROM bank WHERE bankID='$start'");
            if ($banks > 0){
                return self::generateUniqueBankID($start+1);
            }

            return $start;
        }

        $bankID++;
        $banks = db::getCell("SELECT COUNT(*) FROM bank WHERE bankID='$bankID' LIMIT 1");

        if ($banks > 0){
            return self::generateUniqueBankID($bankID+1);
        }

        return $bankID;
    }

    public static function isBankOrBrokerName($bank, $bid){
        return ( $bank['description'] == "Broker" && !empty($bid['on_behalf_of']) ) ? $bank['name']." OBO ".$bid['on_behalf_of'] : $bank['name'];
    }

    public static function getBrokerOBO($bank, $bid){
        return !empty($bank['is_broker']) && !empty($bid['obo']) ? $bid['obo'] : "n/a";
    }

//    public static function showBankOBO($bank, $bid, $columns=3){
//
//        $obo = self::getBrokerOBO($bank, $bid);
//
//        if (trim($obo) != "n/a") {
//            return '<div class="col-sm-'.$columns.'">
//                    <p style="font-weight:bold;">On Behalf of :</p>'.
//                        $obo
//                .'</div>';
//        }else{
//            return '<div class="col-sm-'.$columns.'">
//                    <p style="font-weight:bold;">Institution :</p>'.
//                        $bank["name"]
//                .'</div>';
//        }
//
//    }

    public static function getFI($non_existing_check=false){
        if ($non_existing_check){
            $query = "SELECT * from fi_list WHERE name NOT IN(SELECT name from users WHERE account_status IN('PENDING','ACTIVE','LOCKED','SUSPENDED','REVIEWING','INVITED')) AND status='ACTIVE'";
            return db::getrecords($query);
        }
        $query = "SELECT * from fi_list WHERE status='ACTIVE'";
        return db::getrecords($query);
    }

    public static function getBankTimeZone($bank_id){
        $timezone1 = "select timezone from bank_profile_data where bank_id='$bank_id'";
        return db::getcell($timezone1);
    }

    public static function getBankDateTime($time_zone){
        $date = new DateTime("now", new DateTimeZone(Model::formattedTimezone($time_zone)) );
        return $date->format('Y-m-d H:i:s');
    }

    public static function setBankTimeZoneSystem($user_data){
        $bank_demographic_data = AuthModel::getUserDemographicData($user_data['id']);
        date_default_timezone_set(Model::formattedTimezone($bank_demographic_data['timezone']));
    }

    public static function getBankBidWithRequestID($req_id,$user_id){
        $check_bid = "SELECT o.* FROM offers o,invited i WHERE i.id = o.invitation_id  AND i.invited_user_id = '$user_id' AND i.depositor_request_id = '$req_id'";
        return db::getRecord($check_bid);
    }

    public static function getBankBidContract($offer_id){
        return db::getRecord("SELECT c.* FROM deposits c, offers o WHERE c.offer_id = o.id AND o.id = '$offer_id' AND c.status LIMIT 1");
    }

    public static function getTheContractByOfferID($offer_id){
        return db::getRecord("SELECT c.* FROM deposits c, offers o WHERE c.offer_id = o.id AND o.id = '$offer_id' ORDER BY id DESC LIMIT 1");
    }

    public static function getContractByID($id, $requiresAuth=false){
        if ($requiresAuth){
            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
            return db::getRecord("SELECT c.*,i.invited_user_id,i.depositor_request_id FROM deposits c, offers o,invited i WHERE c.offer_id = o.id AND i.id = o.invitation_id AND c.id = '$id' AND i.invited_user_id='$user_id' LIMIT 1");
        }
        return db::getRecord("SELECT c.*,i.invited_user_id,i.depositor_request_id FROM deposits c, offers o,invited i WHERE c.offer_id = o.id AND i.id = o.invitation_id AND c.id = '$id' LIMIT 1");
    }

    public static function getBankPendingContractsBids($user_id){
        $contracts = db::getrecords("SELECT c.*,i.depositor_request_id,i.depositor_request_id,o.interest_rate_offer,o.rate_held_until,o.rate_type,o.prime_rate,o.rate_operator FROM deposits c, offers o, invited i WHERE c.offer_id = o.id AND i.id = o.invitation_id AND i.invited_user_id = '$user_id' AND c.status IN('PENDING_DEPOSIT') ORDER BY c.reference_no,c.id DESC");

        $total_amount['CAD'] = db::getCell("SELECT SUM(c.offered_amount) FROM deposits c, offers o, invited i,depositor_requests dr WHERE dr.id = i.depositor_request_id AND c.offer_id = o.id AND i.id = o.invitation_id AND i.invited_user_id = '$user_id' AND c.status IN('PENDING_DEPOSIT') AND dr.currency='CAD'");
        $total_amount['USD'] = db::getCell("SELECT SUM(c.offered_amount) FROM deposits c, offers o, invited i,depositor_requests dr WHERE dr.id = i.depositor_request_id AND c.offer_id = o.id AND i.id = o.invitation_id AND i.invited_user_id = '$user_id' AND c.status IN('PENDING_DEPOSIT') AND dr.currency='USD'");;

        return ['total'=>!empty($contracts) ? count($contracts) : 0, 'data'=>$contracts, 'total_deposit'=>$total_amount];
    }

    public static function getBankInProgressBidsSummary($bank_id){
        $data = self::getMyBids($bank_id);

        $size = !empty($data) ? count($data) : 0;
        $pending_total['USD'] = db::getCell("SELECT SUM(dr.amount) FROM offers o,invited i,depositor_requests dr WHERE dr.id = i.depositor_request_id AND o.invitation_id = i.id AND i.invited_user_id = '$bank_id' AND o.offer_status IN('ACTIVE','CONFIRMED') AND dr.currency='USD'");
        $pending_total['CAD'] = db::getCell("SELECT SUM(dr.amount) FROM offers o,invited i,depositor_requests dr WHERE dr.id = i.depositor_request_id AND o.invitation_id = i.id AND i.invited_user_id = '$bank_id' AND o.offer_status IN('ACTIVE','CONFIRMED') AND dr.currency='CAD'");

        return ['total'=>$size, 'data'=>$data, 'total_deposit'=>$pending_total];
    }

    public static function getPostRequestsForBank($user_id,$requireAuth=false){
        if ($requireAuth){
            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
        }
        $utc_now = Model::utcDateTime();
        $query = "SELECT dr.* FROM depositor_requests dr LEFT JOIN invited i ON i.depositor_request_id = dr.id LEFT JOIN offers o ON o.invitation_id = i.id WHERE dr.request_status IN('ACTIVE') AND i.invited_user_id='$user_id' AND o.id IS NULL AND dr.closing_date_time >='$utc_now' ORDER BY dr.reference_no,dr.id DESC";;
        $requests = db::getrecords($query);

        $size = !empty($requests) ? count($requests) : 0;
        $total_amount['USD'] = db::getCell("SELECT SUM(dr.amount) FROM depositor_requests dr LEFT JOIN invited i ON i.depositor_request_id = dr.id LEFT JOIN offers o ON o.invitation_id = i.id WHERE dr.request_status IN('ACTIVE') AND i.invited_user_id='$user_id' AND o.id IS NULL AND dr.currency='USD' AND dr.closing_date_time >= '$utc_now' ");
        $total_amount['CAD'] = db::getCell("select SUM(dr.amount) FROM depositor_requests dr LEFT JOIN invited i ON i.depositor_request_id = dr.id LEFT JOIN offers o ON o.invitation_id = i.id WHERE dr.request_status IN('ACTIVE') AND i.invited_user_id='$user_id' AND o.id IS NULL AND dr.currency='CAD' AND dr.closing_date_time >= '$utc_now' ");

        return ['total'=>$size, 'data'=>$requests,'total_deposit'=>$total_amount];
    }

    public static function getBankHistoryBids($user_id){
        $invited_requests = db::getrecords("SELECT i.depositor_request_id, i.invited_user_id, i.id as invitation_id, dr.product_id, dr.reference_no as request_reference_no, dr.amount, dr.term_length, dr.term_length_type, dr.currency, dr.user_id, dr.closing_date_time, dr.request_status, i.invitation_status FROM invited i, depositor_requests dr WHERE dr.id = i.depositor_request_id AND i.invited_user_id = '$user_id' ORDER BY dr.reference_no");
        return ['data'=>$invited_requests];
    }

    public static function getBankHistoryOffers($user_id){
        $invited_requests = db::getrecords("SELECT  o.modified_date as offer_modified_date, o.id as offer_id, o.offer_status, o.interest_rate_offer, o.reference_no as offers_reference_no, i.depositor_request_id, i.invited_user_id, i.id as invitation_id, dr.product_id, dr.reference_no as request_reference_no, dr.amount, dr.term_length, dr.term_length_type, dr.currency, dr.user_id, dr.closing_date_time, dr.request_status, i.invitation_status,o.rate_type,o.prime_rate,o.rate_operator,o.fixed_rate,o.created_date FROM depositor_requests dr LEFT JOIN invited i ON dr.id=i.depositor_request_id LEFT JOIN offers o ON o.invitation_id=i.id LEFT JOIN deposits c ON o.id=c.offer_id WHERE i.invited_user_id = '$user_id' AND o.offer_status IN ('NOT_SELECTED','EXPIRED','REQUEST_WITHDRAWN') AND c.id IS NULL AND o.id IS NOT NULL ORDER BY o.modified_date DESC");
        return ['data'=>$invited_requests];
    }

    public static function getBankHistoryContracts($user_id){
        $invited_requests = db::getrecords("SELECT c.modified_at as contract_modified_at, c.offered_amount, c.status as contract_status, o.id as offer_id, o.interest_rate_offer, c.reference_no as contract_reference_no, i.depositor_request_id, i.invited_user_id, i.id as invitation_id, dr.product_id, dr.reference_no as request_reference_no, dr.amount, dr.term_length, dr.term_length_type, dr.currency, dr.user_id, dr.closing_date_time, dr.request_status, i.invitation_status,o.rate_type,o.prime_rate,o.rate_operator,o.fixed_rate,c.created_at FROM invited i, depositor_requests dr, deposits c,offers o WHERE o.invitation_id=i.id AND o.id=c.offer_id AND dr.id=i.depositor_request_id AND i.invited_user_id = '$user_id' AND c.status IN('MATURED','WITHDRAWN','INCOMPLETE') ORDER BY c.modified_at DESC");
        return ['data'=>$invited_requests];
    }

    public static function getMyBids($user_id){
        return self::getOffersByUserID($user_id);
    }

    public static function getOffersByUserID($user_id){
        return db::getrecords("SELECT o.*,i.invited_user_id,i.depositor_request_id FROM offers o,invited i WHERE o.invitation_id = i.id AND i.invited_user_id = '$user_id' AND o.offer_status IN('ACTIVE','CONFIRMED') order by o.reference_no,o.id DESC");
    }

    public static function getOffersByRequestID($r_id,$requiresAuth=false){
        if ($requiresAuth){
            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
            return db::getRecords("SELECT o.*,i.invited_user_id,i.depositor_request_id FROM offers o,invited i,depositor_requests dr WHERE o.invitation_id = i.id AND i.depositor_request_id = '$r_id'AND dr.id=i.depositor_request_id AND dr.user_id='$user_id' AND o.offer_status IN('ACTIVE','SELECTED') order by o.reference_no DESC");
        }
        return db::getRecords("SELECT o.*,i.invited_user_id,i.depositor_request_id FROM offers o,invited i WHERE o.invitation_id = i.id AND i.depositor_request_id = '$r_id' AND o.offer_status IN('ACTIVE','SELECTED') order by o.reference_no DESC");
    }

    public static function getAllOffersByRequestID($r_id,$requiresAuth=false){
        if ($requiresAuth){
            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
            return db::getRecords("SELECT o.*,i.invited_user_id,i.depositor_request_id FROM offers o,invited i,depositor_requests dr WHERE o.invitation_id = i.id AND i.depositor_request_id = '$r_id'AND dr.id=i.depositor_request_id AND dr.user_id='$user_id' order by o.reference_no DESC");
        }
        return db::getRecords("SELECT o.*,i.invited_user_id,i.depositor_request_id FROM offers o,invited i WHERE o.invitation_id = i.id AND i.depositor_request_id = '$r_id' order by o.reference_no DESC");
    }

    public static function getOfferByID($id,$requiresInvitation=false){
        if ($requiresInvitation){
            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
            return db::getRecord("SELECT o.*,i.invited_user_id,i.depositor_request_id FROM offers o,invited i WHERE o.invitation_id = i.id AND o.id = '$id' AND i.invited_user_id='$user_id' order by o.reference_no DESC");
        }
        return db::getRecord("SELECT o.*,i.invited_user_id,i.depositor_request_id FROM offers o,invited i WHERE o.invitation_id = i.id AND o.id = '$id' order by o.reference_no DESC");
    }

    public static function getInterest($rate,$is_variable=false,$rate_operator=null, $show_tool_tip=false){
        if ($is_variable){
            $system_setting = Core::getSystemSettings('prime_rate');
            $prime_rate = $system_setting['value'];
            if (!empty($prime_rate)){
                $tool_tip_title="Prime Rate: ".$prime_rate."% ".$rate_operator." Spread Rate: ".$rate.'%';
                $tool_tip="<a href='javascript:void()' data-toggle='tooltip' title='".$tool_tip_title."'><i class='fa fa-info-circle'></i></a>";
                if ($rate_operator=="+"){
                    $return = Model::getInterest($prime_rate+$rate);
                }else{
                    $return = Model::getInterest($prime_rate-$rate);
                }
                if($show_tool_tip){
                    return $return.' '.$tool_tip;
                }
                return $return;
            }
        }
       return Model::getInterest($rate);
    }

    public static function getBankCompletedContracts($user_id){
        return db::getrecords("SELECT c.*,i.depositor_request_id,i.invited_user_id,dr.amount,dr.currency,dr.user_id,dr.closing_date_time,dr.term_length_type,dr.term_length,dr.product_id,o.interest_rate_offer,o.maximum_amount,o.minimum_amount,o.id as offer_id,o.prime_rate,o.rate_operator,o.fixed_rate,o.rate_type FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND i.invited_user_id = '$user_id' AND c.status IN('ACTIVE') ORDER BY c.maturity_date,id ASC");
    }

    public static function getBankCompletedContractsReports($user_id,$from="",$to=""){
        DepositorModel::fixSqlModesError();
        $sql = "SELECT c.gic_number, c.reference_no,c.offered_amount,c.id as contract_id,c.maturity_date,c.gic_start_date,i.depositor_request_id,i.invited_user_id,dr.product_id,dr.amount,dr.currency,dr.user_id,dr.closing_date_time,dr.term_length_type,dr.term_length,o.interest_rate_offer,o.maximum_amount,o.minimum_amount,o.id as offer_id,o.interest_rate_offer,dr.lockout_period_days,o.rate_type,o.prime_rate,o.rate_operator,o.fixed_rate FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND i.invited_user_id = '$user_id' AND c.status IN('ACTIVE')";

        $demographic_user_data = AuthModel::getUserDemographicData($user_id);
        if ( empty($from) ) {
            $from = date('d-m-Y', strtotime("-90 days"));
        }

        if ( empty($to) ) {
            $to = date('d-m-Y');
        }

        $utc_from = Model::dateTimeToUTC('Y-m-d',$from,$demographic_user_data['timezone']);
        $utc_to = Model::dateTimeToUTC('Y-m-d',$to,$demographic_user_data['timezone']);
        $sql .= " AND DATE(c.gic_start_date) BETWEEN '" . $utc_from . "' AND '" . $utc_to. "'";

        $sql.=" ORDER BY c.reference_no DESC";
        return db::getrecords($sql);
    }

}
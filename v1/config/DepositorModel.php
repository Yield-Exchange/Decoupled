<?php
    class DepositorModel{

        public static function getDepositors(){
            $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id";
            $sql_.=" AND rt.description='Depositor' ";
            return db::getRecords($sql_);
        }

        public static function generateUniqueDepositorID( $start = 25001 ){

            $depositorID = db::getCell("SELECT depositorID FROM depositer ORDER BY depositorID DESC LIMIT 1");
            if(empty($depositorID)){

                $depositors = db::getCell("SELECT COUNT(*) FROM depositer WHERE depositorID='$start'");
                if ($depositors > 0){
                    return self::generateUniqueDepositorID($start+1);
                }

                return $start;
            }

            $depositorID++;
            $depositors = db::getCell("SELECT COUNT(*) FROM depositer WHERE depositorID='$depositorID' ");

            if ($depositors > 0){
                return self::generateUniqueDepositorID($depositorID+1);
            }

            return $depositorID;
        }

        public static function generateOfferContractID( $dep_ref, $start = 1 ){

            $new_dep_ref = $dep_ref.$start;
            $contracts = db::getCell("SELECT COUNT(*) FROM deposits WHERE reference_no='$new_dep_ref'");

            if ($contracts > 0){
                return self::generateOfferContractID($dep_ref,$start+1);
            }

            return $new_dep_ref;
        }

        public static function getDepositorDateTime($time_zone){
            $date = new DateTime("now", new DateTimeZone(Model::formattedTimezone($time_zone)) );
            return $date->format('Y-m-d H:i:s');
        }

        public static function getDepositorDoc($id){
            return db::getRecord("SELECT * FROM depositors WHERE user_id='$id'");
        }

        public static function setDepositorTimeZoneSystem($user_data){
            $depositor_demographic_data = AuthModel::getUserDemographicData($user_data['id']);
            date_default_timezone_set(Model::formattedTimezone($depositor_demographic_data['timezone']));
        }

        public static function getDepositorRequestsByUserID($id){
            $utc_date_now = (new DateTime("now", new DateTimeZone("UTC")))->format('Y-m-d H:i:s');
            $query = "select * from depositor_requests where user_id='$id' AND request_status IN('ACTIVE') ";
            $query_history_condition = " AND date_of_deposit >= '$utc_date_now' AND ( closing_date_time >= '$utc_date_now' || (closing_date_time < '$utc_date_now' AND EXISTS (SELECT i.* FROM invited i WHERE i.depositor_request_id = depositor_requests.id AND i.invitation_status IN('PARTICIPATED')))  ) ";
            $requests = db::getrecords($query.$query_history_condition."  order by closing_date_time,id asc");

            $size = !empty($requests) ? count($requests) : 0;
            $total_amount['USD'] = db::getCell("select SUM(amount) from depositor_requests where user_id='$id'  AND request_status IN('ACTIVE') AND date_of_deposit >= '$utc_date_now' AND currency='USD'".$query_history_condition);
            $total_amount['CAD'] = db::getCell("select SUM(amount) from depositor_requests where user_id='$id' AND request_status IN('ACTIVE') AND date_of_deposit >= '$utc_date_now' AND currency='CAD'".$query_history_condition);

            return ['total'=>$size, 'data'=>$requests,'total_deposit'=>$total_amount];
        }

        public static function getDepositorHistoryRequests($id){
            $depositor_requests = db::getrecords("SELECT dr.* FROM depositor_requests dr WHERE dr.user_id = '$id' ORDER BY dr.reference_no");
            return ['total'=>!empty($depositor_requests) ? count($depositor_requests) : 0, 'data'=>$depositor_requests];
        }

        public static function getDepositorRequestsHistory($id){
            $utc_date_now = (new DateTime("now", new DateTimeZone("UTC")))->format('Y-m-d H:i:s');
            $query_history_condition="( date_of_deposit < '$utc_date_now' AND NOT EXISTS (SELECT o.* FROM offers o, invited i WHERE i.id = o.invitation_id AND i.depositor_request_id = dr.id AND o.offer_status IN('SELECTED')) OR closing_date_time < '$utc_date_now' AND NOT EXISTS (SELECT i.* FROM invited i WHERE i.depositor_request_id = dr.id AND i.invitation_status IN('PARTICIPATED')) )";
             $depositor_requests = db::getrecords("SELECT dr.* FROM depositor_requests dr LEFT JOIN invited i ON dr.id=i.depositor_request_id LEFT JOIN offers o ON o.invitation_id=i.id LEFT JOIN deposits c ON o.id=c.offer_id  WHERE dr.user_id = '$id' AND ( dr.request_status IN('EXPIRED','NO_OFFERS_RECEIVED','WITHDRAWN') OR ($query_history_condition) ) AND c.id IS NULL GROUP BY dr.id ORDER BY dr.modified_date DESC");
            return ['total'=>!empty($depositor_requests) ? count($depositor_requests) : 0, 'data'=>$depositor_requests];
        }

        public static function getDepositorContractHistory($id){
            $depositor_requests = db::getrecords("SELECT c.modified_at as contract_modified_at, o.interest_rate_offer, i.invited_user_id, c.id as contract_id, dr.*,c.reference_no as contract_reference_no,c.status as contract_status, c.gic_number,c.offered_amount,o.rate_type,o.prime_rate,o.rate_operator,o.fixed_rate,c.created_at FROM depositor_requests dr,deposits c,offers o,invited i WHERE o.invitation_id=i.id AND o.id=c.offer_id AND dr.id=i.depositor_request_id AND dr.user_id = '$id' AND c.status IN('MATURED','WITHDRAWN','INCOMPLETE') ORDER BY c.modified_at DESC");
            return ['total'=>!empty($depositor_requests) ? count($depositor_requests) : 0, 'data'=>$depositor_requests];
        }

        public static function getPostRequestsByID($id)
        {
            $get_cont = "select * from depositor_requests where id='$id'";
            return db::getRecord($get_cont);
        }

        public static function getInvitedBanksByID($id)
        {
            $get_cont = "SELECT u.*,i.invitation_status,i.invitation_date from invited i,users u WHERE i.invited_user_id = u.id AND i.depositor_request_id = '$id'";
            return db::getRecords($get_cont);
        }

        public static function getDepositorsPendingContracts($user_id){
            $contracts = db::getrecords("SELECT c.*,i.depositor_request_id,i.invited_user_id,dr.term_length,dr.term_length_type,dr.amount,dr.currency,dr.user_id,dr.closing_date_time,dr.date_of_deposit, dr.product_id,o.interest_rate_offer,o.maximum_amount,o.minimum_amount,o.rate_held_until,o.rate_type,o.prime_rate,o.rate_operator FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id' AND c.status IN('PENDING_DEPOSIT') ORDER BY dr.date_of_deposit,dr.id ASC");

            $total_amount['USD'] = db::getCell("SELECT SUM(c.offered_amount) FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id' AND c.status IN('PENDING_DEPOSIT') AND dr.currency='USD'");
            $total_amount['CAD'] = db::getCell("SELECT SUM(c.offered_amount) FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id' AND c.status IN('PENDING_DEPOSIT') AND dr.currency='CAD'");
            return ['total'=>!empty($contracts) ? count($contracts) : 0, 'data'=>$contracts, 'total_deposit'=>$total_amount];
        }

        public static function getDepositorsActiveContracts($user_id){
            $contracts = db::getrecords("SELECT c.*,i.depositor_request_id,i.invited_user_id,dr.amount,dr.currency,dr.user_id,dr.closing_date_time,dr.product_id,dr.term_length,dr.term_length_type,o.interest_rate_offer,o.maximum_amount,o.minimum_amount, o.prime_rate, o.rate_operator,o.fixed_rate, o.rate_type
                                                FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id' AND c.status IN('ACTIVE') ORDER BY c.maturity_date,id ASC");

            $total_amount['USD'] = db::getCell("SELECT SUM(c.offered_amount) FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id' AND c.status IN('ACTIVE') AND dr.currency='USD'");
            $total_amount['CAD'] = db::getCell("SELECT SUM(c.offered_amount) FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id' AND c.status IN('ACTIVE') AND dr.currency='CAD'");

            return ['total'=>!empty($contracts) ? count($contracts) : 0, 'data'=>$contracts, 'total_deposit'=>$total_amount];
        }

        public static function fixSqlModesError(){
            db::query("set global sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';");
            db::query("set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';");
        }

        public static function getDepositorsContractsReports($user_id,$from="",$to=""){
            self::fixSqlModesError();
            $sql = "SELECT c.gic_number,c.maturity_date,c.reference_no,c.offered_amount,c.id as contract_id,c.gic_start_date,i.depositor_request_id,i.invited_user_id,dr.amount,dr.currency,dr.user_id,dr.closing_date_time,dr.product_id,dr.term_length,dr.term_length_type,dr.lockout_period_days,o.interest_rate_offer,o.maximum_amount,o.minimum_amount,o.rate_type,o.prime_rate,o.rate_operator,o.fixed_rate
                                                FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id' AND c.status IN('ACTIVE')";

            $sql_sum = "SELECT SUM(dr.amount) FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id' AND c.status IN('ACTIVE') ";

            $demographic_user_data = AuthModel::getUserDemographicData($user_id);
            if ( empty($from) ) {
                $from = date('d-m-Y', strtotime("-90 days"));
            }

            if ( empty($to) ) {
                $to = date('d-m-Y');
            }

            $utc_from = Model::dateTimeToUTC('Y-m-d',$from,$demographic_user_data['timezone']);
            $utc_to = Model::dateTimeToUTC('Y-m-d',$to,$demographic_user_data['timezone']);
            $s =  " AND DATE(c.gic_start_date) BETWEEN '" . $utc_from . "' AND '" . $utc_to . "'";
            $sql .= $s;
            $sql_sum .= $s;

            $sql.=" ORDER BY c.reference_no DESC";
            $contracts = db::getrecords($sql);

            $total_amount['USD'] = db::getCell($sql_sum." AND dr.currency='USD'");
            $total_amount['CAD'] = db::getCell($sql_sum." AND dr.currency='CAD'");

            return ['total'=>!empty($contracts) ? count($contracts) : 0, 'data'=>$contracts, 'total_deposit'=>$total_amount];
        }

        public static function getContractByID($id, $requiresAuth=false){
            if ($requiresAuth){
                $user_data = AuthModel::getUserdata();
                $user_id = $user_data['id'];
                return db::getRecord("SELECT c.*,i.depositor_request_id,i.invited_user_id,dr.user_id,dr.amount,dr.currency,dr.user_id,dr.closing_date_time,dr.term_length_type,dr.term_length,o.interest_rate_offer,o.maximum_amount,o.minimum_amount,o.id as offer_id FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND c.id = '$id' AND dr.user_id='$user_id'");
            }
            return db::getRecord("SELECT c.*,i.depositor_request_id,i.invited_user_id,dr.user_id,dr.amount,dr.currency,dr.user_id,dr.closing_date_time,dr.term_length_type,dr.term_length,o.interest_rate_offer,o.maximum_amount,o.minimum_amount,o.id as offer_id FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND c.id = '$id'");
        }
        
    }
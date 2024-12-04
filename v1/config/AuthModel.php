<?php

use EasyCSRF\Exceptions\InvalidCsrfTokenException;
include __DIR__."/../config/Emails.php";
class AuthModel{

    public static $pages_restricted_for_non_invited_fi = ['index.php','index','account_settings.php','account_settings','faq.php','faq','comp.php','comp','history.php','history','c_details.php','c_details','h_details.php','h_details','reports','reports.php','notification','notification.php'];

    public static function isLoggedIn( $auto_logout=true ){
        if ( !empty($_SESSION["USERID"]) ){

            if ( !isset($_SESSION['LAST_LOGIN_ACTIVITY']) || ( isset($_SESSION['LAST_LOGIN_ACTIVITY']) && (gmmktime() - $_SESSION['LAST_LOGIN_ACTIVITY'] > 6*3600) ) ) {
                if ($auto_logout) {
                    Core::loginActivity("SESSION TIMED OUT AND LOGGED OUT", $_SESSION["USERID"]);
                    self::logout("required");
                }else{
                    Core::loginActivity("SESSION TIMED OUT", $_SESSION["USERID"]);
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    public static function blurPagesForNewNonInvitedFI($user){
        if ( ($user['is_non_partnered_fi'] ==1) ){
            $current_file = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
            if(in_array($current_file,self::$pages_restricted_for_non_invited_fi)){
                include BASE_DIR."/includes/pages/blur_page.php";
//                exit();
            }
        }
    }

    public static function logout($error=""){
        Core::loginActivity("LOGOUT SUCCESSFUL ", $_SESSION["USERID"]);
        unset($_SESSION["USERID"]);

        if ( !empty($error) ){
            echo "<script>location='" . BASE_URL . "/login?case=" . Core::urlValueEncrypt($error) . "'</script>";
        }else{
            echo "<script>location='" . BASE_URL . "/login'</script>";
        }
    }

    public static function silentLogout(){
        Core::loginActivity("LOGOUT SUCCESSFUL ", $_SESSION["USERID"]);
        unset($_SESSION["USERID"]);
    }

    public static function getUserdata(){
        if(self::isLoggedIn()) {
            $user_id = $_SESSION['USERID'];
            $sql = "SELECT u.*,ur.role_type_id,rt.description FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND u.id = '$user_id' ";
            $data=db::getRecord($sql);
            if (!empty($data)){

                $statuses = ["REJECTED","CLOSED","SUSPENDED","PENDING","DECLINED_TERMS_AND_CONDITIONS","DECLINED_INVITATION"];
                if ( in_array($data['account_status'], $statuses) ){
                    self::logout();
                    exit();
                }

                return $data;
            }else{
                self::logout("required");
            }
        }else{
            self::logout("required");
        }
    }

    public static function getUserDataByID($id){
        $user_id = $id;
        $sql = "SELECT u.*,ur.role_type_id,rt.description FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND u.id = '$user_id' ";
        $data=db::getRecord($sql);
        if (!empty($data)){
            return $data;
        }
        return [];
    }

    public static function getUserDemographicData( $user_id ){
        $sql = "SELECT * FROM `demographic_data` WHERE user_id='$user_id'";
        return db::getRecord($sql);
    }

    public static function generateToken(){
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // cloudflare users
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $sessionProvider = new EasyCSRF\NativeSessionProvider();
        $easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

        return $easyCSRF->generate('_token');
    }

    public static function generateCustomToken($token_name){
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // cloudflare users
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $sessionProvider = new EasyCSRF\NativeSessionProvider();
        $easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

        return $easyCSRF->generate($token_name);
    }

    public static function resendPin( $mail, $account_type = "depositer" ){
        $pin_ = rand(11024, 103540);
        $pin = password_hash($pin_, PASSWORD_BCRYPT);
        $toEmail = $mail;
        $_SESSION["pin_mail"] = $mail;

        $_a="fi";
        if ($account_type == "depositer"){
            $_a="inv";
        }

        
        $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = ? ";
        if ( $account_type=="bank" ) {
            $sql_.=" AND ( rt.description='Bank' || rt.description='Broker' ) ";
        }elseif ( $account_type=="depositer" ){
            $sql_.=" AND rt.description='Depositor' ";
        }else{
            $sql_.=" AND rt.description='' ";
        }
        $sql_.= " AND u.account_status NOT IN('CLOSED')";

        $user = db::preparedQuery($sql_, array("s", $mail));
        if (!empty($user[0])) {
            $user_id = $user[0]['id'];
            

            if ( trim($user[0]['account_status']) == "LOCKED" ){
                Core::loginActivity("TRIED LOGIN BUT ACCOUNT IS LOCKED", $user_id);
                echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt('locked')."'</script>";
                return;
            }

            if ( trim($user[0]['account_status']) == "ACTIVE" || ($user[0]['is_non_partnered_fi']==1) ) {
                $query = "Select * from authentication where user_id='$user_id'";
                $authentication = db::getRecord($query);
                $date_time_now = Model::utcDateTime();

                if (!empty($authentication)) {
                    $query = "Update authentication Set pin='$pin',created_at='$date_time_now' where user_id='$user_id'";
                } else {
                    $query = "Insert into authentication(pin,user_id,created_at) Values ('$pin','$user_id','$date_time_now') ";
                }
                db::query($query);

                Emails::newPin($toEmail,$pin_,$_a);
                Core::loginActivity("Resent Verification Code", $user_id);
                echo "<script>location='" . BASE_URL . "/login?a=" . $_a . "&&case=" . Core::urlValueEncrypt(1) . "'</script>";
                die();
            }else{
                Core::loginActivity("TRIED RESENDING PIN BUT ACCOUNT IS NOT ACTIVE", $user_id);
                echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt(4)."&&status=".Core::urlValueEncrypt($user[0]['account_status'])."'</script>";
            }
        }
        echo "<script>location='" . BASE_URL . "/login?a=".$_a."'</script>";
    }

    public static function depositorOrBankLogin( $account_type="depositor" ){
//        self::authCsrfToken();

        $_a="fi";
        if ($account_type == "depositor"){
            $_a="inv";
        }

        if( self::authCsrfToken(false) === false ){
            echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt('token_invalid_login')."'</script>"; exit();
        }

        $email = $_POST['email'];
        $pass = $_POST['pass'];

        if ( empty($email) || empty($pass) || !filter_var($email, FILTER_VALIDATE_EMAIL) ){
            echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt('required')."'</script>";
            return;
        }

        $limit = Core::invalidLoginResetCount();
        if( !empty($_COOKIE['limit_failed_login']) ) {
            if( $_COOKIE['limit_failed_login'] >= $limit ) {
                echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt(3)."'</script>"; exit();
            }
        }

        $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = ? ";
        if ( $account_type=="bank" ) {
            $sql_.=" AND ( rt.description='Bank' OR rt.description = 'Broker' )";
        }else if ( $account_type=="depositor" ){
            $sql_.=" AND rt.description='Depositor' ";
        }else{
            $sql_.=" AND rt.description='' ";
        }
        $sql_.= " AND u.account_status NOT IN('CLOSED')";

        $user = db::preparedQuery($sql_, array("s", $email));
        if (!empty($user[0])) {
            $user = $user[0];
            $user_id=$user['id'];
            $password = db::getRecord("SELECT * from passwords WHERE user_id='$user_id' ORDER BY id DESC");

            if (empty($password)){
                echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt('password_empty')."'</script>";
            }else if ( password_verify($pass, $password['hash'])) {

                if ( trim($user['account_status']) == "LOCKED" ){
                    Core::loginActivity("TRIED LOGIN BUT ACCOUNT IS LOCKED", $user_id);
                    echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt('locked')."'</script>";
                    return;
                }

//                if ($user['account_status']=="PENDING" && $user['is_non_partnered_fi']==1){
//                    $message = "Please accept the invitation sent to your email first, or reach out to the support team";
//                    $hide_login=true;
//                    include "includes/pages/auth_status.php"; exit();
//                }

                if (trim($user['account_status']) == "ACTIVE" || ( $user['is_non_partnered_fi']==1 && !in_array($user['account_status'],['DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS'])) ) {

                    if( ENVIRONMENT!='Production' && !empty($_REQUEST['_env']) ){
                        $_SESSION["USERID"] = $user_id;

                        db::query("Delete from authentication where user_id='" . $user_id . "'");

                        $_SESSION['LAST_LOGIN_ACTIVITY'] = gmmktime();
                        Core::loginActivity("LOGIN WITHOUT PIN SUCCESSFUL", $user_id);
                        self::resetUserLoginAttempts($user_id);

                        if ($account_type == "depositer") {
                            echo "<script>location='" . BASE_URL . "/depositor/index'</script>";
                        } else {
                            echo "<script>location='" . BASE_URL . "/bank/index'</script>";
                        }

                        exit();

                    }else{
                        $query = "select * from authentication where user_id='$user_id'";
                        $authentication = db::getcell($query);

                        $_pin = rand(11024, 103540);
                        $pin = password_hash($_pin, PASSWORD_BCRYPT);

                        $timeNow = Model::utcDateTime();
                        if (!empty($authentication[0])) {
                            $query = "update authentication set pin='$pin',created_at='$timeNow' where user_id='$user_id'";
                        } else {
                            $query = "insert into authentication(user_id,pin,created_at) values ('$user_id','$pin','$timeNow') ";
                        }
                        db::query($query);

                        $_SESSION["pin_mail"] = $email;
                        self::resetUserLoginAttempts( $user_id );

                        Emails::newPin($email,$_pin,$_a);
                        Core::loginActivity("TRIED LOGIN AND Verification Code Sent", $user_id);
                        echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt(1)."'</script>";
                    }

                } else {
                    Core::loginActivity("TRIED LOGIN BUT ACCOUNT IS NOT ACTIVE", $user_id);
                    echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt(4)."&&status=".Core::urlValueEncrypt($user['account_status'])."'</script>";
                }
            } else {
                Core::loginActivity("TRIED LOGIN BUT ACCOUNT PASSWORD IS WRONG", $user_id);
                self::trackWrongPinAttempts( $user_id );
                $_SESSION["pin_mail"] = $email;
                echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt(2)."'</script>";
            }
        } else {
            // LIMIT LOGIN FAILED ATTEMPTS 10 MINUTES
            $limit = Core::invalidLoginResetCount();
            if( !empty($_COOKIE['limit_failed_login']) ) {
                if( $_COOKIE['limit_failed_login'] < $limit ) {
                    $attempts = $_COOKIE['limit_failed_login'] + 1;
                    setcookie('limit_failed_login', $attempts, time()+60*10, '/');
                }
            } else {
                setcookie('limit_failed_login', 1, time()+60*10, '/');
            }
            echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt(3)."'</script>";
        }

    }

    public static function verifyPin( $account_type="depositer" ){
//          self::authCsrfToken();
        $_a="fi";
        if ($account_type == "depositer"){
            $_a="inv";
        }

        if( self::authCsrfToken(false) === false ){
            echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt('token_invalid')."'</script>"; exit();
        }

        $email = $_POST['email'];
        $pin = $_POST['pinn'];


        if (empty($email)){
            $email = $_SESSION["pin_mail"];
            if (empty($email)){
                echo "<script>location='/login?a=".$_a."'</script>";
                return;
            }
        }

        $sql_ = "SELECT auth.*,u.account_status,u.is_non_partnered_fi,password_changed FROM users u, authentication auth, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND ur.role_type_id=rt.id AND auth.user_id=u.id AND u.email = ? ";
        if ( $account_type=="bank" ) {
            $sql_.=" AND ( rt.description='Bank' OR rt.description = 'Broker' )";
        }else if ( $account_type=="depositor" || $account_type=="depositer" ){
            $sql_.=" AND rt.description='Depositor' ";
        }else{
            $sql_.=" AND rt.description='' ";
        }
        $sql_.=" AND u.account_status NOT IN('CLOSED')";

        $authenticate = db::preparedQuery($sql_, array("s", $email));

        $checkPin = !empty($authenticate[0]['pin']) ? $authenticate[0]['pin'] : "";
        $created_at = !empty($authenticate[0]['created_at']) ? $authenticate[0]['created_at'] : "";

        if (!empty($checkPin) && (password_verify($pin,$checkPin))) {
            $authenticate = $authenticate[0];

            if ($authenticate['account_status'] == "ACTIVE" || ( ( $authenticate['is_non_partnered_fi']==1 && !in_array($authenticate['account_status'],['DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS']))) ) {
                $date_time_now = new DateTime("now", new DateTimeZone("UTC"));
                $created_at_plus_5min = new DateTime($created_at,new DateTimeZone("UTC"));
                $created_at_plus_5min = $created_at_plus_5min->modify('+5 minutes');

                if ($created_at_plus_5min < $date_time_now) {
                    echo "<script>location='" . BASE_URL . "/login?a=" . $_a . "&&case=" . Core::urlValueEncrypt('expired') . "'</script>";
                    return;
                }
                $_SESSION["USERID"] = $authenticate['user_id'];

                db::query("Delete from authentication where user_id='" . $authenticate['user_id'] . "'");
                db::query("UPDATE users SET last_login='".$date_time_now->format('Y-m-d H:i')."' where id='" . $authenticate['user_id'] . "'");

                $_SESSION['LAST_LOGIN_ACTIVITY'] = gmmktime();
                Core::loginActivity("VERIFIED PIN SUCCESSFUL", $authenticate['user_id']);
                self::resetUserLoginAttempts($authenticate['user_id']);

                if ($account_type == "depositer") {
                    echo "<script>location='" . BASE_URL . "/depositor/index'</script>";
                } else {
                    if( $authenticate['is_non_partnered_fi']==1 ){
                        if ( in_array($authenticate['account_status'],['DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS']) ){
                            echo "<script>location='" . BASE_URL . "/login?a=" . $_a . "&&case=" . Core::urlValueEncrypt(4) . "'</script>";
                            exit();
                        }
                        if ( $authenticate['account_status'] == 'ACTIVE' && !$authenticate['password_changed'] ){
                            Core::alert("","Please complete account settings in order to use the Yield Exchange Limited Version","info",BASE_URL."/bank/non_fi_details");
//                            echo "<script>location='" . BASE_URL . "/bank/non_fi_details'</script>";
                            exit();
                        }
                        echo "<script>location='" . BASE_URL . "/bank/requests'</script>"; exit();
                    }
                    echo "<script>location='" . BASE_URL . "/bank/index'</script>";
                }
            }else{
                Core::loginActivity("VERIFIED PIN LOGIN BUT ACCOUNT IS NOT ACTIVE", $authenticate['user_id']);
                echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt(4)."&&status=".Core::urlValueEncrypt($authenticate['account_status'])."'</script>";
            }

        } else {
            if ( !empty($authenticate[0]['user_id']) ) {
                self::trackWrongPinAttempts($authenticate[0]['user_id']);
                Core::loginActivity("VERIFIED PIN FAILED", $authenticate[0]['user_id']);
            }
            echo "<script>location='".BASE_URL."/login?a=".$_a."&&case=".Core::urlValueEncrypt('error')."'</script>";
        }

    }

    public static function getUserPin( $user_id ){
        $sql_ = "SELECT auth.* FROM users u, authentication auth WHERE auth.user_id=u.id AND u.id = '$user_id' ";
        $result = db::preparedQuery($sql_, array("i", $user_id));
        return !empty($result[0]['pin']) ? $result[0]['pin'] : "";
    }

    public static function getUserLoginAttempts( $email,$account_type ){
        $sql_ = "SELECT u.failed_login_attempts FROM users u, user_role_types ur, role_types rt WHERE u.id=ur.user_id AND rt.id=ur.role_type_id AND u.email = '$email' ";
        if ( $account_type=="bank" ) {
            $sql_.=" AND ( rt.description='Bank' || rt.description='Broker' ) ";
        }elseif ( $account_type=="depositor" ){
            $sql_.=" AND rt.description='Depositor' ";
        }else{
            $sql_.=" AND rt.description='' ";
        }
        $sql_.=" AND u.account_status NOT IN('CLOSED')"; // not necessary as you cant login with and inactive status
        return db::getCell($sql_);
    }

    public static function resetUserLoginAttempts( $user_id ){
        $timeNow = Model::utcDateTime();
        db::preparedQuery("update users SET failed_login_attempts = 0, modified_date='$timeNow', modified_by='$user_id', modified_section='failed_login_attempts' where id=?", array("s", $user_id));
    }

    public static function trackWrongPinAttempts( $user_id ){
        $timeNow = Model::utcDateTime();
        $reset_count = Core::passwordResetCount();

        $authenticate = db::preparedQuery("select * from users where id=? ", array("s", $user_id));
        $authenticate = !empty($authenticate[0]) ? $authenticate[0] : [];

        if ( !empty($authenticate) ) {
            $attempts = $authenticate['failed_login_attempts'] > 0 ? $authenticate['failed_login_attempts'] : 0;
            if ( $attempts > $reset_count || ($attempts+1 > $reset_count) ){
                if ($authenticate['account_status'] != "LOCKED") {
                    RequestsModel::archiveTable($user_id,"users","Lock Control");
                    db::preparedQuery("update users SET account_status='LOCKED', modified_date='$timeNow', modified_by='$user_id', modified_section='account_status' where id=?", array("s", $user_id));
                    $subject = "Your account has been locked!";
                    $message = "Your account has been locked. Please contact info@yieldexchange.ca to unlock your account.";
                    Core::sendMail($subject, $authenticate['email'], $message, 'inv',true,false,[],true);

                    $subject = "User account has been locked!";
                    $message = "User account with email " . $authenticate['email'] . " is locked! Please do a followup on the account.";
                    Core::sendAdminEmail($subject, $message);
                }
                if ($attempts > $reset_count) {
                    return;
                }
            }
            $attempts +=1;
            db::preparedQuery("update users SET failed_login_attempts='$attempts', modified_date='$timeNow', modified_by='$user_id', modified_section='failed_login_attempts' where id=? ", array("s", $user_id));
        }
    }

    public static function resendRecoverPasswordLink($account_type = "depositor"){
        $_a = "fi";
        if ($account_type == "depositor") {
            $_a = "inv";
        }
        if(isset($_GET['email']) && isset($_GET['env'])){
                
            $email = $_GET['email'];

            $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = ? ";
            if ($account_type == "bank") {
                $sql_ .= " AND ( rt.description='Bank' OR rt.description = 'Broker' )";
            } elseif ($account_type == "depositor") {
                $sql_ .= " AND rt.description='Depositor' ";
            } else {
                $sql_ .= " AND rt.description='' ";
            }
            $sql_ .= " AND u.account_status NOT IN('CLOSED')";

            $user = db::preparedQuery($sql_, array("s", $email));
            $check = !empty($user[0]) ? $user[0] : "";
            if (!empty($check)) {
                if (trim($check['account_status']) == "ACTIVE" || ( $check['is_non_partnered_fi']==1 && !in_array($check['account_status'],['DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS']) )) {
                    $toEmail = $email;

                    $code = password_hash(time() . '_' . $email, PASSWORD_BCRYPT);
                    $user_id = $user[0]['id'];
                    $expiration_date = date('Y-m-d H:i:s', gmmktime() + (2 * 60 * 60)); // 2 hours UTC time

                    db::query("DELETE FROM password_resets WHERE user_id='$user_id'");
                    $sql = "INSERT INTO `password_resets`(`user_id`, `expiration_date`, `token`, `created_at`) VALUES ('$user_id','$expiration_date','$code','" . Model::utcDateTime() . "')";
                    db::query($sql);
                
                    $link = BASE_URL . '/reset_password_final?env='.$_a.'&code=' . $code;
                    $link2 = BASE_URL . '/'.$account_type.'/logic?env='. $_a .'& email=' . $email.'&resend_pwd=1';
                    $other_buttons = [['linkName'=>'Resend Password Link','link'=>$link2],['linkName'=>'Click Here To Reset Password','link'=>$link]];

                    Emails::passwordReset($toEmail,$_a,$other_buttons);
                    echo "<script>location='" . BASE_URL . "/login?a=" . $_a . "&&case=" . Core::urlValueEncrypt(7) . "'</script>";
                } else {
                    Core::loginActivity("VERIFIED PIN LOGIN BUT ACCOUNT IS NOT ACTIVE", $check['user_id']);
                    echo "<script>location='" . BASE_URL . "/login?a=" . $_a . "&&case=" . Core::urlValueEncrypt(4) . "&&status=" . Core::urlValueEncrypt($check['account_status']) . "'</script>";
                }
            } else {
                echo "<script>location='" . BASE_URL . "/reset_password?a=" . $_a . "&&case=" . Core::urlValueEncrypt('error') . "'</script>";
            }
        }else{
            echo "<script>location='" . BASE_URL . "/reset_password?a=" . $_a . "&&case=" . Core::urlValueEncrypt('recaptcha') . "'</script>";
        }
    }

    public static function recoverPassword( $account_type="depositor" ){
        self::authCsrfToken();

        $_a = "fi";
        if ($account_type == "depositor") {
            $_a = "inv";
        }
        

        if ( isset($_POST['recaptcha']) && Core::verifyCaptcha($_POST['recaptcha'],SECRET_KEY) ) {
            $email = $_POST['email'];

            $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = ? ";
            if ($account_type == "bank") {
                $sql_ .= " AND ( rt.description='Bank' OR rt.description = 'Broker' )";
            } elseif ($account_type == "depositor") {
                $sql_ .= " AND rt.description='Depositor' ";
            } else {
                $sql_ .= " AND rt.description='' ";
            }
            $sql_ .= " AND u.account_status NOT IN('CLOSED')";

            $user = db::preparedQuery($sql_, array("s", $email));
            $check = !empty($user[0]) ? $user[0] : "";
            if (!empty($check)) {
                if (trim($check['account_status']) == "ACTIVE" || ( $check['is_non_partnered_fi']==1 && !in_array($check['account_status'],['DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS']) )) {
                    $toEmail = $email;
                    
                    $code = password_hash(time() . '_' . $email, PASSWORD_BCRYPT);
                    $user_id = $user[0]['id'];
                    $expiration_date = date('Y-m-d H:i:s', gmmktime() + (2 * 60 * 60)); // 2 hours UTC time

                    db::query("DELETE FROM password_resets WHERE user_id='$user_id'");
                    $sql = "INSERT INTO `password_resets`(`user_id`, `expiration_date`, `token`, `created_at`) VALUES ('$user_id','$expiration_date','$code','" . Model::utcDateTime() . "')";
                    db::query($sql);

                    $link = BASE_URL . '/reset_password_final?env='.$_a.'&code=' . $code;
                    $link2 = BASE_URL . '/'.$account_type.'/logic?env='. $_a .'& email=' . $email.'&resend_pwd=1';
                    $other_buttons = [['linkName'=>'Resend Password Link','link'=>$link2],['linkName'=>'Click Here To Reset Password','link'=>$link]];

                    Emails::passwordReset($toEmail,$_a,$other_buttons);

                    echo "<script>location='" . BASE_URL . "/login?a=" . $_a . "&&case=" . Core::urlValueEncrypt(7) . "'</script>";
                } else {
                    Core::loginActivity("VERIFIED PIN LOGIN BUT ACCOUNT IS NOT ACTIVE", $check['user_id']);
                    echo "<script>location='" . BASE_URL . "/login?a=" . $_a . "&&case=" . Core::urlValueEncrypt(4) . "&&status=" . Core::urlValueEncrypt($check['account_status']) . "'</script>";
                }
            } else {
                echo "<script>location='" . BASE_URL . "/reset_password?a=" . $_a . "&&case=" . Core::urlValueEncrypt('error') . "'</script>";
            }
        }else{
            echo "<script>location='" . BASE_URL . "/reset_password?a=" . $_a . "&&case=" . Core::urlValueEncrypt('recaptcha') . "'</script>";
        }
    }

    public static function authCsrfToken($redirect=true,$is_post=true){
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // cloudflare users
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $sessionProvider = new EasyCSRF\NativeSessionProvider();
        $easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

        try {
            $easyCSRF->check('_token', $is_post ? $_POST['_token'] : $_REQUEST['_token']);
            return true;
        } catch(InvalidCsrfTokenException $e) {
            if ($redirect) {
                ob_clean();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }else{
                return false;
            }
        }
    }


    public static function authCustomCsrfToken($token_custom_name){
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // cloudflare users
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $sessionProvider = new EasyCSRF\NativeSessionProvider();
        $easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

        try {
            $easyCSRF->check($token_custom_name, $_POST[$token_custom_name]);
        } catch(InvalidCsrfTokenException $e) {
            ob_clean();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public static function authAjaxCsrfToken(){
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // cloudflare users
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $sessionProvider = new EasyCSRF\NativeSessionProvider();
        $easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

        try {
            $easyCSRF->check('_token', $_POST['_token'],null,true);
            return true;
        } catch(InvalidCsrfTokenException $e) {
            return false;
        }
    }

    public static function depositorAndBankRegistration(){
        if ( isset($_POST['recaptcha']) && Core::verifyCaptcha($_POST['recaptcha'],SECRET_KEY) ) {
            self::authCsrfToken();

            $path = $_POST['path'];

            $timezone = $_POST['time_zone'];
            $name = ($path == 1 ? $_POST["institution_s"] : $_POST["institution"]);;
            $address = $_POST['address'];
            $address2 = $_POST['address2'];
            $city = $_POST['city'];
            $province = $_POST['province'];
            $postal = $_POST['postal'];
            $email = $_POST["email"];
            $telephone = $_POST["telephone"];
            $pass = $_POST["pass"];
            $c_pass = $_POST["cpass"];

            if ($c_pass != $pass) {
                echo "<script>location='" . BASE_URL . "/signup?case=" . Core::urlValueEncrypt(2) . "'</script>";
                die();
            }
            
            if ($path == 1) {
                // Check the Institution if has been added within the few seconds/minutes
                $name_exists = db::exists("SELECT * from users WHERE name ='$name' AND account_status IN('PENDING','ACTIVE','LOCKED','SUSPENDED','INVITED','REVIEWING')");
                if ($name_exists) {
                    echo "<script>location='" . BASE_URL . "/signup?case=" . Core::urlValueEncrypt('institution_exists') . "'</script>"; exit();
                }
            }

            $sql = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = '$email' ";
            if ($path == 1) {
                $role_id = 2;
                $sql .= " AND (rt.description='Bank' || rt.description='Broker') ";
            } elseif ($path == 3) {
                $role_id = 3;
                $sql .= " AND (rt.description='Broker' || rt.description='Bank') ";
            } else {
                $role_id = 4;
                $sql .= " AND rt.description='Depositor' ";
            }
            $sql .= " AND u.account_status NOT IN('CLOSED')";

            $user_exists = db::exists($sql);
            if ($user_exists) {
                echo "<script>location='" . BASE_URL . "/signup?case=" . Core::urlValueEncrypt(3) . "'</script>";
                die();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>location='" . BASE_URL . "/signup?case=" . Core::urlValueEncrypt('invalid_email') . "'</script>";
                die();
            }

            $dateTime = Model::utcDateTime();
            $sql_user = "INSERT INTO `users`(`name`, `email`, `account_opening_date`, `account_status`, `failed_login_attempts`) VALUES (?,?,?,?,?)";
            $user_id = db::preparedQuery($sql_user, array("ssssi", $name, $email, $dateTime, 'PENDING', 0));

            $sql_role = "INSERT INTO `user_role_types`(`user_id`, `role_type_id`) VALUES ('$user_id','$role_id')";
            db::insertRecord($sql_role);

            $hashed_password = password_hash($c_pass, PASSWORD_BCRYPT);

            $sql_password = "INSERT INTO `passwords`(`hash`,`user_id`, `created_at`, `updated_at`) VALUES ('$hashed_password','$user_id','$dateTime','$dateTime')";
            db::insertRecord($sql_password);

            $sql_demographic_data = "INSERT INTO `demographic_data`(`user_id`, `address1`, `address2`, `city`, `province`, `postal_code`, `timezone`, `created_at`, `updated_at`,`telephone`) VALUES (?,?,?,?,?,?,?,?,?,?)";
            db::preparedQuery($sql_demographic_data, array("isssssssss", $user_id, $address, $address2, $city, $province, $postal, $timezone, $dateTime, $dateTime, $telephone));

            $preference = db::getCell("SELECT id FROM `preferences` WHERE name='mute_notification'");
            db::query("INSERT INTO `user_prefences`(`user_id`, `preference_id`, `value`) VALUES ('" . $user_id . "','" . $preference . "','1')");

            $toEmail = $email;
            
            $env= ($path == 1 || $path == 3 ? 'inv' :  '');
            $link = "https://yieldexchange.tawk.help/";
            $other_buttons = [['linkName'=>'Visit FAQ','link'=>$link]];

            Emails::acccount_creation($toEmail,$env,$other_buttons);
            

            if ($path == 1 || $path == 3) {

                if ($path == 1) {
                    $short_term_credit = $_POST['short_term_credit'];
                    $deposit_insurance = $_POST['deposit_insurance'];
                    if (!empty($short_term_credit) && !empty($deposit_insurance)) {
                        db::query("INSERT INTO `credit_rating`(`user_id`, `credit_rating_type_id`, `deposit_insurance_id`) VALUES ('$user_id','$short_term_credit','$deposit_insurance')");
                    }
                }

                $admin_message = "Financial Institution <strong>" . $name . "</strong> created a new account with email: " . $email;
            } else {
                $admin_message = "Depositor <strong>" . $name . "</strong> created a new account with email: " . $email;
            }

            Core::sendAdminEmail("New Sign Up!", $admin_message);
            session_destroy();
            echo "<script>location='" . BASE_URL . "/login?case=" . Core::urlValueEncrypt('success') . "'</script>";
        }else{
            echo "<script>location='" . BASE_URL . "/signup?case=" . Core::urlValueEncrypt('recaptcha') . "'</script>";
        }
    }

    public static function recoverPasswordFinal( $account_type="depositor" ){
        self::authCsrfToken();

        $pass_reset = db::preparedQuery("SELECT * from password_resets where token=?", array("s", $_POST['code']));
        if ( empty($pass_reset[0]['user_id']) ){
            return "The link is invalid, please check your email and try again";
        }

        $pass_reset = $pass_reset[0];

        $pass = $_POST['pass'];
        $user_id=$pass_reset['user_id'];

        $hashed_password = password_hash($pass, PASSWORD_BCRYPT);
        $dateTime = Model::utcDateTime();

        if ( date('Y-m-d H:i:s',strtotime ( '+5 minutes' , strtotime($pass_reset['created_at']))) < $dateTime ){
            return "The link has expired, please try to reset the password again";
        }

        /*$_a="fi";
        if ($account_type=="depositor"){
            $_a="inv";
        }*/
        $_a = $_POST['env'];

        $response = self::checkIfProvidedPasswordHasBeenUsedBefore($user_id,$pass);
        if ( !empty($response) ){
            return $response;
        }

        $pass_counts = db::getRecords("SELECT * FROM `passwords` WHERE user_id='$user_id'");
        if ( !empty($pass_counts) && count($pass_counts) >= 10 ) {
            $first_password_id = db::getCell("SELECT id FROM `passwords` WHERE user_id='$user_id' ORDER BY id ASC LIMIT 1");
            db::query("DELETE FROM `passwords` WHERE id='$first_password_id'");
        }
        db::insertRecord("INSERT INTO `passwords`(`hash`,`user_id`, `created_at`, `updated_at`) VALUES ('$hashed_password','$user_id','$dateTime','$dateTime')");
        db::query("DELETE FROM password_resets WHERE user_id='$user_id'");

        $user_data = AuthModel::getUserDataByID($user_id);
        self::resetUserLoginAttempts($user_id);

        $usr_demographic_data = AuthModel::getUserDemographicData($user_id);
        $timezone = $usr_demographic_data['timezone'];
        $date_now = Model::utcDateTime();
        $datetime_from_utc = Model::dateTimeFromUTC('Y-m-d h:i a T',$date_now,$timezone);

        //$linkName = 'Admin Login';
        //$link = BASE_URL.'/YEI/admin/authenticate';
        $chat_link = BASE_URL;
        $resetLink = BASE_URL."/reset_password?a=".$_a; //BASE_URL."/YEI/admin/reset_password";
        $other_buttons =[];

       
        Emails::passwordResetConfirmation($user_data['email'],$_a,$datetime_from_utc,$resetLink,$chat_link,$other_buttons);

       return "success";
    }

    public static function checkIfProvidedPasswordHasBeenUsedBefore($user_id,$pass){
        $passwords = db::getRecords("SELECT * FROM `passwords` WHERE user_id='$user_id'");
        if (!empty($passwords)){
            foreach ($passwords as $password) {
                if (password_verify($pass,$password['hash'])){
                    return "Password has been used before, please enter a new one";
                }
            }
        }
    }

    public static function userPasswordHasExpired($user_id){
        $pass = db::getCell("SELECT created_at FROM `passwords` WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1");
        $demographic_user_data = AuthModel::getUserDemographicData($user_id);
        $created_at = Model::dateTimeToUTC("Y-m-d H:i:s",$pass,$demographic_user_data['timezone']);
        $created_at = new DateTime($created_at);

        $date_now = Model::utcDateTime();
        $date_now = new DateTime($date_now);

        $diff_days = $date_now->diff($created_at)->format("%a");
        if ($diff_days>= (30*6)){ // this password is more than 6 months old hence expired
            return true;
        }else{

            if ($diff_days <= 10){
                return $diff_days;
            }
        }

        return false;
    }

}

<?php

use EasyCSRF\Exceptions\InvalidCsrfTokenException;
//include __DIR__."/../config/Emails.php";
class AdminModel{

    public static function isLoggedIn(){

        if ( !empty($_SESSION["ADMIN_USERID"]) ){
            if ( !empty(AuthModel::getUserPin($_SESSION["ADMIN_USERID"])) ){
                unset($_SESSION["ADMIN_USERID"]);
                echo "<script>location='".BASE_URL."/YEI/admin/authenticate'</script>";
            }
            return true;
        }

        return false;
    }

    public static function getUserdata(){
        if(AdminModel::isLoggedIn()) {
            $user_id = $_SESSION['ADMIN_USERID'];
            $sql = "SELECT u.*,ur.role_type_id,rt.description FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND u.id = '$user_id' ";
            $data=db::getRecord($sql);
            if (!empty($data)){
                return $data;
            }else{
                unset($_SESSION["ADMIN_USERID"]);
                echo "<script>location='".BASE_URL."/YEI/admin/authenticate'</script>";
            }
        }else{
            echo "<script>location='".BASE_URL."/YEI/admin/authenticate'</script>";
        }
    }

    public static function login(){
        AuthModel::authCsrfToken();

        $username=$_POST['username'];
        $pass=$_POST['pass'];

        $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = ? AND rt.description='Admin' ";
        $user = db::preparedQuery($sql_, array("s", $username));

        if (!empty($user[0])) {
            $user = $user[0];
            $user_id = $user['id'];
            $password = db::getRecord("SELECT * from passwords WHERE user_id='$user_id' ORDER BY id DESC");

            if (empty($password)) {
                echo "<script>location='authenticate?case=".Core::urlValueEncrypt('wrong_credentials')."'</script>";
            } else if (password_verify($pass, $password['hash'])) {

                if ( trim($user['account_status']) == "LOCKED" ){
                    Core::loginActivity("TRIED LOGIN BUT ACCOUNT IS LOCKED", $user_id);
                    echo "<script>location='authenticate?case=".Core::urlValueEncrypt('locked')."'</script>";
                    return;
                }

                if (trim($user['account_status']) == "ACTIVE") {
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

                    $_SESSION["pin_mail"] = $username;
                    $toEmail = $username;

                    self::resetUserLoginAttempts( $user_id );

                    Emails::newPin($toEmail,$_pin,'admin');

                    Core::loginActivity("Admin TRIED LOGIN AND Verification Code Sent", $user_id);
                    echo "<script>location='authenticate?case=" . Core::urlValueEncrypt('pin_request') . "'</script>";
                }else{
                    Core::loginActivity("Admin TRIED LOGIN BUT ACCOUNT IS NOT ACTIVE", $user_id);
                    echo "<script>location='authenticate?case=".Core::urlValueEncrypt(4)."&&status=".Core::urlValueEncrypt($user['account_status'])."'</script>";
                }
            }else{
                Core::loginActivity("TRIED LOGIN BUT ACCOUNT PASSWORD IS WRONG", $user_id);
                self::trackWrongPinAttempts( $user_id );
                $_SESSION["pin_mail"] = $username;
                echo "<script>location='authenticate?case=".Core::urlValueEncrypt('wrong_credentials')."'</script>";
            }

        }else{
            echo "<script>location='authenticate?case=".Core::urlValueEncrypt('wrong_credentials')."'</script>";
        }
    }

    public static function getUserLoginAttempts( $email ){
        $sql_ = "SELECT u.failed_login_attempts FROM users u, user_role_types ur, role_types rt WHERE u.id=ur.user_id AND rt.id=ur.role_type_id AND u.email = '$email' ";
        $sql_.=" AND rt.description='Admin' AND u.account_status IN('ACTIVE','LOCKED')";
        return db::getCell($sql_);
    }

    public static function resetUserLoginAttempts( $user_id ){
        $timeNow = Model::utcDateTime();
        db::preparedQuery("update users SET failed_login_attempts = 0, modified_date='$timeNow', modified_by='$user_id', modified_section='failed_login_attempts' where id=?", array("s", $user_id));
    }

    public static function trackWrongPinAttempts( $user_id ){
        $timeNow = Model::utcDateTime();
        $pin_count = Core::passwordResetCount();

        $authenticate = db::preparedQuery("select * from users where id=? ", array("i", $user_id));
        $authenticate = !empty($authenticate[0]) ? $authenticate[0] : [];
        if ( !empty($authenticate) ) {
            $attempts = $authenticate['failed_login_attempts'] > 0 ? $authenticate['failed_login_attempts'] : 0;
            if ( $attempts > $pin_count || ($attempts+1 > $pin_count) ){

                if ($authenticate['account_status'] != "LOCKED") {
                    RequestsModel::archiveTable($user_id,"users","Lock Control");
                    db::preparedQuery("update users SET account_status='LOCKED', modified_date='$timeNow', modified_by='$user_id', modified_section='account_status' where id=?", array("s", $user_id));
                    $subject = "Your account has been locked!";
                    $message = "Your account has been locked. Please contact info@yieldexchange.ca to unlock your account.";
                    Core::sendMail($subject, $authenticate['email'], $message, 'admin',false,false);

                    $subject = "User account has been locked!";
                    $message = "User account with email " . $authenticate['email'] . " is locked! Please do a followup on the account.";
                    Core::sendAdminEMail($subject, $message);
                }

                if ($attempts > $pin_count) {
                    return;
                }
            }
            $attempts +=1;
            db::preparedQuery("update users SET failed_login_attempts='$attempts', modified_date='$timeNow', modified_by='$user_id', modified_section='failed_login_attempts' where id=? ", array("s", $user_id));
        }
    }

    public static function verifyPin(){
        AuthModel::authCsrfToken();

        $email=$_POST['email'];
        $pin=$_POST['pinn'];

        $sql_ = "SELECT auth.*,u.account_status FROM users u, authentication auth, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND ur.role_type_id=rt.id AND auth.user_id=u.id AND u.email = '$email' AND rt.description='Admin' AND u.account_status NOT IN('CLOSED')";
        $authenticate = db::preparedQuery($sql_, array("s", $email));
        $checkPin = !empty($authenticate[0]['pin']) ? $authenticate[0]['pin'] : "";

        if (!empty($checkPin) && (password_verify($pin, $checkPin))) {
            if ($authenticate[0]['account_status'] == "ACTIVE") {

                $date_time_now = new DateTime("now", new DateTimeZone("UTC"));
                $created_at_plus_5min = new DateTime($authenticate[0]['created_at'],new DateTimeZone("UTC"));
                $created_at_plus_5min = $created_at_plus_5min->modify('+5 minutes');

                if ($created_at_plus_5min < $date_time_now) {
                    echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate?case=" . Core::urlValueEncrypt('expired') . "'</script>";
                    return;
                }

                $_SESSION["ADMIN_USERID"] = $authenticate[0]['user_id'];
                $query = "Delete from authentication where user_id='" . $authenticate[0]['user_id'] . "'";
                db::query($query);

                $_SESSION['LAST_LOGIN_ACTIVITY'] = gmmktime();
                self::resetUserLoginAttempts($authenticate[0]['user_id']);

                Core::loginActivity("ADMIN VERIFIED PIN SUCCESSFUL", $authenticate[0]['user_id']);
                echo "<script>location='" . BASE_URL . "/YEI/admin/index'</script>";
            }else{
                Core::loginActivity("ADMIN VERIFIED PIN BUT ACCOUNT IS NOT ACTIVE", $authenticate[0]['user_id']);
                echo "<script>location='authenticate?case=".Core::urlValueEncrypt(4)."&&status=".Core::urlValueEncrypt($authenticate[0]['account_status'])."'</script>";
            }
        }else{
            if ( !empty($authenticate[0]['user_id']) ) {
                self::trackWrongPinAttempts($authenticate[0]['user_id']);
                Core::loginActivity("ADMIN VERIFYING PIN FAILED", $authenticate[0]['user_id']);
            }
            echo "<script>location='authenticate?case=" . Core::urlValueEncrypt('wrong_pin') . "'</script>";
        }
    }

    public static function silentLogout(){
        unset($_SESSION["ADMIN_USERID"]);
    }

    public static function logout($error=""){
        unset($_SESSION["ADMIN_USERID"]);
        if ( !empty($error) ){
            echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate?case=" . Core::urlValueEncrypt($error) . "'</script>";
        }else{
            echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate'</script>";
        }
    }

//    public static function authCsrfToken(){
//        $sessionProvider = new EasyCSRF\NativeSessionProvider();
//        $easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);
//
//        try {
//            $easyCSRF->check('_token', $_POST['_token']);
//        } catch(InvalidCsrfTokenException $e) {
////            echo $e->getMessage(); exit;
//            ob_clean();
//            header('Location: ' . $_SERVER['HTTP_REFERER']);
//        }
//    }

    public static function authCsrfToken($redirect=true){
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // cloudflare users
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $sessionProvider = new EasyCSRF\NativeSessionProvider();
        $easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

        try {
            $easyCSRF->check('_token', $_POST['_token']);
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

    public static  function resendRecoverPasswordLink(){

        if(isset($_GET['email'])){

            $email = $_GET['email'];

            $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = ? ";
            $sql_ .= " AND rt.description='Admin' ";
            $sql_ .= " AND u.account_status NOT IN('CLOSED')";

            $user = db::preparedQuery($sql_, array("s", $email));
            $check = !empty($user[0]) ? $user[0] : "";
            if (!empty($check)) {
                if (trim($check['account_status']) == "ACTIVE") {
                    $toEmail = $email;

                    $code = md5(time() . '_' . $email);

                    $user_id = $user[0]['id'];
                    $expiration_date = date('Y-m-d H:i:s', gmmktime() + (2 * 60 * 60)); // 2 hours

                    db::query("DELETE FROM password_resets WHERE user_id='$user_id'");
                    $sql = "INSERT INTO `password_resets`(`user_id`, `expiration_date`, `token`, `created_at`) VALUES ('$user_id','$expiration_date','$code','" . Model::utcDateTime() . "')";
                    db::query($sql);

                    $link = BASE_URL . '/YEI/admin/reset_password_final?code=' . urlencode($code);
                    $link2 = BASE_URL . '/YEI/admin/logic?email='. $email.'&resend_pwd=1';

                    $other_buttons = [['linkName'=>'Resend Password Link','link'=>$link2],['linkName'=>'Click Here To Reset Password','link'=>$link]];

                    Emails::passwordReset($toEmail,'admin',$other_buttons);

                    echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate?case=" . Core::urlValueEncrypt(7) . "'</script>";
                } else {
                    Core::loginActivity("Admin TRIED RESET PASSWORD BUT ACCOUNT IS NOT ACTIVE", $check['id']);
                    echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate?case=" . Core::urlValueEncrypt(4) . "&&status=" . Core::urlValueEncrypt($check['account_status']) . "'</script>";
                }
            } else {
                echo "<script>location='" . BASE_URL . "/YEI/admin/reset_password?case=" . Core::urlValueEncrypt('error') . "'</script>";
            }
        }else{
            echo "<script>location='" . BASE_URL . "/YEI/admin/reset_password?case=" . Core::urlValueEncrypt('error') . "'</script>";
        }
        

    }

    public static function recoverPassword(){
        if ( isset($_POST['recaptcha']) && Core::verifyCaptcha($_POST['recaptcha'],SECRET_KEY) ) {
            $email = $_POST['email'];
            self::authCsrfToken();

            $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = ? ";
            $sql_ .= " AND rt.description='Admin' ";
            $sql_ .= " AND u.account_status NOT IN('CLOSED')";

            $user = db::preparedQuery($sql_, array("s", $email));
            $check = !empty($user[0]) ? $user[0] : "";
            if (!empty($check)) {
                if (trim($check['account_status']) == "ACTIVE") {
                    $toEmail = $email;

                    $code = md5(time() . '_' . $email);

                    $user_id = $user[0]['id'];
                    $expiration_date = date('Y-m-d H:i:s', gmmktime() + (2 * 60 * 60)); // 2 hours

                    db::query("DELETE FROM password_resets WHERE user_id='$user_id'");
                    $sql = "INSERT INTO `password_resets`(`user_id`, `expiration_date`, `token`, `created_at`) VALUES ('$user_id','$expiration_date','$code','" . Model::utcDateTime() . "')";
                    db::query($sql);

                    $link = BASE_URL . '/YEI/admin/reset_password_final?code=' . urlencode($code);
                    $link2 = BASE_URL . '/YEI/admin/logic?email='. $email.'&resend_pwd=1';

                    $other_buttons = [['linkName'=>'Resend Password Link','link'=>$link2],['linkName'=>'Click Here To Reset Password','link'=>$link]];

                    Emails::passwordReset($toEmail,'admin',$other_buttons);

                    echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate?case=" . Core::urlValueEncrypt(7) . "'</script>";
                } else {
                    Core::loginActivity("Admin TRIED RESET PASSWORD BUT ACCOUNT IS NOT ACTIVE", $check['id']);
                    echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate?case=" . Core::urlValueEncrypt(4) . "&&status=" . Core::urlValueEncrypt($check['account_status']) . "'</script>";
                }
            } else {
                echo "<script>location='" . BASE_URL . "/YEI/admin/reset_password?case=" . Core::urlValueEncrypt('error') . "'</script>";
            }
        }else{
            echo "<script>location='" . BASE_URL . "/YEI/admin/reset_password?case=" . Core::urlValueEncrypt('error') . "'</script>";
        }
    }

    public static function resendPin( $mail ){
        $_pin = rand(11024, 103540);
        $pin = password_hash($_pin, PASSWORD_BCRYPT);

        $toEmail = $mail;
        $_SESSION["pin_mail"] = $mail;


        $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = ? ";
        $sql_.=" AND rt.description='Admin' AND u.account_status NOT IN('CLOSED')";

        $user = db::preparedQuery($sql_, array("s", $mail));
        if (!empty($user[0])) {
            $user_id = $user[0]['id'];
            

            if ( trim($user[0]['account_status']) == "LOCKED" ){
                Core::loginActivity("TRIED RESENDING PIN BUT ACCOUNT IS LOCKED", $user_id);
                echo "<script>location='".BASE_URL."/YEI/admin/authenticate?case=".Core::urlValueEncrypt('locked')."'</script>";
                return;
            }

            if (trim($user[0]['account_status']) == "ACTIVE") {
                $query = "Select * from authentication where user_id='$user_id'";
                $authentication = db::getRecord($query);

                $date_time_now = Model::utcDateTime();
                if (!empty($authentication)) {
                    $query = "Update authentication Set pin='$pin',created_at='$date_time_now' where user_id='$user_id'";
                } else {
                    $query = "Insert into authentication(pin,user_id,created_at) Values ('$pin','$user_id','$date_time_now') ";
                }
                db::query($query);

                Emails::newPin($toEmail,$_pin,'admin');
                Core::loginActivity("Resent Verification Code", $user_id);
                echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate?case=" . Core::urlValueEncrypt(1) . "'</script>";
                die();
            }else{
                Core::loginActivity("TRIED RESENDING PIN BUT ACCOUNT IS NOT ACTIVE", $user_id);
                echo "<script>location='".BASE_URL."/YEI/admin/authenticate?case=".Core::urlValueEncrypt(4)."&&status=".Core::urlValueEncrypt($user[0]['account_status'])."'</script>";
            }
        }
        echo "<script>location='" . BASE_URL . "/YEI/admin/authenticate'</script>";
    }

    public static function recoverPasswordFinal(){
        self::authCsrfToken();

        $pass_reset = db::preparedQuery("SELECT * from password_resets where token=?", array("s", trim(urldecode($_POST['code']))));
        if ( empty($pass_reset[0]['user_id']) ){
            return "The link is invalid, please check your email and try again";
        }

        $pass_reset = $pass_reset[0];
        $pass = $_POST['pass'];
        $user_id = $pass_reset['user_id'];

        $hashed_password = password_hash($pass, PASSWORD_BCRYPT);
        $dateTime = Model::utcDateTime();

        if ( date('Y-m-d H:i:s',strtotime ( '+5 minutes' , strtotime($pass_reset['created_at']))) < $dateTime ){
            return "The link has expired, please try to reset the password again";
        }

        $response = AuthModel::checkIfProvidedPasswordHasBeenUsedBefore($user_id,$pass);
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

        
        $chat_link = BASE_URL.'/YEI/admin/authenticate';
        $resetLink = BASE_URL."/YEI/admin/reset_password";
        $other_buttons =[['linkName'=>'Admin Login','link'=>BASE_URL.'/YEI/admin/authenticate']];
        $env ='admin';
       
        Emails::passwordResetConfirmation($user_data['email'],$env,$datetime_from_utc,$resetLink,$chat_link,$other_buttons);

        return "success";
       // echo "<script>location='".BASE_URL."/YEI/admin/authenticate'</script>";
    }
}
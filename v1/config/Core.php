<?php
require_once (__DIR__.'/ini.php');
require_once(__DIR__."/class.phpmailer.php");

class Core{

    protected static $encrypt_method = 'aes-128-ctr';
    protected static $encrypt_key = 'yield2020&%$';

    public function __construct(){
        self::$encrypt_key = openssl_digest(self::$encrypt_key, 'SHA256', TRUE); // convert ASCII keys to binary format
    }

    public static function sendMailSMTP($to,$message,$subject, $queued=false){
        if ( !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ) {
            if (!$queued) {
                $date_time = gmdate('Y-m-d H:i:s');
                $message = base64_encode($message);
                db::insertRecord("INSERT INTO `queued_emails`(`to`, `message`, `subject`, `status`, `created_at`, `updated_at`) VALUES ('$to','$message','$subject','PENDING','$date_time','$date_time')");
                return false;
            }
        }

        global $SMTP_HOST,$SMTP_ENCRYPTION,$SMTP_PORT,$SMTP_USERNAME,$SMTP_PASSWORD,$SMTP_FROM;

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = $SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = $SMTP_ENCRYPTION;
        $mail->Port = $SMTP_PORT;
        $mail->Username = $SMTP_USERNAME;
        $mail->Password = $SMTP_PASSWORD;
        $mail->From = $SMTP_FROM;
        $mail->FromName = "Yield Exchange";
        $mail->AddAddress($to);
        $mail->IsHTML(true);
        $mail->Subject = ENVIRONMENT!='Production' ? (ENVIRONMENT.' - '.$subject) : $subject;
        $mail->Body = $message;

        try {
            if(!$mail->Send()){
               return $mail->ErrorInfo;
        }else{
           return true;
            }
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    public static function sendMail($subject, $toEmail, $message_, $user_type='inv', $show_login = false, $show_register=false, $other_buttons=array(), $by_pass_queue=false,$logo_position="top",$header=""){
        $email_body='<div id="content-div">'.$message_.'</div>';

        $header = !empty($header) ? $header : $subject;

        if ($show_login || $show_register || !empty($other_buttons)) {
            $email_body .= '<div id="buttons-div">';

            if ($show_login && $user_type!='admin') {
                $email_body .= '<a href="'.BASE_URL.'/login?a='.$user_type.'" class="btn btn-outline-info btn-lg" id="button2">Login</a>';
            }

            if ($show_register) {
                $email_body .= '<a href="'.BASE_URL.'/signup" class="btn btn-info btn-lg" id="button1">Register</a>';
            }

            if (!empty($other_buttons)){
                $count_n = count($other_buttons);
                $i=1;
                foreach ($other_buttons as $button){
                    if($count_n > 1 && $i==1){
                       $email_body .= '<a href="'.$button['link'].'" style="background:white; color:blue;" class="btn btn-info btn-lg" id="button1">'.$button['linkName'].'</a>'; 
                   } else{
                    $email_body .= '<a href="'.$button['link'].'" class="btn btn-info btn-lg" id="button1">'.$button['linkName'].'</a>';
                   }
    
                $i++;
                }
            }
            $email_body.='</div>';
        }

        $message = '<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <title>Yield Exchange Inc | '.$subject.'</title>
    <style type="text/css">
        @import url("https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200&display=swap");
        body{
            background: #FAFBFC;
        }
        
        #header-box{
            height:  70px;
            font-family: "Nunito Sans", sans-serif;
            background:  #1D212A;
            display: inline-block;
            text-align: center;
            padding-top: 20px;
            width: 100%;
            vertical-align: middle;
        }
        #header-box > img{
            height: 35px;
            margin-top: 10px;
        }
        #body-box{
            min-height: 600px;
            background: white;
        }
        #body-box > div > h4{
            margin-top: 30px;
            width: 100%;
            text-align: center;
            font-family: "Nunito Sans", sans-serif;
            font-size: 22px;
            font-style: bold;
            font-weight: 400;
            margin-bottom: 12px;
        }
        #content-div{
            font-family: "Nunito Sans", sans-serif;
            text-align: left;
            vertical-align: top;
            font-size: 16px;
            line-height: 24px;
            letter-spacing: 0em;
            padding-left: 24px;
            padding-right: 24px;
            font-weight: 300;
        }
        #buttons-div{
            width: 100%;
            margin-top: 20px;
            display: inline-block;
            text-align: center;
        }
        #buttons-div > a{
            border-radius: 36px;
            padding: 8px 32px 8px 32px;
            font-size: 16px;
            transition: transform .2s; /* Animation */
        }
        #button1{
            background: #3656A6;
            border-color: #3656A6;
            margin-left: 2%;
            font-family: "Nunito Sans", sans-serif;
            color: white;
            min-width: 178px;
        }
        #button2{
            border-color: #3656A6!important;
            color:#3656A6!important;
            font-family: "Nunito Sans", sans-serif;
            min-width: 178px;
        }
        #divider-line{
            margin-top: 50px;
            border-top: 1px solid #ccc;
            margin-left: 2%;
            margin-right: 2%;
            width: 96%;
        }
        #button2:hover{
            background: white;
            transform: scale(1.1);
        }
        #button1:hover{
            transform: scale(1.1);
        }
        #social-links-div, #footer-links-div{
            width: 100%;
            display: inline-block;
            text-align: center;
            padding-top: 10px;
        }
        #disclaimer-div{
            font-family: "Nunito Sans", sans-serif;
            text-align: center;
            vertical-align: top;
            font-size: 16px;
            line-height: 24px;
            letter-spacing: 0em;
            font-weight: 300;
            padding-left: 24px;
            padding-right: 24px;
        }
        #disclaimer-div > a, #disclaimer-div > span > a{
            color: #3656A6;
        }
        #disclaimer-div > span{
            font-size: 13px;
        }
        #social-links-div > a{
            color: #444;
        }
        #footer-links-div{
            display: inline-block;
            text-align: center;
        }
        #footer-links-div > a{
            font-size: 13px;
            font-family: "Nunito Sans", sans-serif;
            color: #3656A6;
            border-right: 1px solid #ccc;
            border-radius: 0;
            padding-top: 0;
            padding-bottom: 0;
            font-weight: bold;
        }
        #footer-links-div > a:last-child{
            border-right:0;
        }
        .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            width: 100%;
        }
        table{
            width: 100%;
        }
        #first-td{
            width: 20%;
        }
        #second-td{
            width: 60%;
        }
        #third-td{
            width: 20%;
        }
        @media (min-width: 992px){
            .col-lg-12 {
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        @media (min-width: 768px){
            .col-md-12 {
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        @media (min-width: 576px) {
            .col-sm-12 {
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        @media (max-width: 768px) {
          #body-box > div > h4{
            margin-top: 20px;
          }
          #header-box{
            height:  50px;
          }
          #header-box > img{
            height: 25px;
            margin-top: 5px;
          }
          table{
            width: 100%;
          }
          #first-td{
            width: 10%;
          }
          #second-td{
            width: 80%;
          }
          #third-td{
            width: 10%;
          }
          #footer-links-div > a{
            font-size: 11px;
          }
          #body-box > div > #buttons-div > #button1{
            min-width: 150px;
            margin-bottom: 10px;
          }
          #body-box > div > #buttons-div > #button2{
            min-width: 150px;
            margin-bottom: 10px;
          }
        }
        .col-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .btn-group-lg>.btn, .btn-lg {
            padding: .5rem 1rem;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: .3rem;
        }
        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
        .btn-outline-info {
            color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        a{
            color: #007bff;
            text-decoration: none;
            background-color: transparent;
        }
        .btn-info {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
    </style>
</head>
<body>
<div class="row">
    <table>
        <tr>
            <td id="first-td"></td>
            <td id="second-td">
                <div class="col-sm-12 col-md-12 col-lg-12 col-12" id="body-wrapper">';

                if ($logo_position=="top") {
                    $message .= '<div class="row col-sm-12 col-md-12 col-lg-12 col-12" id="header-box">
                                <img src="' . BASE_URL . '/assets/images/logo_dark.png" alt="Yield Exchange Logo"/>
                            </div>';
                }

                    $message .='<div class="row col-sm-12 col-md-12 col-lg-12 col-12" id="body-box">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-12" style="width: 100%">
                            <h4>'.$header.'</h4>
                            '.$email_body;

                $message .= '<div id="divider-line"></div>';
                if ($logo_position=="bottom") {
                    $message .= '<div class="row col-sm-12 col-md-12 col-lg-12 col-12" id="header-box">
                                <img src="' . BASE_URL . '/assets/images/logo_dark.png" alt="Yield Exchange Logo"/>
                            </div>';
                }

                $message .= '<div id="social-links-div">
                                <a class="btn btn-link" href="https://twitter.com/yieldexchange"><img src="'.BASE_URL.'/assets/images/icons/twitter-brands.png" alt="Twitter" style="height: 20px"/> </a>
                                <a class="btn btn-link" href="//linkedin.com/company/ewob"><img src="'.BASE_URL.'/assets/images/icons/linkedin-brands.png" alt="LinkedIn" style="height: 20px"/></a>
                                <a class="btn btn-link" href="mailto:info@yieldexchange.ca"><img src="'.BASE_URL.'/assets/images/icons/envelope-solid.png" alt="Email" style="height: 20px"/></a>
                            </div>
                            <div id="disclaimer-div">
                                Not sure why you received this email?<br> <a href="mailto:info@yieldexchange.ca"><b>Email</b></a> us or use our <a href="'.BASE_URL.'"><b>Chat</b></a>.<br/><br/>
                                <span>&copy; '.date('Y').' <a target="_blank" href="'.BASE_URL.'"><b>Yield Exchange Inc</b></a></span>
                            </div>
                            <div id="footer-links-div">
                                <a class="btn btn-link" target="_blank" href="'.BASE_URL.'/faq"><b>FAQ</b></a>
                            </div>
                            <p style="color:white;opacity: 0">'.time().'</p> <!--Just to ensure the email is not trimmed as being repetitive-->
                        </div>
                    </div>
                </div>
            </td>
            <td id="third-td"></td>
        </tr>
    </table>
</div>
</body>
</html>';

        if ($user_type=='contact-us-form'){
            global $notification_email;
            $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND rt.description='Admin' ";
            $users = db::getRecords($sql_);
            if ( !empty($users) ){
                foreach ($users as $user){
                    self::sendMailSMTP($user['email'], $message, $subject,$by_pass_queue);
                }
                return true;
            }
            return self::sendMailSMTP($notification_email, $message, $subject,$by_pass_queue);
        }

        return self::sendMailSMTP($toEmail, $message, $subject,$by_pass_queue);
    }

    public static function sendAdminEmail($subject, $message){
        global $notification_email;
        $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND rt.description='Admin' ";
        $users = db::getRecords($sql_);
        if ( !empty($users) ){
            foreach ($users as $user){
                self::sendMail($subject, $user['email'], $message);
            }
            return true;
        }
        return self::sendMail($subject, $notification_email, $message);
    }

    public static function base64_url_encode($input) {
        return strtr(base64_encode($input), '+/=', '._-');
    }

    public static function base64_url_decode($input) {
        return base64_decode(strtr($input, '._-', '+/='));
    }

    public static function urlValueEncrypt($value){
        $value = str_replace(" ","",$value);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$encrypt_method));
        return self::base64_url_encode(bin2hex($iv) . openssl_encrypt($value, self::$encrypt_method, self::$encrypt_key, 0, $iv));
    }

    public static function urlValueDecrypt($value){
        $value = self::base64_url_decode($value);
        $iv_strlen = 2  * openssl_cipher_iv_length(self::$encrypt_method);
        if(preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $value, $regs)) {
            list(, $iv, $crypted_string) = $regs;
            if(ctype_xdigit($iv) && strlen($iv) % 2 == 0) {
                return openssl_decrypt($crypted_string, self::$encrypt_method, self::$encrypt_key, 0, hex2bin($iv));
            }
        }
        return ''; // failed to decrypt
    }

    public static function timeNow(){
        return date('Y-m-d H:i:s');
    }

    public static function render($data){
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    public static function formattedDateTime($time, $no_am_om=false){
        if ($no_am_om){
            return date('Y-m-d h:i',strtotime($time));
        }
        return date('Y-m-d h:i a',strtotime($time));
    }

    public static function getRatings( $user_id ){
        $sql="SELECT di.description as deposit_insurance,crt.description as credit_rating FROM credit_rating cr,deposit_insurance di,credit_rating_type crt WHERE cr.credit_rating_type_id = crt.id AND di.id = cr.deposit_insurance_id AND cr.user_id='$user_id'";
        return db::getRecord($sql);
    }

    public static function getSystemSettingsValue($key=null){
        $data = self::getSystemSettings($key);
        if( empty($data) ){
            return '';
        }

        return $data['value'];
    }

    public static function getSystemSettings($key=null){
        if ( $key==null ){
            return db::getRecord("SELECT * FROM system_settings");
        }

        return db::getRecord("SELECT * FROM system_settings WHERE `key`='$key'");
    }

    public static function getUserTimeZone($user_id){
        $timezone1 = "select timezone from demographic_data where user_id='$user_id'";
        $return = db::getcell($timezone1);
        if ( !empty($return) ){
            return $return;
        }
    }

    public static function activityLog($location){
        $user_id=0;
        if ( !empty($_SESSION["USERID"]) ){
            $user_id=$_SESSION["USERID"];
        }else if( !empty($_SESSION["ADMIN_USERID"]) ){
            $user_id=$_SESSION["ADMIN_USERID"];
        }

        if ($user_id > 0) {
            $location_from ="";
            $qry_ = "SELECT * FROM activity_logs WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1 ";
            $prev_activity_data = db::getRecord($qry_);
            if(!empty($prev_activity_data)){ $location_from =$prev_activity_data['location']; }

            $sql_ = "SELECT u.*,ur.role_type_id,rt.description FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND u.id = '$user_id' ";
            $user_data = db::getRecord($sql_);
            if (!empty($user_data)) {
                $sql="INSERT INTO `activity_logs`(`location`,`from_location`, `query_string`, `event_date`, `user_id`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?)";
                $query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "";
                db::preparedQuery($sql, array("ssssiss", $location, $location_from, $query_string, gmdate('Y-m-d H:i:s'), $user_data['id'], gmdate('Y-m-d H:i:s'), gmdate('Y-m-d H:i:s')));
            }
        }
    }

    public static function loginActivity($event_type, $userid=0){
        $sql="INSERT INTO `login_activities`(`event_time`, `activity_type`, `user_agent`, `user_id`) VALUES (?,?,?,?)";
        if ( $userid > 0 ) {
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
            db::preparedQuery($sql, array("sssi", gmdate('Y-m-d H:i:s'), $event_type, $user_agent, $userid));
        }
    }

    public static function alert($header,$message,$type, $redirect_url=null,$scripts_loaded=false, $timer=true){ //error success info
        if (!$scripts_loaded) {
            echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
            echo '<script src="'.BASE_URL.'/assets/js/sweetalert.min.js"></script>';
        }
        if ( !empty($redirect_url) ) {
            if($timer) {
                echo "<script>$(document).ready(function (){ swal({title:'" . $header . "', text:'" . $message . "', type:'" . $type . "',timer: 4000}).then((value) => { location='" . $redirect_url . "' }); });</script>";
            }else{
                echo "<script>$(document).ready(function (){ swal({title:'" . $header . "', text:'" . $message . "', type:'" . $type . "'}).then((value) => { location='" . $redirect_url . "' }); });</script>";
            }
        }else{
            if($timer) {
                echo "<script>$(document).ready(function (){ swal({title:'" . $header . "', text:'" . $message . "', type:'" . $type . "',timer: 4000}); });</script>";
            }else{
                echo "<script>$(document).ready(function (){ swal({title:'" . $header . "', text:'" . $message . "', type:'" . $type . "'}); });</script>";
            }
        }
    }

    public static function isUserBank(){
        if ( AuthModel::isLoggedIn() ){
            $user_data = AuthModel::getUserdata();
            if ( !empty($user_data) ){
                if (trim(strtolower($user_data['description'])) == "bank"
                    || trim(strtolower($user_data['description'])) == "broker"){
                    return true;
                }
            }
        }

        return false;
    }

    public static function isUserDepositor(){
        if ( AuthModel::isLoggedIn() ){
            $user_data = AuthModel::getUserdata();
            if ( !empty($user_data) ){
                if (trim(strtolower($user_data['description'])) == "depositor"){
                    return true;
                }
            }
        }

        return false;
    }

    public static function isUserAdmin(){
        if ( AdminModel::isLoggedIn() ){
            $user_data = AdminModel::getUserdata();
            if ( !empty($user_data) ){
                if (trim(strtolower($user_data['description'])) == "admin"){
                    return true;
                }
            }
        }

        return false;
    }

    public static function cleanData(&$str){
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }

    public static function getUserPreferences( $user_id=0 ){
        if ( AuthModel::isLoggedIn() ) {
            $user_data = AuthModel::getUserdata();
            if ( !empty($user_data) || $user_id != 0 ) {
                $user_id = $user_id != 0 ? $user_id : $user_data['id'];
                $sql = "SELECT p.*,up.value as p_value FROM preferences p,user_prefences up WHERE up.user_id = '$user_id'";
                $data = db::getRecords($sql);
                $new_data = array();
                if (!empty($data)) {
                    foreach ($data as $datum) {
                        $new_data[$datum['name']] = $datum['p_value'];
                    }
                }
                return $new_data;
            }
        }
        return [];
    }

    public static function getSetTimezone(){
        $date = new DateTime();
        $timeZone = $date->getTimezone();
        return $timeZone->getName();
    }

    public static function verifyCaptcha($response,$CAPTCHA_SECRET){
        if ( in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ){
            return true;
        }

        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // cloudflare users
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $post_data = http_build_query(
            array(
                'secret' => $CAPTCHA_SECRET,
                'response' => $response,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            )
        );
        $context  = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);

        if ($result->success) {
            return true;
        }
        return false;
    }

    public static function previousUrl(){
        return $_SERVER['HTTP_REFERER'];
    }

    public static function validate_url($url) {
        $path = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encoded_path), $url);

        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function startsWith($string, $startString) {
        return preg_match('#^' . $startString . '#', $string) === 1;
    }

    public static function passwordResetCount(){
        return 5;
    }

    public static function invalidLoginResetCount(){
        return self::passwordResetCount();
    }

}


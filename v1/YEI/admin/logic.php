<?php
session_start();
include __DIR__."/../../config/db.php";
include __DIR__."/../../config/Model.php";

require_once __DIR__."/../../config/AuthModel.php";
require_once __DIR__."/../../config/BankModel.php";
require_once __DIR__."/../../config/DepositorModel.php";
require_once __DIR__."/../../config/AdminModel.php";
require_once __DIR__."/../../depositor/functions.php";
require_once __DIR__."/../../config/RequestsModel.php";

if ( isset($_REQUEST['action']) ){
    if (AdminModel::isLoggedIn()) {
        if (isset($_REQUEST['id'])) {
            $id = Core::urlValueDecrypt($_REQUEST['id']);
            $user_data = AuthModel::getUserDataByID($id);
            $logged_in_admin_user_data= AdminModel::getUserdata();
            $sql = "UPDATE `users` SET ";
            $timeNow = gmdate('Y-m-d h:i:s');
            switch ($_GET['action']) {
                case 'approve_non_invited_fi':
                        if ( $user_data['password_changed'] == 0 ){
                            break;
                        }
                        Core::activityLog("admin approved invitation request");
                        $subject = "Your invitation request to join Yield Exchange has been approved";
                        $toEmail = $user_data["email"];

                        $message = "Your invitation request to join Yield Exchange has been approved";
                        $message .= "Please contact us via info@yieldexchange.ca";

                        Core::sendMail($subject, $toEmail, $message, 'fi', false, false);

                        $sql .= " account_status='ACTIVE', is_non_partnered_fi=0";
                        AuthModel::resetUserLoginAttempts($id);
                        RequestsModel::archiveTable($id,"users","Admin Approved Non Partnered FI");
                    break;
                case 'disapprove_non_invited_fi':
                    Core::activityLog("admin rejected invitation request");
                    $subject="Your invitation request to join Yield Exchange has been rejected";
                    $toEmail=$user_data["email"];

                    $message = "Your invitation request to join Yield Exchange has been rejected";
                    $message .= "Please contact us via info@yieldexchange.ca";

                    Core::sendMail($subject,$toEmail,$message,'inv',false,false);
                    $sql .= " account_status='REJECTED'";
                    AuthModel::resetUserLoginAttempts($id);
                    RequestsModel::archiveTable($id,"users","Admin DisApproved Non Partnered FI");
                    break;
                case "reject":
                    Core::activityLog("admin suspended account");
                    $subject="Your account has been suspended!";
                    $toEmail=$user_data["email"];

                    $message = "Your account approval has been rejected!.";
                    $message .= "Please contact us via info@yieldexchange.ca";

                    Core::sendMail($subject,$toEmail,$message,'inv',false,false);
                    $sql .= " account_status='REJECTED'";
                    db::query("UPDATE users SET account_closure_date='$timeNow' WHERE id='$id'");
                    RequestsModel::archiveTable($id,"users","Admin Control");
                    break;
                case "activate":
                    $action = $user_data['account_status']=="LOCKED" ? " unlocked" : " activated";
                    Core::activityLog("admin ".$action." account");
                    $subject="Congratulations!! Your account has been ".$action."!";
                    $toEmail=$user_data["email"];

                    $message = "Congratulations!!! Your account has been ".$action.".";
                    //$message .= "Your Yield Exchange account has been ".$action;

                    Core::sendMail($subject,$toEmail,$message,'inv',true,false);
                    $sql .= " account_status='ACTIVE'";
                    AuthModel::resetUserLoginAttempts($id);
                    RequestsModel::archiveTable($id,"users","Admin Control");
                    break;
                case "approve":
                    Core::activityLog("admin approved account");
                    $subject="Your account is ready to use";
                    $toEmail=$user_data["email"];
                    $header = "Your Account setup is complete";

                    $message = "<center>Your Yield Exchange account is now ready for use. You can sign in and familiarize yourself with Yield Exchange. If you require a demo on how to use Yield Exchange please <a href='mailto:info@yieldexchange.ca'>email</a> us.</center><p><center>Additional resources can be found in our <a href='https://yieldexchange.tawk.help/'>FAQ</a> or you can use our <a href='".BASE_URL."'>Chat</a>.</center></p>";
                    
                    $env = ($user_data["description"] =="Bank" || $user_data["description"] =="Broker") ? 'fi' : 'inv';
                    $link = BASE_URL.'/login?a='.$env;
                    $link2 ="https://yieldexchange.tawk.help/";

                    Core::sendMail($subject,$toEmail,$message,$env,false,false,[['linkName'=>'Visit FAQ','link'=>$link2],['linkName'=>'Sign in','link'=>$link]],true,$logo_position="top",$header);
                    $sql .= " account_status='ACTIVE'";
                    AuthModel::resetUserLoginAttempts($id);
                    RequestsModel::archiveTable($id,"users","Admin Control");
                    break;
                case "suspend":
                    if ($user_data['description'] == "Admin"){
                        $user_id = $user_data['id'];
                        $user_count = db::getRecords("SELECT u.* FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND rt.description='Admin' ");
                        if (empty($user_count) || count($user_count) <= 1){ // make sure we have at-least one admin active
                            ob_clean();
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                            die();
                        }
                    }
                    Core::activityLog("admin suspend account");
                    $subject="Your Yield Exchange account has been suspended";
                    $toEmail=$user_data["email"];

                    $message = "Your Yield Exchange account has been suspended.";
                    $message .= "Kindly contact admin.";

                    Core::sendMail($subject,$toEmail,$message,'inv',false,false);
                    $sql .= " account_status='SUSPENDED'";
                    RequestsModel::archiveTable($id,"users","Admin Control");
                    break;
                case "close":
                    $reason = $_REQUEST['reason'];
                    if ( $user_data['description'] != "Admin"  || $user_data['description'] == "Admin" && !in_array($user_data['email'],['sampath@yieldexchange.ca','ravi@yieldexchange.ca']) ) {
                        db::query("UPDATE users SET account_closure_date='$timeNow', account_closure_reason='$reason', account_status ='CLOSED' WHERE id='$id'");
                        RequestsModel::archiveTable($id,"users","Admin Control");
                        Core::activityLog("admin closed account");
                        if ($user_data['description'] == "Admin"){
                            $toEmail = $user_data['email'];
                            $subject = "Admin Account Closure";

                            $message = "This is to notify you that you are no longer an admin on Yield Exchange Site, terminated by ".$logged_in_admin_user_data['name'];
                            $message .= "<br>";
                            $message .= "Thank you";

                            Core::sendMail($subject, $toEmail, $message,'admin',false,false);
                        }
                    }
                    break;
            }
            $sql .= " WHERE id='$id'";
            db::query($sql);
        }
    }

    ob_clean();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

//login
if(isset($_POST['login'])){
    AdminModel::login();
}

//pin
if(isset($_POST['pin'])){
    AdminModel::verifyPin();
}

if(isset($_GET['email']) && isset($_GET['resend_pwd'])){
   AdminModel::resendRecoverPasswordLink(); 
}

if(isset($_POST['sub_admin'])){
    AdminModel::authCsrfToken();

    if (AdminModel::isLoggedIn()) {
        $user_id = $id = Core::urlValueDecrypt($_POST["id"]);
        $name = $_POST["name"];

        Core::activityLog("admin updated admin account");

        db::preparedQuery("UPDATE users SET name=? WHERE id=?",array("si",$name,$user_id));

        if (isset($_POST["pass"]) && !empty($_POST["pass"])) {
            $new_password = $_POST["pass"];
            $password = db::getRecord("SELECT * from passwords WHERE user_id='$user_id' ORDER BY id DESC");
            $dateTime = gmdate('Y-m-d h:i:s');

            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $sql_password = "INSERT INTO `passwords`(`hash`,`user_id`, `created_at`, `updated_at`)
                VALUES ('$hashed_password','$user_id','$dateTime','$dateTime')";
            db::insertRecord($sql_password);
        }
    }
    echo "<script>location='manage'</script>";
}

if(isset($_POST['add_admin'])){
    AdminModel::authCsrfToken();

    if (AdminModel::isLoggedIn()) {
        $email = $_POST["email"];
        $name = $_POST["name"];
        $pass = $_POST["pass"];

        Core::activityLog("admin created admin account");

        $sql = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = '$email' ";
        $sql.=" AND rt.description='Admin'";
        $user_exists = db::exists($sql);
        if ($user_exists) {
            echo "<script>location='".BASE_URL."/add_ad?case=error'</script>"; die();
        }

        $dateTime = gmdate('Y-m-d h:i:s');

        $sql_user = "INSERT INTO `users`(`name`, `email`, `account_opening_date`, `account_status`, `failed_login_attempts`) VALUES (?,?,?,?,?)";
        $user_id = db::preparedQuery($sql_user,array("ssssi",$name,$email,$dateTime,'ACTIVE',0));

        $sql_role = "INSERT INTO `user_role_types`(`user_id`, `role_type_id`) VALUES ('$user_id','1')";
        db::insertRecord($sql_role);

        $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

        $sql_password = "INSERT INTO `passwords`(`hash`,`user_id`, `created_at`, `updated_at`) VALUES ('$hashed_password','$user_id','$dateTime','$dateTime')";
        db::insertRecord($sql_password);

        $toEmail = $email;
        $subject = "Welcome to Yield Exchange";
        $message = "We warmly you <b>".$name."</b> to Yield Exchange. You can login to the admin dashboard using the link below.";

        Core::sendMail($subject, $toEmail, $message,'admin',false,false,[['linkName'=>'Admin Login','link'=>BASE_URL.'/YEI/admin/authenticate']]);
    }
    echo "<script>location='manage'</script>";
}

if(isset($_GET['logout'])) {
    Core::activityLog("Admin logged out");
    AdminModel::logout();
}

if ( isset($_GET['app']) ) {
    if (AdminModel::isLoggedIn()) {
        $id = $_GET['app'];
        RequestsModel::completeContract($id);
        echo "<script>location='p_cont'</script>";
    }
}

if (isset($_GET['abandon_contract'])){
    if (AdminModel::isLoggedIn()) {
        //abandonRequestContract();
        echo "<script>location='p_cont'</script>";
    }
}

if ( isset($_GET['reject']) || isset($_GET["cancel"]) ) {
    if (AdminModel::isLoggedIn()) {
        Core::activityLog("admin rejected Contract");
        $id = isset($_GET['reject']) ? $_GET['reject'] : $_GET["cancel"];
        RequestsModel::rejectContract($id);
        echo "<script>location='p_cont'</script>";
    }
}

if (isset($_POST['update_profile'])) {
    AdminModel::authCsrfToken();

    if (AdminModel::isLoggedIn()) {
        $user_id = Core::urlValueDecrypt($_POST['id']);
        $user_data = AuthModel::getUserDataByID($user_id);

        if ($user_data['description'] == "Depositor") {
            $target_dir = "../../depositor/image/"; // change do your upload dir
        } else {
            $target_dir = "../../bank/image/"; // change do your upload dir
        }

        $data = $_POST["image"];
        $image_array_1 = explode(";", $data);

        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);

        $imageName = $user_data['name'] . '-' . time() . '.png';
        $target_file = $target_dir . $imageName;

        $success = false;
        if (is_dir($target_dir) && is_writable($target_dir)) {
            if (file_put_contents($target_file, $data)) {
//                RequestsModel::archiveTable($user_id, "users", "Admin Control");
//                db::query("UPDATE `users` SET profile_pic='$imageName' WHERE id='$user_id' ");
                $success = true;
            }
        }
        Core::activityLog("admin updated profile image");

        if ($success) {
            $response = array(
                'image' => $imageName,
                'success' => true
            );
        } else {
            $response = array(
                'image' => '',
                'success' => false
            );
        }

        ob_clean();
        echo json_encode($response);
    }
}

if (isset($_POST['profile_data'])) {
    AdminModel::authCsrfToken();

    if (AdminModel::isLoggedIn()) {
        Core::activityLog("admin updated profile data of user");
        $user_id = Core::urlValueDecrypt($_POST['id']);
        $address1 = $_POST['line1'];
        $address2 = $_POST['line2'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $timezone = $_POST['timezone'];
        $tel = $_POST['tel'];
        $dateTime = gmdate('Y-m-d h:i:s');
        $name = $_POST['institution'];
        $postal = $_POST['postal'];
        $uploaded_profile_pic= $_POST['uploaded_profile_pic'];

        RequestsModel::archiveTable($user_id,"users","Admin Control");
        db::query("UPDATE users SET name='$name' WHERE id='$user_id'");
        db::query("UPDATE `demographic_data` SET `address1`='$address1',`address2`='$address2',`city`='$city',
                             `province`='$province',`timezone`='$timezone',`telephone`='$tel',`postal_code`='$postal',`created_at`='$dateTime',`updated_at`='$dateTime' WHERE user_id='$user_id'");

        $account_document = $_POST['account_document'];
        if (!empty($account_document)) {
            $depositors = db::getRecord("SELECT * FROM `depositors` WHERE user_id='$user_id'");
            if (empty($depositors)) {
                $query = "INSERT INTO `depositors`(`user_id`, `account_doc`, `created_at`, `updated_at`) VALUES ('$user_id','$account_document','$dateTime','$dateTime')";
            } else {
                $query = "UPDATE `depositors` SET `account_doc`='$account_document',`created_at`='$dateTime',`updated_at`='$dateTime' WHERE `user_id`='$user_id'";
            }
            db::query($query);
        }

        RequestsModel::archiveTable($user_id, "users");
        db::preparedQuery("UPDATE `users` SET `profile_pic`=? WHERE `id`=?", array("si", (empty($uploaded_profile_pic) ? NULL : $uploaded_profile_pic), $user_id));

        if ( !empty($_POST['short']) || !empty($_POST['long']) ) {
            $short1 = $_POST['short'];
            $long1 = $_POST['long'];
//            $user_id = Core::urlValueDecrypt($_POST['id']);

            $credit_rating = db::getRecord("SELECT * FROM credit_rating WHERE user_id='$user_id'");
            if (empty($credit_rating)) {
                db::query("INSERT INTO `credit_rating`(`user_id`, `credit_rating_type_id`, `deposit_insurance_id`) VALUES ('$user_id','$short1','$long1')");
            } else {
                db::query("UPDATE `credit_rating` SET `credit_rating_type_id`='$short1',`deposit_insurance_id`='$long1' WHERE user_id='$user_id'");
            }
        }
    }
    ob_clean();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

if (isset($_POST['update_password'])) {
    AdminModel::authCsrfToken();
        
    if (AdminModel::isLoggedIn()) {

        $user_id = Core::urlValueDecrypt($_POST['id']);
        Core::activityLog("admin updated password");

        $password = db::getRecord("SELECT * from passwords WHERE user_id='$user_id' ORDER BY id DESC");
        $new_password = $_POST['new_password'];
        $dateTime = gmdate('Y-m-d h:i:s');

        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $pass_counts = db::getRecords("SELECT * FROM `passwords` WHERE user_id='$user_id'");
        if ($pass_counts == 10) {
            $first_password_id = db::getCell("SELECT id FROM `passwords` WHERE user_id='$user_id' ORDER BY id ASC LIMIT 1");
            db::query("DELETE FROM `passwords` WHERE id='$first_password_id'");
        }
        db::insertRecord("INSERT INTO `passwords`(`hash`,`user_id`, `created_at`, `updated_at`) VALUES ('$hashed_password','$user_id','$dateTime','$dateTime')");
        db::query("DELETE FROM password_resets WHERE user_id='$user_id'");

    }
    ob_clean();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

//if (isset($_POST['saverating'])) {
//    AdminModel::authCsrfToken();
//
//    if (AdminModel::isLoggedIn()) {
//
//        Core::activityLog("admin updated profile data of user Credit Rating And Deposit Insurance");
//
//        $short1 = $_POST['short'];
//        $long1 = $_POST['long'];
//        $user_id = Core::urlValueDecrypt($_POST['id']);
//
//        $credit_rating = db::getRecord("SELECT * FROM credit_rating WHERE user_id='$user_id'");
//        if (empty($credit_rating)) {
//            db::query("INSERT INTO `credit_rating`(`user_id`, `credit_rating_type_id`, `deposit_insurance_id`) VALUES ('$user_id','$short1','$long1')");
//        } else {
//            db::query("UPDATE `credit_rating` SET `credit_rating_type_id`='$short1',`deposit_insurance_id`='$long1' WHERE user_id='$user_id'");
//        }
//    }
//    ob_clean();
//    header('Location: ' . $_SERVER['HTTP_REFERER']);
//}

//if (isset($_GET['remove_profile'])){
//    if (AdminModel::isLoggedIn()) {
//        Core::activityLog("Admin Removing User Profile Picture");
//        $user_id = Core::urlValueDecrypt($_GET['remove_profile']);
//        RequestsModel::archiveTable($user_id,"users","Admin Control");
//        db::query("UPDATE users SET profile_pic = NULL WHERE id='$user_id'");
//        ob_clean();
//        header('Location: ' . $_SERVER['HTTP_REFERER']);
//    }
//}
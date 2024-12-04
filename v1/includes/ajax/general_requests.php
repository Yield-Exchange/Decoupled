<?php
session_start();
include "../../config/db.php";
include "../../config/Model.php";
require_once BASE_DIR."/config/AuthModel.php";
require_once BASE_DIR."/config/RequestsModel.php";

if (AuthModel::isLoggedIn()) {
//    if ( !AuthModel::authAjaxCsrfToken()){
//        echo json_encode(['data'=>[],'message'=>'Invalid token, please reload the page','success'=>false,'reload'=>true]); exit();
//    }
    $non_fi_ids =array();

    $action = $_POST['action'];
    switch ($action){
        case 'CLEAR_CACHED_INVITES':
            if ($_POST['CACHE_INVITES']=='all') {
                unset($_SESSION['CACHE_INVITES']);
            }else{
                if( $_SESSION['CACHE_INVITES'] == 'all') {
                    $banks = BankModel::getBanksANDBrokersWithCreditDepositInsuranceFilters($_SESSION["val18"], $_SESSION["val12"], $user_data, true);
                    $ids = [];
                    if (!empty($banks)) {
                        foreach ($banks as $bank) {
                            array_push($ids, $bank['id']);
                        }
                        $_SESSION['CACHE_INVITES'] = $ids;
                    }
                }
                $_SESSION['CACHE_INVITES'] = array_diff($_SESSION['CACHE_INVITES'], array($_POST['CACHE_INVITES']));
            }
            echo json_encode(['data'=>[],'message'=>"Cleared successfully",'success'=>true]); exit();
        case 'CACHE_INVITES':
            if (empty($_SESSION['CACHE_INVITES'])) {
                $_SESSION['CACHE_INVITES'] = [];
            }
            if ($_POST['CACHE_INVITES']=='all') {
                $_SESSION['CACHE_INVITES'] = $_POST['CACHE_INVITES'];
            }else{
                array_push($_SESSION['CACHE_INVITES'],$_POST['CACHE_INVITES']);
            }
            echo json_encode(['data'=>[],'message'=>"Cached successfully",'success'=>true]); exit();
        case 'DECLINE_TERMS_AND_CONDITIONS':
            Core::activityLog("non partnered FI declined invitation by clicking NO to terms and conditions");
            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
            db::query("UPDATE users SET account_status='DECLINED_TERMS_AND_CONDITIONS' WHERE id='$user_id'");
            db::query("UPDATE invited SET invitation_status='DID_NOT_PARTICIPATE' WHERE invited_user_id='$user_id'");
            AuthModel::silentLogout();
            echo json_encode(['data'=>[],'message'=>"Decline T&C successfully",'success'=>false]); exit();
        case 'ACCEPT_TERMS_AND_CONDITIONS':
            Core::activityLog("non partnered FI Accepted invitation by clicking YES to terms and conditions");
            $user_data = AuthModel::getUserdata();
            $user_id = $user_data['id'];
            db::query("UPDATE users SET account_status='ACTIVE' WHERE id='$user_id'");
            $pass=rand(11024, 103540);
            $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

            $dateTime = Model::utcDateTime();
            $sql_password = "INSERT INTO `passwords`(`hash`,`user_id`, `created_at`, `updated_at`) VALUES ('$hashed_password','$user_id','$dateTime','$dateTime')";
            db::insertRecord($sql_password);
            $subject="Welcome to Yield Exchange";
            $message = "Hi ".$user_data['name'].",<br/>
            <p>Welcome to Yield Exchange, your temporary password to login to Yield Exchange is 
                <br/> <div style='color:blue;width: 100%;text-align: center;padding-top:0;padding-bottom: 0;margin: 0'>".$pass."</div><br/></p>

            <p>Please use this password to login and finish setting up your account. You will be requested to change the password when you login the first time.</p>

            <p>Thanks,</p>";
            Core::sendMail($subject, $user_data['email'], $message,'fi',false,false,[['linkName'=>'Login','link'=>BASE_URL.'/login?a=fi&&tmpPass='.Core::urlValueEncrypt(1)]]);
            AuthModel::silentLogout();
            echo json_encode(['data'=>[],'message'=>"You are required to re-login. An email has been sent to you with details for the next step.",'success'=>true,'relogin'=>true]); exit();
        case 'DECLINE_INVITATION_NON_PARTNERED_FI':
            Core::activityLog("non partnered FI Declined invitation by clicking NO to terms and conditions");
            $user_id = AuthModel::getUserdata()['id'];
            db::query("UPDATE users SET account_status='DECLINED_INVITATION' WHERE id='$user_id'");
            db::query("UPDATE invited SET invitation_status='DID_NOT_PARTICIPATE' WHERE invited_user_id='$user_id'");
            AuthModel::silentLogout();
            echo json_encode(['data'=>[],'message'=>"Decline invitation successfully",'success'=>true]); exit();
        case 'CANCEL_NON_PARTNERED_INVITES';
            Core::activityLog("Newly created non partnered FI discarded on navigation to other pages");
            $user_id = AuthModel::getUserdata()['id'];
            db::query("DELETE FROM users WHERE is_temporary=1 AND created_by='$user_id'");
            unset($_SESSION['new_invited_fi']);
            unset($_SESSION['CACHE_INVITES'] );
            echo json_encode(['data'=>[],'message'=>"Invites discarded successfully",'success'=>true]); exit();
        case 'INVITE_NEW_FI':
            Core::activityLog("Newly created a non partnered FI for their offer");
            $error="An error occurred while inviting ";
            $pass=rand(11024, 103540);

            if ( $_POST['email'] != $_POST['re_enter_email'] ) {
                echo json_encode(['data'=>[],'message'=>"The emails do not match.",'success'=>false]); exit();
            }

            $sql = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND email = '".$_POST['email']."' ";
            $role_id = 2;
            $sql .= " AND (rt.description='Bank' || rt.description='Broker') ";
            $sql .= " AND u.account_status IN('PENDING','ACTIVE','LOCKED','SUSPENDED','INVITED','REVIEWING')";

            $user_exists = db::exists($sql);
            if ($user_exists) {
                echo json_encode(['data'=>[],'message'=>"User with that email already exist.",'success'=>false]); exit();
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['data'=>[],'message'=>"Invalid email.",'success'=>false]); exit();
            }
            $logged_in_user_data = AuthModel::getUserdata();
            $name=$_POST['name']; //.' '.$_POST['lastname'];
            if (empty($name)) {
                echo json_encode(['data'=>[],'message'=>"Institution name is required.",'success'=>false]); exit();
            }

            // Check the Institution if has been added within the few seconds/minutes
            $name_exists = db::exists("SELECT * from users WHERE name ='$name' AND account_status IN('PENDING','ACTIVE','LOCKED','SUSPENDED','INVITED','REVIEWING')");
            if ($name_exists){
                echo json_encode(['data'=>[],'message'=>"Failed, the institution is already invited.",'success'=>false]); exit();
            }

            $email=$_POST['email'];
            // Check the Institution if has been added within the few seconds/minutes
            $DECLINED_INVITATION_exists = db::exists("SELECT * from users WHERE email ='$email' AND account_status IN('DECLINED_INVITATION')");
            if ($DECLINED_INVITATION_exists){
                $message = "Please invite a different Account Manager at ".$name.". If you do not know another Account Manager please contact Yield Exchange for more information";
                echo json_encode(['data'=>[],'message'=>$message,'success'=>false]); exit();
            }

            $DECLINED_TERMS_AND_CONDITIONS_exists = db::exists("SELECT * from users WHERE email ='$email' AND account_status IN('DECLINED_TERMS_AND_CONDITIONS')");
            if ($DECLINED_TERMS_AND_CONDITIONS_exists){
                echo json_encode(['data'=>[],'message'=>"Failed, ".$name." has declined to participate at Yield Exchange. Please invite another FI to yield exchange.",'success'=>false]); exit();
            }

            if ( isset($_POST['stage']) && $_POST['stage']=="1" ){
                echo json_encode(['data'=>[],'message'=>"Next Stage",'success'=>true]); exit();
            }

            if (empty($_POST['account_manager_name'])) {
                $_POST['account_manager_name']='';
//                echo json_encode(['data'=>[],'message'=>"Account manager name is required.",'success'=>false]); exit();
            }

            if (empty($_POST['your_name'])) {
//                echo json_encode(['data'=>[],'message'=>"Your name is required.",'success'=>false]); exit();
                $your_name=$logged_in_user_data['name'];
            } else{
                $your_name = trim($_POST['your_name']);
            }

            $province='';//$_POST['province'];

            $account_manager=isset($_POST['account_manager_name']) ? $_POST['account_manager_name'] : '';
            $dateTime = Model::utcDateTime();
            $sql_user = "INSERT INTO `users`(`name`, `email`, `account_opening_date`, `account_status`, `failed_login_attempts`,`is_non_partnered_fi`, `created_by`,`is_temporary`,`account_manager`,`inviter_name`) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $user_id = db::preparedQuery($sql_user, array("ssssiiiiss", $name, $email, $dateTime, 'INVITED', 0,1,$logged_in_user_data['id'],1,$account_manager,$your_name));

            $sql_role = "INSERT INTO `user_role_types`(`user_id`, `role_type_id`) VALUES ('$user_id','$role_id')";
            db::insertRecord($sql_role);

            $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

            $sql_password = "INSERT INTO `passwords`(`hash`,`user_id`, `created_at`, `updated_at`) VALUES ('$hashed_password','$user_id','$dateTime','$dateTime')";
            db::insertRecord($sql_password);

            $sql_demographic_data = "INSERT INTO `demographic_data`(`user_id`, `address1`, `address2`, `city`, `province`, `postal_code`, `timezone`, `created_at`, `updated_at`,`telephone`) VALUES (?,?,?,?,?,?,?,?,?,?)";
            db::preparedQuery($sql_demographic_data, array("isssssssss", $user_id, '', '', '', $province, '', 'Central', $dateTime, $dateTime, ''));

            $preference = db::getCell("SELECT id FROM `preferences` WHERE name='mute_notification'");
            db::query("INSERT INTO `user_prefences`(`user_id`, `preference_id`, `value`) VALUES ('" . $user_id . "','" . $preference . "','1')");

            $gmt_now = gmdate('Y-m-d H:i:s');

            $short_term_credit=db::getCell(" SELECT id FROM credit_rating_type WHERE description='Any/Not Rated' LIMIT 1");
            $deposit_insurance=db::getCell(" SELECT id FROM deposit_insurance WHERE description='Any' LIMIT 1");

            db::query("INSERT INTO `credit_rating`(`user_id`, `credit_rating_type_id`, `deposit_insurance_id`) VALUES ('$user_id','$short_term_credit','$deposit_insurance')");

            Core::activityLog("Non Invited FI created > ".$name);

            //send invitation email
            $toEmail = $email;
            if(!empty($_POST['your_name'])){
            $subject = "Invitation from ".trim($_POST['your_name']).' '.'at '.$logged_in_user_data['name'];
            } else{
                $subject = "Invitation from ".$logged_in_user_data['name'];
            }

            $new_invited_fi = [];
            $new_invited_fi_account_managers = [];
            if ( isset($_SESSION['new_invited_fi']) ) {
                $new_invited_fi = $_SESSION['new_invited_fi'];
                $new_invited_fi_account_managers = $_SESSION['new_invited_fi_account_managers'];
            }

           $new_invited_fi_account_managers[$user_id] = [
               'account_manager'=>$account_manager,//$_POST['account_manager_name'],
               'your_name'=>isset($_POST['your_name']) ? trim($_POST['your_name']) : ''
           ];

            $_SESSION['new_invited_fi_account_managers'] = $new_invited_fi_account_managers;

            array_push($new_invited_fi,$user_id);
            $_SESSION['new_invited_fi'] = $new_invited_fi;

            $error="FI invited successfully.";
            echo json_encode(['data'=>[],'message'=>$error,'success'=>true]); exit();
        default:
            echo json_encode(['data'=>[],'message'=>'Unknown action, please retry','success'=>false]); exit();
    }

}

echo json_encode(['data'=>[],'message'=>'Invalid session, please reload the page','success'=>false,'reload'=>true]); exit();
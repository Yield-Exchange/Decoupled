<?php

if (isset($_POST['update_profile'])) {
    if ( !AuthModel::authAjaxCsrfToken() ){
        $response = array(
            'image' => '',
            'success' => false
        );
        ob_clean();
        echo json_encode($response);
    }


    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];

    $target_dir = "image/"; // change do your upload dir

    $data = $_POST["image"];
    $image_array_1 = explode(";", $data);

    $image_array_2 = explode(",", $image_array_1[1]);
    $data = base64_decode($image_array_2[1]);

    $imageName = $user_data['name'].'-'.time() . '.png';
    $target_file = $target_dir . $imageName;

    $success=false;
    if ( is_dir($target_dir) && is_writable($target_dir) ) {
        if( file_put_contents($target_file, $data) ){
//            RequestsModel::archiveTable($user_id,"users");
//            db::query("UPDATE `users` SET profile_pic='$imageName' WHERE id='$user_id' ");
            $success=true;
        }
    }
    Core::activityLog("Updating Account Profile Picture");

    if ($success) {
        $response = array(
            'image' => $imageName,
            'success' => true,
//            'url'=>$user_data['is_non_partnered_fi'] ==1 && $user_data['password_changed'] ==0 ? BASE_URL.'/bank/non_fi_details' : BASE_URL.'/bank/account_settings'
        );
    }else{
        $response = array(
            'image' => '',
            'success' => false
        );
    }

    ob_clean();
    echo json_encode($response);
}

if (isset($_POST['profile_data'])) {
    AuthModel::authCsrfToken();

    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];
    $address1 = $_POST['line1'];
    $address2 = $_POST['line2'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postal = $_POST['postal'];
    $timezone = $_POST['timezone'];

    $tel = $_POST['tel'];
    $dateTime = Model::utcDateTime();
    $name = $_POST['institution'];
    $uploaded_profile_pic= $_POST['uploaded_profile_pic'];

//    RequestsModel::archiveTable($user_id,"users");
//    db::query("UPDATE users SET name='$name' WHERE id='$user_id'");
//    db::preparedQuery("UPDATE users SET name=? WHERE id=?", array("si", $name, $user_id));
    db::preparedQuery("UPDATE `demographic_data` SET `address1`=?,`address2`=?,`city`=?,
                             `province`=?,`timezone`=?,`telephone`=?,`postal_code`=?,`created_at`=?,`updated_at`=? WHERE user_id=?", array("sssssssssi", $address1, $address2, $city, $province,$timezone,$tel,$postal,$dateTime,$dateTime,$user_id));

    $account_document = @$_POST['account_document'];
    if (!empty($account_document)) {
        Core::activityLog("Updating Account Data Success");
        $depositors = db::getRecord("SELECT * FROM `depositors` WHERE user_id='$user_id'");
        if (empty($depositors)) {
            db::preparedQuery("INSERT INTO `depositors`(`user_id`, `account_doc`, `created_at`, `updated_at`) VALUES (?,?,?,?)", array("isss", $user_id, $account_document,$dateTime,$dateTime));
        } else {
            db::preparedQuery("UPDATE `depositors` SET `account_doc`=?,`created_at`=?,`updated_at`=? WHERE `user_id`=?", array("sssi", $account_document, $dateTime,$dateTime,$user_id));
        }
    }

    if ( !empty($_POST['short']) || !empty($_POST['long']) ) {
        $short1 = $_POST['short'];
        $long1 = $_POST['long'];

        $user_data = AuthModel::getUserdata();
        $user_id = $user_data['id'];

        $credit_rating = db::getRecord("SELECT * FROM credit_rating WHERE user_id='$user_id'");
        if (empty($credit_rating)) {
            db::query("INSERT INTO `credit_rating`(`user_id`, `credit_rating_type_id`, `deposit_insurance_id`) VALUES ('$user_id','$short1','$long1')");
        } else {
            db::query("UPDATE `credit_rating` SET `credit_rating_type_id`='$short1',`deposit_insurance_id`='$long1' WHERE user_id='$user_id'");
        }
    }

    RequestsModel::archiveTable($user_id,"users");
    db::preparedQuery("UPDATE `users` SET `profile_pic`=? WHERE `id`=?", array("si", (empty($uploaded_profile_pic) ? NULL : $uploaded_profile_pic), $user_id));

    Core::alert("Account update","Updating account data was successful","success","account_settings");
}

if (isset($_POST['timezone_update'])) {
    AuthModel::authCustomCsrfToken("_token_top_nav_timezone_switch_form");

    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];

    $timezone = $_POST['timezone'];
    $dateTime = Model::utcDateTime();

    RequestsModel::archiveTable($user_id,"users");
    db::preparedQuery("UPDATE `demographic_data` SET `timezone`=?,`updated_at`=? WHERE user_id=?", array("ssi", $timezone,$dateTime,$user_id));

    Core::alert("Timezone updated","Updating account timezone was successful","success",$_SERVER['HTTP_REFERER']);
}

if(isset($_GET['resend_pwd2'])){

    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];

    $user_email = db::getCell("SELECT email FROM `users` WHERE id='$user_id'");
    $toEmail = $user_email;

    $code = password_hash(time() . '_' . $user_email, PASSWORD_BCRYPT);
    $expiration_date = date('Y-m-d H:i:s', gmmktime() + (2 * 60 * 60)); // 2 hours UTC time

    db::query("DELETE FROM password_resets WHERE user_id='$user_id'");
    $sql = "INSERT INTO `password_resets`(`user_id`, `expiration_date`, `token`, `created_at`) VALUES ('$user_id','$expiration_date','$code','" . Model::utcDateTime() . "')";
    db::query($sql);

     $link = BASE_URL . '/reset_password_final?code=' . $code;
    if( $user_data['description'] ='Bank' || $user_data['description'] = 'Broker' ){
        $link2 = BASE_URL . '/bank/logic?env=fi &resend_pwd2=1';
    } else if($user_data['description'] == 'Depositor'){
        $link2 = BASE_URL . '/depositor/logic?env=inv &resend_pwd2=1';
    }

    $other_buttons =[['linkName'=>'Resend Password Link','link'=>$link2],['linkName'=>'Click Here To Reset Password','link'=>$link]];
    $env='inv';
    Emails::passwordReset($toEmail,$env,$other_buttons);

    Core::activityLog("Updating Password Success");
//    $subject="Password Reset Confirmation";
//    $message="The password on your account has been recently initiated. If this reset was initiated by you, this email is for your information only. If you did not initiate the password reset, please contact info@yieldexchange.ca immediately.";
//    Core::sendMail($subject, $user_data['email'], $message, 'inv');

    Core::alert("Reset password","Reset password link was sent to your email","success","account_settings");

}

if ( isset($_POST['update_password']) || isset($_GET['update_password']) ) {
    AuthModel::authCsrfToken(true,false);

    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];

    $user_email = db::getCell("SELECT email FROM `users` WHERE id='$user_id'");
    $toEmail = $user_email;

    $code = password_hash(time() . '_' . $user_email, PASSWORD_BCRYPT);
    $expiration_date = date('Y-m-d H:i:s', gmmktime() + (2 * 60 * 60)); // 2 hours UTC time

    db::query("DELETE FROM password_resets WHERE user_id='$user_id'");
    $sql = "INSERT INTO `password_resets`(`user_id`, `expiration_date`, `token`, `created_at`) VALUES ('$user_id','$expiration_date','$code','" . Model::utcDateTime() . "')";
    db::query($sql);

    $link = BASE_URL . '/reset_password_final?code=' . $code;
    if( $user_data['description'] ='Bank' || $user_data['description'] = 'Broker' ){
        $link2 = BASE_URL . '/bank/logic?env=fi &resend_pwd2=1';
    } else if($user_data['description'] == 'Depositor'){
        $link2 = BASE_URL . '/depositor/logic?env=inv &resend_pwd2=1';
    }

    $other_buttons =[['linkName'=>'Resend Password Link','link'=>$link2],['linkName'=>'Click Here To Reset Password','link'=>$link]];
    $env='inv';
    Emails::passwordReset($toEmail,$env,$other_buttons);

    Core::activityLog("Updating Password Success");
//    $subject="Password Reset Confirmation";
//    $message="The password on your account has been recently initiated. If this reset was initiated by you, this email is for your information only. If you did not initiate the password reset, please contact info@yieldexchange.ca immediately.";
//    Core::sendMail($subject, $user_data['email'], $message, 'inv');

    Core::alert("Reset password","Reset password link was sent to your email","success","account_settings");
}

if (isset($_GET['del_notification_id'])) {
    Core::activityLog("Delete Notifications");
    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];
    $id = Core::urlValueDecrypt($_GET['del_notification_id']);
    $query = "DELETE from notifications where id='$id' AND user_id='$user_id'";
    $run = db::query($query);
    Core::alert("Delete Notification","Deleting notification was successful","success","notification");
}

//if (isset($_GET['remove_profile'])){
//    Core::activityLog("Removing Profile Picture");
//    $user_data = AuthModel::getUserdata();
//    $user_id = $user_data['id'];
//    RequestsModel::archiveTable($user_id,"users","Removing profile");
//    db::query("UPDATE `users` SET profile_pic = NULL WHERE id='$user_id'");
//    Core::alert("Remove profile photo","Removing profile photo was successful","success", $user_data['is_non_partnered_fi'] == 1 && $user_data['password_changed'] ==0  ? BASE_URL.'/bank/non_fi_details' : BASE_URL.'/bank/account_settings');
//}
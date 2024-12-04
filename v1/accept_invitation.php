<?php
require_once __DIR__."/config/db.php";
require_once __DIR__."/config/Model.php";
require_once __DIR__."/config/AuthModel.php";
require_once __DIR__."/config/RequestsModel.php";
if ( empty($_REQUEST['token']) ){
    $message = "There seems to be an issue with the invitation, reach out to the support team on info@yieldexchange.ca";
    $hide_login=true;
    include "includes/pages/auth_status.php"; exit();
}
$id = Core::urlValueDecrypt($_REQUEST['token']);
$user = AuthModel::getUserDataByID($id);
if ( empty($user) ){
    $message = "There seems to be an issue with the invitation, reach out to the support team on info@yieldexchange.ca";
    $hide_login=true;
    include "includes/pages/auth_status.php"; exit();
}

if ( ($user['is_non_partnered_fi'] ==1 && !in_array($user['account_status'],['REVIEWING','INVITED']) ) || $user['is_non_partnered_fi']==0 ){
    Core::loginActivity("Tried to access the accept invite page but the status for the user ".$user['name']." is ".$user['account_status'],$user['id']);
    ob_clean();
    AuthModel::silentLogout();
    header("Location: ".BASE_URL."/login?a=fi"); exit();
}

$_SESSION["USERID"] = $user['id'];
$date_time_now = new DateTime("now", new DateTimeZone("UTC"));
db::query("UPDATE users SET account_status='REVIEWING', last_login='".$date_time_now->format('Y-m-d H:i')."' where id='" . $user['id'] . "'");

$_SESSION['LAST_LOGIN_ACTIVITY'] = gmmktime();
Core::loginActivity("NON PARTNERED FI LOGIN VIA INVITATION LINK", $user['id']);

echo "<script>location='" . BASE_URL . "/bank/requests'</script>"; exit();
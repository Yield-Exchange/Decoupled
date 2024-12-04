<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";
require_once "../config/AuthModel.php";

$user_data = AuthModel::getUserdata();
$preference = db::getCell("SELECT id FROM `preferences` WHERE name='mute_notification'");

if (!empty($user_data) && isset($_GET['action']) && $_GET['action'] == "unmute") {
    Core::activityLog("Depositor UnMute Notification");
    $user_preferences = db::exists("SELECT * FROM `user_prefences` WHERE `user_id`='".$user_data['id']."' AND `preference_id`='$preference'");
    if($user_preferences) {
        $query = "UPDATE `user_prefences` SET `value`='1' WHERE `user_id`='" . $user_data['id'] . "' AND `preference_id`='$preference'";
    }else{
        $query = "INSERT INTO `user_prefences`(`user_id`, `preference_id`, `value`) VALUES ('".$user_data['id']."','".$preference."','1')";
    }
    db::query($query);
} else if (!empty($user_data) && isset($_GET['action']) && $_GET['action'] == "mute") {
    Core::activityLog("Depositor Mute Notification");
    $user_preferences = db::exists("SELECT * FROM `user_prefences` WHERE `user_id`='".$user_data['id']."' AND `preference_id`='$preference'");
    if($user_preferences) {
        $query = "UPDATE `user_prefences` SET `value`='0' WHERE `user_id`='" . $user_data['id'] . "' AND `preference_id`='$preference'";
    }else{
        $query = "INSERT INTO `user_prefences`(`user_id`, `preference_id`, `value`) VALUES ('".$user_data['id']."','".$preference."','0')";
    }
    db::query($query);
}

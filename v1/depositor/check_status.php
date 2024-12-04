<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";
require_once "../config/AuthModel.php";

$user_data = AuthModel::getUserdata();
$user_id = $user_data['id'];

echo db::getCell("SELECT count('ch.*') from chat ch,deposits co where ch.sent_to='$user_id' AND ch.status='NEW' AND co.id=ch.deposit_id AND co.status='PENDING_DEPOSIT'");
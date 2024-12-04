<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";
require_once "../config/AuthModel.php";

$user_data = AuthModel::getUserdata();
$user_id = $user_data['id'];

$query1="SELECT count(*) from notifications where user_id='$user_id' AND status='ACTIVE'";
$val=db::getRecord($query1);
echo $val['count(*)'];

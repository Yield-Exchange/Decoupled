<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";
require_once "../config/AuthModel.php";

if (AuthModel::isLoggedIn()) {
//    if( !AuthModel::authAjaxCsrfToken() ){
//        return;
//    }

    $user_data = AuthModel::getUserdata();
    $user_id = $user_data['id'];
    $dep_id = $_POST['id'];
    $c_id = $_POST['c_id'];
    $msg = $_POST['msg'];
    if ( empty($msg) ){ return; }

    $contract_data = db::getrecord("SELECT c.* FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id'AND c.id='$c_id' AND c.status IN('PENDING_DEPOSIT')");
    if ( empty($contract_data) ){
        return;
    }

    $status = "NEW";
    $time_now = Model::utcDateTime();
    $query = "INSERT into chat (`sent_by`, `sent_to`, `message`, `deposit_id`, `status`, `created_at`) VALUES (?,?,?,?,?,?)";
    db::preparedQuery($query,array('iisiss',$user_id,$dep_id,$msg,$c_id,$status,$time_now));
}

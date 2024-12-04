<?php

include __DIR__ . "/../config/db.php";
include __DIR__ . "/../config/Model.php";
require_once __DIR__ . "/../config/AuthModel.php";
require_once __DIR__ . "/../config/RequestsModel.php";

// send emails
$emails = db::getRecords("SELECT * FROM `queued_emails` WHERE status IN('PENDING') LIMIT 5");
if ( !empty($emails) ){
    foreach ($emails as $email) {
        $email = (object) $email;
        $date_time=gmdate('Y-m-d H:i:s');
        db::query("UPDATE `queued_emails` SET status='SENDING', updated_at='$date_time' WHERE id='$email->id'");
        if ( Core::sendMailSMTP($email->to,base64_decode($email->message),$email->subject, true) ){
            db::query("UPDATE `queued_emails` SET status='SENT', updated_at='$date_time' WHERE id='$email->id'");
        }else{
            db::query("UPDATE `queued_emails` SET status='FAILED', updated_at='$date_time' WHERE id='$email->id'");
        }
    }
}
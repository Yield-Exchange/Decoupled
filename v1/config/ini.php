<?php
require_once __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

//define("BASE_URL", $_ENV['BASE_URL']);
//define("AUTH_BASE_URL", $_ENV['AUTH_BASE_URL']);
//define("ENVIRONMENT",  $_ENV['ENVIRONMENT']);
//define("RECAPTCHA_KEY", $_ENV['RECAPTCHA_KEY']);
//define("SECRET_KEY", $_ENV['SECRET_KEY']);
//
//define("SIMULATE_IDP", $_ENV['SIMULATE_IDP']);
//
//define("SAML_URL", $_ENV['SAML_URL']);
//define("IDP_URL", AUTH_BASE_URL."/samlsso");
//define("IDP_ADMIN_USERNAME", $_ENV['IDP_ADMIN_USERNAME']);
//define("IDP_ADMIN_PASSWORD", $_ENV['IDP_ADMIN_PASSWORD']);
//define("SingleSignOnService", AUTH_BASE_URL."/samlsso");
//define("SingleLogoutService", AUTH_BASE_URL."/samlsso");
//define("certFingerprint", $_ENV['certFingerprint']);

$DB_NAME=$_ENV['DB_DATABASE'];
$DB_USER=$_ENV['DB_USERNAME'];
$DB_PASS=$_ENV['DB_PASSWORD'];
$DB_SERVER=$_ENV['DB_HOST'];

// For global access -> added because of phinx migration access
$GLOBALS['DB_NAME'] = $DB_NAME;
$GLOBALS['DB_USER'] = $DB_USER;
$GLOBALS['DB_PASS'] = $DB_PASS;
$GLOBALS['DB_SERVER'] = $DB_SERVER;

//if ( $_ENV['ERRORS'] != "OFF" ) {
//    error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//}else{
//    error_reporting(0);
//}
//
//$notification_email=$_ENV['NOTIFICATION_EMAIL'];
//$contact_us_notification_email=$_ENV['CONTACT_US_NOTIFICATION_EMAIL'];
//
//$SMTP_HOST = $_ENV['MAIL_HOST'];
//$SMTP_PORT = $_ENV['MAIL_PORT'];
//$SMTP_USERNAME = $_ENV['MAIL_USERNAME'];
//$SMTP_PASSWORD = $_ENV['MAIL_PASSWORD'];
//$SMTP_ENCRYPTION = $_ENV['MAIL_ENCRYPTION'];
//$SMTP_FROM = $_ENV['MAIL_FROM_ADDRESS'];
//
//$DOCUMENT_ROOT = $_ENV['CUSTOM_DOCUMENT_ROOT'];
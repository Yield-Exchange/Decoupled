<?php
namespace App;

class Constants{
    const EMAIL_DATE_FORMAT = "M d Y";
    const DATE_FORMAT = "Y-m-d";
    const DATE_TIME_FORMAT = "Y-m-d H:i:s";
    const DATE_TIME_FORMAT_NO_SECONDS = "Y-m-d H:i";
    const DATE_TIME_12_HRS_FORMAT = "Y-m-d h:i a";
    const DATE_TIME_NEW_12_24_HRS_FORMAT = "Y-m-d H:i A";
    const DATE_TIME_FORMAT_FOR_URL_NAMES = "Y_m_d_H_i_s";

    const RESPONSE_MESSAGE_CONTACT_US = "Please contact info@yieldexchange.ca";
    const FAILED_LOGIN_ATTEMPT_LIMIT = 5;
    const COOKIE_FAILED_LOGIN_ATTEMPTS = 'failed_login_attempts';
}
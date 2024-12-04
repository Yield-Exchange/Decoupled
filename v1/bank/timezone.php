<?php 
    if(AuthModel::isLoggedIn()){
        BankModel::setBankTimeZoneSystem(AuthModel::getUserdata());
    }else{
        date_default_timezone_set("Canada/Central");
    }

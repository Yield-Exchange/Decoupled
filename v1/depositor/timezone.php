<?php
if ( AuthModel::isLoggedIn() ) {
    DepositorModel::setDepositorTimeZoneSystem(AuthModel::getUserdata());
}else{
    date_default_timezone_set("Canada/Central");
}


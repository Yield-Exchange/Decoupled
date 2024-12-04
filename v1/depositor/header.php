<?php

require_once "../config/db.php";
require_once "../config/Model.php";
require_once "../config/AuthModel.php";
require_once "timezone.php";
if ( AuthModel::isLoggedIn() ) {
    $user_data = AuthModel::getUserdata();

    if (!Core::isUserDepositor()) {
        if (Core::isUserBank()) {
            echo "<script>location='" . BASE_URL . "/bank/index'</script>";
            return;
        }
        AuthModel::logout();
    }

    switch ($user_data['account_status']) {
        case 'LOCKED':
            AuthModel::logout("locked");
            break;
        case 'PENDING':
        case 'CLOSED':
        case 'REJECTED':
        case 'SUSPENDED':
            AuthModel::logout(4);
            break;
        default:
            break;
    }
    require_once BASE_DIR . "/includes/head.php";

    $password_days = AuthModel::userPasswordHasExpired($user_data['id']);
    if (gettype($password_days) == "boolean" && $password_days == true) {
        echo "<script>location='" . BASE_URL . "/password_expired'</script>";
        exit;
    }
    $user_preferences = Core::getUserPreferences();
    $demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
    ?>
    <body>
    <!-- Main navbar -->
    <div style="border:0" class="navbar navbar-expand-md navbar-dark">
        <div class="navbar-brand">
            <img src="<?php echo BASE_URL . '/assets/images/logo_light.png' ?>"/>
        </div>

        <div class="d-md-none">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="icon-tree5" style="color:black"></i>
            </button>
            <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
                <i class="icon-paragraph-justify3" style="color:black"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbar-mobile"
             style="background-color:white;border:1px white solid;min-height:80px;">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                        <i class="icon-paragraph-justify3" style="color:black"></i>
                    </a>
                </li>
            </ul>

            <span class="ml-md-3 mr-md-auto">
                <a style="float:right;background:white;" href="index.php" class="d-inline-block">
                    <img src="<?php echo BASE_URL . '/assets/images/logo_light.png' ?>" style="height:40px;"/>
                </a>
			</span>

            <ul class="navbar-nav">
                <li class="nav-item" style="margin-top: 10px">
                    <form method="post" class="timezone_switcher_form" action="logic.php">
                        <input type="hidden" name="timezone_update" value="1"/>
                        <input type="hidden" name="_token_top_nav_timezone_switch_form"
                               value="<?php echo AuthModel::generateCustomToken('_token_top_nav_timezone_switch_form') ?>"/>
                        <select name="timezone" class="form-control timezone_switcher" required>
                            <option value="">Select Timezone</option>
                            <?php
                            $list_timezones = Model::timezonesList();
                            foreach ($list_timezones as $key => $list_timezone) {
                                ?>
                                <option <?php echo empty($demographic_user_data['timezone']) ? ($key == "Central" ? "selected" : "") : ($demographic_user_data['timezone'] == $key ? "selected" : ""); ?>
                                        value="<?php echo $key; ?>"><?php echo $list_timezone; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </form>
                </li>
                <li class="nav-item dropdown" style="margin-top: 10px">
                    <a href="contract" class="navbar-nav-link nav-notifications">
                        <i><img src="image/Group9.png" style="height:15px;"></i>
                        <span class="d-md-none ml-2">Messages</span>
                        <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0 check_messages"></span>
                    </a>
                </li>

                <li class="nav-item dropdown" style="margin-top: 10px">
                    <a href="notification" class="navbar-nav-link nav-notifications">
                        <i><img src="image/Group3.png" style="height:18px;width:15px"></i>
                        <span class="d-md-none ml-2">Notifications</span>
                        <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0 check_notification"></span>
                    </a>
                </li>

                <li class="nav-item dropdown dropdown-user krishna">
                    <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle no-page-exit-alert"
                       data-toggle="dropdown">
                        <?php
                        if (!empty($user_data['profile_pic'])) {
                            ?>
                            <img src="image/<?php echo $user_data['profile_pic']; ?>" class="rounded-circle mr-2"
                                 height="57" alt=""/>
                            <?php
                        } else {
                            ?>
                            <span class="i-initial-inverse"><span><?php echo !empty($user_data['name'][0]) ? $user_data['name'][0] : 'Y' ?></span></span>
                            <?php
                        }
                        ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-navbar">
                        <?php if ($user_data['is_non_partnered_fi'] != 1) { ?>
                            <a href="javascript:void()" class="dropdown-item no-page-exit-alert"><i
                                        class="icon-volume-mute"></i> Notification &nbsp;&nbsp;&nbsp;&nbsp;<input
                                        type="checkbox" <?php echo !empty($user_preferences) && $user_preferences['mute_notification'] == 1 ? 'checked' : ''; ?>
                                        data-toggle="toggle" class="pull-right notification_ajax"></a>
                            <a href="account_settings" class="dropdown-item"><i class="icon-user-plus"></i> Account
                                Settings</a>
                            <a href="reports" class="dropdown-item"><i class="icon-coins"></i> Reports</a>
                            <?php
                        }
                        ?>
                        <div class="dropdown-divider"></div>
                        <a href="logic?logout=1" class="dropdown-item no-page-exit-alert"><i class="icon-switch2"></i>
                            Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->

    <!-- Page content -->
    <div class="page-content">
    <?php
}else{
    echo "<script>location='" . BASE_URL . "/login'</script>"; exit();
}
    ?>
<?php
if ( AuthModel::isLoggedIn() ) {
    session_start();
    $current_file = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
    $user_data = AuthModel::getUserdata();
    ?>
    <style>
        .check_chat_badge {
            display: none;
        }
    </style>
    <!-- Main sidebar -->
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
        <!-- Sidebar mobile toggler -->
        <div class="sidebar-mobile-toggler text-center">
            <a href="#" class="sidebar-mobile-main-toggle">
                <i class="icon-arrow-left8"></i>
            </a>
            Navigation
            <a href="#" class="sidebar-mobile-expand">
                <i class="icon-screen-full"></i>
                <i class="icon-screen-normal"></i>
            </a>
        </div>
        <!-- /sidebar mobile toggler -->
        <!-- Sidebar content -->
        <div class="sidebar-content">
            <!-- User menu -->
            <div class="sidebar-user">
                <div class="card-body">
                    <div class="media">
                        <div class="mr-3">
                            <?php
                            if (!empty($user_data['profile_pic'])) {
                                ?>
                                <img src="image/<?php echo $user_data['profile_pic']; ?>" width="57" height="57"
                                     class="rounded-circle" alt="">
                                <?php
                            } else {
                                ?>
                                <div class="i-initial"><?php echo !empty($user_data['name'][0]) ? Core::render($user_data['name'][0]) : 'Y' ?></div>
                                <?php
                            }
                            ?>
                        </div>

                        <div class="media-body">
                            <div class="media-title font-weight-semibold">
                                <?php
                                echo Core::render($user_data["name"]);
                                ?>
                            </div>
                            <div class="font-size-xs opacity-50"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /user menu -->

            <!-- Main navigation -->
            <div class="card card-sidebar-mobile">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    <li class="nav-item">
                        <a href="index"
                           class="nav-link <?php echo Model::HighlightUrl($current_file, ["index"]) ? 'active' : ''; ?>">
                            <i><img src="image/dashboard.png" height="20px" width="20px"></i><span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="requests"
                           class="nav-link <?php echo Model::HighlightUrl($current_file, ["requests", "bid_data"]) ? 'active' : ''; ?> <?php
                           if ($user_data['is_non_partnered_fi'] == 1 && $user_data['account_status'] != 'ACTIVE') {
                               echo 'no-page-exit-alert';
                           } ?>">
                            <i><img src="image/newrequests.png" height="20px" width="20px"></i>
                            <span>New Requests </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="my_bids"
                           class="nav-link <?php echo Model::HighlightUrl($current_file, ["my_bids", "view", "edit_bid"]) ? 'active' : ''; ?>">
                            <i><img src="image/bids.png" height="20px" width="20px"></i> <span>In Progress </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="contracts"
                           class="nav-link <?php echo Model::HighlightUrl($current_file, ["contracts", "contract_details", "msgs"]) ? 'active' : ''; ?>">
                            <i><img src="image/pendingcontract.png" height="20px" width="20px"></i> <span>Pending&nbsp;Deposits&nbsp;</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="comp"
                           class="nav-link <?php echo Model::HighlightUrl($current_file, ["comp", "c_details"]) ? 'active' : ''; ?>">
                            <i><img src="image/activecontract.png" height="20px"
                                    width="20px"></i><span>Active Deposits</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="history"
                           class="nav-link <?php echo Model::HighlightUrl($current_file, ["history", "h_details"]) ? 'active' : ''; ?>">
                            <i><img src="image/history.png" height="20px" width="20px"></i> <span>History</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /main navigation -->

            <ul class="nav nav-sidebar" data-nav-type="accordion" style="position:fixed;bottom:0;">
                <li class="nav-item">
                    <a href="https://yieldexchange.tawk.help/" target="_blank" class="nav-link"><i><img
                                    src="../depositor/image/Help.png" height="20px"
                                    width="20px"></i><span>FAQ</span></a>
                </li>
            </ul>

        </div>
        <!-- /sidebar content -->
    </div>
    <!-- /main sidebar -->
<?php
}else{
    echo "<script>location='" . BASE_URL . "/login'</script>"; exit();
}
?>
<?php
session_start();
$current_file = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>
<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md" style="background-image: url(../../assets/img/banner-bg.png);">
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
					<div class="card-body" style="padding:10px;padding-bottom:0px">
						<div class="media">
							<div class="mr-3"></div>
							<div class="media-body">
								<div class="media-title font-weight-semibold"> Welcome Admin</div>
								<div class="font-size-xs opacity-50"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- /user menu -->
				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">
						<!-- Main -->
						<li class="nav-item-header"> <i class="icon-menu" title="Main"></i></li>
						<li class="nav-item">
                            <a href="index" class="nav-link <?php echo Model::HighlightUrl($current_file,["index"])? 'active' : '';?>"><i class="icon-home4"></i><span>Dashboard</span></a>
						</li>
                        <li class="nav-item">
                            <a href="users_onboard" class="nav-link <?php echo Model::HighlightUrl($current_file,["users_onboard"]) || isset($_GET['page']) &&$_GET['page']=='users_onboard' ? 'active' : '';?>"><i class="icon-user-check"></i> <span>Users Onboard</span></a>
                        </li>
                        <li class="nav-item">
							<a href="list_depositor" class="nav-link <?php echo Model::HighlightUrl($current_file,["list_depositor"]) || isset($_GET['page']) &&$_GET['page']=='list_depositor' ? 'active' : '';?>"><i class="icon-user-check"></i> <span>GIC Investors</span></a>
						</li>
                        <!-- <li class="nav-item">
                            <a href="list_brokers" class="nav-link <?php /*echo Model::HighlightUrl($current_file,["list_brokers"]) || isset($_GET['page']) &&$_GET['page']=='list_brokers' ? 'active' : ''; */ ?>"><i class="icon-user-check"></i> <span>GIC Brokers</span></a>
                        </li> -->
                        <li class="nav-item">
							<a href="list_bank" class="nav-link <?php echo Model::HighlightUrl($current_file,["list_bank"]) || isset($_GET['page']) &&$_GET['page']=='list_bank' ? 'active' : '';?>"><i class="icon-home7"></i> <span>Banks</span></a>
						</li>
                        <li class="nav-item">
                            <a href="list_non_partnered_fi" class="nav-link <?php echo Model::HighlightUrl($current_file,["list_non_partnered_fi"]) || isset($_GET['page']) &&$_GET['page']=='list_non_partnered_fi' ? 'active' : '';?>"><i class="icon-home7"></i> <span>Non Partnered FI</span></a>
                        </li>
                         <li class="nav-item">
							<a href="manage" class="nav-link <?php echo Model::HighlightUrl($current_file,["manage","edit_ad"])? 'active' : '';?>"><i class="icon-person"></i> <span>Manage Admins</span></a>
						</li>
                        <li class="nav-item">
                            <a href="sys_setting" class="nav-link <?php echo Model::HighlightUrl($current_file,["sys_setting"])? 'active' : '';?>"><i class="icon-cog"></i> <span>System Settings</span></a>
                        </li>
                    </ul>
				</div>
				<!-- /main navigation -->
			</div>
			<!-- /sidebar content -->
		</div>
		<!-- /main sidebar -->
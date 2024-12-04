<?php
require_once("header.php");
if( AdminModel::isLoggedIn() ){
    require_once("sidebar.php");

    $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id";
    $sql_.=" AND rt.description='Admin' AND u.account_status IN('ACTIVE','SUSPENDED','CLOSED','LOCKED')";
    $data = db::getRecords($sql_);

    $size=0;
    if( !empty($data) ){
        $size=sizeof($data);
    }

    Core::activityLog("admin view admin lists");
?>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Admins</span>
					</div>

					<div class="header-elements d-none">
						<div class="d-flex justify-content-center">
						 <a href="add_ad" class="btn btn-primary mmy_btn">Add</a>
						</div>
					</div>
				</div>
			</div>
			<!-- /page header -->

			<!-- Content area -->
			<div class="content">
                <!-- Main charts -->
				<div class="row">
					<div class="col-xl-12">

						<!-- Traffic sources -->
						<div class="card">
							 	
                             <div class="table-responsive">
								<table id="dtHorizontalExample" class="table " cellspacing="0" width="100%">
									 <thead>
										<tr>
											 <th>Name</th>
											 <th>Email</th>
											 <th>Status</th>
                                            <th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php 
                                        if($data){
                                            foreach($data as $rec){
                                        ?>
                                            <tr>
                                               <td>
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="mb-0"><?php echo Core::render($rec["name"]);?> </h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="mb-0"><?php echo Core::render($rec["email"]);?> </h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php
                                                        $status = $rec['account_status'];
                                                        switch ($status){
                                                            case "ACTIVE":
                                                                echo "<span class='badge badge-success'>".$status."</span>";
                                                                break;
                                                            case "PENDING":
                                                                echo "<span class='badge badge-info'>".$status."</span>";
                                                                break;
                                                            case "SUSPENDED":
                                                            case "LOCKED":
                                                                echo "<span class='badge badge-warning'>".$status."</span>";
                                                                break;
                                                            case "CLOSED":
                                                            case "REJECTED":
                                                                echo "<span class='badge badge-secondary'>".$status."</span>";
                                                                break;
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="edit_ad?id=<?php echo Core::urlValueEncrypt($rec['id']);?>">Edit</a>
                                                            <?php
                                                            if( in_array($rec['account_status'], ["SUSPENDED","LOCKED"]) ) {
                                                            ?>
                                                                <a class="dropdown-item" href="logic?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>&&action=activate"
                                                                   onClick="return confirm('Are you sure to activate account?');">Activate</a>
                                                            <?php
                                                            }
                                                            if ( !in_array($rec['account_status'], ["CLOSED"]) ){
                                                            ?>
                                                                <a class="dropdown-item" href="logic?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>&&action=suspend"
                                                                   onClick="return confirm('Are you sure to suspend this account?');">Suspend</a>
                                                            <?php
                                                            }
                                                            if( !in_array($rec["email"],['sampath@yieldexchange.ca','ravi@yieldexchange.ca']) ){
                                                            ?>
                                                                <!-- Trigger the modal with a button -->
                                                                <a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal<?php echo $rec['id'];?>">Close</a>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div id="myModal<?php echo $rec['id'];?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <form action="logic?id=<?php echo Core::urlValueEncrypt($rec['id']);?>&&action=close" method="post">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Are you sure to Close this account?</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-12">
                                                                                <label>Reason for closing the account</label>
                                                                                <select name="reason" class="form-control">
                                                                                    <?php
                                                                                    $reasons = db::getRecords("SELECT * FROM `account_closure_reasons`");
                                                                                    foreach ( $reasons as $reason) {
                                                                                    ?>
                                                                                        <option value="<?php echo $reason['reason'];?>"><?php echo $reason['reason'];?></option>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Close Account</button>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        
							            <?php
                                            }
                                        }
                                        ?>
									</tbody>
								</table>
							</div>

						</div>
						<!-- /traffic sources -->
                        <br><br>
                        	<!-- Support tickets -->
						<div class="card"></div>
						<!-- /support tickets -->
					</div>
				</div>
				<!-- /main charts -->
			</div>
			<!-- /content area -->
<?php
require_once("footer.php");
}
?>
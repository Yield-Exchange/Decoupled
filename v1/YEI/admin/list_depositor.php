<?php
require_once("header.php");
if( AdminModel::isLoggedIn() ){
 require_once("sidebar.php");

    $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id";
    $sql_.=" AND rt.description='Depositor' AND u.account_status IN('ACTIVE','LOCKED','SUSPENDED','CLOSED')";
    $data = db::getRecords($sql_);

    $size=0;
    if( !empty($data) ){
       $size=sizeof($data);
    }

    Core::activityLog("admin view approved depositors");
?>
        <style>
            .dtHorizontalExampleWrapper {
                max-width: 600px;
                margin: 0 auto;
            }
            #dtHorizontalExample th, td {
                white-space: nowrap;
            }
            table.dataTable thead .sorting:after,
            table.dataTable thead .sorting:before,
            table.dataTable thead .sorting_asc:after,
            table.dataTable thead .sorting_asc:before,
            table.dataTable thead .sorting_asc_disabled:after,
            table.dataTable thead .sorting_asc_disabled:before,
            table.dataTable thead .sorting_desc:after,
            table.dataTable thead .sorting_desc:before,
            table.dataTable thead .sorting_desc_disabled:after,
            table.dataTable thead .sorting_desc_disabled:before {
                bottom: .5em;
            }
        </style>
        <script>
            $(document).ready(function () {
                $('#dtHorizontalExample').DataTable({
                "scrollX": true
                });
                $('.dataTables_length').addClass('bs-select');
            });
        </script>

    		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Depositor List</span>
					</div>
					<div class="header-elements d-none"></div>
				</div>
			</div>
			<!-- /page header -->
            
			<!-- Content area -->
			<div class="content">
                
                	<div class="row">
					<div class="col-xl-12">
  
						<div class="card">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr class="table-active table-border-double">
                                            <td colspan="3">Depositors</td>
                                            <td class="text-right">
                                                <span class="badge bg-blue badge-pill"><?php echo $size; ?></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
								<table id="dtHorizontalExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
									 <thead>
										<tr>
											<th>Id</th>
											<th>Institution</th>
											<th>City</th>
								            <th>Email</th>
								            <th>Telephone</th>
                                            <th>Status</th>
                                            <th>Action</th>
										</tr>
									</thead>
									<tbody>
                                            <?php
                                            $i=1;
                                            if(!empty($data)){
                                                foreach($data as $rec){
                                                    $user_id = $rec['id'];
                                                    $demographic_data = AuthModel::getUserDemographicData($user_id);
                                                    $ratings = Core::getRatings($user_id);
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="mb-0"><?php echo $i++; ?> </h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0"><?php echo Core::render($rec['name']); ?></h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0"><?php echo Core::render($demographic_data['city']); ?></h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0"><?php echo Core::render($rec['email']); ?></h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0"><?php echo Core::render($demographic_data['telephone']); ?></h6>
                                                </td>
                                                <td class="text-center">
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
                                                </td>
                                                <td>
                                                <?php
                                                    if ( !in_array($rec['account_status'], ["CLOSED"]) ){
                                                 ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="edit_user?id=<?php echo Core::urlValueEncrypt($rec['id']);?>&&page=list_depositor">Edit</a>
                                                            <?php
                                                                if( in_array($rec['account_status'], ["ACTIVE","LOCKED"]) ) {
                                                            ?>
                                                                <a class="dropdown-item" href="logic?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>&&action=suspend" onClick="return confirm('Are you sure to suspend account?');">Suspend</a>
                                                            <?php
                                                                }
                                                                if( in_array($rec['account_status'], ["SUSPENDED","LOCKED"]) ) {
                                                            ?>
                                                                <a class="dropdown-item" href="logic?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>&&action=activate"
                                                                   onClick="return confirm('Are you sure to activate account?');">Activate</a>
                                                            <?php
                                                                }
                                                            ?>
                                                            <!-- Trigger the modal with a button -->
                                                            <a href="javascript:void()" class="dropdown-item" data-toggle="modal" data-target="#myModal<?php echo $rec['id'];?>">Close</a>
                                                        </div>
                                                    </div>
                                                <?php
                                                    }
                                                ?>
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
						<!-- /support tickets -->
					</div>
				</div>
            </div>

<script>
		function deleteit(){
			return(confirm("The record will be deleted permanently. Do you really want to continue?"));
        }
		function editit(){
			return(confirm("Do you want to edit?"));
        }
</script>
<?php
require_once("footer.php");  
}
?>
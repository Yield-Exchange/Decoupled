<?php exit();
require_once("header.php");
if( AdminModel::isLoggedIn() ){
require_once("sidebar.php");

    $sql_ = "SELECT u.*,ur.role_type_id FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id";
    $sql_.=" AND rt.description='Depositor' AND u.account_status IN('PENDING')";
    $data = db::getRecords($sql_);

    $size=0;
    if( !empty($data) ){
        $size=sizeof($data);
    }
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
							<span class="breadcrumb-item active">Non Approved Depositors</span>
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
                                                <td colspan="3">Non Approved Depositors</td>
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
								            <th>ST DBRS</th>
                                            <th>Deposit Insurance</th>
                                            <th>Status</th>
										</tr>
									</thead>
									<tbody>

                                            <?php
                                            $i=1;
                                            if( !empty($data) ){
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
												<h6 class="mb-0"><?php  echo $rec['name']; ?></h6>
											</td>
											<td class="">
											    <h6 class="mb-0"><?php  echo $demographic_data['city']; ?></h6>
                                            </td>
											<td class="">
												<h6 class="mb-0"><?php  echo $rec['email']; ?></h6>
                                            
                                            </td>
                                            <td class="">
												<h6 class="mb-0"><?php  echo $demographic_data['telephone']; ?></h6>
                                            </td>
                                            <td>
                                                <?php echo $ratings['credit_rating'];?>
                                            </td>
                                            <td>
                                                <?php echo $ratings['deposit_insurance'];?>
                                            </td>
                                        	<td class="text-center">
                                                <?php
                                                     if( $rec['account_status'] !== "PENDING" ) {
                                                ?>
												    <a href="logic?id=<?php echo $rec['id']; ?>&&action=approve" class="btn btn-primary">Approve</a>
												<?php
                                                     }
                                                 ?>
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
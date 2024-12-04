<?php echo exit();
require_once("header.php");
if( AdminModel::isLoggedIn() ){
require_once("sidebar.php");
?>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Set Commission</span>
					</div>

					<div class="header-elements d-none">
						<div class="d-flex justify-content-center"></div>
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
                                            <th>Product</th>
											<th>Commission %</th>
                                            <th>Action</th>
										</tr>
									</thead>
									<tbody></tbody>
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
<?php
session_start();
require_once("header.php");

$token = AuthModel::generateToken();
if( AdminModel::isLoggedIn() ){
    require_once("sidebar.php");
?>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<a href="/" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Account Setting </span>
					</div>

					<div class="header-elements d-none">
						<div class="d-flex justify-content-center">
                            <div class="header-elements d-none">
                                <div class="d-flex justify-content-center"></div>
                            </div>
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
							<div class="card-header header-elements-inline">
		  
                                <div class="card-body">
                                    <div class="row">
                                      <div class="col-md-2"></div>
                                        <div class="col-md-8">

                                            <fieldset>
                                                <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i> Account Settings</legend>
                                                <form action="logic" method="post" id="myform" enctype="multipart/form-data">
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Old Password:</label>
                                                        <div class="col-lg-8">
                                                            <div class="col-lg-12">
                                                                <input type="text" name="old_password" class="form-control myinput" style="box-shadow: 0 0 2px gray;" placeholder="Old Password" required>
                                                            </div>
                                                        </div>
                                                        <span style="color:red">*</span>
                                                    </div>
                                                    <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">New Password:</label>
                                                        <div class="col-lg-8">
                                                            <div class="col-lg-12">
                                                                <input type="text" name="new_password" class="form-control myinput" style="box-shadow: 0 0 2px gray;" placeholder="New Password" required>
                                                            </div>
                                                        </div>
                                                        <span style="color:red">*</span>
                                                    </div>
                                                    <button style="background-color:lightgrey" type="submit" name="update_password" class="btn btn-default myinput">
                                                        Update Password
                                                    </button>
                                               </form>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>
							</div>
                        </div>
                    </div>
                    <!-- /support tickets -->
                </div>
            </div>
<?php
    require_once("footer.php");
}
?>
<?php
require_once('header.php');
if( AdminModel::isLoggedIn() ){

    require_once("sidebar.php");
    $token = AuthModel::generateToken();

    Core::activityLog("admin add admin");
?>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Admins</span>
							<span class="breadcrumb-item active">Add Admin </span>
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
                                        <?php
                                            if (!empty($_GET['case']) && $_GET['case']=="error"){
                                        ?>
                                            <div class="col-md-12">
                                                <div class="alert alert-warning">Admin with similar email exists!</div>
                                            </div>
                                        <?php
                                            }
                                        ?>
                                      <div class="col-md-2"></div>
                                        <div class="col-md-8">
                                            <fieldset>
                                                <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i> Add Admin</legend>
                                                <form action="logic" method="post" id="myform" enctype="multipart/form-data">
                                                    <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Name:</label>
                                                        <div class="col-lg-8">
                                                            <div class="col-lg-12">
                                                                <input type="text" name="name" class="form-control myinput" style="box-shadow: 0 0 2px gray;" placeholder="Name" required>
                                                            </div>
                                                        </div><span style="color:red">*</span>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Email:</label>
                                                        <div class="col-lg-8">
                                                        <div class="col-lg-12">
                                                            <input type="text" name="email" class="form-control myinput" style="box-shadow: 0 0 2px gray;" placeholder="User name" required>
                                                        </div>
                                                        </div><span style="color:red">*</span>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Password:</label>
                                                        <div class="col-lg-8">
                                                        <div class="col-lg-12">
                                                            <input type="text" name="pass" class="form-control myinput" style="box-shadow: 0 0 2px gray;" placeholder=" Password" required>
                                                        </div>
                                                    </div><span style="color:red">*</span>
                                                    </div>
                                                    <button style="background-color:lightgrey" type="submit" name="add_admin" class="btn btn-default myinput">
                                                        Add
                                                    </button>
                                               </form>
                                            </fieldset>
                                        </div>
                                    </div>
								</div>
                                <div class="col-md-2"></div>
							</div>
                              <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4"></div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <a href="manage.php" style="border:1.5px solid #2664ae" class="btn mmy_btn btn-block" >Back</a>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4"></div>
                              </div>
					    </div>
                    </div>
                </div>
            </div>
<?php
require_once("footer.php");
}
?>
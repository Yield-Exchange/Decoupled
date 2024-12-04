<?php
require_once("header.php");
if( AdminModel::isLoggedIn() ){
require_once("sidebar.php");
    $id=Core::urlValueDecrypt($_GET["id"]);
    $data = AuthModel::getUserDataByID($id);

    $token = AuthModel::generateToken();
    Core::activityLog("admin edit admin");
?>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<a href="index" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Edit Admin</span>
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
                                  <form action="logic" method="post">
                                      <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                    <table id="dtHorizontalExample" class="table" cellspacing="0" width="100%">
                                        <tbody>
                                           <tr>
                                               <td>
                                                    <h4>Email</h4>
                                                    <input type="text" class="form-control col-md-5" readonly value="<?php echo $data["email"];?>" name="email" required/>
                                                    <input type="hidden" value="<?php echo $_GET["id"];?>" name="id" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>Name</h4>
                                                    <input type="text" class="form-control col-md-5" value="<?php echo $data["name"];?>" name="name" required/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>Password</h4>
                                                    <input type="text" class="form-control col-md-5" placeholder="Password"  name="pass">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="submit" value="Update" name="sub_admin" class="btn btn-primary mmy_btn"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                  </form>
							</div>
						</div>
                         <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4"></div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <a href="manage.php" style="border:1.5px solid #2664ae" class="btn mmy_btn btn-block" >Back</a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4"></div>
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
<?php
require_once("header.php");
if( Core::isUserAdmin() ){

require_once("sidebar.php");
Core::activityLog("admin dashboard");

$banks = BankModel::getBanks();
$rec_bank = @sizeof($banks);

$depositors = DepositorModel::getDepositors();
$rec_dep = @sizeof($depositors);

$depositor_requests = db::getRecords("SELECT * FROM depositor_requests");
$rec_req = @sizeof($depositor_requests);

$bids_month=[];
for($i=1;$i<=12;$i++){
     $query="SELECT count(*) FROM offers where MONTH(created_date)='$i'";
    $bids_month[$i]=db::getCell($query);
}

$banks_month=[];
for($i=1;$i<=12;$i++){
    $query="SELECT count(*) FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND rt.description='Bank' WHERE MONTH(account_opening_date)='$i'";
    $res = db::getCell($query);
    $banks_month[$i]=empty($res) ? 0 : $res;
}
    
$dep_month=[];
for($i=1;$i<=12;$i++){
    $query="SELECT count(*) FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND rt.description='Depositor' WHERE MONTH(account_opening_date)='$i'";
    $res = db::getCell($query);
    $dep_month[$i]=empty($res) ? 0 : $res;
}
    
$request_month=[];
for($i=1;$i<=12;$i++){
    $query="SELECT count(*) FROM depositor_requests where MONTH(created_date)='$i'";
    $res = db::getCell($query);
    $request_month[$i]=empty($res) ? 0 : $res;
}
    
$contract_month=[];
for($i=1;$i<=12;$i++){
    $query="SELECT count(*) FROM contracts where MONTH(created_at)='$i'";
    $res = db::getCell($query);
    $contract_month[$i]=empty($res) ? 0 : $res;
}
  
?>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title: {
		text: "Month Wise Bids"
	},
	axisY: {
		title: "",
		suffix: "",
		includeZero: false
	},
	axisX: {
		title: "Months"
	},
	data: [{
		type: "column",
		dataPoints: [
{ label: "Jan", y: <?php echo $bids_month[1];  ?> },	
{ label: "Feb", y: <?php echo $bids_month[2]; ?>},	
{ label: "Mar", y: <?php echo $bids_month[3];?> },
{ label: "Apr", y: <?php echo $bids_month[4];?> },
{ label: "May", y: <?php echo $bids_month[5];?> },
{ label: "June", y: <?php echo $bids_month[6];?> },
{ label: "July", y: <?php echo $bids_month[7]; ?>},
{ label: "Aug", y: <?php echo $bids_month[8];?> },
{ label: "Sep", y: <?php echo $bids_month[9];?> },
{ label: "Oct", y: <?php echo $bids_month[10];?> },
{ label: "Nov", y: <?php echo $bids_month[11];?> },
{ label: "Dec", y: <?php echo $bids_month[12];?> }
			
		]
	}]
});
chart.render();
var chart = new CanvasJS.Chart("chartContainer1", {
	animationEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title: {
		text: "Month Wise Banks"
	},
	axisY: {
		title: "",
		suffix: "",
		includeZero: false
	},
	axisX: {
		title: "Months"
	},
	data: [{
		type: "column",
		dataPoints: [
{ label: "Jan", y: <?php echo $banks_month[1];  ?> },	
{ label: "Feb", y: <?php echo $banks_month[2]; ?>},	
{ label: "Mar", y: <?php echo $banks_month[3];?> },
{ label: "Apr", y: <?php echo $banks_month[4];?> },
{ label: "May", y: <?php echo $banks_month[5];?> },
{ label: "June", y: <?php echo $banks_month[6];?> },
{ label: "July", y: <?php echo $banks_month[7]; ?>},
{ label: "Aug", y: <?php echo $banks_month[8];?> },
{ label: "Sep", y: <?php echo $banks_month[9];?> },
{ label: "Oct", y: <?php echo $banks_month[10];?> },
{ label: "Nov", y: <?php echo $banks_month[11];?> },
{ label: "Dec", y: <?php echo $banks_month[12];?> }
			
		]
	}]
});
chart.render();
var chart = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title: {
		text: "Month Wise Depositers"
	},
	axisY: {
		title: "",
		suffix: "",
		includeZero: false
	},
	axisX: {
		title: "Months"
	},
	data: [{
		type: "column",
		dataPoints: [
{ label: "Jan", y: <?php echo $dep_month[1];  ?> },	
{ label: "Feb", y: <?php echo $dep_month[2]; ?>},	
{ label: "Mar", y: <?php echo $dep_month[3];?> },
{ label: "Apr", y: <?php echo $dep_month[4];?> },
{ label: "May", y: <?php echo $dep_month[5];?> },
{ label: "June", y: <?php echo $dep_month[6];?> },
{ label: "July", y: <?php echo $dep_month[7]; ?>},
{ label: "Aug", y: <?php echo $dep_month[8];?> },
{ label: "Sep", y: <?php echo $dep_month[9];?> },
{ label: "Oct", y: <?php echo $dep_month[10];?> },
{ label: "Nov", y: <?php echo $dep_month[11];?> },
{ label: "Dec", y: <?php echo $dep_month[12];?> }
			
		]
	}]
});
chart.render();
var chart = new CanvasJS.Chart("chartContainer3", {
	animationEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title: {
		text: "Month Wise Requests"
	},
	axisY: {
		title: "",
		suffix: "",
		includeZero: false
	},
	axisX: {
		title: "Months"
	},
	data: [{
		type: "column",
		dataPoints: [
{ label: "Jan", y: <?php echo $request_month[1];  ?> },	
{ label: "Feb", y: <?php echo $request_month[2]; ?>},	
{ label: "Mar", y: <?php echo $request_month[3];?> },
{ label: "Apr", y: <?php echo $request_month[4];?> },
{ label: "May", y: <?php echo $request_month[5];?> },
{ label: "June", y: <?php echo $request_month[6];?> },
{ label: "July", y: <?php echo $request_month[7]; ?>},
{ label: "Aug", y: <?php echo $request_month[8];?> },
{ label: "Sep", y: <?php echo $request_month[9];?> },
{ label: "Oct", y: <?php echo $request_month[10];?> },
{ label: "Nov", y: <?php echo $request_month[11];?> },
{ label: "Dec", y: <?php echo $request_month[12];?> }
			
		]
	}]
});
chart.render();
var chart = new CanvasJS.Chart("chartContainer4", {
	animationEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title: {
		text: "Month Wise Contracts"
	},
	axisY: {
		title: "",
		suffix: "",
		includeZero: false
	},
	axisX: {
		title: "Months"
	},
	data: [{
		type: "column",
		dataPoints: [
{ label: "Jan", y: <?php echo $contract_month[1];  ?> },	
{ label: "Feb", y: <?php echo $contract_month[2]; ?>},	
{ label: "Mar", y: <?php echo $contract_month[3];?> },
{ label: "Apr", y: <?php echo $contract_month[4];?> },
{ label: "May", y: <?php echo $contract_month[5];?> },
{ label: "June", y: <?php echo $contract_month[6];?> },
{ label: "July", y: <?php echo $contract_month[7]; ?>},
{ label: "Aug", y: <?php echo $contract_month[8];?> },
{ label: "Sep", y: <?php echo $contract_month[9];?> },
{ label: "Oct", y: <?php echo $contract_month[10];?> },
{ label: "Nov", y: <?php echo $contract_month[11];?> },
{ label: "Dec", y: <?php echo $contract_month[12];?> }
			
		]
	}]
});
chart.render();
}
</script>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Dashboard</span>
					</div>

					<div class="header-elements d-none">
						<div class="d-flex justify-content-center">
						 
						</div>
					</div>
				</div>
			</div>
			<!-- Content area -->
			<div class="content">
            <!-- Main charts -->
				<div class="row">
					<div class="col-xl-12">

						<!-- Traffic sources -->
						<div class="card">
							<div class="card-header header-elements-inline">
						 
							<div class="col-lg-4">

								<!-- Members online -->
								<div class="card bg-teal-400">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php echo $rec_bank;  ?></h3>
					                	</div>
					                	
					                	<div>
											Total Banks
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->

							</div>

							<div class="col-lg-4">

								<!-- Current server load -->
								<div class="card bg-pink-400">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php echo $rec_dep;  ?></h3>
                                        </div>
					                	
					                	<div>
											Total Customers
										</div>
									</div>

									<div id="server-load"></div>
								</div>
								<!-- /current server load -->

							</div>

							<div class="col-lg-4">

								<!-- Today's revenue -->
								<div class="card bg-blue-400">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php echo $rec_req;  ?></h3>
					                	</div>
					                	<div>
											New Requests
										</div>
									</div>
									<div id="today-revenue"></div>
								</div>
								<!-- /today's revenue -->

							</div>
					 
						<!-- /quick stats boxes -->
 
							</div>
						</div>
						<!-- /traffic sources -->
                        <br><br>
                        	<!-- Support tickets -->
						<div class="card">
                          <div id="chartContainer" style="height: 300px; width: 100%;"></div>
						</div>
						<!-- /support tickets -->
                        <div class="card">
                          <div id="chartContainer1" style="height: 300px; width: 100%;"></div>
                        </div>
                        
                         <div class="card">
                          <div id="chartContainer2" style="height: 300px; width: 100%;"></div>
						</div>
                        <div class="card">
                          <div id="chartContainer3" style="height: 300px; width: 100%;"></div>
						</div>
                        <div class="card">
                          <div id="chartContainer4" style="height: 300px; width: 100%;"></div>
						</div>
					</div>
				</div>
				<!-- /main charts -->
			</div>
			<!-- /content area -->

<script src="canvas.js"></script>
<?php
require_once("footer.php");
}
?>
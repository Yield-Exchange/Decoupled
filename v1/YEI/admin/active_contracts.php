<?php exit();
require_once("header.php");
require_once "../../config/RequestsModel.php";

if( AdminModel::isLoggedIn() ){
require_once("sidebar.php");

    $contracts = db::getrecords("SELECT c.*,i.depositor_request_id,i.invited_user_id, dr.amount,dr.currency,dr.user_id,dr.closing_date_time,dr.product_id,dr.term_length,dr.term_length_type,o.interest_rate_offer,o.maximum_amount,o.minimum_amount
                                                FROM contracts c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND c.status IN('COMPLETED')");
    $size=0;
    if(!empty($contracts)){
        $size=sizeof($contracts);
    }

    Core::activityLog("admin view active deposits");
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
						<a href="index.php"  class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Active Deposits</span>
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
                                                <td colspan="3">Active Deposits</td>
                                                <td class="text-right">
                                                    <span class="badge bg-blue badge-pill"><?php echo $size; ?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                               <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">
                                    <div class="datatable-scroll">
                                        <table class="table datatable-basic dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                    	    <thead>
                                                <tr role="row">
                                                    <th class="sorting_asc" style="padding:10px" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Refference Id: activate to sort column descending">Deposit ID</th>
                                                    <th style="padding:10px;width:20%" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Customer Name: activate to sort column ascending">Institution</th>
                                                    <th style="padding:10px;width:13%" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Deposit Amount: activate to sort column ascending">Deposit Amount</th>
                                                    <th style="padding:13px;width:20%" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Deposit Date: activate to sort column ascending">Maturity Date & Time</th>
                                                    <th style="padding:13px;width:20%" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Nominal Interest Rate %: activate to sort column ascending" data-toggle="tooltip" style="z-index:1" data-placement="left" title="Simple Annual Interest Rate">Interest Rate %</th>
                                                    <th tabindex="0" style="width:30px;padding:10px" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th>
                                                    <th style="padding:10px">Action</th>
                                                </tr>
						                    </thead>
                                        <tbody>
                                    <?php
                                    if (!empty($contracts)){
                                        foreach($contracts as $rec){
                                            $depositor_data = AuthModel::getUserDataByID($rec['user_id']);
                                            $bank_data = AuthModel::getUserDataByID($rec['invited_user_id']);
                                    ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <h6 class="mb-0"><?php echo $rec["reference_no"]; ?></h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0">
                                                        <?php
                                                            echo $bank_data['name'];
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0"  align="left">
                                                        <?php
                                                            $amount = number_format((float)str_replace(",","",$rec["amount"]));
                                                            echo $rec["currency"] . "&nbsp;&nbsp;&nbsp;" . $amount;
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td class=" ">
                                                    <h6 class="mb-0" >
                                                        <?php
                                                            $date_ = $rec["closing_date_time"];
                                                            if ( $rec['term_length_type'] == "MONTHS" ) {
                                                                $date = strtotime(date("Y-m-d", strtotime($date_)) . " +" . $rec['term_length'] . " month");
                                                            }elseif ( $rec['term_length_type'] == "HISA" ) {
                                                                // nothing
                                                            }else{
                                                                $date = strtotime(date("Y-m-d", strtotime($date_)) . " +" . $rec['term_length'] . " day");
                                                            }
                                                            echo date("Y-m-d",$date);
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0">
                                                        <?php
                                                            echo BankModel::getInterest($rec["interest_rate_offer"]);
                                                        ?>
                                                    </h6>
                                                </td>
                                                <td style="color:green">
                                                    Completed
                                                </td>
                                                <td class="text-center">
                                                    <a href="c_details?cnid=<?php echo $rec["id"];?>" class="btn btn-primary  mmy_btn btn-block">Details</a>
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
            
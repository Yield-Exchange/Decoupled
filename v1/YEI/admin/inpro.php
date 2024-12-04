<?php exit();
require_once("header.php");
require_once "../../config/RequestsModel.php";

if( AdminModel::isLoggedIn() ){

    require_once("sidebar.php");
    $data = db::getrecords("SELECT * FROM depositor_requests WHERE request_status IN('ACTIVE') order by id desc");

    $new_data = array();
    foreach ($data as $new_datum) {
        $depositor_request_id = $new_datum['id'];
        $offers = db::getrecords("SELECT o.* FROM offers o,invited i WHERE i.id=o.invitation_id AND o.offer_status IN('ACTIVE','CONFIRMED') AND i.depositor_request_id = '$depositor_request_id'");
        if (empty($offers)){
            continue;
        }
        $new_datum['offers'] = $offers;
        $interest_rate_offer = db::getCell("SELECT MAX(interest_rate_offer) FROM offers o,invited i WHERE i.id=o.invitation_id AND o.offer_status IN('ACTIVE','CONFIRMED') AND i.depositor_request_id = '$depositor_request_id'");
        $new_datum['highest_offer'] = !empty($interest_rate_offer) ? $interest_rate_offer : 0;
        array_push($new_data,$new_datum);
    }

    $size = sizeof($new_data);

    Core::activityLog("admin view In Progress");
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
							<span class="breadcrumb-item active">In Progress</span>
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
                                                <td colspan="3">In progress</td>
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
                                                <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Reference id: activate to sort column descending">Reference id</th>
                                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Amount: activate to sort column ascending">Amount</th>
                                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Date of deposit: activate to sort column ascending">Date of deposit</th>
                                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Product: activate to sort column ascending">Product</th>
                                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="No of Offers: activate to sort column ascending">No of Offers</th>
                                                 <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Highest: activate to sort column ascending">Highest</th>
                                                <th class="text-center sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" style="width: 100px;">Actions</th>
                                            </tr>
                                        </thead>
						                <tbody>
						             <?php
                                        if( !empty($new_data) ){
                                            foreach($new_data as $record){
                                                $r_id=$record["id"];
                                    ?>
										<tr>
                                            <td>
												<h6 class="mb-0"><?php echo $record["reference_no"];?> </h6>
                                            </td>
											<td>
												<h6 class="mb-0">
                                                    <?php
                                                        $amount=$record["amount"];
                                                        echo $record["currency"]." ".$amount;
                                                    ?>
                                                </h6>
											</td>
											<td>
												<h6 class="mb-0"><?php echo $record["date_of_deposit"];?> </h6>
											</td>
                                            <td>
												<h6 class="mb-0">
                                                    <?php
                                                        $product = RequestsModel::getProductByID($record["product_id"]);
                                                        echo !empty($product) ? $product['description'] : '';
                                                    ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0"><?php echo count($record['offers']);?> </h6>
                                            </td>
                                            <td>
												<h6 class="mb-0"><?php echo BankModel::getInterest($record['highest_offer']);?></h6>
                                            </td>
                                            <td>
                                                <a href="rq_details?id=<?php echo $record["id"];?>" class="btn btn-primary mmy_btn">View Request</a>
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
						</div>
						<!-- /support tickets -->
					</div>
				</div>
            </div>
 <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4 style="color:red" class="modal-title">Bidding has started !</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <p>Action can not be completed.</p>
            <p>Bidding has started on this request .</p>
        </div>
        <div class="modal-footer">
          <button type="button" style="border:1.5px solid #2664ae" class="btn mmy_btn" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>              

 <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4 style="color:red" class="modal-title">No Bids To View !</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>
        <div class="modal-body">
            <p>Action can not be completed.</p>
          <p>Bidding has started not on this request .</p>
        </div>
        <div class="modal-footer">
          <button type="button"   style="border:1.5px solid #2664ae" class="btn mmy_btn" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
 </div>
 <script>
		function deleteit(){
			return(confirm("The record will be deleted permanently. Do you realy want to continue?"));
        }
		function editit(){
			return(confirm("Do you want to edit?"));
        }
</script>
<?php
require_once("footer.php");
}
?>
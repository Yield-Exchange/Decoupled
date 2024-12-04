<?php
require_once("header.php");
if( AdminModel::isLoggedIn() ){
    require_once("sidebar.php");

    $sql_ = "SELECT u.*,ur.role_type_id,rt.description FROM users u, user_role_types ur, role_types rt WHERE ur.user_id=u.id AND rt.id=ur.role_type_id AND u.account_status IN('PENDING')";
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
                    <span class="breadcrumb-item active">Users Onboard List</span>
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
                                    <td colspan="3">Users Onboard</td>
                                    <td class="text-right">
                                        <span class="badge bg-blue badge-pill"><?php echo $size; ?></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">
                                <div class="datatable-scroll">
                                    <table class="table datatable-basic dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending">#</th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Role: activate to sort column descending">Role</th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="City: activate to sort column ascending">City</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending">Email</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Telephone: activate to sort column ascending">Telephone</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Account Opening Date &amp; Time: activate to sort column ascending">Account Opening Date &amp; Time</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(!empty($data)){
                                            $counter=1;
                                            foreach($data as $rec){

                                                $user_id = $rec['id'];
                                                $demographic_data = AuthModel::getUserDemographicData($user_id);
                                                $ratings = Core::getRatings($user_id);
                                                ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0"><?php echo $counter++;?> </h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0"><?php  echo $rec['description']; ?></h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0"><?php  echo $rec['name']; ?></h6>
                                                    </td>
                                                    <td><?php  echo $demographic_data['city']; ?></td>
                                                    <td class="">
                                                        <h6 class="mb-0"><?php  echo $rec['email']; ?></h6>
                                                    </td>
                                                    <td><?php  echo $demographic_data['telephone']; ?></td>
                                                    <td><?php echo $rec['account_opening_date'];?> UTC</td>
                                                    <td class="text-center">
                                                        <span class='badge badge-info'><?php echo $rec['account_status'];?></span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item" href="edit_user?id=<?php echo Core::urlValueEncrypt($rec['id']);?>&&page=users_onboard">Edit</a>
                                                                <?php
                                                                    if( $rec['account_status'] == "PENDING" ) {
                                                                        if ( !empty($ratings['deposit_insurance']) && !empty($ratings['credit_rating']) || ($rec['description'] !="Broker" && $rec['description'] !="Bank") ) {
                                                                ?>
                                                                    <a class="dropdown-item" href="logic?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>&&action=approve">Approve</a>
                                                                <?php
                                                                        }else{
                                                                ?>
                                                                    <a class="dropdown-item" onclick="updateCreditRatings()" href="javascript:void();">Approve</a>
                                                                <?php
                                                                        }
                                                                ?>
                                                                    <a class="dropdown-item" href="logic?id=<?php echo Core::urlValueEncrypt($rec['id']); ?>&&action=reject">Reject</a>
                                                                <?php
                                                                    }
                                                                ?>
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
            </div>
        </div>
        <script>
            function deleteit(){
                return(confirm("The record will be deleted permanently. Do you really want to continue?"));
            }
            function editit(){
                return(confirm("Do you want to edit?"));
            }

            function updateCreditRatings() {
                return alert('Update credit and insurance before approving');
            }
        </script>
    <?php
    require_once("footer.php");
}
?>
<?php
session_start();
require_once("header.php");

if( AuthModel::isLoggedIn() ){
    require_once("sidebar.php");

    global $user_data;
    $demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
    $user_id = $user_data['id'];

    Core::activityLog("Depositor View Notifications");

    $query="SELECT * from notifications where user_id='$user_id' order by id desc";
    $rec=db::getRecords($query);

    db::query("UPDATE notifications SET status='SEEN' WHERE user_id='$user_id'");
    ?>
    <!-- Main content -->
    <div class="content-wrapper">
    <!-- Content area -->
    <div class="content">

        <div class="row">
            <div class="col-xl-12">

                <div class="card">

                    <div class="col-12">

                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <tbody>
                                    <tr class="table-active table-border-double">
                                        <td colspan="3">Notifications</td>
                                        <td class="text-right"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">

                            <div class="datatable-scroll">

                                <table class="table datatable-basic dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <tr role="row">
                                            <th>Institution</th>
                                            <th>Notification Details</th>
                                            <th>Sent Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    if(!empty($rec))
                                    {
                                        foreach($rec as $re)
                                        {
                                            $sent_by = AuthModel::getUserDataByID($re['sent_by'])
                                    ?>
                                        <tr>
                                            <td>
                                                <h6 class="mb-0"><?php echo !empty($sent_by) ? Core::render($sent_by["name"]) : ''; ?></h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <?php  echo $re['details']; ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <?php  echo Model::dateTimeFromUTC('Y-m-d h:i:s a',$re['date_sent'],$demographic_user_data['timezone']); ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <a href="logic?del_notification_id=<?php echo Core::urlValueEncrypt($re['id']);?>" onclick="return deleteit();"><img style="height:10px;" src="../depositor/image/cross.png" class="img-responsive"></a>
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

                </div>


            </div>


        </div>


    </div>

    <script>
        function deleteit(){
            return(confirm("The record will be deleted permanently. Do you really want to continue?"));
        }
    </script>
    <?php
    require_once("footer.php");
}
?>
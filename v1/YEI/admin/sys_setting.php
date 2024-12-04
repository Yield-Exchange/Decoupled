<?php
require_once("header.php");
if( AdminModel::isLoggedIn() ){
    require_once("sidebar.php");
   $user_data = AdminModel::getUserdata();

    Core::activityLog("admin edit system settings");
    $message="";
    if ( isset($_POST['update_system_setting']) ){
        if ( AdminModel::authCsrfToken(false) ) {
            $system_setting = Core::getSystemSettings('prime_rate');

            if ( isset($_POST['prime_rate']) && (!empty($system_setting) && $_POST['prime_rate'] != $system_setting['value']) ) {
                $prime_rate = $_POST['prime_rate'];

                $prime_rate = number_format((float)$prime_rate, 2, '.', '');
                $dateTime = Model::utcDateTime();

                $sql = "INSERT INTO system_settings_archive (SELECT 0, `key`, `value`, created_date, modified_by, modified_date FROM system_settings WHERE `key`='prime_rate')";
                db::insertRecord($sql);

                $sql = "UPDATE `system_settings` SET `value`= ?, `modified_date`=?,`modified_by`=? WHERE `key`=?";
                db::preparedQuery($sql, ['ssis', $prime_rate, $dateTime, $user_data['id'], 'prime_rate']);
                $message = "Updated successfully";
            }else{
                $message = "Nothing Updated.";
            }
        }
    }

    $system_setting = Core::getSystemSettings('prime_rate');
    $token = AuthModel::generateToken();
?>
    <!-- Main content -->
    <div class="content-wrapper">

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <a href="index" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                <span class="breadcrumb-item active">System Settings</span>
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
                        <form action="" method="post">
                            <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                            <table id="dtHorizontalExample" class="table" cellspacing="0" width="100%">
                                <tbody>
                                <?php
                                if ( !empty($message) ) {
                                    ?>
                                    <tr>
                                        <td colspan="3"><div class="alert alert-info col-md-5"> <?php echo $message ?></div></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <td style="width: 15%"><span style="font-weight: 300">Set Prime Rate %</span></td>
                                    <td>
                                        <input type="number" step=".01" class="form-control col-md-12" value="<?php echo !empty($system_setting) ? $system_setting['value'] : '';?>" name="prime_rate" required/>
                                    </td>
                                    <td>
                                        <span style="font-weight:normal;font-size:15px;">
                                            Last updated on:
                                            <u>
                                            <?php
                                                echo !empty($system_setting) ? $system_setting['modified_date'].' UTC' : '';
                                            ?>
                                            </u>
                                        <span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right">
                                        <input type="submit" value="Save" name="update_system_setting" class="btn btn-primary mmy_btn btn-lg"/>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
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
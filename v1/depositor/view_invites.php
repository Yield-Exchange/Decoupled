<?php
session_start();
require_once "header.php";

if (AuthModel::isLoggedIn()) {

    if(empty($_SERVER['HTTP_REFERER'])){
        header("Location: index.php");
    }

    require_once "sidebar.php";

    Core::activityLog("Depositor Review Offers -> View Invites");

    $rid = Core::urlValueDecrypt($_GET['rid']);
    $banks = DepositorModel::getInvitedBanksByID($rid);

    if ( !empty($banks) ){
        $size = sizeof($banks);
?>
    <script>
        $(document).ready(function () {
            $('.custom-data-tables').DataTable({
                "order": [[0, "desc"]],
                "scrollX": true,
            });
        });
    </script>
    <!-- Main content -->
    <div class="content-wrapper">

    <!-- Content area -->
    <div class="content">

        <div class="row">
            <div class="col-xl-12">

                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">

                            <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b">Inv</span>ited Institution</td>
                                    <td class="text-left"></td>
                                </tr>
                            </tbody>

                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap tbl_index">
                            <thead>
                                <tr>
                                    <th>Institution</th>
                                    <th>Province</th>
                                    <th>Short term DBRS rating</th>
                                    <th>Deposit Insurance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($banks as $record) {
                                    $user_id = $record['id'];
                                    $user_demographic_data = AuthModel::getUserDemographicData($user_id);
                                    $credit_ratings = Core::getRatings($user_id);
                            ?>
                                    <tr>
                                        <td>
                                            <h6 class="mb-0"><?php echo Core::render($record["name"]); ?></h6>
                                        </td>
                                        <td>
                                            <div class="">
                                                <h6 class="mb-0"><?php echo Core::render($user_demographic_data["province"]); ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0"><?php echo !empty($credit_ratings) ? Core::render($credit_ratings["credit_rating"]) : ''; ?></h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0"><?php echo !empty($credit_ratings) ? Core::render($credit_ratings["deposit_insurance"]) : ''; ?></h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0"><?php echo Core::render(ucwords(strtolower(str_replace("_"," ",$record['invitation_status'])))); ?></h6>
                                        </td>
                                    </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 text-center">
                        <br/>
                        <br/>
                            <a href="<?=  !empty(Core::previousUrl()) ? Core::previousUrl() : 'bids' ?>" class="btn btn-outline-secondary btn-block col-md-3">Back</a>
                        <br/>
                        <br/>
                    </div>
                </div>
                <!-- /support tickets -->
            </div>
        </div>
    </div>
<?php
    require_once "footer.php";
    }
}
?>
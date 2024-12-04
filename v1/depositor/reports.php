<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

if (AuthModel::isLoggedIn()) {
    require_once "sidebar.php";
    global $user_data;
    $demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);

    Core::activityLog("Depositor Reports");
    $contracts_data = DepositorModel::getDepositorsContractsReports($user_data['id'],@$_POST['date_from'],@$_POST['date_to']);
    $contracts = $contracts_data['data'];
?>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
    <script src="<?php echo BASE_URL.'/assets/';?>js/moment-timezone.js"></script>
    <script type="text/javascript">
            let format = 'YYYY/MM/DD HH:mm:ss ZZ';
            let timeZone = <?php echo json_encode(Model::formattedTimezone($demographic_user_data['timezone']));?>;
            let todayDateWithUserTimezone = moment(moment().toISOString(), format).tz(timeZone);
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            let date_picker = $('.date_picker');
            date_picker.datetimepicker('destroy');
            date_picker.datetimepicker({
                maxDate:moment(),
                timepicker: false,
                format: 'Y-m-d',
                onChangeDateTime: function(dp, $input) {
                    var fromdate=$input.val();
                    $('.date_picker1').datetimepicker({
                        defaultDate:fromdate,
                        minDate:fromdate,
                        maxDate:moment(),
                        timepicker: false,
                        format:'Y-m-d',
                    });
                    if (fromdate > $('.date_picker1').val()){
                        $('.date_picker1').val(fromdate);
                    }
                }
            });

            $('.date_picker1').datetimepicker({
                defaultDate:moment().format("Y-MM-DD"),
                timepicker: false,
                format:'Y-m-d',
            });

            $('.date_picker').val()!=''?$('.date_picker').val():$('.date_picker').val(moment().add(-90,"days").format("Y-MM-DD"));
            $('.date_picker1').val()!=''?$('.date_picker1').val():$('.date_picker1').val(moment().format("Y-MM-DD"));
            
            $('.custom-data-tables').DataTable({
                "order": [[ 0, "desc" ]],
                "scrollX": true
            });
        });
    </script>
    <div class="content-wrapper">

    <!-- Content area -->
    <div class="content">

        <div class="row">
            <div class="col-xl-12">

                <div class="card">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr class="table-active table-border-double">
                                    <td colspan="2" class="my_h"><span class="b_b">REP</span>ORT <span class="badge bg-blue badge-pill"><?php echo !empty($contracts) ? count($contracts) : 0; ?></span></td>
                                    <td>
                                    <?php if ( !empty($contracts) ) { foreach ($contracts as $maxgicdat) { $maxgicdate=$maxgicdat['maxgicdate']; }} ?>
                                        <form method="post" action="logic?download_report_csv=true" autocomplete="off" class="row filter_form">
                                            <label class="col-sm-2 text-right"><br/>Date&nbsp;From:</label>
                                            <input type="text" class="form-control col-sm-3 date_picker" id="date_from" name="date_from" value="<?php echo !empty($_POST['date_from']) ? $_POST['date_from'] : ''; ?>" /><br/>
                                            <input type="hidden" class="maxgicdate"  value=" <?php echo $maxgicdate; ?>" />
                                            <label class="col-sm-2 text-right"><br/>Date&nbsp;To:</label>
                                            <input type="text" class="form-control col-sm-3 date_picker1" id="date_to" name="date_to" value="<?php echo !empty($_POST['date_to']) ? $_POST['date_to'] : ''; ?>"/>
                                            <button type="submit" class="btn btn-primary col-sm-1 inline-block btn-sm filterr" name="filter" style="margin-left:2%;">Filter</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                            <button type="submit" class="btn bg-grey-300" name="filter" style="margin-left:2%;"><span class="badge bg-blue badge-pill"><?php echo !empty($contracts) ? count($contracts) : 0; ?></span> Export Pdf</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12">
                        <table id="dtHorizontalExample" class="table table-condensed custom-data-tables table-sm" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Contract Number</th>
                                    <th>GIC Number</th>
                                    <th>Bank Name</th>
                                    <th>Product</th>
                                    <th>Term</th>
                                    <th>Lockout/Notice Period</th>
                                    <th>Amount</th>
                                    <th>Interest Rate</th>
                                    <th>Start Date</th>
                                    <th>Maturity Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ( !empty($contracts) ) {
                                $counter = 1;
                                foreach ($contracts as $contract) {
                                    if($contract["reference_no"]!='')
                                    {
                            ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0"><?php echo Core::render($contract["reference_no"]); ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0"><?php echo Core::render($contract["gic_number"]); ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">
                                                <?php
                                                    $bank_data = AuthModel::getUserDataByID($contract["invited_user_id"]);
                                                    echo Core::render($bank_data['name']);
                                                ?>
                                            </h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">
                                                <?php
                                                    $product = RequestsModel::getProductByID($contract["product_id"]);
                                                    echo !empty($product) ? Core::render($product['description']) : '';
                                                ?>
                                            </h6>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0"><?php echo Core::render($contract['term_length'].' '.strtolower($contract['term_length_type'])); ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0"><?php echo !empty($contract["lockout_period_days"]) ? Core::render($contract["lockout_period_days"]).' days' : ''; ?> </h6>
                                            </div>
                                        </td>
                                        <td data-order="<?php echo $contract["amount"];?>">
                                            <h6 class="mb-0">
                                                <?php
                                                    echo Core::render($contract['currency']).' '.number_format($contract['amount']);
                                                ?>
                                            </h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">
                                                <?php
                                                if ($contract['rate_type']!='VARIABLE'){
                                                    echo BankModel::getInterest($contract['interest_rate_offer']);
                                                }else {
                                                    echo BankModel::getInterest($contract['prime_rate'], true,$contract['rate_operator']);
                                                }
                                                ?>
                                            </h6>
                                        </td>
                                        <td style="border-right:0">
                                            <h6 class="mb-0">
                                                <?php echo Model::dateTimeFromUTC('Y-m-d',$contract['gic_start_date'],$demographic_user_data['timezone']); ?>
                                            </h6>
                                        </td>
                                        <td style="border-right:0">
                                            <h6 class="mb-0">
                                                <?php echo !empty($contract["maturity_date"]) ? Model::dateTimeFromUTC('Y-m-d',$contract['maturity_date'],$demographic_user_data['timezone']) : ''; ?>
                                            </h6>
                                        </td>
                                    </tr>
                                    <?php
                                    $counter++;
                                }
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
    <script>
        $(document).on("click",".filterr",function(e){
            e.preventDefault()
            $(".filter_form").attr("action","");
            $(".filter_form").submit();
        });
    </script>
<?php
    require_once "footer.php";
}
?>
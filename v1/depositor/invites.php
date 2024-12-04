<?php
session_start();
require_once "header.php";
require_once BASE_DIR."/config/RequestsModel.php";

if (AuthModel::isLoggedIn()) {
    if(empty($_SERVER['HTTP_REFERER'])){
        echo "<script>location='index'</script>";
    }
    require_once "sidebar.php";
    $token = AuthModel::generateToken();

    global $user_data;
    $banks = BankModel::getBanksANDBrokersWithCreditDepositInsuranceFilters($_SESSION["val18"],$_SESSION["val12"],$user_data,true);

    $size = 0;
    if ( !empty($banks) ){
        $size = sizeof($banks);
    }
    $email = $user_data["email"];

    $rdref = Core::urlValueDecrypt(trim($_GET['rdref']));
    $p_edit = Core::urlValueDecrypt(trim($_GET['pdit']));

    $deposit_amount = $_SESSION["val4"];
    $deposit_start = $_SESSION["val5"];
    $bid_opening = $_SESSION["val11"];
    $id=$_SESSION["dep_id"];

    if ( empty($deposit_amount) || empty($bid_opening) || empty($deposit_start) ){
        echo "<script>location='".(isset($_GET['editing']) ? 'edit_request?id='.$id : 'p_req')."'</script>"; exit();
    }
?>
<style>
    input[type=checkbox]
    {
        /* Double-sized Checkboxes */
        -ms-transform: scale(2); /* IE */
        -moz-transform: scale(2); /* FF */
        -webkit-transform: scale(2); /* Safari and Chrome */
        -o-transform: scale(2); /* Opera */
        transform: scale(2);
        padding: 10px;
    }
    .fade:not(.show){
        opacity: unset;
    }
    #InviteNewFi{
        margin-top: 20px;
    }
    #InviteNewFi  input, #InviteNewFi p, #InviteNewFi span{
        font-weight: 500;
        font-size: 15px;
    }
    .progress {
        display: -ms-flexbox;
        display: flex;
        height: 2.125rem;
        font-size: 1rem;
    }
</style>
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
                                            <td colspan="4" class="my_h">Select institutions</td>
                                            <!-- <td><button type="button" class="btn btn-primary mmy_btn pull-right" data-toggle="modal" data-target="#update">Add a New FI</button></td> -->
                                            <td class="text-left"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-sm-12">
                                <div class="table-responsive">

                                <table class="table custom-data-tables no-footer">
                                     <thead>
                                        <tr>
                                            <th>Institution</th>
                                            <th>Province</th>
                                            <th>Short term DBRS rating</th>
                                            <th>Deposit Insurance</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    if (!empty($banks)) {
                                        //var_dump($banks);
                                        foreach ($banks as $record) {
                                            $user_id = $record["id"];
                                            $demographic_data = AuthModel::getUserDemographicData($user_id);
                                            $credit_ratings = Core::getRatings($user_id);
                                            $request = RequestsModel::getRequestByRef($_SESSION["val1"]);

                                            $request_id = !empty($request) ? $request['id'] : 0;

                                            $query2 = "SELECT * from invited where invited_user_id='$user_id' AND depositor_request_id='$request_id'";
                                            $invited_bank = db::getRecord($query2);
                                    ?>
                                        <tr>
                                            <td class="">
                                                <h6 class="mb-0"><?php echo Core::render($record["name"]); ?></h6>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <h6 class="mb-0"><?php echo Core::render($demographic_data["province"]); ?></h6>
                                                </div>
                                            </td>
                                            <td class="">
                                                <h6 class="mb-0"><?php echo !empty($credit_ratings) ? Core::render($credit_ratings["credit_rating"]) : ''; ?></h6>
                                            </td>
                                            <td class="">
                                                <h6 class="mb-0"><?php echo !empty($credit_ratings) ? Core::render($credit_ratings["deposit_insurance"]) : ''; ?></h6>
                                            </td>
                                            <td class="text-left">
                                                <?php
                                                if( !empty($invited_bank) && in_array($invited_bank['invitation_status'], ["INVITED","PARTICIPATED"]) ){
                                                    $non_fi_invited_participation_count = db::getCell("SELECT count(*) as total from invited where invited_user_id='$user_id' AND depositor_request_id != '$request_id'");
                                                ?>
                                                    <input type="checkbox" <?php if( $record['is_non_partnered_fi'] ==1 && $non_fi_invited_participation_count==0 ){ echo 'class="select_row_non_fi"'.'  '.' disabled data-toggle="modal" data-target="#pop-message"' ; }else{ echo 'class="select_row"';} ?> checked id="<?php echo $user_id;?>" />
                                                <?php
                                                }else{
                                                    if( isset($_SESSION['new_invited_fi']) ){
                                                     $is_just_invited = (isset($_SESSION['new_invited_fi']) && in_array($user_id,$_SESSION['new_invited_fi']) );
                                                ?>
                                                    <input type="checkbox" <?php if( $is_just_invited ){ echo 'class="select_row_non_fi" checked'.'  '.' disabled'; }else{ echo 'class="select_row"';} ?> id="<?php echo $user_id;?>"  />
                                                <?php
                                                    } else {
                                                ?>
                                                    <input type="checkbox" <?php echo !empty($_SESSION['CACHE_INVITES']) && ($_SESSION['CACHE_INVITES']=='all' || is_array($_SESSION['CACHE_INVITES']) && in_array($user_id,$_SESSION['CACHE_INVITES'])) ? 'checked' : ''?>  id="<?php echo $user_id;?>" class="select_row" />
                                                <?php
                                                    }
                                                }
                                                ?>
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
                            <div class="container-fluid row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                                    <button onclick="window.location.href='<?php echo isset($_GET['editing']) ? 'edit_request?id='.Core::urlValueEncrypt($id) : 'p_req'; ?>'" style="border:1.5px solid gainsboro" class="btn btn-block">Back</button>
                                </div>
                                <div class="col-sm-10 col-10 col-md-10" style="text-align: right;">
                                    <button class="btn btn-primary btn-lg select_all">Invite All</button>
                                        &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-warning btn-lg clear_invites" >Clear All</button>
<!--                                    <button  class="btn btn-warning btn-lg" id="reset">Clear All</button>-->
                                    &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-primary btn-lg send_invites">Next</button>
                                </div>
                            </div>
                            <br/><br/>
                        </div>
                        <!-- /support tickets -->
                    </div>
                </div>
            </div>

    <!-- Modal -->
    <div id="update" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Add New Financial Institution</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="myWizard">
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="2" style="width: 50%;">
                            Step 1 of 2
                        </div>
                    </div>

                    <div class="navbar" style="display: none">
                        <div class="navbar-inner">
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#step1" data-toggle="tab" data-step="1">Step 1</a></li>
                                <li><a href="#step2" data-toggle="tab" data-step="2">Step 2</a></li>
                            </ul>
                        </div>
                    </div>

                    <form action="" method="post" autocomplete="off" id="InviteNewFi">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="step1">
                                <div class="row">
                                    <div class="col-md-12 well">
                                        <h5>Institution Name</h5>
                                        <div class="form-group">
                                            <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                                            <input type="hidden" name="action" value="INVITE_NEW_FI"/>

                                            <select name="name" class="form-control select2 institution_select" required>
                                                <option value="">Select Institution</option>
                                                <?php
                                                $fi = BankModel::getFI(true);
                                                foreach ($fi as $f){
                                                ?>
                                                    <option value="<?php echo $f['name'];?>"><?php echo $f['name'];?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <h5>Email</h5>
                                        <div class="form-group">
                                            <input  type="email" id="email" name="email" placeholder="Email Address"  class="form-control col-md-12" />
                                        </div>

                                        <h5>Re-Email</h5>
                                        <div class="form-group">
                                            <input  type="email" id="re-email" name="re_enter_email" placeholder="Re-email Address"  class="form-control col-md-12" />
                                        </div>

                                        <div class="form-group">
                                            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Close"> 
                                            <input type="reset" class="btn btn-primary" value="Clear" />
                                            <input type="button" name="next_step_non_partnered_fi" class="btn btn-primary mmy_btn pull-right next" value="Next" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="step2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 style="color: red">
                                                    I acknowledge that Yield Exchange will send my request details as well as this email to <span id="institution_name"></span> on my behalf.
                                                </h5>
                                        <div class="card card-primary">
                                            <div class="card-header">
                                
                                            </div>
                                            <div class="card-body">
                                                <p>Hi <input name="account_manager_name" type="text" maxlength="30" placeholder="Account Manager Name" class="form-control col-md-5" style="display: inline" />,</p>
                                                <br/>
                                                <p>
                                                    I'm inviting you to participate in my deposit request through Yield Exchange; a secure, digital platform that allows me to negotiate with Canadian Financial Institutions like you, easily and efficiently.
                                                </p>
                                                <br/>
                                                <p>
                                                    Please contact me if you have any questions regarding this invitation, otherwise I look forward to your response on my Deposit.
                                                </p>
                                                <br/>
                                                <p>Thanks,</p>
                                                <input name="your_name" type="text" placeholder="Input your name here" class="form-control col-md-5" maxlength="30" style="display: inline" />
<!--                                                <p><span id="account_manager_name_footer"></span></p>-->
                                                <br/>
                                                <br/>
                                                <p><?php
                                                    $depositor_demographic_data = AuthModel::getUserDemographicData($user_data['id']);
                                                    echo $user_data['email']?></p>
                                                <p><?php echo $depositor_demographic_data['telephone']?></p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <input type="button" class="btn btn-secondary back" value="Back">
                                            <input type="button" class="btn btn-primary" data-dismiss="modal" value="Cancel Invite" />
                                            <input type="submit" name="create_non_partnered_fi" class="btn btn-primary mmy_btn pull-right" value="Send" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    

<script>
    let action = '<?php echo isset($_GET['editing']) ? 'confirm_values1' : 'confirm_values';?>';
    let table;
</script>
<script src="js/invites.js?v=1.8"></script>
<script>
    let close_btn_clicked_yes=false;
    $('#update').on('hide.bs.modal', function () {
        if(close_btn_clicked_yes){
            close_btn_clicked_yes=false;
            return true;
        }
        swal({
            title: "Are you sure?",
            text: "You are about to abandon this invitation, would you wish to proceed?",
            icon: "warning",
            buttons: ["No", "Yes"],
        }).then((response) => {
            if (response) {
                $('#myWizard a:first').tab('show');
                $('#InviteNewFi').trigger("reset");
                close_btn_clicked_yes=true;
                $('#update').modal('hide');
            }else{
                close_btn_clicked_yes=false;
            }
        });
        return false;
    });
    
    $(".send_invites").click(function(){

        //Show all the rows
        table.page.len( -1 ).draw();

        let arr = [];

        $(".select_row").each(function(){
            if($(this).is(":checked")){
                let id = $(this).attr("id");
                arr.push(id);
            }
        });

        $(".select_row_non_fi").each(function () {
            if($(this).is(":checked")){
                let id = $(this).attr("id");
                arr.push(id);
            }
        });

        if(arr.length > 0){
            let encoded = btoa(JSON.stringify(arr));
            window.location.href="logic?"+action+"=1&&send_invites=<?php echo empty($rdref) ? 0 : $rdref;?>&&to="+encoded;
        }else{
            alert("Please select Institution to send invites");
        }

    });
</script>

<script>

 //function resetForm(){ alert('wapi'); $(':text:not("[readonly],[disabled]")').val(''); }

//var resetForm = function(){ $('input,select,textarea').not('[readonly],[disabled],:button').val(''); }

$("#reset").click(function(){
  $("input").each(function(){

    if( $(this).is('[disabled]')== false)
    {
       $(this).removeAttr("checked");
    }
  });
});
</script>

<?php
require_once "footer.php";
}
?>
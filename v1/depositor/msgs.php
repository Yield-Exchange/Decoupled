<?php
session_start();
require_once "header.php";
require_once "sidebar.php";

if (AuthModel::isLoggedIn()){

$token = AuthModel::generateToken();
if ( isset($_GET['id']) && isset($_GET['c_id'])) {
    global $user_data;
    $user_id = $user_data['id'];

    $id = Core::urlValueDecrypt($_GET['id']);
    $c_id = Core::urlValueDecrypt($_GET['c_id']);
    db::query("UPDATE chat set status='SEEN' where sent_to='$user_id' AND deposit_id='$c_id'");

    Core::activityLog("Depositor Pending Deposits -> Action Chat");
    $contract_data = db::getrecord("SELECT c.*,i.invited_user_id,dr.currency FROM deposits c, offers o, invited i, depositor_requests dr WHERE c.offer_id = o.id AND i.id = o.invitation_id AND dr.id = i.depositor_request_id AND dr.user_id = '$user_id'AND c.id='$c_id' AND c.status NOT IN('COMPLETED')");
    if ( empty($contract_data) ){
        echo "<script>location='contract'</script>";
    }
    $bank_data = AuthModel::getUserDataByID($contract_data['invited_user_id']);
?>
        <style>
        #show {
          height:400px;
          overflow-y: auto;
        }
        .tab-content,.tab-pane{
            border:0px;
        }
        </style>
        <script>
            $(document).ready(function() {

                $(document).on('keypress',function(e) {
                    if(e.which == 13) {
                        $.ajax({
                            type : "POST",
                            url : "insert.php",
                            data : $("#frm").serialize(),
                            success : function(data) {
                                $("#frm")[0].reset();
                                $(".show").stop().animate({ scrollTop: $(".show")[0].scrollHeight}, 1000);
                            }
                        });
                    }
                });

                $("#save").click(function(){
                    $.ajax({
                        type : "POST",
                        url : "insert.php",
                        data : $("#frm").serialize(),
                        success : function(data) {
                             $("#frm")[0].reset();
                            $(".show").stop().animate({ scrollTop: $(".show")[0].scrollHeight}, 1000);
                        }
                    });
                });

                let firstLoad=true;
                setInterval(function(){
                    $.ajax({
                        type : "GET",
                        url : "data.php",
                        data : {c_id: '<?php echo isset($_GET['c_id']) ? $_GET['c_id'] : ''; ?>'},
                        success : function(data) {
                            $("#show").html(data);
                            if (firstLoad){
                                firstLoad=false;
                                $(".show").stop().animate({ scrollTop: $(".show")[0].scrollHeight}, 1000);
                            }
                        }
                    });
                }, 1000);
            });
        </script>
    <!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			<div class="content">
                <h4>Messages </h4>
                <div class="row">
                    <div class="col-md-3"><strong>Institution: </strong><?php echo Core::render($bank_data['name']);?></div>
                    <div class="col-md-3"><strong>Deposit ID: </strong><?php echo Core::render($contract_data['reference_no']);?></div>
                    <div class="col-md-3"><strong>Awarded Amount: </strong><?php echo Core::render($contract_data['currency']).' '.number_format($contract_data['offered_amount']);?></div>
                    <br/>
                    <br/>
                </div>
                <div>
					<div class="tab-content card card-body border-top-0 rounded-0 rounded-bottom mb-0" style="border:0px">
						<div class="tab-pane fade active show" id="show" style="border:0px"></div>
                        <form id="frm">
                            <input type="hidden" name="_token" value="<?php echo $token; ?>"/>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="c_id" value="<?php  echo $c_id; ?>">
                            <textarea  id="msg" name="msg" class="form-control mb-3" rows="3" cols="1" placeholder="Enter your message..."></textarea>
                            <div class="d-flex align-items-center">
                                <button id="save" type="button" class="btn bg-primary btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b> Send</button>
                            </div>
                        </form>
					</div>
				</div>

            </div>
  <div class="modal fade" id="chat_modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4 style="color:red" class="modal-title">Warning</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>
        <div class="modal-body">
          <p>Do not share or request Bank account number/s or any related sensitive information in the chat rooms.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary mmy_btn" data-dismiss="modal">Sure</button>
        </div>
      </div>

    </div>
  </div>
<?php
require_once "footer.php";
}else{
    ob_clean();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

}
?>
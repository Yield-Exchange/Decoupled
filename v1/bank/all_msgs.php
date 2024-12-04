<?php
session_start();
require_once("header.php");
require_once("sidebar.php");

if ( AuthModel::isLoggedIn() ){
    global $user_data;
    $user_id = $user_data['id'];
    $data=DepositorModel::getDepositors();

    $sql="SELECT c.*,i.invited_user_id FROM `deposits` c, `offers` o, `invited` i, `depositor_requests` dr WHERE i.id=o.invitation_id AND o.id=c.offer_id AND dr.id=i.depositor_request_id AND i.invited_user_id='$user_id'";
    $contracts_list = db::getRecords($sql);

    Core::activityLog("Bank all messages");
?>
        <style>
             @media only screen and (min-width: 767px) {
                #mobilescreen{
                    display: none;
                }
                 #desktopscreen{
                      display: block;
                 }
             }
            @media only screen and (max-width: 767px) {
                #mobilescreen{
                    display: block;
                    
                }
                #desktopscreen{
                      display:none;
                 }
            }
            .dropbtn {
              background-color: #4CAF50;
              color: white;
              padding: 16px;
              font-size: 16px;
              border: none;
            }

            .dropdown {
              position: relative;
              display: inline-block;
            }

            .dropdown-content {
              display: none;
              position: absolute;
              background-color: #f1f1f1;
              min-width: 160px;
              box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
              z-index: 1;
            }

            .dropdown-content a {
              color: black;
              padding: 12px 16px;
              text-decoration: none;
              display: block;
            }

            .dropdown-content a:hover {background-color: #ddd;}
            .dropdown:hover .dropdown-content {display: block;}
            .dropdown:hover .dropbtn {background-color: #3e8e41;}
            .list-group{
                height:calc(100vh - 90px);
              overflow-y:auto;
            }
            #show {
              height:400px;
              overflow-y: auto;
            }   
            #list_ins:hover{
                background-color: whitesmoke;
            }
        </style>
        
        <script>
            $(document).ready(function() {
                $("#save").click(function(){
                    $.ajax({
                        type : "POST",
                        url : "insert.php",
                        data : $("#frm").serialize(),
                        success : function(data) {
                             $("#frm")[0].reset();
                        }
                    });                    
                });
                     
                setInterval(function(){
                    $.ajax({
                        type : "GET",
                        url : "data.php",
                        data : {c_id: '<?php echo isset($_GET['c_id']) ? $_GET['c_id'] : ''; ?>'},
                        success : function(data) {
                            $("#show").html(data)
                        }
                    });
                }, 1000); 
            });
        </script>
        <!-- Main content -->
		<div class="content-wrapper">

            <div class="content" id="mobilescreen">
                <div class="row" >
                    <div class="col-xs-12">
                        <div class="dropdown">
                          <button class="dropbtn">Deposits</button>
                          <div class="dropdown-content">
                              <?php
                              if(!empty($contracts_list)){
                                  foreach($contracts_list as $record){
                                      $bank_data = AuthModel::getUserDataByID($record['invited_user_id']);
                                      $contract_id= $record['id'];
                                      $unread = db::getCell("SELECT COUNT(*) FROM `chat` WHERE deposit_id='$contract_id' AND status='NEW'");
                                      $last_sent_date = db::getCell("SELECT created_at FROM `chat` WHERE deposit_id='$contract_id' ORDER BY created_at DESC LIMIT 1");
                              ?>
                                      <a href="all_msgs?c_id=<?php  echo $record['id']; ?>"><strong>
                                              <img src="../bank/image/<?php  echo $bank_data['profile_pic']; ?>" class="rounded-circle" width="40" height="40" alt="" />
                                              &nbsp;</strong><?php echo '<small>('.$unread.')</small> '.$record['reference_no'].' <small> '.$last_sent_date;?></small>
                                      </a>
                              <?php
                                  }
                              }
                              ?>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
			<!-- Content area -->
			<div class="content">
               <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="panel-body"  id="desktopscreen">
                            <ul style="box-shadow: 0 4px 2px -2px gray;" class="list-group">
                                <span style="font-weight:bold;color:black;margin-left:20px;padding:20px;font-size:17px;">Deposits</span>
                                <?php
                                if(!empty($contracts_list)){
                                    foreach($contracts_list as $record){
                                        $bank_data = AuthModel::getUserDataByID($record['invited_user_id']);
                                        $contract_id = $record['id'];
                                        $unread = db::getCell("SELECT COUNT(*) FROM `chat` WHERE deposit_id='$contract_id' AND status='NEW'");
                                        $last_sent_date = db::getCell("SELECT created_at FROM `chat` WHERE deposit_id='$contract_id' ORDER BY created_at DESC LIMIT 1");
                                ?>
                                        <li id="list_ins" class="list-group-item"><strong>
                                                <a href="all_msgs?c_id=<?php  echo $record['id']; ?>"><strong>
                                                        <img src="../bank/image/<?php  echo $bank_data['profile_pic']; ?>" class="rounded-circle" width="40" height="40" alt="">
                                                        &nbsp;</strong><?php echo '<small>('.$unread.')</small> '.$record['reference_no'].'<small> '.$last_sent_date;?></small>
                                                </a>
                                            </strong>
                                        </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                   </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="tab-content card card-body border-top-0 rounded-0 rounded-bottom mb-0">
                            <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4">
                                    <p style="font-weight:bold;text-align:center;">
                                        <?php
                                        if (isset($_GET['c_id'])){
                                            $c_id=$_GET['c_id'];
                                            $sql="SELECT c.*,i.invited_user_id FROM `deposits` c, `offers` o, `invited` i, `depositor_requests` dr WHERE i.id=o.invitation_id AND o.id=c.offer_id AND dr.id=i.depositor_request_id AND dr.user_id='$user_id' AND c.id='$c_id'";
                                            $contract = db::getRecord($sql);

                                            $query="UPDATE chat set status='SEEN' where sent_to='$user_id' WHERE deposit_id='$c_id'";
                                            db::query($query);

                                            if (!empty($contract)){
                                                echo $contract['reference_no'];
                                            }
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-sm-4"></div>
                            </div>
						    <div class="tab-pane fade active show" id="show"></div>
					    </div>
                    </div>
                </div>
            </div>

<?php
require_once("footer.php");
}
?>
<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";
require_once "../config/AuthModel.php";

if ( AuthModel::isLoggedIn() ){
$user_data = AuthModel::getUserdata();
$demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
$user_id = $user_data['id'];

$c_id = isset($_GET['c_id']) ? Core::urlValueDecrypt($_GET['c_id']): 0;
$query = "select * from chat where deposit_id='$c_id' ORDER BY created_at ASC";

$data=db::getRecords($query);
?>
<div class="tab-content card card-body border-top-0 rounded-0 rounded-bottom mb-0">
    <div class="tab-pane fade active show">
        <ul class="media-list media-chat mb-3">
           <?php
            if( !empty($data) )
            {
                foreach($data as $datum)
                {
                    if ($user_id == $datum['sent_by']){
                        $chat_user = AuthModel::getUserDataByID($datum['sent_by']);
                        ?>
                        <li class="media media-chat-item-reverse">
                            <div class="media-body">
                                <div class="media-chat-item">
                                    <?php
                                        echo Core::render($datum['message']).'<br/><small>'.$datum['created_at'].'</small>';
                                    ?>
                                </div>
                            </div>
                            <div class="ml-3">
                                <?php
                                if ( !empty($chat_user['profile_pic']) ) {
                                ?>
                                    <img src="image/<?php echo $chat_user['profile_pic']; ?>" class="rounded-circle" width="40" height="40" alt=""/>
                                <?php
                                } else {
                                ?>
                                    <div class="i-initial-inverse"><?php echo !empty($chat_user['name'][0]) ? Core::render($chat_user['name'][0]) : 'Y'?></div>
                                <?php
                                }
                                ?>
                            </div>
                        </li>
                        <?php
                    }else{
                        $chat_user = AuthModel::getUserDataByID($datum['sent_by']);
                        ?>
                        <li class="media">
                            <div class="mr-3">
                                <?php
                                if ( !empty($chat_user['profile_pic']) ) {
                                ?>
                                    <img src="../depositor/image/<?php  echo $chat_user['profile_pic'];  ?>" class="rounded-circle" width="40" height="40" alt="" />
                                <?php
                                } else {
                                ?>
                                    <div class="i-initial-inverse"><?php echo !empty($chat_user['name'][0]) ? Core::render($chat_user['name'][0]) : 'Y'?></div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="media-body">
                                <div class="media-chat-item">
                                    <?php
                                        echo Core::render($datum['message']).'<br/><small>'. Model::dateTimeFromUTC('Y-m-d h:i:s a',$datum['created_at'],$demographic_user_data['timezone']).'</small>';
                                    ?>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
    </div>

</div>
<?php
}
?>
    
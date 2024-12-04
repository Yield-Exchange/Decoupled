<?php exit();
session_start();
require_once "header.php";

global $notification_email;

if ( AuthModel::isLoggedIn() ) {

    require_once "sidebar.php";
    $offered_amount = isset($_GET['offered_amount']) ? str_replace(",","",$_GET['offered_amount']) : 0;
    $user_data_ = AuthModel::getUserdata();
    $user_id_ =$user_data_['id'];

    if (isset($_GET['bid_id'])) {

        Core::activityLog("Depositor Review Offers -> View offers -> View -> Award Offer");

        $bid_id = $_GET['bid_id'];

        $offer = BankModel::getOfferByID($bid_id);
        if (!empty($offer)){

        $sql_ = "select dr.* from depositor_requests dr,offers o, invited i where i.depositor_request_id = dr.id AND o.invitation_id = i.id AND o.id='$bid_id' AND user_id='$user_id_'";
        $depositor_request = db::getrecord($sql_);

        if (empty($depositor_request)){
            echo "<script>location='bids'</script>"; exit();
        }

        $depositor_id = $depositor_request['user_id'];
        $con_ = db::getRecord("SELECT c.* FROM deposits c, offers o WHERE c.offer_id = o.id AND o.id='$bid_id'");
        $contract_id = $con_['id'];

        RequestsModel::archiveTable($contract_id, "deposits");
        db::query("DELETE FROM deposits WHERE offer_id='$bid_id'");
        $time_now = Core::timeNow();

        $ref_ = DepositorModel::generateOfferContractID( $depositor_request["reference_no"] );

        $query = "INSERT INTO `deposits`(`reference_no`, `offer_id`, `offered_amount`, `status`, `created_at`) VALUES ('$ref_','$bid_id','$offered_amount','IN_PROGRESS', '$time_now')";
        db::query($query);

        $bank  = db::getRecord(" SELECT u.* FROM offers o, invited i, users u WHERE o.invitation_id = i.id AND u.id = i.invited_user_id AND o.id='$bid_id'");

        $toEmail = AuthModel::getUserDataByID($depositor_request['user_id'])['email'];;
        $subject = "Congratulations - Awarded";

        $message = "You awarded the GIC to " . (!empty($bank['name']) ? $bank['name'] : ' (Bank) ');

        $message .= "<br />";
        $message .= "What's next: Login to the account and 'Pending deposits' to view the details. <p style='color:red'>Do not share your account details through the chat forum</p>.";
        $message .= "<br/>";
        $message .= "Have questions?  Please contact info@yieldexchange.ca";

        Core::sendMail($subject, $toEmail, $message,'inv',true);

        $toEmail = $bank['email'];
        $subject = "Congratulations - Offer Accepted";
        $message = "Congratulations!!! You offer has been accepted. Awarded Amount: ".$depositor_request['currency']." ".$offered_amount." (Request ID " . $ref_ . ")";
        $message .= "<br />";
        $message .= "What's next: Login to the account and 'Pending Deposits' to compare the received offers. Use the chat/email/phone to discuss the fund transfer process. <strong style='color:red;'>Do not</strong> request or share Bank account information through the chat function.";
        $message .= "<br />";
        $message .= "Have questions?  Please contact info@yieldexchange.ca";

        Core::sendMail($subject, $toEmail, $message, 'inv',true);

        $notification = "This Request Id " . $ref_. " has been awarded to you. Kindly Check your Deposit Section. ";

        Core::sendAdminEmail("Offer Accepted", $notification);

        $logged_in_user = AuthModel::getUserdata();
        $logged_in_user_id = $logged_in_user['id'];

        $query1 = "INSERT into notifications (`type`, `details`, `date_sent`, `user_id`,`sent_by`, `status`) VALUES('DEPOSIT CREATED','$notification','$time_now','$depositor_id','$logged_in_user_id','ACTIVE')";
        db::query($query1);
        echo "<script>location='bids'</script>";

    ?>

		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			<div class="content">
            <!-- Main charts -->
				<div class="row">
					<div class="col-xl-12">
                        <!-- Support tickets -->
						<div class="card" style="padding:20px;padding-top:10px">
                             <div class="table-responsive" style="padding-left:0px">
								<table class="table text-nowrap">

                                    <tbody>
                                        <tr class="table-active table-border-double">
											<td style="padding-left:10px" colspan="3" class="my_h"><span class="b_b">CONT</span>RACT</td>
											<td class="text-right"></td>
										</tr>
                                    </tbody>

                                </table>
                            </div>
                            <div style="padding-top:20px;" class="row">
                                <div class="col-lg-8">
                                    <h2>Congratulations!!!!</h2>
                                    <p style="font-size:15px;">Your Confirmation has been sent to <span style="font-weight:bold"><?php echo $bank['name']; ?></span>. </p>
                                    <p style="font-size:15px;">
                                        Thank you
                                    </p>
                                    <p style="font-size:15px;">
                                        Yield Exchange
                                    </p>
                                </div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-2"></div>
                            </div>

                            <div style="padding:20px;" class="row">
                               <div class="col-lg-2">
                                   <a href="contract" class="btn btn-primary mmy_btn btn-block">
                                        Done
                                    </a>
                               </div>
                                <div class="col-lg-4"></div>
                                <div class="col-lg-4"></div>
                            </div>

						</div>

					</div>

				</div>

			</div>
			<!-- /content area -->

            <!-- Modal -->


<?php
require_once "footer.php";
        }else{
            echo "<script>location='bids'</script>";
        }
    }else{
        echo "<script>location='bids'</script>";
    }
}
?>

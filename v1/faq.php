<?php
include "config/db.php";
$page_title="Login";
require(BASE_DIR."/includes/header.php");
?>
<style>

    #banner{
        padding-top: 150px!important;
    }
</style>
    <!-- ====== Banner Part HTML Start ====== -->
    <div id="banner" style="background:white;">
				<div class="container">
					<div class="theme-title-four text-center theme-title">
						<h2>Frequently Asked Questions</h2>
						<p></p>
					</div> <!-- /.theme-title-four -->

					<div class="row">
						<div class="col-md-12">
						    <article style="font-size:18px;">
						        
						         <div class="row">
                                    <div class="col-10 mx-auto">
                                        <div class="accordion" id="faqExample">
                                            <div class="card">
                                                <div class="card-header p-2" id="headingOne">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                          What is Yield Exchange?
                                                        </button>
                                                      </h5>
                                                </div>

                                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqExample">
                                                    <div class="card-body">
                                                         We are NOT a deposit-taking institution. Yield Exchange is a GIC auction platform.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="headingTwo">
                                                    <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                      Who are the main participants on the platform?
                                                    </button>
                                                  </h5>
                                                </div>
                                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        GIC investors and associated financial institutions.  GIC investors post their GIC requests and associated financial institutions offer the best rates for the posted requests.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                         How much does it cost?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        This is completely free for GIC investors.  Financial institutions pay a commission if the GIC investment is placed with them.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="headingwhy">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsewhy" aria-expanded="false" aria-controls="collapsewhy">
                                                          Why should I use Yield Exchange?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapsewhy" class="collapse" aria-labelledby="headingwhy" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        For GIC investors, Yield Exchange is a convenient, confidential and smarter way to get the best interest rates from our associated financial institutions.
                                                        GIC investors are under no obligation to proceed with any offer received from the financial institution.
                                                        For financial institutions, Yield Exchange is a cost-effective marketplace to pick and choose the GIC investments based on your cash flow requirements.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading4">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                                         How does the platform work?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#faqExample">
                                                    <div class="card-body">
                                                                a. GIC investors complete a request form and submit through the platform.  <br/>
                                                                b. Our associated Financials institutions (FI) receive this information <br/>
                                                                c. FI send offers through the platform <br/>
                                                                d. Investor picks the FI suits their needs <br/>
                                                                e. Investor and FI setup and transfer funds outside the Yield Exchange platform
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading5">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                                         What is an offer?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        Offer is an indicative simple annual interest rate for your GIC investment.  You are under no obligation to accept the offer.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading6">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                                         Who will send offers?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        All the financial institution participants on our Yield Exchange platform registered regulated financial institutions in Canada.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading7">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapseThree">
                                                         How can the investor set up the GIC after choosing the FI?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse7" class="collapse" aria-labelledby="heading7" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        Yield Exchange only helps in introducing the two parties.  Once the investor chooses the indicative offer, your contact details are sent to the successful financial institution who will get in touch with you to finalize all the details through to settlement.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading8">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                                                         What information a GIC investor needs to provide?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse8" class="collapse" aria-labelledby="heading8" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        <ul>
                                                            <li> <h3>Mandatory information</h3>
                                                                <ul>
                                                                    <li>▪ Product: There are four main products you can choose from.
                                                                            <div class="col-sm-10 col-sm-offset-1">
                                                                                <p>• Cashable</p>
                                                                                <p>• Redeemable</p>
                                                                                <p>• Non-redeemable </p>
                                                                                <p>• High-interest savings </p>
                                                                            </div>
                                                                    </li>
                                                                    <li>▪ Term length: This is the investment period in months</li>
                                                                    <li>▪ Date of deposit: The approximate deposit date.</li>
                                                                    <li> ▪ Closing date/time: Deadline accepting the offers</li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                <br/><hr/>
                                                            </li>
                                                            <li><h3>Optional Information</h3>
                                                              <ul class="col-sm-10 col-sm-offset-1">
                                                                <li>▪ Ask rate: Indicate an asking rate</li>
                                                                <li>▪ DBRS rating: Indicate the minimum credit rating requirement of the financial institution you want to do business with</li>
                                                                <li>▪ Compounding frequency: Number of times per year the accumulated interest is paid out or capitalized regularly.     However, Yield Exchange offer comparison is based on the simple annual interest not based on the compounded interest rate. </li>
                                                                <li>▪ Anonymous Deposit Request:  Hides your information from the financial institution.  This is an anonymous deposit request.  </li>
                                                                <li>▪ Special instructions: Investor can use the free text to share additional information on the request </li>
                                                              <ul>
                                                            </li>
                                                            </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading9">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
                                                         What happens after the investor submits the request?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse9" class="collapse" aria-labelledby="heading9" data-parent="#faqExample">
                                                    <div class="card-body">
                                                    Yield Exchange associated financial institutions receive notifications of all the new requests posted by the potential GIC investors. Financial institutions submit the best rates through the platform.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading10">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
                                                         Do other financial institutions see offers by the competitors?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse10" class="collapse" aria-labelledby="heading10" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        Financial institutions do not see offers submitted by competitors.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading11">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse11" aria-expanded="false" aria-controls="collapse11">
                                                         Does Yield Exchange make any offers or recommendations?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse11" class="collapse" aria-labelledby="heading11" data-parent="#faqExample">
                                                    <div class="card-body">
                                                    Yield Exchange is an independent platform.  We do NOT make any offer or recommendation about GIC products available through this website.
                                                        If you decide to accept an offer for your GIC you should confirm all the relevant product information with your selected financial institution and consider whether the products,
                                                        services and institution meet your needs before you decide. For further details refer to our terms and conditions.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading12">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse12" aria-expanded="false" aria-controls="collapse12">
                                                         Am I obligated to accept any of the offers I receive?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse12" class="collapse" aria-labelledby="heading12" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        No. You are under no obligation to accept any of the offers presented to you.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading13">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse13" aria-expanded="false" aria-controls="collapse13">
                                                         How do I compare the offers?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse13" class="collapse" aria-labelledby="heading13" data-parent="#faqExample">
                                                    <div class="card-body">
                                                       Firstly, log in to your account. All offers will be summarized on your "Dashboard" and "Review offers" for quick reference, starting with the latest first.
                                                       However, you must evaluate the terms of each offer before you choose the one that's best for you.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading14">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse14" aria-expanded="false" aria-controls="collapse14">
                                                         Am I obligated to accept any of the offers I receive?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse14" class="collapse" aria-labelledby="heading14" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        No. You are under no obligation to accept any of the offers presented to you.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading15">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse15" aria-expanded="false" aria-controls="collapse15">
                                                         How do I accept an offer?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse15" class="collapse" aria-labelledby="heading15" data-parent="#faqExample">
                                                    <div class="card-body">
                                                       "Review offers" section lists all the offers sent by financial institutions. For detailed information of each offer, simply click on the "view" button.  Now to award the GIC, on "award" button and select the financial institution.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading16">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse16" aria-expanded="false" aria-controls="collapse16">
                                                         I've accepted an offer. What next?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse16" class="collapse" aria-labelledby="heading16" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        After accepting an offer, the investor can communicate directly with the financial institution by chat, email or phone to complete the GIC process.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading17">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse17" aria-expanded="false" aria-controls="collapse17">
                                                         What happens if I change my mind about dealing with a financial institution?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse17" class="collapse" aria-labelledby="heading17" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        If for any reason you change your mind about dealing with your selected financial institution click on the "retract offer" button in the "review offers" section.
                                                        This will allow you to select another offer. You can only deal with one offer at a time.  Alternatively, if you do not wish to deal with them contact us and we will inform them on your behalf. Email us at info@yieldexchange.ca.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading18">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse18" aria-expanded="false" aria-controls="collapse18">
                                                         Can I change information after I have submitted it?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse18" class="collapse" aria-labelledby="heading18" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        You can only change the information before you receive any offers.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading19">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse19" aria-expanded="false" aria-controls="collapse19">
                                                         I did not receive my PIN?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse19" class="collapse" aria-labelledby="heading19" data-parent="#faqExample">
                                                    <div class="card-body">
                                                       Please check your spam file first in case the emails are there.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header p-2" id="heading200">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse200" aria-expanded="false" aria-controls="collapse200">
                                                            How can I compare different short term ratings?
                                                        </button>
                                                      </h5>
                                                </div>
                                                <div id="collapse200" class="collapse" aria-labelledby="heading200" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        We have created a mapping table to simplify this process.
                                                        <br/>
                                                        <img class="img-responsive" style="width: 100%" src="assets/images/short-term-ratings.png" alt="IMG" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header p-2" id="heading20">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse20" aria-expanded="false" aria-controls="collapse20">
                                                            You have other questions?
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapse20" class="collapse" aria-labelledby="heading20" data-parent="#faqExample">
                                                    <div class="card-body">
                                                        Please send a message to info@yieldexchange.ca.  We will get back to you within 24 hours.
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                 </div>
    <!--/row-->
						    </article>
					    </div>
				    </div>
                </div>
            </div>
<?php
require("includes/footer.php");
?>

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositorMails extends Mailable implements ShouldQueue
{

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $typeofmail = "";
    public $data = [];
    public function __construct($passeddata, $emailtype)
    {
        $this->data = $passeddata;
        $this->typeofmail = $emailtype;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $passed = $this->data;

        switch ($this->typeofmail) {
            case "pending_deposits":
                $pendingdeposits = $passed['pending_deposits'];
                return $this->view('emails.depositor.postrequests.approve-contratcs', compact("pendingdeposits"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                // case "expiring_gic":
                //     $expiringGIC = $passed['expiring_gic'];
                //     return $this->view('emails.depositor.postrequests.maturity-deadline-gic', compact("expiringGIC"))
                //         ->subject(setEmailHeader($passed['subject']))
                //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');                
                // case "offers_selected":
                //     $offersselected = $passed['offers_selected'];
                //     return $this->view('emails.depositor.postrequests.approved-deposits-feed', compact("offersselected"))
                //         ->subject(setEmailHeader($passed['subject']))
                //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');    /////TODO

            case "new_post_request":
                $newrequestDetails = $passed['new_request_details'];
                return $this->view('emails.depositor.postrequests.request-active', compact("newrequestDetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

                // case "campaign_products":
                //     $campaign = $passed['campaign'];
                //     return $this->view('emails.depositor.campaigns.investment-guide', compact("campaign"))
                //         ->subject(setEmailHeader($passed['subject']))
                //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

                // case "campaign_product_purchase":
                //     $product = $passed['purchasedetails'];
                //     return $this->view('emails.depositor.campaigns.gic-purchased', compact("product"))
                //         ->subject(setEmailHeader($passed['subject']))
                //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

                // case "campaigns_marketing":
                //     $products = $passed['products'];
                //     return $this->view('emails.depositor.campaigns.discover-gics', compact("products"))
                //         ->subject(setEmailHeader($passed['subject']))
                //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            case "funds_received":
                $depositdetails = $passed['request_details'];
                return $this->view('emails.depositor.postrequests.funds-received', compact("depositdetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            case "activate_gic":
                $depositdetails = $passed['request_details'];
                return $this->view('emails.depositor.postrequests.activate-gic', compact("depositdetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

                // case "new_post_request_offer":
                //     $offerdetails = $passed['new_offer_details'];
                //     return $this->view('emails.depositor.postrequests.fi-new-offer-placed', compact("offerdetails"))
                //         ->subject(setEmailHeader($passed['subject']))
                //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

                // case 'depositor_pending_deposits':
                //     $offerdetails = $passed['data'];
                //         return $this->view('emails.depositor.postrequests.fi-new-offer-placed', compact("offerdetails"))
                //             ->subject(setEmailHeader($passed['subject']))
                //             ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            case "inactivate_deposit":
                $user_type = $passed['user_type'];
                $header = $passed['header'];
                $organization_name = $passed['organization_name'];
                return $this->view('emails.depositor.inactive-deposit', compact("user_type", "header", "organization_name"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                break;
            case "yourOrgUserAccessRequest":
                $passed = $this->data;
                $access_request = $passed['access_request'];
                return $this->view('emails.depositor.accounts.access_request', compact("access_request"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');    
                break;


            default:

                break;
        }
    }
}

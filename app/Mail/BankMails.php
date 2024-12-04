<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BankMails extends Mailable implements ShouldQueue
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

            case "offers_selected":
                $offersselected = $passed['offers_selected'];
                $depositororganizationname = $passed['depositor_organization_name'];
                return $this->view('emails.bank.postrequests.approved-deposits-feed', compact("offersselected", "depositororganizationname"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            // case "campaign_product_purchase":
            //     $product = $passed['purchasedetails'];
            //     return $this->view('emails.bank.campaign.gic-purchased', compact("product"))
            //         ->subject(setEmailHeader($passed['subject']))
            //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc'); TODO

            // case "campaign_edited":
            //     $campaign = $passed['campaign'];
            //     return $this->view('emails.bank.campaign.campaign-edited', compact("campaign"))
            //         ->subject(setEmailHeader($passed['subject']))
            //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            // case "order_limit_alert":
            //     $purchasedetails = $passed['purchasedetails'];
            //     return $this->view('emails.bank.campaign.product-expiry', compact("purchasedetails"))
            //         ->subject(setEmailHeader($passed['subject']))
            //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');                      

            case "new_post_request":
                $newpostrequestdetails = $passed['new_request_details'];
                return $this->view('emails.bank.postrequests.new-request-invitation', compact("newpostrequestdetails"))
                            ->subject(setEmailHeader($passed['subject']))
                            ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');


            case "share_documents":
                $documents = $passed['documents'];
                $name = $passed['name'];
                return $this->view('emails.bank.share-documents',compact("documents",'name'))
                            ->subject(setEmailHeader($passed['subject']))
                            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');

            case "activate_gic":
                $depositdetails = $passed['request_details'];
                
                $header = $passed['header'];
                $text = $passed['message'];
                $user_type = 'Bank';
                return $this->view('emails.depositor.postrequests.create-gic', compact("depositdetails","header","text","user_type"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc'); 

            case 'bank_pending_deposits':
                $count = $passed['count'];
                return $this->view('emails.bank.pending-deposits', compact("count"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc'); 
                
            case 'inactivate_deposit':
                $header = $passed['header'];
                $user_type = $passed['user_type'];
                $organization_name = $passed['organization_name'];
                return $this->view('emails.admin.inactive-deposit', compact("header","user_type","organization_name"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc'); 
                break;

            default:

                break;
        }
    }
}

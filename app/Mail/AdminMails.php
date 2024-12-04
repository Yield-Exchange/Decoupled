<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AdminMails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $typeofmail = "";
    public $data = [];
    public $roleprefix="ADMIN - ";
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
        if ($this->typeofmail === "pending_accounts") {
            $passed = $this->data;
            $pendingaccounts = $passed['pending_accounts'];
            return $this->view('emails.admin.onboarding.pending_accounts', compact("pendingaccounts"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
        } else if ($this->typeofmail === "pending_deposits") {
            $passed = $this->data;
            $pendingdeposits = $passed['pending_deposits'];
            return $this->view('emails.admin.postrequests.review-contracts', compact("pendingdeposits"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
        }
        //  else if ($this->typeofmail === "offer_awarded") {
            // $passed = $this->data;
            // $pendingdeposits = $passed['pending_deposits'];
            // return $this->view('emails.admin.postrequests.review-contracts', compact("pendingdeposits"))
            //     ->subject($this->roleprefix.setEmailHeader($passed['subject']))
            //     ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
        // }
        //  else if ($this->typeofmail === "offers_selected") {
        //     $passed = $this->data;
        //     $offersselected = $passed['offers_selected'];
        //     $depositororganizationname = $passed['depositor_organization_name'];
        //     return $this->view('emails.bank.postrequests.approved-deposits-feed', compact("offersselected", "depositororganizationname"))
        //         ->subject($this->roleprefix.setEmailHeader("Offers Accepted"))
        //         ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
        // }
         else if ($this->typeofmail === "incomplete_registeration") {
            $passed = $this->data;
            $business_stage = $passed["data"];
            return $this->view('emails.admin.onboarding.abandoned-account', compact("business_stage"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']));
        } else if ($this->typeofmail === "transaction_complete") {
            $passed = $this->data;
            $reference_no = $passed['message'];
            return $this->view('emails.admin.postrequests.transaction-completed', compact("reference_no"))
                ->subject($this->roleprefix.setEmailHeader($passed["subject"]));
        } else if ($this->typeofmail === "new_offer_deposit") {
            $passed = $this->data;
            $new_offer = $passed["data"];
            return $this->view('emails.admin.postrequests.deposit-offer', compact("new_offer"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
        } else if ($this->typeofmail === "new_post_request") {
            $passed = $this->data;
            $newrequestDetails = $passed['new_request_details'];
            return $this->view('emails.admin.postrequests.deposit-request', compact("newrequestDetails"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
        } else if ($this->typeofmail === 'activate_gic') {
            $passed = $this->data;
            $text = $passed['message'];
            $depositdetails = $passed['request_details'];
            $header = $passed['header'];
            $user_type = 'Admin';
            return $this->view('emails.depositor.postrequests.create-gic', compact("depositdetails","header","text",'user_type'))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
        }
        else if ($this->typeofmail === 'admin_offers_selected') {
            $passed = $this->data;
            $offersselected = $passed['offers_selected'];
            $depositororganizationname = $passed['depositor_organization_name'];
            return $this->view('emails.admin.offer-selected', compact("offersselected","depositororganizationname"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
        }
        else if ($this->typeofmail === 'inactivate_deposit') {
            $passed = $this->data;
            $user_type = $passed['user_type'];
            $organization_name = $passed['organization_name'];
            $header = $passed['header'];
            return $this->view('emails.admin.inactive-deposit', compact("user_type","header","organization_name"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

        }else if ($this->typeofmail === "abandoned_consolidated_email") {
            $passed = $this->data;
            $organization_data = $passed["organization_data"];
            return $this->view('emails.admin.onboarding.abandoned-account-consolidate', compact("organization_data"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']));

        }
        else if ($this->typeofmail === 'newRequest') {
            $passed = $this->data;
            $CTRequest = $passed['ct_Request'];
            return $this->view('emails.admin.Trade.new-request-invitation', compact("CTRequest"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');         
            
        }
        else if ($this->typeofmail === 'offerWithdrawn') {
            $passed = $this->data;
            $CTRequest = $passed['ct_Request'];
            return $this->view('emails.admin.Trade.withdraw-repo-request-offer', compact("CTRequest"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');     
            
        }
        else if ($this->typeofmail === 'tradeCancelled') {
            $passed = $this->data;
            $CTRequest = $passed['ct_Request'];
            return $this->view('emails.admin.Trade.cancel-trade-deposit', compact("CTRequest"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');                 
        }
        else if ($this->typeofmail === 'offerEditted') {
            $passed = $this->data;
            $CTRequest = $passed['ct_Request'];
            return $this->view('emails.admin.Trade.repo-offer-edited', compact("CTRequest"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');           
        }
        else if ($this->typeofmail === 'tradeProcessingStarted') {
            $passed = $this->data;
            $CTRequest = $passed['ct_Request'];
            return $this->view('emails.admin.Trade.trade-processing-started', compact("CTRequest"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');           
        }
        else if ($this->typeofmail === 'counterOfferRecieved') {
            $passed = $this->data;
            $offerDetails = $passed['offerDetails'];
            return $this->view('emails.admin.Trade.counter-offer-received', compact("offerDetails"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');           
        }
        else if ($this->typeofmail === 'receivedOffers') {
            $passed = $this->data;
            $CTRequest = $passed['ct_Request'];
            return $this->view('emails.admin.Trade.new-request-offers', compact("CTRequest"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');           
        }
        
        else if ($this->typeofmail === 'accessRequesShared') {
            $passed = $this->data;
            $access_request = $passed['access_request'];
            return $this->view('emails.admin.accounts.access_request', compact("access_request"))
                ->subject($this->roleprefix.setEmailHeader($passed['subject']))
                ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');           
        }
    
           
    }
}

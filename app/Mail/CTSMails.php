<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CTSMails extends Mailable implements ShouldQueue
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
            case "withdrawRequest":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.withdraw-repo-request', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "newRequest":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.request-active', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "offerReceived":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.new-offer-received', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "counterOfferSent":
                $offerDetails = $passed['offerDetails'];
                return $this->view('emails.CT.counter-offer-sent', compact("offerDetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "counterOfferRecieved":
                $offerDetails = $passed['offerDetails'];
                return $this->view('emails.CT.counter-offer-received', compact("offerDetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "counterOfferAccepted":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.counter-offer-accepted', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "counterOfferDeclined":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.counter-offer-declined', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "offfersSelected":
                $selectedOffers = $passed['selectedOffers'];
                return $this->view('emails.CT.offers-selected', compact("selectedOffers"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "offerWithdrawn":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.withdraw-repo-request-offer', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "receivedOffers":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.new-request-offers', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "tradeCancelled":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.cancel-trade-deposit', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "offerEditted":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.repo-offer-edited', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "tradeProcessingStarted":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CT.trade-processing-started', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "48hrMaturingRepos":
                $depositDetails['depositDetails_fourty_eight'] = $passed['depositDetails_fourty_eight'];
                $depositDetails['depositDetails_seven'] = $passed['depositDetails_seven'];
                // return $depositDetails;
                return $this->view('emails.CG.maturing-repos', compact("depositDetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "newRequestFromCG":

                $CGRequest['requests'] = $passed['cg_Request'];  
                $CGRequest['sender'] = $passed['sender'];                
                return $this->view('emails.CT.new-request-invitation', compact("CGRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');


            default:
                break;
        }
    }
}

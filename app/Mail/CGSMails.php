<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CGSMails extends Mailable implements ShouldQueue
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
                return $this->view('emails.CG.withdraw-repo-request', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "newRequest":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CG.new-request-invitation', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "sendOffers":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CG.request-offer-sent', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "counterOfferRecieved":
                $offerDetails = $passed['offerDetails'];
                return $this->view('emails.CG.counter-offer-received', compact("offerDetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "counterOfferSent":
                $offerDetails = $passed['offerDetails'];
                return $this->view('emails.CG.counter-offer-sent', compact("offerDetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "counterOfferDeclined":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CG.counter-offer-declined', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "offfersSelected":
                $selectedOffers = $passed['selectedOffers'];
                return $this->view('emails.CG.offers-selected', compact("selectedOffers"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            case "offerWithdrawn":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CG.withdraw-repo-request-offer', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            case "offerEditted":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CG.repo-offer-edited', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "tradeProcessingStarted":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CG.trade-processing-started', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            case "counterOfferAccepted":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CG.counter-offer-accepted', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            case "tradeCancelled":
                $CTRequest = $passed['ct_Request'];
                return $this->view('emails.CG.cancel-trade-deposit', compact("CTRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            case "updateDummyBasketOrBi":
                $depositDetails['depositDetails'] = $passed['depositDetails'];
                $depositDetails['fourtyeighty']  = $passed['fourtyeightyhrsalert'];
                return $this->view('emails.CG.update-dummy-coll-id', compact("depositDetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case "48hrMaturingRepos":
                $depositDetails['depositDetails_fourty_eight'] = $passed['depositDetails_fourty_eight'];
                $depositDetails['depositDetails_seven'] = $passed['depositDetails_seven'];
                return $this->view('emails.CG.maturing-repos-fourtyeight-hrs', compact("depositDetails"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');

            case "newRequestToCT":
                $CGRequest = $passed['cg_Request'];
                return $this->view('emails.CG.request-active', compact("CGRequest"))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            default:
                break;
        }
    }
}

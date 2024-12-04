<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ConsolidatedMails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $typeofmail = "";
    public $data = [];
    public function __construct($data, $typeofmail)
    {
        $this->data = $data;
        $this->typeofmail = $typeofmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->typeofmail) {
            case 'no_active_campaigns':
                $passed = $this->data;
                $email = $passed['email'];
                $user_id = $passed['user_id'];
                return $this->view('emails.consolidated.no-active-campaigns',compact('email','user_id'))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                break;
            case 'expiring_gic':
                $passed = $this->data;
                $expiring_gic = $this->data['expiring_gic'];
                return $this->view('emails.consolidated.maturity-gic', compact('expiring_gic'))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
            case 'new_offer_received':
                $passed = $this->data;
                $email = $passed['email'];
                $user_id = $passed['user_id'];
                $offers = $this->data['data'];
                return $this->view('emails.consolidated.offer-received', compact('offers','email','user_id'))
                    ->subject(setEmailHeader($passed['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                break;
            case "campaigns_marketing":
                $email = $this->data['email'];
                $user_id = $this->data['user_id'];
                $products = $this->data['products'];
                return $this->view('emails.depositor.campaigns.discover-gics', compact("products","email","user_id"))
                    ->subject(setEmailHeader($this->data['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                break;

            case 'daily_campaign_summery':
                $email = $this->data[0]['email'];
                $user_id = $this->data[0]['user_id'];
                $active = $this->data[0]['active'];
                $scheduled = $this->data[0]['scheduled'];
                $expire = $this->data[0]['expire'];
                $from = $this->data['from'];
                return $this->view('emails.consolidated.daily-summery', compact("active", "scheduled","expire","email","user_id","from"))
                    ->subject(setEmailHeader($this->data['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                break;
            case 'depositor_offers_sent':
                $email = $this->data['email'];
                $user_id = $this->data['user_id'];
                $products = $this->data['data'];
                return $this->view('emails.consolidated.depositor.rates-received', compact("products",'email','user_id'))
                    ->subject(setEmailHeader($this->data['subject']))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                break;
            default:
                # code...
                break;
        }
    }
}

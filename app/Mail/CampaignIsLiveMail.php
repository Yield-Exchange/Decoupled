<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignIsLiveMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data=[];
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return 0;
        $passed=$this->data;
        $campaigndetails= $passed['campaigndetails'];
        return $this->view('emails.bank.campaign.now-live',compact("campaigndetails"))
            ->subject(setEmailHeader("Campaign Going Live."))
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
 
    }
}

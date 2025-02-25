<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class CounterOfferApprovalMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        $data['subject']=setEmailHeader($data['subject']);
        $message_=$data['message'];$show_register=false;$other_buttons=[];$logo_position="top";
        $header=isset($data['header']) ? $data['header'] : "";$subject=$data['subject'];$user_type=$data['user_type'];
        $show_login=true;
        $action = $data['action'];
        return $this->view('emails.bank.counter-offer-approval',compact('message_','show_login','show_register','other_buttons','logo_position','header','subject','user_type','action'))
            ->subject($data['subject'])
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }

}
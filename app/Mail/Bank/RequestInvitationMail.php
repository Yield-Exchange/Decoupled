<?php

namespace App\Mail\Bank;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class RequestInvitationMail extends Mailable implements ShouldQueue
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
        $message_=$data['message'];$show_login=true;$show_register=false;$other_buttons=[];$logo_position="top";
        $header=isset($data['header']) ? $data['header'] : "";$subject=$data['subject'];$user_type=$data['user_type'];
        return $this->view('emails.bank.deposit-request-invitation',compact('message_','show_login','show_register','other_buttons','logo_position','header','subject','user_type'))
            ->subject($data['subject'])
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }

}
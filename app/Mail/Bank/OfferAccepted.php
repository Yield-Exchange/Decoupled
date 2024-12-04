<?php

namespace App\Mail\Bank;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferAccepted extends Mailable
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

        $data['subject'] = setEmailHeader($data['subject']);

        $message_=$data['message'];
        $show_login=true;
        $show_register=false;
        $other_buttons=[];
        $logo_position="top";
        $header=isset($data['header']) ? $data['header'] : "";
        $subject=$data['subject'];
        $user_type='bank';
        return $this->view('emails.bank.offer-accepted',compact('message_','show_login','show_register','other_buttons','logo_position','header','subject','user_type'))
            ->subject($data['subject'])
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }
}

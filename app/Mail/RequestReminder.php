<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
       // $message_=$data['message'];
        $show_login=true;
        $show_register=false;
        $other_buttons=[];
        $logo_position="top";
        $header=isset($data['header']) ? $data['header'] : "";
        $subject=$data['subject'];
        $user_type=$data['user_type'];
        $amount = $data['amount'];
        $ref = $data['ref'];
        $date = $data['date'];

        return $this->view('emails.depositor.request-reminder',compact('show_login','show_register','other_buttons','logo_position','header','subject','user_type','amount','date','ref'))
            ->subject($data['subject'])
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }
}

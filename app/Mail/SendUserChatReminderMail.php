<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendUserChatReminderMail extends Mailable
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
        $data['subject']=setEmailHeader("You have a new message");
        $message_=$data['message'];
        $show_login=true;
        $show_register=false;
        $other_buttons=[];
        $logo_position="top";
        $header=isset($data['header']) ? $data['header'] : "";
        $subject=$data['subject'];
        $user_type=$data['type']; //Bank or deposit
        return $this->view('emails.home.chat-reminder',compact('message_','show_login','show_register','other_buttons','logo_position','header','subject','user_type'))
            ->subject($data['subject'])
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }
}

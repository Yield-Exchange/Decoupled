<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class NewWelcomeMail extends Mailable
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
        $data['subject']=setEmailHeader("Your account is currently being reviewed.");

        $show_login=true;$show_register=false;$other_buttons=[];$logo_position="top";
        $password=$data['password'];
        $header=isset($data['header']) ? $data['header'] : "";$subject=$data['subject'];$user_type=$data['user_type'];
        return $this->view('emails.auth.new-user-welcome',compact('password','show_login','show_register','other_buttons','logo_position','header','subject','user_type'))
            ->subject($data['subject'])
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }

}
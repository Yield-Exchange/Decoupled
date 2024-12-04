<?php

namespace App\Mail;

use App\CustomEncoder;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
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
        $data['subject']=setEmailHeader("Verify your email");
        $code=$data['code'];
        $links = url('/account_verification/'.CustomEncoder::urlValueEncrypt($code));
        $show_login=false;$show_register=false;$other_buttons=[['linkName'=>'Verify your email','link'=>$links]];$logo_position="top";
        $header=isset($data['header']) ? $data['header'] : "";$subject=$data['subject'];$user_type=$data['user_type'];
        return $this->view('emails.auth.verify-email',compact('show_login','show_register','other_buttons','logo_position','header','subject','user_type'))
            ->subject($data['subject'])
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }

}
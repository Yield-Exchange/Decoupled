<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RegistrationMail extends Mailable implements ShouldQueue
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
        $data['subject']=setEmailHeader("Welcome to Yield Exchange");
        // $data['header'] = "Your account has been created";
        $count = $data['count'];
        $user_type = $data['user_type'];
        //$messager = isset($this->data['message']) ? $this->data['message'] : null;
        $show_login=false;$show_register=false;$other_buttons=[['linkName'=>'Visit FAQ','link'=>'https://yieldexchange.tawk.help/']];$logo_position="top";
       // $header = isset($data['header']) ? $data['header'] : "";$subject=$data['subject'];$user_type=$data['user_type'];
        return $this->view('emails.auth.signup',compact('show_login','show_register','other_buttons','logo_position','user_type','count'))
            ->subject(setEmailHeader("Welcome to Yield Exchange"))
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }

}
<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class AdminMail extends Mailable implements ShouldQueue
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
        $message_=$data['message'];$show_login=false;$show_register=false;$other_buttons=[];$logo_position="top";
        $header=(auth()->check() && auth()->user()->is_test ? '(Test) ' : '');
        $header.=isset($data['header']) ? $data['header'] : "";
        $subject=' ADMIN - '.setEmailHeader($data['subject']);
        $user_type="admin";
        $payload = isset($data['payload']) ? json_encode($data['payload']) :'';
        return $this->view('emails.admin.index',compact('message_','show_login','show_register','other_buttons','logo_position','header','subject','user_type','payload'))
            ->subject($subject)
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }

}
<?php

namespace App\Mail\Admin;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class AdminUserActionMail extends Mailable implements ShouldQueue
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
        $message_=$data['message'];
        $subject=setEmailHeader(isset($data['subject']) ? $data['subject'] : '');
        $display_message  = $data['header'];
        return $this->view('emails.admin.onboarding.registration-status',compact('display_message'))
             ->subject($subject)
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }

}
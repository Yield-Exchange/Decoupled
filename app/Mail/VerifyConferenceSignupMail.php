<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\CustomEncoder;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyConferenceSignupMail extends Mailable
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
        $data['subject'] = setEmailHeader("Account Approved");
        $links = url('/conference-authorize/' . CustomEncoder::urlValueEncrypt($data['code']));
        $code = $data['code'];

        return $this->view('emails.auth.conference-validate-account', compact('links', 'code'));
    }
}

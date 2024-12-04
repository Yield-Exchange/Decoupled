<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JoinWaitList extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $typeofmail = "";
    public function __construct($data, $typeofmail)
    {
        $this->data =$data;
        $this->typeofmail = $typeofmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->typeofmail) {
            case 'KEEP_ME_INFORMED':
                return $this->view('emails.auth.newSignUp.keep-me-informed')
                    ->subject(setEmailHeader('Joining Waiting List'))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                break;
            case 'NOT_IN_TIMEZONE':
                return $this->view('emails.auth.newSignUp.timezone')
                    ->subject(setEmailHeader('Joining Waiting List'))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                break;
            case 'PERSONAL_DEPOSITOR':
                return $this->view('emails.auth.newSignUp.join-waiting-list')
                    ->subject(setEmailHeader('Joining Waiting List'))
                    ->replyTo('info@yieldexchange.ca', 'Yield Exchange Inc');
                break;

            default:
                # code...
                break;
        }
    }
}

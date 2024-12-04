<?php

namespace App\Mail\Depositor;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class WithdrawDeposit extends Mailable implements ShouldQueue
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
        $data['subject']=setEmailHeader("Contract Withdrawn");
        $message_=$data['message'];$show_login=true;$show_register=false;$other_buttons=[];$logo_position="top";
        $user_type = $data['user_type'];
        // $header=isset($data['header']) ? $data['header'] : "";$subject=$data['subject'];$user_type='Bank';
        return $this->view('emails.depositor.withdraw-deposit',compact('message_','show_login','show_register','other_buttons','logo_position','user_type'))
            ->subject($data['subject'])
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }

}
<?php

namespace App\Mail;

use App\Models\Offer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class NewCounterOfferMail extends Mailable implements ShouldQueue
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
        $data['subject']=$data['user_type'] == 'Admin' ? "ADMIN-".setEmailHeader($data['subject']) : setEmailHeader($data['subject']) ;
        $message_=$data['message'];$show_register=false;$other_buttons=[];$logo_position="top";
        $header=isset($data['header']) ? $data['header'] : "";$subject=$data['subject'];$user_type=$data['user_type'];
        $counter_offer = $data['counter_offer'];
        $show_login=$data['show_login'];
        $timezoner=$data['timezone'];
        $original_offer = Offer::find($counter_offer->offer_id);
        return $this->view('emails.new-counter-offer',compact('message_','show_login','show_register','other_buttons','logo_position','header','subject','user_type','counter_offer','original_offer','timezoner'))
            ->subject($data['subject'])
            ->replyTo('info@yieldexchange.ca','Yield Exchange Inc');
    }

}
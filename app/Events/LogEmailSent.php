<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;

class LogEmailSent
{
    public function handle($event)
    {
        logEmailSent([
            'to'=>$event->message->getTo(),
            'message'=>$event->message->getBody(),
            'subject'=>$event->message->getSubject()
        ]);
    }
}
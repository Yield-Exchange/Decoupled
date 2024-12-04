<?php

namespace App\Events;

use App\Models\CTTradeRequestCGOffer;
use App\Models\CTTradeRequestOfferChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CTTradeRequestOfferChatEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $ctDeposit;

    public  $chat;
    /**
     * Create a new event instance.
     *
     * @return void
     */
public function __construct(CTTradeRequestCGOffer $ctDeposit, CTTradeRequestOfferChat $chat)
    {
        $this->ctDeposit = $ctDeposit;
        $this->chat = $chat;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('CT_Trade_Request_offer_chat.'.$this->ctDeposit->offer_reference_no);
    }
    public function broadcastWith()
    {
        return [
            'chat'=>json_encode($this->chat)
        ];
    }
}

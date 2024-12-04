<?php

namespace App\Events;

use App\Constants;
use App\Models\CTTradeRequestOfferDeposit;
use App\Models\CTTradeRequestOfferDepositChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Log;

class CTTradeRequestOfferDepositChatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $ctDeposit;

    public  $chat;
    /**
     * Create a new event instance.
     *
     * @return void
     */
public function __construct(CTTradeRequestOfferDeposit $ctDeposit, CTTradeRequestOfferDepositChat $chat)
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

        Log::info('CTRequestsDepositsmessages Event broadcasted successfully. 12');
        return new PrivateChannel('CT_Trade_Request_Deposit_chat.'.$this->ctDeposit->deposit_reference_no);
        // Log::info('CTRequestsDepositsmessages Event broadcasted successfully. 13');

    }
    public function broadcastWith()
    {
        return [
            'chat'=>json_encode($this->chat)
        ];
    }
}

<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\Organization;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DepositChatMessagesCountEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $to;

    /**
     * Create a new event instance.
     *
     * @param Organization $to
     */
    public function __construct(Organization $to)
    {
        $this->to=$to;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('DepositChatCountMessages.'.$this->to->id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['count'=>Chat::where('sent_to_organization_id',$this->to->id)->whereHas('deposit',function ($query){
            $query->where('status','=','PENDING_DEPOSIT');
        })->where('status','NEW')->count()];
    }
}

<?php

namespace App\Events;

use App\Constants;
use App\Models\Chat;
use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DepositChatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $deposit;

    public  $chat;

    /**
     * Create a new event instance.
     *
     * @param Deposit $deposit
     * @param Chat $chat
     */
    public function __construct(Deposit $deposit, Chat $chat)
    {
        $this->deposit = $deposit;
        $this->chat = $chat;
        broadcast(new DepositChatMessagesCountEvent($chat->to))->toOthers();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Deposit_chat.'.$this->deposit->reference_no);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id'=>$this->chat->id,
            'text'=>$this->chat->message,
            'created_at'=>changeDateFromUTCtoLocal($this->chat->created_at,Constants::DATE_TIME_FORMAT_NO_SECONDS),
            'sent_by'=>$this->chat->by,
            'sent_to'=>$this->chat->to,
            'is_mine'=>$this->chat->by->organization->id!=\auth()->user()->organization->id, // sending to other person,
            'file'=>$this->chat ? url('uploads/'.$this->chat->file) : NULL,
            'time_sent'=>Carbon::parse($this->chat->created_at)->diffForHumans()
        ];
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\SendUserChatReminderMail;
use App\Models\Chat;
use App\Models\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ChatReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminder to Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *  !QA2ws#ED4rf%TG6yh&UJ8ik(OL0p
     * @return int
     */
    public function handle()
    {
        $thiryMinuteBefore = now()->subMinutes(30);
        $thirtyMinuteAfter = now()->addMinutes(30);
        $chats = Chat::where('status', "NEW")->whereBetween('created_at', [$thiryMinuteBefore, $thirtyMinuteAfter])->get();
        
        foreach ($chats as $chat) {
            
            $message = "<p>You have a messsage from ". $chat->by->name;
            $message .= "</p><p>Message : ". $chat->message ."</p>";
            Mail::to($chat->toUser->email)->queue(new SendUserChatReminderMail([
                'message' => $message,
                'type' =>  $chat->to->type,
            ]));
        }

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\RequestReminder as RequestReminderMail;
use App\Models\DepositRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RequestReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mail to remind depositor';

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
     *
     * @return int
     */
    public function handle()
    {
        // $requests = DepositRequest::whereBetween('date_of_deposit', [now(), now()->addDay()])->whereHas('offers')->get();
        // foreach ($requests as $key => $request) {
        //     $depositors = $request->organization->notifiableUsersEmails(false);
        //     foreach ($depositors as $depositor) {
        //         $timezone = $depositor ? $depositor->formatted_timezone : 'utc';
        //         Mail::to($depositor->email)->queue(new RequestReminderMail([
        //             'subject' => "Your request expires soon",
        //             'header' => "Your request " . $request->reference_no,
        //             'amount'=> $request->currency . " " . number_format($request->amount, 2),
        //             'ref'=> "Request ". $request->reference_no,
        //             'date' => changeDateFromUTCtoLocal($request->closing_date_time,null,null,null,$depositor)." ".$timezone,
        //             'user_type' => "Depositor"
        //         ]));
        //     }   // don't send to depositor
        // }

        $requests = DepositRequest::whereBetween('closing_date_time', [now(), now()->addDay()])->get();
        foreach ($requests as $key => $request) {
            foreach ($request->invited as $key => $user) {
                if ($user->invitation_status == "INVITED") {

                    $users = $user->bank->notifiableUsersEmails(false);
                    foreach ($users as $user_){
                        $timezone = $user_ ? $user_->formatted_timezone : 'utc';
                        Mail::to($user_->email)->queue(new RequestReminderMail([
                            'subject' => "Request Closes soon",
                            'header' => "Request " . $request->reference_no,
                            'amount'=> $request->currency . " " . number_format($request->amount, 2),
                            'ref'=> "Request ". $request->reference_no,
                            'date' => changeDateFromUTCtoLocal($request->closing_date_time,null,null,null,$user_)." ".$timezone,
                            'user_type' => "Bank"
                        ]));
                    }

                }
            }
           
        }
        return 0;
    }
}

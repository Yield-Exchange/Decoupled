<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\Chat;
use App\Models\Deposit;
use App\Models\DepositRequest;
use App\Models\InvitedBank;
use App\Models\LoginActivity;
use App\Models\Offer;
use App\Models\Organization;
use App\Models\UserNotification;
use App\User;
use Illuminate\Console\Command;

class TrackIsTestDataForOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track-test-data:old-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds is test attribute to old data before the its introduction to the system';

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
        $this->info("STARTED");

        //organizations table
        Organization::where('is_test',0)
            ->chunkById(100,function ($organizations){
                foreach ($organizations as $organization) {
                    if ($organization->admin && $organization->admin->is_test == 1) {
                        $organization->is_test = 1;
                        $organization->save();
                        $this->info('updated organizations > '.$organization->id);
                    }
                }
        });

        //activity_logs
        ActivityLog::where('is_test',0)
            ->chunkById(100,function ($activity_logs){
                foreach ($activity_logs as $activity_log) {
                    if ($activity_log->user && $activity_log->user->is_test == 1) {
                        $activity_log->is_test = 1;
                        $activity_log->save();
                        $this->info('updated activity_log > '.$activity_log->id);
                    }
                }
        });

        //chats
        Chat::where('is_test',0)
            ->chunkById(100,function ($chats){
                foreach ($chats as $chat) {
                    if ($chat->by && $chat->by->is_test == 1) {
                        $chat->is_test = 1;
                        $chat->save();
                        $this->info('updated chat > '.$chat->id);
                    }
                }
            });


        //depositor_requests
        DepositRequest::where('is_test',0)
            ->chunkById(100,function ($data){
                foreach ($data as $datum) {
                    if ($datum->user && $datum->user->is_test == 1) {
                        $datum->is_test = 1;
                        $datum->save();
                        $this->info('updated depositor_requests > '.$datum->id);
                    }
                }
            });

        //depositor_requests_archive
        \DB::table('depositor_requests_archive')->where('is_test',0)
            ->chunkById(100,function ($data){
                foreach ($data as $datum) {
                    $user = User::find($datum->user_id);
                    if ($user && $user->is_test == 1) {
                        \DB::table('depositor_requests_archive')
                            ->where('id',$datum->id)
                            ->update([
                            'is_test'=>1
                        ]);
                        $this->info('updated depositor_requests_archive > ' . $datum->id);
                    }
                }
            });

        //deposits
        Deposit::where('is_test',0)
            ->chunkById(100,function ($data){
                foreach ($data as $datum) {
                    try {
                        if (!empty($datum->offer->invited->depositRequest->user)
                            && $datum->offer->invited->depositRequest->user->is_test == 1) {
                            $datum->is_test = 1;
                            $datum->save();
                            $this->info('updated deposits > ' . $datum->id);
                        }
                    }catch (\Exception $exception){
                        $this->error('deposits > error > '.$exception->getMessage());
                    }
                }
            });

        //deposits_archive
        \DB::table('deposits_archive')->where('is_test',0)
            ->chunkById(100,function ($data){
                foreach ($data as $datum) {
                    $offer = Offer::find($datum->offer_id);
                    if ($offer) {
                        try {
                            if (!empty($offer->invited->bank)
                                && $offer->invited->bank->is_test == 1) {
                                \DB::table('deposits_archive')
                                    ->where('id',$datum->id)
                                    ->update([
                                        'is_test'=>1
                                    ]);
                                $this->info('updated deposits_archive > ' . $datum->id);
                            }
                        }catch (\Exception $exception){
                            $this->error('deposits_archive > error > '.$exception->getMessage());
                        }
                    }
                }
            });

        //offers
        Offer::where('is_test',0)
            ->chunkById(100,function ($data){
                foreach ($data as $datum) {
                    try {
                        if (!empty($datum->invited->bank)
                            && $datum->invited->bank->is_test == 1) {
                            $datum->is_test = 1;
                            $datum->save();
                            $this->info('updated offers > ' . $datum->id);
                        }
                    }catch (\Exception $exception){
                        $this->error('offers > error > '.$exception->getMessage());
                    }
                }
            });

        //offers_archives
        \DB::table('offers_archives')->where('is_test',0)
            ->chunkById(100,function ($data){
                foreach ($data as $datum) {
                    $invited = InvitedBank::find($datum->invitation_id);
                    if ($invited) {
                        try {
                            if (!empty($invited->bank)
                                && $invited->bank->is_test == 1) {

                                \DB::table('offers_archives')
                                    ->where('id',$datum->id)
                                    ->update([
                                        'is_test'=>1
                                    ]);
                                $this->info('updated offers_archives > ' . $datum->id);
                            }
                        }catch (\Exception $exception){
                            $this->error('offers_archives > error > '.$exception->getMessage());
                        }
                    }

                }
            });

        //invited
        InvitedBank::where('is_test',0)
            ->chunkById(100,function ($data){
                foreach ($data as $datum) {
                    $deposit_request = DepositRequest::find($datum->depositor_request_id);
                    try {
                        if (!empty($deposit_request->user)
                            && $deposit_request->user->is_test == 1) {
                            $datum->is_test = 1;
                            $datum->save();
                            $this->info('updated invited > ' . $datum->id);
                        }
                    }catch (\Exception $exception){
                        $this->error('invited > error > '.$exception->getMessage());
                    }
                }
            });

        //notifications
        UserNotification::where('is_test',0)
            ->chunkById(100,function ($notifications){
                foreach ($notifications as $notification) {
                    if ($notification->from && $notification->from->is_test == 1) {
                        $notification->is_test = 1;
                        $notification->save();
                        $this->info('updated notifications > '.$notification->id);
                    }
                }
            });

        //login_activities
        LoginActivity::where('is_test',0)
            ->chunkById(100,function ($login_activities){
                foreach ($login_activities as $login_activity) {
                    if ($login_activity->user && $login_activity->user->is_test == 1) {
                        $login_activity->is_test = 1;
                        $login_activity->save();
                        $this->info('updated login_activities > '.$login_activity->id);
                    }
                }
            });

        $this->info("COMPLETED");

        return 0;
    }
}

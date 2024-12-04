<?php

namespace App\Console\Commands;

use App\Mail\AdminMails;
use App\Models\Organization;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfNewUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new-users:remind-admins';
    // protected $signature = 'newusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a reminder/notifications to the admins of new users';

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
        Organization::where('status','PENDING')->orderBy("id","DESC")->chunk(100,function ($organisations){
            $pendingorganizations=[];
            foreach ($organisations as $organization) {
                array_push($pendingorganizations, $organization->name);                
            }
            Mail::to(getAdminEmails())->send(new AdminMails(['pending_accounts'=>$pendingorganizations,'subject'=>'Pending Accounts'],"pending_accounts"));
        });

        
    }
}

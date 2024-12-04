<?php

namespace App\Console\Commands;

use App\Mail\AdminMails;
use App\Models\Organization;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AbandonedAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abandoned:accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email for all abondoned accounts at the end of the day to admin';

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
        $organizations = Organization::where('status', 'PENDING')
            ->where('type', 'DEPOSITOR')
            ->whereNull('is_waiting')
            ->where('accepted_terms_and_conditions', 0)
            ->get();

        $data = [];
        foreach ($organizations as $organization) {
            $orga_admin = User::find($organization->admin_user_id);
            $stage = '';

            if (is_null($organization->demographicData)) {
                $stage = 'Organization Details';
            } elseif (optional($orga_admin->demographicData)->job_title == "") {
                $stage = "Personal Details";
            } elseif ($orga_admin->requires_password_update == 1) {
                $stage = "Set Password";
            } else {
                $stage = "Accept Terms And Conditions";
            }

            $data[] = ['name' => $organization->name, 'stage' => $stage];
        }


         $this->info(json_encode($data));

        if (count($data) > 0) {
            Mail::to(getAdminEmails())->queue(new AdminMails([
                'subject' => 'Abandoned Accounts',
                'organization_data' => $data
            ], 'abandoned_consolidated_email'));
        }

        return 0;
    }
}

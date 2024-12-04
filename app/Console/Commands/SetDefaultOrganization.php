<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserOrganization;
use Illuminate\Console\Command;

class SetDefaultOrganization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settingDefaultOrganizations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                $defaultOrg = UserOrganization::where('user_id', $user->id)->orderBy('id', 'ASC')->first();
                if ($defaultOrg) {
                    $defaultOrg->update(['is_default' => 1]);
                }
            }
        });
    }
}

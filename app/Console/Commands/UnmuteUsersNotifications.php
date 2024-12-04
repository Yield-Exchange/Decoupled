<?php

namespace App\Console\Commands;

use App\Models\UserPreference;
use Illuminate\Console\Command;

class UnmuteUsersNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unmute-notifications:for-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Un mutes users notifications';

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
        UserPreference::where('preference_id',1)->update([
            'value'=>0
        ]);
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateUserRolesOrganizationId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-roles:organization-id-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates User Roles Table Organization ID';

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
        $this->info("Started");
        $roles = DB::table('role_user')->whereNull('organization_id')
           ->get();

        if ($roles) {
                foreach ($roles as $role) {
                    $user = User::find($role->user_id);
                    if ($user && $user->organization && $user->organization->id > 0) {
                        DB::table('role_user')->where('user_id', $user->id)
                            ->update([
                                'organization_id' => $user->organization->id
                            ]);
                        $this->info("Updated user: ".$user->id);
                    }
                }
        }
        $this->info("Completed");
        return 0;
    }
}

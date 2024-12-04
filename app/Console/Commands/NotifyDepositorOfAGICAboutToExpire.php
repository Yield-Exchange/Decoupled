<?php

namespace App\Console\Commands;

use App\Mail\ConsolidatedMails;
use App\Mail\DepositorMails;
use App\Models\Deposit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class NotifyDepositorOfAGICAboutToExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyuserofexpiringcampaigns';

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
        $deposits_builder = Deposit::with(['offer'])
            ->whereIn('status', ['ACTIVE'])
            ->whereNotNull('maturity_date');

        $days  = [14,7,3,1];
        foreach ($days as $day ) {
            $this->sendEmailForDays($deposits_builder, $day);
        }
    }

    function sendEmailForDays($deposits, $days)
    {
        $groupedDeposits = $deposits->whereRaw('DATEDIFF(DATE(maturity_date), DATE(NOW())) = '.$days)
            ->get()
            ->groupBy(function ($deposit) {
                return $deposit->offer->invited->depositRequest->organization_id;
            });

        foreach ($groupedDeposits as $organizationId => $value) {

            $emails = $value[0]->offer->invited->depositRequest->organization->notifiableUsersEmails(true);
            $subject = '';

            switch ($days) {
                case 1:
                    $subject = 'Maturing GIC - 1 day left';
                    break;
                case 3:
                    $subject = 'Maturing GIC - 3 days left';
                    break;
                case 7:
                    $subject = 'Maturing GIC - 7 days left';
                    break;
                case 14:
                    $subject = 'Maturing GIC - 14 days left';
                    break;
            }

            Mail::to($emails)->queue(new ConsolidatedMails([
                'expiring_gic' => $value,
                'subject' => $subject
            ], "expiring_gic"));
        }
    }
}

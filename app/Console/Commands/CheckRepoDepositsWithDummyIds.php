<?php

namespace App\Console\Commands;

use App\Mail\CGSMails;
use App\Models\CTTradeRequestInvitedCG;
use App\Models\CTTradeRequestOfferDeposit;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CheckRepoDepositsWithDummyIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckRepoDepositsWithDummyIds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks repo deposits with dummy IDs and sends reminder emails.';

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
        $currentDateTime = Carbon::now('UTC');
        $next48Hours = $currentDateTime->copy()->addHours(48);
        $orgs = CTTradeRequestInvitedCG::with('organization')
            ->whereHas('offers', function ($query) use ($currentDateTime) {
                $query->whereHas('CTdeposit', function ($query2) use ($currentDateTime) {
                    $query2->where('deposit_status', 'PENDING_DEPOSIT')
                           ->where('trade_date', '>', $currentDateTime->format('Y-m-d H:i:s'));
                });
            })
            ->distinct('organization_id')
            ->groupBy('organization_id')
            ->get();

        foreach ($orgs as $org) {
            $tosentall = CTTradeRequestOfferDeposit::with('CGOffer')
                ->where('deposit_status', 'PENDING_DEPOSIT')
                ->where('trade_date', '>', $currentDateTime->format('Y-m-d H:i:s'))
                ->whereHas('CGOffer', function ($query) use ($org) {
                    $query->whereHas('invitee', function ($query2) use ($org) {
                        $query2->where('organization_id', $org->organization_id);
                    });
                })
                ->get();
                $tosent48hrs = CTTradeRequestOfferDeposit::with('CGOffer')
                ->where('deposit_status', 'PENDING_DEPOSIT')
                ->whereBetween('trade_date', [$currentDateTime->format('Y-m-d H:i:s'), $next48Hours->format('Y-m-d H:i:s')])
                ->whereHas('CGOffer', function ($query) use ($org) {
                    $query->whereHas('invitee', function ($query2) use ($org) {
                        $query2->where('organization_id', $org->organization_id);
                    });
                })
                ->get();

            try {
                if(sizeof($tosent48hrs)>0){
                    Mail::to($org->organization->notifiableUsersEmails())
                    ->queue(new CGSMails([
                        'subject' => 'URGENT - Update Collaterals For Almost Expiring Repos.',
                        'depositDetails' =>$tosent48hrs ,
                        'fourtyeightyhrsalert'=>true,
                        'user_type' => 'CG',
                    ], 'updateDummyBasketOrBi'));
                }
                if(sizeof($tosentall)>0){
                    Mail::to($org->organization->notifiableUsersEmails())
                    ->queue(new CGSMails([
                        'subject' => 'Reminder - Update Dummy Collaterals',
                        'depositDetails' => $tosentall,
                        'fourtyeightyhrsalert'=>false,
                        'user_type' => 'CG',
                    ], 'updateDummyBasketOrBi'));
                }
               
            } catch (\Exception $e) {
                $this->error("Failed to send email to organization ID {$org->organization_id}: " . $e->getMessage());
            }
        }

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\DepositorMails;
use App\Models\Deposit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use DB;

class DepositorPendingDeposits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifydepositorsoftheirpendingdeposits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the expiry for deposits/contracts';

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
        Deposit::join("offers", "offers.id", "=", "deposits.offer_id")
            ->join("invited", "invited.id", "=", "offers.invitation_id")
            ->join("depositor_requests", "depositor_requests.id", "=", "invited.depositor_request_id")
            ->where('deposits.is_test', 0)
            ->whereIn('status', ['PENDING_DEPOSIT'])
            ->select(DB::raw("DISTINCT(depositor_requests.organization_id) as org_id"))
            ->chunk(100, function ($organizations) {
                              
                foreach ($organizations as $organization) {
                    // get organization deposits 
                    Deposit::with(['offer'])
                        ->join("offers", "offers.id", "=", "deposits.offer_id")
                        ->join("invited", "invited.id", "=", "offers.invitation_id")
                        ->join("depositor_requests", "depositor_requests.id", "=", "invited.depositor_request_id")
                        ->where('deposits.is_test', 0)
                        ->where('depositor_requests.organization_id', $organization->org_id)
                        ->whereIn('status', ['PENDING_DEPOSIT'])
                        ->select("deposits.*")
                        ->chunkById(100, function ($deposits) {
                            //send mails
                            $shouldsend = false;
                            $pendingdeposits=[];
                            foreach ($deposits as $deposit) {
                            $shouldsend=true;
                            $depositRequest = $deposit->offer->invited->depositRequest;
                            array_push(
                                $pendingdeposits,
                                [
                                    "deposit_id" => $deposit['reference_no'],
                                    "offered_amount" => $depositRequest->currency . " " . $deposit['offered_amount'],
                                    "date_of_deposit" => $depositRequest->date_of_deposit,
                                    "depositor" => $depositRequest->organization->name,
                                    "fi" => $deposit->offer->bank_name
                                ]
                            );  
                            
                        }
                        if ($shouldsend) {
                            Mail::to(getAdminEmails())
                                ->queue(new DepositorMails(
                                    [
                                        'pending_deposits' => $pendingdeposits,
                                        'subject' => setEmailHeader('Pending Deposits')
                                    ],
                                    "pending_deposits"
                                ));
                        }
                            //send mails
                        });
                    // get organization deposits
                }
                
            });
    }
}

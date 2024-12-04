<?php

namespace App\Console\Commands;

use App\Mail\AdminMails;
use App\Models\Deposit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DepositExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiry-check:deposits';

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
        $send = false;
        $pendingdeposits = [];
        Deposit::with(['offer'])->whereIn('status', ['PENDING_DEPOSIT'])
            ->where('is_test', 0) //// this is okay, we don't need to send test to admin
            ->chunkById(100, function ($deposits) use(&$send, &$pendingdeposits) {
                foreach ($deposits as $deposit) {
                    $utc_date_time_now = getUTCTimeNow();
                    if (!empty($deposit["maturity_date"])) {
                        $maturity_date = Carbon::parse($deposit["maturity_date"]);
                        if ($utc_date_time_now->greaterThan($maturity_date)) {
                            $deposit->status = 'MATURED';
                            archiveTable($deposit->id, 'deposits', 0, 'MATURED');
                            $deposit->save();
                        }
                        continue;
                    }
                    if ($deposit['status'] == "PENDING_DEPOSIT") {

                        $rate_held_until = ($deposit->offer->rate_held_until ?? null) ? Carbon::parse($deposit->offer->rate_held_until) : null;
                      
                        if ($utc_date_time_now->greaterThan($rate_held_until)&& $rate_held_until!=null) {
                            if (!$deposit['admins_notified_date'] || getUTCTimeNow()->diffInDays(Carbon::parse($deposit['admins_notified_date'])) >= 1) { //Admins should be notified
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
                                );                                $deposit->admins_notified_date = getUTCTimeNow();
                                archiveTable($deposit->id, 'deposits', 0, 'admins_notified_date');
                                $deposit->save();
                                $send = true;
                            }
                        }
                    }
                }
                if ($send) {
                    Mail::to(getAdminEmails())->send(new AdminMails(['pending_deposits' => $pendingdeposits, 'subject' => setEmailHeader('Incomplete Deposits')], "pending_deposits"));
                }
            });
        return 0;
    }
}

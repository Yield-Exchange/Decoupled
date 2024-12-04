<?php

namespace App\Console\Commands;

use App\Mail\CGSMails;
use App\Models\CTTradeRequestInvitedCG;
use App\Models\CTTradeRequestOfferDeposit;
use App\Models\Organization;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ToCGSExpiringReposNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExpiringReposNotificationsToCGS';

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
        $next7days = $currentDateTime->copy()->addDay(7);

        $orgs = CTTradeRequestOfferDeposit::join("c_t_trade_request_c_g_offers", "c_t_trade_request_c_g_offers.id", "=", "c_t_trade_request_offer_deposits.c_t_trade_request_c_g_offer_id")
            ->join("c_t_trade_request_invited_c_g_s", "c_t_trade_request_invited_c_g_s.id", "=", "c_t_trade_request_c_g_offers.invitation_id")
            ->join("c_t_trade_requests", "c_t_trade_requests.id", "=", "c_t_trade_request_invited_c_g_s.c_t_trade_request_id")
            ->whereBetween('maturity_date', [$currentDateTime->format('Y-m-d H:i:s'), $next7days->format('Y-m-d H:i:s')])
            ->groupBy('c_t_trade_requests.organization_id')
            ->select('c_t_trade_requests.organization_id')
            ->get();

        foreach ($orgs as $org) {
            $tosent48hrs = CTTradeRequestOfferDeposit::with('CGOffer')
            // ->where('deposit_status', 'PENDING_DEPOSIT')
            ->whereBetween('maturity_date', [$currentDateTime->format('Y-m-d H:i:s'), $next48Hours->format('Y-m-d H:i:s')])
            ->whereHas('CGOffer', function ($query) use ($org) {
                $query->whereHas('invitee', function ($query2) use ($org) {
                    $query2->whereHas('ctTradeRequest', function ($query3) use ($org) {
                        $query3->where("c_t_trade_requests.organization_id",$org->organization_id);
                    });                    
                });
            })
            ->get();
          
            $tosent7days = CTTradeRequestOfferDeposit::with('CGOffer')
            // ->where('deposit_status', 'PENDING_DEPOSIT')
            ->whereBetween('maturity_date', [$next48Hours->format('Y-m-d H:i:s'), $next7days->format('Y-m-d H:i:s')])
            ->whereHas('CGOffer', function ($query) use ($org) {
                $query->whereHas('invitee', function ($query2) use ($org) {
                    $query2->whereHas('ctTradeRequest', function ($query3) use ($org) {
                        $query3->where("c_t_trade_requests.organization_id",$org->organization_id);
                    });
                });
            })
            ->get();
            try {

                if(sizeof($tosent48hrs)>0){
                    Mail::to(Organization::where("id",$org->organization_id)->first()->notifiableUsersEmails())
                    ->queue(new CGSMails([
                        'subject' => 'Expiring Repos.',
                        'depositDetails_fourty_eight' =>$tosent48hrs ,
                        'depositDetails_seven' =>$tosent7days ,
                        'fourtyeightyhrsalert'=>true,
                        'user_type' => 'CG',
                    ], '48hrMaturingRepos'));
                }              
               
            } catch (\Exception $e) {
                $this->error("Failed to send email to organization ID {$org->organization_id}: " . $e->getMessage());
            }




        }
    }
}

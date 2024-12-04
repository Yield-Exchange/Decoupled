<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminMail;
use App\Models\AWSFileRouting;
use App\Models\CTTradeRequestOfferDeposit;
use App\Models\CTTradeRequestOfferDepositMT527;
use App\Services\MTService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerateMTFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:mt-files {deposit_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate MT527 files for a specific deposit ';

    protected $deposit_id;

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
    public function handle(MTService $service)
    {
        $this->deposit_id = $this->argument('deposit_id');
        try {
            // Fetch the trade data
            $trade = DB::table('c_t_trade_request_offer_deposits')
                ->join(
                    'c_t_trade_request_c_g_offers',
                    'c_t_trade_request_offer_deposits.c_t_trade_request_c_g_offer_id',
                    '=',
                    'c_t_trade_request_c_g_offers.id'
                )
                ->join(
                    'c_t_trade_request_invited_c_g_s',
                    'c_t_trade_request_c_g_offers.invitation_id',
                    '=',
                    'c_t_trade_request_invited_c_g_s.id'
                )
                ->join(
                    'trade_tri_basket_third_parties',
                    'trade_tri_basket_third_parties.id',
                    '=',
                    'c_t_trade_request_c_g_offers.trade_tri_basket_third_party_id'
                )
                ->join(
                    'c_t_trade_requests',
                    'c_t_trade_request_invited_c_g_s.c_t_trade_request_id',
                    '=',
                    'c_t_trade_requests.id'
                )
                ->join(
                    'organizations as c_g_organization',
                    'c_t_trade_request_invited_c_g_s.organization_id',
                    '=',
                    'c_g_organization.id'
                )
                ->join(
                    'organizations as c_t_organization',
                    'c_t_trade_requests.organization_id',
                    '=',
                    'c_t_organization.id'
                )
                ->join(
                    'a_w_s_file_routings as c_g_routing',
                    'c_g_organization.id',
                    '=',
                    'c_g_routing.organization_id'
                )
                ->join(
                    'a_w_s_file_routings as c_t_routing',
                    'c_t_organization.id',
                    '=',
                    'c_t_routing.organization_id'
                )
                ->where('c_t_trade_request_offer_deposits.id', $this->deposit_id)
                ->select([
                    'c_t_trade_request_offer_deposits.*',
                    'c_g_routing.file_type as c_g_file_type',
                    'c_g_routing.routing_agent as c_g_routing_agent',
                    'c_g_routing.delivery_method as c_g_delivery_method',
                    'c_t_routing.file_type as c_t_file_type',
                    'c_t_routing.routing_agent as c_t_routing_agent',
                    'c_t_routing.delivery_method as c_t_delivery_method',
                    'c_t_trade_request_c_g_offers.fixed_rate as rate',
                    'c_t_trade_request_c_g_offers.settlement_date',
                    'c_g_organization.id as c_g_org_id',
                    'c_g_organization.name as c_g_org_name',
                    'c_t_organization.id as c_t_org_id',
                    'c_t_organization.name as c_t_org_name',
                    'trade_tri_basket_third_parties.basket_id',
                    'c_t_trade_requests.currency',
                    'c_t_trade_request_c_g_offers.invitation_id as c_g_offer_invitation_id',
                    'c_t_trade_request_invited_c_g_s.organization_id as c_g_invite_org_id',
                    'c_t_trade_request_invited_c_g_s.id as c_g_invite_id',
                    'c_t_trade_request_c_g_offers.id as c_g_offer_id',
                    'c_t_trade_requests.organization_id as c_t_inviteorg_id'
                ])
                ->first();

            $this->info(json_encode($trade));


            // Archive the trade data
            $archive = archiveTable($trade->id, 'c_t_trade_request_offer_deposits', 0);

            // Create a new MT527 entry
            CTTradeRequestOfferDepositMT527::create([
                'archive_id' => $archive,
                'mt_527_sender_reference' => $trade->deposit_reference_no

            ]);

            $service->generate527AndPushToAws($trade);

            $this->info('MT527 file generated and pushed to AWS successfully.');
        } catch (\Exception $exception) {
            $this->error('Failed to generate MT527: ' . $exception->getMessage());
            // Log::error('Failed to generate MT527: ', ['error' => $exception->getMessage(), 'deposit_id' => $this->deposit_id]);

            // Send failure email
            $to = explode(',', env('ERROR_EMAILS_TO'));
            Mail::to($to)->queue(new AdminMail([
                'subject' => 'Failed to generate MT527 > ' . get_class($this),
                'message' => $exception->getMessage(),
                'payload' => ['deposit_id' => $this->deposit_id],
            ]));

            return 1;
        }

        return 0;
    }
}

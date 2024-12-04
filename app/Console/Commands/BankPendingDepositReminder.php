<?php

namespace App\Console\Commands;

use App\Mail\BankMails;
use App\Mail\DepositorMails;
use App\Models\Deposit;
use App\Models\DocumentType;
use App\Models\Organization;
use App\Models\OrganizationDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class BankPendingDepositReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pendingdepositorganizations';

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
        $deposit  = Deposit::with('offer')->whereIn('status', ['PENDING_DEPOSIT'])->get();

        $deposit->groupBy('offer.invited.organization.id')->each(function ($groupedDeposits) {
            $count = $groupedDeposits->count();
            $emails = $groupedDeposits->first()->offer->invited->organization->notifiableUsersEmails();
            Mail::to($emails)->queue(new BankMails([
                'subject' => 'Log your deposits in Yield Exchange',
                'count' => $count
            ], 'bank_pending_deposits'));
        });


        $countAndFiles = $deposit->groupBy('offer.invited.depositRequest.organization.id') // Group by depositor
            ->map(function ($groupedDeposits) {
                return $groupedDeposits->groupBy('offer.invited.organization.id') // Group by bank
                    ->map(function ($groupedDepositsByBank) use($groupedDeposits) {
                        $wiretranferid = DocumentType::where('type_name', 'Transfer Details')->value('id');
                        if ($wiretranferid) {
                            $result = [];
                            foreach ($groupedDepositsByBank as $organization_id => $value) {
                                $document = OrganizationDocument::where([
                                    ['organization_id', '=', $organization_id],
                                    ['type_id', '=', $wiretranferid],
                                ])->orderBy('id', 'desc')->select('file_name')->first();
                                $result[$organization_id] = ['count' => $groupedDeposits->count(),'grouped_deposits'=>$groupedDeposits, 'each_bank_file' => $document];
                            }


                            return $result;
                        }
                        return [];
                    });
                
            });


            // foreach($countAndFiles as $depositorId => $filesData){
            //     $depositor = Organization::find($depositorId);
            //     $emails = $depositor->notifiableUsersEmails(true);
            //     $files = json_decode($filesData, true);
            //     $filesWithoutKeys = array_values($files);
            //     $this->info(json_encode($filesWithoutKeys));

            //     Mail::to($emails)->queue(new DepositorMails([
            //         'data'=>$filesWithoutKeys,
            //         'subject'=>'Pending Deposits'   
            //     ],'depositor_pending_deposits'));
            // }   recheck this

        return 0;
    }
}

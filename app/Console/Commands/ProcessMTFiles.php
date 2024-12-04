<?php

namespace App\Console\Commands;

use App\Services\MTService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ProcessMTFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:mtfiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process MT files from S3';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $mtService;

    public function __construct(MTService $mtService)
    {
        parent::__construct();
        $this->mtService = $mtService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $sourceFolder = 'repo/inflow/558/yield_inflow/'.now()->format("n-Y").'/';
            $files = Storage::disk('s3')->files($sourceFolder);
            $this->info(json_encode($files));

            if (empty($files)) {
                $this->info('No files found in the source folder.');
                return 0;
            }

            foreach ($files as $file) {
                $message = Storage::disk('s3')->get($file);
                try {
                    $data_message = $this->mtService->processMessage($message);
                    $processedFolder = 'repo/inflow/558/yield_inflow/processed/' . now()->format("n-Y") . '/';
                    Storage::disk('s3')->move($file, $processedFolder . basename($file));
                    Storage::disk('s3')->delete($file);
                    
                    # update the CTTradeRequestOfferDeposit, CTTradeRequestOfferDepositMT527 
                } catch (\Throwable $th) {
                    $failedFolder = 'repo/inflow/558/yield_inflow/failed/' . now()->format("n-Y") . '/';
                    Storage::disk('s3')->move($file, $failedFolder . basename($file));
                    Storage::disk('s3')->delete($file);
                    # send email 
                } 
            }

            $this->info('All Files Processed Successfully');
        } catch (\Throwable $th) {
            # send an email to admins 
            $this->error($th->getMessage());
        }
    }
}

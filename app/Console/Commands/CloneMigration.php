<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use ZipArchive;

class CloneMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clone:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clone Database';

    /**
     * Create a new command instance.i
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
        Artisan::call('backup:clean');
        Artisan::call('backup:run --only-db');

        $now = date('Y-m-d');
        $files = glob(storage_path("app/YieldExchange/$now*"));
        if(count($files) == 0){
            $this->error("No files found today");
            exit();
        }

        $zip = new ZipArchive();
        $path = $files[0];
        $status = $zip->open($path);
        if ($status !== true) {
             throw new \Exception($status);
        }

        $storageDestinationPath = storage_path("app/YieldExchange/unzip");

        if (!\File::exists( $storageDestinationPath)) {
            \File::makeDirectory($storageDestinationPath, 0755, true);
        }
        $zip->extractTo($storageDestinationPath);
        $zip->close();

       $this->drop_tables();

         $files = glob(storage_path("app/YieldExchange/unzip/db-dumps/*.sql"));
        if(count($files) == 0){
            $this->error("No sql found today");
            exit();
        }
    
         \DB::connection('mysql_clone')->unprepared(file_get_contents($files[0]));
         unlink($files[0]);
        return 0;
    }

    public function drop_tables(){
        \DB::connection('mysql_clone')->statement("SET FOREIGN_KEY_CHECKS = 0");
        $tables = \DB::connection('mysql_clone')->select('SHOW TABLES');
        foreach($tables as $table){
            $this->info(json_encode($table));
            Schema::connection('mysql_clone')->drop($table->{'Tables_in_'.config('database.connections.mysql_clone.database')});
        }
        \DB::connection('mysql_clone')->statement("SET FOREIGN_KEY_CHECKS = 1");
    }
}

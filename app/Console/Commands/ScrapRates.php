<?php

namespace App\Console\Commands;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScrapRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'web-scrap:offers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtains rates from given websites';

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
        $this->info("STARTED");

        $links = ['https://www.cannex.com/public/term01e.html','https://www.cannex.com/public/term02e.html'];
        DB::table('public_rates')->update(['status'=>'INACTIVE']);
        foreach($links as $link_index => $link){
            $this->alert("Working on link => ".$link);
            $html = file_get_contents($link);
            $dom = new DOMDocument;
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);
            $rows = $xpath->query('(//table)[1]/tr');
            foreach ($rows as $key => $row) {
                if($key < 3){
                    $this->error("Skipping index: ".$key); continue;
                }
                $cells = $xpath->query('td', $row);

                $org_name = $cells[0]->nodeValue;

                $organization_id=null;
                if( $org_name!=" " ){
                    $organization = DB::table('public_organizations')->where('name',$org_name)->first();
                    if(!$organization){
                        $organization_id = DB::table('public_organizations')->insertGetId([
                            'name'=>$org_name
                        ]);
                    }else{
                        $organization_id = $organization->id;
                    }
                }else{
                    $this->error("Organization missing: ".$key." ".$org_name);
                }

                $product_name = $cells[1]->nodeValue == "no" ? "Non-Redeemable" : ($cells[1]->nodeValue == "yes" ? "Redeemable" : $cells[1]->nodeValue);
                $product = DB::table('public_products')->where('product_type',$product_name)->where('redemption_period',$cells[2]->nodeValue)->first();
                if(!$product){
                    $product_id = DB::table('public_products')->insertGetId([
                        'product_type'=>$product_name,
                        'redemption_period'=>$cells[2]->nodeValue
                    ]);
                }else{
                    $product_id = $product->id;
                }

                $type = $link_index == 0 ? 'days' : 'years';
                $types_ = $link_index == 0 ? [30,60,90,120,180,270] : [1,2,3,4,5,6];

                $public_offers = DB::table('public_rates')->orderBy('id','DESC')->first();
                foreach ($types_ as $index => $type_) {
                    $interest_rate = (float)str_replace(",", "", $cells[($index+1)+3]->nodeValue);
                    DB::table('public_rates')->insertGetId([
                        'organization_id'=>!empty($organization_id) ? $organization_id : ($public_offers ? $public_offers->organization_id : 0),
                        'product_id'=>$product_id,
                        'term_length'=>$type_,
                        'term_length_type'=>$type,
                        'minimum_deposit'=>(float)str_replace(",","",$cells[3]->nodeValue),
                        'interest_rate'=>$interest_rate > 0 ? $interest_rate : NULL,
                        'created_at'=>Carbon::now()
                    ]);
                }

            }

        }

        $this->info("COMPLETED");

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\CustomEncoder;
use App\Mail\ConsolidatedMails;
use App\Models\DepositRequest;
use App\Models\Offer;
use App\Models\Organization;
use App\Models\Product;
use App\Models\UnsubscribedEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NewOffersReceived extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newoffers:received';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email for offers received';

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
        $previous1Hour = $currentDateTime->subHour();
        $offers = Offer::with('deposit')
            ->where('created_date', '>=', $previous1Hour)
            ->whereNull('campaign_product_id')  /// post request only
            ->get();
            
        /// organiztion FI
        $offers->groupBy('invited.organization.id')->each(function ($groupedOffers) { /// Group by FI
            $organizations_users = $groupedOffers->first()->invited->organization->notifiableUsersEmails(false);
            foreach($organizations_users as $user){
                $unsubscibed_emials = UnsubscribedEmail::where('user_email',$user->email)->where('user_id',$user->id)->first();
                if (!$unsubscibed_emials) {
                    Mail::to($user->email)->queue(new ConsolidatedMails([
                        'subject' => 'Your offers have been sent!',
                        'data' => $groupedOffers,
                        'email' => CustomEncoder::urlValueEncrypt($user->email),
                        'user_id' => CustomEncoder::urlValueEncrypt($user->id)
                    ], 'new_offer_received'));
                }  
            }
        });



        /// Depositor 


        $countsAndRates = $offers->groupBy('invited.depositRequest.organization.id') // Group by depositor
            ->map(function ($groupedOffers) {
                return $groupedOffers->groupBy('invited.depositRequest.product.id') // Group by product
                    ->map(function ($groupedProductOffers) {
                        $productName = $groupedProductOffers->first()->invited->depositRequest->lockout_period_days == 0 ?
                            $groupedProductOffers->first()->invited->depositRequest->product_name :
                            $groupedProductOffers->first()->invited->depositRequest->lockout_period_days .
                            ' Days ' . $groupedProductOffers->first()->invited->depositRequest->product_name;  // Product name
                        $count = $groupedProductOffers->count(); // Count per product
                        $highestRate = $groupedProductOffers->max('interest_rate_offer'); // Highest rate for the product
                        return ['count' => $count, 'highest_rate' => $highestRate, 'product_name' => $productName];
                    });
            });


        foreach ($countsAndRates as $depositorId => $productData) {
            $depositor = Organization::find($depositorId);
            $organizations_users = $depositor->notifiableUsersEmails(false);
            $products = json_decode($productData, true);
            $productsWithoutKeys = array_values($products);
            foreach($organizations_users as $user){
                $unsubscibed_emials = UnsubscribedEmail::where('user_email',$user->email)->where('user_id',$user->id)->first();
                if (!$unsubscibed_emials) {
                    Mail::to($user->email)->queue(new ConsolidatedMails([
                        'subject' => "Exciting news... you've an offer!",
                        'data' => $productsWithoutKeys,
                        'email' => CustomEncoder::urlValueEncrypt($user->email),
                        'user_id' => CustomEncoder::urlValueEncrypt($user->id)
                    ], 'depositor_offers_sent'));
                }  
            }
        }



        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\DepositRequest;
use App\Models\InvitedBank;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DepositRequestAndOfferExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiry-check:deposit-request-and-offers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the deposit request and offer expiry';

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
        DepositRequest::with(['offers','invited'])->where('request_status','ACTIVE')->chunkById(100,function ($depositor_requests){
            foreach ($depositor_requests as $depositor_request) {
                $utc_date_time_now = getUTCTimeNow();
                $closing_date_time = Carbon::parse($depositor_request->closing_date_time);
                $date_of_deposit = Carbon::parse($depositor_request->date_of_deposit);
                if( $depositor_request->offers->count() == 0 ){
                    if($utc_date_time_now->greaterThan($closing_date_time)){

                        /*
                         * The Deposit request needs to be closed, by moving to history
                         */
                        $depositor_request->request_status='NO_OFFERS_RECEIVED';
                        archiveTable($depositor_request->id,'depositor_requests',0,'NO_OFFERS_RECEIVED');
                        $depositor_request->save();

                        /*
                         * The invites for the deposit request needs to  closed too
                         */
                        InvitedBank::whereIn('id',$depositor_request->invited->pluck('id')->toArray())->update([
                            'invitation_status'=>'DID_NOT_PARTICIPATE'
                        ]);

                        /*
                         * Go to next request
                         */
                        continue;

                    }
                }

                /*
                 * This deposit request has offers
                 */
                $active_offers = $depositor_request->offers->where('offer_status','ACTIVE');
                $selected_offers = $depositor_request->offers->where('offer_status','SELECTED');

                /*----------------------------------REQUEST EXPIRY-------------------------------------------------------------*/
                if ( $utc_date_time_now->greaterThan($closing_date_time) && ( $utc_date_time_now->greaterThan($date_of_deposit) && $selected_offers->count() == 0) ) {
                    //Closing date and time of request has expired AND no offers were selected by the date of deposit

                    /*
                       * The Deposit request needs to be expired, by moving to history
                       */
                    $depositor_request->request_status='EXPIRED';
                    archiveTable($depositor_request->id,'depositor_requests',0,'EXPIRED');
                    $depositor_request->save();

                    /*
                    * since no offers has been selected at the time of request expiry -> mark all offers as not selected
                    */
                    foreach ( $depositor_request->offers as $offer ){
                        if( in_array($offer->offer_status,['ACTIVE']) ){
                            $offer->offer_status="NOT_SELECTED";
                            archiveTable($offer->id,'offers',0,'NOT_SELECTED');
                            $offer->save();
                        }
                    }

                    /*
                     * Go to next request
                     */
                    continue;
                }
                /*----------------------------------END REQUEST EXPIRY-------------------------------------------------------------*/

                if ( $utc_date_time_now->greaterThan($closing_date_time) ) {
                    // No offer can be sent for a request due to offer closing date and time being PAST

                    // update all invites where there was no offer and update invited table to have a status DID_NOT_PARTICIPATE
                    InvitedBank::whereIn('id',$depositor_request->invited->pluck('id')->toArray())->where('invitation_status','INVITED')->update([
                        'invitation_status'=>'DID_NOT_PARTICIPATE'
                    ]);
                }

                if( $active_offers->count() > 0 ){
                    foreach ($active_offers as $active_offer) {
                        $rate_held_until = Carbon::parse($active_offer->rate_held_until);
                        if($utc_date_time_now->greaterThan($rate_held_until)){
                            $active_offer->offer_status='EXPIRED';
                            archiveTable($active_offer->id,'offers',0,'EXPIRED');
                            $active_offer->save();
                        }
                    }
                }

                if( $selected_offers->count() > 0 ){
                    //Depositor has selected an offer(s) hence mark the request as completed
                    $depositor_request->request_status='COMPLETED';
                    archiveTable($depositor_request->id,'depositor_requests',0,'COMPLETED');
                    $depositor_request->save();
                }

            }
        });

        return 0;
    }
}

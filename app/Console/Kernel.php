<?php

namespace App\Console;

use App\Console\Commands\AbandonedAccounts;
use App\Console\Commands\ChatReminder;
use App\Console\Commands\CounterOfferExpiry;
use App\Console\Commands\DepositExpiry;
use App\Console\Commands\DepositRequestAndOfferExpiry;
use App\Console\Commands\MarketPlaceOfferExpiry;
use App\Console\Commands\NotifyAdminOfNewUsers;
use App\Console\Commands\ResendInvitationNonPartneredFI;
use App\Console\Commands\ScrapRates;
use App\Mail\AdminMail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Stringable;

use App\Console\Commands\ExpireCampaigns;
use App\Console\Commands\ActivateCampaigns;
use App\Console\Commands\BankPendingDepositReminder;
use App\Console\Commands\CampaignNearlyGoingLive;
use App\Console\Commands\CheckRepoDepositsWithDummyIds;
use App\Console\Commands\DailyCampaignSummery;
use App\Console\Commands\DepositorPendingDeposits;
use App\Console\Commands\NotifyDepositorOfAGICAboutToExpire;
use App\Console\Commands\DiscoverTopGIC;
use App\Console\Commands\NewOffersReceived;
use App\Console\Commands\NoActiveCampigns;
use App\Console\Commands\UpdateProductDetails;
use App\Console\Commands\CreateAllDepositorsGroupAndAddNonAddedDepositors;
use App\Console\Commands\DeactivateTradeProducts;
use App\Console\Commands\ExpireCGRequestsForMoney;
use App\Console\Commands\ExpireRepos;
use App\Console\Commands\ExpiringReposNotifications;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ChatReminder::class,
        UpdateProductDetails::class,
        DepositExpiry::class,
        CreateAllDepositorsGroupAndAddNonAddedDepositors::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command("queue:work")->everyMinute()->timezone("America/Vancouver");
        //https://laravel.com/docs/8.x/scheduling#scheduling-artisan-commands
        $schedule->command(DeactivateTradeProducts::class)->everyMinute()->timezone("America/Vancouver")->onSuccess(function (Stringable $output) {
            // The task succeeded...
            $this->notifyDeveloperOfScheduleResponse('DeactivateTradeProducts');
        })->onFailure(function (Stringable $output) {
            // The task failed...
            $this->notifyDeveloperOfScheduleResponse('DeactivateTradeProducts', false, $output);
        });

        $schedule->command(DepositExpiry::class)->weeklyOn(1, '07:00')->timezone("America/Vancouver")->onSuccess(function (Stringable $output) {
            // The task succeeded...
            $this->notifyDeveloperOfScheduleResponse('DepositExpiry');
        })->onFailure(function (Stringable $output) {
            // The task failed...
            $this->notifyDeveloperOfScheduleResponse('DepositExpiry', false, $output);
        });     ///// run weekly on monday at 7 am


        $schedule->command(BankPendingDepositReminder::class)->weeklyOn(1, '07:00')->timezone("America/Vancouver")->onSuccess(function (Stringable $output) {
            // The task succeeded...
            $this->notifyDeveloperOfScheduleResponse('DepositExpiry');
        })->onFailure(function (Stringable $output) {
            // The task failed...
            $this->notifyDeveloperOfScheduleResponse('DepositExpiry', false, $output);
        });     ///// run weekly on monday at 7 am   used by both depositor and bank




        //         $schedule->command(MarketPlaceOfferExpiry::class)->dailyAt('23:59')->timezone("America/Vancouver")
        //             ->onSuccess(function (Stringable $output) {
        //             // The task succeeded...
        //             $this->notifyDeveloperOfScheduleResponse('MarketPlaceOfferExpiry');
        //         })->onFailure(function (Stringable $output) {
        //             // The task failed...
        //             $this->notifyDeveloperOfScheduleResponse('MarketPlaceOfferExpiry', false, $output);
        //         });

        $schedule->command(DepositRequestAndOfferExpiry::class)->everyMinute()->onSuccess(function (Stringable $output) {
            // The task succeeded...
            $this->notifyDeveloperOfScheduleResponse('DepositRequestAndOfferExpiry');
        })->onFailure(function (Stringable $output) {
            // The task failed...
            $this->notifyDeveloperOfScheduleResponse('DepositRequestAndOfferExpiry', false, $output);
        });

        $schedule->command(NotifyAdminOfNewUsers::class)->twiceDaily(8, 17)->timezone("America/Vancouver")
            ->onSuccess(function (Stringable $output) {
                // The task succeeded...
                $this->notifyDeveloperOfScheduleResponse('NotifyAdminOfNewUsers', true, $output, true);
            })->onFailure(function (Stringable $output) {
                // The task failed...
                $this->notifyDeveloperOfScheduleResponse('NotifyAdminOfNewUsers', false, $output, true);
            });

        $schedule->command(ResendInvitationNonPartneredFI::class)->dailyAt('08:00')->timezone("America/Vancouver")
            ->onSuccess(function (Stringable $output) {
                // The task succeeded...
                $this->notifyDeveloperOfScheduleResponse('ResendInvitationNonPartneredFI', true, $output, true);
            })->onFailure(function (Stringable $output) {
                // The task failed...
                $this->notifyDeveloperOfScheduleResponse('ResendInvitationNonPartneredFI', false, $output, true);
            });

        $schedule->command(CounterOfferExpiry::class)->everyMinute()->onSuccess(function (Stringable $output) {
            // The task succeeded...
            $this->notifyDeveloperOfScheduleResponse('CounterOfferExpiry');
        })->onFailure(function (Stringable $output) {
            // The task failed...
            $this->notifyDeveloperOfScheduleResponse('CounterOfferExpiry', false, $output);
        });




        $schedule->command('chat:reminder')->everyThirtyMinutes();

        $schedule->command(ScrapRates::class)->dailyAt('12:00')->timezone("America/Vancouver")
            ->onSuccess(function (Stringable $output) {
                // The task succeeded...
                $this->notifyDeveloperOfScheduleResponse('ScrapRates', true, $output, true);
            })->onFailure(function (Stringable $output) {
                // The task failed...
                $this->notifyDeveloperOfScheduleResponse('ScrapRates', false, $output, true);
            });
        $schedule->command('clone:db')->dailyAt('00:30')->timezone("America/Vancouver");

        $schedule->command('request:reminder')->dailyAt('00:00');
        $schedule->command('product:toggle')->dailyAt('00:10');

        //camapigns crons
        $schedule->command(ExpireCampaigns::class)->everyMinute()->onSuccess(function (Stringable $output) {
            // The task succeeded...
            $this->notifyDeveloperOfScheduleResponse('ExpireCampaigns');
        })->onFailure(function (Stringable $output) {
            // The task failed...
            $this->notifyDeveloperOfScheduleResponse('ExpireCampaigns', false, $output);
        });

        $schedule->command(ActivateCampaigns::class)->everyMinute()->onSuccess(function (Stringable $output) {
            // The task succeeded...
            $this->notifyDeveloperOfScheduleResponse('ActivateCampaigns');
        })->onFailure(function (Stringable $output) {
            // The task failed...
            $this->notifyDeveloperOfScheduleResponse('ActivateCampaigns', false, $output);
        });
        // $schedule->command(CampaignNearlyGoingLive::class)->everyMinute()->onSuccess(function (Stringable $output) {
        // })->onFailure(function (Stringable $output) {
        // });

        $schedule->command(NotifyDepositorOfAGICAboutToExpire::class)->dailyAt('08:00')->timezone("America/Vancouver")->onSuccess(function (Stringable $output) {})->onFailure(function (Stringable $output) {});

        // $schedule->command(DepositorPendingDeposits::class)->dailyAt('08:00')->timezone("America/Vancouver")->onSuccess(function (Stringable $output) {
        // })->onFailure(function (Stringable $output) {
        // });



        // $schedule->command(DiscoverTopGIC::class)->dailyAt('08:00')->timezone("America/Vancouver")->onSuccess(function (Stringable $output) {
        // })->onFailure(function (Stringable $output) {
        // }); $schedule->command(CampaignNearlyGoingLive::class)->everyMinute()->onSuccess(function (Stringable $output) {
        // })->onFailure(function (Stringable $output) {
        // });
        // $schedule->command(NotifyDepositorOfAGICAboutToExpire::class)->dailyAt('08:00')->timezone("America/Vancouver")->onSuccess(function (Stringable $output) {
        // })->onFailure(function (Stringable $output) {
        // });

        // $schedule->command(DepositorPendingDeposits::class)->dailyAt('08:00')->timezone("America/Vancouver")->onSuccess(function (Stringable $output) {
        // })->onFailure(function (Stringable $output) {
        // });
        // $schedule->command(DepositorPendingDeposits::class)->everyMinute()->onSuccess(function (Stringable $output) {
        // })->onFailure(function (Stringable $output) {
        // });

        // $schedule->command(DiscoverTopGIC::class)->dailyAt('08:00')->timezone("America/Vancouver")->onSuccess(function (Stringable $output) {
        // })->onFailure(function (Stringable $output) {
        // });

        //campaigns crons

        /// consolidated emails
        $schedule->command(NoActiveCampigns::class)->dailyAt('07:00')->timezone("America/Vancouver");
        ///$schedule->command(NotifyDepositorOfAGICAboutToExpire::class)->dailyAt('07:00')->timezone("America/Vancouver");
        $schedule->command(DailyCampaignSummery::class)->dailyAt('07:00')->timezone("America/Vancouver");
        $schedule->command(DiscoverTopGIC::class)->dailyAt('07:00')->timezone("America/Vancouver");
        $schedule->command(NewOffersReceived::class)->hourly()->timezone("America/Vancouver");
        /// consolidated emails

        $schedule->command(AbandonedAccounts::class)->dailyAt('07:00')->timezone("America/Vancouver");

        $schedule->command(CheckRepoDepositsWithDummyIds::class)->dailyAt('07:00')->timezone("America/Vancouver");


        $schedule->command(ExpireCGRequestsForMoney::class)->everyMinute()->onSuccess(function (Stringable $output) {
            // The task succeeded...
            $this->notifyDeveloperOfScheduleResponse('ExpireCGRequestsForMoney');
        })->onFailure(function (Stringable $output) {
            // The task failed...
            $this->notifyDeveloperOfScheduleResponse('ExpireCGRequestsForMoney', false, $output);
        });
        $schedule->command(ExpireRepos::class)->dailyAt('07:00')->timezone("America/Vancouver");
        $schedule->command(ExpiringReposNotifications::class)->dailyAt('07:00')->timezone("America/Vancouver");
        
        

    }

    private function notifyDeveloperOfScheduleResponse($command, $success = true, $output = "", $forceNotify = false)
    {
        if ($success) {
            Log::alert('Cron-job success notification > ' . $command);
            return;
        }

        if ($forceNotify || App::environment('production') && !$success) {
            $to = ['david@yieldexchange.ca', 'ravi@yieldexchange.ca'];
            if ($forceNotify && !App::environment('production')) {
                $to = ['david@yieldexchange.ca'];
            }

            Mail::to($to)->queue(new AdminMail([
                'subject' => 'Cron-job notification > ' . $command,
                'message' => 'The above cron job has ' . ($success ? "SUCCESSFUL" : "FAILED") . ', please notify the developer to fix ASAP!!',
            ]));
            return;
        }

        Config::set('mail.driver', 'smtp');
        Config::set('mail.host', 'smtp.mailtrap.io');
        Config::set('mail.port', '2525');
        Config::set('mail.encryption', 'tls');
        Config::set('mail.username', '7e5ee6e836f400');
        Config::set('mail.password', 'e235d7c38deef4');

        Mail::to(['david@yieldexchange.ca'])->queue(new AdminMail([
            'subject' => config("app.env_name") . ' > Cron-job notification > ' . $command,
            'message' => ($success ? "SUCCESSFUL" : "FAILED") . ', ' . $output,
        ]));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

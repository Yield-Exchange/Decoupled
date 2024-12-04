<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //  $this->call(UserSeeder::class);
         $this->call(FITypesSeeder::class);
         $this->call(DepositInactivateReasonsSeeder::class);
         $this->call(NAICSSeeder::class);
         $this->call(OfferWithdrawalReasonsSeeder::class);
         $this->call(PermissionsSeeder::class);
         $this->call(RequestWithdrawalReasonsSeeder::class);
         $this->call(PotentialYearlyDepositsSeeder::class);
         $this->call(WholeSaleDepositsPortfolioSeeder::class);
         $this->call(ProvinceCitySeeder::class);
         $this->call(DocumentTypeSeeder::class);
         $this->call(InterestRatesTypesSeeder::class);
         $this->call(OrganizationLevelPermissionSeeder::class);
        //  $this->call(CollateralsBasketsSeeder::class);
         $this->call(PreferredCollateralsSeeder::class);
         $this->call(TradeAllowedSettlementPeriodSeeder::class);  
         $this->call(TradeIssuerSeeder::class);  
         $this->call(SyncInterestRatesCalculationConventions::class);
         
         
         
    }
}

<?php

namespace Database\Seeders;

use App\Models\TradeCollateralIssuer;
use Illuminate\Database\Seeder;

class TradeIssuerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $issuers = [
            [
                'id' => 1,
                'name' => 'Issuer 1',
            ],
            [
                'id' => 2,
                'name' => 'Issuer 3',
            ],
            [
                'id' => 3,
                'name' => 'Issuer 4',
            ],
            [
                'id' => 4,
                'name' => 'Issuer 5',
            ],
        ];

        foreach ($issuers as $issuer) {
            // Check if the settlement period already exists
            if (!TradeCollateralIssuer::find($issuer['id'])) {
                $issuer['created_at']=getUTCDateNow();
                TradeCollateralIssuer::create($issuer);
            }
        }
    }
}

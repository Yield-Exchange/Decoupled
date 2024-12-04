<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TradeAllowedSettlementPeriod;

class TradeAllowedSettlementPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settlements = [
            [
                'id' => 1,
                'trade_date_label' => 'T',
                'period_label' => 'Days',
                'duration' => 0,
                'description' => '',
            ],
            [
                'id' => 2,
                'trade_date_label' => 'T',
                'period_label' => 'Days',
                'duration' => 1,
                'description' => '',
            ],
            [
                'id' => 3,
                'trade_date_label' => 'T',
                'period_label' => 'Days',
                'duration' => 2,
                'description' => '',
            ],
            [
                'id' => 4,
                'trade_date_label' => 'T',
                'period_label' => 'Days',
                'duration' => 3,
                'description' => '',
            ],
        ];

        foreach ($settlements as $settlement) {
            // Check if the settlement period already exists
            if (!TradeAllowedSettlementPeriod::find($settlement['id'])) {
                TradeAllowedSettlementPeriod::create($settlement);
            }
        }
    }
}

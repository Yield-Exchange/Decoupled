<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class UpdateProductDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateproductdetails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will help to update the products details including the definition ,earning rate and flexibility';

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
        $product_details = [
            [

                'description' => "Short Term",
                'definition' => "An investment with a flexible term, allowing partial or full redemption before maturity under certain conditions",
                'earning_rate' => "5",
                'earning_text' => "High",
                'flexibility_rate' => "5",
                'flexibility_text' => "Moderate",
            ],
            [

                'description' => "Non-Redeemable",
                'definition' => "A fixed-term investment with a locked interest rate, providing steady and predictable earnings over the investment period.",
                'earning_rate' => "5",
                'earning_text' => "Higher",
                'flexibility_rate' => "5",
                'flexibility_text' => "No",
            ],
            [

                'description' => "Cashable",
                'definition' => "A flexible investment allowing funds to be withdrawn after a specific lock-in period without penalties, offering predictable earnings and partial liquidity.",
                'earning_rate' => "3",
                'earning_text' => "Moderate",
                'flexibility_rate' => "3",
                'flexibility_text' => "Good",
            ],
            [

                'description' => "Notice deposit",
                'definition' => "A savings option requiring advance notice for withdrawals, providing stable returns with planning.",
                'earning_rate' => "2",
                'earning_text' => "Moderate",
                'flexibility_rate' => "3",
                'flexibility_text' => "Good",
            ],
            [

                'description' => "High Interest Savings",
                'definition' => "An account providing full flexibility, allowing funds to be withdrawn at any time without penalties.",
                'earning_rate' => "3",
                'earning_text' => "Moderate",
                'flexibility_rate' => "5",
                'flexibility_text' => "Full",
            ]
        ];

        foreach ($product_details as $product) {
            Product::where('description', $product['description'])->update(
                [

                    'definition' => $product['definition'],
                    'earning_rate' => $product['earning_rate'],
                    'earning_text' => $product['earning_text'],
                    'flexibility_rate' => $product['flexibility_rate'],
                    'flexibility_text' => $product['flexibility_text']
                ],
            );
        }
    }
}

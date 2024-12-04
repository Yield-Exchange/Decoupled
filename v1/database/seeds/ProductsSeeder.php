<?php


use Phinx\Seed\AbstractSeed;

class ProductsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'description' => 'Short Term'
            ],
            [
                'description' => 'Non-Redeemable'
            ],
            [
                'description' => 'Cashable'
            ],
            [
                'description' => 'Notice deposit'
            ],
            [
                'description' => 'High Interest Savings'
            ]
        ];

        $insert_data=[];
        foreach ($data as $datum) {
            $product = $this->fetchRow("SELECT * FROM products WHERE description LIKE '".$datum['description']."'");
            if ( !$product ) {
                array_push($insert_data,$datum);
            }
        }

        if (!empty($insert_data)) {
            $products = $this->table('products');
            $products->insert($insert_data)->saveData();
        }
    }
}

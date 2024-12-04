<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddHighInterestRatesOnDepositRequest extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $offers = $this->table('offers');
        $offers->addColumn('rate_type', 'enum',['values'=>['VARIABLE','FIXED'],'default'=>'FIXED']);
        $offers->addColumn('prime_rate','float',['null' => true]);
        $offers->addColumn('rate_operator','enum',['values'=>['+','-'],'default'=>NULL,'null' => true]);
        $offers->addColumn('fixed_rate','float',['null' => true])
            ->save();

        $offers_archives = $this->table('offers_archives');
        $offers_archives->addColumn('rate_type', 'enum',['values'=>['VARIABLE','FIXED'],'default'=>'FIXED']);
        $offers_archives->addColumn('prime_rate','float',['null' => true]);
        $offers_archives->addColumn('rate_operator','enum',['values'=>['+','-'],'default'=>NULL,'null' => true]);
        $offers_archives->addColumn('fixed_rate','float',['null' => true])
            ->save();
    }
}

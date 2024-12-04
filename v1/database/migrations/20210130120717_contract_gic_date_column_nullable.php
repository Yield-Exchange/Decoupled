<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ContractGicDateColumnNullable extends AbstractMigration
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
    public function up()
    {
        $users = $this->table('contracts');
        $users->changeColumn('gic_start_date', 'datetime', ['null' => true]);
        $users->changeColumn('gic_number', 'string', ['null' => true])
            ->save();

        $users = $this->table('contracts_archive');
        $users->changeColumn('gic_start_date', 'datetime', ['null' => true]);
        $users->changeColumn('gic_number', 'string', ['null' => true])
            ->save();
    }
}

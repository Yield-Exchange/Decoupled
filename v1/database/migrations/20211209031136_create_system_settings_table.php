<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateSystemSettingsTable extends AbstractMigration
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
        $system_settings = $this->table('system_settings');
        $system_settings->addColumn('prime_rate', 'float',['null'=>true,'default'=>0])
            ->addColumn('created_date', 'datetime',['null'=>true])
            ->addColumn('modified_date', 'datetime',['null'=>true])
            ->addColumn('modified_section', 'string',['limit'=>50,'null'=>true])
            ->addColumn('modified_by', 'integer',['null'=>true])
            ->create();
    }
}

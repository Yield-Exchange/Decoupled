<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateSystemSettingsTable extends AbstractMigration
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
        $system_settings->addColumn('key', 'string',['null'=>false]);
        $system_settings->addColumn('value', 'string',['null'=>false]);
        $system_settings->removeColumn('modified_section');
        $system_settings->removeColumn('prime_rate')
            ->save();
    }
}

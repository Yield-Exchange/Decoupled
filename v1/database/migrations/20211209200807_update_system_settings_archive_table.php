<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateSystemSettingsArchiveTable extends AbstractMigration
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
        $system_settings_archive = $this->table('system_settings_archive');
        $system_settings_archive->addColumn('key', 'string',['null'=>false]);
        $system_settings_archive->addColumn('value', 'string',['null'=>false]);
        $system_settings_archive ->addColumn('created_date', 'datetime',['null'=>false])
            ->addColumn('modified_by', 'integer',['null'=>true])
            ->addColumn('modified_date', 'datetime',['null'=>true])
            ->save();
    }
}

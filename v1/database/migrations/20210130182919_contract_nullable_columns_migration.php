<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ContractNullableColumnsMigration extends AbstractMigration
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
    public function up(): void
    {
        $users = $this->table('contracts');
        $users->changeColumn('modified_section', 'string', ['null' => true,'limit'=>255]);
        $users->changeColumn('modified_by', 'integer', ['null' => true]);
        $users->changeColumn('created_at', 'datetime', ['null' => false])
            ->save();

        $users = $this->table('contracts_archive');
        $users->changeColumn('modified_section', 'string', ['null' => true,'limit'=>255]);
        $users->changeColumn('modified_by', 'integer', ['null' => true]);
        $users->changeColumn('created_at', 'datetime', ['null' => false])
            ->save();
    }
}

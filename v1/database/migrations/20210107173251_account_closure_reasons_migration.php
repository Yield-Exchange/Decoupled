<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AccountClosureReasonsMigration extends AbstractMigration
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
        $account_closure_reasons = $this->table('account_closure_reasons');
        $account_closure_reasons->addColumn('reason', 'string',['limit'=>255])
            ->addColumn('created_at', 'datetime',['null'=>true])
            ->addColumn('updated_at', 'datetime',['null'=>true])
            ->create();
    }
}

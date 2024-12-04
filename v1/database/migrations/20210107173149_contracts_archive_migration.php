<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ContractsArchiveMigration extends AbstractMigration
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
        $contracts_archive = $this->table('contracts_archive');
        $contracts_archive->addColumn('reference_no', 'string',['limit'=>255])
            ->addColumn('offer_id', 'integer')
            ->addColumn('offered_amount', 'double')
            ->addColumn('deposit_date', 'datetime')
            ->addColumn('status', 'enum',['values'=>['PENDING_CONTRACT','WITHDRAWN','MATURED','ACTIVE','EARLY_REDEMPTION']])
            ->addColumn('created_at', 'datetime',['null'=>true])
            ->addColumn('modified_at', 'datetime',['null'=>true])
            ->addColumn('modified_section', 'string',['limit'=>50])
            ->addColumn('modified_by', 'integer')
            ->create();
    }
}

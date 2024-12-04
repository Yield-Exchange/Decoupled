<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RenameContractTableToDeposit extends AbstractMigration
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
        $this->query("RENAME TABLE `contracts` TO `deposits`;");
        $this->query("RENAME TABLE `contracts_archive` TO `deposits_archive`;");
        $this->query("ALTER TABLE `chat` ADD `deposit_id` INTEGER AFTER `contract_id`;");
        $this->query("UPDATE `chat` SET deposit_id=contract_id");
        $this->table('chat')->removeColumn('contract_id')->save();

        $contracts = $this->table('deposits');
        $contracts->changeColumn('status', 'enum',['values'=>['PENDING_CONTRACT','PENDING_DEPOSIT','INCOMPLETE','EARLY_REDEMPTION','ACTIVE','MATURED','WITHDRAWN']])
            ->save();

        $contracts_archive = $this->table('deposits_archive');
        $contracts_archive->changeColumn('status', 'enum',['values'=>['PENDING_CONTRACT','PENDING_DEPOSIT','INCOMPLETE','EARLY_REDEMPTION','ACTIVE','MATURED','WITHDRAWN']])
            ->save();

        $this->query("UPDATE `deposits` SET status='PENDING_DEPOSIT' WHERE status='PENDING_CONTRACT'");
        $this->query("UPDATE `deposits_archive` SET status='PENDING_DEPOSIT' WHERE status='PENDING_CONTRACT'");

        $contracts = $this->table('deposits');
        $contracts->changeColumn('status', 'enum',['values'=>['PENDING_DEPOSIT','INCOMPLETE','EARLY_REDEMPTION','ACTIVE','MATURED','WITHDRAWN']])
            ->save();

        $contracts_archive = $this->table('deposits_archive');
        $contracts_archive->changeColumn('status', 'enum',['values'=>['PENDING_DEPOSIT','INCOMPLETE','EARLY_REDEMPTION','ACTIVE','MATURED','WITHDRAWN']])
            ->save();
    }
}

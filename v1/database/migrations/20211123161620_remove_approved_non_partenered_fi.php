<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RemoveApprovedNonParteneredFi extends AbstractMigration
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
        $this->query("UPDATE `users` SET `account_status` = 'ACTIVE' WHERE account_status='APPROVED';");

        $users = $this->table('users');
        $users->changeColumn('account_status', 'enum',[
            'values' => ['ACTIVE','PENDING','SUSPENDED','LOCKED','CLOSED','REJECTED','INVITED','DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS','REVIEWING']
        ])->save();

        $this->query("UPDATE `users_archive` SET `account_status` = 'ACTIVE' WHERE account_status='APPROVED';");

        $users_archive = $this->table('users_archive');
        $users_archive->changeColumn('account_status', 'enum',[
            'values' => ['ACTIVE','PENDING','SUSPENDED','LOCKED','CLOSED','REJECTED','INVITED','DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS','REVIEWING']
        ])->save();
    }
}

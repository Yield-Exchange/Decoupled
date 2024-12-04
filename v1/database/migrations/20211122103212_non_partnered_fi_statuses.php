<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class NonPartneredFiStatuses extends AbstractMigration
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
        $users = $this->table('users');
        $users->changeColumn('account_status', 'enum',[
            'values' => ['ACTIVE','PENDING','SUSPENDED','LOCKED','CLOSED','REJECTED','INVITED','DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS','REVIEWING','APPROVED','ON_REVIEW']
        ])->save();

        $this->query("UPDATE `users` SET `account_status` = 'INVITED' WHERE account_status='PENDING' AND is_non_partnered_fi=1;");
        $this->query("UPDATE `users` SET `account_status` = 'APPROVED' WHERE is_non_partnered_fi=1 AND terms_and_conditions_accepted=1;");
        $this->query("UPDATE `users` SET `account_status` = 'REVIEWING' WHERE is_non_partnered_fi=1 AND terms_and_conditions_accepted=0;");
        $this->query("UPDATE `users` SET `account_status` = 'PENDING' WHERE `account_status` NOT IN('INVITED','APPROVED','PENDING') AND is_non_partnered_fi=1;");

        $users = $this->table('users');
        $users->changeColumn('account_status', 'enum',[
            'values' => ['ACTIVE','PENDING','SUSPENDED','LOCKED','CLOSED','REJECTED','INVITED','DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS','REVIEWING','APPROVED']
        ])->save();

        $users = $this->table('users');
        $users->removeColumn('terms_and_conditions_accepted')
        ->removeColumn('invitation_email_sent')
        ->save();

        $users_archive = $this->table('users_archive');
        $users_archive->removeColumn('account_status')
            ->save();

        $users_archive = $this->table('users_archive');
        $users_archive->addColumn('created_by', 'integer',['null' => false])
            ->addColumn('last_login', 'datetime', ['null' => true])
            ->addColumn('account_manager', 'string',['null' => true])
            ->addColumn('is_temporary', 'boolean',['default' => 0])
            ->addColumn('is_non_partnered_fi', 'boolean',['default' => 0])
            ->addColumn('password_changed', 'boolean',['default' => 0])
            ->addColumn('account_status', 'enum',[
                'values' => ['ACTIVE','PENDING','SUSPENDED','LOCKED','CLOSED','REJECTED','INVITED','DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS','REVIEWING','APPROVED']
            ])->save();

    }
}

<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UsersMigration extends AbstractMigration
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
        $users->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('profile_pic', 'string', ['limit' => 255,'null'=>true])
            ->addColumn('email', 'string', ['limit' => 50])
            ->addColumn('account_opening_date', 'string', ['limit' => 100])
            ->addColumn('account_status', 'enum', ['values' => ['ACTIVE','PENDING','SUSPENDED','LOCKED','CLOSED','REJECTED']])
            ->addColumn('modified_date', 'datetime', ['null' => true])
            ->addColumn('modified_section', 'string', ['limit' => 255,'null' => true,])
            ->addColumn('modified_by', 'integer',['null' => true])
            ->addColumn('failed_login_attempts', 'integer')
            ->addColumn('account_closure_date', 'datetime', ['null' => true])
            ->addColumn('account_closure_reason', 'string', ['null' => true,'limit'=>255])
            ->create();
    }
}

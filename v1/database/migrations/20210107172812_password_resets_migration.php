<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PasswordResetsMigration extends AbstractMigration
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
        $password_resets = $this->table('password_resets');
        $password_resets->addColumn('user_id', 'integer')
            ->addColumn('expiration_date', 'datetime')
            ->addColumn('token', 'string', ['limit' => 255])
            ->addColumn('created_at', 'datetime')
            ->create();
    }
}

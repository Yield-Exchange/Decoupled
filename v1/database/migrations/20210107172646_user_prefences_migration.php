<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UserPrefencesMigration extends AbstractMigration
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
        $users_preferences = $this->table('user_prefences');
        $users_preferences->addColumn('user_id', 'integer')
            ->addColumn('preference_id', 'integer')
            ->addColumn('value', 'string', ['limit' => 255])
            ->create();
    }
}

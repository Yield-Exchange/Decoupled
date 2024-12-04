<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class LoginActivitiesMigration extends AbstractMigration
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
        $login_activities = $this->table('login_activities');
        $login_activities->addColumn('event_time', 'datetime')
            ->addColumn('activity_type', 'string',['limit'=>255])
            ->addColumn('user_agent', 'string',['limit'=>255])
            ->addColumn('user_id', 'integer')
            ->create();
    }
}

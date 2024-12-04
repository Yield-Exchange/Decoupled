<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class NotificationsMigration extends AbstractMigration
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
        $notifications = $this->table('notifications');
        $notifications->addColumn('type', 'string',['limit'=>50])
            ->addColumn('details', 'string',['limit'=>255])
            ->addColumn('date_sent', 'datetime')
            ->addColumn('user_id', 'integer')
            ->addColumn('sent_by', 'integer')
            ->addColumn('status', 'enum',['values' => ['ACTIVE','SEEN']])
            ->create();
    }
}

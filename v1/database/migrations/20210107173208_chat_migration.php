<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ChatMigration extends AbstractMigration
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
        $chat = $this->table('chat');
        $chat->addColumn('sent_by', 'integer')
            ->addColumn('sent_to', 'integer')
            ->addColumn('message', 'text')
            ->addColumn('contract_id', 'integer')
            ->addColumn('status', 'enum',['values'=>['NEW','SEEN'],'default'=>'NEW'])
            ->addColumn('created_at', 'datetime')
            ->create();
    }
}

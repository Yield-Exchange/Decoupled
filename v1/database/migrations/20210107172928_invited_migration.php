<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InvitedMigration extends AbstractMigration
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
        $invited = $this->table('invited');
        $invited->addColumn('invitation_status', 'string',['limit'=>50])
            ->addColumn('invitation_date', 'datetime')
            ->addColumn('modified_date', 'datetime',['null'=>true])
            ->addColumn('depositor_request_id', 'integer')
            ->addColumn('invited_user_id', 'integer')
            ->addColumn('modified_section', 'string',['limit'=>50,'null'=>true])
            ->addColumn('modified_by', 'integer',['null'=>true])
            ->create();
    }
}

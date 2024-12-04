<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DemographicDataMigration extends AbstractMigration
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
        $demographic_data = $this->table('demographic_data');
        $demographic_data->addColumn('user_id', 'integer')
            ->addColumn('address1', 'string',['limit'=>255])
            ->addColumn('address2', 'string',['limit'=>255])
            ->addColumn('city', 'string',['limit'=>50])
            ->addColumn('province', 'string',['limit'=>50])
            ->addColumn('postal_code', 'string',['limit'=>50])
            ->addColumn('timezone', 'string',['limit'=>50])
            ->addColumn('telephone', 'string',['limit'=>50,'null'=>true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime',['null'=>true])
            ->create();
    }
}

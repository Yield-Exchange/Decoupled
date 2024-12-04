<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RequestStatusUpdation extends AbstractMigration
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
        $depositor_req_archive = $this->table('depositor_requests_archive');
        $depositor_req_archive->changeColumn('request_status', 'enum',['values'=>['WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','NO_OFFERS_RECEIVED']])
            ->save();

        $depositor_req = $this->table('depositor_requests');
        $depositor_req->changeColumn('request_status', 'enum',['values'=>['WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','NO_OFFERS_RECEIVED']])
            ->save();

    }
}

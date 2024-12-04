<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class OffersStatusUpdateEnumWithdrawn extends AbstractMigration
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
        $depositor_req_archive = $this->table('offers_archives');
        $depositor_req_archive->changeColumn('offer_status', 'enum',['values'=>['ACTIVE','EXPIRED','SELECTED','NOT_SELECTED','REQUEST_WITHDRAWN']])
            ->save();

        $depositor_req = $this->table('offers');
        $depositor_req->changeColumn('offer_status', 'enum',['values'=>['ACTIVE','EXPIRED','SELECTED','NOT_SELECTED','REQUEST_WITHDRAWN']])
            ->save();
    }
}

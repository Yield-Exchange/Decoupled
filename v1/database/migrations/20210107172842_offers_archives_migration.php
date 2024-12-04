<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class OffersArchivesMigration extends AbstractMigration
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
        $offers_archives = $this->table('offers_archives');
        $offers_archives->addColumn('invitation_id', 'integer')
            ->addColumn('on_behalf_of', 'string',['limit'=>255])
            ->addColumn('reference_no', 'string',['limit'=>255])
            ->addColumn('maximum_amount', 'double')
            ->addColumn('minimum_amount', 'double')
            ->addColumn('offered_interest_rate', 'float')
            ->addColumn('offer_expiry', 'datetime')
            ->addColumn('product_disclosure_statement', 'string',['limit'=>255,'null'=>true])
            ->addColumn('product_disclosure_url', 'string',['limit'=>255,'null'=>true])
            ->addColumn('special_instructions', 'string',['limit'=>255])
            ->addColumn('offer_status', 'enum',['values'=>['PENDING_CONTRACT','WITHDRAWN','MATURED','ACTIVE','EARLY_REDEMPTION']])
            ->addColumn('created_date', 'datetime',['null'=>true])
            ->addColumn('modified_date', 'datetime',['null'=>true])
            ->addColumn('modified_section', 'string',['limit'=>50,'null'=>true])
            ->addColumn('modified_by', 'integer',['null'=>true])
            ->create();
    }
}

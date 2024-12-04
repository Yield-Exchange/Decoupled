<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DepositorRequestsArchiveMigration extends AbstractMigration
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
        $depositor_requests_archive = $this->table('depositor_requests_archive');
        $depositor_requests_archive->addColumn('reference_no', 'string',['limit'=>255])
            ->addColumn('term_length_type', 'enum',['values'=>['DAYS','MONTHS']])
            ->addColumn('term_length', 'string',['limit'=>50])
            ->addColumn('offered_interest_rate', 'float')
            ->addColumn('lockout_period_days', 'integer')
            ->addColumn('lockout_period_months', 'integer')
            ->addColumn('offer_closing_date_time', 'datetime')
            ->addColumn('amount', 'double')
            ->addColumn('currency', 'string',['limit'=>50])
            ->addColumn('date_of_deposit', 'datetime')
            ->addColumn('compound_frequency', 'string',['limit'=>50])
            ->addColumn('requested_rate', 'float')
            ->addColumn('requested_short_term_credit_rating', 'string',['limit'=>50])
            ->addColumn('requested_deposit_insurance', 'string',['limit'=>50])
            ->addColumn('special_instructions', 'string',['limit'=>255])
            ->addColumn('request_status', 'enum',['values'=>['WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','PENDING_CONTRACT','CONTRACT_WITHDRAWN']])
            ->addColumn('created_date', 'datetime')
            ->addColumn('closed_date', 'datetime',['null'=>true])
            ->addColumn('user_id', 'integer')
            ->addColumn('product_id', 'integer')
            ->addColumn('modified_date', 'datetime',['null'=>true])
            ->addColumn('modified_section', 'string',['limit'=>50,'null'=>true])
            ->addColumn('modified_by', 'integer',['null'=>true])
            ->create();
    }
}

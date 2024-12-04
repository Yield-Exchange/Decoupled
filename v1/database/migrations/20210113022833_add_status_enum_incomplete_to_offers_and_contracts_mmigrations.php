<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddStatusEnumIncompleteToOffersAndContractsMmigrations extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $offers = $this->table('offers');
        $offers->changeColumn('offer_status', 'enum',['values'=>['PENDING_CONTRACT','WITHDRAWN','MATURED','ACTIVE','EARLY_REDEMPTION','CONTRACT_INCOMPLETE']])
            ->save();

        $offers_archives = $this->table('offers_archives');
        $offers_archives->changeColumn('offer_status', 'enum',['values'=>['PENDING_CONTRACT','WITHDRAWN','MATURED','ACTIVE','EARLY_REDEMPTION','CONTRACT_INCOMPLETE']])
            ->save();

        $contracts = $this->table('contracts');
        $contracts->changeColumn('status', 'enum',['values'=>['PENDING_CONTRACT','WITHDRAWN','MATURED','ACTIVE','EARLY_REDEMPTION','INCOMPLETE']])
            ->save();

        $contracts_archive = $this->table('contracts_archive');
        $contracts_archive->changeColumn('status', 'enum',['values'=>['PENDING_CONTRACT','WITHDRAWN','MATURED','ACTIVE','EARLY_REDEMPTION','INCOMPLETE']])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}

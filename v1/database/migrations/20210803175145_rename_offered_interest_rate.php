<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RenameOfferedInterestRate extends AbstractMigration
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
        $this->query("ALTER TABLE `offers` ADD `interest_rate_offer` FLOAT AFTER `offered_interest_rate`;");
        $this->query("ALTER TABLE `offers_archives` ADD `interest_rate_offer` FLOAT AFTER `offered_interest_rate`;");

        $this->query("UPDATE offers SET interest_rate_offer=offered_interest_rate");
        $this->query("UPDATE offers_archives SET interest_rate_offer=offered_interest_rate");

        $this->table('offers')->removeColumn('offered_interest_rate')->save();
        $this->table('offers_archives')->removeColumn('offered_interest_rate')->save();
    }
}

<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DepositRequestOfferClosingDateRename extends AbstractMigration
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
        $this->query("ALTER TABLE `depositor_requests` ADD `request_closing_date_time` DATETIME NULL AFTER `offer_closing_date_time`;");
        $this->query("ALTER TABLE `depositor_requests_archive` ADD `request_closing_date_time` DATETIME NULL AFTER `offer_closing_date_time`;");

        $this->query("UPDATE depositor_requests SET request_closing_date_time=offer_closing_date_time");
        $this->query("UPDATE depositor_requests_archive SET request_closing_date_time=offer_closing_date_time");

        $this->table('depositor_requests')->removeColumn('offer_closing_date_time')->save();
        $this->table('depositor_requests_archive')->removeColumn('offer_closing_date_time')->save();
    }
}

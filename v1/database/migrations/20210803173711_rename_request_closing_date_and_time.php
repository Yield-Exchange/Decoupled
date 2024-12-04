<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RenameRequestClosingDateAndTime extends AbstractMigration
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
        $this->query("ALTER TABLE `depositor_requests` ADD `closing_date_time` DATETIME NULL AFTER `request_closing_date_time`;");
        $this->query("ALTER TABLE `depositor_requests_archive` ADD `closing_date_time` DATETIME NULL AFTER `request_closing_date_time`;");

        $this->query("UPDATE depositor_requests SET closing_date_time=request_closing_date_time");
        $this->query("UPDATE depositor_requests_archive SET closing_date_time=request_closing_date_time");

        $this->table('depositor_requests')->removeColumn('request_closing_date_time')->save();
        $this->table('depositor_requests_archive')->removeColumn('request_closing_date_time')->save();
    }
}

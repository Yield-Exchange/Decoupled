<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RenameOfferExpiryDateAndTime extends AbstractMigration
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
        $this->query("ALTER TABLE `offers` ADD `rate_held_until` DATETIME NULL AFTER `offer_expiry`;");
        $this->query("ALTER TABLE `offers_archives` ADD `rate_held_until` DATETIME NULL AFTER `offer_expiry`;");

        $this->query("UPDATE offers SET rate_held_until=offer_expiry");
        $this->query("UPDATE offers_archives SET rate_held_until=offer_expiry");

        $this->table('offers')->removeColumn('offer_expiry')->save();
        $this->table('offers_archives')->removeColumn('offer_expiry')->save();
    }
}

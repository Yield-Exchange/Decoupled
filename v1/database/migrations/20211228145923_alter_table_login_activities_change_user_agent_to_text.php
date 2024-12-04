<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterTableLoginActivitiesChangeUserAgentToText extends AbstractMigration
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
        $this->query("ALTER TABLE `login_activities` CHANGE `user_agent` `user_agent` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
    }
}

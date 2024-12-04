<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdatePinColumnToLongLengthMigration extends AbstractMigration
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
    /**
     * Migrate Up.
     */
    public function up()
    {
        $users = $this->table('authentication');
        $users->changeColumn('pin', 'string', ['limit' => 255])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}

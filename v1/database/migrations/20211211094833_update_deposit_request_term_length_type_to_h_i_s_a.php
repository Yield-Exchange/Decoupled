<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateDepositRequestTermLengthTypeToHISA extends AbstractMigration
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
        $depositor_requests = $this->table('depositor_requests');
        $depositor_requests->changeColumn('term_length_type', 'enum',['values'=>['DAYS','MONTHS','HISA','NONE']])
            ->save();

        $depositor_requests_archives = $this->table('depositor_requests_archive');
        $depositor_requests_archives->changeColumn('term_length_type', 'enum',['values'=>['DAYS','MONTHS','HISA','NONE']])
            ->save();

        $this->query("UPDATE `depositor_requests` SET term_length_type='HISA' WHERE term_length_type='NONE'");
        $this->query("UPDATE `depositor_requests_archive` SET term_length_type='HISA' WHERE term_length_type='NONE'");

        $depositor_requests = $this->table('depositor_requests');
        $depositor_requests->changeColumn('term_length_type', 'enum',['values'=>['DAYS','MONTHS','HISA']])
            ->save();

        $depositor_requests_archives = $this->table('depositor_requests_archive');
        $depositor_requests_archives->changeColumn('term_length_type', 'enum',['values'=>['DAYS','MONTHS','HISA']])
            ->save();
    }
}

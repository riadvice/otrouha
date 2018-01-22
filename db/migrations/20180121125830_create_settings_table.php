<?php


use Phinx\Migration\AbstractMigration;

class CreateSettingsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $table = $this->table('settings');
        $table->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('value', 'text')
            ->addColumn('created_on', 'datetime', ['default' => '1000-01-01 00:00:00'])
            ->addColumn('updated_on', 'datetime', ['default' => '1000-01-01 00:00:00'])->setOptions([
                'encoding'  => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->create();
    }

    public function down(): void
    {
        $table = $this->table('settings');
        $table->drop();
    }
}

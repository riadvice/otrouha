<?php

/**
 * Copyright (C) 2018 RIADVICE SUARL <otrouha@riadvice.tn>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
use Phinx\Migration\AbstractMigration;

class CreateUsersSessionsTable extends AbstractMigration
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

    /**
     * {@inheritdoc}
     */
    public function up(): void
    {
        $table = $this->table('users_sessions');
        $table->addColumn('session_id', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('data', 'text', ['null' => true])
            ->addColumn('ip', 'string', ['limit' => 45, 'null' => true])
            ->addColumn('agent', 'string', ['limit' => 300, 'null' => true])
            ->addColumn('stamp', 'integer', ['null' => true])
            ->setOptions([
                'encoding'    => 'utf8mb4',
                'collation'   => 'utf8mb4_unicode_ci',
                'id'          => false,
                'primary_key' => 'session_id'
            ])
            ->create();
    }

    public function down(): void
    {
        $table = $this->table('users_sessions');
        $table->drop();
    }
}

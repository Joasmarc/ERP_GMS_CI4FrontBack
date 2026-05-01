<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'position' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'group' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'state' => [
                'type'       => 'ENUM',
                'constraint' => ['ACTIVO', 'INACTIVO'],
                'default'    => 'ACTIVO',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('activities');
    }

    public function down()
    {
        $this->forge->dropTable('activities');
    }
}

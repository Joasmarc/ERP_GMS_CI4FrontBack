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
            'posicion' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'grupo' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'estado' => [
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

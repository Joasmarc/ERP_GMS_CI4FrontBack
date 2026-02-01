<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    // 1.0 Crear tabla de usuarios
    public function up()
    {
        // 1.1 Definir estructura de la tabla
        $this->forge->addField([
            // 1.2 Campo ID autoincremental
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            // 1.3 Campo nombre
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            // 1.4 Campo email único
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true
            ],
            // 1.5 Campo PIN de seguridad
            'pin' => [
                'type' => 'VARCHAR',
                'constraint' => 4
            ],
            // 1.6 Timestamp de creación
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            // 1.7 Timestamp de actualización
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // 1.8 Definir clave primaria
        $this->forge->addKey('id', true);

        // 1.9 Crear tabla
        $this->forge->createTable('users');
    }

    // 2.0 Eliminar tabla de usuarios
    public function down()
    {
        // 2.1 Eliminar tabla si existe
        $this->forge->dropTable('users');
    }
}

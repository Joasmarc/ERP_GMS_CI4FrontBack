<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDispatchAdviceTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'client' => [
                'type'       => 'VARCHAR',
                'constraint' => '75',
                'null'       => true,
            ],
            'nit' => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
                'null'       => true,
            ],
            'adress' => [
                'type'       => 'VARCHAR',
                'constraint' => '105',
                'null'       => true,
            ],
            'sequence' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'city' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'transfer_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '55',
                'null'       => true,
            ],
            'observation' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'null'       => true,
            ],
            'dispatcher' => [
                'type'       => 'VARCHAR',
                'constraint' => '55',
                'null'       => true,
            ],
            'did_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('dispatch_advice');
    }

    public function down()
    {
        $this->forge->dropTable('dispatch_advice', true);
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDispatchAdviceItemsTable extends Migration
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
            'id_base' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'reference' => [
                'type'       => 'VARCHAR',
                'constraint' => '75',
                'null'       => true,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '75',
                'null'       => true,
            ],
            'batch' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'expiration_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'quiantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->createTable('dispatch_advice_items');
    }

    public function down()
    {
        $this->forge->dropTable('dispatch_advice_items', true);
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentsTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '55',
                'null'       => false,
            ],
            'path' => [
                'type'       => 'VARCHAR',
                'constraint' => '350',
                'null'       => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('documents');
    }

    public function down()
    {
        $this->forge->dropTable('documents', true);
    }
}

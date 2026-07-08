<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePicturesTable extends Migration
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
            'path' => [
                'type'       => 'VARCHAR',
                'constraint' => '350',
                'null'       => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('pictures');
    }

    public function down()
    {
        $this->forge->dropTable('pictures', true);
    }
}

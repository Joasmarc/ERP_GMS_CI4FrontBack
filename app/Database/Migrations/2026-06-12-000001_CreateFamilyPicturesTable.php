<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFamilyPicturesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'family_id' => [
                'type'       => 'INT',
                'unsigned'   => false,
                'null'       => false,
            ],
            'picture_id' => [
                'type'       => 'INT',
                'unsigned'   => false,
                'null'       => false,
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
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        
        // Add foreign keys matching the signed types of parent tables
        $this->forge->addForeignKey('family_id', 'families', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('picture_id', 'pictures', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('family_pictures');
    }

    public function down()
    {
        $this->forge->dropTable('family_pictures');
    }
}

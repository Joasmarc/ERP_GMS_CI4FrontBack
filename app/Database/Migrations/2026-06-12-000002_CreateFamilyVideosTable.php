<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFamilyVideosTable extends Migration
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
            'video_id' => [
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
        $this->forge->addForeignKey('video_id', 'videos', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('family_videos');
    }

    public function down()
    {
        $this->forge->dropTable('family_videos');
    }
}

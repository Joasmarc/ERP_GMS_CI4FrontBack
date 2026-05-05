<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFamilyDocumentsTable extends Migration
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
                'unsigned'   => true,
                'null'       => false,
            ],
            'document_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
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
        
        // Add foreign keys
        // Assuming 'families' and 'documents' tables exist and 'id' is unsigned INT
        $this->forge->addForeignKey('family_id', 'families', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('document_id', 'documents', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('family_documents');
    }

    public function down()
    {
        $this->forge->dropTable('family_documents');
    }
}

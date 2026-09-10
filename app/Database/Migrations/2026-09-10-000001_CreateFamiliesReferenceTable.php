<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFamiliesReferenceTable extends Migration
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
            'id_family' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['ACTIVE', 'INACTIVE'],
                'default'    => 'ACTIVE',
            ],
            'reference' => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
                'null'       => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_family');
        $this->forge->addKey('reference');
        $this->forge->createTable('families_reference', true);
    }

    public function down()
    {
        $this->forge->dropTable('families_reference', true);
    }
}

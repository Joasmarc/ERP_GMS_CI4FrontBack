<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeToDispatchAdviceTable extends Migration
{
    public function up()
    {
        $fields = [
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['INTERNO', 'INGRESO', 'AJUSTE', 'REMISION'],
                'default'    => 'INTERNO',
                'null'       => false,
                'after'      => 'sequence',
            ],
        ];

        $this->forge->addColumn('dispatch_advice', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('dispatch_advice', 'type');
    }
}

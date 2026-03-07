<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterProductIdToVarchar extends Migration
{
    // 1.0 Modificar tabla pictures_products
    public function up()
    {
        // 1.1 Eliminar la clave foránea si existe (esto depende de la bd actual, asumiendo que dbForge puede hacer el modify column directo)
        // 1.2 Alterar la columna id_product a VARCHAR(50)
        $fields = [
            'id_product' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
        ];

        $this->forge->modifyColumn('pictures_products', $fields);
        
        // 1.3 Modificamos tabien videos y documents por si acaso
        $this->forge->modifyColumn('videos_products', $fields);
        $this->forge->modifyColumn('documents_products', $fields);
    }

    // 2.0 Revertir la modificación de id_product
    public function down()
    {
        // 2.1 Volver a tipo INT si se hace rollback
        $fields = [
            'id_product' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // Suponiendo que era unsigned INT
            ],
        ];

        $this->forge->modifyColumn('pictures_products', $fields);
        $this->forge->modifyColumn('videos_products', $fields);
        $this->forge->modifyColumn('documents_products', $fields);
    }
}

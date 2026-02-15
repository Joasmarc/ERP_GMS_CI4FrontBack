<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentsProducts extends Model
{
    // 1.0 Configurar tabla pivote
    protected $table = 'documents_products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'id_document',
        'id_product'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'id_document' => 'int',
        'id_product' => 'int'
    ];

    // 4.0 Validación de datos
    protected $validationRules = [
        'id_document' => 'required|is_natural_no_zero',
        'id_product' => 'required|is_natural_no_zero'
    ];

    protected $validationMessages = [
        'id_document' => [
            'required' => 'El ID del documento es obligatorio',
            'is_natural_no_zero' => 'El ID del documento debe ser un número válido'
        ],
        'id_product' => [
            'required' => 'El ID del producto es obligatorio',
            'is_natural_no_zero' => 'El ID del producto debe ser un número válido'
        ]
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class PicturesProducts extends Model
{
    // 1.0 Configurar tabla pivote
    protected $table = 'pictures_products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'id_product',
        'id_picture'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'id_product' => 'int',
        'id_picture' => 'int'
    ];

    // 4.0 Validación de datos
    protected $validationRules = [
        'id_product' => 'is_natural_no_zero',
        'id_picture' => 'is_natural_no_zero'
    ];

    protected $validationMessages = [
        'id_product' => [
            'is_natural_no_zero' => 'El ID del producto debe ser un número válido'
        ],
        'id_picture' => [
            'is_natural_no_zero' => 'El ID de la imagen debe ser un número válido'
        ]
    ];
}

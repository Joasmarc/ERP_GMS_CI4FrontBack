<?php

namespace App\Models;

use CodeIgniter\Model;

class VideosProducts extends Model
{
    // 1.0 Configurar tabla pivote
    protected $table = 'videos_product';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'id_product',
        'id_video'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'id_product' => 'int',
        'id_video' => 'int'
    ];

    // 4.0 Validación de datos
    protected $validationRules = [
        'id_product' => 'required|is_natural_no_zero',
        'id_video' => 'required|is_natural_no_zero'
    ];

    protected $validationMessages = [
        'id_product' => [
            'required' => 'El ID del producto es obligatorio',
            'is_natural_no_zero' => 'El ID del producto debe ser un número válido'
        ],
        'id_video' => [
            'required' => 'El ID del video es obligatorio',
            'is_natural_no_zero' => 'El ID del video debe ser un número válido'
        ]
    ];
}

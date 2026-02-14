<?php

namespace App\Models;

use CodeIgniter\Model;

class Categories extends Model
{
    // 1.0 Configurar tabla de categorías
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'nombre'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'nombre' => 'string'
    ];

    // 4.0 Validación de datos
    protected $validationRules = [
        'nombre' => 'required|string|min_length[3]|max_length[250]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre de la categoría es requerido',
            'min_length' => 'El nombre debe tener al menos 3 caracteres'
        ]
    ];
}

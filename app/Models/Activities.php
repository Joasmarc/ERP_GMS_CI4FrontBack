<?php

namespace App\Models;

use CodeIgniter\Model;

class Activities extends Model
{
    // 1.0 Configurar tabla
    protected $table = 'activities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'position',
        'description',
        'group',
        'state'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'position' => 'int',
        'description' => 'string',
        'group' => 'int',
        'state' => 'string'
    ];

    // 4.0 Habilitar timestamps
    protected $useTimestamps = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'position' => 'required|is_natural',
        'description' => 'required|string|max_length[255]',
        'group' => 'required|is_natural',
        'state' => 'required|in_list[ACTIVO,INACTIVO]'
    ];
}

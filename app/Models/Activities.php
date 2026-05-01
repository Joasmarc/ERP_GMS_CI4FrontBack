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
        'posicion',
        'descripcion',
        'grupo',
        'estado'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'posicion' => 'int',
        'descripcion' => 'string',
        'grupo' => 'int',
        'estado' => 'string'
    ];

    // 4.0 Habilitar timestamps
    protected $useTimestamps = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'posicion' => 'required|is_natural',
        'descripcion' => 'required|string|max_length[255]',
        'grupo' => 'required|is_natural',
        'estado' => 'required|in_list[ACTIVO,INACTIVO]'
    ];
}

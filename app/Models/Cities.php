<?php

namespace App\Models;

use CodeIgniter\Model;

class Cities extends Model
{
    // 1.0 Configurar tabla de ciudades
    protected $table = 'cities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'name',
        'code',
        'state',
        'department_id'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'name' => 'string',
        'code' => 'string',
        'state' => 'string',
        'department_id' => 'int'
    ];

    // 4.0 Configurar Timestamps (deshabilitado por falta de columnas en BD)
    protected $useTimestamps = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'name' => 'required|string|max_length[55]',
        'code' => 'required|string|max_length[10]',
        'state' => 'required|in_list[ACTIVO,INACTIVO]',
        'department_id' => 'permit_empty|integer'
    ];
}

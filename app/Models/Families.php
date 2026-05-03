<?php

namespace App\Models;

use CodeIgniter\Model;

class Families extends Model
{
    // 1.0 Configurar tabla de familias
    protected $table = 'families';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'keyword',
        'state',
        'img_path'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'keyword' => 'string',
        'state' => 'string',
        'img_path' => '?string'
    ];

    // 4.0 Configurar fechas y eliminación suave
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // 5.0 Validación de datos
    protected $validationRules = [
        'keyword' => 'required|string|max_length[250]',
        'state' => 'in_list[ACTIVO,INACTIVO]',
        'img_path' => 'permit_empty|string|max_length[250]'
    ];

    protected $validationMessages = [
        'keyword' => [
            'required' => 'La palabra clave es requerida',
            'max_length' => 'La palabra clave no puede exceder los 250 caracteres'
        ],
        'state' => [
            'in_list' => 'El estado debe ser ACTIVO o INACTIVO'
        ]
    ];
}

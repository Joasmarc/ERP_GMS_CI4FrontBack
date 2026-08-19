<?php

namespace App\Models;

use CodeIgniter\Model;

class Documents extends Model
{
    // 1.0 Configurar tabla de documentos
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'name',
        'path'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'name' => 'string',
        'path' => 'string'
    ];

    // 4.0 Configurar fechas y eliminación suave
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // 5.0 Validación de datos
    protected $validationRules = [
        'name' => 'required|string|max_length[55]',
        'path' => 'required|string|max_length[350]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'El nombre del documento es obligatorio',
            'max_length' => 'El nombre no puede exceder 55 caracteres'
        ],
        'path' => [
            'required' => 'La ruta del documento es obligatoria',
            'max_length' => 'La ruta no puede exceder 350 caracteres'
        ]
    ];
}

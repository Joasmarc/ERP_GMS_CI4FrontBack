<?php

namespace App\Models;

use CodeIgniter\Model;

class Videos extends Model
{
    // 1.0 Configurar tabla de videos
    protected $table = 'videos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'path'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
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
        'path' => 'required|string|max_length[350]'
    ];

    protected $validationMessages = [
        'path' => [
            'required' => 'La ruta del video es obligatoria',
            'max_length' => 'La ruta no puede exceder 350 caracteres'
        ]
    ];
}

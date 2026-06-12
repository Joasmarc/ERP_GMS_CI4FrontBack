<?php

namespace App\Models;

use CodeIgniter\Model;

class FamilyVideos extends Model
{
    // 1.0 Configurar tabla pivote
    protected $table = 'family_videos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'family_id',
        'video_id'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'family_id' => 'int',
        'video_id' => 'int'
    ];

    // 4.0 Configurar fechas y eliminación suave
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // 5.0 Validación de datos
    protected $validationRules = [
        'family_id' => 'required|is_natural_no_zero',
        'video_id' => 'required|is_natural_no_zero'
    ];

    protected $validationMessages = [
        'family_id' => [
            'required' => 'El ID de la familia es requerido',
            'is_natural_no_zero' => 'El ID de la familia debe ser un número válido'
        ],
        'video_id' => [
            'required' => 'El ID del video es requerido',
            'is_natural_no_zero' => 'El ID del video debe ser un número válido'
        ]
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class FamilyDocuments extends Model
{
    // 1.0 Configurar tabla pivote
    protected $table = 'family_documents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'family_id',
        'document_id'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'family_id' => 'int',
        'document_id' => 'int'
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
        'document_id' => 'required|is_natural_no_zero'
    ];

    protected $validationMessages = [
        'family_id' => [
            'required' => 'El ID de la familia es requerido',
            'is_natural_no_zero' => 'El ID de la familia debe ser un número válido'
        ],
        'document_id' => [
            'required' => 'El ID del documento es requerido',
            'is_natural_no_zero' => 'El ID del documento debe ser un número válido'
        ]
    ];
}

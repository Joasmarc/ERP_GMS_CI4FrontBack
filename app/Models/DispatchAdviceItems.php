<?php

namespace App\Models;

use CodeIgniter\Model;

class DispatchAdviceItems extends Model
{
    // 1.0 Configurar tabla
    protected $table = 'dispatch_advice_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'id_base',
        'reference',
        'description',
        'batch',
        'expiration_date',
        'quiantity', // Escrito igual que en la columna de la base de datos
        'deleted_at'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id'              => 'int',
        'id_base'         => 'int',
        'reference'       => 'string',
        'description'     => 'string',
        'batch'           => 'string',
        'expiration_date' => 'datetime',
        'quiantity'       => 'int',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'deleted_at'      => 'int'
    ];

    // 4.0 Configurar Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    // Como 'deleted_at' es de tipo INT, desactivamos el soft deletes nativo
    protected $useSoftDeletes = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'id_base'         => 'permit_empty|integer',
        'reference'       => 'required|string|max_length[75]',
        'description'     => 'required|string|max_length[75]',
        'batch'           => 'required|string|max_length[10]',
        'expiration_date' => 'permit_empty|valid_date',
        'quiantity'       => 'required|integer'
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class DispatchAdvices extends Model
{
    // 1.0 Configurar tabla
    protected $table = 'dispatch_advice';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'client',
        'nit',
        'adress',
        'sequence',
        'city',
        'type',
        'transfer_code',
        'observation',
        'dispatcher',
        'did_user',
        'deleted_at'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'client' => 'string',
        'nit' => 'string',
        'adress' => 'string',
        'sequence' => 'int',
        'city' => 'int',
        'type' => 'string',
        'transfer_code' => 'string',
        'observation' => 'string',
        'dispatcher' => 'string',
        'did_user' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'int'
    ];

    // 4.0 Configurar Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    // Nota: Como 'deleted_at' es de tipo INT y los otros de tipo DATETIME,
    // el borrado lógico nativo ($useSoftDeletes = true) lanzará error al
    // intentar guardar una fecha en formato string. Se recomienda manejarlo
    // manualmente actualizando el campo, o cambiar el tipo a DATETIME en la DB.
    protected $useSoftDeletes = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'client'        => 'permit_empty|string|max_length[75]',
        'nit'           => 'permit_empty|string|max_length[25]',
        'adress'        => 'permit_empty|string|max_length[105]',
        'sequence'      => 'required|integer',
        'city'          => 'required|integer',
        'type'          => 'permit_empty|in_list[INTERNO,INGRESO,AJUSTE,REMISION]',
        'transfer_code' => 'permit_empty|string|max_length[55]',
        'observation'   => 'permit_empty|string|max_length[250]',
        'dispatcher'    => 'permit_empty|string|max_length[55]',
        'did_user'      => 'permit_empty|integer'
    ];
}

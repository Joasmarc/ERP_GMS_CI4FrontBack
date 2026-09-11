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
        'id'            => 'int',
        'client'        => '?string',
        'nit'           => '?string',
        'adress'        => '?string',
        'sequence'      => 'int',
        'city'          => '?int',
        'type'          => 'string',
        'transfer_code' => '?string',
        'observation'   => '?string',
        'dispatcher'    => '?int',
        'did_user'      => 'int',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => '?int'
    ];

    // 4.0 Configurar Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    // Como 'deleted_at' es de tipo INT, desactivamos el soft deletes nativo
    protected $useSoftDeletes = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'client'        => 'permit_empty|string|max_length[75]',
        'nit'           => 'permit_empty|string|max_length[25]',
        'adress'        => 'permit_empty|string|max_length[105]',
        'sequence'      => 'required|integer',
        'city'          => 'permit_empty|integer',
        'type'          => 'permit_empty|in_list[INTERNO,INGRESO,AJUSTE,REMISION,EXTERNO]',
        'transfer_code' => 'permit_empty|string|max_length[55]',
        'observation'   => 'permit_empty|string|max_length[250]',
        'dispatcher'    => 'permit_empty|integer',
        'did_user'      => 'permit_empty|integer'
    ];
}

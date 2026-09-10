<?php

namespace App\Models;

use CodeIgniter\Model;

class WarehousesBase extends Model
{
    // 1.0 Configurar tabla
    protected $table = 'warehouses_base';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'name',
        'adress',
        'state',
        'id_user',
        'deleted_at'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id'         => 'int',
        'name'       => 'string',
        'adress'     => 'string',
        'state'      => 'string',
        'id_user'    => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'int'
    ];

    // 4.0 Configurar Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Como 'deleted_at' es de tipo INT, desactivamos el soft deletes nativo
    protected $useSoftDeletes = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'name'    => 'required|string|max_length[55]',
        'adress'  => 'required|string|max_length[55]',
        'state'   => 'permit_empty|in_list[ACTIVE,INACTIVE]',
        'id_user' => 'permit_empty|integer'
    ];
}

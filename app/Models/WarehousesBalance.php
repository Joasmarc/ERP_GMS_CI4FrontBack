<?php

namespace App\Models;

use CodeIgniter\Model;

class WarehousesBalance extends Model
{
    // 1.0 Configurar tabla
    protected $table = 'warehouses_balance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'id_warehouse',
        'name_item',
        'quantity',
        'deleted_at'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id'           => 'int',
        'id_warehouse' => 'int',
        'name_item'    => 'string',
        'quantity'     => 'int',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'deleted_at'   => 'int'
    ];

    // 4.0 Configurar Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Como 'deleted_at' es de tipo INT, desactivamos el soft deletes nativo
    protected $useSoftDeletes = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'id_warehouse' => 'permit_empty|integer',
        'name_item'    => 'required|string|max_length[85]',
        'quantity'     => 'permit_empty|integer'
    ];
}

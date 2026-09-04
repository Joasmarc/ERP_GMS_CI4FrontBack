<?php

namespace App\Models;

use CodeIgniter\Model;

class WarehousesTransferBase extends Model
{
    // 1.0 Configurar tabla
    protected $table = 'warehouses_transfer_base';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'id_warehouse_send',
        'id_warehouse_receives',
        'id_user',
        'deleted_at'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id'                    => 'int',
        'id_warehouse_send'     => 'int',
        'id_warehouse_receives' => 'int',
        'id_user'               => 'int',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'deleted_at'            => 'int'
    ];

    // 4.0 Configurar Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Como 'deleted_at' es de tipo INT, desactivamos el soft deletes nativo
    protected $useSoftDeletes = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'id_warehouse_send'     => 'required|integer',
        'id_warehouse_receives' => 'required|integer',
        'id_user'               => 'permit_empty|integer'
    ];
}

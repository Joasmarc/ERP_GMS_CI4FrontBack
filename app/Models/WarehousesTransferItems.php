<?php

namespace App\Models;

use CodeIgniter\Model;

class WarehousesTransferItems extends Model
{
    // 1.0 Configurar tabla
    protected $table = 'warehouses_transfer_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'id_warehouse_transfer',
        'id_family',
        'quantity'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id'                    => 'int',
        'id_warehouse_transfer' => '?int',
        'id_family'             => '?int',
        'quantity'              => '?int'
    ];

    // Timestamps no están definidos en esta tabla
    protected $useTimestamps = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'id_warehouse_transfer' => 'permit_empty|integer',
        'id_family'             => 'permit_empty|integer',
        'quantity'              => 'permit_empty|integer'
    ];
}

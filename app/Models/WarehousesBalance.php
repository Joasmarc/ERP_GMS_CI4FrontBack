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
        'id_family',
        'quantity',
        'lot',
        'expiration_date',
        'deleted_at'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id'              => 'int',
        'id_warehouse'    => 'int',
        'id_family'       => '?int',
        'quantity'        => 'int',
        'lot'             => '?string',
        'expiration_date' => '?datetime',
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
        'id_warehouse'    => 'permit_empty|integer',
        'id_family'       => 'permit_empty|integer',
        'quantity'        => 'permit_empty|integer',
        'lot'             => 'permit_empty|string|max_length[25]',
        'expiration_date' => 'permit_empty|valid_date'
    ];

    /**
     * Obtener el balance de una bodega con los datos de la familia asociada
     *
     * @param int $warehouseId
     * @return array
     */
    public function getBalanceWithFamilies(int $warehouseId): array
    {
        return $this->select('warehouses_balance.*, families.keyword as family_name, families.keyword as name_item')
            ->join('families', 'families.id = warehouses_balance.id_family AND families.deleted_at IS NULL', 'left')
            ->where('warehouses_balance.id_warehouse', $warehouseId)
            ->where('warehouses_balance.deleted_at IS NULL')
            ->orderBy('warehouses_balance.id', 'DESC')
            ->findAll();
    }
}

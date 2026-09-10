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
        'id_user_admin',
        'id_client',
        'deleted_at'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id'            => 'int',
        'name'          => 'string',
        'adress'        => 'string',
        'state'         => 'string',
        'id_user_admin' => '?int',
        'id_client'     => '?int',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'int'
    ];

    // 4.0 Configurar Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Como 'deleted_at' es de tipo INT, desactivamos el soft deletes nativo
    protected $useSoftDeletes = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'name'          => 'required|string|max_length[55]',
        'adress'        => 'required|string|max_length[55]',
        'state'         => 'permit_empty|in_list[ACTIVE,INACTIVE]',
        'id_user_admin' => 'permit_empty|integer',
        'id_client'     => 'permit_empty|integer'
    ];

    /**
     * Obtener listado de bodegas con datos de usuario administrador y cliente titular
     *
     * @return array
     */
    public function getWarehousesWithDetails(): array
    {
        return $this->select('warehouses_base.id, warehouses_base.name, warehouses_base.adress, warehouses_base.state, warehouses_base.id_user_admin, warehouses_base.id_user_admin as admin, warehouses_base.id_client, warehouses_base.id_client as titular, warehouses_base.created_at, warehouses_base.updated_at, u.name as admin_name, u.email as admin_email, c.nombre_cliente as client_name, COUNT(b.id) as total_items')
            ->join('warehouses_balance b', 'b.id_warehouse = warehouses_base.id AND b.deleted_at IS NULL AND b.quantity > 0', 'left')
            ->join('users u', 'u.id = warehouses_base.id_user_admin', 'left')
            ->join('clients c', 'c.id = warehouses_base.id_client', 'left')
            ->where('warehouses_base.deleted_at IS NULL')
            ->groupBy('warehouses_base.id, warehouses_base.name, warehouses_base.adress, warehouses_base.state, warehouses_base.id_user_admin, warehouses_base.id_client, warehouses_base.created_at, warehouses_base.updated_at, u.name, u.email, c.nombre_cliente')
            ->orderBy('warehouses_base.id', 'ASC')
            ->findAll();
    }
}

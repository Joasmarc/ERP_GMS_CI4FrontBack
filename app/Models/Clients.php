<?php

namespace App\Models;

use CodeIgniter\Model;

class Clients extends Model
{
    // 1.0 Configurar tabla de clientes
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // 2.0 Configurar campos permitidos
    protected $allowedFields    = [
        'nombre_cliente',
        'tipo_documento',
        'numero_documento',
        'telefono_cliente',
        'correo_cliente',
        'direccion_cliente',
        'city'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id'                => 'int',
        'nombre_cliente'    => 'string',
        'tipo_documento'    => '?string',
        'numero_documento'  => 'string',
        'telefono_cliente'  => '?string',
        'correo_cliente'    => '?string',
        'direccion_cliente' => '?string',
        'city'              => '?int',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => '?datetime'
    ];

    // 4.0 Configurar fechas y eliminación
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // 5.0 Validación de datos
    protected $validationRules = [
        'nombre_cliente'    => 'permit_empty|string|max_length[255]',
        'tipo_documento'    => 'permit_empty|string|max_length[50]',
        'numero_documento'  => 'permit_empty|string|max_length[100]',
        'telefono_cliente'  => 'permit_empty|string|max_length[50]',
        'correo_cliente'    => 'permit_empty|valid_email|max_length[255]',
        'direccion_cliente' => 'permit_empty|string|max_length[255]',
        'city'              => 'permit_empty|integer'
    ];
}

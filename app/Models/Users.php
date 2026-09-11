<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    // 1.0 Configurar tabla de usuarios
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'name',
        'email',
        'pin',
        'credentials',
        'gender',
        'city',
        'dni',
        'adress',
        'phone'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id'          => 'int',
        'name'        => 'string',
        'email'       => 'string',
        'pin'         => 'string',
        'credentials' => '?string',
        'gender'      => '?string',
        'city'        => '?int',
        'dni'         => 'int',
        'adress'      => 'string',
        'phone'       => 'int',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime'
    ];

    // 4.0 Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // 4.5 Configurar callbacks de modelo
    protected $beforeInsert = ['hashPin'];
    protected $beforeUpdate = ['hashPin'];

    // 5.0 Validación de datos
    protected $validationRules = [
        'name'        => 'permit_empty|string|min_length[3]|max_length[100]',
        'email'       => 'permit_empty|valid_email|max_length[100]',
        'pin'         => 'permit_empty|string|max_length[255]',
        'credentials' => 'permit_empty|string|max_length[55]',
        'gender'      => 'permit_empty|in_list[male,female]',
        'city'        => 'permit_empty|integer',
        'dni'         => 'permit_empty|integer',
        'adress'      => 'permit_empty|string|max_length[85]',
        'phone'       => 'permit_empty|integer'
    ];

    // 6.0 Métodos callback de CodeIgniter
    protected function hashPin(array $DATA)
    {
        // 6.1 Verificar si el pin existe en los datos
        if (isset($DATA['data']['pin'])) {
            $pin = $DATA['data']['pin'];
            // Evitar re-hashear si ya es un hash de BCRYPT
            if (!(is_string($pin) && str_starts_with($pin, '$2y$') && strlen($pin) === 60)) {
                $DATA['data']['pin'] = password_hash($pin, PASSWORD_BCRYPT);
            }
        }
        // 6.2 Retornar los datos procesados
        return $DATA;
    }
}

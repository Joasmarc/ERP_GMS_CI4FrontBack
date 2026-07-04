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
        'gender'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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
        'name' => 'required|string|min_length[3]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'pin' => 'required|numeric|exact_length[4]',
        'credentials' => 'permit_empty|string|max_length[55]',
        'gender' => 'permit_empty|in_list[male,female]'
    ];

    // 6.0 Métodos callback de CodeIgniter
    protected function hashPin(array $DATA)
    {
        // 6.1 Verificar si el pin existe en los datos
        if (isset($DATA['data']['pin'])) {
            $DATA['data']['pin'] = password_hash($DATA['data']['pin'], PASSWORD_BCRYPT);
        }
        // 6.2 Retornar los datos procesados
        return $DATA;
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    // 1.0 Configurar tabla de usuarios
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'name',
        'email',
        'pin'
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

    // 5.0 Validación de datos
    protected $validationRules = [
        'name' => 'required|string|min_length[3]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'pin' => 'required|numeric|exact_length[4]'
    ];
}

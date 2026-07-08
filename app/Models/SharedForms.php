<?php

namespace App\Models;

use CodeIgniter\Model;

class SharedForms extends Model
{
    protected $table            = 'shared_forms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'token',
        'numero_identificacion',
        'contraparte_type',
        'person_type',
        'status',
        'expires_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

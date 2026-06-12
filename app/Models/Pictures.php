<?php

namespace App\Models;

use CodeIgniter\Model;

class Pictures extends Model
{
    // 1.0 Configurar tabla de imágenes
    protected $table = 'pictures';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'path'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'path' => 'string'
    ];

    // 4.0 Deshabilitar timestamps ya que no existen en la definición de la tabla
    protected $useTimestamps = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'path' => 'required|string|max_length[350]'
    ];

    protected $validationMessages = [
        'path' => [
            'required' => 'La ruta de la imagen es obligatoria',
            'max_length' => 'La ruta no puede exceder 350 caracteres'
        ]
    ];
}

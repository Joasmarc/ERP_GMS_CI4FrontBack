<?php

namespace App\Models;

use CodeIgniter\Model;

class Products extends Model
{
    // 1.0 Configurar tabla de productos
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // 2.0 Configurar campos permitidos
    protected $allowedFields = [
        'nombre',
        'id_categoria',
        'presentacion',
        'id_marca',
        'observacion'
    ];

    // 3.0 Configurar tipos de datos
    protected $castings = [
        'id' => 'int',
        'nombre' => 'string',
        'id_categoria' => 'int',
        'presentacion' => 'string',
        'id_marca' => 'int',
        'observacion' => 'string',
        'created_at' => 'date',
        'updated_at' => 'date'
    ];

    // 4.0 Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'date';

    // 5.0 Validación de datos
    protected $validationRules = [
        'nombre' => 'required|string|min_length[3]|max_length[250]',
        'id_categoria' => 'is_natural_no_zero',
        'presentacion' => 'string|max_length[250]',
        'id_marca' => 'is_natural_no_zero',
        'observacion' => 'string|max_length[250]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del producto es requerido',
            'min_length' => 'El nombre debe tener al menos 3 caracteres'
        ],
        'id_categoria' => [
            'is_natural_no_zero' => 'Debe seleccionar una categoría válida'
        ],
        'id_marca' => [
            'is_natural_no_zero' => 'Debe seleccionar una marca válida'
        ]
    ];

    // 6.0 Relaciones (si es necesario usarlas)
    public function getCategory()
    {
        return $this->belongsTo(Category::class, 'id_categoria', 'id');
    }

    public function getBrand()
    {
        return $this->belongsTo(Brand::class, 'id_marca', 'id');
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class FamiliesReference extends Model
{
    // 1.0 Configurar tabla
    protected $table            = 'families_reference';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // 2.0 Configurar campos permitidos
    protected $allowedFields    = [
        'id_family',
        'status',
        'reference'
    ];

    // 3.0 Configurar tipos de datos (Casting)
    protected $castings = [
        'id'        => 'int',
        'id_family' => '?int',
        'status'    => 'string',
        'reference' => 'string'
    ];

    // 4.0 Configurar Timestamps y Soft Deletes (deshabilitados al no existir columnas en la tabla)
    protected $useTimestamps  = false;
    protected $useSoftDeletes = false;

    // 5.0 Validación de datos
    protected $validationRules = [
        'id_family' => 'permit_empty',
        'status'    => 'permit_empty|in_list[ACTIVE,INACTIVE]',
        'reference' => 'required|max_length[25]'
    ];

    protected $validationMessages = [
        'status' => [
            'in_list' => 'El estado debe ser ACTIVE o INACTIVE'
        ],
        'reference' => [
            'required'   => 'La referencia es requerida',
            'max_length' => 'La referencia no puede superar los 25 caracteres'
        ]
    ];

    /**
     * Obtener referencias asociadas a una familia
     *
     * @param int $idFamily
     * @param string|null $status
     * @return array
     */
    public function getByFamily(int $idFamily, ?string $status = 'ACTIVE'): array
    {
        $builder = $this->where('id_family', $idFamily);

        if ($status !== null) {
            $builder->where('status', $status);
        }

        return $builder->findAll();
    }

    /**
     * Buscar una referencia específica
     *
     * @param string $reference
     * @return array|null
     */
    public function getByReference(string $reference): ?array
    {
        return $this->where('reference', $reference)->first();
    }

    /**
     * Obtener referencia con datos de la familia asociada
     *
     * @param string $reference
     * @return array|null
     */
    public function getWithFamily(string $reference): ?array
    {
        return $this->select('families_reference.*, families.keyword as family_keyword, families.state as family_state')
            ->join('families', 'families.id = families_reference.id_family AND families.deleted_at IS NULL', 'left')
            ->where('families_reference.reference', $reference)
            ->first();
    }

    /**
     * Obtener todas las referencias activas con los datos de sus familias activas
     *
     * @return array
     */
    public function getAllActiveWithFamilies(): array
    {
        return $this->select('families_reference.id as reference_id, families_reference.reference, families_reference.status as reference_status, families.id as id_family, families.keyword as family_name, families.state as family_state')
            ->join('families', 'families.id = families_reference.id_family AND families.deleted_at IS NULL', 'inner')
            ->where('families.state', 'ACTIVO')
            ->where('families_reference.status', 'ACTIVE')
            ->notLike('families_reference.reference', 'Producto gen', 'after')
            ->notLike('families_reference.reference', 'productogenerico', 'both')
            ->orderBy('families.keyword', 'ASC')
            ->orderBy('families_reference.reference', 'ASC')
            ->findAll();
    }

    /**
     * Obtener referencia por ID con datos de la familia asociada
     *
     * @param int $id
     * @return array|null
     */
    public function getWithFamilyById(int $id): ?array
    {
        return $this->select('families_reference.*, families.keyword as family_name, families.keyword as family_keyword, families.state as family_state')
            ->join('families', 'families.id = families_reference.id_family AND families.deleted_at IS NULL', 'left')
            ->where('families_reference.id', $id)
            ->first();
    }
}


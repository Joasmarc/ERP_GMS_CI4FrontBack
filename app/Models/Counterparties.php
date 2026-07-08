<?php

namespace App\Models;

use CodeIgniter\Model;

class Counterparties extends Model
{
    protected $table            = 'counterparties';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'contraparte_type',
        'person_type',
        'nombre_completo',
        'tipo_identificacion',
        'numero_identificacion',
        'digito_verificador',
        'pais_identificacion',
        'fecha_expedicion_identificacion',
        'fecha_vencimiento_identificacion',
        'departamento_expedicion',
        'ciudad_expedicion',
        'pais_residencia',
        'departamento_residencia',
        'ciudad_residencia',
        'direccion_completa',
        'numero_exterior',
        'numero_interior',
        'apartamento_complemento',
        'codigo_postal',
        'email_principal',
        'telefono_principal',
        'telefono_secundario',
        'pagina_web',
        'linkedin_redes',
        'regimen_tributario',
        'estado_tributario',
        'antecedentes_judiciales',
        'antecedentes_disciplinarios',
        'reportado_ofac',
        'reportado_onu',
        'reportado_locales',
        'fecha_ultimo_screening',
        'resultado_ultimo_screening',
        'sector_economico',
        'subsector_especifico',
        'tipo_producto_servicio',
        'nivel_riesgo_inicial',
        'justificacion_riesgo',
        'es_pep',
        'tipo_pep',
        'descripcion_vinculo_pep',
        'vinculos_criminales',
        'antecedentes_penales',
        'descripcion_antecedentes',
        'sancionado_disciplinariamente',
        'descripcion_sanciones',
        'opera_pais_alto_riesgo',
        'justificacion_exposicion_geografica',
        'paises_opera',
        'pais_origen',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'lugar_nacimiento_ciudad',
        'departamento_nacimiento',
        'pais_nacimiento',
        'sexo',
        'estado_civil',
        'tiempo_residencia_meses',
        'direccion_anterior',
        'tiempo_direccion_anterior',
        'direccion_laboral',
        'actividad_principal',
        'profesion',
        'area_especializacion',
        'anos_experiencia',
        'antecedentes_laborales',
        'certificaciones_profesionales',
        'membresias_profesionales',
        'licencias_profesionales'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

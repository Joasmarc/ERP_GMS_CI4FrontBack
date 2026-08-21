<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCounterpartiesAndSharedForms extends Migration
{
    public function up()
    {
        // 1.0 Crear tabla de departamentos
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('departments');

        // 2.0 Modificar tabla de ciudades (añadir department_id)
        $fields = [
            'department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id'
            ]
        ];
        $this->forge->addColumn('cities', $fields);

        // 3.0 Sembrar Departamentos y Ciudades de Colombia
        $db = \Config\Database::connect();
        
        $departmentsData = [
            ['name' => 'Amazonas', 'code' => 'AMA'],
            ['name' => 'Antioquia', 'code' => 'ANT'],
            ['name' => 'Arauca', 'code' => 'ARA'],
            ['name' => 'Atlántico', 'code' => 'ATL'],
            ['name' => 'Bolívar', 'code' => 'BOL'],
            ['name' => 'Boyacá', 'code' => 'BOY'],
            ['name' => 'Caldas', 'code' => 'CAL'],
            ['name' => 'Caquetá', 'code' => 'CAQ'],
            ['name' => 'Casanare', 'code' => 'CAS'],
            ['name' => 'Cauca', 'code' => 'CAU'],
            ['name' => 'Cesar', 'code' => 'CES'],
            ['name' => 'Chocó', 'code' => 'CHO'],
            ['name' => 'Córdoba', 'code' => 'COR'],
            ['name' => 'Cundinamarca', 'code' => 'CUN'],
            ['name' => 'Guainía', 'code' => 'GUA'],
            ['name' => 'Guaviare', 'code' => 'GUV'],
            ['name' => 'Huila', 'code' => 'HUI'],
            ['name' => 'La Guajira', 'code' => 'LAG'],
            ['name' => 'Magdalena', 'code' => 'MAG'],
            ['name' => 'Meta', 'code' => 'MET'],
            ['name' => 'Nariño', 'code' => 'NAR'],
            ['name' => 'Norte de Santander', 'code' => 'NSA'],
            ['name' => 'Putumayo', 'code' => 'PUT'],
            ['name' => 'Quindío', 'code' => 'QUI'],
            ['name' => 'Risaralda', 'code' => 'RIS'],
            ['name' => 'San Andrés y Providencia', 'code' => 'SAP'],
            ['name' => 'Santander', 'code' => 'SAN'],
            ['name' => 'Sucre', 'code' => 'SUC'],
            ['name' => 'Tolima', 'code' => 'TOL'],
            ['name' => 'Valle del Cauca', 'code' => 'VAC'],
            ['name' => 'Vaupés', 'code' => 'VAU'],
            ['name' => 'Vichada', 'code' => 'VID'],
            ['name' => 'Bogotá D.C.', 'code' => 'DC']
        ];

        $citiesData = [
            'Amazonas' => [
                ['name' => 'Leticia', 'code' => '91001']
            ],
            'Antioquia' => [
                ['name' => 'Medellín', 'code' => '05001'],
                ['name' => 'Bello', 'code' => '05088'],
                ['name' => 'Itagüí', 'code' => '05360'],
                ['name' => 'Envigado', 'code' => '05266'],
                ['name' => 'Rionegro', 'code' => '05615']
            ],
            'Arauca' => [
                ['name' => 'Arauca', 'code' => '81001']
            ],
            'Atlántico' => [
                ['name' => 'Barranquilla', 'code' => '08001'],
                ['name' => 'Soledad', 'code' => '08758']
            ],
            'Bolívar' => [
                ['name' => 'Cartagena', 'code' => '13001']
            ],
            'Boyacá' => [
                ['name' => 'Tunja', 'code' => '15001'],
                ['name' => 'Duitama', 'code' => '15238'],
                ['name' => 'Sogamoso', 'code' => '15759']
            ],
            'Caldas' => [
                ['name' => 'Manizales', 'code' => '17001']
            ],
            'Caquetá' => [
                ['name' => 'Florencia', 'code' => '18001']
            ],
            'Casanare' => [
                ['name' => 'Yopal', 'code' => '85001']
            ],
            'Cauca' => [
                ['name' => 'Popayán', 'code' => '19001']
            ],
            'Cesar' => [
                ['name' => 'Valledupar', 'code' => '20001']
            ],
            'Chocó' => [
                ['name' => 'Quibdó', 'code' => '27001']
            ],
            'Córdoba' => [
                ['name' => 'Montería', 'code' => '23001']
            ],
            'Cundinamarca' => [
                ['name' => 'Soacha', 'code' => '25754'],
                ['name' => 'Girardot', 'code' => '25307'],
                ['name' => 'Facatativá', 'code' => '25269'],
                ['name' => 'Zipaquirá', 'code' => '25899']
            ],
            'Guainía' => [
                ['name' => 'Inírida', 'code' => '94001']
            ],
            'Guaviare' => [
                ['name' => 'San José del Guaviare', 'code' => '95001']
            ],
            'Huila' => [
                ['name' => 'Neiva', 'code' => '41001']
            ],
            'La Guajira' => [
                ['name' => 'Riohacha', 'code' => '44001'],
                ['name' => 'Uribia', 'code' => '44847']
            ],
            'Magdalena' => [
                ['name' => 'Santa Marta', 'code' => '47001'],
                ['name' => 'Ciénaga', 'code' => '47189']
            ],
            'Meta' => [
                ['name' => 'Villavicencio', 'code' => '50001']
            ],
            'Nariño' => [
                ['name' => 'Pasto', 'code' => '52001'],
                ['name' => 'Tumaco', 'code' => '52835'],
                ['name' => 'Ipiales', 'code' => '52356']
            ],
            'Norte de Santander' => [
                ['name' => 'Cúcuta', 'code' => '54001'],
                ['name' => 'Ocaña', 'code' => '54498'],
                ['name' => 'Pamplona', 'code' => '54518']
            ],
            'Putumayo' => [
                ['name' => 'Mocoa', 'code' => '86001']
            ],
            'Quindío' => [
                ['name' => 'Armenia', 'code' => '63001'],
                ['name' => 'Calarcá', 'code' => '63130']
            ],
            'Risaralda' => [
                ['name' => 'Pereira', 'code' => '66001'],
                ['name' => 'Dosquebradas', 'code' => '66170']
            ],
            'San Andrés y Providencia' => [
                ['name' => 'San Andrés', 'code' => '88001']
            ],
            'Santander' => [
                ['name' => 'Bucaramanga', 'code' => '68001'],
                ['name' => 'Floridablanca', 'code' => '68276'],
                ['name' => 'Girón', 'code' => '68307'],
                ['name' => 'Barrancabermeja', 'code' => '68081']
            ],
            'Sucre' => [
                ['name' => 'Sincelejo', 'code' => '70001']
            ],
            'Tolima' => [
                ['name' => 'Ibagué', 'code' => '73001'],
                ['name' => 'Espinal', 'code' => '73268']
            ],
            'Valle del Cauca' => [
                ['name' => 'Cali', 'code' => '76001'],
                ['name' => 'Palmira', 'code' => '76520'],
                ['name' => 'Buenaventura', 'code' => '76109'],
                ['name' => 'Tuluá', 'code' => '76834'],
                ['name' => 'Buga', 'code' => '76111'],
                ['name' => 'Cartago', 'code' => '76147']
            ],
            'Vaupés' => [
                ['name' => 'Mitú', 'code' => '97001']
            ],
            'Vichada' => [
                ['name' => 'Puerto Carreño', 'code' => '99001']
            ],
            'Bogotá D.C.' => [
                ['name' => 'Bogotá', 'code' => '11001']
            ]
        ];

        // Insertar departamentos y guardar sus IDs asignados para las ciudades
        foreach ($departmentsData as $dept) {
            $db->table('departments')->insert($dept);
            $departmentId = $db->insertID();

            if (isset($citiesData[$dept['name']])) {
                foreach ($citiesData[$dept['name']] as $city) {
                    $db->table('cities')->insert([
                        'department_id' => $departmentId,
                        'name'          => $city['name'],
                        'code'          => $city['code'],
                        'state'         => 'ACTIVO'
                    ]);
                }
            }
        }

        // 4.0 Crear tabla de contrapartes (counterparties)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'contraparte_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // 'proveedor' o 'cliente'
            ],
            'person_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // 'juridica' o 'natural'
            ],
            // Datos comunes
            'nombre_completo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tipo_identificacion' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'numero_identificacion' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'digito_verificador' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'pais_identificacion' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'fecha_expedicion_identificacion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'fecha_vencimiento_identificacion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'departamento_expedicion' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'ciudad_expedicion' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'pais_residencia' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'departamento_residencia' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'ciudad_residencia' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'direccion_completa' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'numero_exterior' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'numero_interior' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'apartamento_complemento' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'codigo_postal' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'email_principal' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'telefono_principal' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'telefono_secundario' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'pagina_web' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'linkedin_redes' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'regimen_tributario' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'estado_tributario' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Activo',
            ],
            'antecedentes_judiciales' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'No',
            ],
            'antecedentes_disciplinarios' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'No',
            ],
            'reportado_ofac' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'No',
            ],
            'reportado_onu' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'No',
            ],
            'reportado_locales' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'No',
            ],
            'fecha_ultimo_screening' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'resultado_ultimo_screening' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sector_economico' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'subsector_especifico' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'tipo_producto_servicio' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'nivel_riesgo_inicial' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Bajo',
            ],
            'justificacion_riesgo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'es_pep' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'No',
            ],
            'tipo_pep' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'descripcion_vinculo_pep' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'vinculos_criminales' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'No',
            ],
            'antecedentes_penales' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'descripcion_antecedentes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sancionado_disciplinariamente' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'No',
            ],
            'descripcion_sanciones' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'opera_pais_alto_riesgo' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'No',
            ],
            'justificacion_exposicion_geografica' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'paises_opera' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            
            // Datos específicos Personas Naturales
            'pais_origen' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'primer_apellido' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'segundo_apellido' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'fecha_nacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'lugar_nacimiento_ciudad' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'departamento_nacimiento' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'pais_nacimiento' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'sexo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'estado_civil' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'tiempo_residencia_meses' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'direccion_anterior' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tiempo_direccion_anterior' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'direccion_laboral' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'actividad_principal' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'profesion' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'area_especializacion' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'anos_experiencia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'antecedentes_laborales' => [
                'type' => 'TEXT', // JSON
                'null' => true,
            ],
            'certificaciones_profesionales' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'membresias_profesionales' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'licencias_profesionales' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            
            // Timestamps
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('counterparties');

        // 5.0 Crear tabla de enlaces compartidos (shared_forms)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'numero_identificacion' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'contraparte_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // 'proveedor' o 'cliente'
            ],
            'person_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // 'juridica' o 'natural'
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'pending', // 'pending', 'completed'
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('shared_forms');
    }

    public function down()
    {
        $this->forge->dropTable('shared_forms', true);
        $this->forge->dropTable('counterparties', true);
        $this->forge->dropColumn('cities', 'department_id');
        $this->forge->dropTable('departments', true);
    }
}

<?php

namespace App\Commands;

use App\Libraries\SiigoService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SiigoSync extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Siigo';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'siigo:sync';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Sincroniza las tablas families y families_reference con el catálogo de productos de Siigo.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'siigo:sync [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--force' => 'Fuerza la sincronización ignorando el caché de ejecuciones recientes.'
    ];

    /**
     * Ejecutar el comando spark
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Iniciando sincronización con Siigo...', 'yellow');

        $force = array_key_exists('force', $params) || CLI::getOption('force');

        $service = new SiigoService();

        CLI::write('Autenticando contra Siigo API...', 'cyan');
        $token = $service->getAuthToken($force);

        if (!$token) {
            CLI::error('Error: No se pudo obtener el token de autenticación de Siigo.');
            return;
        }

        CLI::write('Token obtenido con éxito. Sincronizando familias y referencias...', 'cyan');

        $result = $service->syncFamiliesAndReferences($force);

        if ($result['status'] === 'success') {
            CLI::write('Sincronización completada exitosamente.', 'green');
            CLI::table([
                ['Métrica', 'Cantidad'],
                ['Total Productos Siigo', $result['total_siigo_products'] ?? 0],
                ['Familias Creadas', $result['families_created'] ?? 0],
                ['Familias Actualizadas / Inactivadas', $result['families_updated'] ?? 0],
                ['Total Familias Activas', $result['total_active_families'] ?? 0],
                ['Referencias Creadas', $result['references_created'] ?? 0],
                ['Referencias Actualizadas', $result['references_updated'] ?? 0],
                ['Referencias Inactivadas', $result['references_inactivated'] ?? 0],
                ['Total Referencias Activas', $result['total_active_references'] ?? 0],
                ['Total Referencias Registradas', $result['total_references'] ?? 0],
            ]);
        } elseif ($result['status'] === 'already_synced') {
            CLI::write('El catálogo ya se encontraba sincronizado recientemente.', 'green');
            CLI::write('Familias registradas: ' . ($result['families_count'] ?? 0));
            CLI::write('Referencias registradas: ' . ($result['references_count'] ?? 0));
            CLI::write('Usa --force para forzar la actualización.');
        } else {
            CLI::error('Resultado: ' . ($result['message'] ?? 'Error desconocido'));
        }
    }
}

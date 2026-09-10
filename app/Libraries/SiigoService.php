<?php

namespace App\Libraries;

use App\Models\Families;
use App\Models\FamiliesReference;
use Config\Database;
use Config\Services;

class SiigoService
{
    /**
     * Obtener o renovar el token de autenticación de Siigo
     *
     * @param bool $forceRefresh
     * @return string|null
     */
    public function getAuthToken(bool $forceRefresh = false): ?string
    {
        $cache = Services::cache();

        // 1. Revisar sesión actual si no se fuerza la renovación
        if (!$forceRefresh) {
            $sessionToken = session()->get('siigo_token');
            $sessionExpires = session()->get('siigo_token_expires');
            if (!empty($sessionToken) && !empty($sessionExpires) && $sessionExpires > time()) {
                return $sessionToken;
            }

            // 2. Revisar caché del framework
            $cachedToken = $cache->get('siigo_api_token');
            if (!empty($cachedToken)) {
                return $cachedToken;
            }
        }

        // 3. Modo de simulación local
        if (env('SIIGO_SIMULATE') === true || env('SIIGO_SIMULATE') === 'true') {
            $simToken = 'simulated_siigo_token_12345';
            try {
                if (!is_cli()) {
                    session()->set([
                        'siigo_token'         => $simToken,
                        'siigo_token_expires' => time() + 86400
                    ]);
                }
            } catch (\Throwable $t) {
                // Sesión no inicializada
            }
            $cache->save('siigo_api_token', $simToken, 86400);
            return $simToken;
        }

        // 4. Autenticación con API de Siigo
        $authUrl = env('SIIGO_AUTH_URL', 'https://api.siigo.com/auth');
        $partnerId = env('SIIGO_PARTNER_ID', 'gsmerp');
        $username = env('SIIGO_USERNAME');
        $accessKey = env('SIIGO_ACCESS_KEY');

        if (empty($username) || empty($accessKey)) {
            log_message('warning', 'SiigoService: Credenciales no configuradas en el entorno.');
            return null;
        }

        $httpClient = Services::curlrequest();

        try {
            $response = $httpClient->post($authUrl, [
                'headers' => [
                    'Partner-Id'   => $partnerId,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'username'   => $username,
                    'access_key' => $accessKey,
                ],
                'http_errors' => false
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                if (!empty($data['access_token'])) {
                    $token = $data['access_token'];
                    $expiresIn = (int)($data['expires_in'] ?? 86400);

                    // Guardar en caché y sesión
                    $cache->save('siigo_api_token', $token, max(60, $expiresIn - 120));

                    try {
                        if (!is_cli()) {
                            session()->set([
                                'siigo_token'         => $token,
                                'siigo_token_expires' => time() + $expiresIn
                            ]);
                        }
                    } catch (\Throwable $t) {
                        // Sesión no disponible (ej. CLI)
                    }

                    return $token;
                }
            }

            log_message('error', 'SiigoService: Error al autenticar: ' . $response->getBody());
            return null;
        } catch (\Throwable $e) {
            log_message('error', 'SiigoService: Excepción al conectar con Siigo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener el catálogo de productos de Siigo (paginado o simulado)
     *
     * @param string|null $token
     * @return array
     */
    public function fetchProducts(?string $token = null): array
    {
        if (empty($token)) {
            $token = $this->getAuthToken();
        }

        if (empty($token)) {
            return [];
        }

        // Modo simulación
        if (env('SIIGO_SIMULATE') === true || env('SIIGO_SIMULATE') === 'true' || $token === 'simulated_siigo_token_12345') {
            return [
                [
                    'id'            => 'siigo-prod-1',
                    'name'          => 'Producto Simulado 1 (Siigo)',
                    'account_group' => ['name' => 'Categoría Simbólica A'],
                    'unit_label'    => 'Caja',
                    'code'          => 'MOCK-001',
                    'reference'     => 'MOCK-001',
                    'description'   => 'Descripción de producto simulado 1 para pruebas locales.',
                    'active'        => true
                ],
                [
                    'id'            => 'siigo-prod-2',
                    'name'          => 'Producto Simulado 2 (Siigo)',
                    'account_group' => ['name' => 'Categoría Simbólica B'],
                    'unit_label'    => 'Unidad',
                    'code'          => 'MOCK-002',
                    'reference'     => 'MOCK-002',
                    'description'   => 'Descripción de producto simulado 2 para pruebas locales.',
                    'active'        => true
                ],
                [
                    'id'            => 'siigo-prod-3',
                    'name'          => 'Producto Simulado 3 (Siigo)',
                    'account_group' => ['name' => 'Categoría Simbólica C'],
                    'unit_label'    => 'Paquete',
                    'code'          => 'MOCK-003',
                    'reference'     => 'MOCK-003',
                    'description'   => 'Descripción de producto simulado 3 para pruebas locales.',
                    'active'        => true
                ]
            ];
        }

        $httpClient = Services::curlrequest();
        $url = 'https://api.siigo.com/v1/products?page_size=100';
        $partnerId = env('SIIGO_PARTNER_ID', 'gsmerp');
        $products = [];

        try {
            while ($url) {
                $response = $httpClient->get($url, [
                    'headers' => [
                        'Partner-Id'    => $partnerId,
                        'Authorization' => 'Bearer ' . $token,
                    ],
                    'http_errors' => false
                ]);

                if ($response->getStatusCode() === 200) {
                    $data = json_decode($response->getBody(), true);
                    foreach ($data['results'] ?? [] as $item) {
                        $products[] = $item;
                    }
                    $url = $data['_links']['next']['href'] ?? null;
                } else {
                    log_message('error', 'SiigoService: Error listando productos Siigo en URL ' . $url . ': ' . $response->getBody());
                    break;
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'SiigoService: Excepción listando productos: ' . $e->getMessage());
        }

        return $products;
    }

    /**
     * Asegurar que la tabla families_reference existe en la base de datos
     */
    public function ensureTablesExist(): void
    {
        $db = Database::connect();
        if (!$db->tableExists('families_reference')) {
            $forge = Database::forge();
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => false,
                    'auto_increment' => true,
                ],
                'id_family' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['ACTIVE', 'INACTIVE'],
                    'default'    => 'ACTIVE',
                ],
                'reference' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '25',
                    'null'       => false,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey('id_family');
            $forge->addKey('status');
            $forge->addKey('reference');
            $forge->createTable('families_reference', true);
        }
    }

    /**
     * Sincronizar familias y families_reference con los datos de Siigo
     *
     * @param bool $force Si es true, ignora el caché reciente
     * @return array Resumen de sincronización
     */
    /**
     * Sincronizar familias y families_reference con los datos de Siigo
     *
     * @param bool $force Si es true, ignora el caché reciente
     * @return array Resumen de sincronización
     */
    public function syncFamiliesAndReferences(bool $force = false): array
    {
        $cache = Services::cache();

        // 1. Asegurar existencia de las tablas
        $this->ensureTablesExist();

        $refModel = new FamiliesReference();
        $familyModel = new Families();
        $currentRefsCount = $refModel->countAllResults();

        // 2. Revisar si ya se sincronizó recientemente (TTL 15 min), solo si ya existen referencias y no se fuerza
        if (!$force && $currentRefsCount > 0 && $cache->get('siigo_synced_recently')) {
            return [
                'status'           => 'already_synced',
                'message'          => 'Catálogo sincronizado recientemente.',
                'families_count'   => $familyModel->countAllResults(),
                'references_count' => $currentRefsCount
            ];
        }

        // 3. Obtener token y productos de Siigo
        $token = $this->getAuthToken($force);
        if (empty($token)) {
            return [
                'status'  => 'error',
                'message' => 'No se pudo obtener el token de autenticación de Siigo.'
            ];
        }

        $products = $this->fetchProducts($token);
        if (empty($products)) {
            return [
                'status'  => 'empty',
                'message' => 'No se encontraron productos en Siigo para sincronizar.'
            ];
        }

        // Función auxiliar para normalizar cadenas
        $normalizeKey = static function(string $str): string {
            return preg_replace('/\s+/', ' ', mb_strtolower(trim($str), 'UTF-8'));
        };

        // 4. Cargar datos actuales en memoria
        $existingFamiliesDb = $familyModel->where('deleted_at IS NULL')->findAll();
        $existingFamilies = [];
        foreach ($existingFamiliesDb as $f) {
            $key = $normalizeKey((string)($f['keyword'] ?? ''));
            $existingFamilies[$key] = $f;
        }

        $existingReferencesDb = $refModel->findAll();
        $existingReferences = [];
        foreach ($existingReferencesDb as $r) {
            $refKey = $normalizeKey((string)($r['reference'] ?? ''));
            $existingReferences[$refKey] = $r;
        }

        $newFamilies = [];
        $familiesSeenInSiigo = [];       // Familias detectadas en la respuesta de Siigo
        $familiesActiveInSiigo = [];     // Familias con al menos un producto activo
        $createdFamiliesCount = 0;
        $updatedFamiliesCount = 0;
        $createdReferencesCount = 0;
        $updatedReferencesCount = 0;
        $inactivatedReferencesCount = 0;

        // 5. Primer pase: Identificar familias activas/inactivas en Siigo y registrar nuevas
        foreach ($products as $p) {
            $rawFamilyName = trim((string)($p['name'] ?? ''));
            if ($rawFamilyName === '') {
                continue;
            }

            // Omitir servicios o productos no inventariables del catálogo general
            $accountGroup = mb_strtolower(trim($p['account_group']['name'] ?? ''), 'UTF-8');
            if ($accountGroup === 'servicios') {
                continue;
            }
            $code = strtolower(trim((string)($p['code'] ?? '')));
            if (in_array($code, ['productogenericonube', 'registromanual'], true)) {
                continue;
            }

            $normKey = $normalizeKey($rawFamilyName);
            $familiesSeenInSiigo[$normKey] = true;

            $isProductActive = !isset($p['active']) || $p['active'] !== false;
            if ($isProductActive) {
                $familiesActiveInSiigo[$normKey] = true;
            }

            if (!isset($existingFamilies[$normKey]) && !isset($newFamilies[$normKey])) {
                $newFamilies[$normKey] = [
                    'keyword'  => $rawFamilyName,
                    'state'    => $isProductActive ? 'ACTIVO' : 'INACTIVO',
                    'img_path' => null
                ];
            }
        }

        // Insertar nuevas familias detectadas
        if (!empty($newFamilies)) {
            try {
                $familyModel->insertBatch(array_values($newFamilies));
                $createdFamiliesCount = count($newFamilies);
            } catch (\Throwable $t) {
                log_message('error', 'SiigoService: Error insertando familias nuevas: ' . $t->getMessage());
            }
        }

        // ESCALA 1: Actualizar estado de las familias existentes en BD
        // - Si la familia NO viene en Siigo (o todos sus productos están inactivos) -> INACTIVAR ('INACTIVO')
        // - Si la familia SÍ viene activa en Siigo -> ACTIVAR ('ACTIVO')
        foreach ($existingFamilies as $normKey => $fam) {
            $famId = (int)$fam['id'];
            $currentState = $fam['state'] ?? 'ACTIVO';
            $targetState = isset($familiesActiveInSiigo[$normKey]) ? 'ACTIVO' : 'INACTIVO';

            if ($currentState !== $targetState) {
                try {
                    $familyModel->update($famId, ['state' => $targetState]);
                    $existingFamilies[$normKey]['state'] = $targetState;
                    $updatedFamiliesCount++;
                } catch (\Throwable $t) {
                    log_message('error', "SiigoService: Error actualizando estado de familia ID {$famId}: " . $t->getMessage());
                }
            }
        }

        // Recargar mapa de familias con sus IDs y estados actualizados
        $existingFamiliesDb = $familyModel->where('deleted_at IS NULL')->findAll();
        $existingFamilies = [];
        foreach ($existingFamiliesDb as $f) {
            $key = $normalizeKey((string)($f['keyword'] ?? ''));
            $existingFamilies[$key] = $f;
        }

        // 6. Segundo pase: Procesar referencias de cada producto
        $newReferences = [];
        $seenRefsInBatch = [];
        $cleanRefsInSiigo = []; // Referencias válidas recibidas en esta consulta de Siigo
        $db = Database::connect();

        foreach ($products as $p) {
            $rawFamilyName = trim((string)($p['name'] ?? ''));
            if ($rawFamilyName === '') {
                continue;
            }

            $normFamilyKey = $normalizeKey($rawFamilyName);
            $family = $existingFamilies[$normFamilyKey] ?? null;
            $familyId = $family ? (int)$family['id'] : null;

            if (!$familyId) {
                continue;
            }

            // El estado de la referencia depende del producto de Siigo y de la familia:
            // Si la familia completa está inactiva, la referencia también debe ser INACTIVE
            $isProductActive = !isset($p['active']) || $p['active'] !== false;
            $isFamilyActive = ($family['state'] ?? 'ACTIVO') === 'ACTIVO';
            $refStatus = ($isProductActive && $isFamilyActive) ? 'ACTIVE' : 'INACTIVE';

            // Extraer SOLAMENTE la referencia limpia (sin nombre completo)
            $cleanRef = $this->extractCleanReference($p, $rawFamilyName);
            if ($cleanRef === null || $cleanRef === '') {
                continue;
            }

            $normRefKey = $normalizeKey($cleanRef);

            // Si viene al menos un producto activo para esta referencia, debe ser ACTIVE
            if (!isset($cleanRefsInSiigo[$normRefKey]) || $refStatus === 'ACTIVE') {
                $cleanRefsInSiigo[$normRefKey] = [
                    'id_family' => $familyId,
                    'reference' => $cleanRef,
                    'status'    => $refStatus
                ];
            }

            if (isset($existingReferences[$normRefKey])) {
                // La referencia ya existe: verificar si requiere actualizar familia, estado o texto
                $currentRef = $existingReferences[$normRefKey];
                $needsUpdate = false;
                $updateData = [];

                if ((int)($currentRef['id_family'] ?? 0) !== $familyId) {
                    $updateData['id_family'] = $familyId;
                    $needsUpdate = true;
                }

                $targetStatus = $cleanRefsInSiigo[$normRefKey]['status'];
                if (($currentRef['status'] ?? 'ACTIVE') !== $targetStatus) {
                    $updateData['status'] = $targetStatus;
                    $needsUpdate = true;
                }
                if (($currentRef['reference'] ?? '') !== $cleanRef) {
                    $updateData['reference'] = $cleanRef;
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    try {
                        $db->table('families_reference')
                            ->where('id', $currentRef['id'])
                            ->update($updateData);

                        $existingReferences[$normRefKey]['id_family'] = $familyId;
                        $existingReferences[$normRefKey]['status'] = $targetStatus;
                        $existingReferences[$normRefKey]['reference'] = $cleanRef;
                        $updatedReferencesCount++;
                    } catch (\Throwable $t) {
                        log_message('error', 'SiigoService: Error actualizando referencia ID ' . $currentRef['id'] . ': ' . $t->getMessage());
                    }
                }
            } else {
                // Agregar al lote de inserción si es una referencia nueva
                if (!isset($seenRefsInBatch[$normRefKey])) {
                    $newReferences[$normRefKey] = [
                        'id_family' => $familyId,
                        'reference' => $cleanRef,
                        'status'    => $cleanRefsInSiigo[$normRefKey]['status']
                    ];
                    $seenRefsInBatch[$normRefKey] = true;
                } elseif ($cleanRefsInSiigo[$normRefKey]['status'] === 'ACTIVE') {
                    $newReferences[$normRefKey]['status'] = 'ACTIVE';
                }
            }
        }

        // 7. Insertar referencias nuevas en lote directamente en BD
        if (!empty($newReferences)) {
            $builder = $db->table('families_reference');
            $chunks = array_chunk(array_values($newReferences), 50);

            foreach ($chunks as $chunk) {
                try {
                    $builder->insertBatch($chunk);
                    $createdReferencesCount += count($chunk);
                } catch (\Throwable $t) {
                    log_message('error', 'SiigoService: Error en insertBatch de referencias: ' . $t->getMessage());
                    // Fallback fila por fila para no perder registros válidos
                    foreach ($chunk as $singleRow) {
                        try {
                            $builder->ignore(true)->insert($singleRow);
                            $createdReferencesCount++;
                        } catch (\Throwable $t2) {
                            log_message('error', 'SiigoService: Error insertando referencia individual: ' . json_encode($singleRow) . ' - ' . $t2->getMessage());
                        }
                    }
                }
            }
        }

        // 8. ESCALA 2: Inactivar referencias que ya no vienen en Siigo (SIN BORRAR) y purgar registros inválidos
        try {
            $allCurrentDbRefs = $db->table('families_reference r')
                ->select('r.id, r.id_family, r.reference, r.status, f.keyword, f.state as family_state')
                ->join('families f', 'f.id = r.id_family', 'left')
                ->get()
                ->getResultArray();

            $idsToInactivate = [];
            $idsToDelete = [];

            foreach ($allCurrentDbRefs as $dbRef) {
                $refVal = $normalizeKey((string)($dbRef['reference'] ?? ''));
                $famVal = $normalizeKey((string)($dbRef['keyword'] ?? ''));
                $refId = (int)$dbRef['id'];

                // A. Purgar datos corruptos (referencia vacía, sin familia, o idéntica al nombre de la familia)
                if ($refVal === $famVal || empty($refVal) || empty($dbRef['id_family'])) {
                    $idsToDelete[] = $refId;
                }
                // B. Si la familia está inactiva O la referencia NO vino en Siigo: INACTIVAR la referencia
                elseif (!isset($cleanRefsInSiigo[$refVal]) || ($dbRef['family_state'] ?? '') === 'INACTIVO') {
                    if (($dbRef['status'] ?? 'ACTIVE') === 'ACTIVE') {
                        $idsToInactivate[] = $refId;
                    }
                }
            }

            // Inactivar referencias que no vinieron en Siigo (preservando historial para auditoría/remisiones)
            if (!empty($idsToInactivate)) {
                $db->table('families_reference')
                    ->whereIn('id', $idsToInactivate)
                    ->update(['status' => 'INACTIVE']);
                $inactivatedReferencesCount = count($idsToInactivate);
                log_message('info', "SiigoService: Inactivadas {$inactivatedReferencesCount} referencias que no vinieron en Siigo.");
            }

            // Purgar únicamente registros corruptos (vacíos o idénticos al nombre de la familia)
            if (!empty($idsToDelete)) {
                $db->table('families_reference')->whereIn('id', $idsToDelete)->delete();
                log_message('info', 'SiigoService: Eliminadas ' . count($idsToDelete) . ' filas corruptas en families_reference.');
            }
        } catch (\Throwable $t) {
            log_message('error', 'SiigoService: Error en inactivación/depuración de referencias: ' . $t->getMessage());
        }

        $totalCleanRefs = $db->table('families_reference')->countAllResults();
        $totalActiveRefs = $db->table('families_reference')->where('status', 'ACTIVE')->countAllResults();
        $totalActiveFamilies = $familyModel->where('state', 'ACTIVO')->where('deleted_at IS NULL')->countAllResults();

        // 9. Guardar en caché por 30 minutos el catálogo y flag de sincronización reciente (15 min)
        $cache->save('siigo_products_catalog', $products, 1800);
        $cache->save('siigo_synced_recently', time(), 900);

        return [
            'status'                   => 'success',
            'total_siigo_products'     => count($products),
            'families_created'         => $createdFamiliesCount,
            'families_updated'         => $updatedFamiliesCount,
            'references_created'       => $createdReferencesCount,
            'references_updated'       => $updatedReferencesCount,
            'references_inactivated'   => $inactivatedReferencesCount,
            'total_active_families'    => $totalActiveFamilies,
            'total_references'         => $totalCleanRefs,
            'total_active_references'  => $totalActiveRefs
        ];
    }

    /**
     * Extraer y limpiar la referencia para que contenga ÚNICAMENTE la referencia comercial,
     * excluyendo el nombre completo del producto o prefijos redundantes de la familia.
     *
     * @param array $product Datos del producto de Siigo
     * @param string $familyName Nombre de la familia
     * @return string|null Referencia limpia o null si no corresponde guardar en families_reference
     */
    public function extractCleanReference(array $product, string $familyName): ?string
    {
        // 1. Omitir servicios y productos no inventariables de sistema
        $accountGroup = mb_strtolower(trim($product['account_group']['name'] ?? ''), 'UTF-8');
        if ($accountGroup === 'servicios') {
            return null;
        }

        $code = trim((string)($product['code'] ?? ''));
        if (in_array(strtolower($code), ['productogenericonube', 'registromanual'], true)) {
            return null;
        }

        // 2. Priorizar estrictamente el campo 'reference' comercial de Siigo
        $rawRef = '';
        if (!empty($product['reference'])) {
            $rawRef = trim((string)$product['reference']);
        } elseif (!empty($code)) {
            // Fallback a código únicamente si no hay referencia comercial
            $rawRef = $code;
        }

        if ($rawRef === '') {
            return null;
        }

        // 3. Normalizar para comparar si la referencia es el nombre completo de la familia
        $normalize = static function(string $str): string {
            return preg_replace('/[^\p{L}\p{N}]+/u', '', mb_strtolower($str, 'UTF-8'));
        };

        $normRef = $normalize($rawRef);
        $normFam = $normalize($familyName);

        // Si la referencia es idéntica al nombre completo de la familia, omitir
        if ($normRef === $normFam || empty($normRef)) {
            return null;
        }

        // 4. Si la referencia contiene el nombre de la familia o palabras clave de la familia al inicio,
        // remover el prefijo para conservar solo la referencia comercial.
        // Ej: 'CATHETER SIMMONS-ACSIM26F1125' en familia 'Angiography Catheter' -> 'SIMMONS-ACSIM26F1125'
        $cleaned = $rawRef;

        // A. Remover el nombre completo de la familia al inicio si coincide
        $escapedFamName = preg_quote($familyName, '/');
        $cleaned = preg_replace('/^' . $escapedFamName . '\s*[-:_]?\s*/iu', '', $cleaned);

        // B. Remover palabras individuales de la familia (mínimo 4 caracteres) al inicio de la referencia
        $famWords = preg_split('/[\s\-_]+/u', $familyName);
        foreach ($famWords as $word) {
            $word = trim($word);
            if (mb_strlen($word, 'UTF-8') >= 4) {
                $escapedWord = preg_quote($word, '/');
                $cleaned = preg_replace('/^' . $escapedWord . '\s*[-:_]?\s*/iu', '', (string)$cleaned);
            }
        }

        $cleaned = trim((string)$cleaned);
        if ($cleaned === '' || $normalize($cleaned) === $normFam) {
            return null;
        }

        // Acotar a 25 caracteres UTF-8 según el tamaño de la columna VARCHAR(25)
        return mb_substr($cleaned, 0, 25, 'UTF-8');
    }

    /**
     * Asegurar que el catálogo esté sincronizado si nunca se ha hecho o si la tabla está vacía
     */
    public function ensureSynced(): array
    {
        $this->ensureTablesExist();

        $refModel = new FamiliesReference();
        $referencesCount = $refModel->countAllResults();

        $cache = Services::cache();

        // Si ya hay referencias y se sincronizó recientemente, omitir
        if ($referencesCount > 0 && $cache->get('siigo_synced_recently')) {
            return ['status' => 'skipped', 'message' => 'Sincronizado recientemente.'];
        }

        // Si no hay referencias registradas, forzar sincronización ignorando el caché
        return $this->syncFamiliesAndReferences($referencesCount === 0);
    }
}

<?php

namespace App\Controllers;

use App\Models\Products;


class Product extends BaseController
{
    // Tabla_G03
    public function listing()
    {
        // 1.0 Inicializar interfaz y modelo - Declarar interfaz e instanciar modelo de productos
        $LISTING = [];
        $model = new Products();
        // 1.1 Obtener datos con joins
        $LISTING['data'] = $model->join('categories b', 'b.id = products.id_categoria', 'left')
            ->join('brands c', 'c.id = products.id_marca', 'left')
            ->join('pictures_products d', 'd.id_product = products.id', 'left')
            ->join('pictures e', 'e.id = d.id_picture', 'left')
            ->select("
                products.id,
                products.nombre,
                products.presentacion,
                products.observacion,
                b.nombre as id_categoria,
                c.nombre as id_marca,
                MAX(e.path) as img
            ")
            ->groupBy('products.id')
            ->get()->getResultArray();
        // 1.2 Contar cantidad de registros
        $LISTING['cantidad'] = count($LISTING['data']);

        // 2.0 Preparar y enviar respuesta - Construir arreglo de respuesta
        $response = [
            "draw" => 1,
            "recordsTotal" => $LISTING['cantidad'],
            "recordsFiltered" => $LISTING['cantidad'],
            "data" => $LISTING['data'],
        ];
        // 2.1 Enviar JSON al cliente
        exit(json_encode($response));
    }

    // Tabla_G03
    public function listing_siigo()
    {
        // 1.0 Inicializar interfaz y verificar sesión - Obtener token de Siigo
        $SIIGO = [];
        $SIIGO['token'] = session()->get('siigo_token');
        // 1.1 Verificar existencia del token
        if (!$SIIGO['token']) {
            exit(json_encode([
                "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => "No hay sesión de Siigo activa"
            ]));
        }

        // 2.0 Preparar cliente y solicitar productos - Inicializar variables y estado
        $SIIGO['mapped_data'] = [];
        $SIIGO['total_results'] = 0;

        // 2.0.1 Usar simulación si está activa en local
        if (env('SIIGO_SIMULATE') === true || env('SIIGO_SIMULATE') === 'true' || $SIIGO['token'] === 'simulated_siigo_token_12345') {
            $siigo_data = [
                'results' => [
                    [
                        'id' => 'siigo-prod-1',
                        'name' => 'Producto Simulado 1 (Siigo)',
                        'account_group' => ['name' => 'Categoría Simbólica A'],
                        'unit_label' => 'Caja',
                        'code' => 'MOCK-001',
                        'description' => 'Descripción de producto simulado 1 para pruebas locales.'
                    ],
                    [
                        'id' => 'siigo-prod-2',
                        'name' => 'Producto Simulado 2 (Siigo)',
                        'account_group' => ['name' => 'Categoría Simbólica B'],
                        'unit_label' => 'Unidad',
                        'code' => 'MOCK-002',
                        'description' => 'Descripción de producto simulado 2 para pruebas locales.'
                    ],
                    [
                        'id' => 'siigo-prod-3',
                        'name' => 'Producto Simulado 3 (Siigo)',
                        'account_group' => ['name' => 'Categoría Simbólica C'],
                        'unit_label' => 'Paquete',
                        'code' => 'MOCK-003',
                        'description' => 'Descripción de producto simulado 3 para pruebas locales.'
                    ]
                ]
            ];
            foreach ($siigo_data['results'] as $product) {
                $SIIGO['mapped_data'][] = [
                    'id'           => $product['id'],
                    'nombre'       => $product['name'],
                    'id_categoria' => $product['account_group']['name'] ?? '',
                    'presentacion' => $product['unit_label'] ?? '',
                    'id_marca'     => $product['code'] ?? '',
                    'observacion'  => $product['description'] ?? '',
                    'img'          => null
                ];
            }
            $SIIGO['total_results'] = count($SIIGO['mapped_data']);
        } else {
            $client = \Config\Services::curlrequest();
            $url = 'https://api.siigo.com/v1/products';
            // 2.1 Bucle para obtener todas las páginas
            try {
                while ($url) {
                    $response = $client->get($url, [
                        'headers' => [
                            'Partner-Id'    => env('SIIGO_PARTNER_ID', 'gsmerp'),
                            'Authorization' => 'Bearer ' . $SIIGO['token'],
                        ],
                        'http_errors' => false
                    ]);
                    // 2.2 Verificar respuesta exitosa
                    if ($response->getStatusCode() === 200) {
                        $siigo_data = json_decode($response->getBody(), true);
                        // 2.3 Mapear datos al formato de DataTables
                        foreach ($siigo_data['results'] as $product) {
                            $SIIGO['mapped_data'][] = [
                                'id'           => $product['id'],
                                'nombre'       => $product['name'],
                                'id_categoria' => $product['account_group']['name'] ?? '',
                                'presentacion' => $product['unit_label'] ?? '',
                                'id_marca'     => $product['code'] ?? '',
                                'observacion'  => $product['description'] ?? '',
                                'img'          => null
                            ];
                        }
                        // 2.4 Obtener total de resultados
                        $SIIGO['total_results'] = $siigo_data['pagination']['total_results'] ?? count($SIIGO['mapped_data']);
                        // 2.5 Obtener URL de la siguiente página
                        $url = $siigo_data['_links']['next']['href'] ?? null;
                    } else {
                        // 2.6 Registrar error si la petición falla y salir del bucle
                        log_message('error', 'Error listando productos Siigo en URL ' . $url . ': ' . $response->getBody());
                        break;
                    }
                }
            } catch (\Exception $e) {
                // 2.7 Manejar excepción
                log_message('error', 'Excepción listando productos Siigo: ' . $e->getMessage());
            }
        }

        // 3.0 Sincronizar familias y fusionar datos - Instanciar modelo y obtener familias
        $families_model = new \App\Models\Families();
        $families_data = $families_model->findAll();
        // 3.1 Mapear familias existentes para búsqueda rápida
        $existing_families = [];
        foreach ($families_data as $family) {
            $existing_families[$family['keyword']] = $family;
        }
        // 3.2 Preparar listas para evaluación
        $SIIGO['final_data'] = [];
        $new_families = [];
        // 3.3 Iterar sobre datos mapeados de Siigo
        foreach ($SIIGO['mapped_data'] as $item) {
            $keyword = trim($item['nombre']);
            // 3.4 Validar existencia, estado inactivo y asignar imagen
            if (!isset($existing_families[$keyword])) {
                if (!isset($new_families[$keyword])) {
                    $new_families[$keyword] = [
                        'keyword'  => $keyword,
                        'state'    => 'ACTIVO',
                        'img_path' => null
                    ];
                }
                $item['img'] = null;
            } else {
                if ($existing_families[$keyword]['state'] === 'INACTIVO') {
                    continue;
                }
                $item['img'] = $existing_families[$keyword]['img_path'];
            }
            // 3.5 Agregar a la lista final
            $SIIGO['final_data'][] = $item;
        }
        // 3.6 Insertar familias nuevas en bloque
        if (!empty($new_families)) {
            $families_model->insertBatch(array_values($new_families));
            // Actualizar arreglo de familias existentes con las nuevas IDs
            $families_data = $families_model->findAll();
            $existing_families = [];
            foreach ($families_data as $family) {
                $existing_families[$family['keyword']] = $family;
            }
        }

        // 3.7 Asignar family_id a los datos finales
        foreach ($SIIGO['final_data'] as &$final_item) {
            $keyword = trim($final_item['nombre']);
            if (isset($existing_families[$keyword])) {
                $final_item['family_id'] = $existing_families[$keyword]['id'];
            } else {
                $final_item['family_id'] = null;
            }
        }

        // 4.0 Finalizar solicitud y enviar respuesta - Construir arreglo
        $response_data = [
            "draw" => 1,
            "recordsTotal" => $SIIGO['total_results'] ?: count($SIIGO['final_data']),
            "recordsFiltered" => $SIIGO['total_results'] ?: count($SIIGO['final_data']),
            "data" => $SIIGO['final_data'],
        ];
        // 4.1 Enviar JSON al cliente
        exit(json_encode($response_data));
    }

    // Documentos_G03
    public function listing_document()
    {
        // 1.0 Inicializar interfaz y modelo - Declarar interfaz y obtener ID
        $DOCUMENT = [];
        $id = $this->request->getUri()->getSegment(3);

        // 1.1 Intentar obtener por ID de familia primero
        $familyDocsModel = new \App\Models\FamilyDocuments();
        $DOCUMENT['data'] = $familyDocsModel
            ->join('documents b', 'b.id = family_documents.document_id')
            ->where('family_documents.family_id', $id)
            ->where('b.deleted_at', null)
            ->select('b.id, b.path, b.name')
            ->findAll();

        // 1.2 Si no hay resultados y el ID parece ser de un producto legacy, buscar por producto
        if (empty($DOCUMENT['data']) && is_numeric($id)) {
            $model = new Products();
            $DOCUMENT['data'] = $model
                ->join('documents_products a', 'a.id_product = products.id', 'left')
                ->join('documents b', 'b.id = a.id_document')
                ->where('products.id', $id)
                ->where('b.deleted_at', null)
                ->select('b.id, b.path, b.name')
                ->findAll();
        }

        // 2.0 Enviar respuesta JSON - Formatear y retornar datos
        $response = ['documents' => $DOCUMENT['data']];
        // 2.1 Finalizar con JSON
        exit(json_encode($response));
    }

    // Documentos_G03
    public function upload_family_document()
    {
        // 1.0 Validar entrada
        $familyId = $this->request->getPost('family_id');
        $file = $this->request->getFile('document');
        $documentName = $this->request->getPost('document_name') ?: ($file ? $file->getClientName() : '');

        if (!$familyId || !$file || !$file->isValid() || $file->getExtension() !== 'pdf') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Archivo PDF inválido o falta family_id.']);
        }

        // 2.0 Subir archivo
        $newName = $file->getRandomName();
        $uploadPath = 'uploads/documents/';

        if (!is_dir(ROOTPATH . $uploadPath)) {
            mkdir(ROOTPATH . $uploadPath, 0777, true);
        }

        $file->move(ROOTPATH . $uploadPath, $newName);
        $publicPath = '/' . $uploadPath . $newName;

        // 3.0 Registrar documento
        $documentModel = new \App\Models\Documents();
        $documentId = $documentModel->insert([
            'name' => $documentName,
            'path' => $publicPath
        ]);

        // 4.0 Vincular con familia
        $familyDocsModel = new \App\Models\FamilyDocuments();
        $familyDocsModel->insert([
            'family_id' => $familyId,
            'document_id' => $documentId
        ]);

        // 5.0 Retornar éxito
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Documento PDF vinculado exitosamente.',
            'data' => [
                'id' => $documentId,
                'name' => $documentName,
                'path' => $publicPath
            ]
        ]);
    }

    public function upload_image()
    {
        // 1.0 Validar entrada
        $familyId = $this->request->getPost('family_id');
        $file = $this->request->getFile('image');

        if (!$familyId || !$file || !$file->isValid() || !in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Archivo de imagen inválido o falta family_id.']);
        }

        // 2.0 Subir archivo
        $newName = $file->getRandomName();
        $uploadPath = 'uploads/families/';

        if (!is_dir(ROOTPATH . $uploadPath)) {
            mkdir(ROOTPATH . $uploadPath, 0777, true);
        }

        $file->move(ROOTPATH . $uploadPath, $newName);
        $publicPath = '/' . $uploadPath . $newName;

        // 3.0 Actualizar en base de datos la familia
        $familiesModel = new \App\Models\Families();
        $familiesModel->update($familyId, [
            'img_path' => $publicPath
        ]);

        // 4.0 Retornar éxito
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Imagen de producto actualizada correctamente.',
            'path' => $publicPath
        ]);
    }

    public function listing_img()
    {
        $id = $this->request->getUri()->getSegment(3);
        $familyPicsModel = new \App\Models\FamilyPictures();
        $data = $familyPicsModel
            ->join('pictures b', 'b.id = family_pictures.picture_id')
            ->where('family_pictures.family_id', $id)
            ->where('b.deleted_at', null)
            ->select('b.id, b.path')
            ->findAll();

        return $this->response->setJSON(['images' => $data]);
    }

    public function listing_video()
    {
        $id = $this->request->getUri()->getSegment(3);
        $familyVideosModel = new \App\Models\FamilyVideos();
        $data = $familyVideosModel
            ->join('videos b', 'b.id = family_videos.video_id')
            ->where('family_videos.family_id', $id)
            ->where('b.deleted_at', null)
            ->select('b.id, b.path')
            ->findAll();

        return $this->response->setJSON(['videos' => $data]);
    }

    public function upload_family_picture()
    {
        $familyId = $this->request->getPost('family_id');
        $file = $this->request->getFile('image');

        if (!$familyId || !$file || !$file->isValid() || !in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Archivo de imagen inválido o falta family_id.']);
        }

        $newName = $file->getRandomName();
        $uploadPath = 'uploads/pictures/';

        if (!is_dir(ROOTPATH . $uploadPath)) {
            mkdir(ROOTPATH . $uploadPath, 0777, true);
        }

        $file->move(ROOTPATH . $uploadPath, $newName);
        $publicPath = '/' . $uploadPath . $newName;

        $pictureModel = new \App\Models\Pictures();
        $pictureId = $pictureModel->insert([
            'path' => $publicPath
        ]);

        $familyPicsModel = new \App\Models\FamilyPictures();
        $familyPicsModel->insert([
            'family_id' => $familyId,
            'picture_id' => $pictureId
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Imagen vinculada exitosamente.',
            'path' => $publicPath
        ]);
    }

    public function upload_family_video()
    {
        $familyId = $this->request->getPost('family_id');
        $file = $this->request->getFile('video');

        if (!$familyId || !$file || !$file->isValid() || !in_array($file->getMimeType(), ['video/mp4', 'video/webm', 'video/ogg'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Archivo de video inválido o falta family_id.']);
        }

        $newName = $file->getRandomName();
        $uploadPath = 'uploads/videos/';

        if (!is_dir(ROOTPATH . $uploadPath)) {
            mkdir(ROOTPATH . $uploadPath, 0777, true);
        }

        $file->move(ROOTPATH . $uploadPath, $newName);
        $publicPath = '/' . $uploadPath . $newName;

        $videoModel = new \App\Models\Videos();
        $videoId = $videoModel->insert([
            'path' => $publicPath
        ]);

        $familyVideosModel = new \App\Models\FamilyVideos();
        $familyVideosModel->insert([
            'family_id' => $familyId,
            'video_id' => $videoId
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Video vinculado exitosamente.',
            'path' => $publicPath
        ]);
    }

    // Archivo_G03 - Eliminación lógica de documento
    public function delete_document()
    {
        // 1.0 Validar entrada - Obtener ID del documento
        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID de documento no proporcionado.']);
        }

        // 2.0 Ejecutar soft delete - Eliminar documento lógicamente
        $documentModel = new \App\Models\Documents();
        $documentModel->delete($id);

        // 3.0 Retornar éxito
        return $this->response->setJSON(['status' => 'success', 'message' => 'Documento eliminado correctamente.']);
    }

    // Archivo_G03 - Eliminación lógica de imagen
    public function delete_picture()
    {
        // 1.0 Validar entrada - Obtener ID de la imagen
        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID de imagen no proporcionado.']);
        }

        // 2.0 Ejecutar soft delete - Eliminar imagen lógicamente
        $pictureModel = new \App\Models\Pictures();
        $pictureModel->delete($id);

        // 3.0 Retornar éxito
        return $this->response->setJSON(['status' => 'success', 'message' => 'Imagen eliminada correctamente.']);
    }

    // Archivo_G03 - Eliminación lógica de video
    public function delete_video()
    {
        // 1.0 Validar entrada - Obtener ID del video
        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID de video no proporcionado.']);
        }

        // 2.0 Ejecutar soft delete - Eliminar video lógicamente
        $videoModel = new \App\Models\Videos();
        $videoModel->delete($id);

        // 3.0 Retornar éxito
        return $this->response->setJSON(['status' => 'success', 'message' => 'Video eliminado correctamente.']);
    }
}

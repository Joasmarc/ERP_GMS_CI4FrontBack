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
        // 1.0 Inicializar interfaz y servicio Siigo
        $siigoService = new \App\Libraries\SiigoService();
        $token = $siigoService->getAuthToken();

        // 1.1 Verificar existencia del token
        if (!$token) {
            exit(json_encode([
                "draw"            => 1,
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => [],
                "error"           => "No hay sesión de Siigo activa ni credenciales válidas"
            ]));
        }

        // 2.0 Sincronizar familias y families_reference automáticamente
        try {
            $siigoService->syncFamiliesAndReferences();
        } catch (\Throwable $e) {
            log_message('error', 'Error en syncFamiliesAndReferences desde listing_siigo: ' . $e->getMessage());
        }

        // 3.0 Obtener productos de Siigo (desde caché o API)
        $rawProducts = $siigoService->fetchProducts($token);

        // 3.1 Mapear familias existentes con imágenes y estados
        $familiesModel = new \App\Models\Families();
        $familiesData = $familiesModel->where('deleted_at IS NULL')->findAll();
        $existingFamilies = [];
        foreach ($familiesData as $family) {
            $key = mb_strtolower(trim($family['keyword']), 'UTF-8');
            $existingFamilies[$key] = $family;
        }

        // 3.2 Formatear datos para la interfaz y DataTables
        $finalData = [];
        foreach ($rawProducts as $product) {
            $rawName = trim($product['name'] ?? '');
            $normKey = mb_strtolower($rawName, 'UTF-8');
            $family = $existingFamilies[$normKey] ?? null;

            if ($family && $family['state'] === 'INACTIVO') {
                continue;
            }

            // Extraer referencia comercial limpia (sin nombre completo)
            $cleanRef = $siigoService->extractCleanReference($product, $rawName);
            $ref = $cleanRef !== null ? $cleanRef : (!empty($product['reference']) ? trim($product['reference']) : trim($product['code'] ?? ''));

            $finalData[] = [
                'id'           => $product['id'] ?? '',
                'nombre'       => $rawName,
                'id_categoria' => $product['account_group']['name'] ?? '',
                'presentacion' => $product['unit_label'] ?? '',
                'id_marca'     => $product['code'] ?? '',
                'reference'    => $ref,
                'observacion'  => $product['description'] ?? '',
                'img'          => $family['img_path'] ?? null,
                'family_id'    => $family['id'] ?? null
            ];
        }

        // 4.0 Finalizar solicitud y enviar respuesta JSON
        $total = count($finalData);
        $responseData = [
            "draw"            => 1,
            "recordsTotal"    => $total,
            "recordsFiltered" => $total,
            "data"            => $finalData,
        ];

        exit(json_encode($responseData));
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

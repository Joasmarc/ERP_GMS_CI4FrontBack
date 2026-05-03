<?php

namespace App\Controllers;

use App\Models\Products;

class Product extends BaseController
{
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

    public function listing_img()
    {
        // 1.0 Inicializar interfaz y obtener datos - Declarar interfaz y obtener ID
        $IMAGE = [];
        $id_product = $this->request->getUri()->getSegment(3);
        $model = new Products();
        // 1.1 Obtener imágenes asociadas
        $IMAGE['data'] = $model->join('pictures_products a', 'a.id_product = products.id', 'left')
            ->join('pictures b', 'b.id = a.id_picture', 'left')
            ->where('products.id', $id_product)
            ->select("b.path as img")
            ->get()->getResultArray();

        // 2.0 Enviar respuesta JSON - Formatear y retornar datos
        $response = ['imagePaths' => array_column($IMAGE['data'], 'img')];
        // 2.1 Finalizar con JSON
        exit(json_encode($response));
    }

    public function listing_video()
    {
        // 1.0 Inicializar interfaz y modelo - Declarar interfaz y obtener ID
        $VIDEO = [];
        $id_product = $this->request->getUri()->getSegment(3);
        $model = new Products();
        // 1.1 Obtener videos asociados
        $VIDEO['data'] = $model->join('videos_products a', 'a.id_product = products.id', 'left')
            ->join('videos b', 'b.id = a.id_video', 'left')
            ->where('products.id', $id_product)
            ->select("b.path as video")
            ->get()->getResultArray();

        // 2.0 Enviar respuesta JSON - Formatear y retornar datos
        $response = ['videoPaths' => array_column($VIDEO['data'], 'video')];
        // 2.1 Finalizar con JSON
        exit(json_encode($response));
    }

    public function listing_document()
    {
        // 1.0 Inicializar interfaz y modelo - Declarar interfaz y obtener ID
        $DOCUMENT = [];
        $id_product = $this->request->getUri()->getSegment(3);
        $model = new Products();
        // 1.1 Obtener documentos asociados
        $DOCUMENT['data'] = $model->join('documents_products a', 'a.id_product = products.id', 'left')
            ->join('documents b', 'b.id = a.id_document', 'left')
            ->where('products.id', $id_product)
            ->select("b.path, b.name")
            ->get()->getResultArray();

        // 2.0 Enviar respuesta JSON - Formatear y retornar datos
        $response = ['documents' => $DOCUMENT['data']];
        // 2.1 Finalizar con JSON
        exit(json_encode($response));
    }
}

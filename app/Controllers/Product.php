<?php

namespace App\Controllers;

use App\Models\Products;

class Product extends BaseController
{
    // 1.0 Listar productos de base de datos
    public function listing()
    {
        // 1.1 Iniciar modelo de productos
        $model = new Products();

        // 1.2 Obtener datos con joins
        $data = $model->join('categories b', 'b.id = products.id_categoria', 'left')
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
            MAX(e.path) as img"
        )
            ->groupBy('products.id')
            ->get()->getResultArray();

        // 1.3 Contar y preparar respuesta para DataTables
        $cantidad = count($data);

        $data = array(
            "draw" => 1,
            "recordsTotal" => $cantidad,
            "recordsFiltered" => $cantidad,
            "data" => $data,
        );

        // 1.4 Enviar respuesta
        exit(json_encode($data));
    }

    // 2.0 Listar productos desde Siigo
    public function listing_siigo()
    {
        // 2.1 Obtener token de Siigo de la sesión
        $siigoToken = session()->get('siigo_token');

        // 2.2 Verificar existencia del token
        if (!$siigoToken) {
            // Si no hay token, retorna data vacía o un error
            exit(json_encode([
                "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => "No hay sesión de Siigo activa"
            ]));
        }

        // 2.3 Iniciar cliente HTTP
        $client = \Config\Services::curlrequest();

        try {
            // 2.4 Realizar petición GET a la API de Siigo
            $response = $client->get('https://api.siigo.com/v1/products', [
                'headers' => [
                    'Partner-Id'    => env('SIIGO_PARTNER_ID', 'gsmerp'),
                    'Authorization' => 'Bearer ' . $siigoToken,
                ],
                'http_errors' => false
            ]);

            // 2.5 Verificar respuesta exitosa
            if ($response->getStatusCode() === 200) {
                // 2.6 Decodificar respuesta JSON
                $siigoData = json_decode($response->getBody(), true);
                
                // 2.7 Mapear datos al formato de DataTables
                $mappedData = [];
                foreach ($siigoData['results'] as $product) {
                    $mappedData[] = [
                        'id'           => $product['id'],
                        'nombre'       => $product['name'],
                        'id_categoria' => $product['account_group']['name'] ?? '',
                        'presentacion' => $product['unit_label'] ?? '',
                        'id_marca'     => $product['code'] ?? '',
                        'observacion'  => $product['description'] ?? '',
                        'img'          => null // Por ahora sin imagen desde Siigo
                    ];
                }

                // 2.8 Preparar estructura de respuesta para DataTables
                $cantidad = count($mappedData);

                $data = array(
                    "draw" => 1,
                    "recordsTotal" => $siigoData['pagination']['total_results'] ?? $cantidad,
                    "recordsFiltered" => $siigoData['pagination']['total_results'] ?? $cantidad,
                    "data" => $mappedData,
                );

                // 2.9 Enviar respuesta
                exit(json_encode($data));
            }

            // 2.10 Registrar error si la petición falla
            log_message('error', 'Error listando productos Siigo: ' . $response->getBody());
        } catch (\Exception $e) {
            log_message('error', 'Excepción listando productos Siigo: ' . $e->getMessage());
        }

        // 2.11 Retornar vacío en caso de excepción o error
        exit(json_encode([
            "draw" => 1,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => []
        ]));
    }

    // 3.0 Listar imágenes de un producto
    public function listing_img()
    {
        // 3.1 Obtener ID del producto desde URL
        $id_product = $this->request->getUri()->getSegment(3);
        // Si estás en un controlador, ya tienes $this->request disponible

        // 3.2 Iniciar modelo
        $model = new Products();

        // 3.3 Obtener imágenes asociadas
        $data = $model->join('pictures_products a', 'a.id_product = products.id', 'left')
            ->join('pictures b', 'b.id = a.id_picture', 'left')
            ->where('products.id', $id_product)
            ->select("
            b.path as img"
        )
            ->get()->getResultArray();

        // 3.4 Retornar JSON
        exit(json_encode(['imagePaths' => array_column($data, 'img')]));
    }

    // 4.0 Listar videos de un producto
    public function listing_video()
    {
        // 4.1 Obtener ID del producto desde URL
        $id_product = $this->request->getUri()->getSegment(3);
        // Si estás en un controlador, ya tienes $this->request disponible

        // 4.2 Iniciar modelo
        $model = new Products();

        // 4.3 Obtener videos asociados
        $data = $model->join('videos_products a', 'a.id_product = products.id', 'left')
            ->join('videos b', 'b.id = a.id_video', 'left')
            ->where('products.id', $id_product)
            ->select("
                b.path as video
            "
        )
            ->get()->getResultArray();

        // 4.4 Retornar JSON
        exit(json_encode(['videoPaths' => array_column($data, 'video')]));
    }

    // 5.0 Listar documentos de un producto
    public function listing_document()
    {
        // 5.1 Obtener ID del producto desde URL
        $id_product = $this->request->getUri()->getSegment(3);
        // Si estás en un controlador, ya tienes $this->request disponible

        // 5.2 Iniciar modelo
        $model = new Products();

        // 5.3 Obtener documentos asociados
        $data = $model->join('documents_products a', 'a.id_product = products.id', 'left')
        ->join('documents b', 'b.id = a.id_document', 'left')
        ->where('products.id', $id_product)
        ->select("
            b.path, b.name
        ")
        ->get()->getResultArray();

        // 5.4 Retornar JSON
        exit(json_encode(['documents' => $data]));
    }
}

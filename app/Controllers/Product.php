<?php

namespace App\Controllers;

use App\Models\Products;

class Product extends BaseController
{
    public function listing()
    {
        $model = new Products();

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

        $cantidad = count($data);

        $data = array(
            "draw" => 1,
            "recordsTotal" => $cantidad,
            "recordsFiltered" => $cantidad,
            "data" => $data,
        );

        exit(json_encode($data));
    }

    public function listing_img()
    {
        $id_product = $this->request->getUri()->getSegment(3);
        // Si estás en un controlador, ya tienes $this->request disponible

        $model = new Products();

        $data = $model->join('pictures_products a', 'a.id_product = products.id', 'left')
            ->join('pictures b', 'b.id = a.id_picture', 'left')
            ->where('products.id', $id_product)
            ->select("
            b.path as img"
        )
            ->get()->getResultArray();

        exit(json_encode(['imagePaths' => array_column($data, 'img')]));
    }

    public function listing_video()
    {
        $id_product = $this->request->getUri()->getSegment(3);
        // Si estás en un controlador, ya tienes $this->request disponible

        $model = new Products();

        $data = $model->join('videos_products a', 'a.id_product = products.id', 'left')
            ->join('videos b', 'b.id = a.id_video', 'left')
            ->where('products.id', $id_product)
            ->select("
                b.path as video
            "
        )
            ->get()->getResultArray();

        exit(json_encode(['videoPaths' => array_column($data, 'video')]));
    }

    public function listing_document()
    {
        $id_product = $this->request->getUri()->getSegment(3);
        // Si estás en un controlador, ya tienes $this->request disponible

        $model = new Products();

        $data = $model->join('documents_products a', 'a.id_product = products.id', 'left')
        ->join('documents b', 'b.id = a.id_document', 'left')
        ->where('products.id', $id_product)
        ->select("
            b.path, b.name
        ")
        ->get()->getResultArray();

        exit(json_encode(['documents' => $data]));
    }
}

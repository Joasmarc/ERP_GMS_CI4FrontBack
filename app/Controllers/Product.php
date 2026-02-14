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
            e.path as img"
        )  
        ->get()->getResultArray();

        $cantidad = count($data);

        $data = array(
            "draw"            => 1,
            "recordsTotal"    => $cantidad,
            "recordsFiltered" => $cantidad,
            "data"            => $data,
        );

        exit(json_encode($data));
    }
}

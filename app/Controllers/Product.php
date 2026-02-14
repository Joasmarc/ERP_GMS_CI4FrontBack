<?php

namespace App\Controllers;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Products;

class Product extends BaseController
{
    public function listing()
    {
        $model = new Products();
        $data = $model->findAll();

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

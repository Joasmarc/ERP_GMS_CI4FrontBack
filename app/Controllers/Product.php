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
        $data['products'] = $model->findAll();

        exit(json_encode($data));
    }
}

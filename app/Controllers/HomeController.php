<?php

namespace App\Controllers;

use App\Core\Controller;

use App\Models\Product;

class HomeController extends Controller {
    public function index() {
        $productModel = new Product();
        // Get top 6 products for home page
        $products = array_slice($productModel->getAll(), 0, 6);
        $this->view('home/index', ['products' => $products]);
    }
}

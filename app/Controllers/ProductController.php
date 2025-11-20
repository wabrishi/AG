<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller {
    public function index() {
        $productModel = new Product();
        $categoryModel = new Category();

        $search = $_GET['search'] ?? '';
        if ($search) {
            $products = $productModel->search($search);
        } else {
            $products = $productModel->getAll();
        }

        $categories = $categoryModel->getAll();

        // Basic Filtering by Category
        if (isset($_GET['category']) && $_GET['category'] !== '') {
            $filtered = [];
            foreach ($products as $p) {
                if ($p['category_id'] == $_GET['category']) {
                    $filtered[] = $p;
                }
            }
            $products = $filtered;
        }

        $this->view('products/index', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search
        ]);
    }

    public function show() {
        if (!isset($_GET['id'])) {
            $this->redirect('/products');
        }

        $productModel = new Product();
        $product = $productModel->findById($_GET['id']);

        if (!$product) {
            die("Product not found");
        }

        $this->view('products/show', ['product' => $product]);
    }
}

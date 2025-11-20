<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class AdminController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
    }

    public function index() {
        $this->view('admin/dashboard');
    }

    public function products() {
        $productModel = new Product();
        $products = $productModel->getAll();
        $this->view('admin/products/index', ['products' => $products]);
    }

    public function createProduct() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                // Validate file type
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                        $imagePath = '/uploads/' . $fileName;
                    }
                }
            }

            $data = [
                'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'slug' => strtolower(str_replace(' ', '-', $_POST['name'])),
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'stock' => $_POST['stock'],
                'image' => $imagePath
            ];

            $productModel = new Product();
            if ($productModel->create($data)) {
                $this->redirect('/admin/products');
            }
        }

        $this->view('admin/products/create', ['categories' => $categories]);
    }

    public function editProduct() {
        if (!isset($_GET['id'])) {
            $this->redirect('/admin/products');
        }

        $productModel = new Product();
        $product = $productModel->findById($_GET['id']);

        if (!$product) {
            die("Product not found");
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $data = [
                'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'slug' => strtolower(str_replace(' ', '-', $_POST['name'])),
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'stock' => $_POST['stock']
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                // Validate file type
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                        $data['image'] = '/uploads/' . $fileName;
                    }
                }
            }

            if ($productModel->update($product['id'], $data)) {
                $this->redirect('/admin/products');
            }
        }

        $this->view('admin/products/edit', ['product' => $product, 'categories' => $categories]);
    }

    public function deleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $id = $_POST['id'];
            $productModel = new Product();
            $productModel->delete($id);
            $this->redirect('/admin/products');
        }
    }

    public function categories() {
        $categoryModel = new Category();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $name = $_POST['name'];
            $slug = strtolower(str_replace(' ', '-', $name));
            $categoryModel->create($name, $slug);
            $this->redirect('/admin/categories');
        }

        $categories = $categoryModel->getAll();
        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    public function orders() {
        $orderModel = new Order();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $id = $_POST['order_id'];
            $status = $_POST['status'];
            $orderModel->updateStatus($id, $status);
            $this->redirect('/admin/orders');
        }

        $orders = $orderModel->getAll();
        $this->view('admin/orders/index', ['orders' => $orders]);
    }
}

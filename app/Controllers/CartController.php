<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class CartController extends Controller {
    public function index() {
        $cartItems = [];
        $total = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $productModel = new Product();
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $productModel->findById($productId);
                if ($product) {
                    $product['quantity'] = $quantity;
                    $product['subtotal'] = $product['price'] * $quantity;
                    $total += $product['subtotal'];
                    $cartItems[] = $product;
                }
            }
        }

        $this->view('cart/index', ['cartItems' => $cartItems, 'total' => $total]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF check omitted for cart for better UX in this simple demo or could be added via JS/form
            // But let's add it for consistency
            $this->validateCsrfToken();
            $productId = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = $quantity;
            }

            $this->redirect('/cart');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $productId = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];

            if ($quantity > 0) {
                $_SESSION['cart'][$productId] = $quantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }

            $this->redirect('/cart');
        }
    }

    public function remove() {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $productId = $_POST['product_id'];
            unset($_SESSION['cart'][$productId]);
            $this->redirect('/cart');
         }
    }
}

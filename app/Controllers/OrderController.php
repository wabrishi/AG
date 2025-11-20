<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller {
    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        if (empty($_SESSION['cart'])) {
            $this->redirect('/cart');
        }

        $cartItems = [];
        $total = 0;
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

        $this->view('checkout/index', ['cartItems' => $cartItems, 'total' => $total]);
    }

    public function placeOrder() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
            $this->redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $shippingAddress = $_POST['address'];
            $paymentMethod = $_POST['payment_method'];

            // Recalculate total to be safe
            $total = 0;
            $productModel = new Product();
            $cartItems = [];
             foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $productModel->findById($productId);
                if ($product) {
                     $total += $product['price'] * $quantity;
                     $cartItems[] = ['product' => $product, 'qty' => $quantity];
                }
            }

            $orderModel = new Order();
            $orderId = $orderModel->create($_SESSION['user_id'], $total, $shippingAddress, $paymentMethod);

            foreach ($cartItems as $item) {
                $orderModel->addOrderItem($orderId, $item['product']['id'], $item['qty'], $item['product']['price']);
            }

            // Clear Cart
            unset($_SESSION['cart']);

            $this->redirect('/order/success?id=' . $orderId);
        }
    }

    public function success() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $this->redirect('/');
        }

        $orderId = $_GET['id'];
        $orderModel = new Order();
        $order = $orderModel->findById($orderId);

        // Security check: ensure order belongs to logged in user
        if ($order['user_id'] != $_SESSION['user_id']) {
             die("Unauthorized");
        }

        $items = $orderModel->getItems($orderId);

        $this->view('checkout/success', ['order' => $order, 'items' => $items]);
    }
}

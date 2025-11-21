<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\OrderController;

$router = new Router();

// Define Routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/product', [ProductController::class, 'show']);

// Cart Routes
$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add']);
$router->post('/cart/update', [CartController::class, 'update']);
$router->post('/cart/remove', [CartController::class, 'remove']);

// Order Routes
$router->get('/checkout', [OrderController::class, 'checkout']);
$router->post('/order/place', [OrderController::class, 'placeOrder']);
$router->get('/order/success', [OrderController::class, 'success']);

// Auth Routes
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Admin Routes
$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/products', [AdminController::class, 'products']);
$router->get('/admin/products/create', [AdminController::class, 'createProduct']);
$router->post('/admin/products/create', [AdminController::class, 'createProduct']);
$router->get('/admin/products/edit', [AdminController::class, 'editProduct']);
$router->post('/admin/products/edit', [AdminController::class, 'editProduct']);
$router->post('/admin/products/delete', [AdminController::class, 'deleteProduct']);
$router->get('/admin/categories', [AdminController::class, 'categories']);
$router->post('/admin/categories', [AdminController::class, 'categories']);
$router->get('/admin/orders', [AdminController::class, 'orders']);
$router->post('/admin/orders', [AdminController::class, 'orders']);

// Dispatch
$router->dispatch();

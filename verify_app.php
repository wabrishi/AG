<?php

// Simple verification script to simulate a user flow

$baseUrl = 'http://localhost:8080';
$cookieFile = tempnam(sys_get_temp_dir(), 'cookie');

function request($url, $method = 'GET', $data = [], $headers = []) {
    global $cookieFile;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code' => $httpCode, 'body' => $response];
}

// Helper to extract CSRF token
function getCsrfToken($html) {
    if (preg_match('/name="csrf_token" value="([a-f0-9]+)"/', $html, $matches)) {
        return $matches[1];
    }
    return null;
}

echo "Starting Verification...\n";

// 1. Register User
echo "[1] Registering User... ";
$email = 'test' . time() . '@example.com';
// Get token from register page
$res = request($baseUrl . '/register');
$token = getCsrfToken($res['body']);

$res = request($baseUrl . '/register', 'POST', [
    'name' => 'Test User',
    'email' => $email,
    'password' => 'password123',
    'address' => '123 Test St',
    'csrf_token' => $token
]);
if (strpos($res['body'], 'Login') !== false) {
    echo "OK\n";
} else {
    echo "FAILED (Response doesn't contain Login)\n";
}

// 2. Login
echo "[2] Logging in... ";
// Get token from login page
$res = request($baseUrl . '/login');
$token = getCsrfToken($res['body']);

$res = request($baseUrl . '/login', 'POST', [
    'email' => $email,
    'password' => 'password123',
    'csrf_token' => $token
]);
if (strpos($res['body'], 'Logout') !== false) {
    echo "OK\n";
} else {
    echo "FAILED (Response doesn't contain Logout)\n";
}

// Logout first
request($baseUrl . '/logout');

// 3. Login as Admin
echo "[3] Login as Admin... ";
$res = request($baseUrl . '/login');
$token = getCsrfToken($res['body']);

$res = request($baseUrl . '/login', 'POST', [
    'email' => 'admin@example.com',
    'password' => 'admin123',
    'csrf_token' => $token
]);

if (strpos($res['body'], 'Admin Dashboard') !== false) {
    echo "OK\n";
} else {
    echo "FAILED (Not Admin Dashboard)\n";
}

echo "[4] Creating Category... ";
// Get token from category page
$res = request($baseUrl . '/admin/categories');
$token = getCsrfToken($res['body']);

$res = request($baseUrl . '/admin/categories', 'POST', [
    'name' => 'Test Category',
    'csrf_token' => $token
]);
if ($res['code'] == 200) echo "OK\n"; else echo "FAILED " . $res['code'] . "\n";

// 4. Create Product
echo "[5] Creating Product... ";
// Get token from create product page
$res = request($baseUrl . '/admin/products/create');
$token = getCsrfToken($res['body']);

// Assuming ID 1 exists
$res = request($baseUrl . '/admin/products/create', 'POST', [
    'category_id' => 1,
    'name' => 'Unique Product Name',
    'description' => 'Test Description',
    'price' => 99.99,
    'stock' => 10,
    'csrf_token' => $token
]);
if ($res['code'] == 200) echo "OK\n"; else echo "FAILED\n";


// 5. Search Product (Public)
echo "[6] Searching Product... ";
$res = request($baseUrl . '/products?search=Unique');
if (strpos($res['body'], 'Unique Product Name') !== false) {
    echo "OK\n";
} else {
    echo "FAILED\n";
}

// Logout Admin
request($baseUrl . '/logout');

// Login as Customer
request($baseUrl . '/login', 'POST', [
    'email' => $email,
    'password' => 'password123',
    'csrf_token' => $token // Token reuse might fail if session regenerated, but usually okay here
]);
// Refetch token just in case
$res = request($baseUrl . '/login');
$token = getCsrfToken($res['body']);
request($baseUrl . '/login', 'POST', [
    'email' => $email,
    'password' => 'password123',
    'csrf_token' => $token
]);


// 6. Add to Cart
echo "[7] Adding to Cart... ";
// Need to find the product ID. We know we created one. Let's guess 1.
// We need a token from a page.
$res = request($baseUrl . '/products');
$token = getCsrfToken($res['body']); // Should find one in the logout form or similar if present, but strictly we need one for the add cart form.
// Actually the product page has the form.
$res = request($baseUrl . '/product?id=1');
$token = getCsrfToken($res['body']);

$res = request($baseUrl . '/cart/add', 'POST', [
    'product_id' => 1,
    'quantity' => 1,
    'csrf_token' => $token
]);
// Check if cart page loads
$res = request($baseUrl . '/cart');
if (strpos($res['body'], 'Unique Product Name') !== false) {
    echo "OK\n";
} else {
    echo "FAILED (Cart empty or product not found)\n";
}

// 7. Checkout
echo "[8] Placing Order... ";
$token = getCsrfToken($res['body']); // From cart page (remove button form) or checkout page
$res = request($baseUrl . '/checkout');
$token = getCsrfToken($res['body']);

$res = request($baseUrl . '/order/place', 'POST', [
    'address' => 'Shipping Address 123',
    'payment_method' => 'cod',
    'csrf_token' => $token
]);

if (strpos($res['body'], 'Order Placed Successfully') !== false) {
    echo "OK\n";
} else {
    echo "FAILED\n";
}

echo "Verification Complete.\n";
unlink($cookieFile);

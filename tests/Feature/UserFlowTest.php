<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class UserFlowTest extends TestCase {
    private $client;
    private $cookieJar;
    private $baseUrl = 'http://localhost:8080';

    protected function setUp(): void {
        $this->cookieJar = new CookieJar();
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'cookies' => $this->cookieJar,
            'http_errors' => false, // Don't throw exceptions on 4xx/5xx
            'allow_redirects' => true
        ]);
    }

    private function getCsrfToken($html) {
        if (preg_match('/name="csrf_token" value="([a-f0-9]+)"/', $html, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function testUserRegistration() {
        $email = 'test' . time() . '@example.com';

        // Get CSRF Token
        $response = $this->client->get('/register');
        $token = $this->getCsrfToken((string)$response->getBody());
        $this->assertNotNull($token, 'CSRF token not found on register page');

        // Register
        $response = $this->client->post('/register', [
            'form_params' => [
                'name' => 'PHPUnit User',
                'email' => $email,
                'password' => 'password123',
                'address' => 'Test Address',
                'csrf_token' => $token
            ]
        ]);

        $body = (string)$response->getBody();
        // Should redirect to login or show login page content
        $this->assertStringContainsString('Login', $body);

        return $email;
    }

    /**
     * @depends testUserRegistration
     */
    public function testUserLogin($email) {
        // Get CSRF Token
        $response = $this->client->get('/login');
        $token = $this->getCsrfToken((string)$response->getBody());

        // Login
        $response = $this->client->post('/login', [
            'form_params' => [
                'email' => $email,
                'password' => 'password123',
                'csrf_token' => $token
            ]
        ]);

        $body = (string)$response->getBody();
        $this->assertStringContainsString('Logout', $body);

        return $email;
    }

    public function testAdminFlow() {
        // Login as Admin
        $response = $this->client->get('/login');
        $token = $this->getCsrfToken((string)$response->getBody());

        $response = $this->client->post('/login', [
            'form_params' => [
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'csrf_token' => $token
            ]
        ]);

        $this->assertStringContainsString('Admin Dashboard', (string)$response->getBody());

        // Create Category
        $response = $this->client->get('/admin/categories');
        $token = $this->getCsrfToken((string)$response->getBody());

        $categoryName = 'Test Category ' . time();
        $response = $this->client->post('/admin/categories', [
            'form_params' => [
                'name' => $categoryName,
                'csrf_token' => $token
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        // Create Product
        $response = $this->client->get('/admin/products/create');
        $token = $this->getCsrfToken((string)$response->getBody());

        $productName = 'Test Product ' . time();
        $response = $this->client->post('/admin/products/create', [
            'form_params' => [
                'category_id' => 1, // Assuming 1 exists
                'name' => $productName,
                'description' => 'Desc',
                'price' => 50.00,
                'stock' => 100,
                'csrf_token' => $token
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        return $productName;
    }

    /**
     * @depends testAdminFlow
     */
    public function testProductSearch($productName) {
         $response = $this->client->get('/products', [
             'query' => ['search' => $productName]
         ]);
         $this->assertStringContainsString($productName, (string)$response->getBody());
    }
}

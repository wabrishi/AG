<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];

                if ($user['role'] === 'admin') {
                    $this->redirect('/admin');
                } else {
                    $this->redirect('/');
                }
            } else {
                $this->view('auth/login', ['error' => 'Invalid credentials']);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'address' => $_POST['address']
            ];

            $userModel = new User();
            // Check if email exists
            if ($userModel->findByEmail($data['email'])) {
                 $this->view('auth/register', ['error' => 'Email already registered']);
                 return;
            }

            if ($userModel->create($data)) {
                $this->redirect('/login');
            } else {
                $this->view('auth/register', ['error' => 'Registration failed']);
            }
        } else {
            $this->view('auth/register');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
}

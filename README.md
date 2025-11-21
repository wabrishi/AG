# E-Commerce PHP Application

This is a complete E-Commerce application built with vanilla PHP using a custom MVC architecture.

## Requirements
- PHP 8.0+
- MySQL or MariaDB (or SQLite for development)
- PDO Extension

## Installation

1.  **Clone the repository**
2.  **Install Dependencies**
    *   Run `composer install` to install PHPUnit and other dependencies.
3.  **Database Setup**
    *   **For MySQL:** Import `database.sql` into your MySQL database. Update `app/Config/Database.php` with your credentials.
    *   **For Development (SQLite):** Run `php setup_database.php`. This will create a `database.sqlite` file.
3.  **Serve the application**
    *   You can use the built-in PHP server:
        ```bash
        php -S localhost:8080 -t public public/index.php
        ```
    *   Or configure Apache/Nginx to point to the `public` directory.

## Features
- **User Module:** Registration, Login (Session-based).
- **Product Module:** Product Listing, Details, Search/Filter by Category.
- **Shopping Cart:** Add, Update, Remove items.
- **Checkout:** Address entry, Order placement, Invoice generation (Print view).
- **Admin Panel:** Manage Products (CRUD), Manage Categories.

## Credentials
- **Admin:** `admin@example.com` / `admin123` (Created by `setup_database.php`)

## Directory Structure
- `app/`: Core application code (Controllers, Models, Views).
- `public/`: Public entry point (`index.php`) and assets.
- `tests/`: PHPUnit tests.
- `vendor/`: Composer dependencies.
- `database.sql`: Database schema.

## Testing

To run the automated test suite:

1.  Start the development server:
    ```bash
    php -S localhost:8080 -t public public/index.php > server.log 2>&1 &
    ```
2.  Run PHPUnit:
    ```bash
    ./vendor/bin/phpunit
    ```

## Hostinger Deployment Guide

1.  **Create Database**
    *   Log in to Hostinger hPanel.
    *   Go to **Databases** -> **Management**.
    *   Create a new MySQL Database. Note down the **Database Name**, **Database User**, and **Password**.
    *   Open **phpMyAdmin** for the new database.
    *   Import the `database.sql` file provided in this project.

2.  **Configure Application**
    *   Open `app/Config/Database.php`.
    *   Change `$dbDriver = 'sqlite';` to `$dbDriver = 'mysql';`.
    *   Update `$mysqlConfig` with your Hostinger database details:
        ```php
        $mysqlConfig = [
            'host' => 'localhost',
            'dbname' => 'u123456789_your_db_name',
            'user' => 'u123456789_your_db_user',
            'password' => 'your_password'
        ];
        ```

3.  **Upload Files**
    *   Use the **File Manager** or FTP.
    *   Navigate to `public_html`.
    *   **Recommended Structure:**
        *   Create a folder named `private_app` *outside* `public_html` (e.g., `/home/u123456/domains/domain.com/private_app`) and upload the contents of the `app` folder there.
        *   Upload the contents of the `public` folder (index.php, assets, .htaccess) directly into `public_html`.
        *   Edit `public_html/index.php` and update the require paths:
            ```php
            // Change from:
            require_once __DIR__ . '/../app/Core/Router.php';
            // To (example):
            require_once __DIR__ . '/../private_app/Core/Router.php';
            ```
    *   **Simple Structure (Alternative):**
        *   Upload the entire project folder into `public_html`.
        *   Your site will be at `yourdomain.com/public`.
        *   To fix this, move contents of `public` to `public_html` and keep `app` in `public_html` as well. This is less secure but easier.

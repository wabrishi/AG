# E-Commerce PHP Application

This is a complete E-Commerce application built with vanilla PHP using a custom MVC architecture.

## Requirements
- PHP 8.0+
- MySQL or MariaDB (or SQLite for development)
- PDO Extension

## Installation

1.  **Clone the repository**
2.  **Database Setup**
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
- `database.sql`: Database schema.

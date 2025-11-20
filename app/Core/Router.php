<?php

namespace App\Core;

class Router {
    protected $routes = [];

    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove trailing slash unless it's root
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        // Simple matching
        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];

            if (is_array($handler)) {
                $controllerName = $handler[0];
                $actionName = $handler[1];
                $controller = new $controllerName();
                return $controller->$actionName();
            }
        } else {
            // Handle 404
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}

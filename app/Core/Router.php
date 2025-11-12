<?php

namespace App\Core;

/**
 * Class Router
 * 
 * Router untuk menangani request GET dan POST
 * 
 * Cara pakai:
 * $router->get('login', function() { ... });
 * $router->post('login', 'AuthController', 'handleLogin');
 */

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Daftar route GET
     * 
     * @param string $path - URL path (misal: 'login', 'dashboard')
     * @param mixed $controller - Bisa function atau nama controller
     * @param string|array $method - Nama method atau array middleware (jika controller adalah function)
     * @param array $middleware - Array middleware class
     */

    public function get($path, $controller, $method = null, $middleware = [])
    {

        // Jika controller adalah function
        if (is_callable($controller)) {
            $this->routes['GET'][$path] = [
                'callback' => $controller,
                'middleware' => $method ?? [], // method akan jadi middlwware
            ];
        } else {

            // Controller adalah class name
            $this->routes['GET'][$path] = [
                'controller' => $controller,
                'method' => $method,
                'middleware' => $middleware,
            ];
        }
    }

    public function post($path, $controller, $method = null, $middleware = [])
    {
        // Jika controller adalah function
        if (is_callable($controller)) {
            $this->routes['POST'][$path] = [
                'callback' => $controller,
                'middleware' => $method ?? [], // method akan jadi middlwware
            ];
        } else {

            // Controller adalah class name
            $this->routes['POST'][$path] = [
                'controller' => $controller,
                'method' => $method,
                'middleware' => $middleware,
            ];
        }
    }

    /**
     * Jalankan router
     * 
     * Proses:
     * 1. Ambil URL dari $_GET['url']
     * 2. Cari route yang cocok
     * 3. Jalankan middleware
     * 4. Jalankan controller/callback
     */
    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $url = $_GET['url'] ?? '/';
        $routes = $this->routes[$requestMethod] ?? [];

        $found = false;

        foreach ($routes as $path => $route) {
            // Ubah path jadi regex - "misalnya "dosen/edit/(\d+)
            $pattern = "#^" . $path . "$#";

            if (preg_match($pattern, $url, $matches)) {
                $found = true;
                array_shift($matches); // Untuk menghapus full match

                // Jalankan middleware (jika ada)
                $middleware = $route['middleware'] ?? [];

                foreach ($middleware as $ms) {
                    $middlewareInstance = new $ms();
                    $middlewareInstance->handle();
                }

                // Jalankan controller atau callback
                if (isset($route['callback'])) {

                    // Jalankan callback function
                    call_user_func_array($route['callback'], $matches);
                } else {
                    // Jalankan controller method
                    $controllerInstance = new $route['controller']();
                    $method = $route['method'];
                    call_user_func_array([$controllerInstance, $method], $matches);
                }
                return;
            }
        }

        if (!$found) {
            echo "404 Not Found";
        }
    }
}
